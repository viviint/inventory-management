<?php

namespace App\Policies;

use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BorrowingPolicy
{
    use HandlesAuthorization;

    /**
     * Admin and Staff can view borrowing listings.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can view a borrowing detail.
     */
    public function view(User $user, Borrowing $borrowing): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can create borrowing transactions.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Admin and Staff can update borrowings (e.g. process returns).
     */
    public function update(User $user, Borrowing $borrowing): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Only Admin can delete borrowing records.
     */
    public function delete(User $user, Borrowing $borrowing): bool
    {
        return $user->isAdmin();
    }
}
