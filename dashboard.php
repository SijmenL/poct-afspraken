<?php
session_start();
require_once "includes/login_check.php";


//Get name from the SESSION
$username = $_SESSION['loggedInUser']['name'];
$account_type = $_SESSION['loggedInUser']['account_type'];

$hour = date('H');


if ($hour >= 20) {
    $greetings = "Goedenacht";
} elseif ($hour > 17) {
    $greetings = "Goedenavond";
} elseif ($hour > 11) {
    $greetings = "Goedemiddag";
} elseif ($hour < 12) {
    $greetings = "Goedenmorgen";
}

if ($account_type == 1) {
    $user_type = "Bloedafname";
} elseif ($account_type == 2) {
    $user_type = "Teamleider";
} elseif ($account_type == 3) {
    $user_type = "POCT";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check Star-shl</title>
    <link rel="stylesheet" type="text/css" href="css/home.css"/>
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.webmanifest">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#142d49">
    <meta name="msapplication-TileColor" content="#142d49">
    <meta name="theme-color" content="#142d49">

</head>

<header>
    <section class="logos">
        <img class="logo" src="img/check_logo.webp" alt="check logo">
        <p class="role"><?= $user_type ?></p>
    </section>
    <section class="account-settings">
        <div class="dropdown">
        <img class="profile-picture" src="img/profile_picture.webp" alt="profile_picture">
            <div class="dropdown-content">
                <a href="settings.php">Mijn Account</a>
                <a target="_blank" href="https://www.star-shl.nl/zorgverlener/">Star-shl</a>
                <hr>
                <b><a href="logout.php">Log uit</a></b>
            </div>
        </div>
    </section>
</header>

<body>
<section class="text-content">

    <ul class="breadcrumb">
        <li><a>home</a></li>
    </ul>

    <h1><?= $greetings ?>, <?= $username ?></h1>
    <section class="menu">
        <a class="no-link" href="appointments.php">
            <section class="menu-buttons menu-main-page">
                <img class="logo" src="img/kalender.webp" alt="Afspraken">
                <p>afspraken</p>
            </section>
        </a>
        <a class="no-link" href="notifications.php">
            <p class="notifications">29</p>
            <section class="menu-buttons menu-main-page">
                <img class="logo" src="img/alert.webp" alt="Meldingen">
                <p>meldingen</p>
            </section>
        </a>
        <?php if ($account_type == 1 || $account_type == 3) {?>
        <a class="no-link" href="day_jobs.php">
            <p class="notifications">1</p>
            <section class="menu-buttons menu-main-page">
                <img class="logo" src="img/day_jobs.webp" alt="Dagtaken">
                <p>dagtaken</p>
            </section>
        </a>
        <?php }
        if ($account_type == 3) {?>
        <a class="no-link" href="accounts.php">
            <section class="menu-buttons menu-other-page">
                <img class="logo" src="img/account.webp" alt="Accounts">
                <p>accounts</p>
            </section>
        </a>
        <a class="no-link" href="pages.php">
            <section class="menu-buttons menu-other-page">
                <img class="logo" src="img/pages.webp" alt="Pagina's">
                <p>pagina's</p>
            </section>
        </a>
        <?php } ?>
        <a class="no-link" href="settings.php">
            <section class="menu-buttons menu-other-page">
                <img class="logo" src="img/settings.webp" alt="Instellingen">
                <p>instellingen</p>
            </section>
        </a>
    </section>
</section>
</body>
</html>