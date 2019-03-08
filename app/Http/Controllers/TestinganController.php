<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use App\vabdiall;
use Carbon\Carbon;
use PDF;

class TestinganController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        
        return view('testingan');
    }

    public function getSecret()
    {
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
    }

    public function krtUjian() {
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
</html>';*/
        $height = 228; $width = 379; $top1 = 0; $top2 = 10; $top3 = 70; $top4 = 210; $top5 = 160; $posx = 20; $mul = 0;
        $html = '<style>
        .crop {
            background-image: url("..\avenger.jpg");
            width: 70px;
            height: 90px;
            background-size: 720px 405px;
            border: 1px solid red;
        }</style>';
        for ($y=0 ; $y < 5 ; $y++ ) {
            $left1 = 0; $left2 = 10; $left3 = 85; $left4 = 175;
            for ($x=0; $x < 2; $x++) { 
             $html .= '<div style="position: fixed; top: '.$top1.'px; left: '.$left1.'px; width: 100mm; height: 60mm; border: 1px solid black;"></div>
        <div style="position: fixed; top: '.$top2.'px; left: '.$left2.'px; width: 50px; height: 50px; ">caleb</div>
        <div style="position: fixed; top: '.$top2.'px; left: '.$left3.'px; width: 280px; height: 50px; border: 1px solid black; padding: 0px;">
        caleb</div>
        <div style="position: fixed; top: '.$top3.'px; left: '.$left2.'px; background-position: -'.$posx.'px -50px" class="crop"></div>
        <div style="position: fixed; top: '.$top3.'px; left: '.$left3.'px; width: 280px; height: 80px; padding: 0px;">
        caleb</div>
        <div style="position: fixed; top: '.$top4.'px; left: '.$left2.'px; width: 150px; height: 10px; "><strong>Catatan:</strong> Kartu harus di bawa saat ujian.</div>
        <div style="position: fixed; top: '.$top5.'px; left: '.$left4.'px; width: 190px; height: 60px; ">caleb</div>';
            $left1+=$width;$left2+=$width;$left3+=$width;$left4+=$width;
            if (($mul / 6) == 1) { $mul = 0; $posx = 20; } else {$posx = 100 + $posx;$mul++;}
            }
            $top1+=$height;$top2+=$height;$top3+=$height;$top4+=$height;$top5+=$height;
        }
        
        $pdf = PDF::loadHTML($html, ['format' => 'Folio']);

        return $pdf->stream('calebs');
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
}
