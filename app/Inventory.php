<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = ['jenis_aktiva','quantity','harga','total','tanggal_beli','maks','penyusutan','id_cat'];

    protected $dates = ['tanggal_beli'];

    public function kodeunit()
    {
    	return $this->belongsToMany('App\KodeUnit','locinven','id_inven','id_kodeunit')->withTimeStamps();
    }

    public function getLocinvenAttribute()
    {
    	return $this->kodeunit->pluck('id')->toArray();
    }
}
