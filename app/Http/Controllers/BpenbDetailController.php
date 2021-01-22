<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BpenbDetail;

class BpenbDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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

        $bpenbdetail = new BpenbDetail();
            $bpenbdetail->invoices_no = $input['invoices_no'];
            $bpenbdetail->description = $input['description'];
            $bpenbdetail->kode_d_ger = $input['kode_d_ger'];
            $bpenbdetail->nominal = $input['nominal'];
            $bpenbdetail->save();

            return response()->json($bpenbdetail);
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

        $bpenbdetail = BpenbDetail::findOrFail($id);
            $bpenbdetail->description = $input['description'];
            $bpenbdetail->nominal = $input['nominal'];
            $bpenbdetail->kode_d_ger = $input['kode_d_ger'];
            $bpenbdetail->save();

            return response()->json($bpenbdetail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bpenbdetail = BpenbDetail::findOrFail($id);
        $bpenbdetail->delete();

        return response()->json($bpenbdetail);
    }
}
