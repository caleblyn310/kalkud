<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vabdiall extends Model
{
    protected $table = 'vabdiall';

    protected $fillable = ['nova','transdate','transcode','nominal','description'];

    protected $dates = ['transdate'];
}
