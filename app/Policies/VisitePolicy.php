<?php

namespace App\Policies;

use App\Traits\HasValidableAbility;

class VisitePolicy extends EmployeePolicy
{
    use HasValidableAbility;
}
