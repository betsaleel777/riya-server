<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

/**
 * @mixin IdeHelperProprietaire
 */
class Proprietaire extends Model implements ContractsAuditable
{
    use Auditable;
    protected $fillable = ['code', 'nom_complet', 'telephone', 'email', 'cni'];

    protected $dates = ['created_at'];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'PRO' . Str::upper(Str::random(5));
    }
}
