<?php

namespace App\Models;

use App\Enums\ValidableEntityStatus;
use App\StateMachines\ValidableEntityStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * @mixin IdeHelperPaiement
 */
class Paiement extends Model
{
    use HasFactory, HasStateMachines, HasEagerLimit;
    protected $fillable = ['achat_id', 'montant', 'code'];
    protected $dates = ['created_at'];
    protected $casts = ['montant' => 'integer'];

    public $stateMachines = [
        'status' => ValidableEntityStateMachine::class
    ];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'PAI' . Str::upper(Str::random(3)) . Carbon::now()->format('y');
    }

    public function achat(): BelongsTo
    {
        return $this->belongsTo(Achat::class);
    }
}
