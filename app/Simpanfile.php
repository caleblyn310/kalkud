<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Simpanfile extends Model
{
    protected $table = 'simpanfile';

    protected $fillable = ['namafile','kode_unit','nominal'];
}
