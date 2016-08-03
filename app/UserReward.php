<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReward extends Model
{
    protected $table = 'user_rewards';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'reward_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function reward(){
        return $this->belongsTo('App\Reward');
    }
}
