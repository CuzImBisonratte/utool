<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

require_once 'translations.php';

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $survey["title"] ?> | uTool</title>
    <link rel="stylesheet" href="/res/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/res/fontawesome/css/regular.min.css">
    <link rel="stylesheet" href="/res/css/fonts.css">
    <link rel="stylesheet" href="/res/css/main.css">
    <link rel="stylesheet" href="thankyou.css">
    <link rel="shortcut icon" href="/res/img/uTool.webp" type="image/x-icon">
</head>

<body>
    <main>
        <div class="circle"><i class="far fa-check-circle"></i></div>
        <h1><?= $translations["thankyou"]["title"] ?></h1>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="submit.js"></script>
</body>

</html>