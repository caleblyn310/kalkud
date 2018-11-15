<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\InvoicesDetail;

class InvoicesDetailController extends Controller
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

        $invoicesdetail = new InvoicesDetail();
            $invoicesdetail->invoices_no = $input['invoices_no'];
            $invoicesdetail->description = $input['description'];
            $invoicesdetail->kode_d_ger = $input['kode_d_ger'];
            $invoicesdetail->nominal = $input['nominal'];
            $invoicesdetail->save();

            return response()->json($invoicesdetail);
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

        $invoicesdetail = InvoicesDetail::findOrFail($id);
            $invoicesdetail->description = $input['description'];
            $invoicesdetail->nominal = $input['nominal'];
            $invoicesdetail->kode_d_ger = $input['kode_d_ger'];
            $invoicesdetail->save();

            return response()->json($invoicesdetail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoicesdetail = InvoicesDetail::findOrFail($id);
        $invoicesdetail->delete();

        return response()->json($invoicesdetail);
    }
}
