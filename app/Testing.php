<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testing extends Model
{
    protected $table = 'table_coba';

    protected $primaryKey = 'table_coba_id';

    protected $fillable = ['nama','alamat','umur'];
}
