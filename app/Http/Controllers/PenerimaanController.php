<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Penerimaan;
use Validator;
use Session;

class PenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $daftar_penerimaan = Penerimaan::orderBy('dot','desc')->paginate(20);
        return view('penerimaan.index',compact('daftar_penerimaan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'dot' => 'required|before_or_equal:'.date('Y-m-d')],[
                'dot.before_or_equal' => 'Pilih tanggal hari ini atau sebelum hari ini'
        ]);

        if ($validator->fails())
        { return redirect('penerimaan')->withInput()->withErrors($validator); }

        $pembeliandetail = new Penerimaan();
        $pembeliandetail->dot = $input['dot'];
        $pembeliandetail->nominal = $input['nominal'];
        $pembeliandetail->save();

        return redirect('penerimaan');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        $penerimaan->delete();

        return redirect('penerimaan');
    }
}
