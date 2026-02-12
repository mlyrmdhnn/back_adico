<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);
    }

    public function countNotif(User $user) : bool
    {
        return $user->role === 'salesman';
    }

    public function createSupervisor(User $user) : bool
    {
        return in_array($user->role, ['manager', 'supervisor'], true);
    }

    public function deleteSupervisor(User $user) : bool
    {
        return $user->role === 'manager';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    public function updateStatus(User $user) : bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    public function editOmset(User $user) : bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    public function editPaymentMethod(User $user) :bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    public function createPaymentMethod(User $user) :bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    public function createAttendance(User $user) :bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    public function crateTargetSalesman(User $user) : bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    public function inputHariKerja(User $user) : bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    public function editProffile(User $user, User $model) : bool
    {
        return $user->id === $model->id;
    }

    public function deleteSalesman (User $user) : bool
    {
        // return $user->role === 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // return $user->role == 'manager';
        return in_array($user->role, ['manager', 'supervisor'], true);

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
