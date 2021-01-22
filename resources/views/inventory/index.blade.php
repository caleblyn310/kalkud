@extends('layouts/app')

@section('content')
    <div class="container table-responsive">
        <div class="row" style="padding-top: 15px;">
        @include('_partial.flash_message')
        <div class="col-2" style="color: black;"><h5><strong>Daftar Inventory</strong></h5></div>
        <div class="col">
        <a href="inventory/create" class="btn btn-sm btn-success">Tambah Inventory</a>
        <a href={{ asset('inventory/genDep') }} class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to generate depreciation report for period {{ $period->format('F Y') }}?')">Generate Depreciation Report</a>
        <span class="align-middle pt-1"><strong>Periode {{ $period->format('F Y') }}</strong></span></div>
        </div><hr>
        <div>
        @if (!empty($inventory_list))
            <table class="table table-striped table-bordered table-hover table-condensed table-sm">
                <thead><tr>
                    <th>Tanggal Beli</th><th style="width: 50%;text-align: left;">Jenis Aktiva</th><th style="width: 5%;">Qty</th><th>Harga Satuan</th><th>Total</th><th>Pemakaian</th><th style="width: 10%;">Action</th>
                </tr></thead>
                <tbody>
                <?php $total = 0 ?>
                <?php foreach ($inventory_list as $inventory) : ?>
                <tr>
                    <td>{{ $inventory->tanggal_beli->format('d-m-Y') }}</td>
                    <td style="text-align: left;">{{ $inventory->jenis_aktiva }}</td>
                    <td>{{ $inventory->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($inventory->harga,0,",",".") }}</td>
                    <td style="text-align: right;">{{ number_format($inventory->total,0,",",".") }}</td>
                    <td style="text-align: right">{{ $inventory->maks }}</td>
                    <?php $total += $inventory->total ?>
                    <td>
                        @if ($inventory->status != "NA")
                        <div class="box-button">{{ link_to('inventory/'.$inventory->id,' Show',['class'=> 'btn btn-success btn-sm fa fa-eye']) }}</div>
                        @elseif ($inventory->status == "NA")
                        <div class="box-button">{{ link_to('inventory/'.$inventory->id.'/edit','',['class'=> 'btn btn-warning btn-sm fa fa-pencil-square-o']) }}</div>
                        <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['InventoryController@destroy',$inventory->id]]) !!}
                            {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                        'onclick'=> 'return confirm("Are you sure you want to delete it??")']) !!}{!! Form::close() !!}</div>
                        <div class="box-button">{{ link_to('inventory/'.$inventory->id.'/lock','',['class'=> 'btn btn-primary btn-sm fa fa-lock confirmation']) }}</div>
                        @endif
                    </td>
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        @else
            <p>Tidak ada data inventory yang belum di upload</p>
        @endif
            <div class="paging">{!! $inventory_list->links('vendor.pagination.bootstrap-4') !!}</div>
            <!--<nav><ul class="pagination justify-content-end">{{ $inventory_list->links() }} </ul></nav>-->
    </div></div>
@stop

@section('scripts')
<script type="text/javascript">
    $('.confirmation').on('click', function () {
        return confirm('Are you sure want to lock this inventory?');
    });
</script>
@stop