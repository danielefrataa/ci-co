<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Events</title>
</head>
<body>
    <h1>Daftar Events</h1>

    @if (!empty($events['data']))
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Event</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events['data'] as $event)
                    <tr>
                        <td>{{ $event['id'] }}</td>
                        <td>{{ $event['name'] }}</td>
                        <td>{{ $event['status'] }}</td>
                        <td>{{ $event['pic_name'] }}</td>
                        <td>{{ $event['created_at'] }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data tersedia.</p>
    @endif
</body>
</html>