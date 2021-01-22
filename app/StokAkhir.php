<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokAkhir extends Model
{
    protected $table = 'stok_akhir';

    protected $fillable = ['id_periodesak','id_barang','qty_terpakai','qty_akhir','hpp'];
}
