<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Kaskecil;

use Illuminate\Validation\Rule;
use Validator;
use Session;
use Response;

use Illuminate\Support\Facades\Auth;

use PDF;
use App;
use Carbon\Carbon;

class DatareimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return $this->funcsdr();
    }

    public function edit($namafile)
    {
        if(substr($namafile,-1) != "z") {
            //$kaskecil_list = DB::select(DB::raw("select * from $namafile order by no_bukti, tanggal_trans, id"))->paginate(10);
            $kaskecil_list = DB::table($namafile)->get();
            $totalreim = $kaskecil_list->sum('nominal');
            $kaskecil_list = DB::table($namafile)->orderBy('no_bukti')->orderBy('tanggal_trans')->orderBy('id')->get();
            DB::table('simpanfile')->where('namafile','LIKE',$namafile.'%')->update(['mode'=>'edit']);
            return view('datareim.datareimedit',compact('kaskecil_list','namafile','totalreim'));
        }
        else {
            $t = explode('z',$namafile);
            $kaskcl = DB::select(DB::raw("select * from $t[0] WHERE id = $t[1]"));
            $kaskecil = $kaskcl[0];$namafile = $t[0];
            return view('datareim.editreim',compact('kaskecil','namafile'));
        }
    }

    public function update($id,Request $request)
    {
        $input = $request->all();
        $kaskecil = Kaskecil::findOrFail($id);
        //dd($kaskecil->id);
        $validator = Validator::make($input, [
            'kode_d_ger' => 'required|min:10',
            'no_bukti' => 'required',
            'tanggal_trans' => 'required|before_or_equal:'.date('Y-m-d')],[
            'kode_d_ger.required' => 'Kode D-Ger belum di input',
            'tanggal_trans.before_or_equal' => 'Tanggal transaksi harus sebelum atau pada hari ini',
        ]);

        if ($validator->fails())
        { return redirect('datareim/'.$input['nv'].'z'.$kaskecil->id.'z/edit')->withInput()->withErrors($validator); }

        $kaskecil->update($request->all());
        Session::flash('flash_message', 'Data berhasil diupdate.');
        return redirect('datareim/'.$input['nv'].'/edit');
    }

    public function destroy($id,Request $request)
    {
        $kaskecil = Kaskecil::findOrFail($id);
        $kaskecil->delete();
        Session::flash('flash_message','Data berhasil dihapus');
        Session::flash('penting',true);
        return redirect('datareim/'.$request->nv.'/edit');
    }

    public function funcsdr()
    {
        $reimburse = DB::table('simpanfile')->where('kode_unit',Auth::user()->kode_unit)->whereNotIn('mode',['final','cheque'])->get();
        //dd(count($reimburse));
        //(count($reimburse)>0) ? $reimburse = $reimburse[0]->namafile . '|' . $reimburse[0]->mode : $reimburse = '' ;
        return Response::json($reimburse)->withCallback();
        //return view('datareim.datareim',compact('tempf'));
    }
}
