<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PembelianDetail;

class PembelianDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $pembeliandetail = new PembelianDetail();
        $pembeliandetail->id_barang = $input['id_barang'];
        $pembeliandetail->qty1 = $input['qty1'];
        $pembeliandetail->qty2 = $input['qty2'];
        $pembeliandetail->hrg_tot = $input['hrg_tot'];
        $pembeliandetail->hrg_sat = $input['hrg_sat'];
        $pembeliandetail->diskon = $input['diskon'];
        $pembeliandetail->save();

        return response()->json($pembeliandetail);
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
        $input = $request->all();

        $pembeliandetail = PembelianDetail::findOrFail($id);
        $pembeliandetail->id_barang = $input['id_barang'];
        $pembeliandetail->qty1 = $input['qty1'];
        $pembeliandetail->qty2 = $input['qty2'];
        $pembeliandetail->hrg_tot = $input['hrg_tot'];
        $pembeliandetail->hrg_sat = $input['hrg_sat'];
        $pembeliandetail->diskon = $input['diskon'];
        $pembeliandetail->save();

        return response()->json($pembeliandetail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pembeliandetail = PembelianDetail::findOrFail($id);
        $pembeliandetail->delete();

        return response()->json($pembeliandetail);
    }
}
