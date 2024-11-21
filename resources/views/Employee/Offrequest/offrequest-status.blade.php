<!DOCTYPE html>
<html>

<head>
    <title>Leave Application Status</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h1 style="color: #2c3e50; font-size: 24px;">Leave Application Status</h1>

        <p>Hai, {{ $offRequest->name }}</p>
        <p>Your leave application has been <strong>{{ $status }}</strong>.</p>

        <p><strong>Submission Details:</strong></p>
        <ul style="list-style-type: none; padding: 0;">
            <li><strong>Title:</strong> {{ $offRequest->title }}</li>
            <li><strong>Description:</strong> {{ $offRequest->description }}</li>
            <li><strong>Start Event:</strong> {{ $offRequest->start_event }}</li>
            <li><strong>End Event:</strong> {{ $offRequest->end_event }}</li>
        </ul>

        <p style="margin-top: 20px;">Thank You,<br>HR Department</p>
    </div>
</body>

</html>
