@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-5 col-sm-12 mx-auto">
            <div class="card">
                <div class="card-body" style="border-radius: 5px;">
                    <div class="mb-4" style="text-align: center;width: 100%;">
                        <h4 style="color: #343434;">Add New Transaction</h4>
                    </div>
                    {!! Form::open(['url'=>'kaskecil','class'=>'']) !!}
                    @include('kaskecil.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>

    @endsection