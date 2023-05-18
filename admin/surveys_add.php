<?php

if (!isset($_SESSION)) session_start();

// Not logged in
if (
    !isset($_SESSION['login_method']) ||
    !isset($_SESSION['email']) ||
    !isset($_SESSION['name']) ||
    !isset($_SESSION['id'])
) header("Location: /admin/login.html");

// Get input
$title = $_POST["title"];
$description = $_POST["description"];
$timespan = $_POST["timespan"];
if ($timespan == "true") {
    $timespan_start = $_POST["timespan_start"];
    $timespan_end = $_POST["timespan_end"];
} else {
    $timespan_start = null;
    $timespan_end = null;
}

// Check if input is valid
if (empty($title) || empty($description)) {
    echo "Please fill out all fields!";
    exit();
}

// Load config
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Conect to database
$con = mysqli_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);
if (mysqli_connect_errno()) exit("Error connecting to our database! Please try again later.");

// Insert user into database
if ($stmt = $con->prepare("INSERT INTO " . $config["db"]["tables"]["surveys"] . " (title, description, timespan_start, timespan_end) VALUES (?, ?, ?, ?)")) {
    $stmt->bind_param("ssss", $title, $description, $timespan_start, $timespan_end);
    $stmt->execute();
    $stmt->close();
    exit("success");
} else {
    echo "Error inserting user into database!";
    exit();
}
