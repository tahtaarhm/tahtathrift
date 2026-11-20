<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>T-THRIFT - Admin Dashboard</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Phosphor Icons CDN -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    body { background-color: #f1f3f5; font-family: 'Inter', sans-serif; }
    .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #a1a1aa; border-radius: 9999px; }
    .admin-nav-item.active { font-weight: 800; color: #09090b; background-color: rgba(0, 0, 0, 0.08); }
  </style>
</head>
<body class="text-zinc-900 min-h-screen flex flex-col p-4 sm:p-6 lg:p-8 antialiased">

  <!-- TOP HEADER -->
  <header class="max-w-7xl w-full mx-auto mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <h1 class="text-3xl font-black tracking-tight text-zinc-900">T-THRIFT</h1>
      <span class="text-[11px] uppercase font-black tracking-widest bg-zinc-900 text-white px-3 py-1 rounded-md">Admin Portal</span>
    </div>
    <a href="{{ route('customer.index') }}" class="text-xs font-bold text-zinc-700 hover:text-black flex items-center gap-1.5 bg-white px-3.5 py-2 rounded-xl shadow-sm transition">
      <i class="ph ph-shopping-bag-open text-base"></i>
      <span>Lihat Web Toko</span>
    </a>
  </header>

  <!-- MAIN WRAPPER -->
  <div class="max-w-7xl w-full mx-auto flex-1 flex flex-col lg:flex-row gap-6 items-start">
    
    <!-- SIDEBAR -->
    <aside class="w-full lg:w-64 flex flex-col justify-between shrink-0 gap-6">
      <div class="bg-[#dcdde1] rounded-3xl p-5 shadow-sm space-y-4">
        <nav class="space-y-2">
          <button onclick="switchAdminTab('dashboard')" id="nav-dashboard" class="admin-nav-item active w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 p-2.5 rounded-2xl">
            <i class="ph ph-laptop text-lg"></i><span>Dashboard</span>
          </button>
          <button onclick="switchAdminTab('products')" id="nav-products" class="admin-nav-item w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 p-2.5 rounded-2xl">
            <i class="ph ph-package text-lg"></i><span>Product & Inventory</span>
          </button>
          <button onclick="switchAdminTab('orders')" id="nav-orders" class="admin-nav-item w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 p-2.5 rounded-2xl">
            <i class="ph ph-shopping-cart text-lg"></i><span>Order Management</span>
          </button>
          <button onclick="switchAdminTab('customers')" id="nav-customers" class="admin-nav-item w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 p-2.5 rounded-2xl">
            <i class="ph ph-users text-lg"></i><span>Customer Management</span>
          </button>
          <button onclick="switchAdminTab('sales')" id="nav-sales" class="admin-nav-item w-full flex items-center gap-3.5 text-left text-sm font-semibold text-zinc-700 p-2.5 rounded-2xl">
            <i class="ph ph-trend-up text-lg"></i><span>Sales Report</span>
          </button>
        </nav>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
          <a href="{{route('logout')}}"onclick="event.preventDefault();
            this.closest('form').submit();" class="w-full bg-[#dcdde1] hover:bg-zinc-300 text-zinc-800 font-bold py-3.5 px-4 rounded-2xl text-sm transition text-center shadow-sm">
              Log Out
          </a>
      </form>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 w-[90%] bg-[#dcdde1] rounded-3xl p-6 sm:p-8 shadow-sm min-h-[400px] mx-auto">
      
      <!-- TAB 1: DASHBOARD -->
      <section id="tab-dashboard" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between">
            <h3 class="text-base font-bold">Total Products</h3>
            <span class="text-3xl font-black">20</span>
          </div>
          <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between">
            <h3 class="text-base font-bold">Total Orders</h3>
            <span class="text-3xl font-black">18</span>
          </div>
          <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between">
            <h3 class="text-base font-bold">Total Customers</h3>
            <span class="text-3xl font-black">12</span>
          </div>
          <div class="bg-[#8b919a] text-white p-6 rounded-2xl h-36 flex flex-col justify-between">
            <h3 class="text-base font-bold">Total Sales</h3>
            <span class="text-2xl font-black">Rp 2.450.000</span>
          </div>
        </div>

      </section>

      <!-- TAB 2: PRODUCTS -->
      <section id="tab-products" class="hidden space-y-5">
        <div class="flex justify-between items-center">
          <input type="text" placeholder="Search Product..." class="bg-[#8b919a] text-white placeholder-zinc-200 rounded-xl px-4 py-2.5 text-sm outline-none">
          <button onclick="openAddProductModal()" class="bg-zinc-900 hover:bg-black text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow">+ Add Product</button>
        </div>
        <div class="bg-[#8b919a] rounded-2xl p-5 space-y-3">
          <h3 class="text-white font-bold">Daftar Produk & Inventaris</h3>
          <div class="bg-white rounded-xl overflow-hidden p-4">
            @if (session('success'))
    <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl mb-5 text-sm font-semibold">
        <i class="ph ph-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-5 text-sm font-semibold">
        <i class="ph ph-warning-circle mr-2"></i>
        {{ session('error') }}
    </div>
@endif
           
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b text-zinc-500 uppercase">
                        <th class="p-2">Name</th>
                        <th class="p-2">Brand</th>
                        <th class="p-2">Size</th>
                        <th class="p-2">Price</th>
                        <th class="p-2">Status</th>
                        <th class="p-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $c)
                    <tr class="border-b">
                        <td class="p-2">{{ $c->name }}</td>
                        <td class="p-2">{{ $c->brand }}</td>
                        <td class="p-2">{{ $c->size }}</td>
                        <td class="p-2 font-bold">{{ $c->price }}</td>

                        <td class="p-2">
                            <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-bold">
                                {{ $c->status }}
                            </span>
                        </td>

                        <td class="p-2">
                            <div class="flex items-center justify-center gap-2">

                                {{-- Edit Button --}}

                                <a href="{{ route('admin.product.edit', $c->id) }}"
                                  class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md font-bold">
                                    Edit
                                </a>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin.product.delete', $c->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md font-bold">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

          </div>
        </div>
      </section>

      <!-- TAB 3: ORDERS -->
      <section id="tab-orders" class="hidden space-y-5">
        <div class="bg-[#8b919a] rounded-2xl p-5 space-y-3">
          <h3 class="text-white font-bold">Order Management</h3>
          <div class="bg-white rounded-xl p-4 text-xs">Daftar pesanan masuk pelanggan aktif.</div>
        </div>
      </section>

      <!-- TAB 4: CUSTOMERS -->
      <section id="tab-customers" class="hidden space-y-5">
        <div class="bg-[#8b919a] rounded-2xl p-5 space-y-3">
          <h3 class="text-white font-bold">Customer Management</h3>
          <div class="bg-white rounded-xl p-4 text-xs">Daftar pelanggan terdaftar.</div>
        </div>
      </section>

      <!-- TAB 5: SALES REPORT -->
      <section id="tab-sales" class="hidden space-y-6">
        <div class="bg-[#8b919a] text-white p-6 rounded-2xl space-y-4">
          <h3 class="text-base font-bold">Sales Report & Analytics</h3>
          <div class="h-64 w-full bg-white rounded-xl p-4"><canvas id="reportChart"></canvas></div>
        </div>
      </section>

    </main>
  </div>

  <!-- MODAL: ADD PRODUCT FORM (Mencocokkan Schema Database) -->
  <div id="modal-add-product" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto custom-scroll">
      <div class="flex items-center justify-between border-b border-zinc-200 pb-3">
        <h3 class="text-xl font-bold text-zinc-900">Add New Thrift Product</h3>
        <button onclick="closeAddProductModal()" class="text-zinc-400 hover:text-black text-2xl font-bold">&times;</button>
      </div>

      <form method="POST" enctype="multipart/form-data" class="space-y-4 text-xs" action="{{route('admin.product.store')}}">
        @csrf
      
        <!-- Nama Produk -->
        <div>
          <label class="block font-bold text-zinc-700 mb-1">Product Name (max: 150 char)</label>
          <input type="text" name="name" maxlength="150" required placeholder="Contoh: Nike Center Swoosh Vintage Tee" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-800">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Kategori ID (Foreign Key) -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Category</label>
            <select name="category_id" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm font-semibold">
             @foreach ($categories as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->name }}
                                </option>
                            @endforeach
            </select>
          </div>
          <!-- Brand -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Brand (max: 80 char)</label>
            <input type="text" name="brand" maxlength="80" required placeholder="Nike, Adidas, 3Second..." class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm">
          </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
          <!-- Size (Enum) -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Size</label>
            <select name="size" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm font-semibold">
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
              <option value="XXL">XXL</option>
            </select>
          </div>
          <!-- Condition Rating -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Condition Rating</label>
            <input type="text" name="condition_rating" placeholder="95%" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm">
          </div>
          <!-- Price -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Price (Rp)</label>
            <input type="number" step="0.01" name="price" placeholder="95000" required class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <!-- Length & Width (Dimensi P x L) -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Length x Width (cm)</label>
            <div class="grid grid-cols-2 gap-2">
              <input type="number" name="length" placeholder="Panjang (cm)" class="bg-zinc-100 rounded-xl px-3 py-2 text-sm">
              <input type="number" name="width" placeholder="Lebar / P2P (cm)" class="bg-zinc-100 rounded-xl px-3 py-2 text-sm">
            </div>
          </div>
          <!-- Status (Enum) -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Status Stock</label>
            <select name="status" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm font-semibold">
              <option value="in_stock">In Stock</option>
              <option value="sold_out">Sold Out</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Material -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Material (max: 100 char)</label>
            <input type="text" name="material" maxlength="100" placeholder="100% Heavy Cotton" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm">
          </div>
          <!-- Product Image File Upload -->
          <div>
            <label class="block font-bold text-zinc-700 mb-1">Product Image File</label>
            <input type="file" name="image" accept="image/*" required class="w-full bg-zinc-100 rounded-xl px-3 py-1.5 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-900 file:text-white hover:file:bg-black cursor-pointer">
          </div>
        </div>

        <!-- Defect Notes -->
        <div>
          <label class="block font-bold text-zinc-700 mb-1">Defect Notes (Catatan Minus)</label>
          <input type="text" name="defect_notes" placeholder="Contoh: Sedikit noda tipis di bagian bawah (hampir tidak kelihatan)" class="w-full bg-zinc-100 rounded-xl px-3.5 py-2.5 text-sm">
        </div>

        <!-- Description -->
        <div>
          <label class="block font-bold text-zinc-700 mb-1">Description</label>
          <textarea name="description" rows="3" placeholder="Ceritakan detail bahan atau keunikan item thrift ini..." class="w-full bg-zinc-100 rounded-xl p-3 text-sm"></textarea>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-4 flex justify-end gap-3">
          <button type="button" onclick="closeAddProductModal()" class="px-5 py-2.5 rounded-xl bg-zinc-200 text-zinc-700 font-bold hover:bg-zinc-300">Batal</button>
          <button type="submit" class="px-6 py-2.5 rounded-xl bg-zinc-900 text-white font-bold hover:bg-black transition">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function switchAdminTab(tabKey) {
      ['dashboard', 'products', 'orders', 'customers', 'sales'].forEach(t => {
        document.getElementById(`tab-${t}`).classList.toggle('hidden', t !== tabKey);
        document.getElementById(`nav-${t}`).classList.toggle('active', t === tabKey);
      });
    }

    function openAddProductModal() {
      document.getElementById('modal-add-product').classList.remove('hidden');
    }

    function closeAddProductModal() {
      document.getElementById('modal-add-product').classList.add('hidden');
    }

    window.onload = () => {
      new Chart(document.getElementById('adminChart'), {
        type: 'line',
        data: { labels: ['Mei', 'Jun', 'Jul', 'Agu', 'Sep'], datasets: [{ data: [12, 16, 19, 21, 24.5], borderColor: '#18181b', tension: 0.3 }] },
        options: { responsive: true, maintainAspectRatio: false }
      });
    };
  </script>
</body>
</html>