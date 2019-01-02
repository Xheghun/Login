<?php
include_once "functions/init.php";
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
    if (!logged_in()) {
        redirect("login.php");
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
                if (isset($_POST["submit"])) {
                    change_password();
                }
                //echo escape($_COOKIE["email"]);
                ?>
            </div>
        </div>
        <div class="row flex-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-title">
                        <!-- Default form login -->
                        <form class="text-center border border-light p-5 needs-validation" action="change_password.php" method="post" novalidate>
                            <p class="h4 mb-4">Change Password</p>
                            <!-- Old password -->
                            <div class="md-form">
                                <input type="password" name="oldpass" required id="oldPass" class="form-control mb-4">
                                <label for="oldPass">Old Password</label>
                            </div>
                            <div class="md-form">
                                <!--New Password -->
                                <input required type="password" name="newpass" id="nPass" class="form-control mb-4">
                                <label for="nPass">New Password</label>
                            </div>
                            <div class="md-form">
                                <!--confirm new password -->
                                <input required type="password" name="cnewpass" id="cpass" class="form-control mb-4" />
                                <label for="cpass">Confirm New Password</label>
                            </div>
                            <div class="d-flex justify-content-around">
                                <div>
                                    <!-- Forgot password -->
                                    <a href="recover.php">Forgot password?</a>
                                </div>
                            </div>
                            <!-- Sign in button -->
                            <button class="btn red btn-block my-4" name="submit" type="submit">Change</button>
                            <input type="hidden" class="text-hide" name="token" id="token" value="<?php echo  token_generator();?>"/>
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