<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coba extends Model
{    
    protected $table = 'coba';

    protected $fillable = ['idp','tgl','wkt'];

    protected $dates = ['tgl'];

    public $timestamps = false;
}
