<?php

namespace App\Policies;

use App\Enums\RolesName;
use App\Models\Terrain;
use App\Models\User;

class TerrainPolicy
{

    private static function ownCheck(User $user, Terrain $terrain): bool
    {
        $terrain->loadMissing('audit:user_id,audits.auditable_type,audits.auditable_id');
        return $terrain->audit->user_id === $user->id;
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
    public function view(User $user, Terrain $terrain): bool
    {
        if ($user->hasRole(RolesName::ADMIN)) {
            return true;
        }
        if ($user->hasRole(RolesName::EMPLOYEE)) {
            return self::ownCheck($user, $terrain);
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
    public function update(User $user, Terrain $terrain): bool
    {
        if ($user->hasAnyRole(RolesName::ADMIN)) {
            return true;
        }
        if ($user->hasRole(RolesName::EMPLOYEE)) {
            return self::ownCheck($user, $terrain);
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Terrain $terrain): bool
    {
        if ($user->hasRole(RolesName::ADMIN)) {return true;}
        if ($user->hasRole(RolesName::EMPLOYEE)) {
            return self::ownCheck($user, $terrain);
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
