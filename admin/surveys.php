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

if ($stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["surveys"])) {
    $stmt->execute();
    $result = $stmt->get_result();
    $surveys = $result->fetch_all(MYSQLI_ASSOC);
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
    <link rel="stylesheet" href="surveys.css">
</head>

<body>
    <div class="overlays">
        <div class="overlay add_survey" id="add_survey">A</div>
    </div>
    <nav>
        <div class="menu">
            <div class="Dashboard" onclick="location.assign('./');"><span><i class="fas fa-tachometer-alt"></i> Dashboard</span></div>
            <div class="surveys" onclick="location.assign('./surveys.php');"><span><i class="fas fa-poll"></i> Surveys</span></div>
            <div class="users" onclick="location.assign('./users.php');"><span><i class="fas fa-users"></i> Users</span></div>
            <div class="settings" onclick="location.assign('./settings.php');"><span><i class="fas fa-cog"></i> Settings</span></div>
        </div>
        <div class="account"></div>
    </nav>
    <main>
        <div class="accent-color"></div>
        <div class="page-title">
            Surveys
            <div class="add-survey-button" onclick="openOverlay('add_survey');"><i class="fas fa-plus"></i> Add survey</div>
        </div>
        <div class="survey_container table-top-container">
            <div class="survey_status"></div>
            <div class="survey_title">Title</div>
            <div class="survey_timespan">Timespan</div>
            <div class="survey_answers">Answers</div>
            <div class="vellip_container"></div>
        </div>
        <div class="survey-list">
            <?php
            foreach ($surveys as $survey) {
                if ($survey["status"] === 0) $status = "red";
                else if ($survey["status"] === 1) $status = "lightgreen";
                else if ($survey["status"] === 2) $status = "yellow";
                if ($survey["answers"] === 0) $answers = "-";
                else $answers = $survey["answers"];
                if (strtotime($survey["timespan_start"]) == 0) $survey["timespan_start"] = "";
                else $survey["timespan_start"] = date("d.m.Y", strtotime($survey["timespan_start"]));
                if (strtotime($survey["timespan_end"]) == 0) $survey["timespan_end"] = "";
                else $survey["timespan_end"] = date("d.m.Y", strtotime($survey["timespan_end"]));
                if ($survey["timespan_start"] == "" && $survey["timespan_end"] == "") $timespan_empty = " timespan_empty";
                else $timespan_empty = "";
                echo '<div class="survey_container">
                    <div class="survey_status" style="color: ' . $status . '"><i class="fas fa-circle"></i></div>
                    <div class="survey_title">' . $survey["title"] . '</div>
                    <div class="survey_timespan' . $timespan_empty . '">' . $survey["timespan_start"] . ' -<br>' . $survey["timespan_end"] . '</div>
                    <div class="survey_answers">' . $answers . '</div>
                    <div class="vellip_container">&vellip;</div>
                </div>';
            }
            ?>
        </div>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="overlay.js"></script>
</body>

</html>