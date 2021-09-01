<header>
    <ul>
        <?php if(!isset($_SESSION['login'])) { ?>
            <li class="btn-login"><a href="login.php">Login</a></li>
        <?php } ?>
        <li><a href="index.php">Products</a></li>
        <li><a href="cart.php">Cart</a></li>
        <?php if(isset($_SESSION['login'])) { ?>
            <li><a href="#">Admin</a></li>
            <li><a href="login.php?logout=true">Logout</a></li>
        <?php } ?>
    </ul>
</header>