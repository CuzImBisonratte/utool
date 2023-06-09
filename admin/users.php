<?php

if (!isset($_SESSION)) session_start();

// Not logged in
if (
    !isset($_SESSION['login_method']) ||
    !isset($_SESSION['email']) ||
    !isset($_SESSION['name']) ||
    !isset($_SESSION['id'])
) header("Location: /admin/login.html");

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$con = mysqli_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);
if (mysqli_connect_errno()) die("Failed to connect to MySQL: " . mysqli_connect_error());

if ($stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["users"])) {
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="users.css">
    <link rel="shortcut icon" href="/res/img/uTool.webp" type="image/x-icon">
</head>

<body>
    <div class="overlays">
        <div class="overlay add_user" id="add_user">
            <h1>Add user</h1>
            <div class="form">
                <input type="text" name="name" id="name" placeholder="Max Mustermann">
                <input type="email" name="email" id="email" placeholder="max.muster@mann.de">
                <input type="submit" value="Add User" onclick="addUser();">
            </div>
        </div>
    </div>
    <nav>
        <div class="menu">
            <div class="Dashboard" onclick="location.assign('./');"><span><i class="fas fa-tachometer-alt"></i> Dashboard</span></div>
            <div class="surveys" onclick="location.assign('./surveys.php');"><span><i class="fas fa-poll"></i> Surveys</span></div>
            <div class="users" onclick="location.assign('./users.php');"><span><i class="fas fa-users"></i> Users</span></div>
            <div class="settings" onclick="location.assign('./settings.php');"><span><i class="fas fa-cog"></i> Settings</span></div>
        </div>
    </nav>
    <main>
        <div class="accent-color"></div>
        <div class="page-title">
            Users
            <div class="add-user-button" onclick="openOverlay('add_user');"><i class="fas fa-plus"></i> Add User</div>
        </div>
        <div class="user_container table-top-container">
            <div class="user_initials"></div>
            <div class="user_name">Name</div>
            <div class="user_email">EMail</div>
            <div class="user_lastlogin">Last login</div>
            <div class="vellip_container"></div>
        </div>
        <div class="user-list">
            <?php
            foreach ($users as $user) {
                echo '<div class="user_container">';
                echo '<div class="user_initials"><div>';
                $names = explode(" ", $user["name"]);
                foreach ($names as $name) {
                    echo substr($name, 0, 1);
                }
                echo '</div></div>';
                echo '<div class="user_name">' . $user["name"] . '</div>';
                echo '<div class="user_email">' . $user["email"] . '</div>';
                echo '<div class="user_lastlogin">' . $user["lastlogin"] . '</div>';
                echo '<div class="vellip_container">&vellip;</div>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="overlay.js"></script>
    <script src="users.js"></script>
</body>

</html>