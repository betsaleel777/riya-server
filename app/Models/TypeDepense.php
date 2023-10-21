<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTypeDepense
 */
class TypeDepense extends Model
{
    protected $fillable = ['nom'];
    protected $dates = ['created_at'];
}
