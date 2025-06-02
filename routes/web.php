<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariationController;

// ==========================
// Guest Routes (Login/Register)
// ==========================
Route::get('/', fn() => view('auth.login'));
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ==========================
// Authenticated User Routes
// ==========================
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==========================
// Admin Panel Routes
// ==========================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    // Categories
    Route::get('/categories/tree', [CategoryController::class, 'getTree'])->name('categories.tree');
    Route::resource('categories', CategoryController::class);

    // Products
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::resource('products', ProductController::class);
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update'); // Explicit if needed
    Route::post('/products', [ProductController::class, 'store'])->name('products.store'); // Optional if using resource

    // Product Images
    // Route::delete('/admin/product-image/{id}', [ProductImageController::class, 'destroy'])->name('product-image.destroy');
    Route::delete('/admin/product-images/{id}', [ProductImageController::class, 'destroy'])->name('admin.product-images.destroy');
    Route::delete('/admin/products/delete-image/{image}', [ProductController::class, 'deleteImage']);

    // Product Variations
    // Add routes for variations here when needed
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::resource('products', ProductController::class);

        // Optional: Separate route to delete a variation via AJAX
        Route::delete('product-variations/{id}', [ProductController::class, 'destroyVariation'])->name('products.variation.destroy');
    });
});
