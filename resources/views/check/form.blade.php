@if ($errors->any())
    <div class="form-group row {{ $errors->has('tanggal_cair') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('tanggal_cair','Tanggal Cair',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-md-8">
        {!! Form::text('tanggal_cair',!empty($cheque) ? $cheque->tanggal_cair->format('Y-m-d') : date('Y-m-d'),['class'=>'form-control form-control-sm','placeholder'=>'Tanggal Cair Cheque',
        'required'=>'']) !!}
    </div>
        @if ($errors->has('tanggal_cair'))
            <span class="help-block">{{ $errors->first('tanggal_cair') }}</span>@endif
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('no_check') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('no_check','No Cheque',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-md-8">
        {!! Form::text('no_check',null,['class'=>'form-control form-control-sm','placeholder'=>'No Cheque',
        'required'=>'']) !!}
    </div>
        @if ($errors->has('no_check'))
            <span class="help-block">{{ $errors->first('no_check') }}</span>@endif
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('data_reimburse') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('data_reimburse','Reimburse',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-md-8">
        {!! Form::text('data_reimburse',!empty($cheque) ? $cheque->data_reimburse : $tempf->namafile,['class'=>'form-control form-control-sm','readonly']) !!}
    </div>
    @if ($errors->has('data_reimburse'))
            <span class="help-block">{{ $errors->first('data_reimburse') }}</span>@endif
</div>

<div class="form-group row">
    {!! Form::label('nominal','Nominal',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-md-8">
        {!! Form::text('nominal',!empty($cheque) ? $cheque->nominal : $tempf->nominal,['class'=>'form-control form-control-sm','placeholder'=>'Nominal',
        'required'=>'','readonly'=>'']) !!}&nbsp;<span id="errmsg"></span>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-8 col-md-offset-4">
        <button type="submit" class="btn btn-sm btn-info">SAVE</button>
        <a href="{{ asset('cheque/cancel') }}" class="btn btn-sm btn-danger" id="chequeCancel">Cancel</a>
    </div>
</div>