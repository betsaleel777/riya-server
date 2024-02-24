<?php

namespace App\Policies;

use App\Enums\RolesName;
use App\Models\User;

class DepensePolicy extends FinancialPolicy
{

    public function viewPending(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }

    public function valider(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }
}
