<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan_barang';

    public function daftarbarang() {
    	return $this->hasMany('App\DaftarBarang','satuan');
    }
}
