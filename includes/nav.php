<?php
/**
 * Created by PhpStorm.
 * User: xheghun
 * Date: 02/12/2018
 * Time: 10:53 AM
 */
?>
<nav class="navbar navbar-expand-lg navbar-dark red fixed-top">
    <div class="container">
        <!--Navbar Brand-->
        <a class="navbar-brand" href="index.php">ProjectX</a>

        <!--Collapse Button-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobile" aria-controls="mobile_control"
                aria-expanded="false" aria-label="toggle">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!--Collapsible content-->
        <div class="collapse navbar-collapse" id="mobile">
            <!-- links -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <?php if (logged_in()):?>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a> </li>
                    <li class="nav-item"><a class="nav-link" href="settings.php" target="_blank">Settings</a></li>
                <?php endif;?>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="r">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php if (!logged_in()):?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif;?>
                <?php if (logged_in()):?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
