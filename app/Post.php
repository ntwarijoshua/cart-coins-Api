<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    protected $table = 'posts';
    protected $dates = ['deleted_at'];
    use SoftDeletes;
    protected $fillable = ['company_id', 'title', 'details', 'attached_file', 'published_at', 'shared_number'];

    public function company(){
        return $this->belongsTo('App\Company');
    }
}
