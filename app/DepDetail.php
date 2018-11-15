<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepDetail extends Model
{
    protected $table = 'dep_detail';

    protected $fillable = ['id_dep','id_inven'];
}
