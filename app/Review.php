<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    protected $table = 'reviews';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['company_id','user_id','star_id','comment'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function star(){
        return $this->belongsTo('App\Star');
    }
}
