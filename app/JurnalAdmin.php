<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JurnalAdmin extends Model
{
	protected $connection = 'mysql4';
	
    protected $table = "jurnal_admin";

    //protected $primaryKey = "No_bukti";

    protected $fillable = ['No_account', 'No_bukti', 'Tanggal', 'Uraian', 'Debet', 'Kredit', 'Kontra_acc','Div'];

    protected $dates = ['Tanggal'];

    public $timestamps = false;
}
