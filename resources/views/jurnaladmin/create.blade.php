@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body"><H6>Add Transaction</H6><hr>
                    {!! Form::open(['url'=>'jurnaladmin','class'=>'']) !!}
                    @include('jurnaladmin.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection