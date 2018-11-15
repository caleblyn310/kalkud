<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\JurnalAdmin;
use App\AccountAdmin;
use App\StatusAccountAdmin;
use DB;
use Session;
use Excel;
use Validator;
use PDF;
use Carbon;
use Response;

class AccountingController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function convertBCA()
    {
        return view('convertbca');
    }

    public function boa(Request $request) {
        $input = $request->all();

        if(!empty($input)) {
            
            $dt1 = $input['tanggal1'];
            $dt2 = $input['tanggal2'];
            
            $validator = Validator::make($input, [
                'kode_d_ger' => 'required',
                'tanggal2' => 'required|before_or_equal:'.date('Y-m-d'),
                'tanggal1' => 'required|before:'.$dt2],[
                    'kode_d_ger.required' => 'Kode D-Ger belum di input',
                    'tanggal2.required' => 'Tanggal2 belum di input',
                    'tanggal2.before_or_equal' => 'Tanggal2 harus sebelum / sama dengan hari ini',
                    'tanggal1.required' => 'Tanggal1 belum di input',
                    'tanggal1.before' => 'Tanggal1 harus sebelum Tanggal2'
            ]);

            if ($validator->fails())
                { return redirect('boa')->withInput()->withErrors($validator); }
            else {
            $coa = $input['kode_d_ger'];
            $flag = AccountAdmin::where('No_account',$coa)->value('Flag');
            $dbt1 = 0;$krd1 = 0;$saw = 0;

            //get saldo awal
            $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND No_account='$coa' AND tanggal < '$dt1'"));
            $dbt1 = $dbt1 + $ja[0]->jadbt;

            $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND No_account='$coa' AND tanggal < '$dt1'"));
            $krd1 = $krd1 + $ja[0]->jakrd;

            $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND Kontra_acc='$coa' AND tanggal < '$dt1'"));
            $krd1 = $krd1 + $ja[0]->jadbt;

            $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND Kontra_acc='$coa' AND tanggal < '$dt1'"));
            $dbt1 = $dbt1 + $ja[0]->jakrd;

            $saa = StatusAccountAdmin::where('No_account',$coa)->get();

            if($flag == 'D') {
                $saw = $saa[0]->debet_awal + $dbt1 - $krd1;
            }
            else {
                $saw = $saa[0]->kredit_awal + $krd1 - $dbt1;
            }
            //end

            $transactions = DB::connection('mysql2')->select(DB::raw("select * from jurnal_admin where tanggal >= '$dt1' AND tanggal <= '$dt2' AND (No_account='$coa' OR Kontra_acc='$coa')"));
            
            return view('accounting.boa',compact('transactions','coa','saw','flag'));}
        }

        return view('accounting.boa');
    }

    public function getSAA(Request $request) {
        $input = $request->all();

        if(!empty($input)) {
            $aa = AccountAdmin::orderBy('No_account')->get();

            $dt1 = $input['tanggal1'];$dt2 = $input['tanggal2'];
            $fn = "SAA ".$dt1." - ".$dt2;
            //dd($dt2);

            foreach ($aa as $coa) {
                $dbt = 0;$krd = 0;$debit = 0;$kredit = 0;$dbt1 = 0;$krd1 = 0;$dak = 0;$kak = 0;
                $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND No_account='$coa->No_account' AND tanggal >= '$dt1' AND tanggal <= '$dt2'"));
                $dbt = $dbt + $ja[0]->jadbt;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND No_account='$coa->No_account' AND tanggal >= '$dt1' AND tanggal <= '$dt2'"));
                $krd = $krd + $ja[0]->jakrd;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND Kontra_acc='$coa->No_account' AND tanggal >= '$dt1' AND tanggal <= '$dt2'"));
                $krd = $krd + $ja[0]->jadbt;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND Kontra_acc='$coa->No_account' AND tanggal >= '$dt1' AND tanggal <= '$dt2'"));
                $dbt = $dbt + $ja[0]->jakrd;

                //get saldo awal
                $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND No_account='$coa->No_account' AND tanggal < '$dt1'"));
                $dbt1 = $dbt1 + $ja[0]->jadbt;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND No_account='$coa->No_account' AND tanggal < '$dt1'"));
                $krd1 = $krd1 + $ja[0]->jakrd;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND Kontra_acc='$coa->No_account' AND tanggal < '$dt1'"));
                $krd1 = $krd1 + $ja[0]->jadbt;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND Kontra_acc='$coa->No_account' AND tanggal < '$dt1'"));
                $dbt1 = $dbt1 + $ja[0]->jakrd;

                $saa = StatusAccountAdmin::where('No_account',$coa->No_account)->get();

                if($coa->Flag == 'D') {
                    $debit = $saa[0]->debet_awal + $dbt1 - $krd1;
                    $dak = $debit + $dbt - $krd;$kak = 0;
                }
                else {
                    $kredit = $saa[0]->kredit_awal + $krd1 - $dbt1;
                    $kak = $kredit + $krd - $dbt;$dak = 0;
                }
                //end get saldo awal


                $temp[] = array('No account' =>$coa->No_account,
                        'Deskripsi' =>$coa->Keterangan,
                        'Debit Awal' =>$debit,
                        'Kredit Awal' =>$kredit,
                        'Debit' =>$dbt,
                        'Kredit' =>$krd,
                        'Debit Akhir' =>$dak,
                        'Kredit Akhir' =>$kak);
            }

            Excel::create($fn, function($excel) use ( $temp) {
                $excel->sheet('Sheet 1', function($sheet) use ($temp)
                {
                    $sheet->setAutoSize(true);
                    $sheet->fromArray($temp,null,'A1',true);
                });
                })->store('csv','exports');

            return response()->json([
                'success' => true,
                'path' => 'http://'. $_SERVER['HTTP_HOST'].'/exports/'.$fn.'.csv'
            ]);
            //return redirect('getSAA');
        }
        return view('accounting.saa');
    }

    public function recalculate() {
    	$aa = AccountAdmin::orderBy('No_account')->get();
    	//dd($aa);
    	foreach ($aa as $coa) {
    		$flag = $coa->flag;
    		$saldo = $coa->saldo;
    		$jadebit = 0;$jakredit = 0;

    		$ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as debet, sum(kredit) as kredit from jurnal_admin where No_account='$coa->No_account'"));
    		//dd($ja);
    		$jadebit = $jadebit + $ja[0]->debet;
    		$jakredit = $jakredit + $ja[0]->kredit;

    		$ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as debet, sum(kredit) as kredit from jurnal_admin where Kontra_acc='$coa->No_account'"));

    		$jadebit = $jadebit + $ja[0]->kredit;
    		$jakredit = $jakredit + $ja[0]->debet;

    		if ($flag == 'D') {
    			$saa_daw = $saldo;
    			$saa_dak = $saldo + $jadebit - $jakredit;
    			$saa_kak = 0;
    			StatusAccountAdmin::where('No_account',$coa->No_account)->update([['debet'=>$jadebit],['kredit'=>$jakredit],['debet_awal'=>$saa_daw],['debet_akhir'=>$saa_dak],['kredit_akhir'=>$saa_kak]]);
    		}
    		else if ($flag == 'K') {
    			$saa_kaw = $saldo;
    			$saa_kak = $saldo + $jakredit - $jadebit;
    			$saa_dak = 0;
    			StatusAccountAdmin::where('No_account',$coa->No_account)->update([['debet'=>$jadebit],['kredit'=>$jakredit],['kredit_awal'=>$saa_kaw],['kredit_akhir'=>$saa_kak],['debet_akhir'=>$saa_dak]]);
    		}
    	}
        Session::flash('flash_message', 'Recalculated...');
        return redirect('/');
    }

    public function mPDFGen(Request $request)
    {
        $input = $request->all();

        if(!empty($input)) {
            
            $dt1 = $input['tanggal1'];
            $dt2 = $input['tanggal2'];
            
            $coa = $input['kode_d_ger'];
            $flag = AccountAdmin::where('No_account',$coa)->value('Flag');
            $ket = AccountAdmin::where('No_account',$coa)->value('Keterangan');
            $dbt1 = 0;$krd1 = 0;$saw = 0;

            //get saldo awal
            $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND No_account='$coa' AND tanggal < '$dt1'"));
            $dbt1 = $dbt1 + $ja[0]->jadbt;

            $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND No_account='$coa' AND tanggal < '$dt1'"));
            $krd1 = $krd1 + $ja[0]->jakrd;

            $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND Kontra_acc='$coa' AND tanggal < '$dt1'"));
            $krd1 = $krd1 + $ja[0]->jadbt;

            $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND Kontra_acc='$coa' AND tanggal < '$dt1'"));
            $dbt1 = $dbt1 + $ja[0]->jakrd;

            $saa = StatusAccountAdmin::where('No_account',$coa)->get();

            if($flag == 'D') {
                $saw = $saa[0]->debet_awal + $dbt1 - $krd1;
            }
            else {
                $saw = $saa[0]->kredit_awal + $krd1 - $dbt1;
            }
            //end

            $transactions = DB::connection('mysql2')->select(DB::raw("select * from jurnal_admin where tanggal >= '$dt1' AND tanggal <= '$dt2' AND (No_account='$coa' OR Kontra_acc='$coa')"));
        }

        $total = 0;

        $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:65%;border-collapse: collapse;width: 100%;}';
        $td .= 'td, th {border: 1px solid; text-align: center;padding: 2px;}';
        $td .= 'tr:nth-child(even) {background-color: #dddddd;} @page {header: page-header;footer: page-footer;}</style>';
        $td .= '<body><htmlpageheader name="page-header"><H4>Laporan '. $ket .' - ';

        $td .= ' ('.$dt1.' - '.$dt2.')</H4></htmlpageheader>';

        $td .= '<table align="center">';

        $td .= '<tr><th>Tanggal</th><th>No Bukti</th><th>Uraian</th><th>Debit</th><th>Kredit</th><th>Saldo</th><th>Kontra Acc</th></tr>';
        
        $dbt1 = 0;$krd1 = 0;$tempsaldo = 0;
        foreach ($transactions as $trans) {
            $td .= '<tr>';
            $td .= '<td>'.Carbon::parse($trans->Tanggal)->format('d-m-Y').'</td>';
            $td .= '<td>'.$trans->No_bukti.'</td>';
            $td .= '<td style="text-align:left;">'.$trans->Uraian.'</td>';
            if($trans->No_account == $coa)
            {
                $td .= '<td style="text-align:right;">'.number_format($trans->Debet,0,'','.').'</td>';
                $td .= '<td style="text-align:right;">'. number_format($trans->Kredit,0,'','.').'</td>';
                $dbt1 += $trans->Debet;$krd1 += $trans->Kredit;
                if($flag == 'D')
                    $tempsaldo += $trans->Debet - $trans->Kredit;
                else
                    $tempsaldo += $trans->Kredit - $trans->Debet;
                $td .= '<td>'.number_format($tempsaldo,0,'','.').'</td>';
                $td .= '<td>'.$trans->Kontra_acc.'</td>';
            }
            elseif ($trans->Kontra_acc == $coa) {
                if($trans->Kredit == 0)
                {
                    $krd1 += $trans->Debet;
                    $td .= '<td style="text-align:right;">0</td>';
                    $td .= '<td style="text-align:right;">'. number_format($trans->Debet,0,'','.').'</td>';
                }
                elseif($trans->Debet == 0)
                {
                    $dbt1 += $trans->Kredit;
                    $td .= '<td style="text-align:right;">'. number_format($trans->Kredit,0,'','.').'</td>';
                    $td .= '<td style="text-align:right;">0</td>';
                }

                if($flag == 'K')
                    $tempsaldo += $trans->Debet - $trans->Kredit;
                else
                    $tempsaldo += $trans->Kredit - $trans->Debet;
                $td .= '<td>'.number_format($tempsaldo,0,'','.').'</td>';
                $td .= '<td>'.$coa.'</td>';
            }
        }    
            
        $td .= '<tr><td colspan="3" style="text-align: right;">Total</td>';
        $td .= '<td>'.$dbt1.'</td>';
        $td .= '<td>'.$krd1.'</td><td colspan="2"></td></tr>';
        $td .= '<tr><td colspan="7">&nbsp;</td></tr>';
        $td .= '<tr><td colspan="3" style="text-align: right;">Saldo Awal</td>';
        $td .= '<td colspan="4" style="text-align: left;">'.$saw.'</td></tr>';
        $td .= '<tr><td colspan="3" style="text-align: right;">Saldo Akhir</td>';
        $saw += $tempsaldo;
        $td .= '<td colspan="4" style="text-align: left;">'.$saw.'</td></tr>';
        
        $td .= '</table>';
        
        $td .= '<htmlpagefooter name="page-footer"><p align="right">{PAGENO} / {nb}</p></htmlpagefooter></body>';

        $pdf = PDF::loadHTML($td);
        
        $tf = 'Laporan '. $ket .date('dmy').'.pdf';
        
        //Session::flash('flash_message','Request berhasil disimpan.');
        $pdf->save(public_path('/storage/'.$tf));
        //dd(public_path($tf));
        //return $pdf->stream($tf);
        //return response()->download(public_path($tf),$tf,['Content-Type: application/pdf']);
        return response::json(['name'=>$tf,'filename'=>$tf]);
        //return redirect('kaskecil');
}
}