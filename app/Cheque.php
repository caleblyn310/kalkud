<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    protected $connection = 'mysql3';
    
    protected $table = 'datacheck';

    protected $fillable = ['tanggal_cair','no_check','nominal','data_reimburse','kode_unit'];

    protected $dates = ['tanggal_cair'];
}
