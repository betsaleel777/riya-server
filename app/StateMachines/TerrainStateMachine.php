<?php

namespace App\StateMachines;

use App\Enums\BienStatus;
use Asantibanez\LaravelEloquentStateMachines\StateMachines\StateMachine;

class TerrainStateMachine extends StateMachine
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

    public function defaultState(): ?string
    {
        return BienStatus::FREE->value;
    }
}
