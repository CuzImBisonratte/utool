<?php

// echo "success";
// echo json_encode($_POST);

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

if ($stmt = $con->prepare("UPDATE " . $config["db"]["tables"]["surveys"] . " SET title = ? WHERE id = ?")) {
    $stmt->bind_param('si', $_POST['survey_title'], $_GET['survey_id']);
    $stmt->execute();
    $stmt->close();

    foreach ($_POST["questions"] as $item) {
        // item.options.options = ["a","b","c",""] => ["a","b","c"]
        if (isset($item['options']['options'])) $item['options']['options'] = array_filter($item['options']['options']);
        if (isset($item['options'])) $item['options'] = json_encode($item['options']);
        if ($stmt = $con->prepare("UPDATE " . $config["db"]["tables"]["questions"] . " SET title = ?, type = ?, params = ? WHERE id = ?")) {
            $stmt->bind_param('sssi', $item['title'], $item['type'], $item['options'], $item['id']);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "error";
        }
    }
} else {
    echo "error";
}
