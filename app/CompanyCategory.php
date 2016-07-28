<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyCategory extends Model
{
    use SoftDeletes;
    protected $date = ['deleted_at'];
    protected $table = 'company_categories';
    protected $fillable = ['category_name', 'description'];
}
