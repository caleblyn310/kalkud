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

class InvoicesController extends Controller
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
        $invoices_list = Invoices::orderBy('status','asc')->orderBy('dot','desc')->orderBy('invoices_no','desc')->paginate(80);
        return view('invoices.index', compact('invoices_list'));
    }

    public function testinput() 
    {
        $invno = Invoices::where('bank',2)->whereMonth('dot',Carbon::now()->month)->whereYear('dot', Carbon::now()->year)->get();$t = count($invno);
        if ($t < 10) {$invno = '00' . ++$t;}
        elseif ($t < 100) {$invno = '0' . ++$t;}
        $bank_list = Bank::pluck('bank','id');$fs = 'new';
        return view('testbpb', compact('bank_list','fs','invno'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*$t = InvNoTemp::where([['user_id',Auth::user()->id],['status','o']])->get();
        $bank_list = Bank::pluck('bank','id');
        $fs = 'new'; //form status
        if(count($t) == 0)
        {
            $total = 0;
            $invoices_no = DB::table('invnolist')->first();//dd($invoices_no);
            $invnotemp = new InvNoTemp();
            $invnotemp->invoices_no = $invoices_no->invoices_no;
            $invnotemp->user_id = Auth::user()->id;
            $invnotemp->save();
            $invoices_no = $invoices_no->invoices_no;
            return view('invoices.create', compact('invoices_no','bank_list','total','fs'));
        }
        else {
            $fs = 'edit';
            $invoices_no = $t[0]->invoices_no;
            $invoicesdetail_list = InvoicesDetail::where('invoices_no',$invoices_no)->get();
            $total = $invoicesdetail_list->sum('nominal');
            return view('invoices.create',compact('invoices_no','bank_list','invoicesdetail_list','total','fs'));
        }*/
        //$bank_list = Bank::pluck('bank','id');$fs = 'new';
        //return view('invoices.create', compact('bank_list','fs'));
        $invno = Invoices::where('bank',1)->whereMonth('dot',Carbon::now()->month)->whereYear('dot', Carbon::now()->year)->get();$t = count($invno);
        $invno = Bpenb::where('bank',1)->whereMonth('dot',Carbon::now()->month)->whereYear('dot', Carbon::now()->year)->get();$t += count($invno);$t++;
        if ($t < 10) {$invno = '00' . $t;}
        elseif ($t < 100) {$invno = '0' . $t;}
        $bank_list = Bank::pluck('bank','id');$fs = 'new';
        return view('invoices.create', compact('bank_list','fs','invno'));
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
            return redirect('invoices/create')->withInput()->withErrors($validator); 
            //return response()->json(['errors'=>$validator->errors()->all()]);
            //return redirect('testbpb')->withInput()->withErrors($validator); 
        }
        else
        {   
            $inv = new Invoices();
            $inv->invoices_no = $input['invoices_no'];
            $inv->bank = $input['bank'];
            $inv->pay_to = $input['pay_to'];
            $inv->give_to = $input['give_to'];
            $inv->dot = $input['dot'];
            $inv->nominal = $input['nominals'];
            $inv->user_id = $input['user_id'];
            $inv->aiw = $input['aiw'];
            $inv->memo = $input['memo'];
            $inv->save();

            $iddet = explode("|", $input['iddet'], -1);$chr = 65;
            if(count($iddet) == 1) $chr = "";
            foreach ($iddet as $i) {
                InvoicesDetail::where('id',$i)->update(['id_invoices' => $inv->id]);
                /*$inv_det = InvoicesDetail::findOrFail($i);
                $ja = new JurnalAdmin();
                $ja->No_account = $inv_det->kode_d_ger;
                $ja->No_bukti = str_replace(")","",str_replace(" (", "", $tempinv));
                $ja->Tanggal = $inv->dot->format('Y-m-d');
                $ja->Uraian = $invdet->description;
                $ja->Debet = 0;
                $ja->Kredit = $invdet->nominal;
                $ja->Kontra_acc = $invdet->kode_d_ger;
                $ja->Div = $invid . "inv";
                $ja->save();*/

            }

            //DB::table('inv_no_temp')->where('invoices_no',$input['invoices_no'])->update(['status' => 's']);
            //DB::table('invnolist')->where('invoices_no',$input['invoices_no'])->update(['invoices_no' => $input['invoices_no']+1]);

            if($request->submitbutton == 'saveprint') {
                //DB::table('invoices')->where('invoices_no',$input['invoices_no'])->update(['status' => 'p']);
                $invid = Invoices::where('id',$inv->id)->value('id');
                return view('invoices.print',compact('invid'));
            }

            Session::flash('flash_message','Invoice saved');
            return redirect('invoices');
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
        $invoices = Invoices::findOrFail($id);
        $trans_list = InvoicesDetail::where('id_invoices',$id)->get();
        return view('invoices.show', compact('invoices','trans_list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = Invoices::findOrFail($id);
        if($invoices->status != 's')
        {
            Session::flash('flash_message','Invoice already locked.');
            return redirect('invoices');
        }
        $bank_list = Bank::pluck('bank','id');
        $invoices_no = $invoices->invoices_no;$invno = $invoices_no;
        $invoicesdetail_list = InvoicesDetail::where('id_invoices',$invoices->id)->get();
        $total = $invoicesdetail_list->sum('nominal');
        $fs = 'edit';
        return view('invoices.edit',compact('invoices','invoices_no','invno','bank_list','invoicesdetail_list','total','fs'));
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
        {   dd($validator);
            return redirect('invoices/'.$id.'/edit')->withInput()->withErrors($validator); }
        else
        {
            $inv = Invoices::findOrFail($id);
            $inv->invoices_no = $input['invoices_no'];
            $inv->bank = $input['bank'];
            $inv->pay_to = $input['pay_to'];
            $inv->give_to = $input['give_to'];
            $inv->dot = $input['dot'];
            $inv->nominal = $input['nominals'];
            $inv->user_id = $input['user_id'];
            $inv->aiw = $input['aiw'];
            $inv->memo = $input['memo'];
            $inv->save();

            if($input['iddet'] != 0 && $input['iddet'] != "") {
                $iddet = explode("|", $input['iddet'], -1);
                foreach ($iddet as $i) {
                InvoicesDetail::where('id',$i)->update(['id_invoices' => $inv->id]);
                }
            }

            if($request->submitbutton == 'saveprint') {
                //DB::table('invoices')->where('invoices_no',$input['invoices_no'])->update(['status' => 'p']);
                $invid = Invoices::where('id',$inv->id)->value('id');
                return view('invoices.print',compact('invid'));
            }

            Session::flash('flash_message','Invoices updated');
            return redirect('invoices');
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
        $temp = Invoices::findOrFail($id);
        $t = InvoicesDetail::where('id_invoices',$id);
        $t->delete();$temp->delete();
        return redirect('invoices');
    }

    public function cancel($invno)
    {
        InvNoTemp::where('invoices_no',$invno)->delete();
        InvoicesDetail::where('invoices_no',$invno)->delete();
        return redirect('invoices');
    }

    public function checkInvNo($invno) {
        $result = Invoices::where('invoices_no',$invno)->get();
        //dd(count($result));
        (count($result) == 0) ? $result = 'eligible' : $result = 'ne';
        return Response::json(['result'=>$result]);
    }

    public function getinvno(Request $request)
    {
        $temp = $request->all();
        $invno = Invoices::where('bank',$temp['bank'])->whereMonth('dot',$temp['mon'])->whereYear('dot', $temp['year'])->get();$t = count($invno);
        $invno = Bpenb::where('bank',$temp['bank'])->whereMonth('dot',$temp['mon'])->whereYear('dot', $temp['year'])->get();$t += count($invno);$t++;
        if ($t < 10) {$invno = '00' . $t;}
        elseif ($t < 100) {$invno = '0' . $t;}
        return Response::json(['invno' => $t]);
    }

    public function cancelprint($id)
    {
        if(substr($id, -3) == "inv")
        {
            JurnalAdmin::where('Div',$id)->delete();
            $id = str_replace("inv", "", $id);
            Invoices::where('id',$id)->update(['status'=>'s']);
        }
        else if(substr($id, -3) == "bpn")
        {
            JurnalAdmin::where('Div',$id)->delete();
            $id = str_replace("bpn", "", $id);
            Bpenb::where('id',$id)->update(['status'=>'s']);
        }
    }

    public function printing($invid)
    {
        ob_end_clean();
        ob_start();
        $inv = Invoices::findOrFail($invid);
        $bank = Bank::findOrFail($inv->bank);
        $invdets = InvoicesDetail::where('id_invoices',$inv->id)->get();
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
        
        //*********************************************Continues form version (input program)
        /*$t = 1;$chr = 65;$detcount = count($invdets);
        $dt = "\n" . str_pad('Bukti Pengeluaran Bank', 76, ' ', STR_PAD_BOTH) . "\n\n";
        $dt .= "NO: ".$inv->invoices_no." (".$bank->bank.")\n";
        $dt .= str_pad("Dibayarkan kpd: ".$inv->pay_to,60,' ',STR_PAD_RIGHT);
        $dt .= str_pad($inv->dot->format('d F Y'),16,' ',STR_PAD_LEFT)."\n";
        $dt .= str_pad("Diberikan kpd: ".$inv->give_to,70,' ',STR_PAD_RIGHT)."\n";
        $dt .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        $dt .= str_pad('Keterangan', 62) . '| ' . str_pad('Nominal (IDR)', 13, ' ', STR_PAD_LEFT) . "\n";
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
                else { $dt .= "          |"; }
                $dt .= str_pad($ts, 51) . "|" . str_pad(number_format($invdet->nominal,0,",","."),14,' ',STR_PAD_LEFT);
                $dt .= "\n";
                $t++;}

            //If the status of the transaction is 's' then save the transaction to d-ger.jurnal_admin
            if($invstatus == 's') {
            $ja = new JurnalAdmin();
            $ja->No_account = $bank->kode_d_ger2;
            $ja->No_bukti = str_replace(")","",str_replace(" (", "", $tempinv));
            $ja->Tanggal = $inv->dot->format('Y-m-d');
            $ja->Uraian = $invdet->description;
            $ja->Debet = 0;
            $ja->Kredit = $invdet->nominal;
            $ja->Kontra_acc = $invdet->kode_d_ger;
            $ja->Div = $invid . "inv";
            $ja->save();}
        }
        for ($i=$t; $i < 10 ; $i++) {$dt .= "\n";}
        $dt .= "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        $dt .= str_pad('Total Rp.', 62, ' ', STR_PAD_LEFT) . '|' . str_pad(number_format($inv->nominal,0,",","."),14,' ',STR_PAD_LEFT);
        $dt .= "\n\n" . str_pad('Submit By', 25, ' ', STR_PAD_BOTH) . str_pad('Receive By', 25, ' ', STR_PAD_BOTH);
        $dt .= str_pad('Known By', 25, ' ', STR_PAD_BOTH);
        $dt .= Chr(13) . Chr(10) . Chr(13) . Chr(10) . Chr(13) . Chr(10) . Chr(13) . Chr(10);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH);
        $dt .= str_pad("(" . str_pad('.', 20 ,'.') . ")",25, ' ', STR_PAD_BOTH) . "\n";
        $dt = Chr(27) . Chr(69) . Chr(1) . $dt . Chr(27) . Chr(69) . Chr (0);

        if($inv->status == 's') {
            DB::table('invoices')->where('id',$inv->id)->update(['status' => 'p']);
        }
        else { $dt .= str_pad('',76, ' ', STR_PAD_LEFT) . "\n"; }
        $dt .= Chr(12);
        return $dt;*/

        //***********************************Existing form version
        $t = 0;$cpl = 38;
        $dt = "\n\n\n          " . $bank->bank;
        if($inv->status == 'p' || $inv->status == 'dg') { $dt .= str_pad("No: " . $inv->invoices_no.'(reprint)',41, ' ', STR_PAD_LEFT); }
        else { $dt .= str_pad("No: ".$inv->invoices_no,41, ' ', STR_PAD_LEFT);}
        $dt .= "\n\n";
        $dt .= "                " . $inv->pay_to . "\n\n";
        $ts = $inv->give_to;$z = 0;
        if (strlen($ts) > $cpl){
            while (strlen($ts) > $cpl) {
                $substr = str_limit($ts,$cpl,'');
                $y = strripos($substr,' ');
                $ts = substr($ts, $y+1);
                $substr = substr($substr, 0, $y);
                $dt .= "                " . str_pad($substr,38, " ", STR_PAD_RIGHT);
                if ($z == 0) { $dt .= "      " . $inv->dot->format('d F Y'); $z++;}
                $dt .= "\n";
            }
            $dt .= "                " . $ts . "\n\n\n";
        }
        else {
            $dt .= "                " . str_pad($inv->give_to,38, ' ', STR_PAD_RIGHT);
            $dt .= "      " . $inv->dot->format('d F Y') . "\n\n\n\n";
        }

        /*$expmemo = explode("\r\n", $inv->memo);
        for ($i=0; $i < count($expmemo); $i++) { 
            $dt .= "           " . $expmemo[$i] . "\n";
        }*/

        //$t += count($expmemo);
        $detcount = count($invdets);$chr = 65;

        foreach ($invdets as $invdet) {
            $ts = $invdet->description;$tempinv = "";
            $result = ''; $cpl = 50;
            if($detcount > 1) {
                $tempinv = $inv->invoices_no.chr($chr)."/".$bank->middle."-". $inv->dot->format('m/y') ;
                $ts .= " (". $tempinv .")"; $chr++;
            }
            else {
                $tempinv = $inv->invoices_no."/".$bank->middle."-". $inv->dot->format('m/y');
                $ts .= " (". $tempinv .")";}
            
            if (strlen($ts) > $cpl){
                $z = 0;
                while (strlen($ts) > $cpl) {
                    $substr = str_limit($ts,$cpl,'');
                    $y = strripos($substr,' ');
                    $ts = substr($ts, $y+1);
                    $substr = substr($substr, 0, $y);   
                    if($z == 0 && $invdet->kode_d_ger != "") {$dt .= $invdet->kode_d_ger . "|";}
                    else { $dt .= "           "; }
                    $dt .= str_pad($substr, 52);
                    if ($z == 0){$dt .= str_pad(number_format($invdet->nominal,0,",","."),13,' ',STR_PAD_LEFT);$z=1;}
                    $dt .= "\n";
                    $t = $t + 1;
                }
                $dt .= "           " . $ts . "\n"; 
            }
            else{
                if($invdet->kode_d_ger != "") { $dt .= $invdet->kode_d_ger . "|"; }
                else { $dt .= "           "; }
                $dt .= str_pad($ts, 52) . str_pad(number_format($invdet->nominal,0,",","."),13,' ',STR_PAD_LEFT);
                $dt .= "\n";
                $t = $t + 1;}

            //If the status of the transaction is 's' then save the transaction to d-ger.jurnal_admin
            if($invstatus == 's') {
            $ja = new JurnalAdmin();
            $ja->No_account = $bank->kode_d_ger2;
            $ja->No_bukti = $tempinv;
            $ja->Tanggal = $inv->dot->format('Y-m-d');
            $ja->Uraian = $invdet->description;
            $ja->Debet = 0;
            $ja->Kredit = $invdet->nominal;
            $ja->Kontra_acc = $invdet->kode_d_ger;
            $ja->Div = $invid . "inv";
            $ja->save();}
        }
        
        //$dt .= "           " . $inv->memo;

        for ($i=$t; $i <= 15 ; $i++) {$dt .= "\n";}
        $dt .= str_pad('Total Rp.', 62, ' ', STR_PAD_LEFT) . str_pad(number_format($inv->nominal,0,",","."),13,' ',STR_PAD_LEFT);
        //$dt = Chr(27) . Chr(69) . Chr(1) . $dt . Chr(27) . Chr(69) . Chr (0);

        $dt .= "\n";
        $cpl = 67;
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

        if ($invstatus == 's') {DB::table('invoices')->where('id',$inv->id)->update(['status' => 'p']); }
                
        return $dt;
    }
}
            
        
