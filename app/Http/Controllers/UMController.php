<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UangMuka;

class UMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('uangmuka.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('uangmuka.create');
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
            'no_bukti' => 'required',
            'dot' => 'required|before_or_equal:'.date('Y-m-d')],[
                'no_bukti.required' => 'Tolong input no bukti ya...',
                'dot.before_or_equal' => 'Tanggal harus sebelum atau hari ini'
        ]);

        if ($validator->fails())
        { return redirect('uangmuka/create')->withInput()->withErrors($validator); }

        UangMuka::create($input);
        Session::flash('flash_message', 'Data berhasil disimpan.');
        return redirect('uangmuka');
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
        //
    }
}
