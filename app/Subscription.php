<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'subscriptions';
    protected $fillable = ['company_id', 'start_date','end_date', 'trial_start','trial_end','user_id','status'];

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function subscribe(){
        return $this->belongsToMany('App\Subscription', 'pv_subscription_company')
            ->withTimestamps()
            ->withPivot('price','start_date','end_date','payment_type', 'status');

    }
}
