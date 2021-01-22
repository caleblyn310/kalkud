<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DaftarBarang extends Model
{
    protected $table = 'daftar_barang';

    protected $fillable = ['nama_barang','kategori','satuan','stok','hpp'];

    public function satuan() {
    	return $this->belongsTo('App\Satuan','satuan');
    }
}
