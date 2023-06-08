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

if ($survey["questions"] != NULL) if ($stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["questions"] . " WHERE id IN (" . implode(",", json_decode($survey["questions"])) . ")")) {
    $stmt->execute();
    $result = $stmt->get_result();
    $questions = array();
    while ($row = $result->fetch_assoc()) {
        $questions[$row["id"]] = $row;
    }
    $stmt->close();
    $questions_sorted = array();
    foreach (json_decode($survey["questions"]) as $question_id) {
        array_push($questions_sorted, $questions[$question_id]);
    }
    $questions = $questions_sorted;
} else die("Failed to prepare MySQL statement.");
if ($survey["questions"] != NULL) if ($stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["answers"] . " WHERE question IN (" . implode(",", json_decode($survey["questions"])) . ")")) {
    $stmt->execute();
    $result = $stmt->get_result();
    $answers = array();
    while ($row = $result->fetch_assoc()) {
        $answers[$row["sender_id"]] = $row;
    }
    $stmt->close();
} else die("Failed to prepare MySQL statement.");

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
    <link rel="stylesheet" href="results.css">
    <link rel="shortcut icon" href="/res/img/uTool.webp" type="image/x-icon">
</head>

<body>
    <div id="survey_id" style="display: none;"><?= $_GET['id'] ?></div>
    <nav>
        <div id="back-button" onclick="location.assign('../surveys.php');"><i class="fas fa-chevron-left"></i></div>
        <span type="text" id="nav-spacer"><?= $survey["title"] ?></span>
        <div id="action_button-results" onclick="location.assign('./?id=<?= $_GET['id'] ?>')" ;><i class="fas fa-chart-pie"></i></div>
    </nav>
    <main>
        <div id="main">
            <?php
            if (isset($questions)) foreach ($questions as $question) {
                $params = json_decode($question["params"], true);
                echo '<div class="question ' . $question["type"] . '"><div class="question_title" id="question_title_' . $question["id"] . '">' . $question["title"] . '</div>';
                if ($question["required"] == 1) echo '<div class="required">*</div>';
                switch ($question["type"]) {
                    case 'rating':
                        if (!isset($params["min"])) $params["min"] = 1;
                        if (!isset($params["max"])) $params["max"] = 5;
                        echo '<table class="rating_table">';
                        echo '<tr><th>Rating</th><th>Count</th></tr>';
                        for ($i = $params["min"]; $i <= $params["max"]; $i++) {
                            $rating = 0;
                            if (isset($answers)) foreach ($answers as $answer) {
                                if ($answer["question"] == $question["id"] && $answer["answer"] == $i) {
                                    $rating++;
                                }
                            }
                            echo '<tr><td>' . $i . '</td><td>' . $rating . '</td></tr>';
                        }
                        echo '</table>';
                        break;
                    case 'line':
                    case 'text':
                        break;
                    case 'select':
                    case 'multiplechoice':
                    case 'dropdown':
                    case 'toggle':
                        break;
                    case 'date':
                    case 'time':
                        break;
                    case 'slider':
                        break;
                    case 'file':
                        break;
                    default:
                        break;
                }
                echo '</div>';
            }
            ?>
        </div>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
</body>

</html>