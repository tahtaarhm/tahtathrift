<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class AdminPortalController extends Controller
{
    // 1. Menampilkan daftar semua produk
    public function index()
    {
        $categories=Category::all();
        $products=Product::with('category')->get();
        return view('admin',compact("categories","products"));
    }
    public function store(Request $request)
    {
        // 2. Validasi input dari form
        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:150',
            'brand'            => 'required|string|max:80',
            'size'             => 'required|in:S,M,L,XL,XXL',
            'length'           => 'nullable|integer',
            'width'            => 'nullable|integer',
            'material'         => 'nullable|string|max:100',
            'condition_rating' => 'nullable|string|max:10',
            'defect_notes'     => 'nullable|string',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi file gambar maks 2MB
            'status'           => 'required|in:in_stock,sold_out',
        ]);

        // 3. Proses upload file gambar jika ada yang diunggah
        if ($request->hasFile('image')) {
            // Menyimpan file ke folder 'public/products' di storage Laravel
            $imagePath = $request->file('image')->store('products', 'public');
            
            // Format path agar bisa dibaca dari kolom 'image_url' di database
            $validated['image_url'] = '/storage/' . $imagePath;
        }
        // 4. Simpan data ke database menggunakan Eloquent Model
        Product::create($validated);

        // 5. Redirect kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('admin.index')
                         ->with('success', 'Produk thrift baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('edit-product', compact('product', 'categories'));
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:150',
            'brand'            => 'required|string|max:80',
            'size'             => 'required|in:S,M,L,XL,XXL',
            'length'           => 'nullable|integer',
            'width'            => 'nullable|integer',
            'material'         => 'nullable|string|max:100',
            'condition_rating' => 'nullable|string|max:10',
            'defect_notes'     => 'nullable|string',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi file gambar maks 2MB
            'status'           => 'required|in:in_stock,sold_out',
        ]);

    // Upload gambar baru jika ada
    if ($request->hasFile('image')) {

        // Hapus gambar lama
        if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
           Storage::disk('public')->delete($product->image_url);
        }

        $imagePath = $request->file('image')->store('products', 'public');
            
            // Format path agar bisa dibaca dari kolom 'image_url' di database
            $validated['image_url'] = '/storage/' . $imagePath;
    }

    $product->update($validated);

    return redirect()->route('admin.index')
        ->with('success', 'Product updated successfully!');
}

    public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('admin.index')
        ->with('success', 'Product deleted successfully!');
}
}

