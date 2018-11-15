@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header"><H2>Data Cheque</H2></div>
                <div class="card-body">
                    {!! Form::open(['url'=>'cheque','class'=>'']) !!}
                    @include('check.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>

    @endsection