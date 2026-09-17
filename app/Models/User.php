<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, SoftDeletes;
 
    protected $primaryKey = 'id_users';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $guard_name = 'web';

    protected $fillable = [
        'id_users', 'username', 'name', 'password', 'role_id', 'login_times', 'last_ip', 'user_agent', 'last_seen', 'gambar'
    ];

    protected $hidden = ['password'];
}