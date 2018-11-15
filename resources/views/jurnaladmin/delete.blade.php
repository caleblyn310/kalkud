@extends('layouts.app')

@section('content')
@if(!empty($ja))
    <div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header"><H2>Detail Transaksi</H2></div>
                <div class="card-body" style="background-color: #d5f4e6;">
                    <table class="table table-striped">
                        <tr>
                            <td>Tanggal</td>
                            <td>{{ $ja->tanggal->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>No Account</td>
                            <td>{{ $ja->No_account }}</td>
                        </tr>
                        <tr>
                            <td>No Bukti</td>
                            <td>{{ $ja->No_bukti }}</td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>{{ $ja->Uraian }}</td>
                        </tr>
                        <tr>
                            <td>Debit</td>
                            <td>{{ number_format($ja->Debet,'2','.',',') }}</td>
                        </tr>
                        <tr>
                            <td>Kredit</td>
                            <td>{{ number_format($ja->Kredit,'2','.',',') }}</td>
                        </tr>
                        <tr>
                            <td>Kontra Account</td>
                            <td>{{ $ja->Kontra_acc }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['JurnalController@destroy',$ja->id]]) !!}
                            {!! Form::button(' Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                            'onclick'=> 'return confirm("Yakin nih mau dihapus??")']) !!}{!! Form::close() !!}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    @endif
@stop