
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - T-THRIFT</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f1f3f5;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="text-zinc-900 min-h-screen p-4 sm:p-6 lg:p-8 antialiased">

    <div class="max-w-5xl mx-auto">

        <!-- HEADER -->
        <header class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black tracking-tight">T-THRIFT</h1>
                <span class="text-[11px] uppercase font-black tracking-widest bg-zinc-900 text-white px-3 py-1 rounded-md">
                    Admin Portal
                </span>
            </div>

            <a href="{{ route('admin.index') }}"
               class="bg-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:bg-zinc-100 transition flex items-center gap-2">
                <i class="ph ph-arrow-left text-lg"></i>
                Back to Dashboard
            </a>
        </header>

        <!-- MAIN CONTENT -->
        <main class="bg-[#dcdde1] rounded-3xl p-6 sm:p-8 shadow-sm">

            <div class="mb-6">
                <h2 class="text-2xl font-black">Edit Product</h2>
                <p class="text-sm text-zinc-600 mt-1">
                    Update product information and inventory details.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 sm:p-8">

                <!-- ERROR MESSAGE -->
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-5 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- EDIT FORM -->
                <form action="{{ route('admin.product.update', $product->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-5">

                    @csrf
                    @method('PUT')

                    <!-- PRODUCT NAME -->
                    <div>
                        <label class="block font-bold text-zinc-700 mb-2 text-sm">
                            Product Name
                        </label>

                        <input type="text"
                               name="name"
                               maxlength="150"
                               value="{{ old('name', $product->name) }}"
                               required
                               class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-zinc-800">
                    </div>

                    <!-- CATEGORY & BRAND -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block font-bold text-zinc-700 mb-2 text-sm">
                                Category
                            </label>

                            <select name="category_id"
                                    class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm font-semibold">

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-2 text-sm">
                                Brand
                            </label>

                            <input type="text"
                                   name="brand"
                                   maxlength="80"
                                   value="{{ old('brand', $product->brand) }}"
                                   required
                                   class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm outline-none">
                        </div>

                    </div>

                    <!-- SIZE, CONDITION, PRICE -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                        <div>
                            <label class="block font-bold text-zinc-700 mb-2 text-sm">
                                Size
                            </label>

                            <select name="size"
                                    class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm font-semibold">

                                @foreach (['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                    <option value="{{ $size }}"
                                        {{ old('size', $product->size) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-2 text-sm">
                                Condition Rating
                            </label>

                            <input type="text"
                                   name="condition_rating"
                                   value="{{ old('condition_rating', $product->condition_rating) }}"
                                   class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-2 text-sm">
                                Price (Rp)
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   value="{{ old('price', $product->price) }}"
                                   required
                                   class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm">
                        </div>

                    </div>

                    <!-- LENGTH, WIDTH, STATUS -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                        <div>
                            <label class="block font-bold text-zinc-700 mb-2 text-sm">
                                Length (cm)
                            </label>

                            <input type="number"
                                   name="length"
                                   value="{{ old('length', $product->length) }}"
                                   class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-2 text-sm">
                                Width (cm)
                            </label>

                            <input type="number"
                                   name="width"
                                   value="{{ old('width', $product->width) }}"
                                   class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-2 text-sm">
                                Status Stock
                            </label>

                            <select name="status"
                                    class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm font-semibold">

                                <option value="in_stock"
                                    {{ old('status', $product->status) == 'in_stock' ? 'selected' : '' }}>
                                    In Stock
                                </option>

                                <option value="sold_out"
                                    {{ old('status', $product->status) == 'sold_out' ? 'selected' : '' }}>
                                    Sold Out
                                </option>

                            </select>
                        </div>

                    </div>

                    <!-- MATERIAL -->
                    <div>
                        <label class="block font-bold text-zinc-700 mb-2 text-sm">
                            Material
                        </label>

                        <input type="text"
                               name="material"
                               maxlength="100"
                               value="{{ old('material', $product->material) }}"
                               class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm">
                    </div>

                    <!-- CURRENT IMAGE -->
                    <div>
                        <label class="block font-bold text-zinc-700 mb-2 text-sm">
                            Current Product Image
                        </label>

                        @if ($product->image_url)
                            <img src="{{ asset($product->image_url) }}"
                                 alt="{{ $product->name }}"
                                 class="w-40 h-40 object-cover rounded-xl border mb-3">
                        @else
                            <p class="text-sm text-zinc-500 mb-3">
                                No product image available.
                            </p>
                        @endif

                        <label class="block font-bold text-zinc-700 mb-2 text-sm">
                            Change Product Image (Optional)
                        </label>

                        <input type="file"
                               name="image"
                               accept="image/*"
                               class="w-full bg-zinc-100 rounded-xl px-3 py-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-900 file:text-white cursor-pointer">
                    </div>

                    <!-- DEFECT NOTES -->
                    <div>
                        <label class="block font-bold text-zinc-700 mb-2 text-sm">
                            Defect Notes
                        </label>

                        <input type="text"
                               name="defect_notes"
                               value="{{ old('defect_notes', $product->defect_notes) }}"
                               class="w-full bg-zinc-100 rounded-xl px-4 py-3 text-sm">
                    </div>

                    <!-- DESCRIPTION -->
                    <div>
                        <label class="block font-bold text-zinc-700 mb-2 text-sm">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="w-full bg-zinc-100 rounded-xl p-4 text-sm outline-none focus:ring-2 focus:ring-zinc-800">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="pt-5 border-t flex flex-col sm:flex-row justify-end gap-3">

                        <a href="{{ route('admin.index') }}"
                           class="px-6 py-3 rounded-xl bg-zinc-200 text-zinc-700 font-bold text-sm text-center hover:bg-zinc-300 transition">
                            Cancel
                        </a>

                        <button type="submit"
                                class="px-6 py-3 rounded-xl bg-zinc-900 text-white font-bold text-sm hover:bg-black transition">
                            <i class="ph ph-floppy-disk mr-1"></i>
                            Update Product
                        </button>

                    </div>

                </form>

            </div>
        </main>

    </div>

</body>
</html>
