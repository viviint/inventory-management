<?php

namespace App\Providers;

use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Product;
use App\Policies\BorrowingPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model policies
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Borrowing::class, BorrowingPolicy::class);

        // Gate for dashboard (Admin + Manager only)
        Gate::define('view-dashboard', function ($user) {
            return $user->isAdmin() || $user->isManager();
        });

        // Gate for user management (Admin only)
        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
    }
}

