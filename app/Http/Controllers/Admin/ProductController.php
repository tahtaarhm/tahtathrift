<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. Menampilkan daftar semua produk
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    // 2. Menampilkan form tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // 3. Menyimpan produk baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:150',
            'brand'       => 'required|string',
            'size'        => 'required|in:S,M,L,XL,XXL',
            'price'       => 'required|numeric|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    // 4. Menampilkan detail 1 produk
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    // 5. Menampilkan form edit produk
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 6. Memperbarui data produk di database
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:150',
            'brand'       => 'required|string',
            'size'        => 'required|in:S,M,L,XL,XXL',
            'price'       => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Data produk berhasil diperbarui!');
    }

    // 7. Menghapus produk dari database
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}