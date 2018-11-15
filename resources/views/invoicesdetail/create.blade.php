@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-10 mx-auto">
            <div class="card">
                <div class="card-header"><H2>Invoices Detail for Invoices No: {{ $invoices_no }}</H2></div>
                <div class="card-body">
                    {!! Form::open(['url'=>'invoicesdetail','class'=>'']) !!}
                    @include('invoicesdetail.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection