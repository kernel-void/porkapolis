<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanDetail extends Model
{
    use HasFactory;

    protected $fillable = ['pemasukan_id', 'menu_id', 'qty', 'subtotal'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
