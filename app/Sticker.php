<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sticker extends Model
{
    use SoftDeletes;
    protected $table = 'stickers';
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'details','user_id','payable','price'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
