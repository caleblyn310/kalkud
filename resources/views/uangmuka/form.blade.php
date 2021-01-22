@if ($errors->any())
    <div class = "form-group row {{ $errors->has('dot') ? 'has-error' : 'has-success' }}">
@else
<div class="form-group row">
@endif
    {!! Form::label('dot','Tanggal',['class'=>'col-lg-3 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::date('dot',!empty($um) ? $um->dot->format('Y-m-d') : date('Y-m-d')
        ,['class'=>'form-control form-control-sm','required'=>'']) !!}&nbsp;
    @if($errors->has('dot'))<span >{{ $errors->first('dot') }}</span>@endif</div>
</div>

<div class="form-group row">
    {!! Form::label('inv_no','No BPB',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8">
    {!! Form::text('inv_no',null,['class'=>'form-control form-control-sm','placeholder'=>'Silakan input NO BPB','required'=>'']) !!}
    </div>
</div>

<div class="form-group row">
    {!! Form::label('description','Deskripsi',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::text('description',null,['class'=>'form-control form-control-sm','placeholder'=>'Deskripsi','required'=>'']) !!}
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
        <a href="{{ asset('uangmuka') }}" class="btn btn-sm btn-danger">Cancel</a>
    </div>
</div>