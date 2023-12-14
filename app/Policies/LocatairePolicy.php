<?php

namespace App\Policies;

use App\Models\Locataire;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LocatairePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->hasRole(['Gerant','Boss','Caissier'])){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Locataire $locataire): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo('create Locataire')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Locataire $locataire): bool
    {
        if($user->hasPermissionTo('edit Locataire')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Locataire $locataire): bool
    {
        if($user->hasPermissionTo('delete Locataire')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Locataire $locataire): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Locataire $locataire): bool
    {
        //
    }
}
