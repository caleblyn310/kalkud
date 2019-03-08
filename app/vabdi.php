<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vabdi extends Model
{
    protected $table = 'vabdi';

    protected $fillable = ['nova','trfdate','nominal','description'];

    protected $dates = ['trfdate'];
}
