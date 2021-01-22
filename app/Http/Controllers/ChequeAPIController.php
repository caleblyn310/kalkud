<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cheque;

class ChequeAPIController extends Controller
{
    public function index()
    {
    	$data = Cheque::where([['mode','!=','print'],['id','>',10]])->orderBy('tanggal_cair','desc')->get();
    	return response()->json($data);
    }

    public function show($id)
    {
    	$data = Cheque::findOrFail($id);
    	return response()->json($data);
    }

    public function getKaskecil($tblview)
    {
        $data = DB::select(DB::raw('select * from '. $tblview .' ORDER BY no_bukti, tanggal_trans, id'));
        return response()->json($data);
    }
}
