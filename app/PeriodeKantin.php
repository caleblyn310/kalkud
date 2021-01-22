<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodeKantin extends Model
{
    protected $table = 'periode_kantin';

    protected $fillable = ['periode','status'];
}
