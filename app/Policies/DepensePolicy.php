<?php

namespace App\Policies;

use App\Traits\HasValidableAbility;

class DepensePolicy extends FinancialPolicy
{
    use HasValidableAbility;
}
