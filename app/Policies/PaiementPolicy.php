<?php

namespace App\Policies;

use App\Enums\RolesName;
use App\Models\User;

class PaiementPolicy extends FinancialPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function valider(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }
}
