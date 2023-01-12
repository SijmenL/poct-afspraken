<?php
session_start();
require_once "includes/login_check.php";

//Get name from the SESSION
$account_type = $_SESSION['loggedInUser']['account_type'];
$account_id = $_SESSION['loggedInUser']['id'];


// Make sure the user is allowed to view the page
$user_type = $_SESSION['loggedInUser']['account_type'];


require_once 'includes/credentials.php';
/** @var array $db */

if (!isset($account)) {
    $account = $account_id;
}

$errors = [];
if (isset($_POST['submit'])) {
    $location = $_POST['location'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $amount = $_POST['amount'];
    $comments = $_POST['comments'];
    $account = $_POST['account'];

    if ($location == '') {
        $errors['location'] = 'Locatie is niet ingevuld';
    }
    if ($date == '') {
        $errors['date'] = 'Datum is niet gezet';
    }
    if ($time == '') {
        $errors['time'] = 'Tijd is niet gezet';
    }
    if ($amount == '') {
        $errors['amount'] = 'Hoeveelheid is niet ingevuld';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO `appointments` (id, account_id, location, date, time, amount, comments, status) VALUES (NULL, '$account', '$location', '$date', '$time', '$amount', '$comments', 0)";
        if (mysqli_query($db, $sql)) {
            echo "Data is toegevoegd aan de database.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
        header("Location: appointments-list.php");
        exit;
    } else {
        $errors['error'] = 'Er is iets fout gegaan';
    }
}
require_once 'includes/credentials.php';

$query = "SELECT * FROM `accounts`;";

$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$accounts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $accounts[] = $row;
}


if ($account_type == 1) {
    $user_type = "Bloedafname";
} elseif ($account_type == 2) {
    $user_type = "Teamleider";
} elseif ($account_type == 3) {
    $user_type = "POCT";
}

mysqli_close($db);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/home.css"/>
    <link rel="stylesheet" type="text/css" href="css/appointments.css"/>
    <title>Check Star-shl</title>
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
        <li><a href="appointments.php">afspraken</a></li>
        <li><a>plannen</a></li>
    </ul>
    <h1 class="title">Afspraak Inplannen</h1>
    <p>Bij onduidelijkheden kan de afspraak komen te vervallen of kunt u om verduidelijking gevraagd worden, telefonisch
        of per mail.</p>
    <section class="form">
        <form action="create-appointment.php" method="post">
            <?php if ($account_type > 1) { ?>
            <h2>Account</h2>
            <p>Kies het account dat deze afspraak heeft aangemaakt, de afspraak komt dan bijvoorbeeld tussen de dagtaken
                van dat account te staan. Als u geen account kiest wordt de afspraak op uw naam ingepland.</p>
            <div class="account-table">
                <table class="styled-table">
                    <thead>
                    <tr>
                        <th>Kies</th>
                        <th class="web-only">ID</th>
                        <th>Naam</th>
                        <th class="web-only">Achternaam</th>
                        <th>Mail</th>
                        <th class="web-only">Functie</th>
                    </tr>
                    </thead>
                    <?php foreach ($accounts as $index => $data_point) { ?>
                        <tr>
                            <?php if ($account == $data_point['id']) { ?>
                                <td><input class="" type="radio" id="<?= $data_point['id'] ?>"
                                           value="<?= $data_point['id'] ?>" name="account" id="account"
                                           checked="checked"></td>
                            <?php } else { ?>
                                <td><input type="radio" id="<?= $data_point['id'] ?>" value="<?= $data_point['id'] ?>"
                                           name="account" id="account"></td>
                            <?php } ?>
                            <td class="web-only"><label for="<?= $data_point['id'] ?>"><?= $data_point['id'] ?></label>
                            </td>
                            <td><label for="<?= $data_point['id'] ?>"><?= $data_point['name'] ?></label></td>
                            <td class="web-only"><label
                                        for="<?= $data_point['id'] ?>"><?= $data_point['last_name'] ?></label></td>
                            <td><label for="<?= $data_point['id'] ?>"><?= $data_point['email'] ?></label></td>
                            <td class="web-only"><label
                                        for="<?= $data_point['id'] ?>"><?php if ($data_point['account_type'] == 1) {
                                        echo "Bloedafname";
                                    }
                                    if ($data_point['account_type'] == 2) {
                                        echo "Teamleider";
                                    }
                                    if ($data_point['account_type'] == 3) {
                                        echo "POCT";
                                    } ?></label></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php } else { ?>
                    <input type="hidden" id="<?= $account_id ?>" value="<?= $account_id ?>" name="account" id="account">
            <?php } ?>
            <h2>Locatie & tijd</h2>
            <section class="form-item form-column">
                <label class="label" for="location">Locatie</label>
                <input class="input styled-field" id="location" type="location" name="location"
                       value="<?= $location ?? '' ?>"/>
                <p class="error">
                    <?= $errors['location'] ?? '' ?>
                </p>
                <p>Als locatie kunt u een adres, of bijvoorbeeld een priklocatie invullen, zolang het een duidelijke
                    plek is.</p>

            </section>
            <section class="form-item form-column">
                <label class="label" for="date">Datum</label>
                <input class="input styled-field" id="date" type="date" name="date"
                       value="<?= $date ?? '' ?>"/>
                <p class="error">
                    <?= $errors['date'] ?? '' ?>
                </p>
            </section>
            <section class="form-item form-column">
                <label class="label" for="time">Tijd</label>
                <input class="styled-field" type="time" id="time" name="time" value="<?= isset($time) ? $time : '' ?>">
                <p class="error">
                    <?= $errors['time'] ?? '' ?>
                </p>
                <p>Als er geen tijden weergegeven worden, kan het zijn dat die dag al volgepland zit of dat er niemand
                    beschikbaar is. </p>
            </section>
            <h2>Andere zaken</h2>
            <section class="form-item form-column">
                <label class="label" for="amount">Hoeveelheid coagucheks te controleren</label>
                <input class="input styled-field" id="amount" type="number" max="25" name="amount"
                       value="<?= $amount ?? '' ?>"/>
                <p class="error">
                    <?= $errors['amount'] ?? '' ?>
                </p>
            </section>
            <section class="form-item form-column">
                <label class="label" for="comments">Opmerkingen</label>
                <input class="input styled-field" id="comments" type="text" name="comments"
                       value="<?= $comments ?? '' ?>"/>
            </section>
            <button class="button normal" type="submit" name="submit">Save</button>
            <p class="error">
                <?= $errors['error'] ?? '' ?>
            </p>
        </form>
    </section>
</body>
</html>
