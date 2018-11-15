<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use Response;
use App\JurnalAdmin;
use App\Kaskecil;

class AutoController extends Controller
{

    public function autocomplete(){
        header("Access-Control-Allow-Origin: *");
        $term = Input::get('term');

        $results = array();

        // this will query the users table matching the first name or last name.
        // modify this accordingly as per your requirements
        if (Auth::user()->kode_unit != 0 AND Auth::user()->kode_unit != 100) {
        $queries = DB::table('acc_num')->where(function ($query) { $query->where('status',Auth::user()->kode_unit)->orWhere('status',50);})
            ->where(function ($query) use ($term) {
                $query->where('desc', 'LIKE', '%'. str_replace(" ", "%", $term) .'%')
                ->orWhere('accnum_id','LIKE','%'. str_replace(" ","%",$term).'%');
            })->take(10)->orderBy('accnum_id')->get();}
        else {
        //$queries = DB::table('acc_num')->where('status','!=',200)->where('desc', 'LIKE', '%'. str_replace(" ", "%", $term) .'%')
        //->orWhere('accnum_id','LIKE','%'. str_replace(" ","%",$term).'%')->take(10)->orderBy('accnum_id')->get();
            $queries = DB::select(DB::raw("select * from acc_num WHERE status != 200 AND (acc_num.desc LIKE '%".str_replace(" ", "%", $term)."%' OR accnum_id LIKE '%".str_replace(" ", "%", $term)."%') ORDER BY accnum_id LIMIT 10"));
        }

        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->accnum_id, 'value' => $query->desc .' | '. $query->accnum_id ];
        }
        return Response::json($results)->withCallback();
    }

    /*public function autocomplete2(){
        header("Access-Control-Allow-Origin: *");
        $term = Input::get('term');

        $results = array();

        // this will query the users table matching the first name or last name.
        // modify this accordingly as per your requirements

        $queries = DB::table('acc_num')
            ->where('accnum_id', 'LIKE', '%'. str_replace(" ", "%", $term) .'%')
            ->take(10)->orderBy('accnum_id')->get();

        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->accnum_id, 'value' => $query->desc .' | '. $query->accnum_id ];
        }
        return Response::json($results)->withCallback();
    }*/

    public function autosearch() {
        header("Access-Control-Allow-Origin: *");
        $term = Input::get('term');

        $results = array();

        // this will query the users table matching the first name or last name.
        // modify this accordingly as per your requirements

        $queries = JurnalAdmin::where('No_bukti', 'LIKE', '%'. str_replace(" ", "%", $term) .'%')
            ->take(10)->orderBy('Tanggal','desc')->get();

        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->id, 'value' => $query->No_bukti ];
        }
        return Response::json($results)->withCallback();
    }

    public function searchkaskecil() {
        header("Access-Control-Allow-Origin: *");
        $term = Input::get('term');

        $results = array();

        // this will query the users table matching the first name or last name.
        // modify this accordingly as per your requirements

        $queries = Kaskecil::where('deskripsi','LIKE', '%'. str_replace(" ", "%", $term) .'%')
            ->where('kode_unit','=',Auth::user()->kode_unit)->take(15)->orderBy('tanggal_trans','desc')->get();


        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->id, 'value' => $query->deskripsi . ' | ' . $query->tanggal_trans->format('Y-m-d') ];
        }
        return Response::json($results)->withCallback();
    }

    public function autocheck()
    {
        $term = Input::get('term');

    }
}