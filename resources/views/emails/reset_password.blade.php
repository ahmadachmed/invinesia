<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Hi {{ $user->name }} ,</h1>
    <p>Kamu telah melakukan permintaan reset password, silahkan konfirmasi melalui <a href="{{ env('URL_APPS) . / . $user->reset_token }}">link ini</a></p>
</body>
</html>