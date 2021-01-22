<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Kaskecils;
use App\JurnalAdmin;
use App\AccountAdmin;
use App\StatusAccountAdmin;
use App\Invoices;
use App\InvoicesDetail;
use App\Bpenb;
use App\BpenbDetail;
use App\AccNum;
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

    public function mutasi() 
    {
        return view('accounting.mutasi');
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
            $flag = (strpos($coa,'.')) ? AccountAdmin::where('No_account',$coa)->value('Flag') : AccNum::where('accnum_id',$coa)->value('flag');
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

            if( strpos($coa, '.')){
                if($flag == 'D') {
                    $saw = $saa[0]->debet_awal + $dbt1 - $krd1;
                }
                else {
                    $saw = $saa[0]->kredit_awal + $krd1 - $dbt1;
                }
            }
            else {
                if($flag == 'DR') {
                    $saw = $dbt1 - $krd1;
                }
                else {
                    $saw = $krd1 - $dbt1;
                }   
            }
            //end
            //dd($dbt1 . " | " . $krd1);

            $transactions = DB::connection('mysql2')->select(DB::raw("select * from jurnal_admin where tanggal >= '$dt1' AND tanggal <= '$dt2' AND (No_account='$coa' OR Kontra_acc='$coa') order by tanggal asc, no_bukti asc"));
            
            //count total per coa
            $temp1 = JurnalAdmin::select(DB::raw('Kontra_acc, SUM(Debet) as totDebt, SUM(Kredit) as totKred'))
                ->where([['Tanggal','>=',$dt1],['Tanggal','<=',$dt2],['No_account',$coa]])->groupBy('Kontra_acc')->get();
            $deb1 = array_column($temp1->toArray(), 'totDebt', 'Kontra_acc');
            $kre1 = array_column($temp1->toArray(), 'totKred', 'Kontra_acc');
            $temp2 = JurnalAdmin::select(DB::raw('No_account, SUM(Debet) as totDebt, SUM(Kredit) as totKred'))
                    ->where([['Tanggal','>=',$dt1],['Tanggal','<=',$dt2],['Kontra_acc',$coa]])->groupBy('No_account')->get();
            $deb2 = array_column($temp2->toArray(), 'totDebt', 'No_account');
            $kre2 = array_column($temp2->toArray(), 'totKred', 'No_account');
            $sums = [];
            $temp1 = array_column($temp1->toArray(), 'totDebt','Kontra_acc');
            $temp2 = array_column($temp2->toArray(), 'totDebt','No_account');
            foreach (array_keys($temp1 + $temp2) as $key) {
                $db1 = isset($deb1[$key]) ? floatval($deb1[$key]) : 0;
                $kr1 = isset($kre1[$key]) ? floatval($kre1[$key]) : 0;
                $db2 = isset($deb2[$key]) ? floatval($deb2[$key]) : 0;
                $kr2 = isset($kre2[$key]) ? floatval($kre2[$key]) : 0;
                //$flag = AccountAdmin::where('No_account',$key)->value('Flag');
                $ket = AccountAdmin::where('No_account',$key)->value('Keterangan');
                $sums[] = array (
                        "coa" => $key,
                        "ket" => $ket,
                        "totKre" => ($kr1+$db2),
                        "totDeb" => ($db1+$kr2),
                    );
                /*if($flag == 'D') { 
                    $sums[] = array (
                        "coa" => $key,
                        "ket" => $ket,
                        "totKre" => ($kr1+$db2),
                        "totDeb" => ($db1+$kr2),
                    );
                }
                else {
                    $sums[] = array (
                        "coa" => $key,
                        "ket" => $ket,
                        "totKre" => ($db1+$kr2),
                        "totDeb" => ($kr1+$db2),
                    );
                }*/
            }
            //end
  //          dd($sums);
            
            return view('accounting.boa',compact('transactions','coa','saw','flag','sums'));}
        }

        return view('accounting.boa');
    }

    public function getSAA(Request $request) {
        $input = $request->all();
        $uri = (strpos($_SERVER['REQUEST_URI'], 'lama')) ? 'lama' : 'baru';
        //dd($uri);

        if(!empty($input)) {
            if( $uri == 'lama' ) {$aa = AccountAdmin::orderBy('No_account')->get();}
            else {$aa = AccNum::orderBy('accnum_id')->get();}
            $dt1 = $input['tanggal1'];$dt2 = $input['tanggal2'];
            //$dt1 = '2019-11-01';$dt2 = '2019-11-30';
            $fn = "SAA ". $uri ." ".$dt1." - ".$dt2;
            //dd($dt2);

            if( str_contains($uri, 'lama')) {
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

                if($coa->flag == 'D') {
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
                        'Debit Awal' =>number_format($debit,'2',',','.'),
                        'Kredit Awal' =>number_format($kredit,'2',',','.'),
                        'Debit' =>number_format($dbt,'2',',','.'),
                        'Kredit' =>number_format($krd,'2',',','.'),
                        'Debit Akhir' =>number_format($dak,'2',',','.'),
                        'Kredit Akhir' =>number_format($kak,'2',',','.'));
                }
            }
            else {
            foreach ($aa as $coa) {
                $dbt = 0;$krd = 0;$debit = 0;$kredit = 0;$dbt1 = 0;$krd1 = 0;$dak = 0;$kak = 0; 
                $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND No_account='$coa->accnum_id' AND tanggal >= '$dt1' AND tanggal <= '$dt2'"));
                $dbt = $dbt + $ja[0]->jadbt;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND No_account='$coa->accnum_id' AND tanggal >= '$dt1' AND tanggal <= '$dt2'"));
                $krd = $krd + $ja[0]->jakrd;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND Kontra_acc='$coa->accnum_id' AND tanggal >= '$dt1' AND tanggal <= '$dt2'"));
                $krd = $krd + $ja[0]->jadbt;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND Kontra_acc='$coa->accnum_id' AND tanggal >= '$dt1' AND tanggal <= '$dt2'"));
                $dbt = $dbt + $ja[0]->jakrd;

                
                $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND No_account='$coa->accnum_id' AND tanggal < '$dt1'"));
                $dbt1 = $dbt1 + $ja[0]->jadbt;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND No_account='$coa->accnum_id' AND tanggal < '$dt1'"));
                $krd1 = $krd1 + $ja[0]->jakrd;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(debet) as jadbt from jurnal_admin where kredit = 0 AND Kontra_acc='$coa->accnum_id' AND tanggal < '$dt1'"));
                $krd1 = $krd1 + $ja[0]->jadbt;

                $ja = DB::connection('mysql2')->select(DB::raw("select sum(kredit) as jakrd from jurnal_admin where debet = 0 AND Kontra_acc='$coa->accnum_id' AND tanggal < '$dt1'"));
                $dbt1 = $dbt1 + $ja[0]->jakrd;

                if($coa->flag == 'DR') {
                    $debit = $dbt1 - $krd1;
                    $dak = $debit + $dbt - $krd;$kak = 0;
                }
                else {
                    $kredit = $krd1 - $dbt1;
                    $kak = $kredit + $krd - $dbt;$dak = 0;
                }

                $temp[] = array('No account' =>$coa->accnum_id,
                        'Deskripsi' =>$coa->desc,
                        'Debit Awal' =>number_format($debit,'2',',','.'),
                        'Kredit Awal' =>number_format($kredit,'2',',','.'),
                        'Debit' =>number_format($dbt,'2',',','.'),
                        'Kredit' =>number_format($krd,'2',',','.'),
                        'Debit Akhir' =>number_format($dak,'2',',','.'),
                        'Kredit Akhir' =>number_format($kak,'2',',','.'));
            }
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
        return view('accounting.saa', compact('uri'));
    }

    public function getAllTransactions(Request $request)
    {
        if(empty($request->all())) {
            return view('accounting.gat');
        }

        ob_end_clean();
        ob_start();
        $dt = Carbon\Carbon::parse($request->all()['tanggal1']);
        $transactions = JurnalAdmin::whereMonth('Tanggal',$dt->month)->whereYear('Tanggal',$dt->year)->orderBy('Tanggal')->get();
        $temp = [];$no = 0;$fn = 'AllTrans ' . $dt->shortEnglishMonth . ' ' . $dt->year;
        //$temp[] = array('No' => 'All Transactions on ' . $dt->englishMonth . ' ' . $dt->year);

        foreach ($transactions as $trans) {$no++;
            //dd($trans);
            $temp[] = array('No' => $no,
                    'Tanggal' => Carbon\Carbon::parse($trans->Tanggal)->format('Y-m-d'),
                    'No_account' => $trans->No_account,
                    'Deskripsi acc' => AccNum::where('accnum_id',$trans->No_account)->value('desc'),
                    'No_bukti' => $trans->No_bukti,
                    'Uraian' => $trans->Uraian,
                    //'Debet' => $trans->Debet,
                    'Debet' => str_replace('.', ',', $trans->Debet),
                    //'Kredit' => $trans->Kredit,
                    'Kredit' => str_replace('.', ',', $trans->Kredit),
                    'Kontra_acc' => $trans->Kontra_acc,
                    'Deskripsi kontra' => AccNum::where('accnum_id',$trans->Kontra_acc)->value('desc'));
        }

        Excel::create($fn, function ($excel) use ($temp) {
            $excel->sheet('Sheet 1', function($sheet) use ($temp) {
                $sheet->setAutoSize(true);
                $sheet->setColumnFormat(array(
                    'G:H' => '#.##0,00'
                ));
                $sheet->fromArray($temp, null, 'A1', true);
            });
        })->store('xls','exports');

        return response()->json([
                'success' => true,
                'path' => 'http://'. $_SERVER['HTTP_HOST'].'/exports/'.$fn.'.xls'
            ]);
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
        ob_end_clean();
        ob_start();
        $input = $request->all();

        if(!empty($input)) {
            
            $dt1 = $input['tanggal1'];
            $dt2 = $input['tanggal2'];
            
            $coa = $input['kode_d_ger'];
            $flag = AccountAdmin::where('No_account',$coa)->value('Flag');
            $keterangan = AccountAdmin::where('No_account',$coa)->value('Keterangan');
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

            $transactions = DB::connection('mysql2')->select(DB::raw("select * from jurnal_admin where tanggal >= '$dt1' AND tanggal <= '$dt2' AND (No_account='$coa' OR Kontra_acc='$coa') ORDER BY Tanggal asc"));
        }

        $temp1 = JurnalAdmin::select(DB::raw('Kontra_acc, SUM(Debet) as totDebt, SUM(Kredit) as totKred'))
                ->where([['Tanggal','>=',$dt1],['Tanggal','<=',$dt2],['No_account',$coa]])->groupBy('Kontra_acc')->get();
        $deb1 = array_column($temp1->toArray(), 'totDebt', 'Kontra_acc');
        $kre1 = array_column($temp1->toArray(), 'totKred', 'Kontra_acc');
        $temp2 = JurnalAdmin::select(DB::raw('No_account, SUM(Debet) as totDebt, SUM(Kredit) as totKred'))
                ->where([['Tanggal','>=',$dt1],['Tanggal','<=',$dt2],['Kontra_acc',$coa]])->groupBy('No_account')->get();
        $deb2 = array_column($temp2->toArray(), 'totDebt', 'No_account');
        $kre2 = array_column($temp2->toArray(), 'totKred', 'No_account');
        $sums = [];
        $temp1 = array_column($temp1->toArray(), 'totDebt','Kontra_acc');
        $temp2 = array_column($temp2->toArray(), 'totDebt','No_account');
        foreach (array_keys($temp1 + $temp2) as $key) {
            $db1 = isset($deb1[$key]) ? floatval($deb1[$key]) : 0;
            $kr1 = isset($kre1[$key]) ? floatval($kre1[$key]) : 0;
            $db2 = isset($deb2[$key]) ? floatval($deb2[$key]) : 0;
            $kr2 = isset($kre2[$key]) ? floatval($kre2[$key]) : 0;
            //$flag = AccountAdmin::where('No_account',$key)->value('Flag');
            $ket = AccountAdmin::where('No_account',$key)->value('Keterangan');
            $sums[] = array (
                    "coa" => $key,
                    "ket" => $ket,
                    "totKre" => ($kr1+$db2),
                    "totDeb" => ($db1+$kr2),
                );
            }
        $total = 0;

        $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:70%;border-collapse: collapse;width: 100%;}';
        $td .= 'td, th {border: 1px solid; text-align: center;padding: 2px;}';
        $td .= 'tr:nth-child(even) {background-color: #dddddd;} @page {header: page-header;footer: page-footer;}</style>';
        $td .= '<body><htmlpageheader name="page-header"><H4>Laporan '. $keterangan .'('.$coa.') - ';

        $td .= ' ('.$dt1.' - '.$dt2.')</H4></htmlpageheader>';

        $td .= '<table align="center">';

        $td .= '<tr><th>Tanggal</th><th>No Bukti</th><th>Uraian</th><th>Debit</th><th>Kredit</th><th>Saldo</th><th>Kontra Acc</th></tr>';
        
        $dbt1 = 0;$krd1 = 0;$tempsaldo = 0;
        foreach ($transactions as $trans) {
            $td .= '<tr>';
            $td .= '<td>'.Carbon::parse($trans->Tanggal)->format('Y-m-d').'</td>';
            $td .= '<td>'.$trans->No_bukti.'</td>';
            $td .= '<td style="text-align:left;">'.$trans->Uraian.'</td>';
            if($trans->No_account == $coa)
            {
                $td .= '<td style="text-align:right;">'.number_format($trans->Debet,2,',','.').'</td>';
                $td .= '<td style="text-align:right;">'. number_format($trans->Kredit,2,',','.').'</td>';
                $dbt1 += $trans->Debet;$krd1 += $trans->Kredit;
                if($flag == 'D')
                    $tempsaldo += $trans->Debet - $trans->Kredit;
                else
                    $tempsaldo += $trans->Kredit - $trans->Debet;
                $td .= '<td style="text-align:right;">'.number_format($tempsaldo,2,',','.').'</td>';
                $td .= '<td>'.$trans->Kontra_acc.'</td>';
            }
            elseif ($trans->Kontra_acc == $coa) {
                if($trans->Kredit == 0)
                {
                    $krd1 += $trans->Debet;
                    $td .= '<td style="text-align:right;">0</td>';
                    $td .= '<td style="text-align:right;">'. number_format($trans->Debet,2,',','.').'</td>';
                }
                elseif($trans->Debet == 0)
                {
                    $dbt1 += $trans->Kredit;
                    $td .= '<td style="text-align:right;">'. number_format($trans->Kredit,2,',','.').'</td>';
                    $td .= '<td style="text-align:right;">0</td>';
                }

                if($flag == 'K')
                    $tempsaldo += $trans->Debet - $trans->Kredit;
                else
                    $tempsaldo += $trans->Kredit - $trans->Debet;
                $td .= '<td style="text-align:right;">'.number_format($tempsaldo,2,',','.').'</td>';
                $td .= '<td>'.$trans->No_account.'</td>';
            }
        }    
            
        $td .= '<tr><td colspan="3" style="text-align: right;">Total</td>';
        $td .= '<td>'.number_format($dbt1,2,',','.').'</td>';
        $td .= '<td>'.number_format($krd1,2,',','.').'</td><td colspan="2"></td></tr>';
        $td .= '<tr><td colspan="7" style="background-color: grey;">&nbsp;</td></tr>';
        $td .= '<tr><td colspan="3" style="text-align: right;">Saldo Awal</td>';
        $td .= '<td colspan="4" style="text-align: left;">'.number_format($saw,2,',','.').'</td></tr>';
        $td .= '<tr><td colspan="3" style="text-align: right;">Saldo Akhir</td>';
        $saw += $tempsaldo;
        $td .= '<td colspan="4" style="text-align: left;">'.number_format($saw,2,',','.').'</td></tr>';
        $td .= '<tr><td colspan="7" style="background-color: grey;">&nbsp;</td></tr>';
        $td .= '<tr><td colspan="7" style="text-align: center;"><strong>Jumlah per COA</strong></td></tr>';
        $td .= '<tr><td colspan="2" style="text-align: right;"><strong>Kontra COA</strong></td>';
        $td .= '<td><strong>Keterangan</strong></td>';
        $td .= '<td><strong>Debet</strong></td>';
        $td .= '<td><strong>Kredit</strong></td><td colspan="2"></td></tr>';
        $totDeb = 0; $totKre = 0;
        for ($i=0; $i < count($sums); $i++) {
            $td .= '<tr><td colspan="2" style="text-align: right;">' . $sums[$i]['coa'].'</td>';
            $td .= '<td style="text-align: left;">' . $sums[$i]['ket'] . '</td>';
            $td .= '<td style="text-align: right;">'.number_format($sums[$i]['totDeb'],2,',','.').'</td>';
            $td .= '<td style="text-align: right;">'.number_format($sums[$i]['totKre'],2,',','.').'</td>';
            $td .= '<td colspan="2"></td></tr>';
            $totDeb += $sums[$i]['totDeb']; $totKre += $sums[$i]['totKre'];
        }
        
        $td .= '<tr><td colspan="3" style="text-align: right;"><strong>TOTAL</strong></td>';
        $td .= '<td style="text-align: right;">'.number_format($totDeb,2,',','.').'</td>';
        $td .= '<td style="text-align: right;">'.number_format($totKre,2,',','.').'</td>';
        $td .= '<td colspan="2"></td></tr>';
        $td .= '<tr><td colspan="7" style="text-align: center;background-color: grey;"></td></tr>';
        
        $td .= '</table>';
        
        $td .= '<htmlpagefooter name="page-footer"><p align="right">{PAGENO} / {nb}</p></htmlpagefooter></body>';

        $pdf = PDF::loadHTML($td, ['format' => 'Folio', 'margin_left' => 10]);
        
        $tf = str_replace('/','','Laporan '. $keterangan .date('dmy').'.pdf');
        
        //Session::flash('flash_message','Request berhasil disimpan.');
        //dd(public_path('/storage/boa.pdf'));
        $pdf->save(public_path('/storage/'.$tf));
        
        //return $pdf->stream($tf);
        //return response()->download(public_path($tf),$tf,['Content-Type: application/pdf']);
        return response::json(['name'=>$tf,'filename'=>$tf]);
        //return redirect('kaskecil');
    }

    public function uploadFile(Request $request) {
        $input = $request->all();
            //dd($request);
            //*****************************************Input mutasi bcaum dari file txt (ganti bulan)
        if($request->hasFile('filebank')){
            if (substr($request->file('filebank')->getClientOriginalName(), -3) == "txt") {
            ob_end_clean();
            ob_start();

            $path = $request->file('filebank')->getRealPath();
            //$data = Excel::load($path, function($reader) {})->get();
            //$data = fopen($path,"r");
            $data = (file($path));
            $firstRow = $data[0];
            //dd($firstRow);
            if( str_contains($firstRow,'BDI')) {

            //$path = $request->file('file_exc')->getRealPath();
            //$data = Excel::load($path, function($reader) {})->get();
            $data = fopen($path,"r");dd(substr(file($path)[0],19,4));
            $totaldebit = 0;
            $totalkredit = 0;
            //print fgets($data)."<br>";
            //print_r(collect($data)->toJson());
            //print_r($data->count());
            
            //print "<tr><th>Tanggal</th><th>Kode Transaksi</th><th>NO VA</th><th>Kelas</th><th>Nama Siswa & Keterangan</th>";
            //print "<th>Debit</th><th>Kredit</th></tr>";
            $year = substr(fgets($data), 19, 4);
            $invno = Invoices::where('bank',2)->whereMonth('dot',12)->whereYear('dot', 2019)->get();$t = count($invno);
            $invno = Bpenb::where('bank',2)->whereMonth('dot',12)->whereYear('dot', 2019)->get();$t += count($invno);
            while(!feof($data))
            {
                $datos = fgets($data);

                if(substr($datos,0,2) == "03") { $t++;
                    //$strtemp = substr($datos,43);
                    //print_r(substr($strtemp,0,16));
                    $dbt = ltrim(substr($datos, 70,16), " ");$krt = ltrim(substr($datos, 94,16), " ");
                    $dbt = str_replace(',','.',str_replace(".", "", $dbt));$krt = str_replace(',','.',str_replace(".", "", $krt));
                    
                    if ($t < 10) {$invno = '00' . $t;}
                    elseif ($t < 100) {$invno = '0' . $t;}
                    else {$invno = $t;}

                    if($dbt != "0.0") {
                        $bpenb = new Invoices();
                        $bpenb->invoices_no = $invno;
                        $bpenb->bank = 2;
                        $bpenb->dot = $year."-".substr($datos,5,2)."-".substr($datos,7,2);
                        $bpenb->nominal = floatval($dbt);
                        $bpenb->status = 's';
                        $bpenb->user_id = 19;
                        $bpenb->aiw = Terbilang::make($dbt);
                        $bpenb->save();

                        $bdet = new InvoicesDetail();
                        $bdet->id_invoices = $bpenb->id;
                        $bdet->invoices_no = $invno;
                        (substr($datos, 12,2) == "BR") ? $bdet->description = substr($datos,17,25) : $bdet->description = substr($datos,12,30);
                        $bdet->nominal = floatval($dbt);
                        $bdet->save();
                    }
                    elseif ($krt != "0.0") {
                        $bpenb = new Bpenb();
                        $bpenb->invoices_no = $invno;
                        $bpenb->bank = 2;
                        $bpenb->dot = $year."-".substr($datos,5,2)."-".substr($datos,7,2);
                        $bpenb->nominal = floatval($krt);
                        $bpenb->status = 's';
                        $bpenb->user_id = 19;
                        $bpenb->aiw = Terbilang::make($krt);
                        $bpenb->save();

                        $bdet = new BpenbDetail();
                        $bdet->id_bpenb = $bpenb->id;
                        $bdet->invoices_no = $invno;
                        (substr($datos, 12,2) == "BR") ? $bdet->description = substr($datos,17,25) : $bdet->description = substr($datos,12,30);
                        $bdet->nominal = floatval($krt);
                        $bdet->save();
                    }
                    //print $year."-".substr($datos,8,2)."-".substr($datos,6,2);
                    //print substr($datos,44,60);
                    //print "Rp. ". (($dbt != ".00") ? $dbt : "0.00") . "|";$totaldebit += substr($datos, 104,16);
                    //print "Rp. ". (($krt != ".00") ? $krt : "0.00") . "|";$totalkredit += substr($datos, 120,16);
                }
                //else
                    //print "Total: Rp. $totaldebit | Rp. $totalkredit";
            fclose($data);
            }
            }
            else if( str_contains($firstRow,'Informasi Rekening - Mutasi Rekening') && str_contains($data[2], '7750381132')) {
            $totaldebit = 0;
            $totalkredit = 0;
            //print fgets($data)."<br>";
            //print_r(collect($data)->toJson());
            //print_r($data->count());
            
            //print "<tr><th>Tanggal</th><th>Kode Transaksi</th><th>NO VA</th><th>Kelas</th><th>Nama Siswa & Keterangan</th>";
            //print "<th>Debit</th><th>Kredit</th></tr>";

            $jmldata = count($data)-5;
            $year = substr($data[4],17,4);
            $invno = Invoices::where('bank',1)->whereMonth('dot',11)->whereYear('dot', 2019)->get();$t = count($invno);
            $invno = Bpenb::where('bank',1)->whereMonth('dot',11)->whereYear('dot', 2019)->get();$t += count($invno);
            for ($i=7; $i < $jmldata; $i++)
            {
                $temp = explode('|',str_replace('"','',str_replace('","', '"|"', $data[$i])));
                $t++;
                    
                $dbcr = explode(" ", $temp[3])[1];
                $nominal = str_replace(",","",explode(" ", $temp[3])[0]);//dd($nominal);
                $date = $year . "-" . explode("/", $temp[0])[1] . "-" . explode("/", $temp[0])[0];
                
                if ($t < 10) {$invno = '00' . $t;}
                elseif ($t < 100) {$invno = '0' . $t;}
                else {$invno = $t;}

                if($dbcr == "DB") {
                    $bpenb = new Invoices();
                    $bpenb->invoices_no = $invno;
                    $bpenb->bank = 1;
                    $bpenb->dot = $date;
                    $bpenb->nominal = floatval($nominal);
                    $bpenb->status = 's';
                    $bpenb->user_id = 19;
                    $bpenb->aiw = Terbilang::make($nominal);
                    $bpenb->save();

                    $bdet = new InvoicesDetail();
                    $bdet->id_invoices = $bpenb->id;
                    $bdet->invoices_no = $invno;
                    $bdet->description = rtrim($temp[1]);
                    $bdet->nominal = floatval($nominal);
                    $bdet->save();
                }
                elseif ($dbcr == "CR") {
                    $bpenb = new Bpenb();
                    $bpenb->invoices_no = $invno;
                    $bpenb->bank = 1;
                    $bpenb->dot = $date;
                    $bpenb->nominal = floatval($nominal);
                    $bpenb->status = 's';
                    $bpenb->user_id = 19;
                    $bpenb->aiw = Terbilang::make($nominal);
                    $bpenb->save();

                    $bdet = new BpenbDetail();
                    $bdet->id_bpenb = $bpenb->id;
                    $bdet->invoices_no = $invno;
                    $bdet->description = rtrim($temp[1]);
                    $bdet->nominal = floatval($nominal);
                    $bdet->save();
                }
                    //print $year."-".substr($datos,8,2)."-".substr($datos,6,2);
                    //print substr($datos,44,60);
                    //print "Rp. ". (($dbt != ".00") ? $dbt : "0.00") . "|";$totaldebit += substr($datos, 104,16);
                    //print "Rp. ". (($krt != ".00") ? $krt : "0.00") . "|";$totalkredit += substr($datos, 120,16);
                
                //else
                    //print "Total: Rp. $totaldebit | Rp. $totalkredit";
            }
            }
            }
            else {
                return view('testing');
            }
        }

        return view('accounting.mutasi');
    }

    public function sendKA() {
        /*$transactions = JurnalAdmin::where([['Tanggal','>=','2018-07-01'],['Tanggal','<=','2019-10-31'],['Kontra_acc','LIKE',"626.1%"],['status','S'],['No_bukti','NOT LIKE','%KK%'],['No_bukti','NOT LIKE','%KA%'],['Debet','!=','0'],['Kredit',0]])->get();*/
        /*$transactions = JurnalAdmin::where([['Tanggal','>=','2018-07-01'],['Tanggal','<=','2019-10-31'],['status','S'],['No_bukti','NOT LIKE','%KK%'],['No_bukti','NOT LIKE','%KA%']])->where(function($q){$q->where('Kontra_acc','LIKE',"626.1%")->orWhere('No_account','LIKE',"626.1%");})->get();*/
        /*$transactions = JurnalAdmin::where([['Tanggal','>=','2018-07-01'],['Tanggal','<=','2019-10-31'],['Kontra_acc','LIKE',"626.1%"],['status','S'],['No_bukti','NOT LIKE','%KK%'],['No_bukti','NOT LIKE','%KA%']])->get();*/

        $dt = Carbon::now()->subMonthsNoOverflow(1)->lastOfMonth()->format('Y-m-d');
        //dd($dt);
        $temp = JurnalAdmin::where([['Tanggal','>=','2019-01-01'],['Tanggal','<=',$dt],['No_bukti','NOT LIKE','%KA%'],['No_bukti','NOT LIKE','%KK%'],['status','S']])->where(function($q){$q->where('Kontra_acc','LIKE',"626.1%")->orWhere('No_account','LIKE',"626.1%");})->orderBy('Tanggal');
        $transactions = $temp->get();
        //dd($transactions->update(['status'=>'L']));

        foreach ($transactions as $trans) {
            //if(($trans->Debet == 0 AND str_contains($trans->Kontra_acc, "626.1")) || ($trans->Kredit == 0 AND str_contains($trans->No_account, "626.1"))) 
            //$kaskecil->kode_d_ger = $trans->Kontra_acc;
            //$kaskecil->kode_unit = substr($trans->Kontra_acc, 5,1);
            //$kaskecil->nominal = '-' . str_replace("[.][\d][\d]", "", $trans->Debet);
            $kaskecil = new Kaskecils();
            $kaskecil->no_bukti = $trans->No_bukti;
            $kaskecil->deskripsi = $trans->Uraian;
            $kaskecil->tanggal_trans = $trans->Tanggal;
            $kaskecil->status = "da" . $trans->id;
            if(str_contains($trans->No_account, "626.1")) {
                $kaskecil->kode_d_ger = $trans->No_account;
                $kaskecil->kode_unit = substr($trans->No_account, 5,1);
                if($trans->Kredit == 0) {$kaskecil->nominal = str_replace("[.][\d][\d]", "", $trans->Debet);}
                else {$kaskecil->nominal = '-' . str_replace("[.][\d][\d]", "", $trans->Kredit);}
            }
            else if(str_contains($trans->Kontra_acc, "626.1")) {
                $kaskecil->kode_d_ger = $trans->Kontra_acc;
                $kaskecil->kode_unit = substr($trans->Kontra_acc, 5,1);
                if($trans->Debet == 0) {$kaskecil->nominal = str_replace("[.][\d][\d]", "", $trans->Kredit);}
                else {$kaskecil->nominal = '-' . str_replace("[.][\d][\d]", "", $trans->Debet);}
            }
            $kaskecil->save();
        }
        
        $temp->update(['status'=>'L']);
        /*JurnalAdmin::where([['Tanggal','>=','2018-07-01'],['Tanggal','<=','2019-10-31'],['Kontra_acc','LIKE',"626.1%"],['status','S'],['No_bukti','NOT LIKE','%KK%'],['No_bukti','NOT LIKE','%KA%']])->update(['status' => 'L']);*/
    }
}