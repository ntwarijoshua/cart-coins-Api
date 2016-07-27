<?php

namespace App;
use Bican\Roles\Models\Role as RoleModel;

class Role extends RoleModel
{
    protected $hidden = [
        'description', 'created_at', 'updated_at','level'
    ];


    public function roles()
    {
        return $this->belongsToMany('App\user');
    }
}
