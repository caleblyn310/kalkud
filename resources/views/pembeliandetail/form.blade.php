<div class="form-group row">
    <div class="col-sm-12 col-md-6 col-lg-3">
    {!! Form::label('kode_brg','Kode Barang',['class'=>' form-control-label']) !!}
    </div>
    <div class="col-lg-8">
        {!! Form::text('q_add', '', ['class'=>'form-control form-control-sm','id' =>  'q_add', 'placeholder' =>  'Cari berdasarkan nama barang . . .',
        'autofocus'=>'']) !!}</div>
</div>

<div class="form-group row">
    {!! Form::label('kosong','      ',['class'=>'col-3 form-control-label']) !!}
    <div class="col-lg-8">{!! Form::text('kode_brg_add',null,['class'=>'form-control form-control-sm', 'id'=>'kode_brg_add', 'placeholder'=>'KODE Barang','required'=>'','readonly'=>'']) !!}</div>
</div>

<div class="form-group row">
    <div class="col">
    <div class="form-group row">
        {!! Form::label('qty','Kuantiti 1',['class'=>'col-3 form-control-label']) !!}
        <div class="col-8">
            {!! Form::text('kuantiti1',null,['class'=>'form-control form-control-sm', 'id'=>'kuantiti1', 'placeholder'=>'Kuantiti 1']) !!}
            <!--<p class="errorDescription text-center alert alert-danger hidden"></p>-->
        </div>
    </div>
    <div class="form-group row">
    {!! Form::label('qty','Kuantiti 2',['class'=>'col-3 form-control-label']) !!}
        <div class="col-8">
            {!! Form::text('kuantiti2',null,['class'=>'form-control form-control-sm', 'id'=>'kuantiti2', 'placeholder'=>'Kuantiti 2']) !!}
            <!--<p class="errorDescription text-center alert alert-danger hidden"></p>-->
        </div>
    </div>
    <div class="form-group row">
    {!! Form::label('nominal','Nominal',['class'=>'col-3 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('nominal',null,['class'=>'form-control form-control-sm','maxlength'=>'10']) !!}
        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
    </div>
    </div>
     <div class="form-group row">
    {!! Form::label('diskon','Diskon',['class'=>'col-3 form-control-label']) !!}
    <div class="col-8">
        {!! Form::text('diskon',null,['class'=>'form-control form-control-sm','maxlength'=>'10']) !!}
        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
    </div>
    </div>
    <div class="form-group row">
    <div class="col-3">
    {!! Form::label('hrg_sat','Harga Satuan',['class'=>'form-control-label','style'=>'margin-bottom:1px;']) !!}
    <span class="badge badge-pill badge-success" style="background-color: lightred;">(setelah diskon)</span></div>
    <div class="col-8">
        {!! Form::text('hrg_sat',null,['class'=>'form-control form-control-sm','placeholder'=>'0','readonly'=>'']) !!}
        <!--<p class="errorNominal text-center alert alert-danger hidden"></p>-->
    </div>
</div>
    </div>
</div>