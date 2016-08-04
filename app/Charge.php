<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    protected $table = 'charges';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['amount','currency','customer','source',
        'description','metadata','capture','statement_description',
        'receipt_email','application_fee','shipping'];

}
