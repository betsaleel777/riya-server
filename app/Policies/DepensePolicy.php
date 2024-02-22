<?php

namespace App\Policies;

use App\Enums\RolesName;
use App\Models\Depense;
use App\Models\User;

class DepensePolicy
{

    private static function ownCheck(User $user, Depense $depense): bool
    {
        $depense->loadMissing('audit:user_id,audits.auditable_type,audits.auditable_id');
        return $depense->audit->user_id === $user->id;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(RolesName::ADMIN, RolesName::FINANCIAL);
    }

    public function viewPending(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Depense $depense): bool
    {
        if ($user->hasRole(RolesName::ADMIN)) {
            return true;
        }
        if ($user->hasRole(RolesName::FINANCIAL)) {
            return self::ownCheck($user, $depense);
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(RolesName::ADMIN, RolesName::FINANCIAL);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Depense $depense): bool
    {
        if ($user->hasAnyRole(RolesName::ADMIN)) {
            return true;
        }
        if ($user->hasRole(RolesName::FINANCIAL)) {
            return self::ownCheck($user, $depense);
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Depense $depense): bool
    {
        if ($user->hasRole(RolesName::ADMIN)) {return true;}
        if ($user->hasRole(RolesName::FINANCIAL)) {
            return self::ownCheck($user, $depense);
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Depense $depense): bool
    {
        return $user->hasRole(RolesName::ADMIN);
        if ($user->hasRole(RolesName::FINANCIAL)) {
            return self::ownCheck($user, $depense);
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }

    public function valider(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }
}
