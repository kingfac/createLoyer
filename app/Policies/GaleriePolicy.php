<?php

namespace App\Policies;

use App\Models\Galerie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GaleriePolicy
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
    public function view(User $user, Galerie $galerie): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo('create Galerie')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Galerie $galerie): bool
    {
        if($user->hasPermissionTo('edit Galerie')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Galerie $galerie): bool
    {
        if($user->hasPermissionTo('delete Galerie')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Galerie $galerie): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Galerie $galerie): bool
    {
        //
    }
}
