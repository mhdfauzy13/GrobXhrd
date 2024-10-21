<!DOCTYPE html>
<html>
<head>
    <title>Status Pengajuan Cuti</title>
</head>
<body>
    <h1>Status Pengajuan Cuti</h1>
    <p>Hai, {{ $offRequest->name }}</p>
    <p>Pengajuan cuti kamu telah <strong>{{ $status }}</strong>.</p>

    <p>Detail Pengajuan:</p>
    <ul>
        <li>Judul: {{ $offRequest->title }}</li>
        <li>Deskripsi: {{ $offRequest->description }}</li>
        <li>Tanggal Mulai: {{ $offRequest->start_event }}</li>
        <li>Tanggal Selesai: {{ $offRequest->end_event }}</li>
    </ul>
</body>
</html>
