@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 25px">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header"><h3>Edit Kas Kecil</h3></div>
                <div class="card-body">
                {!! Form::model($kaskecil,['method'=>'PATCH', 'action'=>['DatareimController@update',$kaskecil->id]]) !!}
                    {!! Form::hidden('nv',$namafile) !!}

                @if ($errors->any())
                    <div class = "form-group row {{ $errors->has('tanggal_trans') ? 'has-error' : 'has-success' }}">
                @else
                    <div class="form-group row">
                @endif
                    {!! Form::label('tanggal_trans','Tanggal BPU',['class'=>'col-md-3 form-control-label']) !!}
                    <div class="col-md-4">
                        {!! Form::date('tanggal_trans',
                        !empty($kaskecil) ? Carbon\Carbon::parse($kaskecil->tanggal_trans) : null,
                        ['class'=>'form-control','required'=>'']) !!}
                    </div>
                    @if($errors->has('tanggal_trans'))
                        <span class="help-block">{{ $errors->first('tanggal_trans') }}</span>@endif
                </div>

                    <div class="form-group row">
                    {!! Form::label('kode_d_ger','Kode D-Ger',['class'=>'col-md-3 form-control-label']) !!}

                    <div class="col-md-4">
                        {!! Form::text('q', '', ['class'=>'form-control','id' =>  'q', 'placeholder' =>  'Cari kode d-ger',
                        'autofocus'=>'']) !!}
                        {!! Form::text('kode_d_ger',null,['class'=>'form-control','placeholder'=>'KODE D-Ger',
                        'required'=>'','maxlength'=>'10','readonly'=>'']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('subkode','Sub Kode',['class'=>'col-md-3 form-control-label']) !!}

                    <div class="col-md-4">
                        {!! Form::text('subkode',null,['class'=>'form-control','placeholder'=>'Sub KODE','maxlength'=>'2']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('no_bukti','No BPU',['class'=>'col-md-3 form-control-label']) !!}
                    <div class="col-md-4">
                        {!! Form::text('no_bukti',null,['class'=>'form-control','placeholder'=>'Awali dengan KA atau KK','required'=>'',
                        'maxlength'=>'10','pattern'=>'K(A|K|B)\d{5,}']) !!}
                        @if ($errors->has('no_bukti'))
                            <span class="help-block">{{ $errors->first('no_bukti') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('deskripsi','Deskripsi',['class'=>'col-md-3 form-control-label']) !!}

                    <div class="col-md-4">
                        {!! Form::text('deskripsi',null,['class'=>'form-control','placeholder'=>'Deskripsi','required'=>'']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('nominal','Nominal',['class'=>'col-md-3 form-control-label']) !!}

                    <div class="col-md-4">
                        {!! Form::text('nominal',null,['class'=>'form-control','placeholder'=>'Nominal',
                        'required'=>'']) !!}&nbsp;<span id="errmsg"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">SAVE</button>
                        <a href="{{ asset('datareim/'.$namafile.'/edit') }}" class="btn btn-primary">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection