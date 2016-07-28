<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'plans';
    protected $fillable = ['company_id', 'points', 'equivalent'];

    public function company(){
        return $this->belongsTo('App\Company');
    }
}
