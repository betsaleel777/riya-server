<?php

namespace App\StateMachines;

use App\Enums\ContratStatus;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class ContratStatusStateMachine extends StateMachine
{
    public function recordHistory(): bool
    {
        return false;
    }

    public function transitions(): array
    {
        return [
            '*' => '*'
        ];
    }

    public function defaultState(): ?string
    {
        return ContratStatus::UPTODATE->value;
    }
}
