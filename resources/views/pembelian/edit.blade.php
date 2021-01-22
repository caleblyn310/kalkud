@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-10 mx-auto">
            <div class="card">
                <div class="card-body"><h6>Edit Data Pembelian</h6><hr>
                    {!! Form::model($pembelian,['method'=>'PATCH', 'action'=>['PembelianController@update',$pembelian->id]]) !!}
                    @include('pembelian.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection