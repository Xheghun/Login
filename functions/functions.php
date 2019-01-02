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
        $firstname = trim(filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS));
        $lastname = trim(filter_input(INPUT_POST,"lastname", FILTER_SANITIZE_SPECIAL_CHARS));
        $username = trim(filter_input(INPUT_POST,"username", FILTER_SANITIZE_SPECIAL_CHARS));
        $email = trim(filter_input(INPUT_POST,"email", FILTER_SANITIZE_EMAIL));
        $password = trim(filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS));
        $confirm_pass = trim(filter_input(INPUT_POST,"confirm_password", FILTER_SANITIZE_SPECIAL_CHARS));

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
        //$mail->Body =  $mail->msgHTML(file_get_contents('activation_mail_template.php'),__DIR__);
        $mail->Body = $mail->msgHTML($msg);
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

        $msg = <<<HTML
        <!DOCTYPE html>
        <html>
            <head>
            
            </head>
            <body>
                <div class="mail" style="width: 100%;">    
                        <div style="
                                color: white;
                                -moz-border-radius-topleft: 4px;
                                -moz-border-radius-topright: 4px;
                                margin-left: 5%;
                                margin-right: 5%;
                                padding: 5px;
                                text-align: center;
                                background-color: crimson;
                                font-family: 'Arial Narrow';
                            ">
                            <h2 >Hello {$username}</h2>
                        </div>
                        <div class="body" style=" margin-left: 10%;
                            margin-right: 10%;
                            text-align: left;
                            padding: 4px;
                            font-family: Calibri;
                            font-weight: bold;
                            line-height: 0.3in;">
                            <p>we noticed you recently registered with us.
                                <br>you are one step away from activating your projectX account, please click on the link below to activate your account.
                                <br/>
                            </p>
                        </div>
                        <div 
                            style="
                                margin-left: 8%;
                                margin-right: 8%;
                                text-align: center;"
                        >
                            <a href="http://localhost:8888/Login_System(Standard)/activate.php?email={$email}&code={$validation_code}"class="button"
                                style="color: white;
                                        background-color: crimson;
                                        font-weight: bolder;
                                        border: 0;
                                        padding: 25px;
                                        text-decoration: none;
                                        border-radius: 4px;"
                            >Activate</a>
                        </div>
                    </div>
                </center>
            </body>
        </html>
             
HTML;

        $subject = "ACCOUNT ACTIVATION (void.io)";

        $header = "From: admin@void.io";
        //send mail
        if (send_mail($email,$subject,$msg,$header)) {
            $sql = "UPDATE users set verification_link_sent = 'yes' WHERE email = '$email'";
            $result =  query($sql);
            confirm($result);
            return true;
        }else{
            $sql = "DELETE FROM users WHERE email = '$email'";
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
            $email = trim(filter_input(INPUT_GET, "email", FILTER_SANITIZE_EMAIL));
            $code = trim(filter_input(INPUT_GET,"code", FILTER_SANITIZE_SPECIAL_CHARS));


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
        $email = trim(filter_input(INPUT_POST,"email", FILTER_SANITIZE_EMAIL));
        $password = trim(filter_input(INPUT_POST,"password", FILTER_SANITIZE_SPECIAL_CHARS));
        $remember = trim(filter_input(INPUT_POST,"remember", FILTER_SANITIZE_SPECIAL_CHARS));

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
            $email = trim(filter_input(INPUT_POST,"email", FILTER_SANITIZE_EMAIL));
            if (email_exits($email)) {
                $validation_code = md5($email.microtime());
                $sql = "UPDATE users SET validation_code = '".escape($validation_code)."' WHERE email = '".escape($email)."'";
                $result = query($sql);
                confirm($result);
                setcookie("temp_access",$validation_code, time()+60*20);

                $subject = "Password Reset(void.io)";
               $body = <<<HTML
                <html>
                <head>
                   
                </head>
                <body>
                <div class="mail" style=" width: 100%;">
                    <div class="heading" style="
                            color: white;
                            -moz-border-radius-topleft: 4px;
                            -moz-border-radius-topright: 4px;
                            margin-left: 5%;
                            margin-right: 5%;
                            padding: 5px;
                            text-align: center;
                            background-color: crimson;
                            font-family: 'Arial Narrow';
                            ">
                        <p style="font-size: 2em;
                            font-weight: bold;">Password Reset confirmation</p>
                    </div>
                    <div  style=" margin-left: 10%;
                            margin-right: 10%;
                            text-align: left;
                            padding: 4px;
                            font-family: Calibri;
                            line-height: 0.3in;">
                        <p>Hello user we recently received request to  reset your ProjectX account password with us.
                            <br/>please use this code to reset your password <b>{$validation_code}</b>
                            <br>if this wasn't you, we suggest you <a href="http://xheghun-projectx.000webhostapp.com">change</a>  your password.
                        </p>
                    </div>
                    <div class="button" style="margin-left: 8%;
                            margin-right: 8%;
                            text-align: center;">
                        <a style="color: white;
                            padding: 25px;
                            background-color: crimson;
                            font-weight: bolder;
                            border: 0;
                            margin: 10px;
                            text-decoration: none;
                            border-radius: 4px;" href="http://localhost:8888/code.php?email=$email&code=$validation_code" class="link">Reset</a>
                    </div>
                
                </div>
                </body>
                </html>

HTML;
                $header = "From: noreply@void.io";

                try {
                        if (!send_mail($email, $subject, $body, $header)) {
                            echo validation_errors("Error Sending Mail");
                        }else{
                            set_message("Please Check Your spam folder for your password reset code");
                            redirect("recover.php");
                        }
                    } catch (Exception $e) {
                }
            }else {
                echo validation_errors("This Email Doesn't Exist");
            }
        }else {
            set_message("Token not set");
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
                    $pass = trim(filter_input(INPUT_POST,trim("password"), FILTER_SANITIZE_SPECIAL_CHARS));
                    $confirm_pass = trim(filter_input(INPUT_POST,trim("confirm_password"), FILTER_SANITIZE_SPECIAL_CHARS));
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


//change password function
function change_password() {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['submit'])) {
            $oldpass = filter_input(INPUT_POST, "oldpass", FILTER_SANITIZE_SPECIAL_CHARS);
            $newpass = filter_input(INPUT_POST, "newpass", FILTER_SANITIZE_SPECIAL_CHARS);
            $cnewpass = filter_input(INPUT_POST, "cnewpass", FILTER_SANITIZE_SPECIAL_CHARS);

            if ($newpass !== $cnewpass) {
                set_message("Passwords don't match");
                redirect("change_password.php");
            } else {
                $sql = "SELECT password FROM users WHERE email = '" . escape($_SESSION["email"]) . "' OR email = '" . $_COOKIE["email"] . "'";
                $result = query($sql);
                confirm($result);
                if (row_count($result) == 1) {
                    $row = fetch_array($result);
                    $existing_password = $row['password'];
                    if (password_verify($oldpass, $existing_password)) {
                        $hashed_newpass = password_hash(PASSWORD_BCRYPT, $newpass);
                        $sql = "UPDATE users SET  password = '" . escape($hashed_newpass) . "' WHERE email = '" . escape($_SESSION["email"]) . "' OR email = '" . $_COOKIE["email"] . "'";
                        $result = query($sql);
                        confirm($result);
                        set_message("Password Has Been Updated");
                        return true;
                    }
                } else {
                    set_message("Wrong Password <b>Have You forgotten your password?</b> <a href='recover.php'>reset</a>");
                    return false;
                }
            }
        }
        else {
                set_message("Token Not Set");
                //redirect("change_password.php");
            }
        } else {
            set_message("invalid request");
            //redirect("change_password.php");
        }
}
//echo token_generator();