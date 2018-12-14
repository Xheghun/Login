<?php
/**
 * Created by PhpStorm.
 * User: xheghun
 * Date: 02/12/2018
 * Time: 09:54 AM
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include "includes/head.php"?>
    </head>
    <body>
        <!--Navigation -->
        <?php include "includes/nav.php"?>
        <!--END Navigation -->
        <main style="margin-top: 2.5cm;">
            <div class="container">
                <div class="row flex-center">
                    <div class="col-md-6">
                        <?php validate_user_registration();
                            display_message();
                        ?>
                    </div>
                </div>
                <div class="row flex-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-title">
                                <!-- Default form login -->
                                <form class="text-center border border-light p-5 needs-validation" novalidate method="post">
                                    <p class="h4 mb-4">Register</p>
                                    <!-- First Name -->
                                    <div class="md-form">
                                        <input type="text" name="firstname" required id="firstname" class="form-control mb-4">
                                        <label for="firstname">First Name</label>
                                    </div>
                                    <div class="md-form">
                                        <!-- Last Name -->
                                        <input required type="text" name="lastname" id="lastname" class="form-control mb-4">
                                        <label for="lastname">Last Name</label>
                                    </div>
                                    <div class="md-form">
                                        <!--Usersname -->
                                        <input required type="text" name="username" id="username" class="form-control mb-4"/>
                                        <label for="username">Username</label>
                                    </div>
                                    <div class="md-form">
                                        <!--Email -->
                                        <input required type="email" name="email" id="email" class="form-control mb-4"/>
                                        <label for="email">Email</label>
                                    </div>
                                    <div class="md-form">
                                        <!-- Password-->
                                        <input required type="password" name="password" id="password" class="form-control mb-4"/>
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="md-form">
                                        <!--Confirm Password-->
                                        <input required type="password" id="Cpassword" name="confirm_password" class="form-control mb-4">
                                        <label for="Cpassword">Confirm Password</label>
                                    </div>
                                    <!-- Sign in button -->
                                    <button class="btn btn-block btn-outline-red my-4" type="submit">Register</button>
                                    <!-- Register -->
                                    <p>Already a member?<a href="login.php"> Sign In</a></p>
                                </form>
                                <!-- Default form login -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Footer-->
        <?php include "includes/footer.php"?>
        <!--END Footer -->

        <!--Scripts -->
            <?php include "includes/scripts.php";?>
            <script type="text/javascript" src="js/form_validation.js"></script>
        <!--END Script-->
    </body>
</html>
