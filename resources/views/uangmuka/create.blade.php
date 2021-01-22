@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body" ><h6>Tambah Transaksi Uang Muka</h6><hr>
                    {!! Form::open(['url'=>'uangmuka','class'=>'']) !!}
                    @include('uangmuka.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection