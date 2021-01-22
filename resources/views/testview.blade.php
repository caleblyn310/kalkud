<table>
    <thead>
    <tr>
        @foreach($dtex[0] as $key => $value)
            <th>{{ ucfirst($key) }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($dtex as $row)
        <tr>
        @foreach ($row as $value)
            <td>{{ $value }}</td>
        @endforeach
        </tr>
    @endforeach
    </tbody>
</table>