<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inven extends Model
{
    protected $table = 'inventori';

    protected $fillable = ['tahun','unit','sumber_dana','klasifikasi','tipe_brg','bhn_merk','lokasi'];
}
