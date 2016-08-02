<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = 'user_points';
    protected $fillable = ['user_id','company_id','earned_point'];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function company(){
        return $this->belongsTo('App\Company');
    }
}
