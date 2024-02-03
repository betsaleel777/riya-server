<?php

namespace App\StateMachines;

use App\Enums\PayableStatus;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class LoyerStatusStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return true;
    }

    public function transitions(): array
    {
        return ['*' => '*'];
    }

    public function defaultState(): string
    {
        return PayableStatus::UNPAID->value;
    }
}
