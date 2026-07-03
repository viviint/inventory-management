<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Admin and Staff can view any category listing.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can view a single category.
     */
    public function view(User $user, Category $category): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can create categories.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can update categories.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can delete categories.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }
}
