<?php

namespace App\Policies;

use App\Models\TypeOccu;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TypeOccuPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->hasRole(['Gerant','Boss'])){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TypeOccu $typeOccu): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo('create Type Occus')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TypeOccu $typeOccu): bool
    {
        if($user->hasPermissionTo('edit Type Occus')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TypeOccu $typeOccu): bool
    {
        if($user->hasPermissionTo('delete Type Occus')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TypeOccu $typeOccu): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TypeOccu $typeOccu): bool
    {
        //
    }
}
