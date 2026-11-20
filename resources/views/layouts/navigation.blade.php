<!-- STORE NAVIGATION HEADER -->
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-zinc-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
      
      <div class="flex items-center space-x-6 sm:space-x-10">
        <button onclick="navigateStore('home')" class="text-2xl sm:text-3xl font-black tracking-tight text-zinc-900 hover:opacity-80 transition">
          T-THRIFT
        </button>
        <nav class="flex items-center space-x-5 text-sm sm:text-base font-bold">
          <button onclick="navigateStore('shop')" id="nav-shop" class="text-zinc-600 hover:text-black transition">Product</button>
          <button onclick="navigateStore('orders')" id="nav-orders" class="text-zinc-600 hover:text-black transition">Orders</button>
        </nav>
      </div>

      <!-- Search Bar -->
      <div class="flex-1 max-w-lg hidden sm:block">
        <div class="relative">
          <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 text-lg"></i>
          <input 
            type="text" 
            placeholder="Search Product Here" 
            oninput="handleStoreSearch(this.value)"
            class="w-full bg-[#E5E7EB] hover:bg-[#dedede] focus:bg-white text-zinc-800 placeholder-zinc-500 rounded-full py-2.5 pl-11 pr-5 text-sm outline-none transition"
          >
        </div>
      </div>

      <!-- Cart, Profile, & Admin Link -->
      <div class="flex items-center space-x-3">
        <a href="{{url('/admin')}}" class="hidden md:flex items-center gap-1.5 px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 text-xs font-bold rounded-xl border border-zinc-300 transition">
          <i class="ph ph-chart-pie-slice text-sm"></i>
          <span>Admin Portal</span>
        </a>

        <button onclick="navigateStore('cart')" class="relative p-2.5 text-zinc-800 hover:bg-zinc-100 rounded-full transition" title="Shopping Cart">
          <i class="ph ph-shopping-cart-simple text-2xl"></i>
          <span id="storeCartBadge" class="absolute -top-1 -right-1 bg-zinc-900 text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center">3</span>
        </button>

        <button onclick="openProfileOrLogin()" class="flex items-center space-x-2 p-1.5 sm:px-3 sm:py-2 text-zinc-800 rounded-full hover:bg-zinc-100 transition">
          <i class="ph ph-user text-2xl"></i>
          <span id="userProfileLabel" class="hidden md:inline text-sm font-semibold">Account</span>
        </button>
      </div>
    </div>
  </header>