@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-10 mx-auto">
            <div class="card">
                <div class="card-body"><h6>Edit Data Penjualan</h6><hr>
                    {!! Form::model($penjualan,['method'=>'PATCH', 'action'=>['PenjualanController@update',$penjualan->id]]) !!}
                    @include('penjualan.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection