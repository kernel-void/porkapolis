<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemasukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemasukans'; // Nama tabel

    protected $primaryKey = 'id';

    protected $fillable = [
        'menu_id',
        'tanggal', 
        'keterangan', 
        'qty', 
        'total', 
        'deleted_at'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id')->withTrashed();
    }
    
    protected $casts = [
        'qty' => 'integer',
    ];
    
}
