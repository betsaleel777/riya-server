<?php
namespace App\Traits;

use App\Enums\RolesName;
use App\Models\User;

trait HasCancellableAbility
{
    public function resilier(User $user): bool
    {
        return $user->hasRole(RolesName::ADMIN);
    }
}
