<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RekapVABCA extends Model
{
    protected $table = 'rekap_va_bca';

    protected $fillable = ['tanggal','no_va','nominal'];

    protected $dates = ['tanggal'];
}
