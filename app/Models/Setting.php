<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id_settings';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected $fillable = [
        'nama_aplikasi',
        'ikon_sidebar',
        'tema',
        'footer',
        'logo',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_settings = Str::uuid();
        });
    }

}
