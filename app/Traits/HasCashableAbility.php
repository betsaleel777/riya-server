<?php
namespace App\Traits;

use App\Enums\RolesName;
use App\Models\User;

trait HasCashableAbility
{
    public function encaisser(User $user): bool
    {
        return $user->hasAnyRole(RolesName::ADMIN, RolesName::EMPLOYEE);
    }
}
