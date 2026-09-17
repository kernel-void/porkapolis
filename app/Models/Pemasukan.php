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
        'tanggal', 
        'keterangan', 
        'total',
    ];

    public function details()
    {
        return $this->hasMany(PemasukanDetail::class);
    }
    
}
