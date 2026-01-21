<!DOCTYPE html>
<html>

<head>
    <title>Account Credentials</title>
</head>

<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Your account has been created successfully. Here are your login credentials:</p>
    <p>
        <strong>Email:</strong> {{ $user->email }}<br>
        <strong>Password:</strong> {{ $password }}
    </p>
    <p>Please log in and change your password as soon as possible.</p>
    <p>Thank you!</p>
</body>

</html>