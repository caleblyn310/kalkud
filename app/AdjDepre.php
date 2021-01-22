<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdjDepre extends Model
{
    protected $table = 'adjInvenDepre';

    protected $fillable = ['id_inven','adjDescription','oldNBV','newNBV','adjDepreciation','doa'];

    protected $dates = ['doa'];
}
