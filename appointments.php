<?php
session_start();
require_once "includes/login_check.php";


//Get name from the SESSION
$username = $_SESSION['loggedInUser']['name'];
$account_type = $_SESSION['loggedInUser']['account_type'];

$hour = date('H');


if ($account_type == 1) {
    $user_type = "Bloedafname";
} elseif ($account_type == 2) {
    $user_type = "Teamleider";
} elseif ($account_type == 3) {
    $user_type = "POCT";
}

/** @var array $appointments */
/** @var array $db */

require_once 'includes/credentials.php';

$count_new_appointments = "SELECT COUNT(appointments.id) FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE `status` = 0;";

$items = mysqli_query($db, $count_new_appointments)
or die('Error ' . mysqli_error($db) . ' with query ' . $count_new_appointments);

while ($row_count = $items->fetch_assoc()) {
    $new_appointments = $row_count['COUNT(appointments.id)'];
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
        <li><a href="dashboard.php">home</a></li>
        <li><a>afspraken</a></li>
    </ul>

    <h1>Afspraken</h1>
    <section class="menu">
        <?php if ($account_type == 3) { ?>
            <a class="no-link" href="appointments-list.php?show=new">
                <?php if ($new_appointments > 0) { ?>
                    <p class="notifications"><?= $new_appointments ?></p>
                <?php } ?>
                <section class="menu-buttons menu-main-page">
                    <img class="logo" src="img/nieuwe_afspraken.webp" alt="nieuwe afspraken">
                    <p>nieuw</p>
                </section>
            </a>
            <a class="no-link" href="appointments-list.php?show=accepted">
                <section class="menu-buttons menu-main-page">
                    <img class="logo" src="img/geaccepteerde_afspraken.webp" alt="geaccepteerde afspraken">
                    <p>geaccepteerd</p>
                </section>
            </a>
            <a class="no-link" href="appointments-list.php?show=deleted">
                <section class="menu-buttons menu-main-page">
                    <img class="logo" src="img/verwijderde_afspraken.webp" alt="verwijderde afspraken">
                    <p>verwijderd</p>
                </section>
            </a>
            <a class="no-link" href="appointments-list.php?show=all">
                <section class="menu-buttons menu-other-page">
                    <img class="logo" src="img/alle_afspraken.webp" alt="alle afspraken">
                    <p>alles</p>
                </section>
            </a>
        <?php } ?>
        <a class="no-link" href="create-appointment.php">
            <section class="menu-buttons <?php if ($account_type == 3) { ?> menu-other-page <?php } else { ?> menu-main-page <?php } ?>">
                <img class="logo" src="img/afspraak_maken.webp" alt="maak afspraak">
                <p>plannen</p>
            </section>
        </a>
            <a class="no-link" href="my-appointments.php">
                <section class="menu-buttons <?php if ($account_type == 3) { ?> menu-other-page <?php } else { ?> menu-main-page <?php } ?>">
                    <img class="logo" src="img/mijn_afspraken.webp" alt="mijn afspraken">
                    <p>mijn afspraken</p>
                </section>
            </a>
    </section>
</body>
</html>