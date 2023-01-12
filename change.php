<?php
session_start();
require_once "includes/login_check.php";

// Make sure the user is allowed to view the page
$user_type = $_SESSION['loggedInUser']['account_type'];

/** @var array $db */

require_once 'includes/credentials.php';

$today = date('Y-m-d');
$current_time = date("H:i:s");

$delete_old_appointments = "UPDATE `appointments` SET `status` = '2', `delete_reason` = 'Afspraak verstreken.' WHERE TIMESTAMP(`date`, `time`) < '$today $current_time' AND `status` != 2;";
mysqli_query($db, $delete_old_appointments);


$index = htmlentities($_GET['index']);
$view = htmlentities($_GET['view']);
$page = htmlentities($_GET['page']);
$max_items_per_page = htmlentities($_GET['max-items']);
$sort = htmlentities($_GET['sort']);
$sortmethod = htmlentities($_GET['sortby']);
$search = htmlentities($_GET['search']);
$search_type = htmlentities($_GET['searchtype']);
$show = htmlentities($_GET['show']);

if (!isset($index)) {
    $index = htmlentities($_POST['id']);
}

$query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id  WHERE appointments.id = $index";

$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$data = mysqli_fetch_assoc($result);

if (!isset($data['id'])) {
    header("Location: details.php?show=$show&search=$search&searchtype=$search_type&index=$index&view=$index&page=$page&max-items=$max_items_per_page&sortby=$sortmethod&sort=$sort");
}

$errors = [];
if(isset($_POST['submit'])) {
    $location = mysqli_escape_string($db, $_POST['location']);
    $date = mysqli_escape_string($db, $_POST['date']);
    $time = mysqli_escape_string($db, $_POST['time']);
    $amount = mysqli_escape_string($db, $_POST['amount']);
    $comments = mysqli_escape_string($db, $_POST['comments']);
    $status = mysqli_escape_string($db, $_POST['status']);
    $delete_reason = mysqli_escape_string($db, $_POST['delete_reason']);

    if ($location == '') {
        $errors['location'] = "De locatie is niet gespecificeerd.";
    }
    if ($date == '') {
        $errors["date"] = "De datum is niet gespecificeerd.";
    }
    if ($time == '') {
        $errors["time"] = "De tijd is niet gespecificeerd.";
    }
    if ($amount == '') {
        $errors["amount"] = "De hoeveelheid is niet gespecificeerd.";
    }
    if ($status == '') {
        $errors["state"] = "De status is niet gespecificeerd.";
    }

    if (empty($errors)) {
        $sql = "UPDATE `appointments` SET location = '$location', date = '$date', time = '$time', amount = '$amount', comments = '$comments', delete_reason = '$delete_reason', status = '$status' WHERE id = '$index';";
        if (mysqli_query($db, $sql)) {
            header("Location: details.php?show=$show&search=$search&searchtype=$search_type&index=$index&view=$index&page=$page&max-items=$max_items_per_page&sortby=$sortmethod&sort=$sort");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
        exit;
    } else {
        $errors['error'] = 'Er is iets fout gegaan';
    }
}

if ($data['date'] <= $today && $data['time'] < $current_time) {
    $errors["state"] = "De status kan niet worden aangepast omdat de afspraak in het verleden ligt. Oude afspraken zoals deze worden automatisch verwijderd.";
}

if (isset($data['id'])) {
    $title = "Details van ongeaccepteerde reservering met ID " . $data['id'];
} else {
    $title = 'Het lijkt erop dat deze pagina niet werkt. Probeer het nog een keer of neem contact op met de beheerder.';
}

//Get name from the SESSION
$account_type = $_SESSION['loggedInUser']['account_type'];

if ($account_type == 1) {
    $user_type = "Bloedafname";
} elseif ($account_type == 2) {
    $user_type = "Teamleider";
} elseif ($account_type == 3) {
    $user_type = "POCT";
}

if ($show == 'new') {
    $breadcrumb = 'Nieuwe Afspraken';
}
if ($show == 'deleted') {
    $breadcrumb = 'Verwijderde Afspraken';
}
if ($show == 'accepted') {
    $breadcrumb = 'Geaccepteerde Afspraken';
}
if ($show == 'all') {
    $breadcrumb = 'Alle Afspraken';
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
    <link rel="stylesheet" type="text/css" href="css/appointments.css"/>
    <link rel="stylesheet" type="text/css" href="css/home.css"/>
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
        <li><a href="appointments-list.php?show=<?=$show ?>"><?php echo strtolower($breadcrumb);?></a></li>
        <li><a>details</a></li>
    </ul>
    <h1 class="title"><?=$title ?></h1>
    <p>Deze pagina is voor het laatst vernieuwd op <?= $today . ", " . $current_time ?></p>
    <form action="" method="post">
    <table class="styled-table detail-table">
        <thead>
        <tr>
            <th class="detail-table">Gegevens</th>
            <th class="detail-table">Data</th>
            <?php if (!empty($errors)) { ?>
            <th class="detail-table">Error</th>
            <?php } ?>
        </tr>
        </thead>
        <?php if(isset($data['name'])) { ?>
            <tr>
                <th>Naam: </th>
                <th><?= $data['name'] . " " . $data['last_name']?></th>
                <?php if (!empty($errors)) { ?>
                    <th class="errors"></th>
                <?php } ?>
            </tr>
            <tr>
                <th>email: </th>
                <th><?= $data['email']?></th>
                <?php if (!empty($errors)) { ?>
                    <th class="errors"></th>
                <?php } ?>
            </tr>
        <?php } else { ?>
            <tr>
                <th>Error: </th>
                <th><div class="code">Geen account gelinkt met afspraak. Neem contact op met de locatie.</div></th>
                <?php if (!empty($errors)) { ?>
                    <th class="errors"></th>
                <?php } ?>
            </tr>
        <?php } ?>
        <tr>
            <th><label class="label" for="location">Locatie</label></th>
            <th><input class="input" id="location" type="text" name="location" value="<?= htmlentities($data['location']) ?>"/></th>
            <?php if (!empty($errors)) { ?>
            <th class="errors detail-table"><?php if (isset($errors["location"])) { echo $errors["location"]; }}?></th>
        </tr>
        <tr>
            <th><label class="label" for="date">Datum</label></th>
            <th><input class="input" id="date" type="text" name="date" value="<?= htmlentities($data['date']) ?>"/></th>
            <?php if (!empty($errors)) { ?>
            <th class="errors detail-table"><?php if (isset($errors["date"])) { echo $errors["date"]; }}?></th>
        </tr>
        <tr>
            <th><label class="label" for="time">Tijd</label></th>
            <th><input class="input" id="time" type="text" name="time" value="<?= htmlentities($data['time']) ?>"/></th>
            <?php if (!empty($errors)) { ?>
            <th class="errors detail-table"><?php if (isset($errors["time"])) { echo $errors["time"]; }}?></th>
        </tr>
        <tr>
            <th><label class="label" for="amount">Hoeveelheid</label></th>
            <th><input class="input" id="year" type="amount" name="amount" value="<?= htmlentities($data['amount']) ?>"/></th>
            <?php if (!empty($errors)) { ?>
            <th class="errors detail-table"><?php if (isset($errors["amount"])) { echo $errors["amount"]; }}?></th>
        </tr>
            <tr>
                <th><label class="label" for="comments">Opmerkingen</label></th>
                <th><input class="input" id="comments" type="text" name="comments" value="<?= htmlentities($data['comments']) ?>"/></th>
                <?php if (!empty($errors)) { ?>
                    <th class="errors"></th>
                <?php } ?>
            </tr>
        <tr>
            <th>ID:</th>
            <th><?= $data['id'] ?></th>
            <?php if (!empty($errors)) { ?>
                <th class="errors"></th>
            <?php } ?>
        </tr>
        <?php if ($account_type == 3) {?>
        <tr class="admin-details">
            <th><label class="label" for="status">Status</label></th>
            <th><select name="status" id="status" <?php if ($data['date'] <= $today && $data['time'] < $current_time) { ?> disabled <?php } ?>>
            <?php for ($i = 0; $i < 3; $i++) {
            if ($i == $data['status']) { ?>
                <option selected value="<?=$i?>"><?php if ($i == 0) { ?> Ongeaccepteerd <?php } if ($i == 1) { ?> Geaccepteerd <?php } if ($i == 2) { ?> Verwijderd <?php }?></option>
            <?php } else {?>
                <option value="<?=$i?>"><?php if ($i == 0) { ?> Ongeaccepteerd <?php } if ($i == 1) { ?> Geaccepteerd <?php } if ($i == 2) { ?> Verwijderd <?php }?></option>
            <?php } } ?>
                </select></th>
            <?php if ($data['date'] <= $today && $data['time'] < $current_time) { ?>
                <th class="errors detail-table" rowspan="2" ><div class="code">De status kan niet worden aangepast omdat de afspraak in het verleden ligt. Oude afspraken zoals deze worden automatisch verwijderd.</div></th>
            <?php } else {
                if (!empty($errors)) {?>
            <th class="errors detail-table" > <?php if (isset($errors["state"])) { echo $errors["state"]; }}?></th>
            <?php } ?>
        </tr>
        <tr class="admin-details">
            <th><label class="label" for="delete_reason">Reden tot verwijderen</label></th>
            <th><input class="input" id="delete_reason" type="text" name="delete_reason" value="<?= htmlentities($data['delete_reason']) ?>" <?php if ($data['date'] <= $today && $data['time'] < $current_time) { ?> disabled <?php } ?>/></th>
            <?php if (!empty($errors)) { ?>
                <th class="errors"></th>
            <?php } ?>
        </tr>
        <tr class="admin-details">
            <th>Wergegeven op pagina:</th>
            <th><?=$page + 1?></th>
            <?php if (!empty($errors)) { ?>
                <th class="errors"></th>
            <?php } ?>
        </tr>
        <tr class="admin-details">
            <th>Index van die pagina:</th>
            <th><?= $view ?></th>
            <?php if (!empty($errors)) { ?>
                <th class="errors"></th>
            <?php }
        } ?>
        </tr>
        </tbody>
    </table>
    <input id="id" name="id" hidden value="<?=$index?>">
        <button class="button accept" type="submit" name="submit" id="submit">Opslaan</button>
        <a href="details.php?show=<?=$show ?>&search=<?=$search ?>&searchtype=<?=$search_type ?>&index=<?= $index ?>&view=<?= $view ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sortmethod ?>&sort=<?=$sort?>"><button type="button" class="button deny">Niet opslaan.</button></a>
    </form>
</section>
</body>
</html>
