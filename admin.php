<?php
include_once "functions/init.php";
/**
 * Created by PhpStorm.
 * User: xheghun
 * Date: 02/12/2018
 * Time: 09:52 AM
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "includes/head.php";?>
    </head>
    <body>
        <!--Nav -->
        <?php include "includes/nav.php"?>
        <!--END Nav -->
        <main style="margin-top: 2.5cm;">
            <div class="container">
                <div class="jumbotron">
                    <h3 class="text-center"><?php if (logged_in()) {
                            echo "Logged In";
                        }else {
                            redirect("logout.php");
                        }?></h3>
                </div>
            </div>
        </main>
        <!-- Footer -->
        <?php include "includes/footer.php"?>
        <!-- END Footer -->
        <!-- Scripts -->
        <?php include "includes/scripts.php"?>
        <!--END Scripts -->
    </body>
</html>
