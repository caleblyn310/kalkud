<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvNoTemp extends Model
{
    protected $table = 'inv_no_temp';

    protected $fillable = ['invoices_no','user_id'];
}
