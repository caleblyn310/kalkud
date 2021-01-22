@if ($errors->any())
<div class = "form-group row {{ $errors->has('Tanggal') ? 'has-error' : 'has-success' }}">
@else
<div class="form-group row">
@endif 
    {!! Form::label('Tanggal','Tanggal',['class'=>'col-3 col-md-3 col-lg-3 col-sm-12 form-control-label']) !!}
    <div class="col-lg-9 col-md-9 col-sm-12">
        {!! Form::date('Tanggal',!empty($jurnaladmin) ? $jurnaladmin->Tanggal->format('Y-m-d') : date('Y-m-d')
        ,['class'=>'form-control form-control-sm','required'=>'']) !!}&nbsp;
    @if($errors->has('Tanggal'))<span >{{ $errors->first('Tanggal') }}</span>@endif</div>
</div>

<div class="row"> 
    {!! Form::label('No_bukti','No BPU',['class'=>'col-3 form-control-label float-right']) !!}
    <div class="col-lg-9 col-md-9 col-sm-9">
    {!! Form::text('No_bukti',null,['class'=>'form-control form-control-sm','placeholder'=>'Slip no','required'=>'']) !!}&nbsp;
    @if($errors->has('No_bukti'))<span >{{ $errors->first('No_bukti') }}</span>@endif</div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('No_account') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('No_account','Kode D-Ger',['class'=>'col-3 form-control-label']) !!}
    <div class="col-6">
        {!! Form::text('qq', '', ['class'=>'form-control form-control-sm','id' =>  'qq', 'placeholder' =>  'Cari kode d-ger', 'autofocus'=>'', 'maxlength' => '10']) !!}</div>
        <div class="col-3"> 
        {!! Form::text('No_account',null,['class'=>'form-control form-control-sm','placeholder'=>'KODE D-Ger',
        'required'=>'','readonly'=>'', 'maxlength'=>'10','pattern'=>'\d{3}(.)\d{2}(.)\d{3}']) !!}
        @if ($errors->has('No_account'))
            <span class="help-block">{{ $errors->first('No_account') }}</span>@endif</div>
</div>

<div class="form-group row">
    {!! Form::label('Uraian','Deskripsi',['class'=>'col-3 form-control-label']) !!}
    <div class="col-9">
        {!! Form::text('Uraian',null,['class'=>'form-control form-control-sm','placeholder'=>'Deskripsi','required'=>'','maxlength'=>'255']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('Debet','Debet',['class'=>'col-3 form-control-label']) !!}

    <div class="col-4">
        {!! Form::text('Debet',!empty($jurnaladmin) ? substr($jurnaladmin->Debet,0,-3) : 0, ['class'=>'form-control form-control-sm','placeholder'=>'Debet','required'=>'','maxlength'=>'15']) !!}</div>
    <div class="col-4">    
        <span id="errmsg"></span>
    </div>
</div>

<div class="form-group row">
    {!! Form::label('Kredit','Kredit',['class'=>'col-3 form-control-label']) !!}

    <div class="col-4">
        {!! Form::text('Kredit',!empty($jurnaladmin) ? substr($jurnaladmin->Kredit,0,-3) : 0, ['class'=>'form-control form-control-sm','placeholder'=>'Kredit','maxlength'=>'15','required'=>'']) !!}</div>
    <div class="col-4">
        <span id="errmsg2"></span>
    </div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('kontra_acc') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('Kontra_acc','Kontra Acc',['class'=>'col-3 form-control-label']) !!}
    <div class="col-6">
        {!! Form::text('q2', '', ['class'=>'form-control form-control-sm','id' => 'q2', 'placeholder' =>  'Cari kode d-ger',
        'autofocus'=>'']) !!}</div>
    <div class="col-3"> 
        {!! Form::text('Kontra_acc',null,['class'=>'form-control form-control-sm','placeholder'=>'KODE D-Ger',
        'required'=>'','maxlength'=>'10','readonly'=>'', 'pattern'=>'\d{3}(.)\d{2}(.)\d{3}']) !!}
        @if ($errors->has('kontra_acc'))
            <span class="help-block">{{ $errors->first('kontra_acc') }}</span>@endif</div>
</div>

<div class="form-group row">
    <div class="col-md-8 col-md-offset-4">
        <button type="submit" class="btn btn-sm btn-success">SAVE</button>
        <a href="{{ asset('ja/edit') }}" class="btn btn-sm btn-success" id="btnCancel">Cancel</a>
    </div>
</div>

@section('scripts')
<script type="text/javascript">
    var lochref = location.href;
    if ($('#title').html().includes('Edit')) {
        //$('a#btnCancel').attr('href',lochref.replace(/(\/)\d+/,""));
    }
</script>
@endsection