<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('services.midtrans.is_3ds', true);
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'message' => 'Silakan login terlebih dahulu sebelum checkout.',
            ], 401);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json([
                'message' => 'Keranjang belanja masih kosong.',
            ], 422);
        }

        try {
            $result = DB::transaction(function () use ($cart, $user) {
                $subtotal = 0;
                $itemsData = [];
                $midtransItems = [];

                foreach ($cart as $item) {
                    $product = Product::lockForUpdate()->find($item['id']);

                    if (! $product || $product->status !== 'in_stock') {
                        throw new \RuntimeException('Produk "' . ($item['name'] ?? 'Item') . '" sudah tidak tersedia.');
                    }

                    $qty = max(1, (int) ($item['quantity'] ?? 1));
                    $price = (int) round((float) $product->price);
                    $lineTotal = $price * $qty;
                    $subtotal += $lineTotal;

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $price,
                    ];

                    $midtransItems[] = [
                        'id' => (string) $product->id,
                        'price' => $price,
                        'quantity' => $qty,
                        'name' => Str::limit($product->name, 50, ''),
                    ];
                }

                $discount = 0;
                $totalAmount = max(0, $subtotal - $discount);
                $orderNumber = 'THRIFT-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total_amount' => $totalAmount,
                    'payment_status' => 'unpaid',
                    'order_status' => 'pending',
                ]);

                foreach ($itemsData as $row) {
                    OrderItems::create([
                        'order_id' => $order->id,
                        'product_id' => $row['product_id'],
                        'quantity' => $row['quantity'],
                        'price' => $row['price'],
                    ]);
                }

                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_number,
                        'gross_amount' => (int) round($totalAmount),
                    ],
                    'item_details' => $midtransItems,
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->no_hp ?? '',
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);

                $order->update([
                    'snap_token' => $snapToken,
                ]);

                return [
                    'order' => $order,
                    'snap_token' => $snapToken,
                ];
            });

            return response()->json([
                'snap_token' => $result['snap_token'],
                'order_number' => $result['order']->order_number,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function markPaidFromClient(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'processing',
            'paid_at' => $order->paid_at ?? now(),
        ]);

        session()->forget('cart');

        return response()->json(['ok' => true]);
    }

    public function callback(Request $request)
    {
        try {
            $notification = new Notification();

            $orderNumber = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $paymentType = $notification->payment_type ?? null;
            $transactionId = $notification->transaction_id ?? null;

            $order = Order::with('items')->where('order_number', $orderNumber)->first();
            if (! $order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            DB::transaction(function () use ($order, $transactionStatus, $fraudStatus, $paymentType, $transactionId) {
                $updates = [
                    'midtrans_transaction_id' => $transactionId,
                    'payment_method' => $paymentType ?? $order->payment_method,
                ];

                if ($transactionStatus === 'capture') {
                    if ($fraudStatus === 'accept') {
                        $updates['payment_status'] = 'paid';
                        $updates['order_status'] = 'processing';
                        $updates['paid_at'] = $order->paid_at ?? now();
                        $this->markProductsSold($order);
                    }
                } elseif ($transactionStatus === 'settlement') {
                    $updates['payment_status'] = 'paid';
                    $updates['order_status'] = 'processing';
                    $updates['paid_at'] = $order->paid_at ?? now();
                    $this->markProductsSold($order);
                } elseif ($transactionStatus === 'pending') {
                    $updates['payment_status'] = 'unpaid';
                    $updates['order_status'] = 'pending';
                } elseif (in_array($transactionStatus, ['deny', 'cancel'], true)) {
                    $updates['payment_status'] = 'failed';
                    $updates['order_status'] = 'cancelled';
                } elseif ($transactionStatus === 'expire') {
                    $updates['payment_status'] = 'expired';
                    $updates['order_status'] = 'cancelled';
                }

                $order->update($updates);
            });

            return response()->json(['message' => 'Notification handled']);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['message' => 'Callback error'], 500);
        }
    }

    private function markProductsSold(Order $order): void
    {
        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)->update([
                'status' => 'sold',
            ]);
        }
    }
}