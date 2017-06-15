<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    protected $table = 'roles';
    protected $fillable = ['role'];
    protected $primaryKey = 'id';

    public function users () {
        return $this->belongsToMany(User::class, 'users_roles_xref', 'role_id', 'user_id');
    }
}