<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>T-THRIFT - Customer Storefront</title>

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

  <!-- MAIN CONTAINER -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">

    <!-- HOMEPAGE -->
    <section id="view-store-home" class="space-y-12">
      <div class="relative rounded-3xl overflow-hidden bg-[#CBD5E1] min-h-[380px] sm:min-h-[440px] flex items-end p-8 sm:p-14 shadow-sm border border-zinc-300/50">
        <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=1600&q=80" alt="Hero" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-40 filter contrast-125"/>
        <div class="relative z-10 max-w-xl">
          <span class="inline-block px-3 py-1 bg-zinc-900 text-white text-xs font-black tracking-widest uppercase rounded-full mb-3">Sustainable Fashion</span>
          <h1 class="text-3xl sm:text-5xl font-black text-zinc-900 tracking-tight leading-tight mb-4">FIND YOUR SIGNATURE VINTAGE PIECE.</h1>
          <button onclick="navigateStore('shop')" class="bg-[#71717A] hover:bg-[#52525B] text-white font-extrabold text-base tracking-wider px-8 py-3.5 rounded-2xl shadow-lg transition uppercase">
            ORDER NOW !
          </button>
        </div>
      </div>

      <div class="py-6 text-center max-w-3xl mx-auto px-4">
        <h2 class="text-3xl sm:text-4xl font-black text-zinc-900 mb-4 tracking-tight">Hello, We Are T-Thrift</h2>
        <p class="text-base sm:text-lg text-zinc-600 leading-relaxed">TahtaThrift is your destination for quality preloved apparel combining style, affordability, and sustainability.</p>
      </div>

      <div>
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl sm:text-2xl font-black tracking-tight text-zinc-900">Featured Curation</h3>
          <button onclick="navigateStore('shop')" class="text-sm font-bold text-zinc-600 hover:text-black flex items-center gap-1">Explore All &rarr;</button>
        </div>
        <div id="featuredGridContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8"></div>
      </div>
    </section>

    <!-- SHOP / CATALOG -->
    <section id="view-store-shop" class="hidden space-y-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-zinc-200 gap-4">
        <div>
          <h2 class="text-3xl font-black text-zinc-900 tracking-tight">All Thrift Collections</h2>
          <p class="text-sm text-zinc-500 mt-1">Koleksi kaos & jaket vintage terkurasi siap pakai.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <span class="text-xs font-bold uppercase text-zinc-500 mr-1">Filter Size:</span>
          <button onclick="filterStoreBySize('ALL')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-zinc-900 text-white transition">All</button>
          <button onclick="filterStoreBySize('S')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition">S</button>
          <button onclick="filterStoreBySize('M')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition">M</button>
          <button onclick="filterStoreBySize('L')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition">L</button>
          <button onclick="filterStoreBySize('XL')" class="store-size-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800 hover:bg-zinc-300 transition">XL</button>
        </div>
      </div>
      <div id="catalogGridContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"></div>
    </section>

    <!-- CART -->
    <section id="view-store-cart" class="hidden space-y-8">
      <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <h2 class="text-3xl font-black text-zinc-900 tracking-tight">My Cart</h2>
        <button onclick="navigateStore('shop')" class="text-sm font-bold text-zinc-600 hover:text-black">&larr; Continue Shopping</button>
      </div>

      <div class="bg-white/80 backdrop-blur rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-sm">
        <div class="grid grid-cols-12 pb-4 border-b-2 border-zinc-900 font-bold text-zinc-800 text-sm">
          <div class="col-span-6">Product</div>
          <div class="col-span-2 text-center">Price</div>
          <div class="col-span-2 text-center">Qty</div>
          <div class="col-span-2 text-right">Total</div>
        </div>

        <div id="cartItemsContainer" class="divide-y divide-zinc-200 min-h-[160px]"></div>

        <div class="mt-8 pt-6 border-t border-zinc-200">
          <div class="max-w-xl ml-auto bg-[#D1D5DB] rounded-3xl p-6 sm:p-8 space-y-5 text-zinc-900 shadow-inner">
            <div>
              <label class="block font-bold text-base mb-2">Select Payment Method</label>
              <select id="storePaymentMethod" class="w-full bg-white font-semibold py-3 px-4 rounded-xl border border-zinc-300 text-sm cursor-pointer">
                <option value="QRIS">⚪ QRIS (GoPay, OVO, Dana, BCA)</option>
                <option value="Bank BCA">Bank Transfer - BCA VA</option>
                <option value="Bank Mandiri">Bank Transfer - Mandiri Bill</option>
                <option value="COD">Cash On Delivery (COD)</option>
              </select>
            </div>
            <div class="space-y-2 pt-2 border-t border-zinc-400 text-sm font-semibold">
              <div class="flex justify-between"><span>Subtotal</span><span id="cartSubtotalText">Rp 0</span></div>
              <div class="flex justify-between"><span>Discount</span><span id="cartDiscountText">Rp 0</span></div>
              <div class="flex justify-between text-lg font-black pt-2 border-t-2 border-zinc-900"><span>Total</span><span id="cartTotalText">Rp 0</span></div>
            </div>
            <div class="pt-4 text-center">
              <button onclick="processStoreCheckout()" class="bg-[#71717A] hover:bg-[#52525B] text-white font-bold px-10 py-3.5 rounded-2xl shadow uppercase text-base">Check Out</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ORDERS -->
    <section id="view-store-orders" class="hidden space-y-10">
      <div>
        <h2 class="text-3xl font-black text-zinc-900 tracking-tight">Order Status & History</h2>
        <p class="text-zinc-500 text-sm mt-1">Lacak kiriman paket thrift Anda.</p>
      </div>

      <div class="space-y-4">
        <h3 class="text-2xl font-black text-zinc-900">Shipping</h3>
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-sm">
          <div id="shippingListContainer" class="divide-y divide-zinc-100"></div>
        </div>
      </div>

      <div class="space-y-4">
        <h3 class="text-2xl font-black text-zinc-900">Order completed</h3>
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-sm">
          <div id="completedListContainer" class="divide-y divide-zinc-100"></div>
        </div>
      </div>
    </section>

  </main>

  <footer class="mt-auto border-t border-zinc-200 bg-white/95 py-6 text-center">
    <p class="font-black tracking-widest text-base">T-THRIFT</p>
    <p class="text-xs text-zinc-500 font-medium">TahtaThrift &copy; 2026. Customer Storefront.</p>
  </footer>

  <!-- MODALS -->
  <div id="modal-product-detail" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 hidden">
    <div class="relative bg-white rounded-[32px] w-full max-w-2xl p-6 sm:p-8 shadow-2xl">
      <button onclick="closeModal('modal-product-detail')" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-zinc-100 hover:bg-zinc-200 flex items-center justify-center text-zinc-600 font-bold">&times;</button>
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
          <div class="bg-zinc-50 rounded-2xl p-4 text-xs sm:text-sm space-y-1">
            <div class="flex justify-between"><span class="text-zinc-500">Size</span><span id="detailSize" class="font-bold"></span></div>
            <div class="flex justify-between"><span class="text-zinc-500">Dimensi</span><span id="detailDimension" class="font-bold"></span></div>
            <div class="flex justify-between"><span class="text-zinc-500">Bahan</span><span id="detailMaterial" class="font-bold"></span></div>
            <div class="flex justify-between"><span class="text-zinc-500">Minus</span><span id="detailDefect" class="font-bold text-emerald-600"></span></div>
          </div>
          <p id="detailDescription" class="text-xs text-zinc-600"></p>
          <div class="flex gap-3">
            <button onclick="addFromModal()" class="flex-1 bg-zinc-900 hover:bg-black text-white font-bold py-3 px-4 rounded-2xl text-sm">Add to Cart</button>
            <button onclick="buyNowFromModal()" class="flex-1 bg-[#71717A] hover:bg-[#52525B] text-white font-bold py-3 px-4 rounded-2xl text-sm uppercase">Buy Now</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let products = [
      { id: 1, name: "T-Shirt Outmost Brown", category: "T-Shirt", brand: "Outmost", condition: "95%", size: "M", price: 85000, dimension: "71 cm x 52 cm", material: "100% Heavy Cotton 20s", defect: "No Minus", description: "Kaos vintage tone earthy brown yang netral.", img: "https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80" },
      { id: 2, name: "3Second Women's T-Shirt", category: "T-Shirt", brand: "3Second", condition: "90%", size: "M", price: 70000, dimension: "66 cm x 48 cm", material: "Combed Cotton 30s", defect: "Pemakaian wajar", description: "Kaos casual wanita adem.", img: "https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80" },
      { id: 3, name: "3Second Men's Jacket Crewneck", category: "Crewneck", brand: "3Second", condition: "99%", size: "M", price: 100000, dimension: "70 cm x 55 cm", material: "Baby Terry Fleece", defect: "Like New", description: "Crewneck sweater dengan karet rib kencang.", img: "https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80" }
    ];

    let cart = [];
    let shippingOrders = [];
    let completedOrders = [];
    let activeFilterSize = "ALL";
    let activeStoreSearch = "";
    let selectedDetailProductId = null;

    function formatRupiah(amount) {
      return "Rp " + Number(amount).toLocaleString("id-ID");
    }

    function navigateStore(viewName) {
      ['home', 'shop', 'cart', 'orders'].forEach(v => {
        const el = document.getElementById(`view-store-${v}`);
        if (el) el.classList.toggle('hidden', v !== viewName);
      });
      window.scrollTo({ top: 0, behavior: 'smooth' });
      if (viewName === 'shop') renderStoreCatalog();
      if (viewName === 'cart') renderStoreCart();
      if (viewName === 'orders') renderStoreOrders();
    }

    function renderStoreCatalog() {
      const container = document.getElementById('catalogGridContainer');
      if (!container) return;
      const filtered = products.filter(p => {
        const matchQuery = p.name.toLowerCase().includes(activeStoreSearch.toLowerCase());
        const matchSize = (activeFilterSize === 'ALL') || (p.size === activeFilterSize);
        return matchQuery && matchSize;
      });
      container.innerHTML = filtered.map(item => `
        <div class="group flex flex-col space-y-3 cursor-pointer" onclick="openProductDetailModal(${item.id})">
          <div class="relative aspect-[4/5] bg-[#CBD5E1] rounded-3xl overflow-hidden shadow-sm">
            <img src="${item.img}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-105 transition">
            <button onclick="event.stopPropagation(); addToStoreCart(${item.id})" class="absolute bottom-4 right-4 bg-zinc-900 text-white p-3 rounded-2xl shadow">Add</button>
            <div class="absolute top-4 left-4 bg-white/90 px-3 py-1 rounded-full text-xs font-extrabold">Condition : ${item.condition}</div>
          </div>
          <div>
            <h4 class="font-bold text-lg">${item.name}</h4>
            <p class="text-sm text-zinc-600">Size : ${item.size}</p>
            <p class="font-bold text-base text-zinc-900">${formatRupiah(item.price)}</p>
          </div>
        </div>
      `).join('');
    }

    function renderStoreFeatured() {
      const container = document.getElementById('featuredGridContainer');
      if (!container) return;
      container.innerHTML = products.slice(0, 3).map(item => `
        <div class="group flex flex-col space-y-3 cursor-pointer" onclick="openProductDetailModal(${item.id})">
          <div class="relative aspect-[4/5] bg-[#CBD5E1] rounded-3xl overflow-hidden">
            <img src="${item.img}" alt="${item.name}" class="w-full h-full object-cover">
          </div>
          <div>
            <h4 class="font-bold text-base">${item.name}</h4>
            <p class="font-extrabold text-sm">${formatRupiah(item.price)}</p>
          </div>
        </div>
      `).join('');
    }

    function filterStoreBySize(size) {
      activeFilterSize = size;
      renderStoreCatalog();
    }

    function handleStoreSearch(val) {
      activeStoreSearch = val;
      navigateStore('shop');
      renderStoreCatalog();
    }

    function addToStoreCart(id) {
      const prod = products.find(p => p.id === id);
      if (!prod) return;
      const exist = cart.find(c => c.id === id);
      if (exist) exist.qty++; else cart.push({ ...prod, qty: 1 });
      document.getElementById('storeCartBadge').innerText = cart.reduce((a, b) => a + b.qty, 0);
      showNotification(`"${prod.name}" dimasukkan ke keranjang.`);
    }

    function renderStoreCart() {
      const container = document.getElementById('cartItemsContainer');
      if (!container) return;
      if (cart.length === 0) {
        container.innerHTML = `<p class="py-10 text-center text-zinc-400">Keranjang kosong</p>`;
        document.getElementById('cartSubtotalText').innerText = 'Rp 0';
        document.getElementById('cartTotalText').innerText = 'Rp 0';
        return;
      }
      let sub = 0;
      container.innerHTML = cart.map(i => {
        sub += i.price * i.qty;
        return `<div class="grid grid-cols-12 py-4 items-center">
          <div class="col-span-6 font-bold">${i.name}</div>
          <div class="col-span-2 text-center">${formatRupiah(i.price)}</div>
          <div class="col-span-2 text-center">${i.qty}</div>
          <div class="col-span-2 text-right font-black">${formatRupiah(i.price * i.qty)}</div>
        </div>`;
      }).join('');
      document.getElementById('cartSubtotalText').innerText = formatRupiah(sub);
      document.getElementById('cartTotalText').innerText = formatRupiah(sub);
    }

    function processStoreCheckout() {
      if (cart.length === 0) { showNotification("Keranjang kosong!"); return; }
      cart.forEach(i => shippingOrders.push({...i, id: "ORD-" + Math.floor(1000+Math.random()*9000), status: "Shipping"}));
      cart = [];
      document.getElementById('storeCartBadge').innerText = 0;
      navigateStore('orders');
      showNotification("Checkout berhasil!");
    }

    function renderStoreOrders() {
      document.getElementById('shippingListContainer').innerHTML = shippingOrders.map(o => `<div class="py-3 font-bold">${o.id} - ${o.name} (${o.status})</div>`).join('') || '<p class="text-zinc-400 py-4">Tidak ada pengiriman.</p>';
      document.getElementById('completedListContainer').innerHTML = completedOrders.map(o => `<div class="py-3 font-bold">${o.id} - ${o.name} (Completed)</div>`).join('') || '<p class="text-zinc-400 py-4">Tidak ada riwayat.</p>';
    }

    function openProductDetailModal(id) {
      const p = products.find(x => x.id === id);
      if (!p) return;
      selectedDetailProductId = id;
      document.getElementById('detailImg').src = p.img;
      document.getElementById('detailCondBadge').innerText = `Condition : ${p.condition}`;
      document.getElementById('detailBrand').innerText = p.brand;
      document.getElementById('detailName').innerText = p.name;
      document.getElementById('detailPrice').innerText = formatRupiah(p.price);
      document.getElementById('detailSize').innerText = p.size;
      document.getElementById('detailDimension').innerText = p.dimension;
      document.getElementById('detailMaterial').innerText = p.material;
      document.getElementById('detailDefect').innerText = p.defect;
      document.getElementById('detailDescription').innerText = p.description;
      document.getElementById('modal-product-detail').classList.remove('hidden');
    }

    function addFromModal() {
      if (selectedDetailProductId) { addToStoreCart(selectedDetailProductId); closeModal('modal-product-detail'); }
    }
    function buyNowFromModal() {
      if (selectedDetailProductId) { addToStoreCart(selectedDetailProductId); closeModal('modal-product-detail'); navigateStore('cart'); }
    }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function openProfileOrLogin() { showNotification("Fitur akun pembeli aktif."); }

    function showNotification(msg) {
      const t = document.getElementById('toast');
      document.getElementById('toast-text').innerText = msg;
      t.classList.remove('opacity-0', 'translate-y-24', 'pointer-events-none');
      setTimeout(() => t.classList.add('opacity-0', 'translate-y-24', 'pointer-events-none'), 3000);
    }

    window.onload = () => { renderStoreFeatured(); renderStoreCatalog(); };
  </script>
</body>
</html>