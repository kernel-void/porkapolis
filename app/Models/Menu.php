<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_menu',
        'stok',
        'harga',
        'keterangan',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function pemasukans()
    {
        return $this->hasMany(Pemasukan::class, 'menu_id', 'id');
    }
}