<?php

namespace App\Models;

use App\Scopes\OrderByIdDescScope;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

/**
 * @mixin IdeHelperTypeDepense
 */
class TypeDepense extends Model implements ContractsAuditable
{
    use Auditable;

    protected $fillable = ['nom'];
    protected $dates = ['created_at'];
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByIdDescScope);
    }
}
