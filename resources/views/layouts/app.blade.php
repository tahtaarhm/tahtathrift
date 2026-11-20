<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'T-THRIFT - Customer Storefront')</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Phosphor Icons CDN -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='420' height='68' viewBox='0 0 420 68'%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Inter, sans-serif' font-weight='900' font-size='48' fill='%23000000' fill-opacity='0.045' letter-spacing='2'%3ETAHTA THRIFT%3C/text%3E%3C/svg%3E");
      background-repeat: repeat;
      background-attachment: fixed;
      font-family: 'Inter', sans-serif;
    }
    .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #c5c5c5; border-radius: 9999px; }
  </style>

  @stack('styles')
</head>
<body class="text-zinc-900 min-h-screen flex flex-col relative antialiased selection:bg-zinc-800 selection:text-white">

  <!-- TOAST NOTIFICATION -->
  <div id="toast" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 pointer-events-none transition-all duration-300 bg-zinc-900 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-zinc-700">
    <i class="ph-fill ph-check-circle text-xl text-emerald-400"></i>
    <span id="toast-text" class="text-xs sm:text-sm font-semibold">Pemberitahuan</span>
  </div>

  <!-- STORE NAVIGATION HEADER -->
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-zinc-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

      <div class="flex items-center space-x-6 sm:space-x-10">
        <a href="{{ url('/') }}" class="text-2xl sm:text-3xl font-black tracking-tight text-zinc-900 hover:opacity-80 transition">
          T-THRIFT
        </a>
        <nav class="flex items-center space-x-5 text-sm sm:text-base font-bold">
          <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'text-zinc-900 underline underline-offset-4' : 'text-zinc-600 hover:text-black' }} transition">
            Home
          </a>
          <a href="{{ url('/#featured') }}" class="text-zinc-600 hover:text-black transition">
            Product
          </a>
          <a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'text-zinc-900 underline underline-offset-4' : 'text-zinc-600 hover:text-black' }} transition">
            Cart
          </a>
        </nav>
      </div>

      <!-- Search Bar -->
      <div class="flex-1 max-w-lg hidden sm:block">
        <form action="{{ url('/') }}" method="GET" class="relative">
          <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 text-lg"></i>
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search Product Here"
            class="w-full bg-[#E5E7EB] hover:bg-[#dedede] focus:bg-white text-zinc-800 placeholder-zinc-500 rounded-full py-2.5 pl-11 pr-5 text-sm outline-none transition"
          >
        </form>
      </div>

      <!-- Cart, Profile, & Admin Link -->
      <div class="flex items-center space-x-3">
        @auth
            <form method="POST" action="{{ route('logout') }}">
            @csrf
                <a href="{{route('logout')}}"onclick="event.preventDefault();
                this.closest('form').submit();" class="w-full bg-[#dcdde1] hover:bg-zinc-300 text-zinc-800 font-bold py-3.5 px-4 rounded-2xl text-sm transition text-center shadow-sm">
                    Log Out
                </a>
            </form>
            
          @if(auth()->user()->role === 'admin')
            <a href="{{ url('/admin') }}" class="hidden md:flex items-center gap-1.5 px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 text-xs font-bold rounded-xl border border-zinc-300 transition">
              <i class="ph ph-chart-pie-slice text-sm"></i>
              <span>Admin Portal</span>
            </a>
          @endif
        @endauth

        @php
          $cartCount = is_array(session('cart')) ? collect(session('cart'))->sum('quantity') : 0;
        @endphp

        <a href="{{ route('cart.index') }}" class="relative p-2.5 text-zinc-800 hover:bg-zinc-100 rounded-full transition" title="Shopping Cart">
          <i class="ph ph-shopping-cart-simple text-2xl"></i>
          <span id="storeCartBadge" class="absolute -top-1 -right-1 bg-zinc-900 text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
            {{ $cartCount }}
          </span>
        </a>

      </div>
    </div>
  </header>

  <!-- MAIN CONTAINER -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
    @if(session('success'))
      <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl mb-6 text-sm font-semibold">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-2xl mb-6 text-sm font-semibold">
        {{ session('error') }}
      </div>
    @endif

    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="mt-auto border-t border-zinc-200 bg-white/95 py-6 text-center">
    <p class="font-black tracking-widest text-base">T-THRIFT</p>
    <p class="text-xs text-zinc-500 font-medium">TahtaThrift © 2026. Customer Storefront.</p>
  </footer>

  <!-- PRODUCT DETAIL MODAL (Dipanggil dari index) -->
  <div id="modal-product-detail" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 hidden">
    <div class="relative bg-white rounded-[32px] w-full max-w-2xl p-6 sm:p-8 shadow-2xl">
      <button onclick="closeProductDetailModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-zinc-100 hover:bg-zinc-200 flex items-center justify-center text-zinc-600 font-bold">×</button>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
        <div class="relative aspect-[4/5] bg-zinc-200 rounded-2xl overflow-hidden">
          <img id="detailImg" src="" alt="Product" class="w-full h-full object-cover">
          <span id="detailCondBadge" class="absolute top-3 left-3 bg-zinc-900/85 text-white text-xs font-extrabold px-3 py-1 rounded-full"></span>
        </div>
        <div class="space-y-4 flex flex-col justify-between">
          <div>
            <span id="detailBrand" class="text-xs uppercase font-black text-zinc-400"></span>
            <h3 id="detailName" class="text-2xl font-black text-zinc-900 mt-0.5"></h3>
            <p id="detailPrice" class="text-2xl font-black text-zinc-900 mt-2">Rp 0</p>
          </div>
          <div class="bg-zinc-50 rounded-2xl p-4 text-xs sm:text-sm space-y-1.5">
            <div class="flex justify-between"><span class="text-zinc-500">Size</span><span id="detailSize" class="font-bold"></span></div>
            <div class="flex justify-between"><span class="text-zinc-500">Dimensi</span><span id="detailDimension" class="font-bold"></span></div>
            <div class="flex justify-between"><span class="text-zinc-500">Bahan</span><span id="detailMaterial" class="font-bold"></span></div>
            <div class="flex justify-between"><span class="text-zinc-500">Minus</span><span id="detailDefect" class="font-bold text-emerald-600"></span></div>
          </div>
          <p id="detailDescription" class="text-xs text-zinc-600"></p>

          @if (Route::has('cart.add'))
            <form action="{{ route('cart.add') }}" method="POST">
              @csrf
              <input type="hidden" name="product_id" id="modalProductId">
              <button type="submit" class="w-full bg-zinc-900 hover:bg-black text-white font-bold py-3 px-4 rounded-2xl text-sm transition">
                Add to Cart
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>

  <script>
    function formatRupiah(amount) {
      return "Rp " + Number(amount || 0).toLocaleString("id-ID");
    }

    function openProductDetailModal(el) {
      const d = el.dataset;
      const modalIdInput = document.getElementById('modalProductId');
      if (modalIdInput) modalIdInput.value = d.id || '';

      document.getElementById('detailImg').src = d.image || '';
      document.getElementById('detailCondBadge').innerText = `Condition : ${d.condition || '-'}`;
      document.getElementById('detailBrand').innerText = d.brand || '';
      document.getElementById('detailName').innerText = d.name || '';
      document.getElementById('detailPrice').innerText = formatRupiah(d.price);
      document.getElementById('detailSize').innerText = d.size || '-';
      document.getElementById('detailDimension').innerText = d.dimension || '-';
      document.getElementById('detailMaterial').innerText = d.material || '-';
      document.getElementById('detailDefect').innerText = d.defect || 'No Minus';
      document.getElementById('detailDescription').innerText = d.description || '';

      document.getElementById('modal-product-detail').classList.remove('hidden');
    }

    function closeProductDetailModal() {
      document.getElementById('modal-product-detail').classList.add('hidden');
    }

    function showNotification(msg) {
      const t = document.getElementById('toast');
      document.getElementById('toast-text').innerText = msg;
      t.classList.remove('opacity-0', 'translate-y-24', 'pointer-events-none');
      setTimeout(() => t.classList.add('opacity-0', 'translate-y-24', 'pointer-events-none'), 3000);
    }
  </script>

  @stack('scripts')
</body>
</html>