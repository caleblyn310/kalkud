<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Kaskecil;
use App\Cheque;
use App\Simpanfile;
use App\KodeUnit;
use App\AccNum;
use Session;
use PDF;
use App;
use DB;
use Carbon\Carbon;
use View;
use Response;

class KaskecilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /*if (Auth::user()->id == 19) {
            return redirect('invoices');
        }
        else if(Auth::user()->kode_unit < 50) {*/

        if(Auth::user()->kode_unit == 100) {
            $client = new \GuzzleHttp\Client();
            $request = $client->get('https://kalamkudus.or.id/kaskecil/api/kaskecil');
            $kaskecil_list = json_decode($request->getBody()->getContents());
            $kode_unit = collect($kaskecil_list)->unique('kode_unit');
            $groupKaskecil = collect($kaskecil_list)->groupBy('kode_unit')->toArray();

            return view('kaskecil.index',compact('kaskecil_list','kode_unit','groupKaskecil'));
        }
        else{
                $kaskecil_list = Kaskecil::where([['kode_unit', Auth::user()->kode_unit], ['status', 'bu']])
                    ->orderBy('no_bukti')->orderBy('tanggal_trans')->orderBy('id');
                    
                $totalreim = $kaskecil_list->sum('nominal');
                $kaskecil_list = $kaskecil_list->paginate(15);
                
                $plafon = DB::table('kodeunit')->where('id', Auth::user()->kode_unit)->value('plafon');
        
                $reimburse = DB::table('simpanfile')->where('kode_unit',Auth::user()->kode_unit)->whereNotIn('mode',['final','cheque'])->get();
                //dd(count($reimburse));
                (count($reimburse)>0) ? $reimburse = $reimburse[0]->namafile . '|' . $reimburse[0]->mode : $reimburse = '' ;
                $totalcoa = Kaskecil::select(DB::raw('kode_d_ger, SUM(nominal) as total'))
                    ->where([['status','bu'],['kode_unit',Auth::user()->kode_unit]])->groupBy('kode_d_ger')->get();
        
                $totalsk = Kaskecil::select(DB::raw('subkode, SUM(nominal) as total'))
                    ->where([['status','bu'],['kode_unit',Auth::user()->kode_unit]])->groupBy('subkode')->get();
                return view('kaskecil.index', compact('kaskecil_list', 'plafon', 'totalreim','reimburse','totalcoa','totalsk'));}
        /*}
        else if(Auth::user()->kode_unit == 100) {
            $check_list = Cheque::where([['mode','!=','print'],['id','>',10]])->orderBy('tanggal_cair','desc')->get();
            return view('check.index',compact('check_list'));
        }
        else if(Auth::user()->kode_unit == 50) {
            return redirect('inventory');
        }*/
    }

    public function create()
    {
        //$list_unit = KodeUnit::pluck('unit','id');
        $kaskecil_list = Kaskecil::where([['kode_unit', Auth::user()->kode_unit], ['status', 'bu']])
                    ->orderBy('no_bukti')->orderBy('tanggal_trans')->orderBy('id');
                    
        $totalreim = $kaskecil_list->sum('nominal');
        
        $plafon = DB::table('kodeunit')->where('id', Auth::user()->kode_unit)->value('plafon');

        $sisaSaldo = $plafon - $totalreim;
        return view('kaskecil.create',compact("sisaSaldo"));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'kode_d_ger' => 'required|min:10',
            'no_bukti' => 'required',
            'tanggal_trans' => 'required|before_or_equal:'.date('Y-m-d')],[
                'kode_d_ger.required' => 'Kode D-Ger belum di input',
                'tanggal_trans.before_or_equal' => 'Tanggal harus sebelum atau hari ini'
        ]);

        if ($validator->fails())
        { return redirect('kaskecil/create')->withInput()->withErrors($validator); }

        Kaskecil::create($input);
        Session::flash('flash_message', 'Data berhasil disimpan.');
        return redirect('kaskecil');
    }

    public function edit(Kaskecil $kaskecil)
    {
        $kaskecil_list = Kaskecil::where([['kode_unit', Auth::user()->kode_unit], ['status', 'bu']])
                    ->orderBy('no_bukti')->orderBy('tanggal_trans')->orderBy('id');
                    
        $totalreim = $kaskecil_list->sum('nominal');
        
        $plafon = DB::table('kodeunit')->where('id', Auth::user()->kode_unit)->value('plafon');
        $sisaSaldo = $plafon - $totalreim + $kaskecil->nominal;
        return view('kaskecil.edit',compact('kaskecil','sisaSaldo'));
    }

    public function update(Kaskecil $kaskecil,Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'kode_d_ger' => 'required|min:10',
            'no_bukti' => 'required',
            'tanggal_trans' => 'required|before_or_equal:'.date('Y-m-d')],[
            'kode_d_ger.required' => 'Kode D-Ger belum di input',
            'tanggal_trans.before_or_equal' => 'Tanggal harus sebelum atau hari ini'
        ]);

        if ($validator->fails())
        { return redirect('kaskecil/'.$kaskecil->id.'/edit')->withInput()->withErrors($validator); }

        $kaskecil->update($request->all());
        Session::flash('flash_message', 'Data berhasil diupdate.');
        return redirect('kaskecil');
    }

    public function destroy(Kaskecil $kaskecil)
    {
        $kaskecil->delete();
        Session::flash('flash_message','Data berhasil dihapus');
        Session::flash('penting',true);
        return redirect('kaskecil');
    }

    public function searchtransaction(Request $request)
    {
        $input = $request->all();
        
        if(!empty($input)) {
            $kaskecil = Kaskecil::findOrFail($input['id']);
            return view('kaskecil.search',compact('kaskecil'));
        }
        return view('kaskecil.search');
    }

    public function transactions()
    {
        return view('kaskecil.transactions');
    }

    public function laporanKasAdmin(Request $request)
    {
        $input = $request->all();//dd($input);

        $sk_list = Kaskecil::where([['subkode','!=',''],['kode_unit',Auth::user()->kode_unit]])->distinct('subkode')->orderByRaw('LENGTH(subkode)', 'ASC')->orderBy('subkode')->pluck('subkode','subkode');
        $sk_list = $sk_list->toArray();
        $sk_list['0'] = "Semua";
        $coa_list = AccNum::where([['status',Auth::user()->kode_unit],['accnum_id','NOT LIKE','111.%'],['accnum_id','NOT LIKE','415.%']])->orderBy('accnum_id')->pluck('accnum_id','accnum_id');
        //$coa_list['0'] = 'Semua';
        $coa_list = $coa_list->toArray();
        array_unshift($coa_list, "Semua");
        //dd($sk_list);
        //dd($sk_list);
        if(!empty($input)) {
            $dt1 = $input['tanggal1'];
            $dt2 = $input['tanggal2'];
            

            $validator = Validator::make($input, [
                'tanggal2' => 'required|before_or_equal:'.date('Y-m-d'),
                'tanggal1' => 'required|before:'.$dt2],[
                    'subkode.required' => 'Sub Kode belum di pilih',
                    'tanggal2.required' => 'Tanggal2 belum di input',
                    'tanggal2.before_or_equal' => 'Tanggal2 harus sebelum / sama dengan hari ini',
                    'tanggal1.required' => 'Tanggal1 belum di input',
                    'tanggal1.before' => 'Tanggal1 harus sebelum Tanggal2'
            ]);

            if ($validator->fails())
                {   
                    Session::flash('flash_message','Silakan set tanggal dengan benar. Tidak bisa lebih dari hari ini.');
                    return redirect('kaskecil/laporan');}

            $transactions = ''; $kategori = $input['kategori'];
            
            if($kategori == '1') { $sk = $input['subkode']; 
                if($sk == '0') {
                    $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode','!=',null],['subkode','!=',''],['kode_unit',Auth::user()->kode_unit]])->orderByRaw('LENGTH(subkode)','ASC')->orderBy('subkode')->orderBy('tanggal_trans')->get();
    
                    $totalsk = Kaskecil::select(DB::raw('subkode as sub, SUM(nominal) as total'))
                            ->where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode','!=',null],['subkode','!=',''],['kode_unit',Auth::user()->kode_unit]])->orderByRaw('LENGTH(subkode)','ASC')->orderBy('subkode')->groupBy('subkode')->get();
                    $sk = '1|0';
                    return view('kaskecil.laporan',compact('transactions','totalsk','sk_list','coa_list','sk'));
                }
                else if($sk != null){
                    $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode',$sk],['kode_unit',Auth::user()->kode_unit]])->orderBy('subkode')->orderBy('tanggal_trans')->get();
                    $sk = '1|' . $sk;
                    return view('kaskecil.laporan', compact('transactions','sk_list','coa_list','sk'));
            }}
            else if($kategori == '0') { $coa = $input['coa'];
                if($coa == '0') {
                    $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['kode_unit',Auth::user()->kode_unit]])->orderBy('kode_d_ger')->orderBy('tanggal_trans')->get();
    
                    $totalsk = Kaskecil::select(DB::raw('kode_d_ger as sub, SUM(nominal) as total'))
                            ->where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['kode_unit',Auth::user()->kode_unit]])->groupBy('kode_d_ger')->get();
                    $sk = '0|0';
                    return view('kaskecil.laporan',compact('transactions','totalsk','sk_list','coa_list','sk'));
                }
                else if($coa != null) {
                    $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['kode_d_ger',$coa],['kode_unit',Auth::user()->kode_unit]])->orderBy('kode_d_ger')->orderBy('tanggal_trans')->get();
                    $sk = '0|' . $coa; $judul = $coa;
                    return view('kaskecil.laporan', compact('transactions','sk_list','coa_list','sk','judul'));
            }}

        }
        return view('kaskecil.laporan',compact('sk_list','coa_list'));
    }

    public function mPDFGen($mode)
    {
        if(str_contains($mode,'req'))
        {
            //cek apakah sudah buat request reimburse atau belum pada hari ini
            $q = "SELECT date(created_at) FROM simpanfile WHERE kode_unit = ".Auth::user()->kode_unit;
            $q .= " AND date(created_at) = date(now())" ;
            $tempdate = DB::select(DB::raw($q));
                if(count($tempdate) > 0)
                {
                Session::flash('flash_message', 'Hai, hari ini sudah melakukan request. Coba lagi besok ya...');
                Session::flash('penting', true);
                return redirect('kaskecil');
                }

            //cek apakah cheque untuk reimburse yang terakhir sudah diinput atau belum
            $lastreim = Simpanfile::where('kode_unit',Auth::user()->kode_unit)->orderBy('id','desc')->get();
            
            if(count($lastreim) == 0) {}
            else if(count($lastreim) > 0 && $lastreim[0]->mode == 'cheque')
            {
                $lastreim = Cheque::where([['data_reimburse',$lastreim[0]->namafile],['mode','!=','print']])->get();
                
                if(count($lastreim) == 0) 
                {
                    Session::flash('flash_message','Hai, silakan klik tombol \'Buat Laporan\' terlebih dahulu ya untuk cheque yang sudah cair');
                    Session::flash('penting', true);
                    return redirect('kaskecil');
                }
            }
            else
            {
                Session::flash('flash_message', 'Hai, data cheque yang terakhir belum diinput. Silakan input dulu ya.');
                Session::flash('penting', true);
                return redirect('kaskecil');
            }

            //kalau belum lanjut dimari
            $items = Kaskecil::where([['kode_unit',Auth::user()->kode_unit],['status','bu']])
                    ->orderBy('no_bukti')->orderBy('tanggal_trans')->orderBy('id')->get();

            $perawal = $items->min('tanggal_trans')->format('d-m-y');
            
            $perakhir = $items->max('tanggal_trans')->format('d-m-y');
            
            $totalcoa = Kaskecil::select(DB::raw('kode_d_ger, SUM(nominal) as total'))
                ->where([['status','bu'],['kode_unit',Auth::user()->kode_unit]])->groupBy('kode_d_ger')->get();

            $totalsk = Kaskecil::select(DB::raw('subkode, SUM(nominal) as total'))
                ->where([['status','bu'],['kode_unit',Auth::user()->kode_unit]])->groupBy('subkode')->get();
            
            //$jl = 'Rekap Reimburse';
        }
        else if(str_contains($mode,'reimburse'))
        {
            $items = DB::select(DB::raw("select * from $mode ORDER BY  no_bukti, tanggal_trans, id"));
            $totalcoa = DB::select(DB::raw("select kode_d_ger, SUM(nominal) as total from $mode GROUP BY kode_d_ger"));
            $totalsk = DB::select(DB::raw('select subkode, SUM(nominal) as total from '.  $mode . ' GROUP BY subkode'));
            $tempper = DB::select(DB::raw("select MIN(tanggal_trans) as perawal, MAX(tanggal_trans) as perakhir from $mode"));
            $perawal = Carbon::parse($tempper[0]->perawal)->format('d-m-y');
            $perakhir = Carbon::parse($tempper[0]->perakhir)->format('d-m-y');
            $rev = ' - Revisi';//$jl = 'Rekap Reimburse';
        }
        else {
            $tempd = DB::connection('mysql3')->select('select * from datacheck where id = ?',[$mode]);
            foreach ($tempd as $t) {
                $mode = substr($t->data_reimburse,0,-4);
                $items = DB::select(DB::raw("select * from $mode ORDER BY no_bukti, tanggal_trans, id"));
                $totalcoa = DB::select(DB::raw("select kode_d_ger, SUM(nominal) as total from $mode GROUP BY kode_d_ger"));
                $totalsk = DB::select(DB::raw('select subkode, SUM(nominal) as total from '.  $mode . ' GROUP BY subkode'));
                $tempper = DB::select(DB::raw("select MIN(tanggal_trans) as perawal, MAX(tanggal_trans) as perakhir from $mode"));
                $perawal = Carbon::parse($tempper[0]->perawal)->format('d-m-y');
                $perakhir = Carbon::parse($tempper[0]->perakhir)->format('d-m-y');
                $check = 'Cek Danamon: '.$t->no_check.' ('.$t->data_reimburse.')</td>';
                $check .= '<td style="text-align:left;">'. number_format($t->nominal,0,'','.') .',00</td></tr>';
            }
            $mode = 'laporan'.$t->data_reimburse;$rev = '';//$jl = 'Rekap Reimburse';
        }

        /*if(!str_contains($mode,'laporan'))
        {
            $bf = Cheque::where([['kode_unit',Auth::user()->kode_unit],['mode','final']])->orderBy('tanggal_cair','desc')->get();
            $bf = $bf->toArray();
            $pla = DB::select(DB::raw("select plafon from kodeunit where id = :id"),array('id'=>Auth::user()->kode_unit));
            $sak = $pla[0]->plafon - $bf[0]['nominal'];

            $tempt = '<table align="center">';
            $tempt .= '<tr><th style="width:80%;">Deskripsi</th><th>Debit</th><th>Kredit</th></tr>';
            $tempt .= '<tr><td style="text-align:right;">Saldo Akhir</td><td></td>';
            $tempt .= '<td style="text-align:right;">'.number_format($sak,0,'','.').',00</td></tr>';
            $tempt .= '<tr><td style="text-align:right;">Cek Danamon: '.$bf[0]['no_check'].' ('.$bf[0]['data_reimburse'].')</td>';
            $tempt .= '<td style="text-align:left;">'.number_format($bf[0]['nominal'],0,'','.').',00</td><td></td></tr>';
            //$tempt .= '<tr><td>Total</td>';
            //$tempt .= '<td style="text-align:center;">'.number_format($pla[0]->plafon,0,'','.').',00</td></tr></table><br><br>';
            $tempt .= '</table><br>';
        }*/

        //proses generate halaman html untuk diconvert ke pdf nantinya
        $total = 0;
        //PDF::setPaper('A4');
        //  PDF::setOptions(['dpi' => 300, 'defaultFont' => 'sans-serif']);

        $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:65%;border-collapse: collapse;width: 100%;}';
        $td .= 'td, th {border: 1px solid; text-align: center;padding: 2px;}';
        $td .= 'tr:nth-child(even) {background-color: #dddddd;} @page {header: page-header;footer: page-footer;}</style>';
        $td .= '<body><htmlpageheader name="page-header"><H4>Laporan Reimburse - ';
        $td .= DB::table('kodeunit')->where('id',Auth::user()->kode_unit)->value('unit');

        if (str_contains($mode,'reimburse')){$td .= $rev;}

        $td .= ' ('.$perawal.' - '.$perakhir.')</H4></htmlpageheader>';

        //(!str_contains($mode,'laporan')) ? $td .= $tempt : '';

        $td .= '<table align="center">';

        //Start table TU
        if (Auth::user()->kode_unit != 0) {
            $td .= '<tr><th>Tanggal</th><th style="width:55px;">Kode<br>D-Ger</th><th style="width:15px;">Sub<br>Kode</th><th>No BPU</th><th style="width:415px;">Deskripsi</th><th>Nominal</th></tr>';
            if(str_contains($mode,'reimburse')) {
            foreach ($items as $item) {
                $td .= '<tr>';
                $td .= '<td>'.Carbon::parse($item->tanggal_trans)->format('d-m-Y').'</td>';
                $td .= '<td>'.$item->kode_d_ger.'</td>';
                $td .= '<td>'.$item->subkode.'</td>';
                $td .= '<td>'.$item->no_bukti.'</td>';
                $td .= '<td style="text-align:left;">'.$item->deskripsi.'</td>';
                $td .= '<td style="text-align: right;">'.number_format($item->nominal,0,'','.').',00</td>';
                $td .= '</tr>';$total += $item->nominal;
            }}
            else if (str_contains($mode,'req')) {
            foreach ($items as $item) {
                $td .= '<tr>';
                $td .= '<td>'.$item->tanggal_trans->format('d-m-Y').'</td>';
                $td .= '<td>'.$item->kode_d_ger.'</td>';
                $td .= '<td>'.$item->subkode.'</td>';
                $td .= '<td>'.$item->no_bukti.'</td>';
                $td .= '<td style="text-align:left;">'.$item->deskripsi.'</td>';
                $td .= '<td style="text-align: right;">'.number_format($item->nominal,0,'','.').',00</td>';
                $td .= '</tr>';$total += $item->nominal;
            }}
            $td .= '<tr><td colspan="5" style="text-align: right;">Total</td>';
            $td .= '<td style="text-align: right;">'.number_format($total,0,'','.').',00</td></tr>';

            if(str_contains($mode,'laporan'))
                { $td .= '<tr><td colspan="5" style="text-align: right;">'.$check ;}

            $td .= '<tr><td colspan="6"></td></tr><tr><td colspan="4">Total Per Sub Kode</td><td colspan="2">Total Per COA</td></tr>';
            /*foreach ($totalcoa as $tc) {
                $td .= '<tr bgcolor="white"><td colspan="5" style="text-align: right;">'.$tc->kode_d_ger.'</td>';
                $td .= '<td style="text-align: right;">';
                $td .= number_format($tc->total,0,'','.').',00</td></tr>';
            }*/

            //Start Total Per COA dan Total Per SUBKODE
            (count($totalcoa) > count($totalsk)) ? $totalrow=count($totalcoa) : $totalrow=count($totalsk);
            if(!str_contains($mode,'laporan')) $totalsk[0]->subkode = 'Kas Kecil';
            
            for ($i=0; $i < $totalrow; $i++) { 
                $td .= '<tr>';
                if(count($totalsk)>$i)
                {
                    $td .= '<td colspan="2" style="text-align:right;">'.$totalsk[$i]->subkode.'</td>';
                    $td .= '<td colspan="2" style="text-align:right">'.number_format($totalsk[$i]->total,0,'','.').',00</td>';
                }
                else{$td .= '<td colspan="4"></td>';}
                if(count($totalcoa)>$i)
                {
                    $td .= '<td style="text-align:right;">'.$totalcoa[$i]->kode_d_ger.'</td>';
                    $td .= '<td style="text-align:right;">'.number_format($totalcoa[$i]->total,0,'','.').',00</td>';
                }
                else {$td .= '<td colspan="2"></td>';}
                $td .= '</tr>';
            }
            //END Total PER COA dan Total PER SUBKODE
        }
        //End table TU

        //Start table Yayasan
        else if (Auth::user()->kode_unit == 0) {
        $td .= '<tr><th>Tanggal</th><th style="width:55px;">Kode<br>D-Ger</th><th>No BPU</th><th style="width:415px;">Deskripsi</th><th>Nominal</th></tr>';
        if (str_contains($mode,'reimburse')) {
            foreach ($items as $item) {
                $td .= '<tr>';
                $td .= '<td>'.Carbon::parse($item->tanggal_trans)->format('d-m-Y').'</td>';
                $td .= '<td>'.$item->kode_d_ger.'</td>';
                $td .= '<td>'.$item->no_bukti.'</td>';
                $td .= '<td style="text-align:left;">'.$item->deskripsi.'</td>';
                $td .= '<td style="text-align: right;">'
                    .number_format($item->nominal,0,'','.').',00</td>';
                $td .= '</tr>';$total += $item->nominal;
            }}
        else if (str_contains($mode,'req')) {
            foreach ($items as $item) {
                $td .= '<tr>';
                $td .= '<td>'.$item->tanggal_trans->format('d-m-Y').'</td>';
                $td .= '<td>'.$item->kode_d_ger.'</td>';
                $td .= '<td>'.$item->no_bukti.'</td>';
                $td .= '<td style="text-align:left;">'.$item->deskripsi.'</td>';
                $td .= '<td style="text-align: right;">'
                    .number_format($item->nominal,0,'','.').',00</td>';
                $td .= '</tr>';$total += $item->nominal;
            }}
            $td .= '<tr><td colspan="4" style="text-align: right;">Total</td>';
            $td .= '<td style="text-align: right;">'.number_format($total,0,'','.').',00</td></tr>';

            if(str_contains($mode,'laporan'))
                { $td .= '<tr><td colspan="4" style="text-align: right;">'.$check;}

            $td .= '<tr><td colspan="5"></td></tr><tr><td colspan="5">Total Per COA</td></tr>';
            foreach ($totalcoa as $tc) {
                $td .= '<tr bgcolor="white"><td colspan="4" style="text-align: right;">'.$tc->kode_d_ger.'</td>';
                $td .= '<td style="text-align: right;">';
                $td .= number_format($tc->total,0,'','.').',00</td></tr>';
            }
        }
        //End table Yayasan
        
        $td .= '</table><br><br><table style="border: 0px">';
        $td .= '<tr><td colspan="2" style="text-align: right"><b>Bandung, '.date('j F Y').'</b></td></tr>';
        $td .= '<tr style="background-color: white;"><td style="width:50%">';
        (Auth::user()->kode_unit == 0) ? $td .= 'Bendahara' : $td .= 'Kepala Sekolah';
        $td .= '<br><br><br><br><br><br>';
        $td .= DB::table('kodeunit')->where('id',Auth::user()->kode_unit)->value('kepsek').'</td><td>';
        (Auth::user()->kode_unit == 0) ? $td .= 'Keuangan' : $td .= 'TU Keuangan';
        $td .= '<br><br><br><br><br><br>'.Auth::user()->fullname.'</td></tr></table>';
        $td .= '<htmlpagefooter name="page-footer"><p align="right">{PAGENO} / {nb}</p></htmlpagefooter></body>';

        $pdf = PDF::loadHTML($td);
        if(str_contains($mode,"req"))
        {
            $tf = 'reimburse'.date('dmY').'u'.Auth::user()->kode_unit.'.pdf';
            $this->updateStatus();
            DB::table('simpanfile')->insert(['namafile'=>$tf,'kode_unit'=>Auth::user()->kode_unit,'nominal'=>$total]);
            Session::flash('flash_message','Request berhasil disimpan.');
            //return $pdf->stream($tf);
            $pdf->save('storage/'.$tf);
            return redirect('kaskecil');}
        else if (!str_contains($mode,'laporan'))
        {   //$tf = $mode.'.pdf';
            $status = DB::select(DB::raw("select distinct(status) from $mode"));
            $dr_new = 'reimburse'.date('dmYHi').'u'.Auth::user()->kode_unit;
            DB::statement("drop view $mode");
            DB::statement('create view '.$dr_new.' as select * from kaskecil WHERE status = \'' . $status[0]->status . '\'');
            Simpanfile::where('namafile','LIKE',$mode.'%')->update(['namafile' => $dr_new.'.pdf','mode'=>'print','nominal'=>$total]);
            //DB::table('simpanfile')->where('namafile','LIKE',$mode.'%')
            //    ->update(['mode'=>'print','updated_at'=>date("Y-m-d H:i:s"),'nominal'=>$total]);
            Session::flash('flash_message','Request berhasil disimpan.');
            //return $pdf->stream($tf);
            $pdf->save('storage/'.$dr_new.'.pdf');
            return redirect('kaskecil');}
        else {
            $dr = str_replace('laporan','',$mode);
            $tf = str_replace('reimburse', '',$mode);
            DB::table('laporanfile')->insert(['namafile'=>$tf,'data_reimburse'=>$dr,'kode_unit'=>Auth::user()->kode_unit]);
            Cheque::where('data_reimburse','LIKE','%'.$dr.'%')->update(['mode'=>'final']);
            Session::flash('flash_message','Laporan berhasil disimpan');
            Simpanfile::where('namafile',$dr)->update(['mode' => 'cheque']);
            //return $pdf->stream($tf);
            $pdf->save('storage/'.$tf);
            return redirect('cheque');
        }
    }

    public function laporankampdf(Request $request)
    {
        ob_end_clean();
        ob_start();

        $input = $request->all();
        
        if(!empty($input)) {
            $dt1 = $input['tanggal1'];
            $dt2 = $input['tanggal2'];

            $transactions = '';
            
            /*if($input['subkode'] == '0') {
                $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode','!=',null],['subkode','!=',''],['kode_unit',Auth::user()->kode_unit]])->orderBy('subkode')->orderBy('tanggal_trans')->get();

                $totalsk = Kaskecil::select(DB::raw('subkode, SUM(nominal) as total'))
                        ->where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode','!=',null],['kode_unit',Auth::user()->kode_unit]])
                        ->groupBy('subkode')->get();
            }
            else {
                $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode',$input['subkode']],['kode_unit',Auth::user()->kode_unit]])->orderBy('subkode')->orderBy('tanggal_trans')->get();                
            }
*/
            $transactions = ''; $kategori = explode("|",$input['subkode'])[0];
            
            if($kategori == '1') { $sk = explode("|",$input['subkode'])[1];
                if($sk == '0') {
                    $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode','!=',null],['subkode','!=',''],['kode_unit',Auth::user()->kode_unit]])->orderBy('subkode')->orderBy('tanggal_trans')->get();
    
                    $totalsk = Kaskecil::select(DB::raw('subkode as sub, SUM(nominal) as total'))
                            ->where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode','!=',null],['subkode','!=',''],['kode_unit',Auth::user()->kode_unit]])
                            ->groupBy('subkode')->get();
                }
                else if($sk != null){
                    $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['subkode',$sk],['kode_unit',Auth::user()->kode_unit]])->orderBy('subkode')->orderBy('tanggal_trans')->get();
            }}
            else if($kategori == '0') { $coa = explode("|",$input['subkode'])[1];
                if($coa == '0') {
                    $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['kode_unit',Auth::user()->kode_unit]])->orderBy('kode_d_ger')->orderBy('tanggal_trans')->get();
    
                    $totalsk = Kaskecil::select(DB::raw('kode_d_ger as sub, SUM(nominal) as total'))
                            ->where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['kode_unit',Auth::user()->kode_unit]])->groupBy('kode_d_ger')->get();
                }
                else if($coa != null) {
                    $transactions = Kaskecil::where([['tanggal_trans','>=',$dt1],['tanggal_trans','<=',$dt2],['kode_d_ger',$coa],['kode_unit',Auth::user()->kode_unit]])->orderBy('kode_d_ger')->orderBy('tanggal_trans')->get();
            }}

            $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:70%;border-collapse: collapse;width: 100%;}';
            $td .= 'td, th {border: 1px solid; text-align: center;padding: 2px;}';
            $td .= 'tr:nth-child(even) {background-color: #dddddd;} @page {header: page-header;footer: page-footer;}</style>';
            $td .= '<body><htmlpageheader name="page-header"><H4>Rekap Kas Admin '. KodeUnit::where('id',Auth::user()->kode_unit)->value('unit') .' - ('.$dt1.' - '.$dt2.')</H4></htmlpageheader>';

            $td .= '<table align="center"><thead>';

            $td .= '<tr><th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th style="width: 63%;">Deskripsi</th><th>Nominal</th></tr></thead><tbody>';

            $totalnominal = 0;
            foreach ($transactions as $trans) {
            $td .= '<tr>';
            $td .= '<td>' . Carbon::parse($trans->tanggal_trans)->format('d/m/Y') . '</td>';
            $td .= '<td>' . $trans->kode_d_ger . '</td>';
            $td .= '<td>' . $trans->subkode . '</td>';
            $td .= '<td>' . $trans->no_bukti . '</td>';
            $td .= '<td style="text-align: left;">' . $trans->deskripsi . '</td>';
            $td .= '<td style="text-align: right;">' . number_format($trans->nominal,0,'.',',') . '</td>';
            $td .= '</tr>';$totalnominal+= $trans->nominal;
            }
            $td .= '<tr>';
            $td .= '<td colspan="5" style="text-align: right;"><strong>TOTAL</strong></td>';
            $td .= '<td style="text-align: right;">' . number_format($totalnominal,0,'.',',') . '</td></tr>';
            $td .= '<tr><td colspan="6" style="text-align: center;background-color: grey;"></td></tr>';

            if(explode("|",$input['subkode'])[1] == '0'){
                if(explode("|",$input['subkode'])[0] == '1'){
                    $td .= '<tr><td colspan="6" style="text-align: center;"><strong>Jumlah per SUB Kode</strong></td></tr>';
                    $td .= '<tr><td colspan="5" style="text-align: right;"><strong>Sub Kode</strong></td>';
                }
                else {
                    $td .= '<tr><td colspan="6" style="text-align: center;"><strong>Jumlah per COA</strong></td></tr>';
                    $td .= '<tr><td colspan="5" style="text-align: right;"><strong>Chart of Account</strong></td>';   
                }
                $td .= '<td><strong>Nominal</strong></td></tr>';
                for ($i=0; $i < count($totalsk); $i++){
                    $td .= '<tr><td colspan="5" style="text-align: right;">' . $totalsk[$i]['sub'] . '</td>';
                    $td .= '<td style="text-align: right;">' . number_format($totalsk[$i]['total'],0,'.',',') . '</td></tr>';}
                
                $td .= '<tr><td colspan="5" style="text-align: right;"><strong>TOTAL</strong></td>';
                $td .= '<td style="text-align: right;">' . number_format($totalnominal,0,'.',',') . '</td></tr>';
                $td .= '<tr><td colspan="6" style="text-align: center;background-color: grey;"></td></tr>';
            }
            $td .= '</tbody><tfoot></tfoot></table>';

            $td .= '<htmlpagefooter name="page-footer"><p align="right">{PAGENO} / {nb}</p></htmlpagefooter></body>';

            $pdf = PDF::loadHTML($td, ['format' => 'Folio', 'margin_left' => 10]);
        
            $tf = 'RKA('.$dt1.'-'.$dt2.')'.date('His').'.pdf';

            $pdf->save(public_path('/storage/'.$tf));

            return response::json(['name'=>$tf,'filename'=>$tf]);
        }
    }

    private function updateStatus()
    {
        $tf = date('dmyHi').'u'.Auth::user()->kode_unit;
        Kaskecil::where([['status','bu'],['kode_unit',Auth::user()->kode_unit]])->update(['status' => $tf]);
        $temp = 'create view reimburse'.date('dmY').'u'.Auth::user()->kode_unit.' as ';
        $temp .= 'select * from kaskecil WHERE status = \''.$tf.'\'';
        DB::statement($temp);

    }

    /*public function requestreimburse($mode)
    {
        $row = 0;
        if(str_contains($mode,'req'))
        {
            //cek apakah sudah buat request reimburse atau belum pada hari ini
            $q = "SELECT date(created_at) FROM simpanfile WHERE kode_unit = ".Auth::user()->kode_unit;
            $q .= " AND date(created_at) = date(now())" ;
            $tempdate = DB::select(DB::raw($q));
                if(count($tempdate) > 0)
                {
                Session::flash('flash_message', 'Hai, hari ini sudah melakukan request. Coba lagi besok ya...');
                Session::flash('penting', true);
                return redirect('kaskecil');
                }

            //kalau belum lanjut dimari
            $items = Kaskecil::where([['kode_unit',Auth::user()->kode_unit],['status','bu']])
                    ->orderBy('no_bukti')->orderBy('tanggal_trans')->orderBy('id')->get();
            $perawal = $items->first()['tanggal_trans']->format('d-m-y');
            $perakhir = $items->last()['tanggal_trans']->format('d-m-y');
            
            $totalcoa = Kaskecil::select(DB::raw('kode_d_ger, SUM(nominal) as total'))
                ->where([['status','bu'],['kode_unit',Auth::user()->kode_unit]])->groupBy('kode_d_ger')->get();

            $totalsk = Kaskecil::select(DB::raw('subkode, SUM(nominal) as total'))
                ->where([['status','bu'],['kode_unit',Auth::user()->kode_unit]])->groupBy('subkode')->get();
            
            //$jl = 'Rekap Reimburse';
        }
        else if(str_contains($mode,'reimburse'))
        {
            $items = DB::select(DB::raw("select * from $mode ORDER BY  no_bukti, tanggal_trans, id"));
            $totalcoa = DB::select(DB::raw("select kode_d_ger, SUM(nominal) as total from $mode GROUP BY kode_d_ger"));
            $totalsk = DB::select(DB::raw('select subkode, SUM(nominal) as total from '.  $mode . ' GROUP BY subkode'));
            $tempper = DB::select(DB::raw("select MIN(tanggal_trans) as perawal, MAX(tanggal_trans) as perakhir from $mode"));
            $perawal = Carbon::parse($tempper[0]->perawal)->format('d-m-y');
            $perakhir = Carbon::parse($tempper[0]->perakhir)->format('d-m-y');
            $rev = ' - Revisi';//$jl = 'Rekap Reimburse';
        }
        else {
            $tempd = DB::select('select * from datacheck where id = ?',[$mode]);
            foreach ($tempd as $t) {
                $mode = substr($t->data_reimburse,0,-4);
                $items = DB::select(DB::raw("select * from $mode ORDER BY kode_d_ger, tanggal_trans, no_bukti, id"));
                $totalcoa = DB::select(DB::raw("select kode_d_ger, SUM(nominal) as total from $mode GROUP BY kode_d_ger"));
                $totalsk = DB::select(DB::raw('select subkode, SUM(nominal) as total from '.  $mode . ' GROUP BY subkode'));
                $tempper = DB::select(DB::raw("select MIN(tanggal_trans) as perawal, MAX(tanggal_trans) as perakhir from $mode"));
                $perawal = Carbon::parse($tempper[0]->perawal)->format('d-m-y');
                $perakhir = Carbon::parse($tempper[0]->perakhir)->format('d-m-y');
                $check = 'Cek Danamon: '.$t->no_check.' ('.$t->data_reimburse.')</td>';
                $check .= '<td style="text-align:left;">'. number_format($t->nominal,0,'','.') .',00</td></tr>';
            }
            $mode = 'laporan'.$t->data_reimburse;$rev = '';//$jl = 'Rekap Reimburse';
        }

        if(!str_contains($mode,'laporan'))
        {
            $bf = Cheque::where([['kode_unit',Auth::user()->kode_unit],['mode','final']])->orderBy('tanggal_cair','desc')->get();
            $bf = $bf->toArray();
            $pla = DB::select(DB::raw("select plafon from kodeunit where id = :id"),array('id'=>Auth::user()->kode_unit));
            $sak = $pla[0]->plafon - $bf[0]['nominal'];

            $tempt = '<table align="center">';
            $tempt .= '<tr><th style="width:80%;">Deskripsi</th><th>Debit</th><th>Kredit</th></tr>';
            $tempt .= '<tr><td style="text-align:right;">Saldo Akhir</td><td></td>';
            $tempt .= '<td style="text-align:right;">'.number_format($sak,0,'','.').',00</td></tr>';
            $tempt .= '<tr><td style="text-align:right;">Cek Danamon: '.$bf[0]['no_check'].' ('.$bf[0]['data_reimburse'].')</td>';
            $tempt .= '<td style="text-align:left;">'.number_format($bf[0]['nominal'],0,'','.').',00</td><td></td></tr>';
            //$tempt .= '<tr><td>Total</td>';
            //$tempt .= '<td style="text-align:center;">'.number_format($pla[0]->plafon,0,'','.').',00</td></tr></table><br><br>';
            $tempt .= '</table><br>';
        }
        //proses generate halaman html untuk diconvert ke pdf nantinya
        $total = 0;
        PDF::setPaper('A4');
        PDF::setOptions(['dpi' => 300, 'defaultFont' => 'sans-serif']);

        $td = '<style>html {margin-top:10px;} table {font-family: arial, sans-serif;font-size:65%;border-collapse: collapse;width: 100%;}';
        $td .= 'td, th {border: 1px solid; text-align: center;padding: 2px;}';
        $td .= 'tr:nth-child(even) {background-color: #dddddd;} </style>';
        $td .= '<H4 align="center">Laporan Rekap Reimburse - ';
        $td .= DB::table('kodeunit')->where('id',Auth::user()->kode_unit)->value('unit');

        if (str_contains($mode,'reimburse')){$td .= $rev;}

        $td .= ' ('.$perawal.' - '.$perakhir.')</H4>';

        (!str_contains($mode,'laporan')) ? $td .= $tempt : '';

        $td .= '<table align="center">';

        //Start table TU
        if (Auth::user()->kode_unit != 0) {
            $td .= '<tr><th>Tanggal</th><th style="width:55px;">Kode<br>D-Ger</th><th style="width:15px;">Sub<br>Kode</th><th>No BPU</th><th style="width:415px;">Deskripsi</th><th>Nominal</th></tr>';
            if(str_contains($mode,'reimburse')) {
            foreach ($items as $item) {
                $td .= '<tr>';
                $td .= '<td>'.Carbon::parse($item->tanggal_trans)->format('d-m-Y').'</td>';
                $td .= '<td>'.$item->kode_d_ger.'</td>';
                $td .= '<td>'.$item->subkode.'</td>';
                $td .= '<td>'.$item->no_bukti.'</td>';
                $td .= '<td style="text-align:left;">'.$item->deskripsi.'</td>';
                $td .= '<td style="text-align: right;">'
                    .number_format($item->nominal,0,'','.').',00</td>';
                $td .= '</tr>';$total += $item->nominal;
            }}
            else if (str_contains($mode,'req')) {
            foreach ($items as $item) {
                $td .= '<tr>';
                $td .= '<td>'.$item->tanggal_trans->format('d-m-Y').'</td>';
                $td .= '<td>'.$item->kode_d_ger.'</td>';
                $td .= '<td>'.$item->subkode.'</td>';
                $td .= '<td>'.$item->no_bukti.'</td>';
                $td .= '<td style="text-align:left;">'.$item->deskripsi.'</td>';
                $td .= '<td style="text-align: right;">'
                    .number_format($item->nominal,0,'','.').',00</td>';
                $td .= '</tr>';$total += $item->nominal;
            }}
            $td .= '<tr><td colspan="5" style="text-align: right;">Total</td>';
            $td .= '<td style="text-align: right;">'.number_format($total,0,'','.').',00</td></tr>';

            if(str_contains($mode,'laporan'))
                { $td .= '<tr><td colspan="5" style="text-align: right;">'.$check ;}

            $td .= '<tr><td colspan="6"></td></tr><tr><td colspan="4">Total Per Sub Kode</td><td colspan="2">Total Per COA</td></tr>';
            foreach ($totalcoa as $tc) {
                $td .= '<tr bgcolor="white"><td colspan="5" style="text-align: right;">'.$tc->kode_d_ger.'</td>';
                $td .= '<td style="text-align: right;">';
                $td .= number_format($tc->total,0,'','.').',00</td></tr>';
            }

            //Start Total Per COA dan Total Per SUBKODE
            (count($totalcoa) > count($totalsk)) ? $totalrow=count($totalcoa) : $totalrow=count($totalsk);
            $totalsk[0]->subkode = 'Kas Kecil';
            
            for ($i=0; $i < $totalrow; $i++) { 
                $td .= '<tr>';
                if(count($totalsk)>$i)
                {
                    $td .= '<td colspan="2" style="text-align:right;">'.$totalsk[$i]->subkode.'</td>';
                    $td .= '<td colspan="2" style="text-align:right">'.number_format($totalsk[$i]->total,0,'','.').',00</td>';
                }
                else{$td .= '<td colspan="4"></td>';}
                if(count($totalcoa)>$i)
                {
                    $td .= '<td style="text-align:right;">'.$totalcoa[$i]->kode_d_ger.'</td>';
                    $td .= '<td style="text-align:right;">'.number_format($totalcoa[$i]->total,0,'','.').',00</td>';
                }
                else {$td .= '<td colspan="2"></td>';}
                $td .= '</tr>';
            }
            //END Total PER COA dan Total PER SUBKODE
        }
        //End table TU

        //Start table Yayasan
        else if (Auth::user()->kode_unit == 0) {
        $td .= '<tr><th>Tanggal</th><th style="width:55px;">Kode<br>D-Ger</th><th>No BPU</th><th style="width:415px;">Deskripsi</th><th>Nominal</th></tr>';
        if (str_contains($mode,'reimburse')) {
            foreach ($items as $item) {
                $td .= '<tr>';
                $td .= '<td>'.Carbon::parse($item->tanggal_trans)->format('d-m-Y').'</td>';
                $td .= '<td>'.$item->kode_d_ger.'</td>';
                $td .= '<td>'.$item->no_bukti.'</td>';
                $td .= '<td style="text-align:left;">'.$item->deskripsi.'</td>';
                $td .= '<td style="text-align: right;">'
                    .number_format($item->nominal,0,'','.').',00</td>';
                $td .= '</tr>';$total += $item->nominal;
            }}
        else if (str_contains($mode,'req')) {
            foreach ($items as $item) {
                $td .= '<tr>';
                $td .= '<td>'.$item->tanggal_trans->format('d-m-Y').'</td>';
                $td .= '<td>'.$item->kode_d_ger.'</td>';
                $td .= '<td>'.$item->no_bukti.'</td>';
                $td .= '<td style="text-align:left;">'.$item->deskripsi.'</td>';
                $td .= '<td style="text-align: right;">'
                    .number_format($item->nominal,0,'','.').',00</td>';
                $td .= '</tr>';$total += $item->nominal;
            }}
            $td .= '<tr><td colspan="4" style="text-align: right;">Total</td>';
            $td .= '<td style="text-align: right;">'.number_format($total,0,'','.').',00</td></tr>';

            if(str_contains($mode,'laporan'))
                { $td .= '<tr><td colspan="4" style="text-align: right;">'.$check;}

            $td .= '<tr><td colspan="5"></td></tr><tr><td colspan="5">Total Per COA</td></tr>';
            foreach ($totalcoa as $tc) {
                $td .= '<tr bgcolor="white"><td colspan="4" style="text-align: right;">'.$tc->kode_d_ger.'</td>';
                $td .= '<td style="text-align: right;">';
                $td .= number_format($tc->total,0,'','.').',00</td></tr>';
            }
        }
        //End table Yayasan
        
        $td .= '</table><br><br><table style="border: 0px">';
        $td .= '<tr><td colspan="2" style="text-align: right"><b>Bandung, '.date('j F Y').'</b></td></tr>';
        $td .= '<tr style="background-color: white;"><td>';
        (Auth::user()->kode_unit == 0) ? $td .= 'Bendahara' : $td .= 'Kepala Sekolah';
        $td .= '<br><br><br><br><br><br>';
        $td .= DB::table('kodeunit')->where('id',Auth::user()->kode_unit)->value('kepsek').'</td><td>';
        (Auth::user()->kode_unit == 0) ? $td .= 'Keuangan' : $td .= 'TU Keuangan';
        $td .= '<br><br><br><br><br><br>'.Auth::user()->name.'</td></tr></table>';

        $pdf = PDF::loadHTML($td)->setWarnings(false);
        if(str_contains($mode,"req"))
        {
            $tf = 'reimburse'.date('dmY').'u'.Auth::user()->kode_unit.'.pdf';
            $this->updateStatus();
            DB::table('simpanfile')->insert(['namafile'=>$tf,'kode_unit'=>Auth::user()->kode_unit,'nominal'=>$total]);
            Session::flash('flash_message','Request berhasil disimpan.');
            $pdf->save('storage/'.$tf);return redirect('kaskecil');}
        else if (!str_contains($mode,'laporan'))
        {   $tf = $mode.'.pdf';
            DB::table('simpanfile')->where('namafile','LIKE',$mode.'%')
                ->update(['mode'=>'print','updated_at'=>date("Y-m-d H:i:s"),'nominal'=>$total]);
            Session::flash('flash_message','Request berhasil disimpan.');
            //return $pdf->stream();}
            $pdf->save('storage/'.$tf);return redirect('datareim');}
        else {
            $dr = str_replace('laporan','',$mode);
            $tf = str_replace('reimburse', '',$mode);
            DB::table('laporanfile')->insert(['namafile'=>$tf,'data_reimburse'=>$dr,'kode_unit'=>Auth::user()->kode_unit]);
            Cheque::where('data_reimburse','LIKE','%'.$dr.'%')->update(['mode'=>'final']);
            Session::flash('flash_message','Laporan berhasil disimpan');
            $pdf->save('storage/'.$tf);return redirect('cheque');
        }
    }*/
}
