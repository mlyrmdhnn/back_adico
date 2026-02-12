<?php

namespace App\Policies;

use App\Models\DataCustomer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataCustomer $dataCustomer): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataCustomer $dataCustomer): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        // return $user->role === 'manager';
        // return false;
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DataCustomer $dataCustomer): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DataCustomer $dataCustomer): bool
    {
        return false;
    }
}
