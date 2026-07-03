<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Admin, Staff, and Manager can view product listings.
     */
    public function viewAny(User $user): bool
    {
        return true; // all authenticated users can browse products
    }

    /**
     * All authenticated users can view a product detail.
     */
    public function view(User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Admin and Staff can create products.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can update products.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can delete products.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }
}
