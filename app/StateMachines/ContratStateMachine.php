<?php

namespace App\StateMachines;

use App\Enums\ContratState;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class ContratStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return true;
    }

    public function transitions(): array
    {
        return [
            ContratState::USING->value => ContratState::ABORTED->value
        ];
    }

    public function defaultState(): ?string
    {
        return ContratState::USING->value;
    }
}
