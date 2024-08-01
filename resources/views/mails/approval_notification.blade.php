<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Approval Notification</title>
</head>
<body>
    <p>Hello,</p>

    <p>The following permit application has been updated:</p>

    <p><strong>Permit ID:</strong> {{ $permitApplication->id }}</p>
    <p><strong>Status:</strong> {{ $permitApplication->status }}</p>
    <p><strong>Updated By:</strong> {{ $permitApplication->updated_by }}</p>

    <p>Please review the application at your earliest convenience.</p>

    <p>Thank you,</p>
    <p>The Permit Application Team</p>
</body>
</html>
