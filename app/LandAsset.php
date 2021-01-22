<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LandAsset extends Model
{
    protected $table = 'landasset';

    protected $dates = ['dot'];

    public $timestamps = false;
}
