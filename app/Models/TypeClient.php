<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

/**
 * @mixin IdeHelperTypeClient
 */
class TypeClient extends Model implements ContractsAuditable
{
    use Auditable;
    protected $fillable = ['nom'];
    protected $dates = ['created_at'];
}
