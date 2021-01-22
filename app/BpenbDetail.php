<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BpenbDetail extends Model
{
    protected $table = 'bpenb_detail';

    protected $fillable = ['id_bpenb','invoices_id','description','nominal','kode_d_ger'];
}
