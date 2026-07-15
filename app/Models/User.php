<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users'; 
    protected $primaryKey = 'id_users';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'id_users', 'username', 'name', 'bypass', 'password', 'role_id', 'login_times', 'last_ip', 'user_agent', 'last_seen', 'gambar'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) { 
        });
    }

    // app/Models/User.php
    public function guru()
    {
        return $this->hasOne(Guru::class, 'users_id', 'id_users');
    }


}