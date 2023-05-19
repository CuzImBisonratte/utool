<?php

if (!isset($_GET["id"])) die("No survey ID specified.");

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$con = mysqli_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);
if (mysqli_connect_errno()) die("Failed to connect to MySQL: " . mysqli_connect_error());

if ($stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["surveys"] . " WHERE id = ?")) {
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) die("Survey not found.");
    $survey = $result->fetch_assoc();
    $stmt->close();
} else die("Failed to prepare MySQL statement.");

// survey["questions"] is a list of qustion IDs | Format: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
if ($stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["questions"] . " WHERE id IN (" . implode(",", json_decode($survey["questions"])) . ")")) {
    $stmt->execute();
    $result = $stmt->get_result();
    $questions = array();
    while ($row = $result->fetch_assoc()) {
        $questions[$row["id"]] = $row;
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
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="/res/img/uTool.webp" type="image/x-icon">
</head>

<body>
    <main>
        <div class="accent-color"></div>
        <div class="page-title"><?= $survey["title"] ?><div id="required">Answers to questions marked with * are required</div>
        </div>
        <?php

        foreach ($questions as $question) {
            echo '<div class="question ' . $question["type"] . '">
                <div class="question_title">' . $question["title"] . '</div>';
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
                    echo '<div class="rating_star" id="question_' . $question["id"] . '_1" onclick="rate(' . $question["id"] . ', 1)"><i class="fas fa-star"></i></div>';
                    echo '<div class="rating_star" id="question_' . $question["id"] . '_2" onclick="rate(' . $question["id"] . ', 2)"><i class="fas fa-star"></i></div>';
                    echo '<div class="rating_star" id="question_' . $question["id"] . '_3" onclick="rate(' . $question["id"] . ', 3)"><i class="fas fa-star"></i></div>';
                    echo '<div class="rating_star" id="question_' . $question["id"] . '_4" onclick="rate(' . $question["id"] . ', 4)"><i class="far fa-star"></i></div>';
                    echo '<div class="rating_star" id="question_' . $question["id"] . '_5" onclick="rate(' . $question["id"] . ', 5)"><i class="far fa-star"></i></div>';
                    echo '</div></div>';
                    break;
                default:
                    break;
            }
            if ($question["required"] == 1) echo '<div class="required">*</div>';
            echo '</div>';
        }
        ?>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
</body>

</html>