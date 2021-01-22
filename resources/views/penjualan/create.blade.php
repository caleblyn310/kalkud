@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-body"><h6>Input Penjualan</h6><hr>
                    {!! Form::open(['url'=>'penjualan','class'=>'']) !!}
                    @include('penjualan.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection