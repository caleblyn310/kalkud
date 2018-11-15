@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-md-5 mx-auto" style="padding-top: 25px;">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form class="" method="POST" action="{{ route('login') }}">{{ csrf_field() }}
                        <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-lg-4 form-control-label">Username</label>
                            <div class="col-lg-8">
                                <input id="name" class="form-control" name="name" value="{{ old('name') }}"
                                       required autofocus>@if ($errors->has('name')) <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-lg-4 form-control-label">Password</label>
                            <div class="col-lg-8">
                                <input id="password" type="password" class="form-control" name="password"
                                       required>@if ($errors->has('password')) <span class="help-block">

                                    {{ $errors->first('password') }}

                                </span>
                                @endif</div>
                        </div>
                        <div class="form-group row mx-auto">
                            
                                <button type="submit" class="btn btn-success">Login</button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    
@endsection
