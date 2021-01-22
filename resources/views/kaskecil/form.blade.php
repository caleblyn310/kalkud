@section('css')
<style type="text/css">
    #tanggal_trans, #qqq, #subkode, #deskripsi, #nominal, #no_bukti, #kode_d_ger {
        max-height: 36px;
        min-height: 36px;
        box-shadow: none !important;
        border-width: 0 0 1px 0;
        border-radius: 0;
        background-color: #fbfff1;
        padding-left: 5px;
        padding-top: 0px;
        margin-top: 0px;
        font-size: 18px;
        color: black;
    }

    input:focus {
        border-color: #ccc !important;
    }

    .control-label {
        padding-left: 6px;
        padding-bottom: 0px;
        margin-top: 5px;
        margin-bottom: 0px;
        font-size: 12px;
        color: #00582c;
    }

    /* .input-group-append .input-group-text {
        max-width: 100px;
        text-align: left;
        background: none;
        border-width: 0 0 1px 0;
        padding-left: 4px;
        padding-right: 6px;
        margin-right: 5px;
        border-radius: 0;
        padding-top: 0px;
        margin-top: 0px;
    } */
</style>
@endsection

{!! Form::hidden('kode_unit',Auth::user()->kode_unit) !!}
<input type="hidden" id="sisaSaldo" value="400000">

@if ($errors->any())
    <div class = "form-group {{ $errors->has('tanggal_trans') ? 'has-error' : 'has-success' }}">
@else
<div class="form-group mb-2">
@endif
    <label class="control-label">Date</label>
    {!! Form::date('tanggal_trans',!empty($kaskecil) ? $kaskecil->tanggal_trans->format('Y-m-d') : date('Y-m-d')
    ,['class'=>'form-control form-control-sm','required'=>'', 'id' => 'tanggal_trans']) !!}
    <label class="control-label">COA</label>
    <div class="row">
        <div class="col-9" style="padding-right: 0px">
            {!! Form::text('qqq', '', ['class'=>'form-control form-control-sm','id' =>  'qqq', 'required' ,'autofocus'=>'']) !!}
        </div>
        <div class="col-3">
            {!! Form::text('kode_d_ger',null,['id'=>'kode_d_ger','class'=>'form-control form-control-sm',
            'style' => 'max-width: 80px; background-color: #f0f0f9; font-size: 14px;padding: 0px 0px 0px 5px;',
            'required'=>'','maxlength'=>'10','pattern'=>'\d{3}(.)\d{2}(.)\d{3}','readonly'=>'']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="control-label">Sub Code</label>
            {!! Form::text('subkode',null,['id'=>'subkode', 'class'=>'form-control form-control-sm','maxlength'=>'2']) !!}
        </div>
        <div class="col">
            <label class="control-label">Reff No. (Start with KA/KK)</label>
            {!! Form::text('no_bukti',null,['id'=>'subkode','class'=>'form-control form-control-sm','required'=>'',
            'maxlength'=>'10','pattern'=>'K(A|K|B)\d{5,}']) !!}
        </div>
    </div>
    <label class="control-label">Description</label>
    {!! Form::text('deskripsi',null,['id'=>'deskripsi','class'=>'form-control form-control-sm','required'=>'']) !!}
    <label class="control-label">Nominal (saldo: {{ $sisaSaldo }})</label>
    {!! Form::text('nominal',null,['id'=>'nominal','class'=>'form-control form-control-sm', 'maxlength'=>'8',
    'required'=>'']) !!}
    <span id="errmsg"></span><span>&nbsp;</span>
</div>

<div class="form-group row mb-2">
    <div class="col" style="text-align: center;">
        <button type="submit" class="btn btn-sm btn-success" id="btnSave" style="height: 50px; width: 150px;border-radius: 50px;"><strong>SAVE</strong></button>
    </div>
</div>
<div style="text-align: center;">
    <a href="{{ asset('kaskecil') }}" id="btnCancel" style="text-decoration: none;">go back</a>
</div>

@section('scripts')
<script type="text/javascript">
    var listdata;

    $('#nominal').keyup(function () {
        var sisaSaldo = {{ $sisaSaldo }};
        var nominal = parseInt($('#nominal').val(), 10);
        if(nominal > sisaSaldo)
            $('#btnSave').prop('disabled',true);
        else
            $('#btnSave').prop('disabled',false);
    });

    $('form').submit(function() {
        var spinner = $('#loader');
        spinner.show();
        //return false;
    });

    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: "http://localhost/search/autocomplete",
            cache: false,
            success: function (resp) {
                console.log(resp);
            }
        });
        //alert(listdata);
    });

    $( "#qqq" ).autocomplete({
        source: "http://localhost/search/autocomplete",
        minLength: 3,
        select: function(event, ui) {
            $('#q').val(ui.item.value);
            $('#kode_d_ger').val(ui.item.id);
        }
    });
</script>
@stop