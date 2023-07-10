<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperAvance
 */
class Avance extends Model
{
    use HasFactory;
    protected $fillable = ['visite_id', 'mois'];
    protected $dates = ['created_at'];
    protected $casts = ['mois' => 'integer'];

    public function visite(): BelongsTo
    {
        return $this->belongsTo(Visite::class);
    }
}
