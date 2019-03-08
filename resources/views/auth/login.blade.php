@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-md-5 mx-auto" style="padding-top: 25px;">
            <div class="card">
                <div class="card-body">Login<hr>
                    <form class="" method="POST" action="{{ route('login') }}">{{ csrf_field() }}
                        <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-lg-4 form-control-label">Username</label>
                            <div class="col-lg-8">
                                <input id="name" class="form-control form-control-sm" name="name" value="{{ old('name') }}"
                                       required autofocus>@if ($errors->has('name')) <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-lg-4 form-control-label">Password</label>
                            <div class="col-lg-8">
                                <input id="password" type="password" class="form-control form-control-sm" name="password"
                                       required>@if ($errors->has('password')) <span class="help-block">

                                    {{ $errors->first('password') }}

                                </span>
                                @endif</div>
                        </div>
                        <div class="form-group row mx-auto">
                            
                                <button type="submit" class="btn btn-success btn-sm" id="btnSbmt">Login</button>
                            
                        </div>
                    </form>
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
