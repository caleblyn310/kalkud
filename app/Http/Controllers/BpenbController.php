<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App;
use Validator;
use DB;
use PDF;
use App\Invoices;
use App\InvoicesDetail;
use App\Bank;
use App\InvNoTemp;
use App\JurnalAdmin;
use App\Bpenb;
use App\BpenbDetail;

class BpenbController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bpenb_list = Bpenb::orderBy('status','asc')->orderBy('dot','desc')->orderBy('invoices_no','desc')->paginate(80);
        return view('bpenb.index',compact('bpenb_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invno = Invoices::where('bank',1)->whereMonth('dot',Carbon::now()->month)->whereYear('dot', Carbon::now()->year)->get();$t = count($invno);
        $invno = Bpenb::where('bank',1)->whereMonth('dot',Carbon::now()->month)->whereYear('dot', Carbon::now()->year)->get();$t += count($invno);$t++;
        if ($t < 10) {$invno = '00' . $t;}
        elseif ($t < 100) {$invno = '0' . $t;}
        $bank_list = Bank::pluck('bank','id');$fs = 'new';
        return view('bpenb.create', compact('bank_list','fs','invno'));
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
            'invoices_no' => 'required',
            /*'dot' => 'required|before_or_equal:'.date('Y-m-d')],[
                'dot.before_or_equal' => 'Please set the date to today or before'*/
        ]);

        if ($validator->fails())
        {   
            ($validator->errors()->has('nominals')) ? Session::flash('flash_message',$validator->errors()->first('nominals')) : Session::flash('flash_message',$validator->errors()->first('invoices_no'));
            //dd($validator->errors()->first('invoices_no'));
            return redirect('bpenb/create')->withInput()->withErrors($validator); 
            //return response()->json(['errors'=>$validator->errors()->all()]);
            //return redirect('testbpb')->withInput()->withErrors($validator); 
        }
        else
        {   
            $inv = new Bpenb();
            $inv->invoices_no = $input['invoices_no'];
            $inv->bank = $input['bank'];
            $inv->pay_from = $input['pay_from'];
            $inv->given_by = $input['given_by'];
            $inv->dot = $input['dot'];
            $inv->nominal = $input['nominals'];
            $inv->user_id = $input['user_id'];
            $inv->aiw = $input['aiw'];
            $inv->memo = $input['memo'];
            $inv->save();
            

            $iddet = explode("|", $input['iddet'], -1);
            foreach ($iddet as $i) {
                BpenbDetail::where('id',$i)->update(['id_bpenb' => $inv->id]);
            }

            //DB::table('inv_no_temp')->where('invoices_no',$input['invoices_no'])->update(['status' => 's']);
            //DB::table('invnolist')->where('invoices_no',$input['invoices_no'])->update(['invoices_no' => $input['invoices_no']+1]);

            if($request->submitbutton == 'saveprint') {
                //DB::table('invoices')->where('invoices_no',$input['invoices_no'])->update(['status' => 'p']);
                $invid = Bpenb::where('id',$inv->id)->value('id');
                return view('bpenb.print',compact('invid'));
            }

            Session::flash('flash_message','Bank In Transaction Saved');
            return redirect('bpenb');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bpenb = Bpenb::findOrFail($id);
        if($bpenb->status != 's')
        {
            Session::flash('flash_message','Invoice already locked.');
            return redirect('bpenb');
        }
        $bank_list = Bank::pluck('bank','id');
        $invoices_no = $bpenb->invoices_no;$invno = $invoices_no;
        $bpenbdetail_list = BpenbDetail::where('id_bpenb',$id)->get();
        $total = $bpenbdetail_list->sum('nominal');
        $fs = 'edit';
        return view('bpenb.edit',compact('bpenb','invoices_no','invno','bank_list','bpenbdetail_list','total','fs'));
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'invoices_no' => 'required',
            /*'dot' => 'required|before_or_equal:'.date('Y-m-d')],[
                'dot.before_or_equal' => 'Please set the date to today or before'*/
        ]);

        if ($validator->fails())
        {   //dd($validator);
            return redirect('bpenb/'.$id.'/edit')->withInput()->withErrors($validator); }
        else
        {
            $inv = Bpenb::findOrFail($id);
            $inv->invoices_no = $input['invoices_no'];
            $inv->bank = $input['bank'];
            $inv->pay_from = $input['pay_from'];
            $inv->given_by = $input['given_by'];
            $inv->dot = $input['dot'];
            $inv->nominal = $input['nominals'];
            $inv->user_id = $input['user_id'];
            $inv->aiw = $input['aiw'];
            $inv->memo = $input['memo'];
            $inv->save();

            if($input['iddet'] != 0 && $input['iddet'] != "") {
                $iddet = explode("|", $input['iddet'], -1);
                foreach ($iddet as $i) {
                BpenbDetail::where('id',$i)->update(['id_bpenb' => $inv->id]);
                }
            }

            if($request->submitbutton == 'saveprint') {
                //DB::table('invoices')->where('invoices_no',$input['invoices_no'])->update(['status' => 'p']);
                $invid = Bpenb::where('id',$inv->id)->value('id');
                return view('bpenb.print',compact('invid'));
            }

            Session::flash('flash_message','Bank In Transaction updated');
            return redirect('bpenb');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $temp = Bpenb::findOrFail($id);
        $t = BpenbDetail::where('id_bpenb',$id);
        $t->delete();$temp->delete();
        return redirect('bpenb');
    }

    public function cancel($invno)
    {
        BpenbDetail::where('invoices_no',$invno)->delete();
        return redirect('bpenb');
    }

    public function print($id) {
        ob_end_clean();
        ob_start();
        $inv = Bpenb::findOrFail($id);
        $bank = Bank::findOrFail($inv->bank);
        $invdets = BpenbDetail::where('id_bpenb',$inv->id)->get();
        $invstatus = $inv->status;

        //PDF Version
        /*$td = '<style>html {margin-top;0px;} table {font-family: arial, sans-serif;font-size:90%;width: 100%;border-collapse:collapse;}';
        $td .= 'th {border-bottom: 1px dotted black;} td, th {padding:3px;} @page {header: page-header;footer: page-footer;}</style>';
        $td .= '<body><htmlpageheader name="page-header"><H5 style="text-align:center;">INVOICE</H5></htmlpageheader>';

        $td .= '<table><tr><td>Pay To: '.$inv->pay_to.'</td><td style="text-align:right;">Date: '.$inv->dot->format('d F Y').'</td></tr>';
        $td .= '<tr><td>Submit To: '.$inv->give_to.'</td><td style="text-align:right;">Invoices No: '.$inv->invoices_no.' ('.$bank->bank.')</td></tr></table>';

        $td .= '<br><table><tr><th>Description</th><th style="width:100px;">Nominal</th></tr>';
        $total = 0;
        foreach ($invdets as $invdet) {
            $td .= '<tr><td>'.$invdet->description.'</td>';
            $td .= '<td style="text-align:right;">'.number_format($invdet->nominal,0,",",".").'</td></tr>';
            $total += $invdet->nominal;
        }
        $td .= '<tr><td style="text-align:right;">Total Rp. </td>';
        $td .= '<td style="border: 1px solid black;text-align:right;">'.number_format($total,0,",",".").'</td></tr></table>';

        $td .= '<br><table style="border: 0px;text-align:center;">';
        $td .= '<tr style="background-color: white;"><td style="width:33%">Submit By:';
        $td .= '<br><br><br><br>';
        $td .= '(.............................)<br>Name</td>';
        $td .= '<td style="width:34%">Receive By:';
        $td .= '<br><br><br><br>';
        $td .= '(.............................)<br>Name</td>';
        $td .= '<td style="width:33%">Known By:';
        $td .= '<br><br><br><br>';
        $td .= '(.............................)<br>Name</td></tr></table>';

        $td .= '</body>';
        $pdf = PDF::loadHTML($td);
        $pdf->save('storage/'.$inv->invoices_no);
        return $pdf->stream('storage/'.$inv->invoices_no);*/

        //Folio paper version
        /*$t = 1;$chr = 65;$detcount = count($invdets);
        $dt = "\n" . str_pad('Bukti Penerimaan Bank', 76, ' ', STR_PAD_BOTH) . "\n";
        $dt .= "NO: ".$inv->invoices_no." (".$bank->bank.")\n";
        $dt .= str_pad("Pay To: ".$inv->pay_to,60,' ',STR_PAD_RIGHT);
        $dt .= str_pad($inv->dot->format('d F Y'),16,' ',STR_PAD_LEFT)."\n";
        $dt .= str_pad("Give To: ".$inv->give_to,70,' ',STR_PAD_RIGHT)."\n";
        $dt .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        $dt .= str_pad('Description', 62) . '| ' . str_pad('Nominal (IDR)', 13, ' ', STR_PAD_LEFT) . "\n";
        $dt .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        foreach ($invdets as $invdet) {
            //$dt .= str_pad($invdet->description, 62) . '| ' . str_pad(number_format($invdet->nominal,0,",","."),13,' ',STR_PAD_LEFT);
            //$dt .= "\n";
            //$t++;
            $ts = $invdet->description;
            $result = ''; $cpl = 50;
            if($detcount > 1) {
                $tempinv = " (".$inv->invoices_no.chr($chr)."/".$bank->middle."-". $inv->dot->format('m/y') .")";
                $ts .= $tempinv; $chr++;
            }
            else {
                $tempinv = " (".$inv->invoices_no."/".$bank->middle."-". $inv->dot->format('m/y') .")";
                $ts .= $tempinv;}
            
            if (strlen($ts) > $cpl){
                $z = 0;
                while (strlen($ts) > $cpl) {
                    $substr = str_limit($ts,$cpl,'');
                    $y = strripos($substr,' ');
                    $ts = substr($ts, $y+1);
                    $substr = substr($substr, 0, $y);   
                    if($z == 0 && $invdet->kode_d_ger != "") {$dt .= $invdet->kode_d_ger . "|";}
                    else { $dt .= "          |"; }
                    $dt .= str_pad($substr, 51) . "|";
                    if ($z == 0){$dt .= str_pad(number_format($invdet->nominal,0,",","."),14,' ',STR_PAD_LEFT);$z=1;}
                    $dt .= "\n";
                    $t++;
                }
                $dt .= "          |" . str_pad($ts, 51) . "|" . "\n"; 
            }
            else{
                if($invdet->kode_d_ger != "") { $dt .= $invdet->kode_d_ger . "|"; }
                else { $dt .= "           "; }
                $dt .= str_pad($invdet->description, 51) . str_pad(number_format($invdet->nominal,0,",","."),14,' ',STR_PAD_LEFT);
                $dt .= "\n";
                $t++;}

            //If the status of the transaction is 's' then save the transaction to d-ger.jurnal_admin
            if($invstatus == 's') {
            $ja = new JurnalAdmin();
            $ja->No_account = $bank->kode_d_ger;
            $ja->No_bukti = $tempinv;
            $ja->Tanggal = $inv->dot->format('Y-m-d');
            $ja->Uraian = $invdet->description;
            $ja->Debet = $invdet->nominal;
            $ja->Kredit = 0;
            $ja->Kontra_acc = $invdet->kode_d_ger;
            $ja->save();}
        }
        for ($i=$t; $i <= 10 ; $i++) {$dt .= "\n";}
        $dt .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        $dt .= str_pad('Total Rp.', 62, ' ', STR_PAD_LEFT) . '|' . str_pad(number_format($inv->nominal,0,",","."),14,' ',STR_PAD_LEFT);
        $dt .= "\n\n" . str_pad('Submit By', 25, ' ', STR_PAD_BOTH) . str_pad('Receive By', 25, ' ', STR_PAD_BOTH);
        $dt .= str_pad('Known By', 25, ' ', STR_PAD_BOTH);
        $dt .= Chr(13) . Chr(10) . Chr(13) . Chr(10) . Chr(13) . Chr(10) . Chr(13) . Chr(10);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH) . "\n";
        //$dt = Chr(27) . Chr(69) . Chr(1) . $dt . Chr(27) . Chr(69) . Chr (0);

        if($inv->status != 'p') {
            Bpenb::where('id',$inv->id)->update(['status' => 'p']);
        }
        else { $dt .= str_pad('reprint',76, ' ', STR_PAD_LEFT) . "\n"; }
        $dt .= Chr(12);
        return $dt;
        */
        
        //Continues form version//
        /*$t = 1;$chr = 65;$detcount = count($invdets);
        $dt = "\n" . str_pad('Bukti Penerimaan Bank', 76, ' ', STR_PAD_BOTH) . "\n\n";
        $dt .= "NO: ".$inv->invoices_no." (".$bank->bank.")\n";
        $dt .= str_pad("Pay From: ".$inv->pay_from,55,' ',STR_PAD_RIGHT);
        $dt .= str_pad($inv->dot->format('d F Y'),16,' ',STR_PAD_LEFT)."\n";
        $dt .= str_pad("Given By: ".$inv->given_by,65,' ',STR_PAD_RIGHT)."\n";
        $dt .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        $dt .= str_pad('Description', 61) . '| ' . str_pad('Nominal (IDR)', 13, ' ', STR_PAD_LEFT) . "\n";
        $dt .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        foreach ($invdets as $invdet) {
            //$dt .= str_pad($invdet->description, 62) . '| ' . str_pad(number_format($invdet->nominal,0,",","."),13,' ',STR_PAD_LEFT);
            //$dt .= "\n";
            //$t++;
            $ts = $invdet->description;
            $result = ''; $cpl = 45;
            if($detcount > 1) {
                $tempinv = " (".$inv->invoices_no.chr($chr)."/".$bank->middle."-". $inv->dot->format('m/y') .")";
                $ts .= $tempinv; $chr++;
            }
            else {
                $tempinv = " (".$inv->invoices_no."/".$bank->middle."-". $inv->dot->format('m/y') .")";
                $ts .= $tempinv;}
            
            if (strlen($ts) > $cpl){
                $z = 0;
                while (strlen($ts) > $cpl) {
                    $substr = str_limit($ts,$cpl,'');
                    $y = strripos($substr,' ');
                    $ts = substr($ts, $y+1);
                    $substr = substr($substr, 0, $y);   
                    if($z == 0 && $invdet->kode_d_ger != "") {$dt .= $invdet->kode_d_ger . "|";}
                    else { $dt .= "          |"; }
                    $dt .= str_pad($substr, 50) . "|";
                    if ($z == 0){$dt .= str_pad(number_format($invdet->nominal,0,",","."),14,' ',STR_PAD_LEFT);$z=1;}
                    $dt .= "\n";
                    $t++;
                }
                $dt .= "          |" . str_pad($ts, 50) . "|" . "\n"; 
            }
            else{
                if($invdet->kode_d_ger != "") { $dt .= $invdet->kode_d_ger . "|"; }
                else { $dt .= "          |"; }
                $dt .= str_pad($invdet->description, 50) . "|" . str_pad(number_format($invdet->nominal,0,",","."),14,' ',STR_PAD_LEFT);
                $dt .= "\n";
                $t++;}

            //If the status of the transaction is 's' then save the transaction to d-ger.jurnal_admin
            if($invstatus == 's') {
            $ja = new JurnalAdmin();
            $ja->No_account = $bank->kode_d_ger2;
            $ja->No_bukti = str_replace(")","",str_replace(" (", "", $tempinv));
            $ja->Tanggal = $inv->dot->format('Y-m-d');
            $ja->Uraian = $invdet->description;
            $ja->Debet = $invdet->nominal;
            $ja->Kredit = 0;
            $ja->Kontra_acc = $invdet->kode_d_ger;
            $ja->Div = $id . "bpn";
            $ja->save();}
        }
        for ($i=$t; $i <= 10 ; $i++) {$dt .= "\n";}
        $dt .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        $dt .= str_pad('Total Rp.', 61, ' ', STR_PAD_LEFT) . '|' . str_pad(number_format($inv->nominal,0,",","."),14,' ',STR_PAD_LEFT);
        $dt .= "\n\n" . str_pad('Submit By', 25, ' ', STR_PAD_BOTH) . str_pad('Receive By', 25, ' ', STR_PAD_BOTH);
        $dt .= str_pad('Known By', 25, ' ', STR_PAD_BOTH);
        $dt .= Chr(13) . Chr(10) . Chr(13) . Chr(10) . Chr(13) . Chr(10) . Chr(13) . Chr(10);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH) . "\n";
        $dt = Chr(27) . Chr(69) . Chr(1) . $dt . Chr(27) . Chr(70) . Chr (0);

        if($inv->status != 'p') {
            Bpenb::where('id',$inv->id)->update(['status' => 'p']);
        }
        else { $dt .= str_pad('',76, ' ', STR_PAD_LEFT) . "\n"; }
        $dt .= Chr(12);

        return $dt;*/

        //Existing form version (BCA)
        $t = 0;$cpl = 35;
        $dt = "\n\n\n           " . $bank->bank;
        if($inv->status != 's') { $dt .= str_pad('No: '. $inv->invoices_no .'(reprint)',40, ' ', STR_PAD_LEFT); }
        else { Bpenb::where('id',$inv->id)->update(['status' => 'p']); 
                $dt .= str_pad("No: ".$inv->invoices_no,41, ' ', STR_PAD_LEFT);}
        $dt .= "\n\n\n";
        $dt .= "                 " . $inv->pay_from . "\n";
        $ts = $inv->given_by;$z = 0;
        if (strlen($ts) > $cpl){
            while (strlen($ts) > $cpl) {
                $substr = str_limit($ts,$cpl,'');
                $y = strripos($substr,' ');
                $ts = substr($ts, $y+1);
                $substr = substr($substr, 0, $y);
                $dt .= "                 " . str_pad($substr,40, " ", STR_PAD_RIGHT);
                if ($z == 0) { $dt .= "     " . $inv->dot->format('d F Y'); $z++;}
                $dt .= "\n";
            }
            $dt .= "                 " . $ts . "\n\n";
        }
        else {
            $dt .= "                 " . str_pad($inv->given_by,40, " ", STR_PAD_RIGHT);
            $dt .= "     " . $inv->dot->format('d F Y') . "\n\n\n";
        }

        /*$expmemo = explode("\r\n", $inv->memo);
        for ($i=0; $i < count($expmemo); $i++) { 
            $dt .= "           " . $expmemo[$i] . "\n";
        }

        $t += count($expmemo);*/
        $detcount = count($invdets);$chr = 65;

        //transaction detail
        foreach ($invdets as $invdet) {
            $ts = $invdet->description;$tempinv = "";
            $result = ''; $cpl = 40;

            //input dari file excel
            //$dger = JurnalAdmin::where('no_bukti','like','%'.$invdet->invoices_no.'%06/19')->value('no_bukti');
            //$ts .= ' ('.$dger.')';

            //input dari program
            if($detcount > 1) {
                $tempinv = $inv->invoices_no.chr($chr)."/".$bank->middle."-". $inv->dot->format('m/y');
                $ts .= " (" . $tempinv . ")"; $chr++;
            }
            else {
                $tempinv = $inv->invoices_no."/".$bank->middle."-". $inv->dot->format('m/y');
                $ts .= " (" . $tempinv . ")";}
            
            if (strlen($ts) > $cpl){
                $z = 0;
                while (strlen($ts) > $cpl) {
                    $substr = str_limit($ts,$cpl,'');
                    $y = strripos($substr,' ');
                    $ts = substr($ts, $y+1);
                    $substr = substr($substr, 0, $y);   
                    if($z == 0 && $invdet->kode_d_ger != "") {$dt .= "   ".$invdet->kode_d_ger . "|";}
                    else { $dt .= "             |"; }
                    $dt .= str_pad($substr, 49);
                    if ($z == 0){$dt .= str_pad(number_format($invdet->nominal,0,",","."),13,' ',STR_PAD_LEFT);$z=1;}
                    $dt .= "\n";
                    $t = $t + 1;
                }
                $dt .= "             |" . $ts . "\n"; 
            }
            else{
                if($invdet->kode_d_ger != "") { $dt .= $invdet->kode_d_ger . "|"; }
                else { $dt .= "             |"; }
                $dt .= str_pad($invdet->description, 49) . str_pad(number_format($invdet->nominal,0,",","."),13,' ',STR_PAD_LEFT);
                $dt .= "\n";
                $t = $t + 1;}

            //If the status of the transaction is 's' then save the transaction to d-ger.jurnal_admin
            if($invstatus == 's') {
            $ja = new JurnalAdmin();
            $ja->No_account = $bank->kode_d_ger2;
            $ja->No_bukti = str_replace(")","",str_replace(" (", "", $tempinv));
            $ja->Tanggal = $inv->dot->format('Y-m-d');
            $ja->Uraian = $invdet->description;
            $ja->Debet = $invdet->nominal;
            $ja->Kredit = 0;
            $ja->Kontra_acc = $invdet->kode_d_ger;
            $ja->Div = $id . "bpn";
            $ja->save();}
        }
        
        //$dt .= "           " . $inv->memo;

        for ($i=$t; $i <= 15 ; $i++) {$dt .= "\n";}
        $dt .= str_pad('Total Rp.', 62, ' ', STR_PAD_LEFT) . str_pad(number_format($inv->nominal,0,",","."),13,' ',STR_PAD_LEFT);
        //$dt = Chr(27) . Chr(69) . Chr(1) . $dt . Chr(27) . Chr(69) . Chr (0);

        $dt .= "\n";
        $cpl = 57;
        $ts = $inv->aiw;
        //$dt .= $inv->aiw . "\n";

        if (strlen($ts) > $cpl){
            $z = 0;
            while (strlen($ts) > $cpl) {
                $substr = str_limit($ts,$cpl,'');
                $y = strripos($substr,' ');
                $ts = substr($ts, $y+1);
                $substr = substr($substr, 0, $y);
                $dt .= "     ";
                $dt .= $substr;
                $dt .= "\n";
            }
            $dt .= "     " . $ts;
        }
        else { $dt .= "     " . $ts . "\n"; }

        $dt .= Chr(12);
        return $dt;

        //Existing form version (BDI)//
        /*$t = 0;$cpl = 35;
        $dt = "\n\n\n           " . $bank->bank;
        if($inv->status != 's') { $dt .= str_pad('No: '. $inv->invoices_no .'(reprint)',40, ' ', STR_PAD_LEFT); }
        else { DB::table('bpenb')->where('invoices_no',$inv->invoices_no)->update(['status' => 'p']); 
                $dt .= str_pad("No: ".$inv->invoices_no,41, ' ', STR_PAD_LEFT);}
        $dt .= "\n\n";
        $dt .= "                 " . $inv->pay_from . "\n";
        $ts = $inv->given_by;$z = 0;
        if (strlen($ts) > $cpl){
            while (strlen($ts) > $cpl) {
                $substr = str_limit($ts,$cpl,'');
                $y = strripos($substr,' ');
                $ts = substr($ts, $y+1);
                $substr = substr($substr, 0, $y);
                $dt .= "                 " . str_pad($substr,40, " ", STR_PAD_RIGHT);
                if ($z == 0) { $dt .= "     " . $inv->dot->format('d F Y'); $z++;}
                $dt .= "\n";
            }
            $dt .= "                 " . $ts . "\n\n";
        }
        else {
            $dt .= "                 " . str_pad($inv->given_by,40, " ", STR_PAD_RIGHT);
            $dt .= "     " . $inv->dot->format('d F Y') . "\n\n\n";
        }

        $expmemo = explode("\r\n", $inv->memo);
        for ($i=0; $i < count($expmemo); $i++) { 
            $dt .= "           " . $expmemo[$i] . "\n";
        }

        $t += count($expmemo);
        $detcount = count($invdets);$chr = 65;

        //transaction detail
        foreach ($invdets as $invdet) {
            $ts = $invdet->description;$tempinv = "";
            $result = ''; $cpl = 40;

            //input dari file excel
            $dger = JurnalAdmin::where('no_bukti','like','%'.$invdet->invoices_no.'%06/19')->value('no_bukti');
            $ts .= ' ('.$dger.')';

            //input dari program
            /*if($detcount > 1) {
                $tempinv = " (".$inv->invoices_no.chr($chr)."/".$bank->middle."-". $inv->dot->format('m/y') .")";
                $ts .= $tempinv; $chr++;
            }
            else {
                $tempinv = " (".$inv->invoices_no."/".$bank->middle."-". $inv->dot->format('m/y') .")";
                $ts .= $tempinv;}//
            
            if (strlen($ts) > $cpl){
                $z = 0;
                while (strlen($ts) > $cpl) {
                    $substr = str_limit($ts,$cpl,'');
                    $y = strripos($substr,' ');
                    $ts = substr($ts, $y+1);
                    $substr = substr($substr, 0, $y);   
                    if($z == 0 && $invdet->kode_d_ger != "") {$dt .= "   ".$invdet->kode_d_ger . "|";}
                    else { $dt .= "             |"; }
                    $dt .= str_pad($substr, 49);
                    if ($z == 0){$dt .= str_pad(number_format($invdet->nominal,0,",","."),13,' ',STR_PAD_LEFT);$z=1;}
                    $dt .= "\n";
                    $t = $t + 1;
                }
                $dt .= "             |" . $ts . "\n"; 
            }
            else{
                if($invdet->kode_d_ger != "") { $dt .= $invdet->kode_d_ger . "|"; }
                else { $dt .= "             |"; }
                $dt .= str_pad($invdet->description, 49) . str_pad(number_format($invdet->nominal,0,",","."),13,' ',STR_PAD_LEFT);
                $dt .= "\n";
                $t = $t + 1;}

            //If the status of the transaction is 's' then save the transaction to d-ger.jurnal_admin
            if($invstatus == 's') {
            $ja = new JurnalAdmin();
            $ja->No_account = $bank->kode_d_ger2;
            $ja->No_bukti = $tempinv;
            $ja->Tanggal = $inv->dot->format('Y-m-d');
            $ja->Uraian = $invdet->description;
            $ja->Debet = $invdet->nominal;
            $ja->Kredit = 0;
            $ja->Kontra_acc = $invdet->kode_d_ger;
            $ja->save();}
        }
        
        //$dt .= "           " . $inv->memo;

        for ($i=$t; $i <= 15 ; $i++) {$dt .= "\n";}
        $dt .= str_pad('Total Rp.', 62, ' ', STR_PAD_LEFT) . str_pad(number_format($inv->nominal,0,",","."),13,' ',STR_PAD_LEFT);
        //$dt = Chr(27) . Chr(69) . Chr(1) . $dt . Chr(27) . Chr(69) . Chr (0);

        $dt .= "\n";
        $cpl = 57;
        $ts = $inv->aiw;
        //$dt .= $inv->aiw . "\n";

        if (strlen($ts) > $cpl){
            $z = 0;
            while (strlen($ts) > $cpl) {
                $substr = str_limit($ts,$cpl,'');
                $y = strripos($substr,' ');
                $ts = substr($ts, $y+1);
                $substr = substr($substr, 0, $y);
                $dt .= "     ";
                $dt .= $substr;
                $dt .= "\n";
            }
            $dt .= "     " . $ts;
        }
        else { $dt .= "     " . $ts . "\n"; }

        $dt .= Chr(12);
        return $dt;*/
    }
}
