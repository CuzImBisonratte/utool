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
</head>

<body>
    <div class="overlays">
        <div class="overlay add_user" id="add_user">A</div>
    </div>
    <nav>
        <div class="menu">
            <div class="Dashboard" onclick="location.assign('./');"><span><i class="fas fa-tachometer-alt"></i> Dashboard</span></div>
            <div class="users" onclick="location.assign('./users.php');"><span><i class="fas fa-poll"></i> Surveys</span></div>
            <div class="users" onclick="location.assign('./users.php');"><span><i class="fas fa-users"></i> Users</span></div>
            <div class="settings" onclick="location.assign('./settings.php');"><span><i class="fas fa-cog"></i> Settings</span></div>
        </div>
    </nav>
    <main>
        <div class="accent-color"></div>
        <div class="page-title">
            Users
            <div class="add-user-button" onclick="openOverlay('add_user');"><i class="fas fa-plus"></i> Add user</div>
        </div>
        <div class="user_container table-top-container">
            <div class="user_initials"></div>
            <div class="user_name">Name</div>
            <div class="user_email">EMail</div>
            <div class="user_lastlogin">Last login</div>
            <div class="vellip_container"></div>
        </div>
        <div class="user-list">
            <div class="user_container">
                <div class="user_initials">
                    <div>BB</div>
                </div>
                <div class="user_name">Beate Beispielname</div>
                <div class="user_email">beate@beispielmail.de</div>
                <div class="user_lastlogin">16.05.2023<br>21:49</div>
                <div class="vellip_container">&vellip;</div>
            </div>
            <div class="user_container">
                <div class="user_initials">
                    <div>MM</div>
                </div>
                <div class="user_name">Margarete Musterfrau</div>
                <div class="user_email">m.musterfrau@mustermail.de</div>
                <div class="user_lastlogin">01.01.1970<br>00:00</div>
                <div class="vellip_container">&vellip;</div>
            </div>
        </div>
    </main>
    <script src="/res/js/jquery/jquery-3.6.1.min.js"></script>
    <script src="overlay.js"></script>
</body>

</html>