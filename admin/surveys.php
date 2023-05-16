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
            <div class="survey_container">
                <div class="survey_status" style="color: red"><i class="fas fa-circle"></i></div>
                <div class="survey_title">I am a test title</div>
                <div class="survey_timespan">-</div>
                <div class="survey_answers">42</div>
                <div class="vellip_container">&vellip;</div>
            </div>
            <div class="survey_container">
                <div class="survey_status" style="color: lightgreen"><i class="fas fa-circle"></i></div>
                <div class="survey_title">Nice?</div>
                <div class="survey_timespan">15.05.2023 -<br>15.12.2023</div>
                <div class="survey_answers">69</div>
                <div class="vellip_container">&vellip;</div>
            </div>
            <div class="survey_container">
                <div class="survey_status" style="color: lightgreen"><i class="fas fa-circle"></i></div>
                <div class="survey_title">Test survey name</div>
                <div class="survey_timespan">23.03.2023 -<br>23.03.2024</div>
                <div class="survey_answers">5</div>
                <div class="vellip_container">&vellip;</div>
            </div>
            <div class="survey_container">
                <div class="survey_status" style="color: yellow"><i class="fas fa-circle"></i></div>
                <div class="survey_title">Automated Survey heh</div>
                <div class="survey_timespan">15.08.2023 -<br>04.11.2023</div>
                <div class="survey_answers">-</div>
                <div class="vellip_container">&vellip;</div>
            </div>
        </div>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="overlay.js"></script>
</body>

</html>