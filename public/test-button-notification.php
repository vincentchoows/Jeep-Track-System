<!-- resources/views/notification.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Button</title>
    <style>
        .notification-btn {
            position: relative;
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .notification-btn .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            padding: 5px 10px;
            border-radius: 50%;
            background-color: red;
            color: white;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <button class="notification-btn">
        Messages
        <span class="badge">5</span>
    </button>
</body>
</html>
