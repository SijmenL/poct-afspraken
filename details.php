<?php
session_start();
require_once "includes/login_check.php";

// Make sure the user is allowed to view the page
$user_type = $_SESSION['loggedInUser']['account_type'];


/** @var array $appointment */
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


$query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id  WHERE appointments.id = $index";
$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$data = mysqli_fetch_assoc($result);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/appointments.css">
    <link rel="stylesheet" href="css/home.css">
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
    <h1 class="title"> <?= $title;?> </h1>
    <p>Deze pagina is voor het laatst vernieuwd op <?= $today . ", " . $current_time ?></p>

    <?php if (isset($data['id'])) { ?>
        <section class="content">
            <table class="styled-table detail-table">
                <thead>
                <tr>
                    <th class="detail-table">Gegevens</th>
                    <th class="detail-table">Data</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($data['name'])) { ?>
                <tr>
                    <th>Naam: </th>
                    <th><?= htmlentities($data['name']) . " " . htmlentities($data['last_name'])?></th>
                </tr>
                <tr>
                    <th>email: </th>
                    <th><?= htmlentities($data['email'])?></th>
                </tr>
                    <?php } else { ?>
                <tr>
                    <th>Error: </th>
                    <th><div class="code">Geen account gelinkt met afspraak. Neem contact op met de locatie.</div></th>
                </tr>
                <?php } ?>
                <tr>
                    <th>Locatie: </th>
                    <th><?= htmlentities($data['location'])?></th>
                </tr>
                <tr>
                    <th>Datum:</th>
                    <th><?=htmlentities($data['date'])?></th>
                </tr>
                <tr>
                    <th>Tijd:</th>
                    <th><?=htmlentities($data['time'])?></th>
                </tr>
                <tr>
                    <th>Hoeveelheid:</th>
                    <th><?= htmlentities($data['amount'])?></th>
                </tr>
                <?php if ($data['comments'] !== "") { ?>
                <tr>
                    <th>Opmerkingen:</th>
                    <th><?= htmlentities($data['comments'])?></th>
                </tr>
                <?php } ?>
                <tr>
                    <th>ID:</th>
                    <th><?= htmlentities($data['id']) ?></th>
                </tr>
                <?php if ($account_type == 3) {?>
                <tr class="admin-details">
                    <th>Status:</th>
                    <?php if ($data['status'] == 0){?> <th>Ongeaccepteerd</th> <?php } if ($data['status'] == 1){?> <th>Geaccepteerd</th> <?php } if ($data['status'] == 2){?> <th>Verwijderd</th> <?php } ?>
                </tr>
                    <?php if ($data['delete_reason'] !== "") { ?>
                        <tr class="admin-details">
                            <th>Reden tot verwijderen:</th>
                            <th><?= htmlentities($data['delete_reason'])?></th>
                        </tr>
                    <?php } ?>
                    <tr class="admin-details">
                    <th>Wergegeven op pagina:</th>
                    <th><?=$page + 1?></th>
                </tr>
                <tr class="admin-details">
                    <th>Index van die pagina:</th>
                    <th><?= $view ?></th>
                </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="buttons">
                <a href="appointments-list.php?show=<?=$show ?>&search=<?=$search ?>&searchtype=<?=$search_type ?>&view=<?= $index ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sortmethod ?>&sort=<?=$sort?>#<?= $view ?>"><button class="button normal">Ga terug naar de lijst</button></a>
                <a href="change.php?show=<?=$show ?>&search=<?=$search ?>&searchtype=<?=$search_type ?>&index=<?= $index ?>&view=<?= $view ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sortmethod ?>&sort=<?=$sort?>"><button class="button normal">Wijzig</button></a>
            </div>
        </section>

<?php } else { ?>
    <p>Er is iets misgegaan.</p>
    <p><i>De link die je hebt ingetypt verwijst naar een reservering die niet bestaat. Je probeert de details van afspraakwf <b>#<?= $index ?></b> te beijken, maar die is niet bekend.</i></p>
    <a href="appointments-list.php"><button class="button normal">Ga terug</button></a>
<?php } ?>
</section>
</body>
</html>
