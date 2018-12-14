<?php
require "vendor/autoload.php";
require "classes/Config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * Created by PhpStorm.
 * User: xheghun
 * Date: 21/11/2018
 * Time: 08:56 PM
 */

function clean($string){
    return htmlentities($string);
}

/**
 * @param $alert
 * @return string
 */
function validation_errors($alert) {
    $alert = <<<MHT
    <div class="alert alert-danger alert-dismissable" role="alert">
                        <strong>Error!</strong> $alert 
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times;
                            </span>
                        </button>       
     </div>
MHT;
    return $alert;
}

/**
 * @param $email
 * @return bool
 */
function email_exits($email) {
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = query($sql);
    if(row_count($result) == 1) {
        return true;
    }else{
        return false;
    }
}

/**
 * @param $username
 * @return bool
 */
function username_exits($username) {
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = query($sql);
    if(row_count($result) == 1) {
        return true;
    }else{
        return false;
    }
}

/**
 * @param $url
 */
function redirect($url) {
    header("Location: {$url}");
    exit;
}

/**
 * @param $message
 */
function set_message($message) {
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    }else {
        $message = "";
    }
}

/**
 *
 */
function display_message() {
    if (isset($_SESSION['message'])) {
        echo   '<div class="alert alert-info alert-dismissible" style="color: black;" role="alert">
				<button type="button" class="close" data-dismiss="alert">
					<span aria-hidden="true">×</span><span class="sr-only">Close</span>
				</button>'.$_SESSION['message'].'
			</div>';
        unset($_SESSION['message']);
    }
}

/**
 * @return string
 */
function token_generator() {
    $token = $_SESSION["token"] = md5(uniqid("void_".mt_rand(), true));
    return $token;
}

//validate

/**
 *
 */
function validate_user_registration() {
    $errors = [];
    $min = 3;
    $max = 20;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstname = filter_input(INPUT_POST, "firstname");
        $lastname = filter_input(INPUT_POST,"lastname");
        $username = filter_input(INPUT_POST,"username");
        $email = filter_input(INPUT_POST,"email");
        $password = filter_input(INPUT_POST, "password");
        $confirm_pass = filter_input(INPUT_POST,"confirm_password");

        if (strlen($firstname) < $min) {
            $errors[] = "first name is too short, min of {$min} characters required {$firstname}";
        }

        if (strlen($firstname) > $max) {
            $errors[] = "first name is too long, max of {$max} characters allowed";
        }
        if (strlen($lastname) < $min) {
            $errors[] = "last name is too short, min of {$min} characters required";
        }
        if (strlen($lastname) > $max) {
            $errors[] = "last name is too long, max of {$max} characters allowed";
        }

        if (strlen($username) < $min) {
            $errors[] = "username is too short, min of {$min} characters required";
        }
        if (strlen($username) > $max) {
            $errors[] = "username is too long, max of  {$max} characters allowed";
        }


        if (username_exits($username)) {
            $errors[] = "username already taken";
        }

        if (email_exits($email)) {
            $errors[] = "email exist";
        }

        if ($password !== $confirm_pass) {
            $errors[] = "passwords doesn't match";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo validation_errors($error);
            }
        }else {
            if (register($firstname,$lastname,$username,$email,$password)) {
                set_message("A Verification Link has been sent to your email");
                redirect("index.php");
            }else {
                set_message("User Registration Failed");
                redirect("register.php");
            }
        }
    }
}

/**
 * @param $email
 * @param $subject
 * @param $msg
 * @param $headers
 * @return bool
 * @throws Exception
 */
function send_mail($email, $subject, $msg, $headers) {


    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                 // 2 = Enable verbose debug output
        $mail->isSMTP();                                   // Set mailer to use SMTP
        $mail->CharSet = "UTF-8";
        $mail->Host = Config::SMTP_HOST;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = Config::SMTP_USER;                 // SMTP username
        $mail->Password = Config::SMTP_PASSWORD;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = Config::SMTP_PORT;                                    // TCP port to connect to
        //sender
        $mail->setFrom("noreply@annonymous.tarz", "null");
        //recipient
        $mail->addAddress($email);
        $mail->addReplyTo("xheghun@outlook.com", "Xheghun");

        //Content
        $mail->isHTML(true);
        //  $mail->secureHeader($headers);// Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->setFrom("noreply@void.io", "Xheghun");
        if (!$mail->send()) {
            return false;
        }else {
            return true;
        }
    }catch (Exception $exception) {
        set_message("MAIL TRANSFER ERROR, check logs for details");
        $file = fopen("mail_error_log.txt","w");
        $time = strftime("%B-%M-%Y %H:%M:%S", time());
        $error = $time."\n".$exception."\n\n\n";
        fwrite($file,$error);
    }

}

//registration function
/**
 * @param $firstname
 * @param $lastname
 * @param $username
 * @param $email
 * @param $password
 * @return bool
 */
function register($firstname, $lastname, $username, $email, $password) {
    //escape data
    $firstname = escape($firstname);
    $lastname = escape($lastname);
    $email = escape($email);
    $username = escape($username);
    $password = escape($password);



    if (email_exits($email)) {
        return false;
    }
    elseif (username_exits($username)) {
        return false;
    }else {
        $password = password_hash($password,PASSWORD_BCRYPT,array('cost'=>12));
        $time = time();
        $validation_code = md5($username.microtime()) ;
        $f_time = strftime("%B-%d-%Y %H:%M:%S",$time);
        $sql = "INSERT INTO users(firstname,lastname,username,email,password,validation_code,active,time_added)
                VALUES ('$firstname','$lastname','$username','$email','$password','$validation_code','0','$f_time')";
        $result = query($sql);
        confirm($result);

        $subject = "ACCOUNT ACTIVATION(void.io)";
        $msg = "Please click to activate account";
        $msg .= <<<H
        <a class="btn btn-success btn-lg" href="http://localhost/Login_System(Standard)/activate.php?email=$email&code=$validation_code">ACTIVATE ACCOUNT</a>";
H;
        $header = "From: admin@void.io";
        //send mail
        if (send_mail($email,$subject,$msg,$header)) {
            $sql = "UPDATE users set verification_link_sent = 'yes' WHERE email = '$email'";
            $result =  query($sql);
            confirm($result);
            return true;
        }else{
            $sql = "UPDATE users SET verification_link_sent = 'no' WHERE email = '$email'";
            $result = query($sql);
            confirm($result);
            return false;
        }
    }
}

//Activate user
/**
 *
 */
function activate() {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["email"])) {
            $email = filter_input(INPUT_GET, "email");
            $code = filter_input(INPUT_GET,"code");


            $sql = "SELECT id FROM users WHERE email = '".escape($email)."' AND validation_code = '".escape($code)."'";
            $result = query($sql);
            confirm($result);
            if (row_count($result) == 1) {
                $sql = "UPDATE users SET active = 1, validation_code = 0 WHERE email = '$email' AND validation_code = '$code'";
                $result = query($sql);
                confirm($result);
                set_message("
                    <p class='bg-success' style='padding: 12px;'>
                    Your Account Has Been Activated
                    </p>");
                redirect("login.php");
            }
        }else {
            set_message("Sorry, There was a problem activating your account");
            redirect("register.php");
        }
    }
}

//validate login
/**
 *
 */
function validate_user_login() {
    $errors = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_input(INPUT_POST,"email");
        $password = filter_input(INPUT_POST,"password");
        $remember = filter_input(INPUT_POST,"remember");

        if (empty($email)) {
            $errors[] = "Email field required";
        }
        if (empty($password)) {
            $errors[] = "Password field required";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo validation_errors($error);
            }
        }else {
            if (login_user($email,$password,$remember)) {
                redirect("admin.php");
            }else {
                echo validation_errors("Account not registered");
            }
        }
    }
}

//login user
/**
 * @param $email
 * @param $password
 * @param $remember
 * @return bool
 */
function login_user($email, $password, $remember) {
    $sql = "SELECT password FROM users WHERE email = '$email' AND active = 1";
    $result = query($sql);
    if (row_count($result) == 1) {
        $row = fetch_array($result);
        $user_password = $row['password'];

        if (password_verify($password,$user_password)) {
            if ($remember == "on") {
                setcookie("email",$email,time() + 86400 * 6);
            }
            $_SESSION['email'] = $email;
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }
}

//logged in function
/**
 * @return bool
 */
function logged_in() {
    if (isset($_SESSION['email']) || isset($_COOKIE["email"])) {
        return true;
    }else{
        return false;
    }
}
//recover password
/**
 *
 */
function recover_password() {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {
            $email = escape(filter_input(INPUT_POST,"email"));
            if (email_exits($email)) {
                $validation_code = md5($email.microtime());
                $sql = "UPDATE users SET validation_code = '".escape($validation_code)."' WHERE email = '".escape($email)."'";
                $result = query($sql);
                confirm($result);
                setcookie("temp_access",$validation_code, time()+60*20);

                $subject = "Password Reset(void.io)";
                $body = "Please Use this code to reset your account password {$validation_code}
                            <br>";
                $body .= <<<H
    <a href="http://localhost/code.php?email=$email&code=$validation_code" class="btn btn-success btn-lg">RESET</a>
H;
                $header = "From: noreply@void.io";

                try {
                        if (!send_mail($email, $subject, $body, $header)) {
                            echo validation_errors("Error Sending Mail");
                        }else{
                            set_message("Please Check Your spam folder for your password reset code");
                            redirect("code.php");
                        }
                    } catch (Exception $e) {
                }
            }else {
                echo validation_errors("This Email Doesn't Exist");
            }
        }else {
            redirect("index.php");
        }
    }
}

//Code Validation
/**
 *
 */
function validate_code() {
    if (isset($_COOKIE["temp_access"])) {
        if (!isset($_GET['email']) && !isset($_GET['code'])) {
            redirect("index.php");
        } elseif(empty($_GET['email']) || empty($_GET['code'])) {
            redirect("index.php");
        }else {
            if (isset($_POST['code'])) {
                $email = clean($_GET["email"]);
                $code = clean($_POST["code"]);
                $sql = "SELECT id FROM users WHERE validation_code = '".escape($code)."' AND  email = '".escape($email)."'";
                $result = query($sql);
                confirm($result);
                if (row_count($result) == 1) {
                    setcookie('temp_access',$code, time() + 60*5);
                    redirect("reset.php?email=$email&validation_code=$code");
                }else {
                    set_message("Sorry We Can't Identify Your Validation Code");
                }
            }else {
                set_message("You're Lost");
            }
        }
    }else{
        set_message("Sorry Your Validation Code Has expired");
        redirect("recover.php");
    }
}
//Password Reset
function password_reset() {
    if (isset($_COOKIE['temp_access'])){
        if (isset($_GET['email']) && isset($_GET['validation_code'])) {
            if (isset($_SESSION['token']) && isset($_POST['token'])) {
                if ($_POST['token'] === $_SESSION['token']) {
                    $pass = filter_input(INPUT_POST,trim("password"));
                    $confirm_pass = filter_input(INPUT_POST,trim("confirm_password"));
                    if ($pass == $confirm_pass) {
                        $password = md5($pass);
                        $sql = "UPDATE users SET password = '".escape($password)."', validation_code = '0' WHERE email = '" . escape($_GET['email']) . "'";
                        $result = query($sql);
                        confirm($result);
                        set_message("Your Password Has been changed successfully");
                        redirect("login.php");
                    }else {
                        validation_errors("Password Doesn't match");
                        //redirect("reset.php");
                    }
                }
            }
        }
    }else {
        set_message("Sorry the page has expired");
        redirect("recover.php");
    }
}
//echo token_generator();