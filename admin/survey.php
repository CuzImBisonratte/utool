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
        <div id="action_button-save"><i class="fas fa-save"></i></div>
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
            <i class="fa-solid fa-calendar"></i>
            <h3>Date</h3>
        </div>
        <div class="question-type">
            <i class="fa-solid fa-clock"></i>
            <h3>Time</h3>
        </div>
        <hr>
        <div class="question-type">
            <i class="fa-solid fa-sliders"></i>
            <h3>Slider</h3>
        </div>
        <div class="question-type">
            <i class="fa-solid fa-upload"></i>
            <h3>File</h3>
        </div>
        <div class="question-type">
            <i class="fa-solid fa-toggle-off"></i>
            <h3>Toggle</h3>
        </div>
        <div class="question-type">
            <i class="fa-solid fa-star-half-stroke"></i>
            <h3>Rating</h3>
        </div>
    </aside>
    <main>
        <div id="main">
            <div class="accent-color"></div>
            <div class="page-title">
                <?= $survey["title"] ?><div id="required">Answers to questions marked with * are required</div>
            </div>
            <?php
            if (isset($questions)) foreach ($questions as $question) {
                echo '<div class="question ' . $question["type"] . '">
                    <div class="question_title" id="question_title_' . $question["id"] . '">' . $question["title"] . '</div>';
                switch ($question["type"]) {
                    case 'line':
                        echo '<div class="question_area"><div class="question_line"><input type="text" placeholder="Your answer" id="question_' . $question["id"] . '"></div></div>';
                        break;
                    case 'text':
                        echo '<div class="question_area"><div class="question_text"><textarea placeholder="Your answer" id="question_' . $question["id"] . '"></textarea></div></div>';
                        break;
                    case 'multiplechoice':
                        echo '<div class="question_area"><div class="question_multiplechoice">';
                        foreach (json_decode($question["params"], true)["options"] as $option) {
                            echo '<div class="option"><input type="radio" name="question_' . $question["id"] . '" id="question_' . $question["id"] . '_' . $option . '"><label for="question_' . $question["id"] . '_' . $option . '">' . $option . '</label></div>';
                        }
                        echo '</div></div>';
                        break;
                    case 'select':
                        echo '<div class="question_area"><div class="question_select">';
                        foreach (json_decode($question["params"], true)["options"] as $option) {
                            echo '<div class="option"><input type="checkbox" name="question_' . $question["id"] . '" id="question_' . $question["id"] . '_' . $option . '"><label for="question_' . $question["id"] . '_' . $option . '">' . $option . '</label></div>';
                        }
                        echo '</div></div>';
                        break;
                    case 'dropdown':
                        echo '<div class="question_area"><div class="question_dropdown"><select id="question_' . $question["id"] . '">';
                        foreach (json_decode($question["params"], true)["options"] as $option) {
                            echo '<option value="' . $option . '">' . $option . '</option>';
                        }
                        echo '</select></div></div>';
                        break;
                    case 'date':
                        echo '<div class="question_area"><div class="question_date"><input type="date" id="question_' . $question["id"] . '"></div></div>';
                        break;
                    case 'time':
                        echo '<div class="question_area"><div class="question_time"><input type="time" id="question_' . $question["id"] . '"></div></div>';
                        break;
                    case 'slider':
                        echo '<div class="question_area"><div class="question_slider"><input type="range" id="question_' . $question["id"];
                        if (isset(json_decode($question["params"], true)["min"])) echo '" min="' . json_decode($question["params"], true)["min"];
                        if (isset(json_decode($question["params"], true)["max"])) echo '" max="' . json_decode($question["params"], true)["max"];
                        if (isset(json_decode($question["params"], true)["step"])) echo '" step="' . json_decode($question["params"], true)["step"];
                        echo '"></div></div>';
                        break;
                    case 'file':
                        echo '<div class="question_area">
                            <div class="question_file">
                                <label for="question_' . $question["id"] . '">Choose a file</label>
                                <input type="file" id="question_' . $question["id"] . '"';
                        if (isset(json_decode($question["params"], true)["accept"])) echo ' accept="' . json_decode($question["params"], true)["accept"] . '"';
                        echo '></div></div>';
                        break;
                    case 'toggle':
                        echo '<div class="question_area"><div class="question_toggle">';
                        foreach (json_decode($question["params"], true)["labels"] as $option) {
                            echo '<div class="option"><input type="radio" name="question_' . $question["id"] . '" id="question_' . $question["id"] . '_' . $option . '"><label for="question_' . $question["id"] . '_' . $option . '">' . $option . '</label></div>';
                        }
                        echo '</div></div>';
                        break;
                    case 'rating':
                        echo '<div class="question_area"><div class="question_rating">';
                        echo '<div class="rating_star" id="question_' . $question["id"] . '_1"><i class="fas fa-star"></i></div>';
                        echo '<div class="rating_star" id="question_' . $question["id"] . '_2"><i class="fas fa-star"></i></div>';
                        echo '<div class="rating_star" id="question_' . $question["id"] . '_3"><i class="fas fa-star"></i></div>';
                        echo '<div class="rating_star" id="question_' . $question["id"] . '_4"><i class="far fa-star"></i></div>';
                        echo '<div class="rating_star" id="question_' . $question["id"] . '_5"><i class="far fa-star"></i></div>';
                        echo '</div></div>';
                        break;
                    default:
                        break;
                }
                if ($question["required"] == 1) echo '<div class="required">*</div>';
                echo '<div class="options-container">';
                switch ($question["type"]) {
                    case 'rating':
                        echo '<div class="options">
                            <fieldset>
                                <legend>Default</legend>
                                <input type="number" id="question_' . $question["id"] . '_default" value="0">
                            </fieldset>
                        </div>';
                        break;
                    case 'line':
                    case 'text':
                        echo '<div class="validation">
                            <fieldset>
                                <legend>Min. number of characters</legend>
                                <input type="number" id="question_8_min" value="1">
                            </fieldset>
                            <fieldset>
                                <legend>Max. number of characters</legend>
                                <input type="number" id="question_8_max" value="5">
                            </fieldset>
                        </div>';
                        break;
                    case 'select':
                        echo '<div class="validation">
                            <fieldset>
                                <legend>Minimum selections</legend>
                                <input type="number" id="question_8_min">
                            </fieldset>
                            <fieldset>
                                <legend>Maximum selections</legend>
                                <input type="number" id="question_8_max">
                            </fieldset>
                        </div>';
                    case 'multiplechoice':
                    case 'dropdown':
                    case 'toggle':
                        echo '<div class="options">
                            <fieldset>
                                <legend>Options - Seperated by spaces';
                        if ($question["type"] === "toggle") echo ' (Max 3)';
                        echo '</legend>
                                <textarea id="question_5_options" cols="2">Option1 Option2 Option3</textarea>
                            </fieldset>
                        </div>';
                        break;
                    case 'date':
                    case 'time':
                        echo '<div class="validation">
                            <fieldset>
                                <legend>Min</legend>
                                <input type="' . $question["type"] . '" id="question_8_min" value="1">
                            </fieldset>
                            <fieldset>
                                <legend>Default</legend>
                                <input type="' . $question["type"] . '" id="question_8_max" value="3">
                            </fieldset>
                            <fieldset>
                                <legend>Max</legend>
                                <input type="' . $question["type"] . '" id="question_8_max" value="5">
                            </fieldset>
                        </div>';
                        break;
                    case 'slider':
                        echo '<div class="validation">
                            <fieldset>
                                <legend>Min</legend>
                                <input type="number" id="question_8_min" value="1">
                            </fieldset>
                            <fieldset>
                                <legend>Default</legend>
                                <input type="number" id="question_8_max" value="3">
                            </fieldset>
                            <fieldset>
                                <legend>Max</legend>
                                <input type="number" id="question_8_max" value="5">
                            </fieldset>
                        </div>';
                        break;
                    case 'file':
                        echo '<div class="validation">
                            <fieldset>
                                <legend>Allowed Types - Seperated by spaces</legend>
                                <input type="text" id="question_8_min" value="png txt jpg pdf">
                            </fieldset>
                            <fieldset>
                                <legend>Max</legend>
                                <input type="number" id="question_8_max" value="5">
                            </fieldset>
                        </div>';
                        break;
                    default:
                        break;
                }
                echo '<div class="updown"><i class="fas fa-chevron-up"></i><i class="fas fa-chevron-down"></i></div></div></div>';
            }
            ?>
        </div>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="survey.js"></script>
</body>

</html>