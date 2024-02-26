<?php

namespace App\Policies;

use App\Traits\HasCashableAbility;
use App\Traits\HasValidableAbility;

class LoyerPolicy extends EmployeePolicy
{
    use HasValidableAbility, HasCashableAbility;
}
