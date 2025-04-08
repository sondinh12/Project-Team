<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form action="<?=$_ENV['BASE_URL'] . 'login'?>" method="post">
        <label for="user_name">Tên</label>
        <input type="text" name="user_name" >
        <label for="password">Mật khẩu</label>
        <input type="password" name="password">
        <button type="submit">Đăng nhập</button>
    </form>
</body>
</html>