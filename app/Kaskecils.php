<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kaskecils extends Model
{
    protected $connection = 'mysql3';

    protected $table = 'kaskecil';

    protected $fillable = ['kode_d_ger','subkode','no_bukti','deskripsi','kode_unit','nominal','tanggal_trans','status'];

    protected $dates = ['tanggal_trans'];

    public $timestamps = false;
}
