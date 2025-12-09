<?php

namespace App\Policies;

use App\Enums\RolesName;
use App\Models\User;
use App\Traits\HasCashableAbility;
use App\Traits\HasValidableAbility;

class LoyerPolicy extends EmployeePolicy
{
    use HasValidableAbility, HasCashableAbility;
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return true;
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasAnyRole(RolesName::ADMIN);
    }
}
