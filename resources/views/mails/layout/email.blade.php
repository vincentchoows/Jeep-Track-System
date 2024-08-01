<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Notification' }}</title>
</head>
<body>
    <div style="padding: 20px;">
        @yield('content')
    </div>
</body>
</html>
