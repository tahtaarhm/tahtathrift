<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });


        return view('cart', compact('cart', 'total'));
    }

    // Menambahkan produk ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->status !== 'in_stock') {
            return back()->with('error', 'Produk sudah tidak tersedia.');
        }

        $cart = session()->get('cart', []);

        $id = $product->id;

        if (isset($cart[$id])) {
            return back()->with('error', 'Produk ini sudah ada di keranjang.');
        }

        $cart[$id] = [
            'id' => $product->id,
            'name' => $product->name,
            'brand' => $product->brand,
            'price' => $product->price,
            'image' => $product->image_url,
            'size' => $product->size,
            'quantity' => 1,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Menghapus produk dari keranjang
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        unset($cart[$id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
