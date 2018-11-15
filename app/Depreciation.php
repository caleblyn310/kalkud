<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depreciation extends Model
{
    protected $table = 'depreciation';

    protected $fillable = ['id_dep,period'];

    protected $dates = ['period'];
}
