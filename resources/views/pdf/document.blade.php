<!DOCTYPE html>
<html>
<head>
    <style>
        html {margin-top:10px;} table {font-family: arial, sans-serif;font-size:65%;border-collapse: collapse;width: 100%;}
        td, th {border: 1px solid; text-align: center;padding: 2px;}
        tr:nth-child(even) {background-color: #dddddd;}

        @page {
            header: page-header;
            footer: page-footer;
        }
    </style>
</head>
<body>
    <htmlpageheader name="page-header">
    <H4>Laporan Reimburse - </H4>
</htmlpageheader>
    @if (!empty($kaskecil_list))
    <table class="table table-striped table-bordered table-hover table-condensed table-sm">
                <thead><tr>
                    <th>Tanggal</th><th>Kode D-Ger</th><th>Sub<br>Kode</th><th>No BPU</th><th>Deskripsi</th><th>Nominal</th>
                </tr></thead>
                <tbody>
                <?php $total = 0 ?>
                <?php foreach ($kaskecil_list as $kaskecil) : ?>
                <tr>
                    <td>{{ $kaskecil->tanggal_trans->format('d-m-Y') }}</td>
                    <td>{{ $kaskecil->kode_d_ger }}</td>
                    <td>{{ $kaskecil->subkode }}</td>
                    <td>{{ $kaskecil->no_bukti }}</td>
                    <td style="text-align: left;">{{ $kaskecil->deskripsi }}</td>
                    <td style="text-align: right">{{ number_format($kaskecil->nominal,0,",",".") }},00</td>
                    <?php $total += $kaskecil->nominal ?>
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        @else
            <p>Tidak ada data kas kecil yang belum di upload</p>
        @endif
        <htmlpagefooter name="page-footer">
    {PAGENO} / {nb}
</htmlpagefooter>
</body>
</html>
