<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    protected $table = 'pembelian_detail';

    protected $fillable = ['id_pembelian','id_barang','qty1','qty2','hrg_tot','hrg_sat','diskon'];
}
