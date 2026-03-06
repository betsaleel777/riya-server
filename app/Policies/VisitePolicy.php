<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\HasValidableAbility;

class VisitePolicy extends EmployeePolicy
{
    use HasValidableAbility;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return true;
    }
}
