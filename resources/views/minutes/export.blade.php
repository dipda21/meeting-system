<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notulen Rapat</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Daftar Notulen Rapat</h2>
    <table>
        <thead>
            <tr>
                <th>Judul Rapat</th>
                <th>Penulis</th>
                <th>Ringkasan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($minutes as $minute)
                <tr>
                    <td>{{ $minute->meeting->title }}</td>
                    <td>{{ $minute->creator->name }}</td>
                    <td>{{ $minute->summary }}</td>
                    <td>{{ $minute->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
