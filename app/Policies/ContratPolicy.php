<?php

namespace App\Policies;

use App\Traits\HasCancellableAbility;
use App\Traits\HasValidableAbility;

class ContratPolicy extends EmployeePolicy
{
    use HasValidableAbility, HasCancellableAbility;
}
