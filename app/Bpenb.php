<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bpenb extends Model
{
    protected $table = 'bpenb';

    protected $fillable = ['invoices_no','bank','pay_from','given_by','dot','nominal','aiw','memo'];

    protected $dates = ['dot'];
}
