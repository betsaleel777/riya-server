<?php

namespace App\StateMachines;

use App\Enums\ValidableEntityStatus;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class ValidableEntityStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return true;
    }

    public function transitions(): array
    {
        return [
            ValidableEntityStatus::WAIT->value => ValidableEntityStatus::VALID->value
        ];
    }

    public function defaultState(): ?string
    {
        return ValidableEntityStatus::WAIT->value;
    }
}
