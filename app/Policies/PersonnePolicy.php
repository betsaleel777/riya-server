<?php

namespace App\Policies;

use App\Enums\RolesName;
use App\Models\Personne;
use App\Models\User;

class PersonnePolicy
{

    private static function ownCheck(User $user, Personne $personne): bool
    {
        $personne->loadMissing('audit:user_id,audits.auditable_type,audits.auditable_id');
        return $personne->audit->user_id === $user->id;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Personne $personne): bool
    {
        if ($user->hasRole(RolesName::ADMIN)) {
            return true;
        }
        if ($user->hasRole(RolesName::EMPLOYEE)) {
            return self::ownCheck($user, $personne);
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(RolesName::ADMIN, RolesName::EMPLOYEE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Personne $personne): bool
    {
        if ($user->hasAnyRole(RolesName::ADMIN)) {
            return true;
        }
        if ($user->hasRole(RolesName::EMPLOYEE)) {
            return self::ownCheck($user, $personne);
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Personne $personne): bool
    {
        if ($user->hasRole(RolesName::ADMIN)) {return true;}
        if ($user->hasRole(RolesName::EMPLOYEE)) {
            return self::ownCheck($user, $personne);
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }
}
