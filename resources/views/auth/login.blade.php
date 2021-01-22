@extends('layouts.app')
@section('css')
<link href="{{ asset('css/style_login.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container" style="min-height: 100vh;height: 100%;">
  <div class="row" style="min-height: 100vh;width:100%;height: 100%;">
    <div class="col-lg-4 col-md-8 col-sm-12 align-self-center mx-auto">
      <div class="card card-block">
                <div class="card-body" style="text-align: center;color: #636363;">
                    <div class="avatar">
                        <img src="/logo-skkkb.png" alt="Avatar">
                    </div>
                    <h4>Sign In</h4>
                    <form class="" method="POST" action="{{ route('login') }}">{{ csrf_field() }}
                        <div class="input-group mb-3 {{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input id="name" class="form-control form-control-sm" name="name" value="{{ old('name') }}"
                                   required placeholder="Username" style="box-shadow: none !important;border-width: 0 0 1px 0; border-radius: 0;">@if ($errors->has('name')) <span class="help-block">
                                {{ $errors->first('name') }}
                            </span>
                            @endif
                        </div>
                        <div class="input-group mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                                <input id="password" type="password" class="form-control form-control-sm" name="password"
                                       required placeholder="Password">@if ($errors->has('password')) <span class="help-block">
                                    {{ $errors->first('password') }}
                                </span>
                                @endif
                        </div>
                        <div class="form-group row mx-auto mt-4 mb-5">
                            
                                <button type="submit" class="btn btn-primary btn-block" id="btnSbmt"><strong>Sign In</strong></button>
                            
                        </div>
                    </form>
                    <hr>
                    <p style="margin: 0px;padding: 0px;font-size: 10px;">&copy; Yayasan Kalam Kudus Indonesia 1978 - {{ date("Y") }}</p>
                    <!--<div class="card-footer">
                        <p style="margin: 0px;padding: 0px;font-size: 10px;">&copy; Perusahaan Kalam Kudus Indonesia 1978 - {{ date("Y") }}</p>
                    </div>-->
                </div>
        </div>
    </div>
  </div>
</div>
@endsection

@section ('scripts')
<script>
var spinner = $('#loader');
$(function() {
  $('#btnSbmt').click(function(e) {
    //e.preventDefault();
    spinner.show();
  });
});
</script>
@endsection
