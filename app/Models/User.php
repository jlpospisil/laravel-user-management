<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = true;
    protected $table = 'users';
    protected $fillable = ['name','email', 'password'];
    protected $primaryKey = 'id';
    protected $hidden = ['remember_token', 'password', 'api_key'];
    protected $dates = ['created_at','updated_at'];

    public function roles () {
        return $this->belongsToMany(Role::class, 'users_roles_xref', 'user_id', 'role_id');
    }

    public function hasRole (String $role=null) {
        return (!$role || $this->roles()->where('role',$role)->first());
    }
}