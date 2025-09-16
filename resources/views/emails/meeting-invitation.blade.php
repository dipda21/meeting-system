<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Rapat</title>
</head>
<body>
    <h2>Undangan Rapat</h2>

    <p>Yth. {{ $participant->name }},</p>

    <p>Anda diundang untuk menghadiri rapat berikut:</p>

    <ul>
        <li><strong>Judul:</strong> {{ $meeting->title }}</li>
        <li><strong>Deskripsi:</strong> {{ $meeting->description }}</li>
        <li><strong>Agenda:</strong> {{ $meeting->agenda }}</li>
        <li><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</li>
        <li><strong>Jam:</strong> {{ $meeting->start_time }} - {{ $meeting->end_time }}</li>
        <li><strong>Lokasi:</strong> {{ $meeting->location }}</li>
    </ul>

    <p>Harap konfirmasi kehadiran Anda sebelum waktu rapat dimulai.</p>

    <p>Hormat kami,<br>Tim Sistem Rapat Bank Sulselbar</p>
</body>
</html>
