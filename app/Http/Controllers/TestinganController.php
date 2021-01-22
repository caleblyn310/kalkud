<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use App\vabdiall;
use Carbon\Carbon;
use App\JurnalAdmin;
use App\AccountAdmin;
use App\Depreciation;
use App\LandAsset;
use App\Bpenb;
use App\BpenbDetail;
use App\Bank;
use App\InvoicesDetail;
use Session;
use PDF;
use DB;
use Excel;

class TestinganController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        /*$bla = "BDI (123214)";
        dd(explode(" ", $bla)[0]);*/
        return view('testingan');
    }

    public function getSecret(Request $request)
    {
        ob_start();
        //dd($request->gal_loc);
        if($request->hasFile('dftr_siswa') && $request != '') {
            if($request->get('dot') < Carbon::now()->format('Y-m-d'))
            {
                Session::flash('flash_message','Date must be after or equal today');
                return redirect('testingan');
            }
            
            $path = $request->dftr_siswa->getRealPath();
            Excel::load($path, function ($reader) use ($request) {
                $results = $reader->get()->toArray();
                $title = $request->title;
                $year1 = Carbon::now()->year;
                if(Carbon::now()->month < 7) {$year1--;}
                $year2 = $year1+1;
                $dt = Carbon::parse($request->get('dot'))->format('j F Y');
                $logo = '';
                if($request->has('logo')) $logo = '<img src="logo-dinas-2.png" width=50 height=50>';
                $total = ceil(count($results)/2);
        $top1 = 160; $left1 = 220;$c = 0;$d = 0;
        $html = '<style>h5, h4, h6, h3, h2, h1 {font-weight: 300; font-family: Arial;} p {padding: 0px 0px 0px 0px; margin: 0px; text-align: center; width: auto;} td {font-size: 80%;border:} table { border-collapse: collapse; margin: 0px 0px 0px 0px; padding: 0px;} #informasi td {font-size:85%;} p {font-size:75%;}</style>';
        $html .= '<body>';
        for ($i=1; $i <= $total; $i++) {
            //$html .= '<tr>'; 
            //for ($y=0; $y < 2; $y++) { 
        /*$html .= '<td><div style="position: relative; border: 1px solid black;width: 370px;height: 60mm; top: '.$top1.'px; left: '.$left1.'px; padding: 5px;">';
        $html .= '<div style="border: 1px solid blue; width: 50px;height: 50px; float: left;"></div>';
        $html .= '<div style="border: 1px solid green; width: 300px;height: 50px; float: right;"></div>';
        $html .= '<div style="border: 1px solid grey; width: 368px; margin-top: 5px; float: left;"></div>';
        $html .= '<div style="border: 1px solid yellow; width: 100px; height: 125px; float: left; margin-top: 5px; margin-bottom: 40px;"></div>';
        $html .= '<div style="border: 1px solid yellow; width: 260px; height: 105px; float: right;"></div>';
        $html .= '<div style="border: 1px solid yellow; width: 185px; height: 65px; float: right; margin-top: 5px;"></div>';
        $html .= '<div style="border: 1px solid yellow; width: 175px; height: 10px; float: left;"></div>';
        $html .= '</div></td>';*/
            $html .= '<table><tr>';
            for ($a=0; $a < 2; $a++) { 
                if($c < count($results)) {
                $html .= '<td style="width: 59px; height: 60px; padding-left: 7px;border-left: 1px solid black; border-top: 1px solid black;"><img src="logo-skkkb.png" width=50 height=50></td>
                          <td style="width: 261px; height: 60px;text-align: center; vertical-align:top; padding-top: 5px;border-top: 1px solid black; "><h4 style="color:red;">'.$title.'</h4><h4 style="color: blue;font-size:115%;">SMA KRISTEN KALAM KUDUS BANDUNG</h4><h4>TAHUN PELAJARAN '.$year1.'-'.$year2.'</h4></td>';
                $html .= '<td style="width: 59px; height: 60px; padding-right: 7px;border-top: 1px solid black;border-right: 1px solid black;">';
                $html .= $logo.'</td>';$c++;}
            }
            $html .= '</tr></table>';

            $html .= '<table><tr>';
            for ($b=0; $b < 2; $b++) { 
                if($d < count($results)){
                    $html .= '<td style="width: 98px; height: 165px; padding: 5px 0px 0px 3px; vertical-align: top;border-left: 1px solid black;border-top: 1px solid black;"><img src="file:///home/elisa/foto X dan XI/'.$results[$d]['no._va'].'.resized.jpg" width=93 height=124>
                            <div style="height: 40px;font-size: 80%;color: red;"><br><br><u>Kartu selalu dibawa.</u></div></td>
                            <td style="width: 281px; height: 165px;text-align: center; vertical-align:top; text-align: left; font-size: 85%; padding-top:2px;border-right:1px solid black; border-top: 1px solid black;">
                            <table id="informasi" style="border: 0px;"><tr><td>NO. PESERTA</td><td>:</td><td>'.$results[$d]['no._peserta'].'</td></tr>
                            <tr><td style="vertical-align:top;">NAMA</td><td style="vertical-align:top;">:</td><td>'.$results[$d]['nama'].'</td></tr>
                            <tr><td>NO. ABSEN</td><td>:</td><td>'.$results[$d]['no.absen'].'</td></tr>
                            <tr><td>RUANG</td><td>:</td><td>'.$results[$d]['ruang'].'</td></tr>
                            <tr><td>KELAS</td><td>:</td><td>'.$results[$d]['kelas'].'</td></tr></table>
                            <div style="position: fixed;top: '.$top1.'px; left: '.$left1.'px; width: 150px; height: 70px; text-align: center;">
                            <p>Bandung, '.$dt.'</p><img src="ttdkepsek.jpg" width=58 height=35><p>Kristanto, S. Psi</p></div></td>';
                    $left1 += 381;$d++;}
            }
            $html .= '</tr></table>';
            //}
            $top1 += 227;$left1 = 220;//$html .= '</tr>';
            if(($i % 5) == 0 && $i != 0) {$top1 = 160;}
        }

        $html .= '</body>';
        //dd($html);

        $pdf = PDF::loadHTML($html, ['format' => 'Folio']);
        //$pdf = PDF::loadView('testkrt', ['format' => 'Folio','margin_top' => '1']);

        $pdf->stream('caleb.pdf');
        ob_end_flush();

        //return redirect('testingan');
            
        /*$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('/home/adit/Downloads/txtvabdi/coba3'),RecursiveIteratorIterator::SELF_FIRST);
        foreach($iterator as $file) {
            if($file->isFile() && $file->getExtension() == 'txt') {
                $fp = fopen($file->getRealPath(), 'r');
                $year = substr(fgets($fp), 5,2);
                while(!feof($fp)) {
                    $temp = fgets($fp);
                    if(substr($temp, 0,1) == "D" && substr($temp, 44,2) == '86' && substr_count($temp, '0',118,15) < 15) {
                        $dt = vabdiall::firstOrCreate(['transcode'=>substr($temp, 0, 18)],['nova' => substr($temp, 44,16),
                        'description' => substr($temp, 61,43),
                        'nominal' => ltrim(substr($temp, 118,15), '0'),
                        'transdate' => Carbon::create('20'.$year,substr($temp, 8,2),substr($temp, 6,2))]);
                        /*$dt = new vabdiall();
                        $dt->nova = substr($temp, 44,16);
                        $dt->description = substr($temp, 61,43);
                        $dt->nominal = ltrim(substr($temp, 118,15), '0');
                        $dt->trfdate = Carbon::create('20'.$year,substr($temp, 8,2),substr($temp, 6,2));
                        $dt->save();
                    }
                }
                fclose($fp);
            }
        }*/
    });}
    }

    public function cobacoba() {
        $temp = scandir('/home/adit/Documents/untitled folder');
        $t = '/home/adit/Documents/untitled folder/';
        for ($i=2; $i < count($temp); $i++) { 
            rename($t . $temp[$i], $t . substr($temp[$i], 0, 19) . '.jpg');
        }
        /*$temp1 = JurnalAdmin::select(DB::raw('Kontra_acc, SUM(Debet) as totDebt, SUM(Kredit) as totKred'))
                ->where([['Tanggal','>=','2018-01-01'],['Tanggal','<=','2018-12-31'],['No_account','112.28.111']])->groupBy('Kontra_acc')->get();
        $deb1 = array_column($temp1->toArray(), 'totDebt', 'Kontra_acc');
        $kre1 = array_column($temp1->toArray(), 'totKred', 'Kontra_acc');
        $temp2 = JurnalAdmin::select(DB::raw('No_account, SUM(Debet) as totDebt, SUM(Kredit) as totKred'))
                ->where([['Tanggal','>=','2018-01-01'],['Tanggal','<=','2018-12-31'],['Kontra_acc','112.28.111']])->groupBy('No_account')->get();
        $deb2 = array_column($temp2->toArray(), 'totDebt', 'No_account');
        $kre2 = array_column($temp2->toArray(), 'totKred', 'No_account');
        $sums = array();
        $temp1 = array_column($temp1->toArray(), 'totDebt','Kontra_acc');
        $temp2 = array_column($temp2->toArray(), 'totDebt','No_account');
        foreach (array_keys($temp1 + $temp2) as $key) {
            $db1 = isset($deb1[$key]) ? floatval($deb1[$key]) : 0;
            $kr1 = isset($kre1[$key]) ? floatval($kre1[$key]) : 0;
            $db2 = isset($deb2[$key]) ? floatval($deb2[$key]) : 0;
            $kr2 = isset($kre2[$key]) ? floatval($kre2[$key]) : 0;
            $flag = AccountAdmin::where('No_account',$key)->value('Flag');
            ($flag == 'D') ? $sums[$key] = ($kr1-$db1)+($db2-$kr2) : $sums[$key] = ($db1-$kr1)+($kr2-$db2);
        }
        dd($sums);*/
    }

    public function krtUjian(Request $request) {
        ob_start();
        /*$html = '<html>
<head>
<style>
.crop {
    background-image: url("..\avenger.jpg");
    width: 125px;
    height: 190px;
    background-size: 3840px 2160px;
    background-position: -45px -80px;
    border: 1px solid red;
}
</style>
</head>
<body>
<div class="crop"></div>

</body>
</html>';

<div style="position: fixed; top: '.$top3.'px; left: '.$left5.'px; width: 280px; height: 80px; padding: 0px; height: 85%; vertical-align: top;"><p>caleb<br>second line<br>third line</p></div>
*/
        
        if($request->hasFile('dftr_siswa')) {
            $path = $request->dftr_siswa->getRealPath();
            Excel::load($path, function ($reader) {
                $results = $reader->get()->toArray();
                //dd($results);
                $total = ceil(count($results)/2);
                //dd($results[0]['no._peserta']);

        $height = 228;$width = 379;$top1 = 0;$top2 = 5;$top3 = 65;$top4 = 210;$top5 = 155;$top6 = 60;$top7 = 1;$top8 = 170;$posx = 20;$mul = 0;$fn = 'avenger';$z = 0;
        $html = '<style>
        .logo {
            background-image: url("..\logo-skkkb.png");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center; 
            background-size: contain;
        }

        .ttdkepsek {
            background-image: url("..\ttdkepsek.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center; 
            background-size: contain;
        }

        .logodinas {
            background-image: url("..\logo-dinas.png");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center; 
            background-size: contain;
        }

        .crop {
            width: 105px;
            height: 135px;
            background-size: 720px 405px;
        }</style>';
        $html .= '<table>';

        for ($y=0 ; $y < 7 ; $y++ ) {
            $left1 = 0; $left2 = 10; $left3 = 68; $left4 = 210; $left5 = 120;$left6 = $left4 + 25;$left7 = 313;
            $html .= '<tr>';
            for ($x=0; $x < 2; $x++) 
            { 
                if($z != count($results)) {
                $nama = $results[$z]['nama'];
                    if (str_word_count($results[$z]['nama']) > 2)
                    {   
                        $temp = str_word_count($results[$z]['nama'],1); $nama = '';
                        for ($i=0; $i < str_word_count($results[$z]['nama']); $i++) { 
                            ($i < 2) ? $nama .= $temp[$i] . " " : $nama .= substr($temp[$i],0,1) . ". ";
                        }
                    }
            $html .= '<td><div style="position: fixed; top: '.$top1.'px; left: '.$left1.'px; width: 100mm; height: 60mm; border: 1px solid black;"></div>
        <div style="position: fixed; top: '.$top2.'px; left: '.$left2.'px; width: 50px; height: 50px;" class="logo"></div>
        <div style="position: fixed; top: '.$top2.'px; left: '.$left3.'px; width: 240px; height: 50px; padding: 0px; text-align:center; font-family: Arial;">
        <span style="color: #ff0038;font-size: 150%;"><strong>KARTU PESERTA USBN</strong></span><br>
        <span style="color: #0038ff;font-size: 150%;"><strong>SMA KRISTEN KALAM KUDUS BANDUNG</strong></span><br>
        <span style="color: #ff0038;font-size: 150%;"><strong>TAHUN PELAJARAN 2018-2019</strong></span></div>
        <div style="position: fixed; top: '.$top7.'px; left: '.$left7.'px; width: 50px; height: 60px; padding = 0px;" class="logodinas"></div>
        <div style="position: fixed; top: '.$top6.'px; left: '.$left2.'px; width: 362px; height: 1px; background-color:lightgrey;"></div>
        <div style="position: fixed; top: '.$top3.'px; left: '.$left2.'px; background-image: url(file:///home/adit/Documents/'.$fn.'.jpg); background-position: -'.$posx.'px -50px;" class="crop"></div>
        <div style="position: fixed; top: '.$top3.'px; left: '.$left5.'px; width: 250px; height: 90px; padding: 0px;">
        <table style="font-family: Arial; font-size: 500%; margin-top: 0px;">
        <tr><td>No. Peserta</td><td>:</td><td><strong>'.$results[$z]['no._peserta'].'</strong></td></tr>
        <tr><td>Nama</td><td>:</td><td><strong>'.$nama.'</strong></td></tr>
        <tr><td>No. Absen</td><td>:</td><td><strong>'.$results[$z]['no.absen'].'</strong></td></tr>
        <tr><td>Ruang</td><td>:</td><td><strong>'.$results[$z]['ruang'].'</strong></td></tr>
        <tr><td>Kelas</td><td>:</td><td><strong>'.$results[$z]['kelas'].'</strong></td></tr>
        </table></div>
        <div style="position: fixed; top: '.$top4.'px; left: '.$left2.'px; width: 150px; height: 10px; "><strong>Catatan:</strong> Kartu harus di bawa saat ujian.</div>
        <div style="position: fixed; top: '.$top5.'px; left: '.$left4.'px; width: 190px; height: 60px; font-size: 75%;">Bandung, 30 Desember 2019</div>
        <div style="position: fixed; top: '.$top8.'px; left: '.$left6.'px; width: 80px; height: 40px; padding = 0px;" class="ttdkepsek"></div>
        <div style="position: fixed; top: '.$top4.'px; left: '.$left6.'px; width: 150px; height: 13px; "><strong>Kristanto, S. Psi</strong></div></td>';
            $left1+=$width;$left2+=$width;$left3+=$width;$left4+=$width;$left5+=$width;$left6+=$width;$left7+=$width;$z++;
            if (($mul / 6) == 1) { $mul = 0; $posx = 20; } else {$posx = 100 + $posx;$mul++;}}
            }
            $top1+=$height;$top2+=$height;$top3+=$height;$top4+=$height;$top5+=$height;$top6+=$height;$top7+=$height;$top8+=$height;
            $html .= '</tr>';
        }
        $html .= '</table>';
        $pdf = PDF::loadHTML($html, ['format' => 'Folio']);

        $pdf->save('caleb.pdf');

        return redirect('testingan');
            });
        }
        ob_end_flush();
    }

    public function create()
    {
        return view('testingan');
    }

    public function store(Request $request)
    {
        //ob_end_clean();
        //ob_start();

        if($request->hasFile('file_exc')) {
            $path = $request->file_exc->path();
            
            /*Excel::load($path . '/exported.xls', function($reader) 
            {
                $reader->sheet(function($sheet) 
                {
                    $sheet->appendRow([
                         'test1', 'test2',
                     ]);
                });
            })->export('xls');*/

            Excel::load($path, function ($reader) {
                $reader->sheet('Sheet1', function ($sheet) { 
                    $sheet->appendRow(['test1','test2',]);
                    //dd($sheet);
                });
            })->export('xls');
        }

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

    public function bpenb() {
        $bpenb_list = Bpenb::orderBy('status','asc')->orderBy('invoices_no','asc')->paginate(80);
        return view('bpenb.index', compact('bpenb_list'));
    }

    public function print($id) {
        ob_end_clean();
        ob_start();
        $inv = Bpenb::findOrFail($id);
        $bank = Bank::findOrFail($inv->bank);
        $invdets = BpenbDetail::where('invoices_no',$inv->invoices_no)->get();

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
        
        //Continues form version
        /*$t = 0;
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
            DB::table('invoices')->where('invoices_no',$inv->invoices_no)->update(['status' => 'p']);
        }
        else { $dt .= str_pad('reprint',76, ' ', STR_PAD_LEFT) . "\n"; }
        $dt .= Chr(12);
        return $dt;*/

        //Existing form version
        $t = 0;$cpl = 35;
        $dt = "\n\n\n           " . $bank->bank;
        if($inv->status != 's') { $dt .= str_pad('reprint',40, ' ', STR_PAD_LEFT); }
        else { DB::table('bpenb')->where('invoices_no',$inv->invoices_no)->update(['status' => 'p']); }
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

        //transaction detail
        foreach ($invdets as $invdet) {
            $ts = $invdet->description;
            $result = ''; $cpl = 40;
            $dger = JurnalAdmin::where('no_bukti','like','%'.$invdet->invoices_no.'%05/19')->value('no_bukti');
            $ts .= ' ('.$dger.')';
            
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
    }

    public function cobagendep()
    {
        ob_end_clean();
        ob_start();
        $las = LandAsset::all();$x = 1;$totalnom = 0;

        $datalap[] = array('No' => '',
                        'Jenis Aktiva' => 'INVENTARIS TANAH',
                        'Q' => 'Luas (m2)',
                        'Tanggal Beli' => 'Tanggal',
                        'Maks' => 'Status',
                        'Pemakaian (bulan)' =>'',
                        'Pemakaian (tahun)' =>'No Sertifikat',
                        'Sisa Pemakaian (tahun)' =>'',
                        'Harga Perolehan' => '',
                        'Akumulasi Penyusutan Awal Bulan' =>'',
                        'Nilai Sisa Awal Bulan' => '',
                        'Penyusutan' => '',
                        'Akumulasi Penyusutan Akhir Bulan' =>'',
                        'Nilai Sisa Akhir Bulan' => '');

        foreach ($las as $la) {
            $datalap[] = array('No' => $x,
                            'Jenis Aktiva' => $la->land_description,
                            'Q' => $la->area,
                            'Tanggal Beli' => Carbon::parse($la->dot)->format('Y-m-d'),
                            'Maks' =>$la->status,
                            'Pemakaian (bulan)' =>'',
                            'Pemakaian (tahun)' =>$la->sert_no,
                            'Sisa Pemakaian (tahun)' =>'',
                            'Harga Perolehan' => number_format($la->nominal,2,'.',''),
                            'Akumulasi Penyusutan Awal Bulan' =>'',
                            'Nilai Sisa Awal Bulan' => number_format($la->nominal,2,'.',''),
                            'Penyusutan' => '',
                            'Akumulasi Penyusutan Akhir Bulan' =>'',
                            'Nilai Sisa Akhir Bulan' => number_format($la->nominal,2,'.',''));
            $totalnom += $la->nominal;$x++;
        }
        $datalap[] = array('No' => '',
                        'Jenis Aktiva' => '',
                        'Q' => '',
                        'Tanggal Beli' => '',
                        'Maks' => '',
                        'Pemakaian (bulan)' =>'',
                        'Pemakaian (tahun)' =>'',
                        'Sisa Pemakaian (tahun)' =>'TOTAL TANAH',
                        'Harga Perolehan' => number_format($totalnom,2,'.',''),
                        'Akumulasi Penyusutan Awal Bulan' =>'0',
                        'Nilai Sisa Awal Bulan' => number_format($totalnom,2,'.',''),
                        'Penyusutan' => '0',
                        'Akumulasi Penyusutan Akhir Bulan' =>'0',
                        'Nilai Sisa Akhir Bulan' => number_format($totalnom,2,'.',''));
        $datalap[] = array();

        $datalap[] = array('No'=>'','Jenis Aktiva' => 'UANG JAMINAN LISTRIK');

        $datalap[] = array('No' => '1',
                        'Jenis Aktiva' => 'TK KOPER',
                        'Q' => '1',
                        'Tanggal Beli' => '2016-02-29',
                        'Maks' => '',
                        'Pemakaian (bulan)' =>'',
                        'Pemakaian (tahun)' =>'',
                        'Sisa Pemakaian (tahun)' =>'',
                        'Harga Perolehan' => number_format(5372400,2,'.',''),
                        'Akumulasi Penyusutan Awal Bulan' =>'0',
                        'Nilai Sisa Awal Bulan' => number_format(5372400,2,'.',''),
                        'Penyusutan' => '0',
                        'Akumulasi Penyusutan Akhir Bulan' =>'0',
                        'Nilai Sisa Akhir Bulan' => number_format(5372400,2,'.',''));
        $datalap[] = array('No' => '2',
                        'Jenis Aktiva' => 'Mekarwangi (Penambah Daya)',
                        'Q' => '1',
                        'Tanggal Beli' => '2016-02-29',
                        'Maks' => '',
                        'Pemakaian (bulan)' =>'',
                        'Pemakaian (tahun)' =>'',
                        'Sisa Pemakaian (tahun)' =>'',
                        'Harga Perolehan' => number_format(2178000,2,'.',''),
                        'Akumulasi Penyusutan Awal Bulan' =>'0',
                        'Nilai Sisa Awal Bulan' => number_format(2178000,2,'.',''),
                        'Penyusutan' => '0',
                        'Akumulasi Penyusutan Akhir Bulan' =>'0',
                        'Nilai Sisa Akhir Bulan' => number_format(2178000,2,'.',''));
        $datalap[] = array('No' => '',
                        'Jenis Aktiva' => '',
                        'Q' => '',
                        'Tanggal Beli' => '',
                        'Maks' => '',
                        'Pemakaian (bulan)' =>'',
                        'Pemakaian (tahun)' =>'',
                        'Sisa Pemakaian (tahun)' =>'TOTAL UANG JAMINAN LISTRIK',
                        'Harga Perolehan' => number_format(7550400,2,'.',''),
                        'Akumulasi Penyusutan Awal Bulan' =>'0',
                        'Nilai Sisa Awal Bulan' => number_format(7550400,2,'.',''),
                        'Penyusutan' => '0',
                        'Akumulasi Penyusutan Akhir Bulan' =>'0',
                        'Nilai Sisa Akhir Bulan' => number_format(7550400,2,'.',''));

        Excel::create("INV ", function($excel) use ( $datalap, $x) {
                //$excel->sheet('Sheet 1', function($sheet) use ($datas)
                //{
                //    $sheet->fromArray($datas,null,'A1',true);
                //});
                $excel->sheet('Sheet 1', function($sheet) use ($datalap, $x)
                {
                    $sheet->setAllBorders('hair');
                    $sheet->setBorder('A2:N2','medium');
                    $sheet->setBorder('A'.($x+2).':N'.($x+2),'medium');
                    $sheet->setBorder('A'.($x+4).':N'.($x+4),'medium');
                    $sheet->setBorder('A'.($x+7).':N'.($x+7),'medium');
                    $sheet->setAutoSize(true);
                    $sheet->fromArray($datalap,null,'A1',true);
                });
                })->download('xls');
    }
}

