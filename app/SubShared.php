<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubShared extends Model
{
    protected $table = 'sub_shared_posts';
    protected $fillable = ['date', 'user_id', 'shared_post_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function shared(){
        return $this->belongsTo('App\SharedPost');
    }
}
