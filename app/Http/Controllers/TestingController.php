<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mike42\Escpos\Printer; 
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

use Carbon\Carbon;
use App\Testing;
use App\Siswa;
use App\Customuser;
use App\Inventory;
use App\Kaskecil;
use App\Invoices;
use App\InvoicesDetail;
use App\JurnalAdmin;
use Excel;
use Input;
use DB;
use Response;
use DateTime;
use Log;
use Mail;
use Terbilang;
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
        $inv = Invoices::findOrFail(30);
        $expmemo = explode("\r\n", $inv->memo);
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

    public function create()
    {
        return view('testing');
    }

    public function store(Request $request)
    {
        ob_end_clean();
        ob_start();
        
        if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            Excel::load($path, function ($reader) {
                $results = $reader->get()->toArray();
                dd($results);

                //multiple sheet
                for ($i=0; $i < count($results); $i++) { 
                    for ($y=0; $y < count($results[$i]); $y++) { 
                        $ja = new JurnalAdmin();
                        $ja->No_account = $results[$i][$y]['kode_coa'];
                        $ja->No_bukti = $results[$i][$y]['slip_no'];
                        $ja->Tanggal = $results[$i][$y]['tanggal'];
                        $ja->Uraian = $results[$i][$y]['uraian'];
                        $ja->Debet = $results[$i][$y]['debit'];
                        $ja->Kredit = $results[$i][$y]['kredit'];
                        $ja->Kontra_acc = $results[$i][$y]['kontra_acc'];
                        $ja->save();
                    }
                }
                //one sheet
                /*for ($i=0; $i < count($results); $i++) { 
                    $ja = new JurnalAdmin();
                    $ja->No_account = $results[$i]['kode_coa'];
                    $ja->No_bukti = $results[$i]['slip_no'];
                    $ja->Tanggal = $results[$i]['tanggal'];
                    $ja->Uraian = $results[$i]['uraian'];
                    $ja->Debet = $results[$i]['debit'];
                    $ja->Kredit = $results[$i]['kredit'];
                    $ja->Kontra_acc = $results[$i]['kontra_acc'];
                    $ja->save();
                }*/
            });
        }

        //input file excel invoices to database
        /*if($request->hasFile('file_exc')) {
            $path = $request->file_exc->getRealPath();
            //dd($path);
            Excel::selectSheets('Des 18')->load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd($results);
                $totalnominal = 0;

                for($i = 0;$i<count($results);$i++) {
                    $invdet = new InvoicesDetail();
                    $invdet->kode_d_ger = $results[$i]['coa'];
                    $invdet->invoices_no = $results[$i]['bpb'];
                    $invdet->description = $results[$i]['keterangan'];
                    $invdet->nominal = $results[$i]['nominal'];
                    $invdet->save();$totalnominal += $results[$i]['nominal'];

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
                    }
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

    public function importFileIntoDB(){

            $ip = '10.42.0.1'; // IP Komputer kita atau printer lain yang masih satu jaringan
            $printer = 'lx300'; // Nama Printer yang di sharing
                $connector = new NetworkPrintConnector("10.42.0.1");
                $printer = new Printer($connector);
                $printer -> text("Email :CLB");
                $printer -> text("Username :CLBCLB");
                $printer -> cut();
            /*$data = Excel::load('/Library/WebServer/Documents/kaskecil/public/cobainv.xls')->get();
            dd($data);
            if($data->count()){
                foreach ($data as $key => $value) {
                    $arr[] = ['name' => $value->name, 'details' => $value->details];
                }
                if(!empty($arr)){
                    \DB::table('products')->insert($arr);
                    dd('Insert Record successfully.');
                }
            }*/
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
        $report = [];$totalsk = 0;$total = 0;
        $subkode = DB::connection('mysql3')->table('kaskecil')->select(DB::raw('distinct(subkode)'))->where('kode_unit','8')->where('status','!=','bu')->orderBy('subkode')->get();
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

        return Excel::create('Laporan Kas Admin', function($excel) use ($report) {
            $excel->sheet('Sheet 1', function($sheet) use ($report)
            {
                $sheet->fromArray($report,null,'A1',true);
            });
        })->download('csv');
    }
}
?>