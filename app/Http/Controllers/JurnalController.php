<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\JurnalAdmin;
use App\Cheque;
use Session;
use DB;

class JurnalController extends Controller
{
	public function index()
	{}

    public function create()
    {
    	return view('jurnaladmin.create');
    }

    public function store(Request $request)
    {
    	$input = $request->all();

        $validator = Validator::make($input, [
            'No_account' => 'required|min:10',
            'No_bukti' => 'required|unique:mysql2.jurnal_admin,No_bukti',
            'Uraian' => 'required|max:150',
            'Kontra_acc' => 'required|min:10',
            'Tanggal' => 'required|before_or_equal:'.date('Y-m-d')],[
                'No_account.required' => 'Kode D-Ger belum di input',
                'No_bukti.unique' => 'No bukti sudah pernah dipakai',
                'Tanggal.before_or_equal' => 'Tanggal harus sebelum atau hari ini'
        ]);

        $validator->getPresenceVerifier()->setConnection('mysql2');

        if ($validator->fails())
        { return redirect('jurnaladmin/create')->withInput()->withErrors($validator); }

        JurnalAdmin::create($input);
        Session::flash('flash_message', 'Data berhasil disimpan.');
        return redirect('kaskecil');
    }

    public function edit(JurnalAdmin $jurnaladmin)
    {
    	return view('jurnaladmin.edit', compact('jurnaladmin'));
    }

    public function update(JurnalAdmin $jurnaladmin, Request $request)
    {
    	//$jurnaladmin = JurnalAdmin::findOrFail($ja);
    	$input = $request->all();//dd($ja->id);

        $validator = Validator::make($input, [
            'No_account' => 'required|min:10',
            'No_bukti' => 'required|unique:mysql2.jurnal_admin,No_bukti,'.$jurnaladmin->id,
            'Uraian' => 'required|max:150',
            'Kontra_acc' => 'required|min:10',
            'Tanggal' => 'required|before_or_equal:'.date('Y-m-d')],[
                'No_account.required' => 'Kode D-Ger belum di input',
                'No_bukti.unique' => 'No bukti sudah terpakai',
                'Tanggal.before_or_equal' => 'Tanggal harus sebelum atau hari ini'
        ]);

        $validator->getPresenceVerifier()->setConnection('mysql2');

        if ($validator->fails())
        {   dd($validator);
            return redirect('search/transaction?No_bukti='.$input['No_bukti'])->withInput()->withErrors($validator); }

        $jurnaladmin->update($input);
        Session::flash('flash_message', 'Data berhasil disimpan.');
        return redirect('ja/edit');
    }

    public function edittransaction()
    {return view('jurnaladmin.search');}

    public function search(Request $request)
    {
    	$input = $request->all();

    	$ja = JurnalAdmin::where('No_bukti',$input['No_bukti'])->first();
    	
        if($ja == null)
        {
            Session::flash('flash_message','Data tidak ditemukan. Silakan coba lagi ya.');
            return redirect('ja/edit');
        }

    	if($ja->count() > 0)
    	{
    		//return view('jurnaladmin.edit',compact('jurnaladmin'));
    		//return redirect('jurnaladmin/'.$jurnaladmin->id.'/edit');
            return view('jurnaladmin.search',compact('ja'));
    	}
    }

    public function exportJA($chequeid)
    {
    	$temp = Cheque::findOrFail($chequeid);
    	$tempdate = $temp->tanggal_cair->format('Y-m-d');
        $tblview = substr($temp->data_reimburse, 0,-4);

        ($temp->kode_unit != 9) ? $contraacc = '111.2'.$temp->kode_unit.'.111' : $contraacc = '111.33.111';
        if($temp->kode_unit == 0) $contraacc = '111.30.111';
    	$data = DB::connection('mysql3')->select(DB::raw('select * from '. $tblview .' ORDER BY no_bukti, tanggal_trans, id'));
    	
        $nomor = 1;
        $short = DB::table('kodeunit')->select('short')->where('id',$temp->kode_unit)->get();
        $short = $short->toArray();
        $short = $short[0]->short;

    	foreach ($data as $dt) {
    		$ja = new JurnalAdmin();
    		$ja->No_account = $dt->kode_d_ger;
    		$ja->No_bukti = str_pad($nomor, 3,"0",STR_PAD_LEFT).'/'.$dt->no_bukti.'/'.$short.'-'.$temp->tanggal_cair->format('m/y');
    		$ja->Tanggal = $tempdate;
    		$ja->Uraian = $dt->deskripsi;
    		$ja->Debet = $dt->nominal;
    		$ja->Kredit = 0;
            $ja->Kontra_acc = $contraacc;
            $ja->Div = 'tes';
    		$ja->save();
    		$nomor++; }

    		/*$ja = new JurnalAdmin();
    		$ja->No_account = '112.28.111';
    		$ja->No_bukti = $nomor.'calebabc';//str_pad($nomor, 3,"0",STR_PAD_LEFT).'/'.$dt->no_bukti.'/'.$short.'-'.$temp->tanggal_cair->format('m/y');
    		$ja->Tanggal = $tempdate;
    		$ja->Uraian = 'Check BDI dengan no. '. $temp->no_check .' untuk reimburse: '. $tblview;
    		$ja->Debet = 0;
    		$ja->Kredit = $temp->nominal;
            $ja->Kontra_acc = $contraacc;
            $ja->Div = 'test';
    		$ja->save();*/
            //dd($datas);

        Cheque::where('id',$chequeid)->update(['mode'=>'saved']);
        Session::flash('flash_message', 'Data berhasil disimpan di database d-ger.');
        return redirect('/');
    }

    public function destroy(JurnalAdmin $jurnaladmin) {
        $jurnaladmin->delete();
        Session::flash('flash_message','Data berhasil dihapus');
        Session::flash('penting',true);
        return redirect('ja/edit');
    }
}
