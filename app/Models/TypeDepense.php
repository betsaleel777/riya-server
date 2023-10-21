<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeDepense extends Model
{
    protected $fillable = ['nom'];
    protected $dates = ['created_at'];
}
