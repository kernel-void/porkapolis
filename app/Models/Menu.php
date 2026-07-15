<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menus';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_menu',
        'stok',
        'harga',
        'keterangan',
        'deleted_at',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function pemasukans()
    {
        return $this->hasMany(Pemasukan::class, 'menu_id', 'id');
    }
}