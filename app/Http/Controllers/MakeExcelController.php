<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Cheque;
use App\Inventory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Session;

class MakeExcelController extends Controller
{
    public function exportExcel($checkid)
    {
    	$temp = Cheque::findOrFail($checkid);
        $middle = DB::table('kodeunit')->select('middle')->where('id',$temp->kode_unit)->value('middle');
        $tempdate = $temp->tanggal_cair;
        $tblview = substr($temp->data_reimburse, 0,-4);
        $filetitle = "Reimburse " . $middle . " " . substr($temp->data_reimburse, 9, 8);


        if(Auth::user()->kode_unit == 100)
        {
            ($temp->kode_unit != 9) ? $contraacc = '111.2'.$temp->kode_unit.'.111' : $contraacc = '111.33.111';
            if($temp->kode_unit == 0) $contraacc = '111.30.111';
        	$data = DB::connection('mysql3')->select(DB::raw('select * from '. $tblview .' ORDER BY no_bukti, tanggal_trans, id'));
        	
        	$datas = [];
            $nomor = 1;
            $short = DB::table('kodeunit')->select('short')->where('id',$temp->kode_unit)->get();
            $short = $short->toArray();
            $short = $short[0]->short;

        	foreach ($data as $dt) {
        		$datas[] = array(
        			'No_account' => $dt->kode_d_ger,
        			'No_bukti' => str_pad($nomor, 3,"0",STR_PAD_LEFT).'/'.$dt->no_bukti.'/'.$short.'-'.$temp->tanggal_cair->format('m/y'),
        			'Tanggal' => $tempdate,
        			'Uraian' => $dt->deskripsi,
        			'Debet' => $dt->nominal,
        			'Kredit' => 0,
        			'Kontra_acc' => $contraacc,
        		);$nomor++;}
            $datas[] = array(
                'No_account' => '112.28.111',
                'No_bukti' => '/BDIUM-'.$temp->tanggal_cair->format('m/y'),
                'Tanggal' => $tempdate,
                'Uraian' => 'Cek BDI dengan no. '. $temp->no_check .' untuk reimburst: '.$tblview,
                'Debet' => 0,
                'Kredit' => $temp->nominal,
                'Kontra_acc' => $contraacc,
            );
            //dd($datas);
        }
        else
        {
            $data = DB::select(DB::raw('select * from '. $tblview .' ORDER BY subkode, tanggal_trans, no_bukti, id'));
            $totalsk = DB::select(DB::raw('select subkode, SUM(nominal) as total from '.  $tblview . ' GROUP BY subkode'));
            
            $datas = [];

            foreach ($data as $dt) {
                $datas[] = array(
                    'Tanggal' => $dt->tanggal_trans,
                    'No BPU' => $dt->no_bukti,
                    'Sub Kode' => $dt->subkode,
                    'Kode D-Ger' => $dt->kode_d_ger,
                    'Uraian' => $dt->deskripsi,
                    'Nominal' => $dt->nominal,
                );}

            $datas[] = array('Total per Sub Kode');
            $datas[] = array('Sub Kode','Total');
            foreach ($totalsk as $tsk) { 
                $datas[] = array(
                    'Sub Kode' => $tsk->subkode,
                    'Nominal' => $tsk->total,
                );}

        }
    	
        return Excel::create($filetitle, function($excel) use ($datas) {
			$excel->sheet('Sheet 1', function($sheet) use ($datas)
	        {
				$sheet->fromArray($datas,null,'A1',true);
	        });
		})->download('csv');

    }

    public function downloadbpb()
    {
        $temp = DB::table('invoices')
                    ->join('invoices_detail','invoices.invoices_no','=', 'invoices_detail.invoices_no')
                    ->select('invoices.dot','invoices_detail.invoices_no','invoices_detail.description','invoices_detail.nominal')
                    ->where('status','p')->get();

        if(count($temp) > 0) {
        $datas = [];$nomor = 1;$invno = 0;

        foreach ($temp as $t) {
            if ($invno != $t->invoices_no) { $nomor = 1; $invno = $t->invoices_no; }
            else { $nomor++; }

            $datas[] = array(
                'Kode COA' => '',
                'Slip No' => $nomor.'/'.$t->invoices_no.'/BDIUM-'.Carbon::parse($t->dot)->format('m/y'),
                'Tanggal' => Carbon::parse($t->dot)->format('Y-m-d'),
                'Uraian' => $t->description,
                'Debit' => 0,
                'Kredit' => $t->nominal,
                'Kontra_acc' => '',
            );
        }

        DB::table('invoices')->where('status','p')->update(['status' => 'dg']);
        return Excel::create("Download BPB " . date('Y-m-d'), function($excel) use ($datas) {
            $excel->sheet('Sheet 1', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas,null,'A1',true);
            });
        })->download('csv');
        }

        else { Session::flash('flash_message','Semua data sudah diimpor ke database d-ger'); return redirect('kaskecil'); }
    }

    public function convertBCA(Request $request)
    {
        ob_end_clean();
        ob_start();
        $middle = DB::table('kodeunit')->select('middle')->where('id',Auth::user()->kode_unit)->value('middle');
        if($request->hasFile('filebca')){
            $dtex = [];
            $dtex[] = array('Penerimaan VA BCA '.$middle);
            $dtex[] = array('Tanggal','NO VA BCA','Nama Siswa','Keterangan','Nominal');
            $row = 4;

            $totaldebit = 0;
            $totalkredit = 0;
            
            foreach ($request->filebca as $fb) {

            $path = $fb->path();
            $data = fopen($path,"r");
            $rw = 0;$tdt = "";$ky = "";
            
            while(!feof($data))
            {
                $rw++;
                $datos = fgets($data);
                if($rw == 2)
                {
                    $tdt = "20".substr($datos, 131, 2)."-".substr($datos, 128,2)."-".substr($datos, 125, 2);
                }
                else if($rw == 4)
                {
                    $ky = substr($datos, 19,5);
                }
                else if($rw >= 10 && (substr($datos, 5, 1) > 0 || substr($datos, 4, 1) > 0 ))
                {
                    /*if(substr($datos,0,1) != "") {
                        $row++;

                        $dtex[] = array('Tanggal'=>"20".substr($datos,66,2)."-".substr($datos,64,2)."-".substr($datos,62,2),
                            'No VA BCA'=>substr($datos, 0,5).substr($datos, 10,11),
                            'Nominal'=>ltrim(substr($datos, 47,13),"0"));
                        $totalkredit += substr($datos, 47,13);
                    }*/
                    $row++;
                    $nominal = str_replace(",", "", ltrim(substr($datos, 58,10)," "));

                    $dtex[] = array('Tanggal' => $tdt,
                        'No VA BCA' => $ky.substr($datos, 8,11),
                        'Nama Siswa' => substr($datos, 28,18),
                        'Keterangan' => substr($datos, 100,17)."|".substr($datos, 117,16),
                        'Nominal'=> (int)$nominal);
                    //dd($nominal);
                    $totalkredit += (int)$nominal;
                }
            }
        }
            fclose($data);
            $dtex[] = array('','','','Total Penerimaan',$totalkredit);

            /*return Excel::create('Laporan VA BCA', function($excel) use ($dtex,$row) {
            $excel->sheet('Sheet 1', function($sheet) use ($dtex,$row)
            {
                $sheet->cells('A3:C3', function($cells) {

                    // call cell manipulation methods
                    $cells->setBackground('#7CFC00');

                });
                $sheet->mergeCells('A'.$row.':B'.$row);
                $sheet->cells('A'.$row,function($cells) {$cells->setAlignment('right');});
                $sheet->setBorder('A3:C'.$row,'thin');
                $sheet->setAutoSize(true);
                $sheet->with($dtex,null,'A1',true,false);
            });
            })->export('xls');*/
            return Excel::create('Laporan VA BCA', function($excel) use ($dtex,$row) {
            $excel->sheet('Sheet 1', function($sheet) use ($dtex,$row)
            {
                $sheet->cells('A3:E3', function($cells) {$cells->setBackground('#4Cff4C');});
                $sheet->setColumnFormat(array('E' => '#,##0'));
                $sheet->cells('D'.$row, function($cells) {$cells->setBackground('#4Cff4C');});
                $sheet->cells('D'.$row,function($cells) {$cells->setAlignment('right');});
                $sheet->setBorder('A3:E'.$row,'thin');
                $sheet->setAutoSize(true);
                $sheet->with($dtex,null,'A1',true,false);
            });
            })->export('xls');
    }
}

    public function makeDepreciation($inventory)
    {
        dump($inventory);
    }
}
