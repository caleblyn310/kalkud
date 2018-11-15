<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusAccountAdmin extends Model
{
    protected $connection = 'mysql2';
	
    protected $table = "status_account_admin";

    protected $fillable = ['debet_awal','kredit_awal','debet','kredit','debet_akhir','kredit_akhir'];

    public $timestamps = false;
}
