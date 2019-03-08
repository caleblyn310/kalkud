@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['url'=>'invoices','class'=>'', 'id'=>'frmInvoice']) !!}
                    @include('invoices.form')
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection