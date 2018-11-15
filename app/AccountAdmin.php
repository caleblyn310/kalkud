<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountAdmin extends Model
{
    protected $connection = 'mysql2';
	
    protected $table = "account_admin";
}
