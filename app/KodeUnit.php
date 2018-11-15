<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodeUnit extends Model
{
    protected $table = 'kodeunit';

    public function inventory()
    {
    	return $this->belongsToMany('App\Inventory', 'locinven','id_inven','id_kodeunit');
    }
}
