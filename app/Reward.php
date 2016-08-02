<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
    protected $table = 'rewards';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['company_id', 'reward_title', 'reward_details', 'photo', 'points', 'equivalents'];


    public function company(){
        return $this->belongsTo('App\Company');
    }
}
