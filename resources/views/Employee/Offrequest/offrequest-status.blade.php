<!DOCTYPE html>
<html>

<head>
    <title>Status Pengajuan Cuti</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h1 style="color: #2c3e50; font-size: 24px;">Status Pengajuan Cuti</h1>

        <p>Hai, {{ $offRequest->name }}</p>
        <p>Pengajuan cuti kamu telah <strong>{{ $status }}</strong>.</p>

        <p><strong>Detail Pengajuan:</strong></p>
        <ul style="list-style-type: none; padding: 0;">
            <li><strong>Judul:</strong> {{ $offRequest->title }}</li>
            <li><strong>Deskripsi:</strong> {{ $offRequest->description }}</li>
            <li><strong>Tanggal Mulai:</strong> {{ $offRequest->start_event }}</li>
            <li><strong>Tanggal Selesai:</strong> {{ $offRequest->end_event }}</li>
        </ul>

        <p style="margin-top: 20px;">Terima kasih,<br>HR Department</p>
    </div>
</body>

</html>
