<header>
    <ul>
        <?php if (!isset($_SESSION['login'])):  ?>
            <li class="btn-login"><a href="login.php"><?= translate('Login') ?></a></li>
        <?php endif ?>
        <li><a class="<?= $_SERVER['REQUEST_URI'] == '/pages/index.php' ? 'active' : ''  ?>" href="index.php"><?= translate('Home') ?></a></li>
        <li><a class="<?= $_SERVER['REQUEST_URI'] == '/pages/cart.php' ? 'active' : ''  ?>" href="cart.php"><?= translate('Cart') ?></a></li>
        <?php if (isset($_SESSION['login'])): ?>
            <li><a class="<?= $_SERVER['REQUEST_URI'] == '/pages/products.php' ? 'active' : ''  ?>" href="products.php"><?= translate('Products') ?></a></li>
            <li><a class="<?= $_SERVER['REQUEST_URI'] == '/pages/orders.php' ? 'active' : ''  ?>" href="orders.php"><?= translate('Orders') ?></a></li>
            <li>
                <form action="login.php" method="POST">
                    <input type="hidden" name="logout" value="true">
                    <button type="submit" style="margin-top: -10px;"><?= translate('Logout') ?></button>
                </form>
            </li>
        <?php endif ?>
    </ul>
</header>