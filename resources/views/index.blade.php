<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TahtaThrift : E-commerce System</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Phosphor Icons CDN -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <!-- Chart.js CDN for Admin Sales Overview -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    /* Full-screen repeating tight watermark TahtaThrift */
    body {
      background-color: #f8f9fa;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='420' height='68' viewBox='0 0 420 68'%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Inter, sans-serif' font-weight='900' font-size='48' fill='%23000000' fill-opacity='0.045' letter-spacing='2'%3ETAHTA THRIFT%3C/text%3E%3C/svg%3E");
      background-repeat: repeat;
      background-attachment: fixed;
      background-position: center top;
      font-family: 'Inter', sans-serif;
    }
    .custom-scroll::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background: #c5c5c5;
      border-radius: 9999px;
    }
    .admin-nav-item.active {
      font-weight: 800;
      color: #09090b;
      background-color: rgba(0, 0, 0, 0.08);
    }
  </style>
</head>
<body class="text-zinc-900 min-h-screen flex flex-col relative antialiased selection:bg-zinc-800 selection:text-white">

  <!-- ================= TOP WORKSPACE MODE TOGGLER ================= -->
  <div class="sticky top-0 z-50 bg-zinc-900 text-white text-xs px-4 py-2 flex items-center justify-between border-b border-zinc-800 shadow-md">
    <div class="flex items-center gap-2">
      <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
      <span class="font-bold tracking-wide uppercase text-[11px] text-zinc-300">TahtaThrift System Mode:</span>
    </div>

    <!-- Mode Selector Pills -->
    <div class="inline-flex bg-zinc-800 p-1 rounded-xl border border-zinc-700">
      <button 
        id="modeBtnStore" 
        onclick="switchWorkspaceMode('store')" 
        class="px-4 py-1.5 rounded-lg font-bold text-xs flex items-center gap-2 bg-white text-zinc-900 shadow transition"
      >
        <i class="ph ph-shopping-bag-open text-sm"></i>
        <span>Customer Store</span>
      </button>

      <button 
        id="modeBtnAdmin" 
        onclick="switchWorkspaceMode('admin')" 
        class="px-4 py-1.5 rounded-lg font-bold text-xs flex items-center gap-2 text-zinc-400 hover:text-white transition"
      >
        <i class="ph ph-chart-pie-slice text-sm"></i>
        <span>Admin Dashboard</span>
      </button>
    </div>

    <div class="hidden sm:flex items-center gap-3 text-zinc-400 text-xs font-semibold">
      <span id="quickStatusLabel">Toko Aktif (20 Pcs)</span>
    </div>
  </div>

  <!-- ================= NOTIFICATION TOAST ================= -->
  <div id="toast" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 pointer-events-none transition-all duration-300 bg-zinc-900 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-zinc-700">
    <i id="toast-icon" class="ph-fill ph-check-circle text-xl text-emerald-400"></i>
    <span id="toast-text" class="text-xs sm:text-sm font-semibold">Pemberitahuan</span>
  </div>

  <!-- ================================================================= -->
  <!--                      1. CUSTOMER STORE VIEW                       -->
  <!-- ================================================================= -->
  <div id="container-store" class="flex-1 flex flex-col">

    <!-- Store Navigation Header -->
    <header class="sticky top-11 z-40 bg-white/95 backdrop-blur-md border-b border-zinc-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
        
        <!-- Brand & Nav -->
        <div class="flex items-center space-x-6 sm:space-x-10">
          <button onclick="navigateStore('home')" class="text-2xl sm:text-3xl font-black tracking-tight text-zinc-900 hover:opacity-80 transition">
            T-THRIFT
          </button>
          <nav class="flex items-center space-x-5 text-sm sm:text-base font-bold">
            <button onclick="navigateStore('shop')" id="nav-shop" class="text-zinc-600 hover:text-black transition">
              Product
            </button>
            <button onclick="navigateStore('orders')" id="nav-orders" class="text-zinc-600 hover:text-black transition">
              Orders
            </button>
          </nav>
        </div>

        <!-- Search Input Bar (Wireframe specification) -->
        <div class="flex-1 max-w-lg hidden sm:block">
          <div class="relative">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 text-lg"></i>
            <input 
              type="text" 
              id="storeSearchInput" 
              placeholder="Search Product Here" 
              oninput="handleStoreSearch(this.value)"
              class="w-full bg-[#E5E7EB] hover:bg-[#dedede] focus:bg-white text-zinc-800 placeholder-zinc-500 rounded-full py-2.5 pl-11 pr-5 text-sm outline-none ring-2 ring-transparent focus:ring-zinc-800 transition"
            >
          </div>
        </div>

        <!-- Cart & Profile Account -->
        <div class="flex items-center space-x-4">
          <!-- Cart button -->
          <button onclick="navigateStore('cart')" class="relative p-2.5 text-zinc-800 hover:bg-zinc-100 rounded-full transition" title="Shopping Cart">
            <i class="ph ph-shopping-cart-simple text-2xl"></i>
            <span id="storeCartBadge" class="absolute -top-1 -right-1 bg-zinc-900 text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center">3</span>
          </button>

          <!-- User Profile -->
          <button onclick="openProfileOrLogin()" class="flex items-center space-x-2 p-1.5 sm:px-3 sm:py-2 text-zinc-800 rounded-full hover:bg-zinc-100 transition">
            <i class="ph ph-user text-2xl"></i>
            <span id="userProfileLabel" class="hidden md:inline text-sm font-semibold">Account</span>
          </button>
        </div>
      </div>

      <!-- Mobile Search bar -->
      <div class="px-4 pb-3 sm:hidden">
        <div class="relative">
          <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-base"></i>
          <input 
            type="text" 
            placeholder="Search Product Here" 
            oninput="handleStoreSearch(this.value)"
            class="w-full bg-[#E5E7EB] text-zinc-800 placeholder-zinc-500 rounded-full py-2 pl-9 pr-4 text-sm outline-none"
          >
        </div>
      </div>
    </header>

    <!-- Main Store Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">

      <!-- ================= STORE VIEW 1: HOMEPAGE (Homepage.png) ================= -->
      <section id="view-store-home" class="space-y-12">
        
        <!-- Hero Banner matching wireframe -->
        <div class="relative rounded-3xl overflow-hidden bg-[#CBD5E1] min-h-[380px] sm:min-h-[440px] flex items-end p-8 sm:p-14 shadow-sm border border-zinc-300/50">
          <img 
            src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=1600&q=80" 
            alt="Thrift fashion hero" 
            class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-40 filter contrast-125"
          />
          <div class="relative z-10 max-w-xl">
            <span class="inline-block px-3 py-1 bg-zinc-900 text-white text-xs font-black tracking-widest uppercase rounded-full mb-3">Sustainable Fashion</span>
            <h1 class="text-3xl sm:text-5xl font-black text-zinc-900 tracking-tight leading-tight mb-4">
              FIND YOUR SIGNATURE VINTAGE PIECE.
            </h1>
            <button 
              onclick="navigateStore('shop')"
              class="bg-[#71717A] hover:bg-[#52525B] text-white font-extrabold text-base sm:text-lg tracking-wider px-8 py-3.5 rounded-2xl shadow-lg hover:shadow-xl transition transform active:scale-95 duration-150 uppercase"
            >
              ORDER NOW !
            </button>
          </div>
        </div>

        <!-- Hello, We Are T-Thrift Statement Section -->
        <div class="py-6 text-center max-w-3xl mx-auto px-4">
          <h2 class="text-3xl sm:text-4xl font-black text-zinc-900 mb-4 tracking-tight">
            Hello, We Are T-Thrift
          </h2>
          <p class="text-base sm:text-lg text-zinc-600 leading-relaxed font-normal">
            TahtaThrift is your destination for quality preloved t-shirts that combine style, affordability, and sustainability. By giving clothes a second life, we help reduce fashion waste and inspire more responsible shopping choices for a better future.
          </p>
        </div>

        <!-- 3 Preview Cards matching Homepage wireframe bottom -->
        <div>
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl sm:text-2xl font-black tracking-tight text-zinc-900">Featured Curation</h3>
            <button onclick="navigateStore('shop')" class="text-sm font-bold text-zinc-600 hover:text-black flex items-center gap-1 group">
              Explore All 20 Pcs <i class="ph ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
          </div>
          <div id="featuredGridContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <!-- Injected via JavaScript -->
          </div>
        </div>
      </section>

      <!-- ================= STORE VIEW 2: SHOP / CATALOG (Shop.png) ================= -->
      <section id="view-store-shop" class="hidden space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-zinc-200 gap-4">
          <div>
            <h2 class="text-3xl font-black text-zinc-900 tracking-tight">All Thrift Collections</h2>
            <p class="text-sm text-zinc-500 mt-1">20 item kaos & jaket vintage pria & wanita terkurasi siap pakai.</p>
          </div>
          
          <!-- Size Filter Controls -->
          <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 mr-1">Filter Size:</span>
            <button onclick="filterStoreBySize('ALL')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-zinc-900 text-white transition">All</button>
            <button onclick="filterStoreBySize('S')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition">S</button>
            <button onclick="filterStoreBySize('M')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition">M</button>
            <button onclick="filterStoreBySize('L')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition">L</button>
            <button onclick="filterStoreBySize('XL')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition">XL</button>
          </div>
        </div>

        <!-- 3-Column Catalog Grid (Shop.png) -->
        <div id="catalogGridContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Injected via JavaScript -->
        </div>

        <div id="noProductsAlert" class="hidden text-center py-20">
          <i class="ph ph-t-shirt text-6xl text-zinc-300 mb-3 inline-block"></i>
          <p class="text-lg font-bold text-zinc-700">Tidak ada produk yang cocok</p>
          <p class="text-sm text-zinc-500 mt-1">Coba kata kunci lain atau setel ulang filter ukuran.</p>
        </div>
      </section>

      <!-- ================= STORE VIEW 3: CART (Cart.png) ================= -->
      <section id="view-store-cart" class="hidden space-y-8">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
          <h2 class="text-3xl font-black text-zinc-900 tracking-tight">My Cart</h2>
          <button onclick="navigateStore('shop')" class="text-sm font-bold text-zinc-600 hover:text-black">
            &larr; Continue Shopping
          </button>
        </div>

        <div class="bg-white/80 backdrop-blur rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-sm">
          <!-- Table Header matching Cart.png wireframe -->
          <div class="grid grid-cols-12 pb-4 border-b-2 border-zinc-900 font-bold text-zinc-800 text-sm sm:text-base">
            <div class="col-span-6">Product</div>
            <div class="col-span-2 text-center">Price</div>
            <div class="col-span-2 text-center">Qty</div>
            <div class="col-span-2 text-right">Total</div>
          </div>

          <!-- Cart Items List -->
          <div id="cartItemsContainer" class="divide-y divide-zinc-200 min-h-[160px]">
            <!-- Injected via JavaScript -->
          </div>

          <div id="emptyCartMessage" class="hidden py-16 text-center text-zinc-500">
            <i class="ph ph-shopping-bag text-5xl text-zinc-300 mb-3 inline-block"></i>
            <p class="text-base font-semibold">Keranjang belanja Anda masih kosong</p>
            <button onclick="navigateStore('shop')" class="mt-4 px-6 py-2.5 bg-zinc-900 text-white rounded-xl text-xs font-bold hover:bg-black transition">
              Cari Kaos Vintage
            </button>
          </div>

          <!-- Payment Box matching Cart.png wireframe -->
          <div class="mt-8 pt-6 border-t border-zinc-200">
            <div class="max-w-xl ml-auto bg-[#D1D5DB] rounded-3xl p-6 sm:p-8 space-y-5 text-zinc-900 shadow-inner">
              <div>
                <label class="block font-bold text-base mb-2">Select Payment Method</label>
                <div class="relative">
                  <select id="storePaymentMethod" class="w-full appearance-none bg-white font-semibold py-3 px-4 rounded-xl border border-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-800 text-sm cursor-pointer">
                    <option value="QRIS">⚪ QRIS (GoPay, OVO, Dana, BCA)</option>
                    <option value="Bank BCA">Bank Transfer - BCA VA</option>
                    <option value="Bank Mandiri">Bank Transfer - Mandiri Bill</option>
                    <option value="COD">Cash On Delivery (COD)</option>
                  </select>
                  <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-600"></i>
                </div>
              </div>

              <!-- Breakdown -->
              <div class="space-y-2 pt-2 border-t border-zinc-400 text-sm sm:text-base font-semibold">
                <div class="flex justify-between">
                  <span>Subtotal</span>
                  <span id="cartSubtotalText" class="font-bold">Rp 0</span>
                </div>
                <div class="flex justify-between text-zinc-700">
                  <span>Discount</span>
                  <span id="cartDiscountText" class="font-medium">Rp 0</span>
                </div>
                <div class="flex justify-between text-lg font-black pt-2 border-t-2 border-zinc-900">
                  <span>Total</span>
                  <span id="cartTotalText" class="font-black text-xl">Rp 0</span>
                </div>
              </div>

              <!-- Checkout Button -->
              <div class="pt-4 text-center">
                <button 
                  onclick="processStoreCheckout()"
                  class="bg-[#71717A] hover:bg-[#52525B] text-white font-bold px-10 py-3.5 rounded-2xl shadow hover:shadow-lg transition transform active:scale-95 text-base uppercase"
                >
                  Check Out
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= STORE VIEW 4: ORDER HISTORY (Order History.png) ================= -->
      <section id="view-store-orders" class="space-y-10 hidden">
        <div>
          <h2 class="text-3xl font-black text-zinc-900 tracking-tight">Order Status & History</h2>
          <p class="text-zinc-500 text-sm mt-1">Lacak kiriman paket thrift Anda dan riwayat pesanan selesai.</p>
        </div>

        <!-- Section: Shipping -->
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <h3 class="text-2xl font-black text-zinc-900">Shipping</h3>
            <span id="shippingBadgeCount" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">1 Item</span>
          </div>
          <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-sm overflow-hidden">
            <div class="grid grid-cols-12 pb-3 border-b border-zinc-300 font-bold text-zinc-700 text-sm">
              <div class="col-span-6">Product</div>
              <div class="col-span-2 text-center">Price</div>
              <div class="col-span-2 text-center">Qty</div>
              <div class="col-span-2 text-right">Total</div>
            </div>
            <div id="shippingListContainer" class="divide-y divide-zinc-100">
              <!-- Injected via JavaScript -->
            </div>
          </div>
        </div>

        <!-- Section: Order completed -->
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <h3 class="text-2xl font-black text-zinc-900">Order completed</h3>
            <span id="completedBadgeCount" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">1 Item</span>
          </div>
          <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-sm overflow-hidden">
            <div class="grid grid-cols-12 pb-3 border-b border-zinc-300 font-bold text-zinc-700 text-sm">
              <div class="col-span-6">Product</div>
              <div class="col-span-2 text-center">Price</div>
              <div class="col-span-2 text-center">Qty</div>
              <div class="col-span-2 text-right">Total</div>
            </div>
            <div id="completedListContainer" class="divide-y divide-zinc-100">
              <!-- Injected via JavaScript -->
            </div>
          </div>
        </div>
      </section>

    </main>

    <!-- Store Footer -->
    <footer class="mt-auto border-t border-zinc-200 bg-white/95 py-6">
      <div class="max-w-7xl mx-auto px-4 text-center space-y-1">
        <p class="font-black tracking-widest text-base">T-THRIFT</p>
        <p class="text-xs text-zinc-500 font-medium">TahtaThrift &copy; 2026. Sustainable E-Commerce System for Curated Vintage Apparel.</p>
      </div>
    </footer>

  </div>


  <!-- ================================================================= -->
  <!--                      2. ADMIN DASHBOARD VIEW                      -->
  <!-- ================================================================= -->
  <div id="container-admin" class="flex-1 flex flex-col hidden p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
    
    <!-- Admin Header Branding -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <h2 class="text-3xl font-black tracking-tight text-zinc-900">T-THRIFT</h2>
        <span class="text-[11px] uppercase font-black tracking-widest bg-zinc-900 text-white px-3 py-1 rounded-md">Admin Workspace</span>
      </div>

      <div class="flex items-center gap-3">
        <button onclick="switchWorkspaceMode('store')" class="bg-white hover:bg-zinc-100 text-zinc-800 font-bold px-4 py-2 rounded-xl text-xs border border-zinc-300 shadow-sm transition flex items-center gap-1.5">
          <i class="ph ph-arrow-left"></i>
          <span>Ke Web Pembeli</span>
        </button>
        <div class="flex items-center gap-2 bg-emerald-100 text-emerald-800 px-3 py-1.5 rounded-xl text-xs font-bold">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>Admin Sync Active</span>
        </div>
      </div>
    </div>

    <!-- Admin Main Split Layout (Sidebar + Tabs) -->
    <div class="flex-1 flex flex-col lg:flex-row gap-6 items-start">
      
      <!-- ================= ADMIN SIDEBAR ================= -->
      <aside class="w-full lg:w-64 flex flex-col justify-between shrink-0 gap-6">
        
        <div class="bg-[#dcdde1] rounded-3xl p-5 shadow-sm space-y-4">
          <nav class="space-y-2">
            
            <!-- 1. Dashboard -->
            <button onclick="switchAdminTab('dashboard')" id="nav-adm-dashboard" class="admin-nav-item active w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 hover:text-black transition p-2.5 rounded-2xl">
              <i class="ph ph-laptop text-lg text-zinc-800"></i>
              <span>Dashboard</span>
            </button>

            <!-- 2. Product & Inventory Management -->
            <button onclick="switchAdminTab('products')" id="nav-adm-products" class="admin-nav-item w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 hover:text-black transition p-2.5 rounded-2xl">
              <i class="ph ph-package text-lg text-zinc-800"></i>
              <span class="leading-tight">Product & Inventory Management</span>
            </button>

            <!-- 3. Order Management -->
            <button onclick="switchAdminTab('orders')" id="nav-adm-orders" class="admin-nav-item w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 hover:text-black transition p-2.5 rounded-2xl">
              <i class="ph ph-shopping-cart text-lg text-zinc-800"></i>
              <span>Order Management</span>
            </button>

            <!-- 4. Customer Management -->
            <button onclick="switchAdminTab('customers')" id="nav-adm-customers" class="admin-nav-item w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 hover:text-black transition p-2.5 rounded-2xl">
              <i class="ph ph-users text-lg text-zinc-800"></i>
              <span>Customer Management</span>
            </button>

            <!-- 5. Sales Report -->
            <button onclick="switchAdminTab('sales')" id="nav-adm-sales" class="admin-nav-item w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 hover:text-black transition p-2.5 rounded-2xl">
              <i class="ph ph-trend-up text-lg text-zinc-800"></i>
              <span>Sales Report</span>
            </button>

          </nav>
        </div>

        <button onclick="handleAdminLogout()" class="w-full bg-[#dcdde1] hover:bg-zinc-300 text-zinc-800 font-bold py-3.5 px-4 rounded-2xl text-sm transition shadow-sm active:scale-95 text-center">
          Log Out
        </button>

      </aside>

      <!-- ================= ADMIN MAIN TAB CONTENT ================= -->
      <main class="flex-1 w-full bg-[#dcdde1] rounded-3xl p-6 sm:p-8 shadow-sm min-h-[640px]">
        
        <!-- ===== ADMIN TAB 1: DASHBOARD (Dashboard (1).png) ===== -->
        <section id="tab-adm-dashboard" class="space-y-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Total Products -->
            <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between shadow-sm">
              <h3 class="text-base font-bold">Total Products</h3>
              <div>
                <span id="admKpiProducts" class="text-3xl sm:text-4xl font-black">20</span>
                <p class="text-xs text-zinc-200 mt-1 font-medium">Kaos, Jaket, & Crewneck Thrifting</p>
              </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between shadow-sm">
              <h3 class="text-base font-bold">Total Orders</h3>
              <div>
                <span id="admKpiOrders" class="text-3xl sm:text-4xl font-black">18</span>
                <p class="text-xs text-zinc-200 mt-1 font-medium">4 Shipping • 14 Completed</p>
              </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between shadow-sm">
              <h3 class="text-base font-bold">Total Customers</h3>
              <div>
                <span id="admKpiCustomers" class="text-3xl sm:text-4xl font-black">12</span>
                <p class="text-xs text-zinc-200 mt-1 font-medium">Active Registered Members</p>
              </div>
            </div>

            <!-- Total Sales -->
            <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between shadow-sm">
              <h3 class="text-base font-bold">Total Sales</h3>
              <div>
                <span id="admKpiSales" class="text-2xl sm:text-3xl font-black">Rp 2.450.000</span>
                <p class="text-xs text-zinc-200 mt-1 font-medium">+18.5% Growth Month-over-Month</p>
              </div>
            </div>
          </div>

          <!-- Sales Overview (CHART) -->
          <div class="bg-[#8b919a] text-white p-6 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="text-base font-bold tracking-tight">Sales Overview (CHART)</h3>
              <span class="text-xs font-bold bg-white/20 px-3 py-1 rounded-full">FY 2026</span>
            </div>
            <div class="h-64 w-full bg-white rounded-xl p-4 shadow-inner">
              <canvas id="admDashboardChart"></canvas>
            </div>
          </div>

          <!-- Recent Orders Card -->
          <div class="bg-[#8b919a] text-white p-6 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="text-base font-bold">Recent Orders</h3>
              <button onclick="switchAdminTab('orders')" class="text-xs text-zinc-200 hover:text-white underline font-semibold">View All &rarr;</button>
            </div>
            <div class="overflow-x-auto custom-scroll">
              <table class="w-full text-left text-xs bg-white text-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <thead class="bg-zinc-200 text-zinc-700 uppercase font-black text-[11px]">
                  <tr>
                    <th class="p-3.5">Order ID</th>
                    <th class="p-3.5">Customer</th>
                    <th class="p-3.5">Item</th>
                    <th class="p-3.5">Total</th>
                    <th class="p-3.5">Status</th>
                  </tr>
                </thead>
                <tbody id="admRecentOrdersTbody" class="divide-y divide-zinc-200">
                  <!-- Injected via JS -->
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ===== ADMIN TAB 2: PRODUCT & INVENTORY MANAGEMENT ===== -->
        <section id="tab-adm-products" class="hidden space-y-5">
          <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <div class="flex-1">
              <input 
                type="text" 
                id="admFilterProdSearch" 
                placeholder="Search Product..." 
                oninput="filterAdmProducts()"
                class="w-full bg-[#8b919a] text-white placeholder-zinc-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-zinc-800 shadow-sm"
              />
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
              <select id="admFilterProdCat" onchange="filterAdmProducts()" class="bg-[#8b919a] text-white rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none cursor-pointer shadow-sm">
                <option value="ALL">Category: All</option>
                <option value="T-Shirt">T-Shirt</option>
                <option value="Jacket">Jacket</option>
                <option value="Crewneck">Crewneck</option>
              </select>

              <select id="admFilterProdBrand" onchange="filterAdmProducts()" class="bg-[#8b919a] text-white rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none cursor-pointer shadow-sm">
                <option value="ALL">Brand: All</option>
                <option value="Nike">Nike</option>
                <option value="Adidas">Adidas</option>
                <option value="Zara">Zara</option>
                <option value="The North Face">The North Face</option>
                <option value="Levi's">Levi's</option>
                <option value="Dickies">Dickies</option>
                <option value="Uniqlo">Uniqlo</option>
              </select>

              <button onclick="openAddProductModal()" class="bg-[#8b919a] hover:bg-zinc-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-sm active:scale-95">
                + Add Product
              </button>
            </div>
          </div>

          <div class="bg-[#8b919a] rounded-2xl p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between text-white">
              <h3 class="text-base font-bold">Tabel Produk & Stok Inventaris</h3>
              <span id="admProductBadge" class="text-xs font-semibold bg-white/20 px-2.5 py-0.5 rounded-md">20 Items</span>
            </div>
            <div class="overflow-x-auto custom-scroll bg-white rounded-xl shadow-inner max-h-[500px]">
              <table class="w-full text-left text-xs text-zinc-800">
                <thead class="bg-zinc-200 text-zinc-700 uppercase font-black text-[11px] sticky top-0">
                  <tr>
                    <th class="p-3.5">ID</th>
                    <th class="p-3.5">Product Name</th>
                    <th class="p-3.5">Category</th>
                    <th class="p-3.5">Size</th>
                    <th class="p-3.5">Condition</th>
                    <th class="p-3.5">Price</th>
                    <th class="p-3.5">Status</th>
                    <th class="p-3.5 text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="admInventoryTbody" class="divide-y divide-zinc-200">
                  <!-- Injected via JS -->
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ===== ADMIN TAB 3: ORDER MANAGEMENT ===== -->
        <section id="tab-adm-orders" class="hidden space-y-5">
          <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <div class="flex-1">
              <input 
                type="text" 
                id="admFilterOrderSearch" 
                placeholder="Search Customer or Order ID..." 
                oninput="filterAdmOrders()"
                class="w-full bg-[#8b919a] text-white placeholder-zinc-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-zinc-800 shadow-sm"
              />
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
              <select id="admFilterOrderPayment" onchange="filterAdmOrders()" class="bg-[#8b919a] text-white rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none cursor-pointer shadow-sm">
                <option value="ALL">Payment: All</option>
                <option value="QRIS">QRIS</option>
                <option value="Bank BCA">BCA VA</option>
                <option value="Bank Mandiri">Mandiri Bill</option>
                <option value="COD">COD</option>
              </select>

              <select id="admFilterOrderStatus" onchange="filterAdmOrders()" class="bg-[#8b919a] text-white rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none cursor-pointer shadow-sm">
                <option value="ALL">Status: All</option>
                <option value="Shipping">Shipping</option>
                <option value="Completed">Completed</option>
              </select>

              <button class="bg-[#8b919a] text-white font-semibold px-4 py-2.5 rounded-xl text-sm shadow-sm">
                Sep 23, 2026
              </button>
            </div>
          </div>

          <div class="bg-[#8b919a] rounded-2xl p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between text-white">
              <h3 class="text-base font-bold">Tabel Manajemen Pesanan Masuk</h3>
              <span id="admOrderBadge" class="text-xs font-semibold bg-white/20 px-2.5 py-0.5 rounded-md">6 Orders</span>
            </div>
            <div class="overflow-x-auto custom-scroll bg-white rounded-xl shadow-inner max-h-[500px]">
              <table class="w-full text-left text-xs text-zinc-800">
                <thead class="bg-zinc-200 text-zinc-700 uppercase font-black text-[11px] sticky top-0">
                  <tr>
                    <th class="p-3.5">Order ID</th>
                    <th class="p-3.5">Customer</th>
                    <th class="p-3.5">Item Purchased</th>
                    <th class="p-3.5">Payment</th>
                    <th class="p-3.5">Total Amount</th>
                    <th class="p-3.5">Status</th>
                    <th class="p-3.5 text-center">Toggle Status</th>
                  </tr>
                </thead>
                <tbody id="admOrdersTbody" class="divide-y divide-zinc-200">
                  <!-- Injected via JS -->
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ===== ADMIN TAB 4: CUSTOMER MANAGEMENT ===== -->
        <section id="tab-adm-customers" class="hidden space-y-5">
          <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
            <div class="flex-1 max-w-md">
              <input 
                type="text" 
                id="admFilterCustSearch" 
                placeholder="Search Customer by Name, Email, or Phone..." 
                oninput="filterAdmCustomers()"
                class="w-full bg-[#8b919a] text-white placeholder-zinc-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-zinc-800 shadow-sm"
              />
            </div>

            <div class="flex items-center gap-2.5">
              <select id="admFilterCustStatus" onchange="filterAdmCustomers()" class="bg-[#8b919a] text-white rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none cursor-pointer shadow-sm">
                <option value="ALL">Status: All</option>
                <option value="Active">Active</option>
                <option value="Pending">Pending</option>
              </select>
            </div>
          </div>

          <div class="bg-[#8b919a] rounded-2xl p-5 shadow-sm space-y-3">
            <h3 class="text-white text-base font-bold">Tabel Pelanggan Terdaftar</h3>
            <div class="overflow-x-auto custom-scroll bg-white rounded-xl shadow-inner max-h-[500px]">
              <table class="w-full text-left text-xs text-zinc-800">
                <thead class="bg-zinc-200 text-zinc-700 uppercase font-black text-[11px] sticky top-0">
                  <tr>
                    <th class="p-3.5">Cust ID</th>
                    <th class="p-3.5">Customer Name</th>
                    <th class="p-3.5">Email Address</th>
                    <th class="p-3.5">Phone Number</th>
                    <th class="p-3.5">Total Orders</th>
                    <th class="p-3.5">Status</th>
                    <th class="p-3.5 text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="admCustomersTbody" class="divide-y divide-zinc-200">
                  <!-- Injected via JS -->
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ===== ADMIN TAB 5: SALES REPORT ===== -->
        <section id="tab-adm-sales" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="flex items-center gap-2 w-full sm:w-auto">
              <span class="text-sm font-bold text-zinc-800">From Date :</span>
              <input type="date" value="2026-09-01" class="bg-[#8b919a] text-white rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none cursor-pointer shadow-sm">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
              <span class="text-sm font-bold text-zinc-800">To Date :</span>
              <input type="date" value="2026-09-23" class="bg-[#8b919a] text-white rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none cursor-pointer shadow-sm">
            </div>
            <button onclick="showNotification('Laporan omset TahtaThrift periode September 2026 berhasil diekspor!')" class="ml-auto bg-zinc-900 hover:bg-black text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow active:scale-95">
              Export Report (CSV/PDF)
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between shadow-sm">
              <h3 class="text-base font-bold">Total Sales</h3>
              <div>
                <span class="text-3xl font-black">Rp 2.450.000</span>
                <p class="text-xs text-zinc-200 mt-1 font-medium">Settled gross revenue</p>
              </div>
            </div>

            <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between shadow-sm">
              <h3 class="text-base font-bold">Total Orders</h3>
              <div>
                <span class="text-3xl font-black">18 Orders</span>
                <p class="text-xs text-zinc-200 mt-1 font-medium">100% verified transactions</p>
              </div>
            </div>

            <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between shadow-sm">
              <h3 class="text-base font-bold">Products Sold</h3>
              <div>
                <span class="text-3xl font-black">24 Pcs</span>
                <p class="text-xs text-zinc-200 mt-1 font-medium">Vintage T-Shirts & Jackets</p>
              </div>
            </div>

            <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between shadow-sm">
              <h3 class="text-base font-bold">Average Orders</h3>
              <div>
                <span class="text-3xl font-black">Rp 136.111</span>
                <p class="text-xs text-zinc-200 mt-1 font-medium">Average Order Value (AOV)</p>
              </div>
            </div>
          </div>

          <!-- Sales Overview Bar Chart -->
          <div class="bg-[#8b919a] text-white p-6 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="text-base font-bold">Sales Overview (CHART)</h3>
              <span class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full">Weekly Breakdown</span>
            </div>
            <div class="h-64 w-full bg-white rounded-xl p-4 shadow-inner">
              <canvas id="admSalesReportChart"></canvas>
            </div>
          </div>

          <!-- Best Selling Products Table -->
          <div class="bg-[#8b919a] text-white p-6 rounded-2xl shadow-sm space-y-4">
            <h3 class="text-base font-bold">Best Selling Products</h3>
            <div class="overflow-x-auto custom-scroll bg-white rounded-xl shadow-inner">
              <table class="w-full text-left text-xs text-zinc-800">
                <thead class="bg-zinc-200 text-zinc-700 uppercase font-black text-[11px]">
                  <tr>
                    <th class="p-3.5">Rank</th>
                    <th class="p-3.5">Product Name</th>
                    <th class="p-3.5">Brand</th>
                    <th class="p-3.5">Price</th>
                    <th class="p-3.5">Sold Qty</th>
                    <th class="p-3.5">Total Revenue</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                  <tr>
                    <td class="p-3.5 font-bold text-amber-600">#1</td>
                    <td class="p-3.5 font-bold text-zinc-900">T-Shirt Outmost Brown</td>
                    <td class="p-3.5">Outmost</td>
                    <td class="p-3.5">Rp 85.000</td>
                    <td class="p-3.5 font-extrabold text-zinc-900">6 pcs</td>
                    <td class="p-3.5 font-black text-zinc-900">Rp 510.000</td>
                  </tr>
                  <tr>
                    <td class="p-3.5 font-bold text-zinc-500">#2</td>
                    <td class="p-3.5 font-bold text-zinc-900">3Second Men's Jacket Crewneck</td>
                    <td class="p-3.5">3Second</td>
                    <td class="p-3.5">Rp 100.000</td>
                    <td class="p-3.5 font-extrabold text-zinc-900">4 pcs</td>
                    <td class="p-3.5 font-black text-zinc-900">Rp 400.000</td>
                  </tr>
                  <tr>
                    <td class="p-3.5 font-bold text-amber-800">#3</td>
                    <td class="p-3.5 font-bold text-zinc-900">The North Face Windbreaker Jacket</td>
                    <td class="p-3.5">The North Face</td>
                    <td class="p-3.5">Rp 220.000</td>
                    <td class="p-3.5 font-extrabold text-zinc-900">2 pcs</td>
                    <td class="p-3.5 font-black text-zinc-900">Rp 440.000</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>

      </main>
    </div>
  </div>


  <!-- ================================================================= -->
  <!--                      ALL INTERACTIVE MODALS                       -->
  <!-- ================================================================= -->

  <!-- MODAL: PRODUCT DETAIL -->
  <div id="modal-product-detail" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 hidden">
    <div class="relative bg-white rounded-[32px] w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-6 sm:p-8">
      <button onclick="closeModal('modal-product-detail')" class="absolute top-5 right-5 z-10 w-9 h-9 rounded-full bg-zinc-100 hover:bg-zinc-200 flex items-center justify-center text-zinc-600 transition">
        <i class="ph ph-x text-xl font-bold"></i>
      </button>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 items-start">
        <div class="relative w-full aspect-[4/5] bg-zinc-200 rounded-2xl overflow-hidden shadow-inner">
          <img id="detailImg" src="" alt="Product" class="w-full h-full object-cover">
          <span id="detailCondBadge" class="absolute top-3 left-3 bg-zinc-900/85 backdrop-blur-sm text-white text-xs font-extrabold px-3 py-1 rounded-full">
            Condition : 95%
          </span>
        </div>

        <div class="space-y-4 flex flex-col justify-between">
          <div>
            <span id="detailBrand" class="text-xs uppercase tracking-widest font-black text-zinc-400">BRAND</span>
            <h3 id="detailName" class="text-2xl font-black text-zinc-900 leading-tight mt-0.5">Product Name</h3>
            <p id="detailPrice" class="text-2xl font-black text-zinc-900 mt-2">Rp 0</p>
          </div>

          <div class="bg-zinc-50 rounded-2xl p-4 border border-zinc-100 space-y-2 text-xs sm:text-sm">
            <div class="flex justify-between">
              <span class="text-zinc-500 font-medium">Size Tag</span>
              <span id="detailSize" class="font-bold text-zinc-800">M</span>
            </div>
            <div class="flex justify-between">
              <span class="text-zinc-500 font-medium">Dimensi (P x L)</span>
              <span id="detailDimension" class="font-bold text-zinc-800">70 cm x 52 cm</span>
            </div>
            <div class="flex justify-between">
              <span class="text-zinc-500 font-medium">Bahan / Material</span>
              <span id="detailMaterial" class="font-bold text-zinc-800">100% Heavy Cotton</span>
            </div>
            <div class="flex justify-between">
              <span class="text-zinc-500 font-medium">Minus / Defect</span>
              <span id="detailDefect" class="font-bold text-emerald-600">No Minus (Clean)</span>
            </div>
          </div>

          <div>
            <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-1">Description</h4>
            <p id="detailDescription" class="text-xs sm:text-sm text-zinc-600 leading-relaxed">
              Deskripsi preloved vintage siap pakai.
            </p>
          </div>

          <div class="pt-2 flex gap-3">
            <button onclick="addFromModal()" class="flex-1 bg-zinc-900 hover:bg-black text-white font-bold py-3.5 px-4 rounded-2xl text-sm transition transform active:scale-95 flex items-center justify-center gap-2 shadow-md">
              <i class="ph ph-shopping-cart-simple text-lg"></i>
              <span>Add to Cart</span>
            </button>
            <button onclick="buyNowFromModal()" class="flex-1 bg-[#71717A] hover:bg-[#52525B] text-white font-bold py-3.5 px-4 rounded-2xl text-sm transition transform active:scale-95 shadow-md uppercase tracking-wider">
              Buy Now
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL: LOGIN (Login.png) -->
  <div id="modal-login" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 hidden">
    <div class="relative bg-white rounded-[32px] w-full max-w-md p-8 sm:p-10 shadow-2xl">
      <button onclick="closeModal('modal-login')" class="absolute top-5 right-5 text-zinc-400 hover:text-black">
        <i class="ph ph-x text-2xl"></i>
      </button>

      <form onsubmit="handleLoginSubmit(event)" class="space-y-6">
        <div>
          <label class="block text-lg font-bold text-zinc-900 mb-2">Email / No. Telp</label>
          <input type="text" id="loginIdInput" required placeholder="name@example.com" class="w-full bg-[#D1D5DB] rounded-2xl py-4 px-5 text-zinc-900 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>
        <div>
          <label class="block text-lg font-bold text-zinc-900 mb-2">Password</label>
          <input type="password" required placeholder="••••••••" class="w-full bg-[#D1D5DB] rounded-2xl py-4 px-5 text-zinc-900 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>
        <div class="flex items-center justify-between text-xs sm:text-sm font-semibold text-zinc-700">
          <button type="button" onclick="switchAuthModal('modal-login', 'modal-register')" class="hover:underline">Don't have an account?</button>
          <button type="button" onclick="showNotification('Tautan reset kata sandi telah dikirimkan ke email!')" class="hover:underline">Forgot Password?</button>
        </div>
        <div class="pt-4 text-center">
          <button type="submit" class="bg-[#71717A] hover:bg-[#52525B] text-white font-bold px-10 py-3 rounded-2xl shadow transition active:scale-95 uppercase text-sm">
            Login
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: REGISTER (Register.png) -->
  <div id="modal-register" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 hidden">
    <div class="relative bg-white rounded-[32px] w-full max-w-md p-8 sm:p-10 shadow-2xl">
      <button onclick="closeModal('modal-register')" class="absolute top-5 right-5 text-zinc-400 hover:text-black">
        <i class="ph ph-x text-2xl"></i>
      </button>

      <form onsubmit="handleRegisterSubmit(event)" class="space-y-4">
        <div>
          <label class="block text-base font-bold text-zinc-900 mb-1.5">Username</label>
          <input type="text" id="regUsername" required placeholder="vintagefan23" class="w-full bg-[#D1D5DB] rounded-2xl py-3 px-5 text-zinc-900 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>
        <div>
          <label class="block text-base font-bold text-zinc-900 mb-1.5">Email</label>
          <input type="email" id="regEmail" required placeholder="fan@vintage.com" class="w-full bg-[#D1D5DB] rounded-2xl py-3 px-5 text-zinc-900 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>
        <div>
          <label class="block text-base font-bold text-zinc-900 mb-1.5">No. Telp</label>
          <input type="tel" id="regPhone" required placeholder="081234567890" class="w-full bg-[#D1D5DB] rounded-2xl py-3 px-5 text-zinc-900 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>
        <div>
          <label class="block text-base font-bold text-zinc-900 mb-1.5">Password</label>
          <input type="password" required placeholder="••••••••" class="w-full bg-[#D1D5DB] rounded-2xl py-3 px-5 text-zinc-900 font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>
        <div class="text-right text-xs font-semibold text-zinc-600">
          Already registered? <button type="button" onclick="switchAuthModal('modal-register', 'modal-login')" class="text-black underline">Login</button>
        </div>
        <div class="pt-3 text-center">
          <button type="submit" class="bg-[#71717A] hover:bg-[#52525B] text-white font-bold px-10 py-3 rounded-2xl shadow transition active:scale-95 uppercase text-sm">
            Sign Up
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: PROFILE (Profile.png) -->
  <div id="modal-profile" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 hidden">
    <div class="relative bg-white rounded-[36px] w-full max-w-md p-8 sm:p-10 shadow-2xl text-center">
      <button onclick="closeModal('modal-profile')" class="absolute top-5 right-5 text-zinc-400 hover:text-black">
        <i class="ph ph-x text-2xl"></i>
      </button>

      <div class="w-32 h-32 rounded-full bg-[#CBD5E1] mx-auto flex items-center justify-center shadow-inner overflow-hidden border-4 border-white mb-3">
        <i class="ph-fill ph-user text-6xl text-zinc-400"></i>
      </div>
      <h3 class="text-2xl font-black text-zinc-900 mb-5">Profile</h3>

      <div class="space-y-4 text-left">
        <div>
          <label class="block text-sm font-bold text-zinc-900 mb-1">Name</label>
          <input type="text" id="profNameInput" class="w-full bg-[#E5E7EB] rounded-2xl py-2.5 px-4 font-semibold text-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>
        <div>
          <label class="block text-sm font-bold text-zinc-900 mb-1">Email</label>
          <input type="email" id="profEmailInput" class="w-full bg-[#E5E7EB] rounded-2xl py-2.5 px-4 font-semibold text-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>
        <div>
          <label class="block text-sm font-bold text-zinc-900 mb-1">Address</label>
          <textarea id="profAddressInput" rows="2" class="w-full bg-[#E5E7EB] rounded-2xl py-2.5 px-4 font-semibold text-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-800 resize-none"></textarea>
        </div>
      </div>

      <div class="mt-6 flex items-center justify-between gap-3">
        <button onclick="handleStoreLogout()" class="px-5 py-2.5 border border-red-300 text-red-600 rounded-2xl text-xs font-bold hover:bg-red-50 transition">
          Log Out
        </button>
        <button onclick="saveStoreProfile()" class="bg-[#71717A] hover:bg-[#52525B] text-white font-bold px-8 py-2.5 rounded-2xl text-sm transition">
          Save Changes
        </button>
      </div>
    </div>
  </div>

  <!-- MODAL: ADD PRODUCT (ADMIN) -->
  <div id="modal-add-product" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4">
      <div class="flex items-center justify-between border-b border-zinc-200 pb-3">
        <h3 class="text-xl font-bold text-zinc-900">Add New Thrift Product</h3>
        <button onclick="closeModal('modal-add-product')" class="text-zinc-400 hover:text-black text-2xl font-bold">&times;</button>
      </div>

      <form onsubmit="handleSaveNewProduct(event)" class="space-y-3 text-xs">
        <div>
          <label class="block font-bold text-zinc-700 mb-1">Product Name</label>
          <input type="text" id="newProdName" required placeholder="Contoh: Carhartt Workwear Pocket Tee" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-zinc-800 text-sm font-semibold">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Category</label>
            <select id="newProdCat" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm font-semibold">
              <option value="T-Shirt">T-Shirt</option>
              <option value="Jacket">Jacket</option>
              <option value="Crewneck">Crewneck</option>
            </select>
          </div>
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Brand</label>
            <input type="text" id="newProdBrand" required placeholder="Carhartt, Nike, dll" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm">
          </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Size</label>
            <select id="newProdSize" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm font-semibold">
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
            </select>
          </div>
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Condition</label>
            <input type="text" id="newProdCond" placeholder="95%" required class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm">
          </div>
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Price (Rp)</label>
            <input type="number" id="newProdPrice" placeholder="95000" required class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm">
          </div>
        </div>

        <div class="pt-3 flex justify-end gap-2.5">
          <button type="button" onclick="closeModal('modal-add-product')" class="px-4 py-2.5 rounded-xl bg-zinc-200 text-zinc-700 font-bold hover:bg-zinc-300">Batal</button>
          <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-900 text-white font-bold hover:bg-black">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>


  <!-- ================================================================= -->
  <!--                      CORE JAVASCRIPT SYSTEM                       -->
  <!-- ================================================================= -->
  <script>
    /* Global synced products state (20 items) */
    let products = [
      { id: 1, name: "T-Shirt Outmost Brown", category: "T-Shirt", brand: "Outmost", condition: "95%", size: "M", price: 85000, dimension: "71 cm x 52 cm", material: "100% Heavy Cotton 20s", defect: "No Minus (Sangat mulus)", description: "Kaos vintage tone earthy brown yang netral dan timeless. Jahitan rantai kokoh dengan warna pekat tanpa luntur.", img: "https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 2, name: "3Second Women's T-Shirt Short Sleeve", category: "T-Shirt", brand: "3Second", condition: "90%", size: "M", price: 70000, dimension: "66 cm x 48 cm", material: "Combed Cotton 30s Super Soft", defect: "Pemakaian wajar, warna masih cerah", description: "Kaos casual lengan pendek potongan wanita. Bahan sangat adem, elastis dan nyaman dipakai harian.", img: "https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 3, name: "3Second Men's Jacket Crewneck", category: "Crewneck", brand: "3Second", condition: "99%", size: "M", price: 100000, dimension: "70 cm x 55 cm", material: "Baby Terry Premium Fleece", defect: "Like New (Koleksi simpanan mulus)", description: "Crewneck sweater dengan karet rib lengan dan pinggang kencang. Cocok untuk layering outfit casual.", img: "https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 4, name: "Vintage Graphic Tee 90s", category: "T-Shirt", brand: "Vintage", condition: "92%", size: "L", price: 95000, dimension: "74 cm x 56 cm", material: "Single Stitch Heavy Cotton", defect: "Faded natural look khas band vintage", description: "Grafis legendaris dengan efek pudar vintage natural yang estetik. Tag kerah utuh dan fitting boxy 90-an.", img: "https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 5, name: "Oversized Streetwear Black", category: "T-Shirt", brand: "Uniqlo", condition: "97%", size: "XL", price: 110000, dimension: "76 cm x 60 cm", material: "Carded 16s Boxy Fit", defect: "No Minus / Tag lengkap", description: "Potongan boxy streetwear dengan kerah tebal. Sangat cocok untuk style baggy skateboard.", img: "https://images.unsplash.com/photo-1562157873-818bc0726f68?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 6, name: "Basic Pocket Tee White", category: "T-Shirt", brand: "Zara", condition: "88%", size: "S", price: 60000, dimension: "69 cm x 50 cm", material: "Soft Combed Cotton", defect: "No Minus / Saku depan bersih", description: "Kaos saku putih bersih yang serbaguna dipadankan dengan outerwear vintage.", img: "https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 7, name: "Nike Center Swoosh Heavyweight Tee", category: "T-Shirt", brand: "Nike", condition: "94%", size: "L", price: 125000, dimension: "73 cm x 55 cm", material: "100% Combed Cotton Heavy", defect: "No Minus, bordir swoosh tengah sangat rapi", description: "Item incaran kolektor thrifting: Nike Center Swoosh bordir tebal di dada. Warna hitam pekat.", img: "https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 8, name: "Adidas Originals Trefoil Ringer Tee", category: "T-Shirt", brand: "Adidas", condition: "91%", size: "M", price: 95000, dimension: "70 cm x 52 cm", material: "Cotton Jersey Retro", defect: "Pemakaian wajar, print rapi", description: "Ringer tee retro dengan strip bahu dan logo Trefoil ikonik Adidas.", img: "https://images.unsplash.com/photo-1503342394128-c104d54dba01?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 9, name: "Zara Woman Striped Crop T-Shirt", category: "T-Shirt", brand: "Zara", condition: "96%", size: "S", price: 80000, dimension: "48 cm x 40 cm", material: "95% Ribbed Cotton, 5% Elastane", defect: "Like New (Mulus bersih)", description: "Crop top wanita motif garis-garis vintage, elastis dan membentuk tubuh dengan modis.", img: "https://images.unsplash.com/photo-1527719327859-c6ce80353573?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 10, name: "Dickies Heavyweight Workwear Tee", category: "T-Shirt", brand: "Dickies", condition: "93%", size: "XL", price: 105000, dimension: "77 cm x 62 cm", material: "Rugged Heavy Cotton Workwear", defect: "No Minus, patch saku rapi", description: "Kaos kerja Dickies dengan material tahan gesekan dan build kokoh tahan cuci.", img: "https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 11, name: "Champion Reverse Weave Crewneck", category: "Crewneck", brand: "Champion", condition: "90%", size: "L", price: 175000, dimension: "72 cm x 58 cm", material: "12 oz Reverse Weave Heavy Fleece", defect: "Rib pergelangan sedikit kendor pemakaian", description: "Crewneck Champion berbobot mantap dengan teknik jahitan horizontal anti-susut.", img: "https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 12, name: "Uniqlo U Boxy Fit Pastel Lilac", category: "T-Shirt", brand: "Uniqlo", condition: "95%", size: "M", price: 75000, dimension: "71 cm x 55 cm", material: "Heavy Cotton Compact Jersey", defect: "No Minus, tag cuci lengkap", description: "Kaos boxy karya desainer Christophe Lemaire. Bahan tebal dengan kerah anti-melar.", img: "https://images.unsplash.com/photo-1581655353564-df123a1eb820?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 13, name: "The North Face Windbreaker Jacket", category: "Jacket", brand: "The North Face", condition: "98%", size: "L", price: 220000, dimension: "73 cm x 60 cm", material: "Ripstop Parachute Nylon", defect: "No Minus (Sangat terawat)", description: "Jaket parasut outdoor The North Face tahan hembusan angin dan percikan gerimis.", img: "https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 14, name: "Levi's Classic Denim Trucker Jacket", category: "Jacket", brand: "Levi's", condition: "95%", size: "M", price: 250000, dimension: "70 cm x 55 cm", material: "14 oz Rigid Denim Cotton", defect: "Fading alami di kerah (patina estetik)", description: "Jaket jeans orisinil Levi's Type III dengan kancing shank perak dan karakter vintage autentik.", img: "https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 15, name: "Harley Davidson 3D Emblem Eagle Tee", category: "T-Shirt", brand: "Vintage", condition: "89%", size: "XL", price: 190000, dimension: "76 cm x 62 cm", material: "100% Heavyweight Cotton Vintage", defect: "Distressed kecil alami di ujung hem", description: "Kaos motor Harley Davidson dengan sablon elang legendaris vintage Amerika.", img: "https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 16, name: "GAP Arch Logo Hoodie Fleece", category: "Jacket", brand: "GAP", condition: "92%", size: "M", price: 145000, dimension: "68 cm x 54 cm", material: "Cozy Cotton Poly Sherpa Fleece", defect: "No Minus, bordir logo GAP masih tebal", description: "Jaket kasual dengan logo legendaris GAP. Sangat hangat untuk hangout malam.", img: "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 17, name: "H&M Floral Vintage Blouse Tee", category: "T-Shirt", brand: "H&M", condition: "94%", size: "S", price: 65000, dimension: "64 cm x 46 cm", material: "Lightweight Breathable Cotton", defect: "No Minus", description: "Atasan kasual wanita bernuansa vintage minimalis.", img: "https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 18, name: "Polo Ralph Lauren Embroidered Tee", category: "T-Shirt", brand: "Ralph Lauren", condition: "93%", size: "L", price: 115000, dimension: "74 cm x 55 cm", material: "Pima Cotton Super Soft", defect: "No Minus, bordir kuda pony rapi", description: "T-shirt pria berkelas dari Polo Ralph Lauren dengan bahan katun pima sejuk.", img: "https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 19, name: "Pull&Bear Corduroy Overshirt Jacket", category: "Jacket", brand: "Pull&Bear", condition: "97%", size: "XL", price: 185000, dimension: "75 cm x 62 cm", material: "Washed Cotton Corduroy", defect: "No Minus, kancing utuh", description: "Jaket overshirt bahan corduroy tebal bernuansa earthy vintage.", img: "https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80", status: "In Stock" },
      { id: 20, name: "Patagonia Synchilla Snap-T Fleece", category: "Jacket", brand: "Patagonia", condition: "95%", size: "L", price: 290000, dimension: "74 cm x 61 cm", material: "100% Recycled Polyester Fleece", defect: "Kancing snap berfungsi mulus", description: "Jaket fleece legendaris Patagonia Synchilla. Hangat, ikonik, dan sangat diminati kolektor vintage outdoor.", img: "https://images.unsplash.com/photo-1578932750294-f5075e85f44a?auto=format&fit=crop&w=800&q=80", status: "In Stock" }
    ];

    /* Cart & Transaction State */
    let cart = [
      { id: 1, name: "T-Shirt Outmost Brown", condition: "95%", size: "M", price: 85000, qty: 1, img: "https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80" },
      { id: 2, name: "3Second Women's T-Shirt Short Sleeve", condition: "90%", size: "M", price: 70000, qty: 1, img: "https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80" },
      { id: 3, name: "3Second Men's Jacket Crewneck", condition: "99%", size: "M", price: 100000, qty: 1, img: "https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80" }
    ];

    let shippingOrders = [
      { id: "ORD-9021", customer: "Budi Santoso", item: "T-Shirt Outmost Brown", payment: "QRIS", condition: "95%", size: "M", price: 85000, qty: 1, total: 85000, status: "Shipping", img: "https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80" }
    ];

    let completedOrders = [
      { id: "ORD-9018", customer: "Dimas Arya", item: "Patagonia Snap-T Fleece", payment: "COD", condition: "95%", size: "L", price: 290000, qty: 1, total: 290000, status: "Completed", img: "https://images.unsplash.com/photo-1578932750294-f5075e85f44a?auto=format&fit=crop&w=800&q=80" }
    ];

    let adminOrders = [
      { id: "ORD-9021", customer: "Budi Santoso", item: "T-Shirt Outmost Brown", payment: "QRIS", total: 85000, status: "Shipping", date: "2026-09-23" },
      { id: "ORD-9020", customer: "Ahmad Fauzi", item: "3Second Jacket Crewneck", payment: "Bank BCA", total: 100000, status: "Shipping", date: "2026-09-22" },
      { id: "ORD-9019", customer: "Siti Rahma", item: "Nike Center Swoosh Tee", payment: "Bank Mandiri", total: 125000, status: "Shipping", date: "2026-09-22" },
      { id: "ORD-9018", customer: "Dimas Arya", item: "Patagonia Snap-T Fleece", payment: "COD", total: 290000, status: "Completed", date: "2026-09-20" },
      { id: "ORD-9017", customer: "Kevin Pratama", item: "The North Face Windbreaker", payment: "QRIS", total: 220000, status: "Completed", date: "2026-09-19" },
      { id: "ORD-9016", customer: "Rizky Ramadhan", item: "Vintage Graphic Tee 90s", payment: "QRIS", total: 95000, status: "Completed", date: "2026-09-18" }
    ];

    let adminCustomers = [
      { id: "CUST-01", name: "Budi Santoso", email: "budisantoso@gmail.com", phone: "081298765432", ordersCount: 3, status: "Active" },
      { id: "CUST-02", name: "Ahmad Fauzi", email: "ahmad.fauzi@yahoo.com", phone: "081344556677", ordersCount: 2, status: "Active" },
      { id: "CUST-03", name: "Siti Rahma", email: "sitirahma@outlook.com", phone: "085711223344", ordersCount: 1, status: "Active" },
      { id: "CUST-04", name: "Dimas Arya", email: "dimasarya@gmail.com", phone: "082199887766", ordersCount: 4, status: "Active" },
      { id: "CUST-05", name: "Kevin Pratama", email: "kevin.pratama@gmail.com", phone: "089677889900", ordersCount: 1, status: "Pending" },
      { id: "CUST-06", name: "Rizky Ramadhan", email: "rizky.ramadhan@gmail.com", phone: "087711224466", ordersCount: 2, status: "Active" }
    ];

    let userState = {
      isLoggedIn: true,
      name: "Naufal Pratama",
      email: "naufal@tahtathrift.id",
      address: "Jl. Diponegoro No. 42, Cilegon, Banten",
      phone: "081234567890"
    };

    let activeFilterSize = "ALL";
    let activeStoreSearch = "";
    let selectedDetailProductId = null;
    let chartInstanceDashboard = null;
    let chartInstanceSalesReport = null;

    function formatRupiah(amount) {
      return "Rp " + Number(amount).toLocaleString("id-ID");
    }

    /* Workspace Mode Switcher (Customer Store vs Admin Dashboard) */
    function switchWorkspaceMode(mode) {
      const storeContainer = document.getElementById('container-store');
      const adminContainer = document.getElementById('container-admin');
      const btnStore = document.getElementById('modeBtnStore');
      const btnAdmin = document.getElementById('modeBtnAdmin');
      const statusLabel = document.getElementById('quickStatusLabel');

      if (mode === 'store') {
        storeContainer.classList.remove('hidden');
        adminContainer.classList.add('hidden');
        btnStore.className = "px-4 py-1.5 rounded-lg font-bold text-xs flex items-center gap-2 bg-white text-zinc-900 shadow transition";
        btnAdmin.className = "px-4 py-1.5 rounded-lg font-bold text-xs flex items-center gap-2 text-zinc-400 hover:text-white transition";
        if (statusLabel) statusLabel.innerText = `Toko Aktif (${products.length} Pcs)`;
        navigateStore('home');
      } else {
        storeContainer.classList.add('hidden');
        adminContainer.classList.remove('hidden');
        btnAdmin.className = "px-4 py-1.5 rounded-lg font-bold text-xs flex items-center gap-2 bg-white text-zinc-900 shadow transition";
        btnStore.className = "px-4 py-1.5 rounded-lg font-bold text-xs flex items-center gap-2 text-zinc-400 hover:text-white transition";
        if (statusLabel) statusLabel.innerText = "Admin Workspace Aktif";
        switchAdminTab('dashboard');
        renderAdminAll();
      }
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* Store Navigation between subviews */
    function navigateStore(viewName) {
      const views = ['home', 'shop', 'cart', 'orders'];
      views.forEach(v => {
        const el = document.getElementById(`view-store-${v}`);
        if (el) {
          if (v === viewName) el.classList.remove('hidden');
          else el.classList.add('hidden');
        }
      });

      const navShop = document.getElementById('nav-shop');
      const navOrders = document.getElementById('nav-orders');
      if (navShop && navOrders) {
        if (viewName === 'shop') {
          navShop.className = "text-zinc-950 font-black underline underline-offset-4 transition";
          navOrders.className = "text-zinc-600 hover:text-black transition";
        } else if (viewName === 'orders') {
          navOrders.className = "text-zinc-950 font-black underline underline-offset-4 transition";
          navShop.className = "text-zinc-600 hover:text-black transition";
        } else {
          navShop.className = "text-zinc-600 hover:text-black transition";
          navOrders.className = "text-zinc-600 hover:text-black transition";
        }
      }

      window.scrollTo({ top: 0, behavior: 'smooth' });
      if (viewName === 'shop') renderStoreCatalog();
      if (viewName === 'cart') renderStoreCart();
      if (viewName === 'orders') renderStoreOrders();
    }

    /* Render Store Catalog */
    function renderStoreCatalog() {
      const container = document.getElementById('catalogGridContainer');
      const alertEmpty = document.getElementById('noProductsAlert');
      if (!container) return;

      const filtered = products.filter(p => {
        const matchQuery = p.name.toLowerCase().includes(activeStoreSearch.toLowerCase()) || p.brand.toLowerCase().includes(activeStoreSearch.toLowerCase());
        const matchSize = (activeFilterSize === 'ALL') || (p.size === activeFilterSize);
        return matchQuery && matchSize;
      });

      if (filtered.length === 0) {
        container.innerHTML = '';
        if (alertEmpty) alertEmpty.classList.remove('hidden');
        return;
      }

      if (alertEmpty) alertEmpty.classList.add('hidden');
      container.innerHTML = filtered.map(item => `
        <div class="group flex flex-col space-y-3 cursor-pointer" onclick="openProductDetailModal(${item.id})">
          <div class="relative w-full aspect-[4/5] bg-[#CBD5E1] rounded-3xl overflow-hidden shadow-sm group-hover:shadow-md transition">
            <img 
              src="${item.img}" 
              alt="${item.name}" 
              class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
              onerror="this.src='https://placehold.co/400x500/cbd5e1/475569?text=${encodeURIComponent(item.name)}'"
            />
            <button 
              onclick="event.stopPropagation(); addToStoreCart(${item.id})"
              class="absolute bottom-4 right-4 bg-zinc-900 hover:bg-black text-white p-3.5 rounded-2xl shadow-lg transition active:scale-95 flex items-center justify-center gap-1.5"
              title="Add to cart"
            >
              <i class="ph ph-shopping-bag-open text-lg"></i>
              <span class="text-xs font-bold">Add</span>
            </button>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-extrabold text-zinc-800 shadow">
              Condition : ${item.condition}
            </div>
          </div>

          <div class="pt-1 text-zinc-900">
            <h4 class="font-bold text-lg leading-snug line-clamp-1 group-hover:text-zinc-600 transition">${item.name}</h4>
            <div class="text-sm text-zinc-600 mt-1 space-y-0.5 font-medium">
              <p>Condition : ${item.condition}</p>
              <p>Size : ${item.size}</p>
              <p class="font-bold text-base text-zinc-900 pt-0.5">Price : ${formatRupiah(item.price)}</p>
            </div>
          </div>
        </div>
      `).join('');
    }

    function renderStoreFeatured() {
      const container = document.getElementById('featuredGridContainer');
      if (!container) return;
      const featured = products.slice(0, 3);
      container.innerHTML = featured.map(item => `
        <div class="group flex flex-col space-y-3 cursor-pointer" onclick="openProductDetailModal(${item.id})">
          <div class="relative w-full aspect-[4/5] bg-[#CBD5E1] rounded-3xl overflow-hidden shadow-sm group-hover:shadow-md transition">
            <img 
              src="${item.img}" 
              alt="${item.name}" 
              class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
              onerror="this.src='https://placehold.co/400x500/cbd5e1/475569?text=${encodeURIComponent(item.name)}'"
            />
            <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur-md text-white px-3 py-1 rounded-xl text-xs font-semibold">
              ${item.size} • ${item.condition}
            </div>
          </div>
          <div>
            <h4 class="font-bold text-base text-zinc-900 line-clamp-1 group-hover:text-zinc-600 transition">${item.name}</h4>
            <p class="font-extrabold text-sm text-zinc-700">${formatRupiah(item.price)}</p>
          </div>
        </div>
      `).join('');
    }

    function filterStoreBySize(size) {
      activeFilterSize = size;
      document.querySelectorAll('.store-size-btn').forEach(btn => {
        if (btn.textContent.trim() === (size === 'ALL' ? 'All' : size)) {
          btn.className = "store-size-btn px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-zinc-900 text-white transition";
        } else {
          btn.className = "store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition";
        }
      });
      renderStoreCatalog();
    }

    function handleStoreSearch(val) {
      activeStoreSearch = val;
      const shopView = document.getElementById('view-store-shop');
      if (shopView && shopView.classList.contains('hidden')) {
        navigateStore('shop');
      }
      renderStoreCatalog();
    }

    /* Cart Operations */
    function addToStoreCart(productId) {
      const prod = products.find(p => p.id === productId);
      if (!prod) return;

      const existing = cart.find(c => c.id === productId);
      if (existing) {
        existing.qty += 1;
      } else {
        cart.push({ ...prod, qty: 1 });
      }

      updateCartCounters();
      renderStoreCart();
      showNotification(`"${prod.name}" berhasil dimasukkan ke keranjang.`);
    }

    function updateCartQty(productId, delta) {
      const item = cart.find(c => c.id === productId);
      if (!item) return;

      item.qty += delta;
      if (item.qty <= 0) {
        cart = cart.filter(c => c.id !== productId);
      }
      updateCartCounters();
      renderStoreCart();
    }

    function removeCartItem(productId) {
      cart = cart.filter(c => c.id !== productId);
      updateCartCounters();
      renderStoreCart();
      showNotification("Item telah dihapus dari keranjang.");
    }

    function updateCartCounters() {
      const totalCount = cart.reduce((sum, item) => sum + item.qty, 0);
      const badge = document.getElementById('storeCartBadge');
      if (badge) badge.innerText = totalCount;
    }

    function renderStoreCart() {
      const container = document.getElementById('cartItemsContainer');
      const emptyMsg = document.getElementById('emptyCartMessage');
      if (!container) return;

      if (cart.length === 0) {
        container.innerHTML = '';
        if (emptyMsg) emptyMsg.classList.remove('hidden');
        document.getElementById('cartSubtotalText').innerText = 'Rp 0';
        document.getElementById('cartDiscountText').innerText = 'Rp 0';
        document.getElementById('cartTotalText').innerText = 'Rp 0';
        return;
      }

      if (emptyMsg) emptyMsg.classList.add('hidden');
      let subtotal = 0;
      container.innerHTML = cart.map(item => {
        const lineTotal = item.price * item.qty;
        subtotal += lineTotal;

        return `
          <div class="grid grid-cols-12 py-5 items-center gap-2">
            <div class="col-span-6 flex items-start gap-4">
              <div class="w-16 h-20 sm:w-20 sm:h-24 bg-[#CBD5E1] rounded-2xl overflow-hidden flex-shrink-0">
                <img src="${item.img}" alt="${item.name}" class="w-full h-full object-cover">
              </div>
              <div class="space-y-0.5 text-xs sm:text-sm">
                <h4 class="font-bold text-zinc-900 text-sm sm:text-base leading-tight">${item.name}</h4>
                <p class="text-zinc-600">Condition : ${item.condition}</p>
                <p class="text-zinc-600">Size : ${item.size}</p>
                <button onclick="removeCartItem(${item.id})" class="text-red-500 hover:text-red-700 text-xs font-semibold pt-1">
                  Remove
                </button>
              </div>
            </div>

            <div class="col-span-2 text-center text-xs sm:text-sm font-semibold text-zinc-800">
              ${formatRupiah(item.price)}
            </div>

            <div class="col-span-2 flex items-center justify-center space-x-1 sm:space-x-2">
              <button onclick="updateCartQty(${item.id}, -1)" class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-zinc-200 hover:bg-zinc-300 font-bold flex items-center justify-center text-sm">-</button>
              <span class="font-bold text-xs sm:text-sm w-4 sm:w-6 text-center">${item.qty}</span>
              <button onclick="updateCartQty(${item.id}, 1)" class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-zinc-200 hover:bg-zinc-300 font-bold flex items-center justify-center text-sm">+</button>
            </div>

            <div class="col-span-2 text-right font-black text-xs sm:text-sm text-zinc-900">
              ${formatRupiah(lineTotal)}
            </div>
          </div>
        `;
      }).join('');

      const discount = subtotal >= 200000 ? 15000 : 0;
      const finalTotal = Math.max(0, subtotal - discount);

      document.getElementById('cartSubtotalText').innerText = formatRupiah(subtotal);
      document.getElementById('cartDiscountText').innerText = discount > 0 ? `-${formatRupiah(discount)}` : 'Rp 0';
      document.getElementById('cartTotalText').innerText = formatRupiah(finalTotal);
    }

    function processStoreCheckout() {
      if (cart.length === 0) {
        showNotification("Keranjang Anda masih kosong!");
        return;
      }

      const method = document.getElementById('storePaymentMethod').value;
      const orderId = "ORD-" + Math.floor(1000 + Math.random() * 9000);

      cart.forEach(item => {
        const newOrder = {
          id: orderId,
          customer: userState.name,
          item: item.name,
          payment: method,
          condition: item.condition,
          size: item.size,
          price: item.price,
          qty: item.qty,
          total: item.price * item.qty,
          status: "Shipping",
          img: item.img,
          date: "2026-09-23"
        };
        shippingOrders.unshift(newOrder);
        adminOrders.unshift(newOrder);
      });

      cart = [];
      updateCartCounters();
      renderStoreCart();
      renderStoreOrders();
      renderAdminAll();

      showNotification(`Pesanan berhasil dibuat via ${method}! Mengarahkan ke Orders...`);
      setTimeout(() => {
        navigateStore('orders');
      }, 500);
    }

    function renderStoreOrders() {
      const shipList = document.getElementById('shippingListContainer');
      const compList = document.getElementById('completedListContainer');
      const shipBadge = document.getElementById('shippingBadgeCount');
      const compBadge = document.getElementById('completedBadgeCount');

      if (shipBadge) shipBadge.innerText = `${shippingOrders.length} Item`;
      if (compBadge) compBadge.innerText = `${completedOrders.length} Item`;

      if (shipList) {
        if (shippingOrders.length === 0) {
          shipList.innerHTML = `<p class="py-6 text-center text-sm text-zinc-400">Belum ada pengiriman yang sedang berjalan.</p>`;
        } else {
          shipList.innerHTML = shippingOrders.map(item => `
            <div class="grid grid-cols-12 py-4 items-center gap-2">
              <div class="col-span-6 flex items-start gap-4">
                <div class="w-14 h-16 bg-[#CBD5E1] rounded-2xl flex-shrink-0 overflow-hidden">
                  <img src="${item.img || 'https://placehold.co/120x150/cbd5e1/475569?text=THRIFT'}" alt="${item.name}" class="w-full h-full object-cover">
                </div>
                <div>
                  <h5 class="font-bold text-sm text-zinc-900">${item.item || item.name}</h5>
                  <p class="text-xs text-zinc-500">Condition : ${item.condition}</p>
                  <p class="text-xs text-zinc-500">Size : ${item.size}</p>
                  <p class="text-xs text-zinc-500">Price : ${formatRupiah(item.price)}</p>
                </div>
              </div>
              <div class="col-span-2 text-center text-xs sm:text-sm font-semibold">${formatRupiah(item.price)}</div>
              <div class="col-span-2 text-center text-xs sm:text-sm font-bold">${item.qty}</div>
              <div class="col-span-2 text-right font-black text-xs sm:text-sm text-zinc-900">${formatRupiah(item.total)}</div>
            </div>
          `).join('');
        }
      }

      if (compList) {
        if (completedOrders.length === 0) {
          compList.innerHTML = `<p class="py-6 text-center text-sm text-zinc-400">Belum ada pesanan selesai.</p>`;
        } else {
          compList.innerHTML = completedOrders.map(item => `
            <div class="grid grid-cols-12 py-4 items-center gap-2">
              <div class="col-span-6 flex items-start gap-4">
                <div class="w-14 h-16 bg-[#CBD5E1] rounded-2xl flex-shrink-0 overflow-hidden">
                  <img src="${item.img || 'https://placehold.co/120x150/cbd5e1/475569?text=THRIFT'}" alt="${item.name}" class="w-full h-full object-cover">
                </div>
                <div>
                  <h5 class="font-bold text-sm text-zinc-900">${item.item || item.name}</h5>
                  <p class="text-xs text-zinc-500">Condition : ${item.condition}</p>
                  <p class="text-xs text-zinc-500">Size : ${item.size}</p>
                  <p class="text-xs text-zinc-500">Price : ${formatRupiah(item.price)}</p>
                </div>
              </div>
              <div class="col-span-2 text-center text-xs sm:text-sm font-semibold">${formatRupiah(item.price)}</div>
              <div class="col-span-2 text-center text-xs sm:text-sm font-bold">${item.qty}</div>
              <div class="col-span-2 text-right font-black text-xs sm:text-sm text-zinc-900">${formatRupiah(item.total)}</div>
            </div>
          `).join('');
        }
      }
    }

    /* Product Detail Modal */
    function openProductDetailModal(productId) {
      const p = products.find(prod => prod.id === productId);
      if (!p) return;

      selectedDetailProductId = p.id;
      document.getElementById('detailImg').src = p.img;
      document.getElementById('detailCondBadge').innerText = `Condition : ${p.condition}`;
      document.getElementById('detailBrand').innerText = p.brand || 'VINTAGE DROP';
      document.getElementById('detailName').innerText = p.name;
      document.getElementById('detailPrice').innerText = formatRupiah(p.price);
      document.getElementById('detailSize').innerText = p.size;
      document.getElementById('detailDimension').innerText = p.dimension || '71 cm x 52 cm';
      document.getElementById('detailMaterial').innerText = p.material || '100% Cotton';
      document.getElementById('detailDefect').innerText = p.defect || 'No Minus (Clean)';
      document.getElementById('detailDescription').innerText = p.description;

      openModal('modal-product-detail');
    }

    function addFromModal() {
      if (selectedDetailProductId) {
        addToStoreCart(selectedDetailProductId);
        closeModal('modal-product-detail');
      }
    }

    function buyNowFromModal() {
      if (selectedDetailProductId) {
        addToStoreCart(selectedDetailProductId);
        closeModal('modal-product-detail');
        navigateStore('cart');
      }
    }

    /* Modals Controller */
    function openModal(id) {
      const el = document.getElementById(id);
      if (el) el.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
      const el = document.getElementById(id);
      if (el) el.classList.add('hidden');
      document.body.style.overflow = '';
    }

    function switchAuthModal(fromId, toId) {
      closeModal(fromId);
      openModal(toId);
    }

    function openProfileOrLogin() {
      if (userState.isLoggedIn) {
        document.getElementById('profNameInput').value = userState.name;
        document.getElementById('profEmailInput').value = userState.email;
        document.getElementById('profAddressInput').value = userState.address;
        openModal('modal-profile');
      } else {
        openModal('modal-login');
      }
    }

    function handleLoginSubmit(e) {
      e.preventDefault();
      const val = document.getElementById('loginIdInput').value.trim();
      userState.isLoggedIn = true;
      userState.name = val.split('@')[0] || "Thrift Member";
      userState.email = val.includes('@') ? val : `${val}@tahtathrift.id`;
      closeModal('modal-login');
      updateAuthUI();
      showNotification(`Selamat datang kembali, ${userState.name}!`);
    }

    function handleRegisterSubmit(e) {
      e.preventDefault();
      userState.isLoggedIn = true;
      userState.name = document.getElementById('regUsername').value.trim();
      userState.email = document.getElementById('regEmail').value.trim();
      userState.phone = document.getElementById('regPhone').value.trim();
      closeModal('modal-register');
      updateAuthUI();
      showNotification(`Pendaftaran akun baru sukses! Halo ${userState.name}.`);
    }

    function saveStoreProfile() {
      userState.name = document.getElementById('profNameInput').value;
      userState.email = document.getElementById('profEmailInput').value;
      userState.address = document.getElementById('profAddressInput').value;
      closeModal('modal-profile');
      updateAuthUI();
      showNotification("Perubahan profil berhasil disimpan.");
    }

    function handleStoreLogout() {
      userState.isLoggedIn = false;
      closeModal('modal-profile');
      updateAuthUI();
      showNotification("Anda telah logout dari sesi.");
    }

    function updateAuthUI() {
      const lbl = document.getElementById('userProfileLabel');
      if (lbl) {
        lbl.innerText = userState.isLoggedIn ? userState.name.split(' ')[0] : "Account";
      }
    }

    /* ================= ADMIN LOGIC & TABLES ================= */
    function switchAdminTab(tabKey) {
      const tabs = ['dashboard', 'products', 'orders', 'customers', 'sales'];
      tabs.forEach(t => {
        const sec = document.getElementById(`tab-adm-${t}`);
        const nav = document.getElementById(`nav-adm-${t}`);
        if (sec) sec.classList.add('hidden');
        if (nav) nav.classList.remove('active');
      });

      const activeSec = document.getElementById(`tab-adm-${tabKey}`);
      const activeNav = document.getElementById(`nav-adm-${tabKey}`);
      if (activeSec) activeSec.classList.remove('hidden');
      if (activeNav) activeNav.classList.add('active');

      if (tabKey === 'products') renderAdminInventoryTable(products);
      if (tabKey === 'orders') renderAdminOrdersTable(adminOrders);
      if (tabKey === 'customers') renderAdminCustomersTable(adminCustomers);
    }

    function renderAdminAll() {
      // Update KPI
      const kpiProd = document.getElementById('admKpiProducts');
      const kpiOrders = document.getElementById('admKpiOrders');
      if (kpiProd) kpiProd.innerText = products.length;
      if (kpiOrders) kpiOrders.innerText = adminOrders.length;

      renderAdminRecentOrders();
      renderAdminInventoryTable(products);
      renderAdminOrdersTable(adminOrders);
      renderAdminCustomersTable(adminCustomers);
      initAdminCharts();
    }

    function renderAdminRecentOrders() {
      const tbody = document.getElementById('admRecentOrdersTbody');
      if (!tbody) return;
      tbody.innerHTML = adminOrders.slice(0, 4).map(o => `
        <tr class="hover:bg-zinc-50">
          <td class="p-3.5 font-bold text-zinc-900">${o.id}</td>
          <td class="p-3.5 font-semibold text-zinc-800">${o.customer}</td>
          <td class="p-3.5 text-zinc-600">${o.item}</td>
          <td class="p-3.5 font-bold text-zinc-900">${formatRupiah(o.total)}</td>
          <td class="p-3.5">
            <span class="px-2.5 py-1 rounded-full text-[10px] font-black ${o.status === 'Shipping' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'}">
              ${o.status}
            </span>
          </td>
        </tr>
      `).join('');
    }

    function renderAdminInventoryTable(data) {
      const tbody = document.getElementById('admInventoryTbody');
      const badge = document.getElementById('admProductBadge');
      if (badge) badge.innerText = `${data.length} Items`;
      if (!tbody) return;

      if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-zinc-400 font-medium">Tidak ada produk ditemukan.</td></tr>`;
        return;
      }

      tbody.innerHTML = data.map(p => `
        <tr class="hover:bg-zinc-50">
          <td class="p-3.5 font-bold text-zinc-900">#${p.id}</td>
          <td class="p-3.5 font-bold text-zinc-900">${p.name}</td>
          <td class="p-3.5 text-zinc-500 font-medium">${p.category}</td>
          <td class="p-3.5 font-bold">${p.size}</td>
          <td class="p-3.5">${p.condition}</td>
          <td class="p-3.5 font-bold text-zinc-900">${formatRupiah(p.price)}</td>
          <td class="p-3.5">
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">${p.status}</span>
          </td>
          <td class="p-3.5 text-center">
            <button onclick="handleDeleteProduct(${p.id})" class="text-red-500 hover:text-red-700 font-bold text-xs p-1">Delete</button>
          </td>
        </tr>
      `).join('');
    }

    function filterAdmProducts() {
      const q = document.getElementById('admFilterProdSearch').value.toLowerCase();
      const cat = document.getElementById('admFilterProdCat').value;
      const brand = document.getElementById('admFilterProdBrand').value;

      const filtered = products.filter(p => {
        const matchName = p.name.toLowerCase().includes(q) || p.brand.toLowerCase().includes(q);
        const matchCat = cat === 'ALL' || p.category === cat;
        const matchBrand = brand === 'ALL' || p.brand.toLowerCase().includes(brand.toLowerCase());
        return matchName && matchCat && matchBrand;
      });

      renderAdminInventoryTable(filtered);
    }

    function openAddProductModal() {
      openModal('modal-add-product');
    }

    function handleSaveNewProduct(e) {
      e.preventDefault();
      const newId = products.length + 1;
      const newItem = {
        id: newId,
        name: document.getElementById('newProdName').value,
        category: document.getElementById('newProdCat').value,
        brand: document.getElementById('newProdBrand').value,
        size: document.getElementById('newProdSize').value,
        condition: document.getElementById('newProdCond').value,
        price: Number(document.getElementById('newProdPrice').value),
        dimension: "72 cm x 54 cm",
        material: "100% Vintage Cotton",
        defect: "No Minus",
        description: "Preloved curated vintage piece ready to wear.",
        img: "https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80",
        status: "In Stock"
      };

      products.unshift(newItem);
      closeModal('modal-add-product');
      renderAdminAll();
      renderStoreCatalog();
      showNotification(`Produk "${newItem.name}" berhasil ditambahkan ke inventaris dan toko!`);
    }

    function handleDeleteProduct(id) {
      products = products.filter(p => p.id !== id);
      renderAdminAll();
      renderStoreCatalog();
      showNotification(`Produk #${id} dihapus dari sistem.`);
    }

    function renderAdminOrdersTable(data) {
      const tbody = document.getElementById('admOrdersTbody');
      const badge = document.getElementById('admOrderBadge');
      if (badge) badge.innerText = `${data.length} Total`;
      if (!tbody) return;

      tbody.innerHTML = data.map(o => `
        <tr class="hover:bg-zinc-50">
          <td class="p-3.5 font-bold text-zinc-900">${o.id}</td>
          <td class="p-3.5 font-semibold text-zinc-900">${o.customer}</td>
          <td class="p-3.5 text-zinc-600">${o.item}</td>
          <td class="p-3.5 font-medium">${o.payment}</td>
          <td class="p-3.5 font-bold text-zinc-900">${formatRupiah(o.total)}</td>
          <td class="p-3.5">
            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold ${o.status === 'Shipping' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'}">
              ${o.status}
            </span>
          </td>
          <td class="p-3.5 text-center">
            <button onclick="toggleOrderStatus('${o.id}')" class="bg-zinc-900 hover:bg-black text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition">
              ${o.status === 'Shipping' ? 'Mark Completed' : 'Set Shipping'}
            </button>
          </td>
        </tr>
      `).join('');
    }

    function filterAdmOrders() {
      const q = document.getElementById('admFilterOrderSearch').value.toLowerCase();
      const pay = document.getElementById('admFilterOrderPayment').value;
      const stat = document.getElementById('admFilterOrderStatus').value;

      const filtered = adminOrders.filter(o => {
        const matchText = o.id.toLowerCase().includes(q) || o.customer.toLowerCase().includes(q) || o.item.toLowerCase().includes(q);
        const matchPay = pay === 'ALL' || o.payment === pay;
        const matchStat = stat === 'ALL' || o.status === stat;
        return matchText && matchPay && matchStat;
      });

      renderAdminOrdersTable(filtered);
    }

    function toggleOrderStatus(id) {
      const order = adminOrders.find(o => o.id === id);
      if (order) {
        order.status = order.status === 'Shipping' ? 'Completed' : 'Shipping';
        
        // sync to customer shipping/completed lists
        if (order.status === 'Completed') {
          shippingOrders = shippingOrders.filter(s => s.id !== id);
          if (!completedOrders.some(c => c.id === id)) {
            completedOrders.unshift(order);
          }
        } else {
          completedOrders = completedOrders.filter(c => c.id !== id);
          if (!shippingOrders.some(s => s.id === id)) {
            shippingOrders.unshift(order);
          }
        }

        renderAdminOrdersTable(adminOrders);
        renderAdminRecentOrders();
        renderStoreOrders();
        showNotification(`Status order ${id} diubah ke ${order.status}.`);
      }
    }

    function renderAdminCustomersTable(data) {
      const tbody = document.getElementById('admCustomersTbody');
      if (!tbody) return;
      tbody.innerHTML = data.map(c => `
        <tr class="hover:bg-zinc-50">
          <td class="p-3.5 font-bold text-zinc-900">${c.id}</td>
          <td class="p-3.5 font-semibold text-zinc-900">${c.name}</td>
          <td class="p-3.5 text-zinc-500">${c.email}</td>
          <td class="p-3.5 font-medium">${c.phone}</td>
          <td class="p-3.5 font-bold">${c.ordersCount} Orders</td>
          <td class="p-3.5">
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold ${c.status === 'Active' ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-200 text-zinc-700'}">
              ${c.status}
            </span>
          </td>
          <td class="p-3.5 text-center">
            <button onclick="showNotification('Customer: ${c.name} • Tel: ${c.phone}')" class="text-zinc-700 hover:text-black font-bold text-xs">View</button>
          </td>
        </tr>
      `).join('');
    }

    function filterAdmCustomers() {
      const q = document.getElementById('admFilterCustSearch').value.toLowerCase();
      const stat = document.getElementById('admFilterCustStatus').value;

      const filtered = adminCustomers.filter(c => {
        const matchText = c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q) || c.phone.includes(q);
        const matchStat = stat === 'ALL' || c.status === stat;
        return matchText && matchStat;
      });

      renderAdminCustomersTable(filtered);
    }

    function handleAdminLogout() {
      showNotification("Sesi admin dashboard ditutup.");
      switchWorkspaceMode('store');
    }

    /* Chart.js Initialization */
    function initAdminCharts() {
      const ctx1 = document.getElementById('admDashboardChart');
      if (ctx1 && !chartInstanceDashboard) {
        chartInstanceDashboard = new Chart(ctx1, {
          type: 'line',
          data: {
            labels: ['Mei', 'Jun', 'Jul', 'Agu', 'Sep 2026'],
            datasets: [{
              label: 'Penjualan (Rp)',
              data: [1200000, 1650000, 1900000, 2100000, 2450000],
              borderColor: '#18181b',
              backgroundColor: 'rgba(24, 24, 27, 0.08)',
              tension: 0.35,
              fill: true,
              pointBackgroundColor: '#18181b',
              pointRadius: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { ticks: { callback: v => 'Rp ' + (v / 1000) + 'k' } }
            }
          }
        });
      }

      const ctx2 = document.getElementById('admSalesReportChart');
      if (ctx2 && !chartInstanceSalesReport) {
        chartInstanceSalesReport = new Chart(ctx2, {
          type: 'bar',
          data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
              label: 'Weekly Sales',
              data: [540000, 680000, 510000, 720000],
              backgroundColor: '#52525b',
              borderRadius: 8
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
          }
        });
      }
    }

    /* Toast Notification Helper */
    let toastTimer;
    function showNotification(msg) {
      const toast = document.getElementById('toast');
      const text = document.getElementById('toast-text');
      if (!toast || !text) return;

      text.innerText = msg;
      toast.classList.remove('opacity-0', 'translate-y-24', 'pointer-events-none');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-24', 'pointer-events-none');
      }, 3200);
    }

    // Modal background dismiss listeners
    window.addEventListener('click', (e) => {
      ['modal-login', 'modal-register', 'modal-profile', 'modal-product-detail', 'modal-add-product'].forEach(id => {
        const modal = document.getElementById(id);
        if (e.target === modal) closeModal(id);
      });
    });

    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        ['modal-login', 'modal-register', 'modal-profile', 'modal-product-detail', 'modal-add-product'].forEach(id => closeModal(id));
      }
    });

    // Startup Initialization
    window.onload = function() {
      renderStoreCatalog();
      renderStoreFeatured();
      renderStoreCart();
      renderStoreOrders();
      updateCartCounters();
      updateAuthUI();
      renderAdminAll();
    };
  </script>
</body>
</html>