<header>
    <ul>
        <?php if (!isset($_SESSION['login'])):  ?>
            <li class="btn-login"><a href="login.php"><?= translate('Login') ?></a></li>
        <?php endif ?>
        <li><a href="index.php"><?= translate('Home') ?></a></li>
        <li><a href="cart.php"><?= translate('Cart') ?></a></li>
        <?php if (isset($_SESSION['login'])): ?>
            <li><a href="products.php"><?= translate('Products') ?></a></li>
            <li><a href="login.php?logout=true"><?= translate('Logout') ?></a></li>
        <?php endif ?>
    </ul>
</header>