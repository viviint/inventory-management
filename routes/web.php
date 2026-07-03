<?php

use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard (Breeze auth handles the redirect for guests)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ------------------------------------------------------------------
// Authenticated routes
// ------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard — Admin & Manager only (enforced by DashboardController policy)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze default — keep for user profile editing)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ------------------------------------------------------------------
    // Categories — Admin & Staff only (policy enforced in controller)
    // ------------------------------------------------------------------
    Route::resource('categories', CategoryController::class)
        ->except(['show'])
        ->middleware('role:admin,staff');

    // ------------------------------------------------------------------
    // Products — all authenticated users can view; mutations require role
    // IMPORTANT: static segments (index, create) must come before
    // wildcard segments ({product}) to avoid route model binding
    // treating "create" as a product ID.
    // ------------------------------------------------------------------

    // Mutation routes (role-gated) — defined FIRST so "create" is not
    // captured by the {product} wildcard below.
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Read-only product routes — all authenticated users
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

    // ------------------------------------------------------------------
    // Borrowings — Admin & Staff only
    // ------------------------------------------------------------------
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('borrowings', BorrowingController::class)
            ->only(['index', 'create', 'store', 'show']);

        // Return a single borrowing detail line (per-item return)
        Route::post(
            'borrowings/{borrowing}/return/{detail}',
            [BorrowingController::class, 'returnItem']
        )->name('borrowings.return-item');
    });
});

// Breeze auth routes (login, register, password reset, email verification)
require __DIR__ . '/auth.php';
