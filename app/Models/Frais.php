<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

/**
 * @mixin IdeHelperFrais
 */
class Frais extends Model implements ContractsAuditable
{
    use Auditable;
    protected $fillable = ['visite_id', 'mois'];
    protected $dates = ['created_at'];
    protected $casts = ['mois' => 'integer'];

    public function visite(): BelongsTo
    {
        return $this->belongsTo(Visite::class);
    }
}
