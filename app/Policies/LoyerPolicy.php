<?php

namespace App\Policies;

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
}
