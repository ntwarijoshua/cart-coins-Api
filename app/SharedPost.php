<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedPost extends Model
{
    use SoftDeletes;
    protected $table = 'shared_posts';
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'post_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function post(){
        return $this->belongsTo('App\Post');
    }
}
