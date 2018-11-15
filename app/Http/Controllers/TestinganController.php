<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
