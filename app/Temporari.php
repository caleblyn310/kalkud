<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temporari extends Model
{
	protected $connection = 'mysql4';
	
    protected $table = "temporari";

    protected $fillable = ['no_bkt_temp'];

    public $timestamps = false;
}
