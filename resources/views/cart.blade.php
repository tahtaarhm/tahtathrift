@extends('layouts.app')

@section('title', 'Shopping Cart - T-THRIFT')

@section('content')
<div class="max-w-5xl mx-auto py-2">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-zinc-900">Shopping Cart</h1>
            <p class="text-sm text-zinc-500 mt-1">Review your selected products</p>
        </div>

        <a href="{{ url('/') }}"
           class="text-sm font-bold text-zinc-700 hover:text-black">
            Continue Shopping
        </a>
    </div>

    @if(count($cart) > 0)
    <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-7 border border-zinc-200">
        <div class="space-y-5">
            @foreach($cart as $item)
                <div class="flex flex-col sm:flex-row gap-5 border-b border-zinc-100 pb-5">
                    <img
                        src="{{ !empty($item['image']) ? asset($item['image']) : 'https://placehold.co/200x250?text=No+Image' }}"
                        alt="{{ $item['name'] }}"
                        class="w-full sm:w-28 h-36 object-cover rounded-xl bg-zinc-100"
                    >

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase text-zinc-400">
                                {{ $item['brand'] ?? '' }}
                            </p>

                            <h3 class="text-lg font-black text-zinc-900">
                                {{ $item['name'] }}
                            </h3>

                            <p class="text-sm text-zinc-500 mt-1">
                                Size: {{ $item['size'] ?? '-' }}
                            </p>

                            <p class="text-sm text-zinc-500">
                                Quantity: {{ $item['quantity'] }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <p class="font-black text-zinc-900">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>

                            <form action="{{ route('cart.remove', $item['id']) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus produk dari keranjang?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-sm font-bold text-red-500 hover:text-red-700">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border-t border-zinc-200 mt-6 pt-6">
            <div class="flex justify-between items-center">
                <span class="font-bold text-zinc-600">Total</span>

                <span class="text-2xl font-black text-zinc-900">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <button type="button" id="payButton"
                class="w-full mt-6 bg-zinc-900 hover:bg-black text-white font-bold py-4 rounded-xl transition disabled:opacity-50">
                Proceed to Checkout
            </button>
        </div>
    </div>
@else
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-zinc-200">
        <div class="text-5xl mb-4">🛒</div>

        <h2 class="text-xl font-black text-zinc-900">
            Your Cart is Empty
        </h2>

        <p class="text-sm text-zinc-500 mt-2">
            You haven't added any products to your cart yet.
        </p>

        <a href="{{ url('/') }}"
           class="inline-block mt-6 bg-zinc-900 text-white font-bold px-6 py-3 rounded-xl hover:bg-black transition">
            Start Shopping
        </a>
    </div>
@endif

</div>
@push('scripts')
<script
  src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
  data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const payButton = document.getElementById('payButton');
    if (!payButton) return;

    payButton.addEventListener('click', async () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const originalText = payButton.textContent;

        payButton.disabled = true;
        payButton.textContent = 'Memproses Pembayaran...';

        try {
            const response = await fetch("{{ route('checkout.process') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (response.status === 401) {
                window.location.href = "{{ route('login') }}";
                return;
            }

            const data = await response.json();

            if (!response.ok || !data.snap_token) {
                alert(data.message || 'Gagal membuat transaksi Midtrans.');
                payButton.disabled = false;
                payButton.textContent = originalText;
                return;
            }

            window.snap.pay(data.snap_token, {
                onSuccess: async function () {
                    await fetch("{{ route('checkout.paid') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ order_number: data.order_number }),
                    });
                    window.location.href = "{{ route('cart.index') }}";
                },
                onPending: function () {
                    alert('Menunggu pembayaran diselesaikan. Cek status pesanan kamu.');
                    window.location.reload();
                },
                onError: function () {
                    alert('Pembayaran gagal atau dibatalkan.');
                    payButton.disabled = false;
                    payButton.textContent = originalText;
                },
                onClose: function () {
                    payButton.disabled = false;
                    payButton.textContent = originalText;
                }
            });
        } catch (err) {
            alert('Terjadi kesalahan jaringan.');
            payButton.disabled = false;
            payButton.textContent = originalText;
        }
    });
});
</script>
@endpush
@endsection