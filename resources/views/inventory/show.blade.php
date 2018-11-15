@extends('layouts/app')

@section ('content')
<div class="container">
        <div class="row" style="padding-top: 25px;">
            <div class="col-md-7 mx-auto">
            <div class="card">
                <div class="card-header"><H2>Inventory Detail</H2></div>
                <div class="card-body" style="background-color: #d5f4e6;">
                    <table class="table table-striped">
                    	<tr>
                    		<td>Jenis Aktiva</td>
                    		<td>{{ $inventory->jenis_aktiva }}</td>
                    	</tr>
                    	<tr>
                    		<td>Kuantiti</td>
                    		<td>{{ $inventory->quantity }}</td>
                    	</tr>
                    	<tr>
                    		<td>Harga Satuan</td>
                    		<td>{{ number_format($inventory->harga,'2','.',',') }}</td>
                    	</tr>
                    	<tr>
                    		<td>Total</td>
                    		<td>{{ number_format($inventory->total,'2','.',',') }}</td>
                    	</tr>
                    	<tr>
                    		<td>Tanggal Beli</td>
                    		<td>{{ $inventory->tanggal_beli->format('d M Y') }}</td>
                    	</tr>
                    	<tr>
                    		<td>Lokasi</td>
                    		<td>@foreach($inventory->kodeunit as $item)
                                <strong><span>{{ $item->unit }}</span></strong><br>
                                @endforeach
                            </td>
                    	</tr>
                    	<tr>
                    		<td>Status</td>
                    		<td>{{ $inventory->status }}</td>
                    	</tr>
                    	<tr>
                    		<td colspan="2">
                    			@if( $inventory->status == 'NA' )
                    			<a href="{{ asset('inv/.$inventory->id.') }}" class="btn btn-primary">Inventory</a>
                    			<a href="{{ asset('noinv/.$inventory->id.') }}" class="btn btn-primary">Not Inventory</a>
                    			@endif
                    			<a href="{{ asset('inventory') }}" class="btn btn-primary">Close</a>
                    		</td>
                    	</tr>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
@stop