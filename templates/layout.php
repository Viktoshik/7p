<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <?php if(empty($_SESSION['user_id'])): ?>
            <a href="/login">Вход</a>
            <a href="/register">Регистрация</a>
        <?php else:?>
            <a href="/logout">Выход</a>
            <a href="/">Корзина</a>
        <?php endif;?>
        <a href="/">Главная</a>
    </div>
    <?=$content?>
</body>
</html>
