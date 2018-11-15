<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicesDetail extends Model
{
    protected $table = 'invoices_detail';

    protected $fillable = ['invoices_id','description','nominal','kode_d_ger'];
}
