<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;
use Session;
use Carbon\Carbon;
use App\Cheque;
use App\Simpanfile;

class ChequeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    { $this->middleware('auth');}

    public function index()
    {
        if(Auth::user()->kode_unit == 100) {
            //$check_list = Cheque::where([['mode','!=','print'],['id','>',10]])->orderBy('tanggal_cair','desc')->get();
            //dd($check_list);
            $client = new \GuzzleHttp\Client();
            $request = $client->get('https://kalamkudus.or.id/kaskecil/api/cheque');
            $check_list = json_decode($request->getBody()->getContents());
            
            return view('check.index',compact('check_list'));
        }
        else {
            $check_list = Cheque::where([['kode_unit',Auth::user()->kode_unit],['id','>','10']])->orderBy('created_at','desc')->get();
            return view('check.index',compact('check_list'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tempf = Simpanfile::where('kode_unit',Auth::user()->kode_unit)->get();

        if($tempf->last()->mode == 'print')
        {
            $tempf = $tempf->last();
            return view('check.create',compact('tempf'));
        }
        else if($tempf->last()->mode == 'edit')
        {
            Session::flash('flash_message','Reimbursement sedang dalam mode "EDIT". Silakan selesaikan proses edit terlebih dahulu');
            return redirect('datareim/'.str_replace(".pdf","",$tempf->last()->namafile).'/edit');
        }
        else if($tempf->last()->mode == 'cheque')
        {
            Session::flash('flash_message','Tidak ada data reimbursement yang siap untuk diinput.');
            return redirect('cheque');   
        }
        /*$tempf = DB::table('simpanfile')->whereNotIn('namafile', function($query){
            $query->select('datacheck.data_reimburse')->from('datacheck')->where('kode_unit',Auth::user()->kode_unit);
        })->where([['kode_unit',Auth::user()->kode_unit],['mode','print']])->get();*/
        /*if($tempf->count() > 0)
            return view('check.create',compact('tempf'));
        else
        {
            Session::flash('flash_message','Tidak ada data reimbursement yang siap untuk diinput atau reimburse sedang dalam mode edit.');
            return redirect('cheque');
        }*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'tanggal_cair' => 'required|before_or_equal:'.date('Y-m-d'),
            'no_check' => 'required|string|unique:datacheck,no_check',
            'data_reimburse' => 'required|min:9',],[
                'tanggal_cair.before_or_equal' => 'Tanggal Cheque cair harus hari ini atau sebelumnya',
                'no_check.required' => 'No cheque belum di input',
                'no_check.unique' => 'No cheque sudah pernah di input',
                'data_reimburse.min(9)' => 'Silakan pilih salah satu reimbursement...',
        ]);

        if ($validator->fails())
        { return redirect('cheque/create')->withInput()->withErrors($validator); }
        $input['kode_unit'] = Auth::user()->kode_unit;
        //$input['data_reimburse'] = substr($input['data_reimburse'],0,23);
        $input['data_reimburse'] = $input['data_reimburse'];
        Cheque::create($input);
        DB::update('update simpanfile set mode = \'cheque\' where namafile = ?', [$input['data_reimburse']]);
        Session::flash('flash_message', 'Data berhasil disimpan.');
        return redirect('cheque');    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cheque = Cheque::where('id',$id)->value('data_reimburse');
        DB::update('update simpanfile set mode = ? WHERE namafile = ?',['print',$cheque]);
        $cheque = Cheque::findOrFail($id);
        $tempf = 0;
        if(Auth::user()->kode_unit != 100) {
            $tempf = DB::table('simpanfile')->where([['kode_unit',Auth::user()->kode_unit],['mode','print']])->get();
            if(count($tempf) > 0)
            return view('check.edit',compact('cheque','tempf'));
        }
        else {
            return view('check.edit',compact('cheque'));   
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cheque = Cheque::findOrFail($id);
        $input = $request->all();

        $validator = Validator::make($input, [
            'tanggal_cair' => 'required|before_or_equal:'.date('Y-m-d'),
            'no_check' => 'required|string|unique:datacheck,no_check,' . $id,
            'data_reimburse' => 'required|min:9|unique:datacheck,data_reimburse,' . $id,],[
                'tanggal_cair.before_or_equal' => 'Tanggal cheque cair harus hari ini atau sebelumnya', 
                'no_check.required' => 'No cheque belum di input',
                'no_check.unique' => 'No cheque sudah pernah di input',
                'data_reimburse.min(9)' => 'Silakan pilih salah satu reimbursement...',
                'data_reimburse.unique' => 'Silakan pilih data_reimburse yang lain',
        ]);

        if ($validator->fails())
        { return redirect('cheque/'.$id.'/edit')->withInput()->withErrors($validator); }
        if(Auth::user()->kode_unit != 100)
            $input['kode_unit'] = Auth::user()->kode_unit;
        $input['data_reimburse'] = substr($input['data_reimburse'],0,23);
        $cheque->update($input);
        DB::update('update simpanfile set mode = \'cheque\' where namafile = ?', [$input['data_reimburse']]);
        Session::flash('flash_message', 'Data berhasil diupdate.');
        
        if(Auth::user()->kode_unit != 100)
            return redirect('cheque');
        else
            return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cheque = Cheque::findOrFail($id);

        DB::update('update simpanfile set mode = ? where namafile = ?', ['print',$cheque->data_reimburse]);
        $cheque->delete();
        Session::flash('flash_message','Data berhasil dihapus');
        Session::flash('penting',true);
        return redirect('cheque');    }

    public function cancel($userid) {
        $dr = Cheque::where('id',$userid)->value('data_reimburse');
        DB::update('update simpanfile set mode = ? WHERE namafile = ?',['cheque',$dr]);
        return redirect('cheque');
    }
}
