<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

require_once 'translations.php';

$con = mysqli_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["password"], $config["db"]["name"]);
if (mysqli_connect_errno()) die("Failed to connect to MySQL: " . mysqli_connect_error());

$answers = $_POST["answers"];

$id_list = "";
foreach ($answers as $answer) {
    $id = $answer["id"];
    $id_list .= $id . ",";
}
$id_list = substr($id_list, 0, -1);

if ($stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["questions"] . " WHERE id IN (" . $id_list . ")")) {
    $stmt->execute();
    $result = $stmt->get_result();
    $questions = array();
    while ($row = $result->fetch_assoc()) {
        $questions[$row["id"]] = $row;
    }
    $stmt->close();
} else die("Failed to prepare MySQL statement.");


$errors = [];
foreach ($answers as $answer) {
    $id = $answer["id"];
    $question = null;
    foreach ($questions as $question_item) {
        if ($question_item["id"] == $id) {
            $question = $question_item;
            break;
        }
    }
    $params = json_decode($question["params"], true);
    if (isset($answer["answer"])) $value = $answer["answer"];

    $errors_in_answer = [];

    if ($question["required"] && (!isset($value) || $value == "")) {
        array_push($errors_in_answer, $translations["errors"]["required"]);
    } else switch ($question["type"]) {
        case "line":
        case "text":
            if ($question["required"] && strlen($value) == 0) array_push($errors_in_answer, $translations["errors"]["required"]);
            if (strlen($value) > $params["max"]) array_push($errors_in_answer, str_replace("%s", $params["max"], $translations["errors"]["max_length"]));
            if (strlen($value) < $params["min"]) array_push($errors_in_answer, str_replace("%s", $params["min"], $translations["errors"]["min_length"]));
            break;
        case "multiplechoice":
        case "toggle":
            if ($question["required"] && strlen($value) == 0) array_push($errors_in_answer, $translations["errors"]["required"]);
            if (!in_array($value, $params["options"])) array_push($errors_in_answer, $translations["errors"]["invalid_option"]);
            break;
        case "select":
            if ($question["required"] && strlen(json_encode($value)) == 0) array_push($errors_in_answer, $translations["errors"]["required"]);
            $countSelected = 0;
            foreach ($value as $value_item) {
                if (!in_array($value_item, $params["options"])) array_push($errors_in_answer, $translations["errors"]["invalid_option"]);
                $countSelected++;
            }
            if (isset($params["min"]) && $countSelected < $params["min"]) array_push($errors_in_answer, str_replace("%s", $params["min"], $translations["errors"]["min_count"]));
            if (isset($params["max"]) && $countSelected > $params["max"]) array_push($errors_in_answer, str_replace("%s", $params["max"], $translations["errors"]["max_count"]));
            break;
        case "dropdown":
            if ($question["required"] && strlen($value) == 0) array_push($errors_in_answer, $translations["errors"]["required"]);
            if (!in_array($value, $params["options"])) array_push($errors_in_answer, $translations["errors"]["invalid_option"]);
            break;
        case "date":
            if ($question["required"] && strlen($value) == 0) array_push($errors_in_answer, $translations["errors"]["required"]);
            if (strlen($value) > 0) if (!preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $value)) array_push($errors_in_answer, $translations["errors"]["invalid_date"]);
            if (isset($params["min"])) {
                $min_date = strtotime($params["min"]);
                $date = strtotime($value);
                if ($date < $min_date) array_push($errors_in_answer, str_replace("%s", $params["min"], $translations["errors"]["min_date"]));
            }
            if (isset($params["max"])) {
                $max_date = strtotime($params["max"]);
                $date = strtotime($value);
                if ($date > $max_date) array_push($errors_in_answer, str_replace("%s", $params["max"], $translations["errors"]["max_date"]));
            }
            break;
        case "time":
            if ($question["required"] && strlen($value) == 0) array_push($errors_in_answer, $translations["errors"]["required"]);
            if (strlen($value) > 0) if (!preg_match("/^(\d{2}):(\d{2})$/", $value)) array_push($errors_in_answer, $translations["errors"]["invalid_time"]);
            if (isset($params["min"])) {
                $min_time = strtotime($params["min"]);
                $time = strtotime($value);
                if ($time < $min_time) array_push($errors_in_answer, str_replace("%s", $params["min"], $translations["errors"]["min_time"]));
            }
            if (isset($params["max"])) {
                $max_time = strtotime($params["max"]);
                $time = strtotime($value);
                if ($time > $max_time) array_push($errors_in_answer, str_replace("%s", $params["max"], $translations["errors"]["max_time"]));
            }
            break;
        case "slider":
            if ($question["required"] && strlen($value) == 0) array_push($errors_in_answer, $translations["errors"]["required"]);
            if (strlen($value) > 0) if (!preg_match("/^(\d+)$/", $value)) array_push($errors_in_answer, $translations["errors"]["invalid_number"]);
            if (isset($params["min"])) {
                if ($value < $params["min"]) array_push($errors_in_answer, str_replace("%s", $params["min"], $translations["errors"]["min_number"]));
            }
            if (isset($params["max"])) {
                if ($value > $params["max"]) array_push($errors_in_answer, str_replace("%s", $params["max"], $translations["errors"]["max_number"]));
            }
            break;
        case "rating":
            if ($question["required"] && strlen($value) == 0) array_push($errors_in_answer, $translations["errors"]["required"]);
            if (strlen($value) > 0) if (!preg_match("/^(\d+)$/", $value)) array_push($errors_in_answer, $translations["errors"]["invalid_number"]);
            if (isset($params["min"])) {
                if ($value < $params["min"]) array_push($errors_in_answer, str_replace("%s", $params["min"], $translations["errors"]["min_number"]));
            }
            if (isset($params["max"])) {
                if ($value > $params["max"]) array_push($errors_in_answer, str_replace("%s", $params["max"], $translations["errors"]["max_number"]));
            }
            break;
    }

    if (count($errors_in_answer) > 0) {
        array_push($errors, array("id" => $question["id"], "errors" => $errors_in_answer));
    }
}

if (count($errors) != 0) {
    die(json_encode($errors));
}

// Create a sender_id that is unique (10 tries)
$sender_id = "";
for ($i = 0; $i < 10; $i++) {
    $sender_id = substr(str_shuffle(str_repeat($x = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 16);
    $stmt = $con->prepare("SELECT * FROM " . $config["db"]["tables"]["answers"] . " WHERE sender_id = ?");
    $stmt->bind_param("s", $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows == 0) break;
}

// Store answers in database 
foreach ($answers as $answer_obj) {
    $answer = $answer_obj["answer"];
    $question_id = $answer_obj["id"];

    if (is_array($answer)) $answer = json_encode($answer);

    $stmt = $con->prepare("INSERT INTO " . $config["db"]["tables"]["answers"] . " (sender_id, question, answer) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sender_id, $question_id, $answer);
    $stmt->execute();
    $stmt->close();
}

exit("success");
