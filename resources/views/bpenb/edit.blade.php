@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-10 mx-auto">
            <div class="card">
                <div class="card-body"><h6>Bukti Penerimaan Bank</h6><hr>
                    {!! Form::model($bpenb,['method'=>'PATCH', 'action'=>['BpenbController@update',$bpenb->id]]) !!}
                    @include('bpenb.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection