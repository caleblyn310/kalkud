<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DaftarBarang;
use App\Pembelian;
use App\PembelianDetail;
use App\PeriodeKantin;
use Validator;
use Session;
use Response;
use DB;
use Carbon;
use PDF;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $daftar_pembelian = Pembelian::orderBy('status','asc')->orderBy('dot','asc')->orderBy('invoices_no')->paginate(20);
        return view('pembelian.index',compact('daftar_pembelian'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fs = 'new';
        return view('pembelian.create',compact('fs'));
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
        //dd($input);

        $validator = Validator::make($input, [
            'invoices_no' => 'required',
            'dot' => 'required|before_or_equal:'.date('Y-m-d')],[
                'dot.before_or_equal' => 'Pilih tanggal hari ini atau sebelum hari ini'
        ]);

        if ($validator->fails())
        {   
            ($validator->errors()->has('nominals')) ? Session::flash('flash_message',$validator->errors()->first('nominals')) : Session::flash('flash_message',$validator->errors()->first('invoices_no'));
            //dd($validator->errors()->first('invoices_no'));
            return redirect('pembelian/create')->withInput()->withErrors($validator); 
            //return response()->json(['errors'=>$validator->errors()->all()]);
            //return redirect('testbpb')->withInput()->withErrors($validator); 
        }
        else
        {   
            $inv = new Pembelian();
            $inv->invoices_no = $input['invoices_no'];
            $inv->supplier = $input['supplier'];
            $inv->dot = $input['dot'];
            $inv->nominal = $input['nominals'];
            $inv->save();

            $iddet = explode("|", $input['iddet'], -1);
            foreach ($iddet as $i) {
                $pembdet = PembelianDetail::findOrFail($i);
                $pembdet->id_pembelian = $inv->id;
                $pembdet->save();
            }

            if($request->submitbutton == 'savelock') {
                $this->lockpembelian($inv->id);
            }

            Session::flash('flash_message','Data Pembelian berhasil disimpan.');
            return redirect('pembelian');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $pembeliandetail_list = PembelianDetail::where('id_pembelian',$id)->get();
        $total = $pembeliandetail_list->sum('hrg_tot') - $pembeliandetail_list->sum('diskon');
        $total = floatval($total);
        $fs = 'edit';
        return view('pembelian.edit',compact('pembelian','pembeliandetail_list','total','fs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        //dd($input);

        $validator = Validator::make($input, [
            'invoices_no' => 'required',
            'dot' => 'required|before_or_equal:'.date('Y-m-d')],[
                'dot.before_or_equal' => 'Pilih tanggal hari ini atau sebelum hari ini'
        ]);

        if ($validator->fails())
        {   
            ($validator->errors()->has('nominals')) ? Session::flash('flash_message',$validator->errors()->first('nominals')) : Session::flash('flash_message',$validator->errors()->first('invoices_no'));
            //dd($validator->errors()->first('invoices_no'));
            return redirect('pembelian/'.$id.'/edit')->withInput()->withErrors($validator); 
            //return response()->json(['errors'=>$validator->errors()->all()]);
            //return redirect('testbpb')->withInput()->withErrors($validator); 
        }
        else
        {   
            $inv = Pembelian::findOrFail($id);
            $inv->supplier = $input['supplier'];
            $inv->dot = $input['dot'];
            $inv->nominal = $input['nominals'];
            $inv->save();

            $iddet = explode("|", $input['iddet'], -1);
            foreach ($iddet as $i) {
                PembelianDetail::where('id',$i)->update(['id_pembelian' => $inv->id]);
            }

            Session::flash('flash_message','Data Pembelian berhasil di-update');
            return redirect('pembelian');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $temp = Pembelian::findOrFail($id);
        $t = PembelianDetail::where('id_pembelian',$id);
        $t->delete();$temp->delete();
        return redirect('pembelian');
    }

    
    public function getinvno(Request $request)
    {
        $temp = $request->all();
        $invno = Pembelian::whereMonth('dot',$temp['mon'])->whereYear('dot', $temp['year'])->get();$t = count($invno);$t++;
        //if ($t < 10) {$invno = '00' . $t;}
        //elseif ($t < 100) {$invno = '0' . $t;}
        return Response::json(['invno' => $t]);
    }

    public function lockpembelian($id)
    {
        $s = Pembelian::where('id',$id)->value('status');
        if($s == 's'){
            Pembelian::where('id',$id)->update(['status'=>'l']);
            $pembdets = PembelianDetail::where('id_pembelian',$id)->get();
    
            foreach ($pembdets as $pembdet) {
                $dbar = DaftarBarang::findOrFail($pembdet->id_barang);
                $dbar->hpp = (($dbar->hpp * $dbar->stok) + ($pembdet->hrg_tot - $pembdet->diskon)) / ($dbar->stok + ($pembdet->qty1 * $pembdet->qty2));
                $dbar->stok += ($pembdet->qty1 * $pembdet->qty2);
                $dbar->save();
            }
        }
        return redirect('pembelian');
    }

    public function laporan(Request $request)
    {
        $input = $request->all();
        $per = DB::table('periode_kantin')->select(DB::raw("id, periode"))->where('status','L')->pluck('periode','id');

        if(!empty($input)) {
            $periode = $input['periode'];
            $kode_brg = $input['kode_brg'];

            if ($input['kategori'] == 0) {
                $pd = DB::select(DB::raw("select a.dot, b.id_pembelian, b.id_barang, (b.qty1 * b.qty2) as qty, b.hrg_sat, (b.hrg_tot - b.diskon) as hrg_total from pembelian a, pembelian_detail b where a.id = b.id_pembelian and a.id_periode = $periode order by a.dot, b.id_pembelian, b.id_barang"));
                //dd($pd);
                $kode_brg = 0;
                return view('pembelian.laporan',compact('pd','per','kode_brg','periode'));
            }
            else {
                $pd = DB::select(DB::raw("select a.dot, b.id_pembelian, b.id_barang, (b.qty1 * b.qty2) as qty, b.hrg_sat, (b.hrg_tot - b.diskon) as hrg_total from pembelian a, pembelian_detail b where a.id = b.id_pembelian and a.id_periode = $periode and b.id_barang = '$kode_brg' order by a.dot, b.id_barang"));
                //dd($pd[0]->id_pembelian);
                return view('pembelian.laporan',compact('pd','per','kode_brg','periode'));
            }
        }
        else {
            //dd($per);
            return view('pembelian.laporan',compact('per'));
        }
    }

    public function pdfReport(Request $request)
    {
        ob_end_clean();
        ob_start();
        $input = $request->all();

        if(!empty($input)) {
            $kode_brg = $input['kode_brg'];
            $periode = $input['periode'];

            if ($kode_brg == 0) {
                $pd = DB::select(DB::raw("select a.dot, b.id_pembelian, b.id_barang, (b.qty1 * b.qty2) as qty, b.hrg_sat, (b.hrg_tot-b.diskon) as hrg_total from pembelian a, pembelian_detail b where a.id = b.id_pembelian and a.id_periode = $periode order by a.dot, b.id_pembelian, b.id_barang"));
            }
            else {
                $pd = DB::select(DB::raw("select a.dot, b.id_pembelian, b.id_barang, (b.qty1 * b.qty2) as qty, b.hrg_sat, (b.hrg_tot-b.diskon) as hrg_total from pembelian a, pembelian_detail b where a.id = b.id_pembelian and a.id_periode = $periode and b.id_barang = '$kode_brg' order by a.dot, b.id_barang"));
            }

            $td = '<style>html {margin-top:20px;} table {font-family: arial, sans-serif;font-size:70%;border-collapse: collapse;width: 100%;}';
            $td .= 'td, th {border: 1px solid; text-align: center;padding: 2px;}';
            $td .= 'tr:nth-child(even) {background-color: #dddddd;} @page {header: page-header;footer: page-footer;}</style>';
            $td .= '<body><htmlpageheader name="page-header"><H4>Laporan Pembelian Kantin (';
            $td .= PeriodeKantin::where('id',$periode)->value('periode') .')</h4></htmlpageheader>';

            $td .= "<table align='center'><colgroup><col><col><col></colgroup>";
            $td .= "<thead><tr><th style='width: 10%;'>Tanggal</th><th style='width:60%;'>Nama<br>Barang</th><th>Qty</th><th>Harga<br>Satuan</th><th>Total</th><th>Jumlah</th></tr></thead>";
            $td .= "<tbody>";$total = 0; $jumlah = 0;

            for ($i=0; $i < count($pd); $i++)
            {
                $td .= "<tr>";
                $td .= "<td>".Carbon::parse($pd[$i]->dot)->format('d/m/Y')."</td>";
                $td .= "<td style='text-align: left;'>" . DaftarBarang::where('id',$pd[$i]->id_barang)->value('nama_barang') . "</td>";
                $td .= "<td>" . $pd[$i]->qty . "</td>";
                $td .= "<td style='text-align: right;'>" . number_format($pd[$i]->hrg_sat,0,'.',',') . "</td>";
                $td .= "<td style='text-align: right;'>" . number_format($pd[$i]->hrg_total,0,'.',',') . "</td>";
                $jumlah += $pd[$i]->hrg_total; $total += $pd[$i]->hrg_total;
                if ($i == (count($pd)-1) || $pd[$i]->id_pembelian != $pd[$i+1]->id_pembelian ) 
                    {$td .= "<td style='text-align: right; background-color: lightgrey;'>" . number_format($total,0,'.',',') . "</td>"; $total = 0;}
                else $td .= "<td></td>";
            $td .= "</tr>";
            }
            
            $td .= "<tr><td colspan='5' style='text-align: right;'><strong>TOTAL</strong></td>";
            $td .= "<td style='text-align: right;'>".number_format($jumlah,0,'.',',')."</td></tr>";
            $td .= "<tr><td colspan='6' style='text-align: center;background-color: grey;'></td></tr>";
            $td .= "</tbody><tfoot></tfoot></table>";

            $td .= '<htmlpagefooter name="page-footer"><p align="right">{PAGENO} / {nb}</p></htmlpagefooter></body>';

        $pdf = PDF::loadHTML($td, ['format' => 'Folio', 'margin_left' => 10]);
        
        $tf = "Laporan Pembelian Kantin (". PeriodeKantin::where('id',$periode)->value('periode') .").pdf";
        
        //Session::flash('flash_message','Request berhasil disimpan.');
        //dd(public_path('/storage/boa.pdf'));
        $pdf->save(public_path('/storage/'.$tf));
        
        //return $pdf->stream($tf);
        //return response()->download(public_path($tf),$tf,['Content-Type: application/pdf']);
        return response::json(['name'=>$tf,'filename'=>$tf]);
        }
    }
}
