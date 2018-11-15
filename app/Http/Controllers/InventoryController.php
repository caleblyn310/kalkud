<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KodeUnit;
use App\Category;
use App\Inventory;
use App\Depreciation;
use App\DepDetail;
use Excel;
use App\Http\Controllers\MakeExcelController;
use Validator;
use Session;
use Carbon\Carbon;
use DB;
use DateTime;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $inventory_list = Inventory::orderBy('id','desc')->paginate(15);
        $period = Depreciation::all()->last()->period->addMonthNoOverflow();
        /*$temp = Inventory::findOrFail(1);
        $dt = Carbon::today();
        $t = $dt->diffInMonths($temp->tanggal_beli);*/
        
        return view('inventory.index',compact('inventory_list','period'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list_unit = KodeUnit::pluck('unit','id');
        $cat_list = Category::pluck('category','id');
        return view('inventory.create',compact('list_unit','cat_list'));
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
            'jenis_aktiva' => 'required',
            'quantity' => 'required|numeric',
            'harga' => 'required|numeric',
            'total' => 'required|numeric',
            'maks' => 'required|numeric',
            'penyusutan' => 'required|numeric',
            'tanggal_beli' => 'required|before_or_equal:'.date('Y-m-d')],[
                'jenis_aktiva.required' => 'Jenis aktiva belum di input',
                'quantity.required' => 'Quantity belum di input',
                'harga.required' => 'Harga belum di input',
                'total.required' => 'Total belum di input',
                'maks.required' => 'Maksimum pemakaian belum di input',
                'penyusutan.required' => 'Penyusutan belum di input',
                'lokasi.required' => 'Lokasi belum di pilih',
                'tanggal_beli.before_or_equal' => 'Tanggal harus sebelum atau hari ini'
        ]);

        if ($validator->fails())
        { return redirect('inventory/create')->withInput()->withErrors($validator); }

        $inv = Inventory::create($input);
        $inv->kodeunit()->attach($request->input('locinven'));
        Session::flash('flash_message', 'Data inventory berhasil disimpan.');
        return redirect('inventory');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $list_unit = KodeUnit::pluck('unit','id');
        $cat_list = Category::pluck('category','id');
        return view('inventory.edit',compact('list_unit','inventory','cat_list'));    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $input = $request->all();

        $validator = Validator::make($input, [
            'jenis_aktiva' => 'required',
            'quantity' => 'required|numeric',
            'harga' => 'required|numeric',
            'total' => 'required|numeric',
            'maks' => 'required|numeric',
            'penyusutan' => 'required|numeric',
            'tanggal_beli' => 'required|before_or_equal:'.date('Y-m-d')],[
                'jenis_aktiva.required' => 'Jenis aktiva belum di input',
                'quantity.required' => 'Quantity belum di input',
                'harga.required' => 'Harga belum di input',
                'total.required' => 'Total belum di input',
                'maks.required' => 'Maksimum pemakaian belum di input',
                'penyusutan.required' => 'Penyusutan belum di input',
                'lokasi.required' => 'Lokasi belum di pilih',
                'tanggal_beli.before_or_equal' => 'Tanggal harus sebelum atau hari ini'
        ]);

        if ($validator->fails())
        { return redirect('inventory/'.$id.'/edit')->withInput()->withErrors($validator); }

        $inventory->update($input);
        $inventory->kodeunit()->sync($request->input('locinven'));
        Session::flash('flash_message', 'Data inventory berhasil di-update.');
        return redirect('inventory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function setInv($invno)
    {
        DB::table('inventory')->where('id',$invno)->update(['status' => 'D']);
        Session::flash('flash_message', 'Data inventory berhasil di-update.');
        return redirect('inventory');
    }

    public function setNoInv($invno)
    {
        DB::table('inventory')->where('id',$invno)->update(['status' => 'NI']);
        Session::flash('flash_message', 'Data inventory berhasil di-update.');
        return redirect('inventory');
    }

    public function lockinven($idinv)
    {
        DB::table('inventory')->where('id',$idinv)->update(['status' => 'D']);
        Session::flash('flash_message', 'Data inventory berhasil dikunci.');
        return redirect('inventory');
    }

    public function genDepreciation()
    {
        $dep = Depreciation::all()->last();
        //dd($dep);
        if($dep->count() > 0)
        {
        //$test = Inventory::selectRaw('PERIOD_DIFF(date_format(CURRENT_DATE,\'%Y%m\'), date_format(tanggal_beli,\'%Y%m\')) as diff')->where('id',1)->get();
            $period = $dep->period->addMonthNoOverflow();
            $dt = date('dmY');

            $dep = new Depreciation();
            $dep->id_dep = $dt;
            $dep->period = $period;
            $dep->save();

            $datas = [];
            $nomor = 1;$i = 0;$no = 1;$cat = 1;
            $sum1 = 0;$sum2 = 0;$sum3 = 0;$sum4 = 0;$sum5 = 0;$sum6 = 0;
            $dt1 = new DateTime("2000-01");
            $dt2 = new DateTime($period);
            $m = $period->month;$y = $period->year;

            //$invenlist = Inventory::whereRaw('MONTH(tanggal_beli) < 1 OR YEAR(tanggal_beli) < 2018')->get();
            $catlist = Category::get();

            if(!empty($catlist)) {
                foreach ($catlist as $cat) {
                    if($cat->id == 2) $datalap[] = array('No' => 'Inventaris Ciateul');
                    elseif($cat->id == 3) $datalap[] = array('No' => 'Inventaris Kopo Permai');
                    elseif($cat->id == 4) $datalap[] = array('No' => 'Inventaris Taman Kopo Indah');
                    elseif($cat->id == 5) $datalap[] = array('No' => 'Inventaris Mekar Wangi');
                    elseif($cat->id == 6) $datalap[] = array('No' => 'Inventaris Kendaraan');

                    $sum1 = 0;$sum2 = 0;$sum3 = 0;$sum4 = 0;$sum5 = 0;$sum6 = 0;
                    
                    $invenlist = Inventory::whereRaw("id_cat = $cat->id AND (MONTH(tanggal_beli)<$m OR YEAR(tanggal_beli)<$y)")->get();

                    foreach ($invenlist as $inven) {
                        if ($inven->status == 'D') {
                        $maks = $inven->maks;$maks = $maks * 12;$pm = $inven->pemakaian;$total = $inven->total;
                        $pny = round((float) ($total / $maks),2,PHP_ROUND_HALF_UP); $i = 0;
                             
                        $dep_detail = new DepDetail();
                        $dep_detail->id_dep = $dt;
                        $dep_detail->id_inven = $inven->id;
                        $dep_detail->save();

                        $dt1 = $dt1->modify($inven->tanggal_beli->format("Y-m"));
                        $datediff = $dt2->diff($dt1);
                        $datediff = $datediff->m + ($datediff->y * 12);
                        if($datediff == 1) $pm;
                        elseif($datediff > 1) $pm++;
                        
                        $nomor++;
                        
                        $apab = round($pny * $pm,2,PHP_ROUND_HALF_UP);
                        ($pm == $maks) ? $pny = 0 : $pny;
                        $sum1 += $total;$sum2 += $apab;$sum3 += ($total - $apab);
                        $sum4 += $pny;$sum5 += ($apab + $pny);$sum6 += ($total - $apab - $pny);
                        

                        $datalap[] = array('No' =>$no,
                                'Jenis Aktiva' =>$inven->jenis_aktiva,
                                'Q' =>$inven->quantity,
                                'Tanggal Beli' =>$inven->tanggal_beli->format('Y-m-d'),
                                'Maks' =>$inven->maks,
                                'Pemakaian (bulan)' =>$pm,
                                'Pemakaian (tahun)' =>round((float) $pm/12,2,PHP_ROUND_HALF_UP),
                                'Sisa Pemakaian (tahun)' =>round((float) ($maks/12) - ($pm/12),2,PHP_ROUND_HALF_UP),
                                'Harga Perolehan' =>$total,
                                //'Akumulasi Penyusutan Awal Bulan' =>(stripos($apab,'.') !== false) ? substr($apab, 0, stripos($apab, '.')+3) : $apab,
                                'Akumulasi Penyusutan Awal Bulan' =>number_format($apab,2,'.',''),
                                'Nilai Sisa Awal Bulan' =>number_format($total - $apab,2,'.',''),
                                'Penyusutan' =>$pny,
                                'Akumulasi Penyusutan Akhir Bulan' =>number_format($apab + $pny,2,'.',''),
                                'Nilai Sisa Akhir Bulan' => number_format($total - $apab - $pny,2,'.',''));
                        
                        DB::table('inventory')->where('id',$inven->id)->update(['pemakaian' => $pm]);
                        if($pm == $maks) DB::table('inventory')->where('id',$inven->id)->update(['status' => 'FD']);
                        }
                        else {
                        $maks = $inven->maks;$pm = $maks * 12;$total = $inven->total;

                        $datalap[] = array('No' =>$no,
                            'Jenis Aktiva' =>$inven->jenis_aktiva,
                            'Q' =>$inven->quantity,
                            'Tanggal Beli' =>$inven->tanggal_beli->format('Y-m-d'),
                            'Maks' =>$inven->maks,
                            'Pemakaian (bulan)' => $pm,
                            'Pemakaian (tahun)' => $inven->maks,
                            'Sisa Pemakaian (tahun)' => 0,
                            'Harga Perolehan' =>$inven->total,
                            //'Akumulasi Penyusutan Awal Bulan' =>(stripos($apab,'.') !== false) ? substr($apab, 0, stripos($apab, '.')+3) : $apab,
                            'Akumulasi Penyusutan Awal Bulan' => $inven->total,
                            'Nilai Sisa Awal Bulan' =>0,
                            'Penyusutan' => 0,
                            'Akumulasi Penyusutan Akhir Bulan' => $inven->total,
                            'Nilai Sisa Akhir Bulan' =>0);
                        $sum1 += $total;$sum2 += round($total,2,PHP_ROUND_HALF_UP);$sum5 += round($total,2,PHP_ROUND_HALF_UP);
                        $sum3 += 0;$sum4 += 0;$sum6 += 0;
                        }
                        $no++;
                    }

                    $invenlist = Inventory::whereRaw("id_cat = $cat->id AND MONTH(tanggal_beli) = $m AND YEAR(tanggal_beli) = $y")->get();

                    foreach ($invenlist as $inven) {
                        $total = $inven->total;
                        $datalap[] = array('No' =>$no,
                            'Jenis Aktiva' =>$inven->jenis_aktiva,
                            'Q' =>$inven->quantity,
                            'Tanggal Beli' =>$inven->tanggal_beli->format('Y-m-d'),
                            'Maks' =>$inven->maks,
                            'Pemakaian (bulan)' => '-',
                            'Pemakaian (tahun)' => '-',
                            'Sisa Pemakaian (tahun)' => '-',
                            'Harga Perolehan' => $total,
                            'Akumulasi Penyusutan Awal Bulan' => '-',
                            'Nilai Sisa Awal Bulan' => '-',
                            'Penyusutan' => '-',
                            'Akumulasi Penyusutan Akhir Bulan' => '-',
                            'Nilai Sisa Akhir Bulan' => $total);
                        $sum1 += $total;$sum6 += $total;$no++;
                    }

                    $datalap[] = array('No' => '',
                            'Jenis Aktiva' => '',
                            'Q' => '',
                            'Tanggal Beli' => '',
                            'Maks' =>'',
                            'Pemakaian (bulan)' =>'',
                            'Pemakaian (tahun)' =>'',
                            'Sisa Pemakaian (tahun)' =>'TOTAL',
                            'Harga Perolehan' =>$sum1,
                            'Akumulasi Penyusutan Awal Bulan' =>number_format($sum2,2,'.',''),
                            'Nilai Sisa Awal Bulan' =>number_format($sum3,2,'.',''),
                            'Penyusutan' => number_format($sum4,2,'.',''),
                            'Akumulasi Penyusutan Akhir Bulan' =>number_format($sum5,2,'.',''),
                            'Nilai Sisa Akhir Bulan' =>number_format($sum6,2,'.',''));
                    $datalap[] = array();
                }
            }
                Session::flash('flash_message', 'Penyusutan bulan ini berhasil di-generate');
                //dd($datalap);
                Excel::create("INV ".$period->format('M y'), function ($excel) {
                    $excel->sheet('Sheet 1', function ($sheet) {
                        $invlist = Inventory::all();
                        $sheet->fromModel($invlist,null,'A1',true);
                    });
                })->store('csv');

                Excel::create("INV ".$period->format('M y'), function($excel) use ( $datalap) {
                //$excel->sheet('Sheet 1', function($sheet) use ($datas)
                //{
                //    $sheet->fromArray($datas,null,'A1',true);
                //});
                $excel->sheet('Sheet 1', function($sheet) use ($datalap)
                {
                    $sheet->setAutoSize(true);
                    $sheet->fromArray($datalap,null,'A1',true);
                });
                })->download('csv');
        }
        

        Session::flash('flash_message', 'Penyusutan bulan ini sudah pernah di-generate.');
        return redirect('inventory');
    }
}
