<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    protected $table = 'stars';
    protected $fillable = ['name', 'details'];

}
