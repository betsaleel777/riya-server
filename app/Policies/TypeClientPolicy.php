<?php

namespace App\Policies;

use App\Enums\RolesName;
use App\Models\User;

class TypeClientPolicy extends EmployeePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }
}
