<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'invoices';

    protected $fillable = ['invoices_no','bank','pay_to','give_to','dot','nominal','aiw','memo'];

    protected $dates = ['dot'];
}
