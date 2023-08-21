<?php

namespace App\StateMachines;

use App\Enums\LoyerStatus;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class LoyerStatusStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return true;
    }

    public function transitions(): array
    {
        return [
            '*' => '*'
        ];
    }

    public function defaultState(): string
    {
        return LoyerStatus::UNPAID->value;
    }
}
