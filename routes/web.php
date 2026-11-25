<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdminPortalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::redirect('/', '/login');
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','role:admin'])->group(function () {
Route::get("/customer",[HomeController::class,"index"] )->name("customer.index");

Route::get("/admin",[AdminPortalController::class,"index"] )->name("admin.index");
Route::post("/admin/product/store",[AdminPortalController::class,"store"] )->name("admin.product.store");
Route::delete("/admin/product/{product}/delete",[AdminPortalController::class,"destroy"] )->name("admin.product.delete");
Route::get('/admin/products/{id}/edit', [AdminPortalController::class, 'edit'])
    ->name('admin.product.edit');
Route::put('/admin/products/{id}', [AdminPortalController::class, 'update'])
    ->name('admin.product.update');

    

    
});

Route::middleware('auth')->group(function () {
Route::get("/customer",[HomeController::class,"index"] )->name("customer.index");

Route::get("/cart",[CartController::class,"index"] )->name("cart.index");
Route::post("/cart/add",[CartController::class,"add"] )->name("cart.add");
Route::delete("/cart/{id}/remove",[CartController::class,"remove"] )->name("cart.remove");
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.process');
Route::post('/checkout/paid', [CheckoutController::class, 'markPaidFromClient'])->name('checkout.paid');
});
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');
require __DIR__.'/auth.php';
