<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finger extends Model
{
	protected $connection = "mysql5";

    protected $table = "finger";
}
