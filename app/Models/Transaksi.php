<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['kategori_id', 'nominal', 'tanggal', 'keterangan'];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
