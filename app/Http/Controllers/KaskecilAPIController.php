<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kaskecil;

class KaskecilAPIController extends Controller
{
    public function index()
    {
    	$data = Kaskecil::where('status','bu')->orderBy('kode_unit','desc')->orderBy('tanggal_trans','desc')->get();
    	return response()->json($data);
    }
}
