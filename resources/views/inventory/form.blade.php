@if ($errors->any())
    <div class = "form-group row {{ $errors->has('tanggal_beli') ? 'has-error' : 'has-success' }}">
@else
<div class="form-group row">
@endif
    {!! Form::label('tanggal_beli','Tanggal Beli',['class'=>'col-lg-3 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::date('tanggal_beli',!empty($inventory) ? $inventory->tanggal_beli->format('Y-m-d') : date('Y-m-d')
        ,['class'=>'form-control','required'=>'']) !!}
    @if($errors->has('tanggal_beli'))<span >{{ $errors->first('tanggal_beli') }}</span>@endif</div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('jenis_aktiva') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('jenis_aktiva','Jenis Aktiva',['class'=>'col-sm-4 col-lg-3 form-control-label']) !!}
    <div class="col-lg-8">
        {!! Form::text('jenis_aktiva',null,['class'=>'form-control','placeholder'=>'Jenis Aktiva',
        'required'=>'']) !!}
        @if ($errors->has('jenis_aktiva'))
            <span class="help-block">{{ $errors->first('jenis_aktiva') }}</span>@endif</div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('category') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('id_cat','Category', ['class' => 'col-3 form-control-label']) !!}
    @if (count($cat_list) > 0)
        <div class="col-lg-8">
        <label>{!! Form::select('id_cat',$cat_list, null, ['class'=>'form-control']) !!}</label>
        </div>
    @else
        <p>Tidak ada pilihan category, buat dulu ya...</p>
    @endif
    @if ($errors->has('id_cat'))<span class="help-block">{{ $errors->first('id_cat') }}</span>@endif
</div>

@if ($errors->any())
    <div class="form-group row locinven {{ $errors->has('locinven') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row locinven">
@endif
    {!! Form::label('locinven','Lokasi', ['class' => 'col-lg-3 form-control-label','name'=>'lokasi']) !!}<br>
    @if (count($list_unit) > 0)
    <div class="col-lg-8">
        @foreach($list_unit as $key => $value)
        <label class="li li{{$key}}">{!! Form::checkbox('locinven[]',$key, null) !!} {{ $value }}</label>
        @endforeach
    </div>
    @else
        <p>Tidak ada pilihan kode unit, buat dulu ya...</p>
    @endif
    @if ($errors->has('lokasi'))<span class="help-block">{{ $errors->first('lokasi') }}</span>@endif
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('quantity') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('quantity','Qty',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8 form-inline">
        {!! Form::text('quantity',!empty($inventory) ? $inventory->quantity : 1 ,['class'=>'form-control','placeholder'=>'Quantity','maxlength'=>'3','required'=>'']) !!}
        &nbsp;&nbsp;<span id="errmsgqty"></span>
        @if ($errors->has('quantity')) <span class="help-block">{{ $errors->first('quantity') }}</span>@endif
    </div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('harga') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('harga','Harga',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8 form-inline">
    {!! Form::text('harga',!empty($inventory) ? $inventory->harga : 0,['class'=>'form-control','placeholder'=>'Harga','required'=>'']) !!}
    &nbsp;&nbsp;<span id="errmsghrg"></span>
    @if ($errors->has('harga')) <span class="help-block">{{ $errors->first('harga') }}</span>@endif
    </div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('maks') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('maks','Pemakaian',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8 form-inline">
        {!! Form::text('maks',!empty($inventory) ? $inventory->maks : 0, ['class'=>'form-control','placeholder'=>'Maks Penyusutan (dalam tahun)', !empty(Auth::user()->kode_unit == 50) ? 'readonly' : 'required']) !!}&nbsp;&nbsp;<span id="errmsgmaks"></span>
        @if ($errors->has('maks')) <span class="help-block">{{ $errors->first('maks') }}</span>@endif
    </div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('total') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('total','Total',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8 form-inline">
        {!! Form::text('total',!empty($inventory) ? $inventory->total : 0,['class'=>'form-control','placeholder'=>'Total Perolehan','required'=>'','readonly'=>'']) !!}
        &nbsp;&nbsp;<span id="errmsghrg"></span>
        @if ($errors->has('total')) <span class="help-block">{{ $errors->first('total') }}</span>@endif
    </div>
</div>

@if ($errors->any())
    <div class="form-group row {{ $errors->has('penyusutan') ? 'has-error' : 'has-success' }}">
@else
    <div class="form-group row">
@endif
    {!! Form::label('penyusutan','Penyusutan',['class'=>'col-sm-3 form-control-label']) !!}
    <div class="col-lg-8 form-inline">
        {!! Form::text('penyusutan',!empty($inventory) ? $inventory->penyusutan : 0,['class'=>'form-control','placeholder'=>'Penyusutan per bulan','required'=>'','readonly'=>'']) !!}
        &nbsp;&nbsp;<span id="errmsghrg"></span>
        @if ($errors->has('total')) <span class="help-block">{{ $errors->first('total') }}</span>@endif
    </div>
</div>

<div class="form-group row">
    <div class="col-md-8 col-md-offset-4">
        <button type="submit" class="btn btn-sm btn-primary">SAVE</button>
        <a href="{{ asset('inventory') }}" class="btn btn-sm btn-primary">Cancel</a>
    </div>
</div>

@section('scripts')
<script>
  $( function() {
    $( "input[type='checkbox']" ).checkboxradio({
        icon: false
    });
  } );

  $('select').on('change', function() {
    var cat = this.value;
    switch(cat) {
        case '1': 
            $('.li').show();
            break;
        case '2': 
            $('.li').hide();
            $('.li1,.li4,.li0,.li10').show();
            break;
        case '3': 
            $('.li').hide();
            $('.li2,.li5,.li0,.li11,.li7').show();
            break;   
        case '4': 
            $('.li').hide();
            $('.li3,.li9,.li0,.li12').show();
            break;
        case '5': 
            $('.li').hide();
            $('.li6,.li8,.li0,.li13').show();
            break;
        case '6': 
            $('.li').show();
            break;
        default:
            alert('Nobody sucks!');
    }
    });
  </script>
  @stop