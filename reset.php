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
        <title>Reset Password - Void.io</title>
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
                            //password_reset();
                        ?>
                    </div>
                </div>
                    <div class="row flex-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-title">
                                    <form class="text-center border border-light p-5 needs-validation" autocomplete="off" novalidate method="post" >
                                        <p class="h2 mb-5">Password Reset</p>
                                        <div class="md-form">
                                            <input type="text" name="password" required id="password" class="form-control mb-4"/>
                                            <label for="password">New Password</label>
                                        </div>
                                        <div class="md-form">
                                            <input type="password" name="confirm_password" required id="c_password" class="form-control mb-4"/>
                                            <label for="c_password">Confirm New Password</label>
                                        </div>
                                        <button type="submit"  class="btn btn-block btn-success" name="reset-password-submit" id="submit" tabindex="2">Change</button>
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
