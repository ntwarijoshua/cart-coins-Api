<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'companies';
    protected $fillable = ['name', 'vat', 'pobox', 'zip_code', 'city', 'country', 'phone', 'website', 'user_id', 'manager_id', 'category_id'];

    public function manager(){
        return $this->belongsTo('App\User', 'manager_id');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function category(){
        return $this->belongsTo('App\CompanyCategory', 'category_id');
    }
}
