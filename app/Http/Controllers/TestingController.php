<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mike42\Escpos\Printer; 
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use Carbon\Carbon;
use App\Testing;
use App\Siswa;
use App\Customuser;
use App\Inventory;
use App\Kaskecil;
use App\Invoices;
use App\InvoicesDetail;
use App\JurnalAdmin;
use App\AccountAdmin;
use App\LandAsset;
use App\Bpenb;
use App\BpenbDetail;
use App\Bank;
use App\DaftarBarang;
use App\Pembelian;
use App\PembelianDetail;
use App\RekapVABCA;
use App\StokAkhir;
use App\PeriodeSak;
use App\TransferStok;
use App\Finger;
use App\Coba;
use App\Inven;
use App\KodeUnit;
use App\Temporari;
use App\Klasifikasi;
use App\AccNum;
use Excel;
use Input;
use DB;
use Response;
use DateTime;
use Log;
use Mail;
use Terbilang;
use PDF;
use App\Jobs\SendWelcomeEmail;
//use Bca;

class TestingController extends Controller
{


    public function __construct()
    {
        //$this->middleware('auth');
        //dd(Hash::make('keuangan'));
    }

    public function index()
    {
        
        //$this->generateSticker();
        //$this->generateList();
        $this->generateLists();

        //dd(Finger::all()->toArray());

        //$dis_tgl = Coba::distinct()->get(['tgl']);
        //$idp = Coba::where('tgl',$dis_tgl->toArray()[0]["tgl"])->distinct()->get();

        // ***** Membuat file txt yang berisi INSERT SQL untuk keperluan skkkbandung.or.id/absensi *****//
        /*$hasil = DB::table('coba')->select('idp','tgl',DB::raw('min(wkt) as min'),DB::raw('max(wkt) as max'))
                ->groupBy('tgl','idp')->orderBy('tgl')->orderBy('idp')->get();
        $myfile = fopen("ctl180820.txt", "a");
        $f = "INSERT INTO `finger` (`row_id`, `id`, `work_date`, `in_time`, `out_time`, `last_update`, `loc_in`, `loc_out`, `reduction`, `not_late`) VALUES (NULL, ";
        $l = " CURRENT_TIMESTAMP, 'CTL', 'CTL', '0', '0');";
        foreach ($hasil as $v) {
            $txt = $f . "'$v->idp', '$v->tgl', '$v->min', '$v->max'," . $l;
            fwrite($myfile, $txt . "\r\n");
        }
        fclose($myfile);*/
        // ***** END HERE ***** //

        /*$trans = DB::select("select d.nama_barang, s.qty_terpakai, (s.qty_terpakai * s.hpp) as hpp_terpakai,
                            s.qty_akhir, (s.qty_akhir * s.hpp) as hpp_akhir, s.hpp from daftar_barang d, stok_akhir s
                            where d.id = s.id_barang and s.id_periodesak = 3 order by d.kategori, d.nama_barang");
        dd($trans[0]->hpp_terpakai + $trans[0]->hpp_akhir);*/
        //****************************************Rekap VA BCA dari txt
        /*$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('/home/adit/Downloads/2019'),RecursiveIteratorIterator::SELF_FIRST);
        $apaaja = 0;$total = 0;
        foreach($iterator as $file) {
            if($file->isFile() && $file->getExtension() == 'txt') {
                $fp = fopen($file->getRealPath(), 'r');$row = 4;
                $fr = substr(fgets($fp), 0, 31);
                $temp = fgets($fp); $date = '20' . substr($temp, 131, 2) . '-' . substr($temp, 128,2) . '-' . substr($temp, 125, 2);
                $kodecab = substr(fgets($fp), 19, 7);$kodeper = substr(fgets($fp), 19, 5);
                $temp = RekapVABCA::where('tanggal',$date)->get();
                if($fr == '1RETENSI         : RA.1B/6B/10T' && $kodecab == '0006765' && $kodeper == '06862' && count($temp) == 0) {
                $apaaja++;
                while(!feof($fp)) {
                    $datos = fgets($fp); $row++;
                    if($row >= 10){
                        if (substr($datos, 5, 1) > 0 || substr($datos, 4, 1) > 0 ){
                        $nominal = str_replace(",", "", ltrim(substr($datos, 58,10)," "));
                        $vabca = new RekapVABCA();
                        $vabca->tanggal = $date;
                        $vabca->no_va = $kodeper.substr($datos, 8,11);
                        $vabca->nominal = $nominal;
                        $vabca->save();
                        if (substr($datos, 8,4) == '2019') $total += $nominal;
                        }
                    }
                }}
                else {
                    dd($file);
                }
                fclose($fp);
            }
        }
        dd($total);*/
        //end

        //$inv = Invoices::findOrFail(30);
        //$expmemo = explode("\r\n", $inv->memo);
        //dd(count(explode("\r\n", $inv->memo)));
        /*$apa = Testing::all();
        for ($i=0;$i<$apa->count();$i++)
            $apaja[$apa[$i]['table_coba_id']] = $apa[$i]['Nama'];
        $apaapa = Customuser::where('nama',Auth::user()->name)->value('kode_cabang');
        //print_r($GLOBALS['kode_unit']);*/
        /*$temporary = DB::table('invoices')
                    ->join('invoices_detail','invoices.invoices_no','=', 'invoices_detail.invoices_no')
                    ->select('invoices.dot','invoices_detail.invoices_no','invoices_detail.description','invoices_detail.nominal')
                    ->get();*/
                    //dd(Carbon::parse($temporary[0]->dot)->format('Y-m-d'));
        /*$token = "Dxe0aSewwz7b3nHpxDEOCFsgc13iM3HEDjnvWWVC3cJDTu9XROIQ2W";
        $arrayAccNumber = array/*('0201245680', ('0063001004');

        $response = \Bca::getBalanceInfo($token, $arrayAccNumber);
        echo json_encode($response);
        // LIHAT HASIL OUTPUT
        dd($response);*/
        //$response = \Bca::httpAuth();
        //dd($response);
        
        /*$client = new \GuzzleHttp\Client();
        $request = $client->get('https://kalamkudus.or.id/kaskecil/api/index');
        $response = $request->getBody()->getContents();
        dd($response);*/

        /*$subtotal = Inventory::groupBy('id_cat')->selectRaw('count(*) as subtotal, id_cat')->get();
        $subtotal = $subtotal->toArray();$tst = 1;
        foreach ($subtotal as $st) {
            $tst = $tst + $st['subtotal'];
            print_r(++$tst);
            print_r(++$tst);
        }*/

        $temp = Inventory::findOrFail(3);
        //$temp = new DateTime($temp->tanggal_beli->format('Y-m'));
        $temp = new DateTime("2017-12-30");
        //dd($temp->modify("2018-01"));
        $td = new DateTime(date('Y-m-d'));
        $temp = $td->diff($temp)->m;
        //if($temp == 4)
        //dd($temp);
        //else
            //dd("wrong");
        $ts = "The fox blowing your mindss";
        $result = '';
        while (strlen($ts) > 10) {
                $substr = str_limit($ts,10,'');
                $y = strripos($substr,' ');
                $ts = substr($ts, $y+1);
                $substr = substr($substr, 0, $y);
                $result = $result . $substr . "|";
        } 
        //dd($result.$ts);
        return view('testing');
    }

    private function generateSticker() {
        /********** START PDF GENERATE STICKER INVEN ************/
        $td = '<style>html {margin-top:5px;} table {font-family: arial, sans-serif;font-size:300%; font-weight: bold; border-collapse: collapse;width: 100%;}';
        $td .= 'td, th {border: 1px solid; text-align: center;padding: 5px;}';
        $td .= '@page {header: page-header;footer: page-footer;}</style>';
        $td .= '<body>';

        /*********** Untuk Satu Ruangan atau Satu Sticker atau Satu kelompok yang sama ***********/
        $td .= '<table align="center">';

        //$testtest = Inven::where('lokasi','like','ruang dirpel')->orderBy('lokasi')->orderBy('tipe_brg')->orderBy('id')->get();$t = 0;
        //$testtest = Inven::where('id','>=',4005)->orderBy('lokasi')->orderBy('tipe_brg')->orderBy('id')->get();$t = 0;
        //$testtest = Inven::where('id','>=',3987)->where('id','<=',4004)->orderBy('id')->get();$t = 0;
        //$testtest = Inven::where('id',1094)->orWhere('id',2316)->orderBy('id')->get();$t = 0;
        $testtest = Inven::where('id',4184)->orderBy('id')->get();$t = 0;
        //$testtest = Inven::where('lokasi','RUANG KOPERASI')->whereDate('created_at','2020-11-19')->get();$t = 0;
        //$testtest = Inven::whereDate('created_at','2021-01-13')->get();$t = 0;
        //$testtest = Inven::where('lokasi','Kelas 11 IPA1')->orWhere('id','>=',3955)->get();$t = 0;
        
        /*********** Untuk Beberapa Ruangan / Kelompok ***********/
        //$testtest = Inven::select('lokasi')->distinct()->whereDate('created_at','2021-01-13')->orderBy('lokasi')->get();
        //dd($testtest->toArray());
        
        /*foreach ($testtest as $test2) {
            $temp = Inven::where('lokasi',$test2->lokasi)->whereDate('created_at','2021-01-13')->orderBy('id')->get();$t = 0;
            $td .= '<table>';

            foreach ($temp as $test) {
            if($t == 0) { $td .= '<tr>'; }

            if($test->id < 10000) { $td .= "/0" . $test->id; }
            $td .= "<td>" . substr($test->tahun, 2, 2) . "/0" . $test->unit . "-" . KodeUnit::where('id',$test->unit)->value('lokasi') ."/".$test->sumber_dana ;
            if($test->id < 10) { $td .= "/0000" . $test->id; }
            else if ($test->id < 100) { $td .= "/000" . $test->id; }
            else if ($test->id < 1000) { $td .= "/00" . $test->id; }
            else if ($test->id < 10000) { $td .= "/0" . $test->id; }
            else { $td .= "/" . $test->id; }
            $td .= "</td>"; $t++;
            if($t == 2) { $td .= '</tr>'; $t = 0;}
        }
        $td .= "</table>";
        }
        $td .= "<htmlpagefooter name='page-footer'>";
        $td .= "<p align='right' style='font-size:10px;'>{PAGENO} / {nb}</p>";
        $td .= "</htmlpagefooter></body>";*/

        foreach ($testtest as $test) {
            if($t == 0) { $td .= '<tr>'; }

            if($test->id < 10000) { $td .= "/0" . $test->id; }
            //for ($i=0; $i < 2; $i++) { 
                $td .= "<td>" . substr($test->tahun, 2, 2) . "/0" . $test->unit . "-" . KodeUnit::where('id',$test->unit)->value('lokasi') ."/".$test->sumber_dana ;
            if($test->id < 10) { $td .= "/0000" . $test->id; }
            else if ($test->id < 100) { $td .= "/000" . $test->id; }
            else if ($test->id < 1000) { $td .= "/00" . $test->id; }
            else if ($test->id < 10000) { $td .= "/0" . $test->id; }
            else { $td .= "/" . $test->id; }
            $td .= "</td>"; $t++;
            //}
            
            if($t == 2) { $td .= '</tr>'; $t = 0;}
        }

        //$td .= "</table></body>";
        $td .= "<td style='width:70%;'></td></tr></table></body>";

        $pdf = PDF::loadHTML($td);

        $tf = 'Sticker Inven.pdf';
        
        //Session::flash('flash_message','Request berhasil disimpan.');
        //dd(public_path('/storage/boa.pdf'));
        //$pdf->save(public_path('/storage/'.$tf));
        $pdf->stream($tf);
        /********** END PDF GENERATE STICKER INVEN ***********/
    }

    private function generateList() {
        /********** START PDF GENERATE LIST INVEN ************/
        $lok = "";
        //$testtest = Inven::where('lokasi','like',$lok)->orderBy('lokasi')->orderBy('tipe_brg')->orderBy('id')->get();
        //$testtest = Inven::where('lokasi','like','Kelas 11 IPA1')->orderBy('id')->get();

        $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:80%; border-collapse: collapse;width: 100%;}';
        $td .= 'td, th {border: 1px solid; text-align: center;padding: 3px;}';
        $td .= 'tr:nth-child(even) {background-color: #dddddd;} @page {header: page-header;footer: page-footer;}</style>';
        $td .= '<body><htmlpageheader name="page-header"><strong>'. $lok .'</strong></htmlpageheader>';

        $td .= '<table align="center"><tr><th>Kode</th><th>Keterangan</th><th>Tipe</th><th>Bahan</th></tr>';

        foreach ($testtest as $test) {
            $kd = substr($test['tahun'], 2, 2) . "/0" . $test['unit'] . "-" . KodeUnit::where('id',$test['unit'])->value('lokasi') ."/".$test['sumber_dana'] ;
                        if($test->id < 10) { $kd .= "/0000" . $test->id; }
                        else if ($test->id < 100) { $kd .= "/000" . $test->id; }
                        else if ($test->id < 1000) { $kd .= "/00" . $test->id; }
                        else if ($test->id < 10000) { $kd .= "/0" . $test->id; }
                        else { $kd .= "/" . $test->id; }

            $td .= "<tr>";
            $td .= "<td style='width: 20%;'>" . $kd . "</td><td style='width: 40%;'>" . 
            //$test->keterangan . "</td><td style='width:20%;'>" . 
            Klasifikasi::where('id',$test->klasifikasi)->value('keterangan') . "</td><td style='width: 25%;'>" . 
            Klasifikasi::where('id',$test->tipe_brg)->value('keterangan') . "</td><td>" .
            $test->bhn_merk . "</td>" ;
            $td .= "</tr>";
        }

        $td .= '</table>';

        $pdf = PDF::loadHTML($td, ['format' => 'Folio', 'margin_left' => 5, 'margin_bottom' => 4, 'margin-top' => 4]);
        
        $tf = 'Laporan Tes Inven.pdf';
        
        $pdf->stream($tf);
        /**************** END PDF GENERATE ***************/
    }

    private function generateLists() {
        /********** START PDF GENERATE LIST INVEN MORE THAN 1 GROUP ************/
        //$testtest = Inven::select('lokasi')->distinct()->whereDate('created_at','2021-01-13')->orderBy('lokasi')->get();
        $testtest = Inven::select('lokasi')->distinct()->where('unit',8)->orderBy('lokasi')->get();
        //$testtest = Inven::select('lokasi')->distinct()->where('lokasi','Kelas 10 IPS 1')->orWhere('lokasi','Kelas 10 IPS 4')->orderBy('lokasi')->get();

        //dd($testtest);

        $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:80%; border-collapse: collapse;width: 100%;}';
        $td .= 'td, th {border: 1px solid; text-align: center;padding: 4px;}';
        $td .= '</style>';
        $td .= '<body>';

        foreach ($testtest as $test2) {
            $td .= '<htmlpageheader name="'. $test2->lokasi .'">'. $test2->lokasi .'</htmlpageheader>';
            $td .= '<sethtmlpageheader name="'. $test2->lokasi .'" value="on" show-this-page="1" />';
            $td .= '<sethtmlpageheader name="'. $test2->lokasi .'" value="on"/>';
            $td .= '<table align="center"><tr><th>Kode</th><th>Keterangan</th><th>Tipe</th><th>Bahan</th></tr>';

            //$temp = Inven::where('lokasi',$test2->lokasi)->whereDate('created_at','2021-01-13')->orderBy('id')->get();
            $temp = Inven::where('lokasi',$test2->lokasi)->orderBy('id')->get();

            foreach ($temp as $test) {
                $kd = substr($test['tahun'], 2, 2) . "/0" . $test['unit'] . "-" . KodeUnit::where('id',$test['unit'])->value('lokasi') ."/".$test['sumber_dana'] ;
                        if($test->id < 10) { $kd .= "/0000" . $test->id; }
                        else if ($test->id < 100) { $kd .= "/000" . $test->id; }
                        else if ($test->id < 1000) { $kd .= "/00" . $test->id; }
                        else if ($test->id < 10000) { $kd .= "/0" . $test->id; }
                        else { $kd .= "/" . $test->id; }

                $td .= "<tr>";
                $td .= "<td style='width: 15%;'>" . $kd . "</td><td style='width: 30%;'>" . 
                $test->keterangan . "</td><td style='width:15%;'>" . 
                //Klasifikasi::where('id',$test->klasifikasi)->value('keterangan') . "</td><td style='width: 35%;'>" . 
                "<span>" . Klasifikasi::where('id',$test->tipe_brg)->value('keterangan') . "</span></td><td style='width:15%;'>" .
                $test->bhn_merk . "</td>" ;
                $td .= "</tr>";   
            }

            $td .= '</table>';

            if($testtest->last() != $test2) {$td .= '<pagebreak>';}
        }

        $pdf = PDF::loadHTML($td, ['format' => 'Folio', 'margin_left' => 5, 'margin_bottom' => 4, 'margin-top' => 8]);
        
        $tf = 'Laporan Tes Inven.pdf';
        
        $pdf->stream($tf);
        /**************** END PDF GENERATE ***************/
    }

    public function create()
    {
        return view('testing');
    }

    public function store(Request $request)
    {
        /*if($request->hasFile('file_exc')) {
        $path = $request->file_exc->getRealPath();
            //dd($path);
        Excel::selectSheets('Sheet12')->load($path, function ($reader) {
            $results = $reader->get()->toArray();
            //dd($results);
            
            foreach ($results as $tmp) {
                if(count(AccNum::where('accnum_id',$tmp['coa'])->get()) == 0) {
                    $test = new AccNum();
                    $test->accnum_id = $tmp['coa'];
                    $test->flag = $tmp['drcr'];
                    $test->desc = $tmp['keterangan'];
                    $test->status = 0;
                    $test->save();
                }
            }
        });
    }*/

        /* PINDAH DARI COA LAMA KE COA BARU */
        /*if($request->hasFile('file_exc')) {
        $path = $request->file_exc->getRealPath();
            //dd($path);
        Excel::selectSheets('Sheet4')->load($path, function ($reader) {
            $results = $reader->get()->toArray();
            //dd($results);
            $t = 1;
            
            foreach ($results as $dt) {
                if($dt['balance'] != 0) {
                    if($t < 10 ) {$reff = '00' . $t;}
                    else if ($t < 100) {$reff = '0' . $t;}
                    else if ($t < 1000) {$reff = $t;}
                    if($dt["no_account"] != '') {$no_acc = $dt["no_account"];}
                    if($dt["debit_akhir"] != 0) {$dak = $dt["debit_akhir"];$kak = 0;}
                    else if($dt["kredit_akhir"] != 0) {$kak = $dt["kredit_akhir"];$dak = 0;}

                    $ja = new JurnalAdmin();
                    $ja->No_account = $no_acc;
                    $ja->No_bukti = 'PINCOA/' . $reff . '-06/20';
                    $ja->Tanggal = '2020-06-30';
                    $ja->Uraian = $dt["note"];
                    if($dak != 0 ) {
                        $ja->Debet = 0;
                        $ja->Kredit = $dt["balance"];
                    }
                    else if ($kak != 0 ) {
                        $ja->Kredit = 0;
                        $ja->Debet = $dt["balance"];
                    }
                    
                    $ja->Kontra_acc = $dt["map_to_new_coa"];
                    $ja->Div = 'pincoa';
                    $ja->save();$t++;}
        }
        });
        }

        return view('testing');*/

        /*if($request->hasFile('file_exc')) {
        $path = $request->file_exc->getRealPath();
            //dd($path);
        Excel::selectSheets('Sheet4')->load($path, function ($reader) {
            $results = $reader->get()->toArray();
            
            foreach ($results as $dt) {
                $tmp = new Temporari();
                $tmp->no_bkt_temp = $dt['no_bukti'];
                $tmp->save();
        }
        });
        }

        return view('testing');*/


        // input data inventori ver OKTOBER 2020
        if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('Sheet5')->load($path, function ($reader) {
                $results = $reader->get()->toArray();$t = 0;
                //dd($results);

                $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:300%; font-weight: bold; border-collapse: collapse;width: 100%;}';
                $td .= 'td, th {border: 1px solid; text-align: center;padding: 20px;}';
                $td .= 'tr:nth-child(even) {background-color: #dddddd;} @page {header: page-header;footer: page-footer;}</style>';
                $td .= '<body><htmlpageheader name="page-header"></htmlpageheader>';

                $td .= '<table align="center">';

                foreach ($results as $dt) {
                    //dd(substr($dt['tahun'], 2,2) . "/0" . $dt['unit'] . "-" . KodeUnit::where('id',$dt['unit'])->value('lokasi') . "/" . $dt['sumber_dana'] . "/00001"  );
                    for ($i=0; $i < $dt["jumlah"]; $i++) { 
                        $ja = new Inven();
                        $ja->tahun = $dt["tahun"];
                        $ja->unit = $dt["unit"];
                        $ja->sumber_dana = $dt["sumber_dana"];
                        $ja->klasifikasi = $dt["klasifikasi"];
                        $ja->tipe_brg = $dt["tipe_barang"];
                        $ja->keterangan = $dt["keterangan"];
                        $ja->bhn_merk = $dt["bahan"];
                        $ja->lokasi = $dt["letak"];
                        $ja->save();

                        if($t == 0) { $td .= '<tr>'; }
                        if($ja->id < 10000) { $td .= "/0" . $ja->id; }
                        $td .= "<td>" . substr($dt['tahun'], 2, 2) . "/0" . $dt['unit'] . "-" . KodeUnit::where('id',$dt['unit'])->value('lokasi') ."/".$dt['sumber_dana'] ;
                        if($ja->id < 10) { $td .= "/0000" . $ja->id; }
                        else if ($ja->id < 100) { $td .= "/000" . $ja->id; }
                        else if ($ja->id < 1000) { $td .= "/00" . $ja->id; }
                        else if ($ja->id < 10000) { $td .= "/0" . $ja->id; }
                        else { $td .= "/" . $ja->id; }
                        $td .= "</td>"; $t++;
                        if($t == 2) { $td .= '</tr>'; $t = 0;}
                    }
                }

                $td .= "</table></body>";

                $pdf = PDF::loadHTML($td, ['format' => 'A4', 'margin_left' => 6]);
        
                $tf = 'Laporan Tes Inven.pdf';
                
                //Session::flash('flash_message','Request berhasil disimpan.');
                //dd(public_path('/storage/boa.pdf'));
                //$pdf->save(public_path('/storage/'.$tf));
                $pdf->stream($tf);

                return view('testing');
            }
        );
        }if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('Sheet1')->load($path, function ($reader) {
                $results = $reader->get()->toArray();$t = 0; 

                $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:300%; font-weight: bold; border-collapse: collapse;width: 100%;}';
                $td .= 'td, th {border: 1px solid; text-align: center;padding: 20px;}';
                $td .= 'tr:nth-child(even) {background-color: #dddddd;} @page {header: page-header;footer: page-footer;}</style>';
                $td .= '<body><htmlpageheader name="page-header"></htmlpageheader>';

                $td .= '<table align="center">';

                foreach ($results as $dt) {
                    //dd(substr($dt['tahun'], 2,2) . "/0" . $dt['unit'] . "-" . KodeUnit::where('id',$dt['unit'])->value('lokasi') . "/" . $dt['sumber_dana'] . "/00001"  );
                    for ($i=0; $i < $dt["jumlah"]; $i++) { 
                        $ja = new Inven();
                        $ja->tahun = $dt["tahun"];
                        $ja->unit = $dt["unit"];
                        $ja->sumber_dana = $dt["sumber_dana"];
                        $ja->klasifikasi = $dt["klasifikasi"];
                        $ja->tipe_brg = $dt["tipe_barang"];
                        $ja->keterangan = $dt["keterangan"];
                        $ja->bhn_merk = $dt["bahan"];
                        $ja->lokasi = $dt["letak"];
                        $ja->save();

                        if($t == 0) { $td .= '<tr>'; }
                        if($ja->id < 10000) { $td .= "/0" . $ja->id; }
                        $td .= "<td>" . substr($dt['tahun'], 2, 2) . "/0" . $dt['unit'] . "-" . KodeUnit::where('id',$dt['unit'])->value('lokasi') ."/".$dt['sumber_dana'] ;
                        if($ja->id < 10) { $td .= "/0000" . $ja->id; }
                        else if ($ja->id < 100) { $td .= "/000" . $ja->id; }
                        else if ($ja->id < 1000) { $td .= "/00" . $ja->id; }
                        else if ($ja->id < 10000) { $td .= "/0" . $ja->id; }
                        else { $td .= "/" . $ja->id; }
                        $td .= "</td>"; $t++;
                        if($t == 2) { $td .= '</tr>'; $t = 0;}
                    }
                }

                $td .= "</table></body>";

                $pdf = PDF::loadHTML($td, ['format' => 'A4', 'margin_left' => 6]);
        
                $tf = 'Laporan Tes Inven.pdf';
                
                //Session::flash('flash_message','Request berhasil disimpan.');
                //dd(public_path('/storage/boa.pdf'));
                //$pdf->save(public_path('/storage/'.$tf));
                $pdf->stream($tf);

                return view('testing');
            }
        );
        }

        // input data absensi mentah  ke database akuntansi.coba
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('Sheet1')->load($path, function ($reader) {
                $results = $reader->get()->toArray();

                foreach ($results as $dt) {
                    $ja = new Coba();
                    $ja->idp = $dt["a"];
                    $ja->tgl = $dt["b"];
                    $ja->wkt = $dt["c"];
                    $ja->save();
                }

                return view('testing');
            }
        );
        }*/

        //input transaksi dari excel
        if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('TAMBAH')->load($path, function ($reader) {
                $results = $reader->get()->toArray();
                

                foreach ($results as $dt) {
                    $ja = new JurnalAdmin();
                    $ja->No_account = $dt["no_account"];
                    $ja->No_bukti = $dt["no_bukti"];
                    $ja->Tanggal = $dt["tanggal"];
                    $ja->Uraian = $dt["uraian"];
                    $ja->Debet = $dt["debet"];
                    $ja->Kredit = $dt["kredit"];
                    $ja->Kontra_acc = $dt["kontra_acc"];
                    $ja->Div = 'tmbhtrans';
                    $ja->save();
                }

                return view('testing');
            }
        );
        }

        //ob_end_clean();
        //ob_start();

        //*****************************************Input mutasi bcaum dari file txt (ganti bulan)
        /*if($request->hasFile('file_exc')){
            if (substr($request->file('file_exc')->getClientOriginalName(), -3) == "txt") {

            $path = $request->file('file_exc')->getRealPath();
            //$data = Excel::load($path, function($reader) {})->get();
            //$data = fopen($path,"r");
            $data = (file($path));
            $jmldata = count($data)-5;
            $totaldebit = 0;
            $totalkredit = 0;
            //print fgets($data)."<br>";
            //print_r(collect($data)->toJson());
            //print_r($data->count());
            
            //print "<tr><th>Tanggal</th><th>Kode Transaksi</th><th>NO VA</th><th>Kelas</th><th>Nama Siswa & Keterangan</th>";
            //print "<th>Debit</th><th>Kredit</th></tr>";

            $year = substr($data[4],16,4);
            $invno = Invoices::where('bank',1)->whereMonth('dot',6)->whereYear('dot', 2020)->get();$t = count($invno);
            $invno = Bpenb::where('bank',1)->whereMonth('dot',6)->whereYear('dot', 2020)->get();$t += count($invno);
            for ($i=8; $i < $jmldata; $i++)
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
            else {
                return view('testing');
            }
        }*/
        
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            Excel::load($path, function ($reader) {
                $results = $reader->all();
                dd($results);
            });
        }*/

        //****************************************Get Last Word From a String
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            Excel::load($path, function ($reader) {
                $results = $reader->get()->toArray();
                $datas = [];

                for ($i=0; $i < count($results); $i++) { 
                    $ns = explode(" ", $results[$i]['nama']); $temp = "";
                    for ($y=0; $y < count($ns) - 1; $y++) { 
                        $temp .= $ns[$y] . " ";
                    }
                    $datas[] = array(
                        'First Name' => $temp,
                        'Surname' => $ns[count($ns)-1],
                    );
                }

                return Excel::create("lastword", function($excel) use ($datas) {
                    $excel->sheet('Sheet 1', function($sheet) use ($datas)
                    {
                        $sheet->fromArray($datas,null,'A1',true);
                    });
                })->download('csv');
            });
        }*/

        //****************************************Input Rekap VA BCA dari Excel
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            Excel::load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd(count($reader->getAllSheets()));
                $sumsheet = count($reader->getAllSheets());

                if($sumsheet > 1) {
                //multiple sheet
                for ($i=0; $i < count($results); $i++) { 
                    for ($y=0; $y < count($results[$i]); $y++) { 
                        if($results[$i][$y]['tanggal'] != null){
                            $ja = new RekapVABCA();
                            $ja->tanggal = $results[$i][$y]['tanggal'];
                            $ja->no_va = $results[$i][$y]['no_va_bca'];
                            $ja->nominal = $results[$i][$y]['nominal'];
                            $ja->save();}
                    }
                }}
                else {
                //one sheet
                for ($i=0; $i < count($results); $i++) { 
                    $ja = new RekapVABCA();
                    $ja->tanggal = $results[$i]['tanggal'];
                    $ja->no_va = $results[$i]['no_va_bca'];
                    $ja->nominal = $results[$i]['nominal'];
                    $ja->save();
                }}
            });
        }*/

        //****************************************Input Pembelian Kantin dari Excel
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('FEBB')->load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd($results);
                $totalnominal = 0;$id_id = '';
                $t = 1;
                //dd($results);

                for($i = 0;$i<count($results);$i++) {
                    $pdet = new PembelianDetail();
                    $pdet->id_barang = $results[$i]['id_barang'];
                    $pdet->qty1 = $results[$i]['qty1'];
                    $pdet->qty2 = $results[$i]['qty2'];
                    $pdet->hrg_tot = $results[$i]['nominal'];
                    $pdet->hrg_sat = $results[$i]['hrg_sat'];
                    $pdet->diskon = 0;
                    //$pdet->diskon = $results[$i]['diskon'];
                    $pdet->save();$id_id .= $pdet->id . '|';$totalnominal += $results[$i]['nominal'];

                    $idbar = $results[$i]['id_barang'];
                    //print_r($results[$i]['id_barang'] . "|" . $idbar); //id pembulatan
                    $dbar = DaftarBarang::findOrFail($idbar);
                    $dbar->hpp = (($dbar->hpp * $dbar->stok) + $results[$i]['nominal']) / ($dbar->stok + ($results[$i]['qty1'] * $results[$i]['qty2']));
                    $dbar->stok += ($results[$i]['qty1'] * $results[$i]['qty2']);
                    $dbar->save();

                    if(($i+1) == count($results) || $results[$i]['no_bukti'] != $results[$i+1]['no_bukti']) {
                        if ($t < 10) {$invno = '00' . $t;}
                        elseif ($t < 100) {$invno = '0' . $t;}
                        else {$invno = $t;}

                        $pemb = new Pembelian();
                        $pemb->invoices_no = $invno . '/09-19';
                        $pemb->dot = $results[$i]['tanggal'];
                        $pemb->nominal = $totalnominal;
                        $pemb->save();

                        $id_id = explode("|", $id_id, -1);
                        for ($x=0; $x < count($id_id) ; $x++) { 
                            PembelianDetail::where("id",$id_id[$x])->update(['id_pembelian' => $pemb->id]);
                        }
                        $totalnominal = 0;$t++;$id_id = '';
                    }
                }
            });
        }*/

        //****************************************Input Stok Akhir Kantin
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('Sheet2')->load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd($results);

                if(PeriodeSak::where([['bulan',12],['tahun',2019],['status','U']])->first() != null) 
                {
                    for($i = 0;$i<count($results);$i++) {
                        $sakh = new StokAkhir();
                        $sakh->id_periodesak = 6;
                        $sakh->id_barang = $results[$i]['id_barang'];
                        $sakh->qty_akhir = $results[$i]['kuantiti'];
                        $sakh->save();
                    }

                $brg = DaftarBarang::where([['stok','>','0']])->orderBy('kategori')->orderBy('nama_barang')->get();
                
                $trfdio = 0;
                //$sak = StokAkhir::all();
                //dd($brg);
                //dd($sak->where('id_barang','1'));
                foreach ($brg as $b) {
                    //dd(count($sak->where('id_barang',$b->id)));
                    $trfdio = 0;

                    /*$temp =  // Transfer stok DIO 2 bulan berturut
                    //TransferStok::where([['id_barang',$b->id],['asal',1],['tujuan',2]])->whereMonth('tanggal','10')->whereYear('tanggal','2019')->get();
                    //if(count($temp) > 0) {
                    //    foreach ($temp as $t) {
                    //        $trfdio += $t->qty; 
                    //    }
                    //}

                    $temp = TransferStok::where([['id_barang',$b->id],['asal',1],['tujuan',2]])->whereMonth('tanggal','12')->whereYear('tanggal','2019')->get();
                    if(count($temp) > 0) {
                        foreach ($temp as $t) {
                            $trfdio += $t->qty; 
                        }
                    }

                    //$temp = TransferStok::where([['id_barang',$b->id],['asal',2],['tujuan',1]])->whereMonth('tanggal','9')->whereYear('tanggal','2019')->first();
                    //if($temp != null) $returdio = $temp->qty;

                    $temp = StokAkhir::where([['id_barang',$b->id],['id_periodesak',4]])->first();
                    if( $temp != null) {
                        //dd(StokAkhir::where('id_barang','3')->firstOrFail());
                        $s = $temp;
                        //dd($s['qty_akhir']);
                        $qter = $b->stok - $s->qty_akhir - $trfdio;
                        if(($b->id == 84 || $b->id == 86 || $b->id == 88) && $qter < 0)
                            {
                                $s->qty_terpakai = 0;
                                $s->qty_akhir = $b->stok;
                            }
                        else $s->qty_terpakai = $qter;
                        $s->hpp = $b->hpp;
                        $s->save();

                        //$b = DaftarBarang::findOrFail($b->id);
                        //$b->stok = $s->qty_akhir;
                        //$b->save();
                    }
                    else {
                        $s = new StokAkhir();
                        $s->id_barang = $b->id;
                        $s->qty_terpakai = $b->stok - $trfdio;
                        $s->qty_akhir = 0;
                        $s->hpp = $b->hpp;
                        $s->save();

                        //$b = DaftarBarang::findOrFail($b->id);
                        //$b->stok = $s->qty_akhir;
                        //$b->save();
                    }
                }
            }
            });
            return view('testing');
        }*/

        //****************************************Input BDIUM dari Mutasi txt ver 1
        //Check the month and year first, before input the data from txt to database
        /*if($request->hasFile('file_exc')){
            $path = $request->file('file_exc')->getRealPath();
            //$data = Excel::load($path, function($reader) {})->get();
            $data = fopen($path,"r");
            $totaldebit = 0;
            $totalkredit = 0;
            //print fgets($data)."<br>";
            //print_r(collect($data)->toJson());
            //print_r($data->count());
            
            //print "<tr><th>Tanggal</th><th>Kode Transaksi</th><th>NO VA</th><th>Kelas</th><th>Nama Siswa & Keterangan</th>";
            //print "<th>Debit</th><th>Kredit</th></tr>";
            $year = "20" . substr(fgets($data), 5, 2);
            $invno = Invoices::where('bank',2)->whereMonth('dot',6)->whereYear('dot', 2020)->get();$t = count($invno);
            $invno = Bpenb::where('bank',2)->whereMonth('dot',6)->whereYear('dot', 2020)->get();$t += count($invno);
            while(!feof($data))
            {
                $datos = fgets($data);

                if(substr($datos,0,1) != "T") { $t++;
                    //$strtemp = substr($datos,43);
                    //print_r(substr($strtemp,0,16));
                    $dbt = ltrim(substr($datos, 104,16),'0');$krt = ltrim(substr($datos, 120,16),'0');
                    if ($t < 10) {$invno = '00' . $t;}
                    elseif ($t < 100) {$invno = '0' . $t;}
                    else {$invno = $t;}

                    if($dbt != ".00") {
                        $bpenb = new Invoices();
                        $bpenb->invoices_no = $invno;
                        $bpenb->bank = 2;
                        $bpenb->dot = $year."-".substr($datos,8,2)."-".substr($datos,6,2);
                        $bpenb->nominal = floatval($dbt);
                        $bpenb->status = 's';
                        $bpenb->user_id = 19;
                        $bpenb->aiw = Terbilang::make($dbt);
                        $bpenb->save();

                        $bdet = new InvoicesDetail();
                        $bdet->id_invoices = $bpenb->id;
                        $bdet->invoices_no = $invno;
                        $bdet->description = substr($datos,44,60);
                        $bdet->nominal = floatval($dbt);
                        $bdet->save();
                    }
                    elseif ($krt != ".00") {
                        $bpenb = new Bpenb();
                        $bpenb->invoices_no = $invno;
                        $bpenb->bank = 2;
                        $bpenb->dot = $year."-".substr($datos,8,2)."-".substr($datos,6,2);
                        $bpenb->nominal = floatval($krt);
                        $bpenb->status = 's';
                        $bpenb->user_id = 19;
                        $bpenb->aiw = Terbilang::make($krt);
                        $bpenb->save();

                        $bdet = new BpenbDetail();
                        $bdet->id_bpenb = $bpenb->id;
                        $bdet->invoices_no = $invno;
                        $bdet->description = substr($datos, 44, 60);
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
            }
            fclose($data);
        }*/

        //****************************************Input BDIUM dari Mutasi ver 2 (ganti bulan)
        // if($request->hasFile('file_exc')){
        //     $path = $request->file('file_exc')->getRealPath();
        //     //$data = Excel::load($path, function($reader) {})->get();
        //     $data = fopen($path,"r");
        //     $totaldebit = 0;
        //     $totalkredit = 0;
        //     //print fgets($data)."<br>";
        //     //print_r(collect($data)->toJson());
        //     //print_r($data->count());
            
        //     //print "<tr><th>Tanggal</th><th>Kode Transaksi</th><th>NO VA</th><th>Kelas</th><th>Nama Siswa & Keterangan</th>";
        //     //print "<th>Debit</th><th>Kredit</th></tr>";
        //     $year = substr(fgets($data), 19, 4);
        //     $invno = Invoices::where('bank',2)->whereMonth('dot',5)->whereYear('dot', 2020)->get();$t = count($invno);
        //     $invno = Bpenb::where('bank',2)->whereMonth('dot',5)->whereYear('dot', 2020)->get();$t += count($invno);
        //     while(!feof($data))
        //     {
        //         $datos = fgets($data);

        //         if(substr($datos,0,2) == "03") { $t++;
        //             //$strtemp = substr($datos,43);
        //             //print_r(substr($strtemp,0,16));
        //             $dbt = ltrim(substr($datos, 70,16), " ");$krt = ltrim(substr($datos, 94,16), " ");
        //             $dbt = str_replace(',','.',str_replace(".", "", $dbt));$krt = str_replace(',','.',str_replace(".", "", $krt));
                    
        //             if ($t < 10) {$invno = '00' . $t;}
        //             elseif ($t < 100) {$invno = '0' . $t;}
        //             else {$invno = $t;}

        //             if($dbt != "0.0") {
        //                 $bpenb = new Invoices();
        //                 $bpenb->invoices_no = $invno;
        //                 $bpenb->bank = 2;
        //                 $bpenb->dot = $year."-".substr($datos,5,2)."-".substr($datos,7,2);
        //                 $bpenb->nominal = floatval($dbt);
        //                 $bpenb->status = 's';
        //                 $bpenb->user_id = 19;
        //                 $bpenb->aiw = Terbilang::make($dbt);
        //                 $bpenb->save();

        //                 $bdet = new InvoicesDetail();
        //                 $bdet->id_invoices = $bpenb->id;
        //                 $bdet->invoices_no = $invno;
        //                 (substr($datos, 12,2) == "BR") ? $bdet->description = substr($datos,17,25) : $bdet->description = substr($datos,12,30);
        //                 $bdet->nominal = floatval($dbt);
        //                 $bdet->save();
        //             }
        //             elseif ($krt != "0.0") {
        //                 $bpenb = new Bpenb();
        //                 $bpenb->invoices_no = $invno;
        //                 $bpenb->bank = 2;
        //                 $bpenb->dot = $year."-".substr($datos,5,2)."-".substr($datos,7,2);
        //                 $bpenb->nominal = floatval($krt);
        //                 $bpenb->status = 's';
        //                 $bpenb->user_id = 19;
        //                 $bpenb->aiw = Terbilang::make($krt);
        //                 $bpenb->save();

        //                 $bdet = new BpenbDetail();
        //                 $bdet->id_bpenb = $bpenb->id;
        //                 $bdet->invoices_no = $invno;
        //                 (substr($datos, 12,2) == "BR") ? $bdet->description = substr($datos,17,25) : $bdet->description = substr($datos,12,30);
        //                 $bdet->nominal = floatval($krt);
        //                 $bdet->save();
        //             }
        //             //print $year."-".substr($datos,8,2)."-".substr($datos,6,2);
        //             //print substr($datos,44,60);
        //             //print "Rp. ". (($dbt != ".00") ? $dbt : "0.00") . "|";$totaldebit += substr($datos, 104,16);
        //             //print "Rp. ". (($krt != ".00") ? $krt : "0.00") . "|";$totalkredit += substr($datos, 120,16);
        //         }
        //         //else
        //             //print "Total: Rp. $totaldebit | Rp. $totalkredit";
        //     }
        //     fclose($data);
        // }

        //****************************************Input BCAUM dari Mutasi
        //Please check whereMonth and whereYear first, before input data from excel to database
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            Excel::load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd($results);
                $invno = Invoices::where('bank',1)->whereMonth('dot',11)->whereYear('dot', 2019)->get();$t = count($invno);
                $invno = Bpenb::where('bank',1)->whereMonth('dot',11)->whereYear('dot', 2019)->get();$t += count($invno);
                //if ($t < 10) {$invno = '00' . ++$t;}
                //elseif ($t < 100) {$invno = '0' . ++$t;}

                foreach ($results as $res) {$t++;
                    if ($t < 10) {$invno = '00' . $t;}
                    elseif ($t < 100) {$invno = '0' . $t;}
                    else {$invno = $t;}
                    //dd($res);
                    
                    if($res['dbcr'] == "CR") {
                        //dd($res);
                        $bpenb = new Bpenb();
                        $bpenb->invoices_no = $invno;
                        $bpenb->bank = 1;
                        $bpenb->dot = $res['tanggal'];
                        $bpenb->nominal = $res['nominal'];
                        $bpenb->status = 's';
                        $bpenb->user_id = 19;
                        $bpenb->aiw = Terbilang::make($res['nominal']);
                        $bpenb->save();

                        $bdet = new BpenbDetail();
                        $bdet->id_bpenb = $bpenb->id;
                        $bdet->invoices_no = $invno;
                        $bdet->description = $res['keterangan'];
                        $bdet->nominal = $res['nominal'];
                        $bdet->save();
                    }
                    elseif ($res['dbcr'] == "DB") {
                        $bpenb = new Invoices();
                        $bpenb->invoices_no = $invno;
                        $bpenb->bank = 1;
                        $bpenb->dot = $res['tanggal'];
                        $bpenb->nominal = $res['nominal'];
                        $bpenb->status = 's';
                        $bpenb->user_id = 19;
                        $bpenb->aiw = Terbilang::make($res['nominal']);
                        $bpenb->save();

                        $bdet = new InvoicesDetail();
                        $bdet->id_invoices = $bpenb->id;
                        $bdet->invoices_no = $invno;
                        $bdet->description = $res['keterangan'];
                        $bdet->nominal = $res['nominal'];
                        $bdet->save();
                    }
                }
            });
        }*/

        //****************************************Input Barang Kantin
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            Excel::selectSheets('Sheet1')->load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd(count($reader->getAllSheets()));
                //$sumsheet = count($reader->getAllSheets());

                //multiple sheet
                //for ($i=0; $i < count($results); $i++) { 
                    for ($y=0; $y < count($results); $y++) { 
                            $ja = new DaftarBarang();
                            $ja->nama_barang = $results[$y]['nama_barang'];
                            $ja->satuan = $results[$y]['satuan'];
                            $ja->kategori = $results[$y]['kategori'];
                            $ja->save();
                    }
                //}
            });
        }*/

        //read excel and then import the data to d-ger.jurnal_admin
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            Excel::load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd(count($reader->getAllSheets()));
                $sumsheet = count($reader->getAllSheets());

                if($sumsheet > 1) {
                //multiple sheet
                for ($i=0; $i < count($results); $i++) { 
                    for ($y=0; $y < count($results[$i]); $y++) { 
                        if($results[$i][$y]['kode_coa'] != null){
                            $ja = new JurnalAdmin();
                            $ja->No_account = $results[$i][$y]['kode_coa'];
                            $ja->No_bukti = $results[$i][$y]['slip_no'];
                            $ja->Tanggal = $results[$i][$y]['tanggal'];
                            $ja->Uraian = $results[$i][$y]['uraian'];
                            $ja->Debet = $results[$i][$y]['debit'];
                            $ja->Kredit = $results[$i][$y]['kredit'];
                            $ja->Kontra_acc = $results[$i][$y]['kontra_acc'];
                            $ja->save();}
                    }
                }}
                else {
                //one sheet
                for ($i=0; $i < count($results); $i++) { 
                    $ja = new JurnalAdmin();
                    $ja->No_account = $results[$i]['kode_coa'];
                    $ja->No_bukti = $results[$i]['slip_no'];
                    $ja->Tanggal = $results[$i]['tanggal'];
                    $ja->Uraian = $results[$i]['uraian'];
                    $ja->Debet = $results[$i]['debit'];
                    $ja->Kredit = $results[$i]['kredit'];
                    $ja->Kontra_acc = $results[$i]['kontra_acc'];
                    $ja->save();
                }}
            });
        }*/

        //input file excel invoices to database
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('BTPN KL')->load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd($results);
                $totalnominal = 0;$id_id = '';

                for($i = 0;$i<count($results);$i++) {
                    $invdet = new InvoicesDetail();
                    $invdet->kode_d_ger = $results[$i]['coa'];
                    $invdet->invoices_no = $results[$i]['bpb'];
                    $invdet->description = $results[$i]['keterangan'];
                    $invdet->nominal = $results[$i]['nominal'];
                    $invdet->save();$id_id .= $invdet->id . '|';$totalnominal += $results[$i]['nominal'];

                    $ja = new JurnalAdmin();
                    $ja->No_account = Bank::find($results[$i]['bank'])->kode_dger;
                    $ja->No_bukti = $results[$i]['no_bank'];
                    $ja->Tanggal = $results[$i]['tanggal'];
                    $ja->Uraian = $results[$i]['keterangan'];
                    $ja->Debet = 0;
                    $ja->Kredit = $results[$i]['nominal'];
                    $ja->Kontra_acc = $results[$i]['coa'];
                    $ja->save();

                    if(($i+1) == count($results) || $results[$i]['bpb'] != $results[$i+1]['bpb']) {
                        $inv = new Invoices();
                        $inv->invoices_no = $results[$i]['bpb'];
                        $inv->bank = $results[$i]['bank'];
                        $inv->pay_to = $results[$i]['pay_to'];
                        $inv->give_to = $results[$i]['submit_to'];
                        $inv->dot = $results[$i]['tanggal'];
                        $inv->nominal = $totalnominal;
                        $inv->user_id = 19;
                        $inv->aiw = Terbilang::make($totalnominal);
                        $inv->save();
                        $totalnominal = 0;

                        $id_id = explode("|", $id_id, -1);
                        for ($x=0; $x < count($id_id) ; $x++) { 
                            InvoicesDetail::where("id",$id_id[$x])->update(['id_invoices' => $inv->id]);
                        }
                        $id_id = '';
                    }
                }
            });
        }*/

        //input file excel to akuntansi.bpenb and dger.jurnal_admin
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('JUNI MSK 1')->load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd($results);
                $totalnominal = 0;$id_bd = '';

                for($i = 0;$i<count($results);$i++) {
                    $invdet = new BpenbDetail();
                    $invdet->kode_d_ger = $results[$i]['coa'];
                    $invdet->invoices_no = $results[$i]['bpb'];
                    $invdet->description = $results[$i]['keterangan'];
                    $invdet->nominal = $results[$i]['nominal'];
                    $invdet->save();
                    $id_bd .= $invdet->id . '|';
                    $totalnominal += $results[$i]['nominal'];

                    $ja = new JurnalAdmin();
                    $ja->No_account = Bank::find($results[$i]['bank'])->kode_dger;
                    $ja->No_bukti = $results[$i]['no_bank'];
                    $ja->Tanggal = $results[$i]['tanggal'];
                    $ja->Uraian = $results[$i]['keterangan'];
                    $ja->Debet = $results[$i]['nominal'];
                    $ja->Kredit = 0;
                    $ja->Kontra_acc = $results[$i]['coa'];
                    $ja->save();

                    if(($i+1) == count($results) || $results[$i]['bpb'] != $results[$i+1]['bpb'] || $results[$i]['bank'] != $results[$i+1]['bank']) {
                        $inv = new Bpenb();
                        $inv->invoices_no = $results[$i]['bpb'];
                        $inv->bank = $results[$i]['bank'];
                        $inv->pay_from = $results[$i]['pay_from'];
                        $inv->given_by = $results[$i]['submit_from'];
                        $inv->dot = $results[$i]['tanggal'];
                        $inv->nominal = $totalnominal;
                        $inv->user_id = 19;
                        $inv->aiw = Terbilang::make($totalnominal);
                        $inv->save();
                        $totalnominal = 0;

                        $id_bd = explode("|", $id_bd, -1);
                        for ($x=0; $x < count($id_bd) ; $x++) { 
                            BpenbDetail::where("id",$id_bd[$x])->update(['id_bpenb' => $inv->id]);
                        }
                        $id_bd = '';
                    }
                }
            });
        }*/

        //input file excel to database
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd($results);
                foreach ($results as $res) {
                    $la = new LandAsset();
                    $la->land_description = $res['inven_tanah'];
                    $la->area = $res['luas'];
                    $la->dot = $res['tanggal'];
                    $la->status = $res['status'];
                    $la->sert_no = $res['sert_no'];
                    $la->nominal = $res['nominal'];
                    $la->save();
                }
            });
        }*/
            /*Excel::load($path . '/exported.xls', function($reader) 
            {
                $reader->sheet(function($sheet) 
                {
                    $sheet->appendRow([
                         'test1', 'test2',
                     ]);
                });
            })->export('xls');

            Excel::load($path, function ($reader) {
                $reader->sheet('Sheet1', function ($sheet) { 
                    $sheet->appendRow(['test1','test2',]);
                    //dd($sheet);
                });
            })->export('xls');
        }*/

            /*fclose($data);
            $dtex[] = array('','Total Penerimaan',$totalkredit);

            return Excel::create('Laporan VA BCA', function($excel) use ($dtex,$row) {
            $excel->sheet('Sheet 1', function($sheet) use ($dtex,$row)
            {
                $sheet->cells('A3:C3', function($cells) {

                    // call cell manipulation methods
                    $cells->setBackground('#7CFC00');

                });
                $sheet->setBorder('A3:C'.$row,'thin');
                $sheet->setAutoSize(true);
                $sheet->with($dtex,null,'A1',true,false);
            });
            })->export('xls');*/
            return view('testing');
        }
        
    

    public function edit()
    {
        $apaja = array();
        return view('testing',compact('apaja'))->render();
    }

    public function infophp()
    {
        return view('info');
    }

    public function adminer() {
        return view('adminer');
    }

    public function printDM()
    {
        header("Access-Control-Allow-Origin: *");
        /*$ip = '10.42.0.1'; // IP Komputer kita atau printer lain yang masih satu jaringan
            $printer = 'lx300'; // Nama Printer yang di sharing
                $connector = new NetworkPrintConnector("10.42.0.1");
                $printer = new Printer($connector);
                $printer -> text("Email :CLB");
                $printer -> text("Username :CLBCLB");
                $printer -> cut();
                $printer -> close();*/
        $dt = "\ntesting print dot matrix\nHALLO\n\n";
        $dt = Chr(27) . Chr(69) . Chr(1) . $dt . Chr(27) . Chr(69) . Chr (0);
        //$temp['string'] = $dt;
        return $dt;
    }

    public function  send()
    {
        Log::info("Request cycle without Queues started");
        /*Mail::send('emails.welcome', ['data'=>'data'], function ($message) {
            $message->subject('Nyobain');
            
            $message->from('caleb.kios310@gmail.com', 'Caleb Lyn');

            $message->to('chris@scotch.io');

        });*/
        $this->dispatch((new SendWelcomeEmail())->delay(60 * 5));
        Log::info("Request cycle without Queues finished");
    }

    public function makeReport()
    {
        $report = [];$totalsk = 0;$total = 0;$year = '2017';
        /*$subkode = DB::connection('mysql3')->table('kaskecil')->select(DB::raw('distinct(subkode)'))->where('kode_unit','8')->where('tanggal_trans','<=',$year.'-12-31')->orderBy('subkode')->get();
        //dd($subkode);
        foreach ($subkode as $sk) {
            //dd($sk->subkode);
            if($sk->subkode <> null) {
                //dd($sk->subkode);
                $details = Kaskecil::where('kode_unit','8')->where('status','!=','bu')->where('subkode','=',$sk->subkode)->get();
                $report[] = array('Subkode: '.$sk->subkode);
                foreach ($details as $detail) {
                    $report[] = array(
                        'Deskripsi' => $detail->deskripsi,
                        'Nominal' => $detail->nominal,
                    );
                    $totalsk += $detail->nominal;
                }
                $report[] = array('Total',$totalsk);$total += $totalsk;$totalsk = 0;
                $report[] = array();
            }
        }

        $report[] = array();
        $report[] = array('Total Seluruh Kas Admin',$total);

        $kks = DB::connection('mysql3')->table('kaskecil')->select(DB::raw('distinct(kode_d_ger)'))->where('kode_unit','8')->where('tanggal_trans','<=',$year.'-12-31')->orderBy('kode_d_ger')->get();
        dd($kks);*/
        $totalcoa = Kaskecil::select(DB::raw('kode_d_ger, SUM(nominal) as total'))
                ->where([['tanggal_trans','>=',$year.'-01-01'],['tanggal_trans','<=',$year.'-12-31'],['kode_unit',8]])->groupBy('kode_d_ger')->get();

        $totalsk = Kaskecil::select(DB::raw('subkode, SUM(nominal) as total'))
            ->where([['tanggal_trans','>=',$year.'-01-01'],['tanggal_trans','<=',$year.'-12-31'],['kode_unit',8]])->groupBy('subkode')->get();
        //dd($totalsk);

        $report[] = array('Total COA');
        foreach ($totalcoa as $tc) {
            $des = AccountAdmin::where('No_account',$tc->kode_d_ger)->value('Keterangan');
            $report[] = array('Kode COA' => $tc->kode_d_ger,'Total' => $tc->total,'Keterangan' => $des);
        }
        $report[] = array('Total Sub Kode');
        foreach ($totalsk as $ts) {
            $report[] = array('Kode SK' => $ts->subkode,'Total' => $ts->total,);
        }

        return Excel::create('Laporan Kas Admin & Kas Kecil ' . $year, function($excel) use ($report) {
            $excel->sheet('Sheet 1', function($sheet) use ($report)
            {
                $sheet->fromArray($report,null,'A1',true);
            });
        })->download('csv');
    }

    public function sidebartest()
    {
        return view('sidebar.sidebartest');
    }
}
?>