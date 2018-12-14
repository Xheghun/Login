<?php
/**
 * Created by PhpStorm.
 * User: xheghun
 * Date: 02/12/2018
 * Time: 09:07 AM
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "includes/head.php"?>
    </head>
    <body>
        <!--Navbar -->
            <?php include "includes/nav.php"?>
        <!--END-->
        <!--Page Content -->
        <main style="margin-top: 2.5cm;">
            <div class="container">
                <div class="jumbotron">
                    <h3 class="text-center"><?php display_message();?></h3>
                </div>
            </div>
        </main>
        <!--END -->
            <!-- Footer -->
                <?php include "includes/footer.php"?>
            <!-- Footer -->
    <!-- Scripts -->
        <?php include "includes/scripts.php"?>
    <!--END-->
    </body>
</html>
