<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTypeClient
 */
class TypeClient extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    protected $dates = ['created_at'];
}
