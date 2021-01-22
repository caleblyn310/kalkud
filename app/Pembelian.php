<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
	protected $table = 'pembelian';

    protected $fillable = ['invoices_no','supplier','dot','nominal'];

    protected $dates = ['dot'];
}
