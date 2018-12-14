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
        <?php include "includes/head.php";
            if (logged_in()) {
                redirect("admin.php");
            }
        ?>
    </head>
    <body>
            <!--Navbar -->
            <?php include "includes/nav.php";?>
            <!-- END-->
        <main style="margin-top: 2.5cm;">
            <div class="container">
                <div class="row flex-center">
                    <div class="col-md-6">
                        <?php
                        display_message();
                        validate_user_login();
                        ?>
                    </div>
                </div>
                <div class="row flex-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-title">
                                <!-- Default form login -->
                                <form class="text-center border border-light p-5 needs-validation" novalidate method="post">
                                    <p class="h4 mb-4">Sign in</p>
                                    <!-- Email -->
                                    <div class="md-form">
                                        <input type="email" name="email" required id="email" class="form-control mb-4">
                                        <label for="email">Email</label>
                                    </div>
                                    <div class="md-form">
                                        <!-- Password -->
                                        <input required type="password" name="password" id="password" class="form-control mb-4">
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <!-- Remember me -->
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="remember" id="remember">
                                                <label class="custom-control-label" for="remember">Remember me</label>
                                            </div>
                                        </div>
                                        <div>
                                            <!-- Forgot password -->
                                            <a href="recover.php">Forgot password?</a>
                                        </div>
                                    </div>

                                    <!-- Sign in button -->
                                    <button class="btn red btn-block my-4" type="submit">Sign in</button>

                                    <!-- Register -->
                                    <p>Not a member?<a href="register.php"> Register</a></p>

                                    <!-- Social login -->
                                    <p >or sign in with:</p>

                                    <a  type="button" class="light-blue-text mx-2">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                    <a hidden type="button" class="light-blue-text mx-2">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                    <a hidden type="button" class="light-blue-text mx-2">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                    <a hidden type="button" class="light-blue-text mx-2">
                                        <i class="fa fa-github"></i>
                                    </a>
                                </form>
                                <!-- Default form login -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <!--END -->

            <!--Footer -->
            <?php include "includes/footer.php"?>
            <!--END -->
    <!--Scripts -->
        <?php include "includes/scripts.php"?>
            <script type="text/javascript" src="js/form_validation.js"></script>
    <!-- END-->
    </body>
</html>