<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodeSak extends Model
{
    protected $table = 'periode_sak';

    protected $fillable = ['bulan','tahun','status'];
}
