{!! Form::hidden('kode_unit',Auth::user()->kode_unit) !!}

@if ($errors->any())
    <div class = "form-group row {{ $errors->has('tanggal_trans') ? 'has-error' : 'has-success' }}">
@else
<div class="form-group row">
@endif
    {!! Form::label('tanggal_trans','Tanggal BPU',['class'=>'col-lg-3 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::date('tanggal_trans',!empty($kaskecil) ? $kaskecil->tanggal_trans->format('Y-m-d') : date('Y-m-d')
        ,['class'=>'form-control form-control-sm','required'=>'']) !!}&nbsp;
    @if($errors->has('tanggal_trans'))<span >{{ $errors->first('tanggal_trans') }}</span>@endif</div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('kode_d_ger') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('kode_d_ger','Kode D-Ger',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::text('q', '', ['class'=>'form-control form-control-sm','id' =>  'q', 'placeholder' =>  'Cari kode d-ger',
        'autofocus'=>'']) !!}
        {!! Form::text('kode_d_ger',null,['class'=>'form-control form-control-sm','placeholder'=>'KODE D-Ger',
        'required'=>'','maxlength'=>'10','pattern'=>'\d{3}(.)\d{2}(.)\d{3}','readonly'=>'']) !!}
    
        @if ($errors->has('kode_d_ger'))
            <span class="help-block">{{ $errors->first('kode_d_ger') }}</span>@endif</div>
</div>

<div class="form-group row">
    {!! Form::label('subkode','Sub Kode',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::text('subkode',null,['class'=>'form-control form-control-sm','placeholder'=>'Sub KODE','maxlength'=>'2']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('no_bukti','No BPU',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8">
    {!! Form::text('no_bukti',null,['class'=>'form-control form-control-sm','placeholder'=>'Awali dengan KA atau KK','required'=>'',
    'maxlength'=>'10','pattern'=>'K(A|K|B)\d{5,}']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('deskripsi','Deskripsi',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::text('deskripsi',null,['class'=>'form-control form-control-sm','placeholder'=>'Deskripsi','required'=>'']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('nominal','Nominal',['class'=>'col-sm-3 form-control-label']) !!}

    <div class="col-lg-8">
        {!! Form::text('nominal',null,['class'=>'form-control form-control-sm','placeholder'=>'Nominal', 'maxlength'=>'8',
        'required'=>'']) !!}&nbsp;<span id="errmsg"></span>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-8 col-md-offset-4">
        <button type="submit" class="btn btn-sm btn-success">SAVE</button>
        <a href="{{ asset('kaskecil') }}" class="btn btn-sm btn-danger">Cancel</a>
    </div>
</div>