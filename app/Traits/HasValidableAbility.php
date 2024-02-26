<?php
namespace App\Traits;

use App\Enums\RolesName;
use App\Models\User;

trait HasValidableAbility
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
