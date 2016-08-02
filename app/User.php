<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Bican\Roles\Traits\HasRoleAndPermission;

use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissions;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasRoleAndPermissions
{
    use Authenticatable, CanResetPassword, HasRoleAndPermission;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $date = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at','deleted_at'
    ];

    public function company(){
        return $this->belongsToMany('App\Company','user_points')->withPivot('earned_point','company_id')->withTimestamps();
    }

    public function userPoint(){
        return $this->belongsToMany('App\Company','user_point_company')
            ->withPivot('user_id', 'post','points','point_date')->withTimestamps();

    }
}
