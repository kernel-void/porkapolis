<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengeluarans';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tanggal', 
        'keterangan', 
        'jumlah', 
        'deleted_at'
    ];
}
