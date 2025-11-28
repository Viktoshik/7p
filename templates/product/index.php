<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <title>Document</title>
</head>
<body>
<section class="container">
    <div class="row">
        <div class="col">
            <form class="row g-3 needs-validation" novalidate>
    <div class="col-md-4"><h1>Список всех товаров</h1></div>
    <div class="mb-3"><p><a href="/product/cartItems">Перейти в корзину</a></p></div>
    <div class="mb-3">
        <?php foreach ($products as $product): ?>
        <?=$product['name']?>
        <?=$product['price']?>
        <?=$product['category_id']?>
        <a href="/product/<?=$product['id']?>">Подробнее</a>
    </div>

    <?php if (array_key_exists($product['id'], $cartItems)): ?>

    <div class="mb-3">
        <form action="/cart/minus" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="-" class="btn btn-dark">
        </form>
    </div>

    <span><?=$cartItems[$product['id']]?></span>

        <div class="mb-3">
        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="+">
        </form>
        </div>
    <?php else:?>
        <div class="mb-3">
        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="Добавить в корзину">
        </form>
        </div>
    <?php endif;?>
    <?php endforeach;?>
            </form>
        </div>
    </div>
</section>


<script src="../../bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>
