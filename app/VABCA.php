<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VABCA extends Model
{
    protected $table = 'vabca';

    protected $fillable = ['nova','nominal','dot','kode_unit'];

    protected $dates = ['dot'];
}
