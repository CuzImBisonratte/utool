<?php

// Get input
$name = $_POST["name"];
$email = $_POST["email"];

// Check if input is valid
if (empty($name) || empty($email)) {
    echo "Please fill out all fields!";
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email address!";
    exit();
}

//Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/res/php/PHPMailer-master/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/res/php/PHPMailer-master/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/res/php/PHPMailer-master/src/SMTP.php';

// Load config
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Conect to database
$con = mysqli_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);
if (mysqli_connect_errno()) exit("Error connecting to our database! Please try again later.");

// Create 8 digit password using Chars 0-9 A-Z a-z !"$%&/+*~-_#
$password = "";
for ($i = 0; $i < 8; $i++) {
    $password .= substr("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!\"$&/+*~-_#", mt_rand(0, 71), 1);
}

// Hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
if ($stmt = $con->prepare("INSERT INTO " . $config["db"]["tables"]["users"] . " (name, email, password) VALUES (?, ?, ?)")) {
    $stmt->bind_param("sss", $name, $email, $hash);
    $stmt->execute();
    $stmt->close();
} else {
    echo "Error inserting user into database!";
    exit();
}

// Create an instance of PHPMailer
$mail = new PHPMailer();

try {
    // Server settings
    // $mail->SMTPDebug  = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                                //Send using SMTP
    $mail->Host       = $config["mail"]["host"];                    //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                       //Enable SMTP authentication
    $mail->Username   = $config["mail"]["username"];                //SMTP username
    $mail->Password   = $config["mail"]["password"];                //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                //Enable implicit TLS encryption
    $mail->Port       = $config["mail"]["port"];                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // Recipients
    $mail->setFrom($config["mail"]["sender_mail"], $config["mail"]["sender_displayname"]);
    $mail->addAddress($email, $name);                               //Add a recipient

    // Content
    $mail->isHTML(true);                                            //Set email format to HTML
    $mail->Subject = 'Account Creation';
    $mail->Body    = 'Welcome to the uTool instance on ' . $_SERVER['SERVER_NAME'] . '!<br>Your email is: ' . $email . '<br>Your password is: ' . $password . '<br><br>Have fun!<br><br>uTool';
    $mail->AltBody = 'Welcome to the uTool instance on ' . $_SERVER['SERVER_NAME'] . '!\nYour email is: ' . $email . '\nYour password is: ' . $password . '\n\nHave fun!\n\nuTool';

    // Disable debugging
    $mail->SMTPDebug = false;

    // Send mail
    $mail->send();

    exit("success");
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
