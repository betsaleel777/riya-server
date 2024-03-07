<?php

namespace App\Models;

use App\Enums\ValidableEntityStatus;
use App\StateMachines\ValidableEntityStateMachine;
use App\Traits\HasCountDateFilterScope;
use App\Traits\HasCurrentYearScope;
use App\Traits\HasResponsible;
use App\Traits\HasValidableEntityScope;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

/**
 * @mixin IdeHelperPaiement
 */
class Paiement extends Model implements ContractsAuditable
{
    use Auditable, HasStateMachines, HasValidableEntityScope, HasCountDateFilterScope, HasCurrentYearScope, HasResponsible;
    protected $fillable = ['montant', 'code'];
    protected $dates = ['created_at'];
    protected $casts = ['montant' => 'integer'];

    public $stateMachines = [
        'status' => ValidableEntityStateMachine::class,
    ];

    public function genererCode(string $prefix): void
    {
        $this->attributes['code'] = $prefix . Str::upper(Str::random(3)) . Carbon::now()->format('y');
    }

    public function setValide(): void
    {
        $this->status()->transitionTo(ValidableEntityStatus::VALID->value);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function dette(): MorphOne
    {
        return $this->morphOne(Dette::class, 'origine');
    }
}
