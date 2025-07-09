<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vendor Application Result</title>
</head>
<body>
    <p>Dear {{ $application->vendor_name }},</p>

    <p>Your vendor application has been reviewed. Here are the results:</p>

    <ul>
        <li><strong>Status:</strong> {{ ucfirst($application->status) }}</li>
        @if ($application->visit_schedule_for)
            <li><strong>Visit Scheduled For:</strong> {{ \Carbon\Carbon::parse($application->visit_schedule_for)->format('F j, Y g:i A') }}</li>
        @endif
        @if ($application->validation_notes)
            <li><strong>Notes:</strong> {{ $application->validation_notes }}</li>
        @endif
    </ul>

    <p>Thank you,<br>Supply Chain Team</p>
</body>
</html>
