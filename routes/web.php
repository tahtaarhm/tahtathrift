<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdminPortalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

Route::redirect('/', '/login');
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([
    'auth',
    'role:admin'])->group(function () {
Route::get("/customer",[HomeController::class,"index"] )->name("customer.index");

Route::get("/admin",[AdminPortalController::class,"index"] )->name("admin.index");
Route::post("/admin/product/store",[AdminPortalController::class,"store"] )->name("admin.product.store");
Route::delete("/admin/product/{product}/delete",[AdminPortalController::class,"destroy"] )->name("admin.product.delete");
Route::get('/admin/products/{id}/edit', [AdminPortalController::class, 'edit'])
    ->name('admin.product.edit');
Route::put('/admin/products/{id}', [AdminPortalController::class, 'update'])
    ->name('admin.product.update');
});

Route::middleware([
    'auth',
    'role:customer'])->group(function () {
Route::get("/customer",[HomeController::class,"index"] )->name("customer.index");

Route::get("/cart",[CartController::class,"index"] )->name("cart.index");

});

require __DIR__.'/auth.php';
