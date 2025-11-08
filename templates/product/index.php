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
    <h1>Список всех товаров</h1>
    <?php foreach ($products as $product): ?>
        <?=$product['name']?>
        <?=$product['price']?>
        <?=$product['category_id']?>
        <a href="/product/<?=$product['id']?>">Подробнее</a>

    <?php if ():?>

        <form action="/cart/minus" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="-">
        </form>

    <span>3</span>

        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="+">
        </form>

        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="Добавить в корзину">
        </form>
    <?php endforeach;?>
</body>
</html>