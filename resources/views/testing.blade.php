@extends('layouts.app')


@section('content')
    @include('datareim.datareimfilelist')
    <h2>Hallo Hallo</h2>
    <a href="{{ asset('mR') }}">MR</a>
{!! Form::open (['url'=>'testing','files'=>true]) !!}
<div class="form-group">
    {!! Form::label('acc_num','Account Number:') !!}
    {!! Form::text ('acc_num',null,['class'=>'form-control','required' => '' ]) !!}
    </br>
    {!! Form::select('kodeper') !!}
    </br>
    {!! Form::label('uploaded_file','Upload File:') !!}
    {!! Form::file('file_exc',['class'=>'form-control','required' => '']) !!}
    <!--{!! Form::file('file_bca',['class'=>'form-control','required' => '']) !!}-->
    <!--<input type="file" name="filebca[]" multiple />-->
    </br>
    {!! Form::submit('submit') !!}
</div>
{!! Form::close() !!}
<br>
<a href="{{ route('logout') }}"
   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
    Logout
</a>

<button onclick="webprint.printRaw(buffer, 'lx300');">print dotmatrix</button>

<a href="{{ asset('testing') }} ">Check Query</a>
<a href="/send" class="btn btn-primary btn-sm">Send Email</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

<form>
    <input type="text" id="terbilang"><button type="button" id="sub">Touch ME!</button><br/>
    <input type="text" id="terbilang-1" readonly=""><br/>
    <input type="text" id="terbilang-output2">
    <table><tr><td id="terbilang-input">123456</td></tr><tr><td id="terbilang-output"></td></tr></table>
  </form>
@stop

@section('scripts')
<script src="{{ asset('js/webprint.js') }}"></script>
<script src="{{ asset('terbilang/jTerbilang.js') }}"></script>
  <script type="text/javascript">
    $('#sub').click(function (e) {
        $("#terbilang-input").html($('#terbilang').val());
        $("#terbilang-input").val($('#terbilang').val());
        $('#terbilang-input').change();
        terblg();
        /*$('#terbilang-input').terbilang({
            style : 4,
            output_div : "terbilang-output"
          });*/
    });
    $("#terbilang").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        //$("#terbilang-input").val($('#terbilang').val());
        });
  
  function terblg() {
  $('#terbilang-input').terbilang({
    style: 4,
    output_div: "terbilang-output",
  });
}

    /*webprint = new WebPrint(true, {
            relayHost: "127.0.0.1",
            relayPort: "8080",
            readyCallback: function(){
                
            }
        });

    $(function(){
        $.ajax({
            url: "http://kaskecil.app/printdot",
            async: false,
            cache: false,
            dataType: "json",
            success: function (data, textStatus, jq) {
                buffer = "";
                buffer = data.string;
            }
        });
    });*/
</script>
@stop