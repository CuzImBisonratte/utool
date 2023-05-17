<?php

// Get input
$email = $_POST["email"];
$password = $_POST["password"];

// Check if input is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email address!";
    exit();
}

// Load config
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Conect to database
$con = mysqli_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);
if (mysqli_connect_errno()) exit("Error connecting to our database! Please try again later.");

// Get id, salt and password hash from database
if ($stmt = $con->prepare("SELECT id, name, password FROM " . $config["db"]["tables"]["users"] . " WHERE email = ?")) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $password_hash);
    $stmt->fetch();

    // Check if password is right
    if (!password_verify($password, $password_hash)) exit("Wrong password");

    // Set session variables
    session_start();
    $_SESSION["login_method"] = "login";
    $_SESSION["id"] = $id;
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;

    // Update last login
    if ($stmt = $con->prepare("UPDATE " . $config["db"]["tables"]["users"] . " SET lastlogin = NOW() WHERE id = ?")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();

        // Redirect to app
        header("Location: /admin/");
    }
}
