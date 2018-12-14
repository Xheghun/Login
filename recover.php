<?php
/**
 * Created by PhpStorm.
 * User: xheghun
 * Date: 02/12/2018
 * Time: 09:53 AM
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <!--Header Files -->
        <?php include "includes/head.php"?>
        <!--END Filed-->
        <title>Recover Password - Void.io</title>
    </head>
    <body>
        <!--Navigation -->
        <?php include "includes/nav.php";?>
        <!--END Navigation-->
        <main style="margin-top: 2.5cm;">
            <div class="container">
                <div class="row flex-center">
                    <div class="col-md-6">
                        <?php display_message();
                            recover_password();
                        ?>
                    </div>
                </div>
                    <div class="row flex-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-title">
                                    <form class="text-center border border-light p-5 needs-validation" autocomplete="off" novalidate method="post" >
                                        <p class="h2 mb-5">Recover Password</p>
                                        <div class="md-form">
                                            <input type="email" name="email" required id="email" class="form-control mb-4">
                                            <label for="email">Email</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button class="btn btn-danger btn-block" type="reset" tabindex="1">Reset</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-block btn-success" name="recover-submit" id="submit" tabindex="2">Recover</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="text-hide" name="token" id="token" value="<?php echo  token_generator();?>"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
        <!--Footer -->
        <?php include "includes/footer.php"?>
        <!--END Footer-->

        <!--Scripts-->
        <?php include "includes/scripts.php";?>
        <script type="text/javascript" src="js/form_validation.js"></script>
        <!--END Script-->
    </body>
</html>
