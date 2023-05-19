<?php

if (!isset($_GET['id'])) {
    header("Location: surveys.php");
    exit();
}

if (!isset($_SESSION)) session_start();
if (
    !isset($_SESSION['login_method']) ||
    !isset($_SESSION['email']) ||
    !isset($_SESSION['name']) ||
    !isset($_SESSION['id'])
) header("Location: /admin/login.html");

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$con = mysqli_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);
if (mysqli_connect_errno()) die("Failed to connect to MySQL: " . mysqli_connect_error());

if ($stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["surveys"] . " WHERE id = ?")) {
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        header("Location: surveys.php");
        exit();
    }
    $survey = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Failed to prepare statement: " . $con->error);
}


?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | uTool</title>
    <link rel="stylesheet" href="/res/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/res/fontawesome/css/solid.min.css">
    <link rel="stylesheet" href="/res/css/fonts.css">
    <link rel="stylesheet" href="/res/css/main.css">
    <link rel="stylesheet" href="survey.css">
    <link rel="shortcut icon" href="/res/img/uTool.webp" type="image/x-icon">
</head>

<body>
    <nav>
        <div id="back-button" onclick="location.assign('surveys.php');"><i class="fas fa-chevron-left"></i></div>
        <input type="text" id="nav-spacer" value="<?= $survey["title"] ?>"></input>
        <div id="action_button-edit"><i class="fas fa-save"></i></div>
        <div id="action_button-visibility"><i class="fas fa-eye"></i></div>
        <div id="action_button-delete"><i class="fas fa-trash-can"></i></div>
    </nav>
    <aside>
        <div class="plusicon"><i class="fas fa-plus"></i></div>
        <div class="question-type">
            <i class="fas fa-minus"></i>
            <h3>One-Liner</h3>
        </div>
        <div class="question-type">
            <i class="fas fa-align-left"></i>
            <h3>Text</h3>
        </div>
        <hr>
        <div class="question-type">
            <i class="fas fa-circle-dot"></i>
            <h3>Choose (One)</h3>
        </div>
        <div class="question-type">
            <i class="fas fa-square-check"></i>
            <h3>Choose (Multiple)</h3>
        </div>
        <div class="question-type">
            <i class="fa-solid fa-circle-chevron-down"></i>
            <h3>Dropdown</h3>
        </div>
        <hr>
        <div class="question-type">
            <i class="fa-solid fa-sliders"></i>
            <h3>Slider</h3>
        </div>
        <hr>
        <div class="question-type">
            <i class="fa-solid fa-upload"></i>
            <h3>File</h3>
        </div>
        <div class="question-type">
            <i class="fa-solid fa-calendar"></i>
            <h3>Date</h3>
        </div>
        <div class="question-type">
            <i class="fa-solid fa-clock"></i>
            <h3>Time</h3>
        </div>
    </aside>
    <main>
    </main>
</body>

</html>