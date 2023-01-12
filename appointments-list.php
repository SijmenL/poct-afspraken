<?php
session_start();
require_once "includes/login_check.php";

//Get name from the SESSION
$account_type = $_SESSION['loggedInUser']['account_type'];

if ($account_type < 3) {
    header("Location: appointments.php");
}

/** @var array $appointments */
/** @var array $db */

require_once 'includes/credentials.php';

// Search type
if (isset($_GET['searchtype'])) {
    $search_type = $_GET['searchtype'];
} else {
    $search_type = 'all';
}

if (isset($_GET['show'])) {
    $show = $_GET['show'];
} else {
    $show = 'new';
}

// Search function
if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = '';
}

// set if it is a reverse order method or a normal order method.
if (isset($_GET['sort'])) {
    $sortmethod = $_GET['sort'];
} else {
    $sortmethod = '0';
}

// set the sorting mehtod
if (isset($_GET['sortby'])) {
    $sort = $_GET['sortby'];
} else {
    $sort = 'appointments.id';
}

// set the page your on
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = '0';
}
// set the page your on
if (isset($_GET['max-items'])) {
    $max_items_per_page = $_GET['max-items'];
    if ($max_items_per_page <= 0) {
        $max_items_per_page = 1;
    }
} else {
    $max_items_per_page = '5';
}

// set the item that was last edited
if (isset($_GET['view'])) {
    $view = $_GET['view'];
} else {
    $view = -1;
}

if ($show == 'new') {
    $status_type = 0;
}
if ($show == 'accepted') {
    $status_type = 1;
}
if ($show == 'deleted') {
    $status_type = 2;
}

$today = date('Y-m-d');
$current_time = date("H:i:s");

$delete_old_appointments = "UPDATE `appointments` SET `status` = '2', `delete_reason` = 'Afspraak verstreken.' WHERE TIMESTAMP(`date`, `time`) < '$today $current_time' AND `status` != 2;";
mysqli_query($db, $delete_old_appointments);

$needed_page = $page * $max_items_per_page;

if ($sortmethod == 0) {
    if ($search_type == 'all') {
        if ($show !== 'all') {
            $query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE appointments.status = '$status_type' AND (appointments.id LIKE '%$search%' OR accounts.name LIKE '%$search%' OR accounts.last_name LIKE '%$search%' OR accounts.email LIKE '%$search%' OR appointments.location LIKE '%$search%' OR appointments.date LIKE '%$search%' OR appointments.time LIKE '%$search%' OR appointments.amount LIKE '%$search%' OR appointments.comments LIKE '%$search%' OR appointments.delete_reason LIKE '%$search%') ORDER BY $sort LIMIT $max_items_per_page OFFset $needed_page;";
        } else {
            $query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE (appointments.id LIKE '%$search%' OR accounts.name LIKE '%$search%' OR accounts.last_name LIKE '%$search%' OR accounts.email LIKE '%$search%' OR appointments.location LIKE '%$search%' OR appointments.date LIKE '%$search%' OR appointments.time LIKE '%$search%' OR appointments.amount LIKE '%$search%' OR appointments.comments LIKE '%$search%' OR appointments.delete_reason LIKE '%$search%') ORDER BY $sort LIMIT $max_items_per_page OFFset $needed_page;";
        }
    } else {
        if ($show !== 'all') {
            $query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE appointments.status = '$status_type' AND $search_type LIKE '%$search%' ORDER BY $sort LIMIT $max_items_per_page OFFset $needed_page;";
        } else {
            $query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE $search_type LIKE '%$search%' ORDER BY $sort LIMIT $max_items_per_page OFFset $needed_page;";
        }
    }
    // $sortmethod == 1
} else {
    if ($search_type == 'all') {
        if ($show !== 'all') {
            $query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE appointments.status = '$status_type' AND (appointments.id LIKE '%$search%' OR accounts.name LIKE '%$search%' OR accounts.last_name LIKE '%$search%' OR accounts.email LIKE '%$search%' OR appointments.location LIKE '%$search%' OR appointments.date LIKE '%$search%' OR appointments.time LIKE '%$search%' OR appointments.amount LIKE '%$search%' OR appointments.comments LIKE '%$search%' OR appointments.delete_reason LIKE '%$search%') ORDER BY $sort DESC LIMIT $max_items_per_page OFFset $needed_page;";
        } else {
            $query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE (appointments.id LIKE '%$search%' OR accounts.name LIKE '%$search%' OR accounts.last_name LIKE '%$search%' OR accounts.email LIKE '%$search%' OR appointments.location LIKE '%$search%' OR appointments.date LIKE '%$search%' OR appointments.time LIKE '%$search%' OR appointments.amount LIKE '%$search%' OR appointments.comments LIKE '%$search%' OR appointments.delete_reason LIKE '%$search%') ORDER BY $sort DESC LIMIT $max_items_per_page OFFset $needed_page;";
        }
    } else {
        if ($show !== 'all') {
            $query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE appointments.status = '$status_type' AND $search_type LIKE '%$search%' ORDER BY $sort DESC LIMIT $max_items_per_page OFFset $needed_page;";
        } else {
            $query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.email, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.delete_reason, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE $search_type LIKE '%$search%' ORDER BY $sort DESC LIMIT $max_items_per_page OFFset $needed_page;";
        }
    }
}

// Pagination
if ($search_type == 'all') {
    if ($show !== 'all') {
        $count = "SELECT COUNT(appointments.id) FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id  WHERE `status` = '$status_type' AND (appointments.id LIKE '%$search%' OR accounts.name LIKE '%$search%' OR accounts.last_name LIKE '%$search%' OR accounts.email LIKE '%$search%' OR appointments.location LIKE '%$search%' OR appointments.date LIKE '%$search%' OR appointments.time LIKE '%$search%' OR appointments.amount LIKE '%$search%' OR appointments.comments LIKE '%$search%' OR appointments.delete_reason LIKE '%$search%')";
    } else {
        $count = "SELECT COUNT(appointments.id) FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id  WHERE (appointments.id LIKE '%$search%' OR accounts.name LIKE '%$search%' OR accounts.last_name LIKE '%$search%' OR accounts.email LIKE '%$search%' OR appointments.location LIKE '%$search%' OR appointments.date LIKE '%$search%' OR appointments.time LIKE '%$search%' OR appointments.amount LIKE '%$search%' OR appointments.comments LIKE '%$search%' OR appointments.delete_reason LIKE '%$search%')";
    }
} else {
    if ($show !== 'all') {
        $count = "SELECT COUNT(appointments.id) FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id  WHERE `status` = '$status_type' AND $search_type LIKE '%$search%';";
    } else {
        $count = "SELECT COUNT(appointments.id) FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id  WHERE $search_type LIKE '%$search%';";
    }
}

$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$items = mysqli_query($db, $count)
or die('Error ' . mysqli_error($db) . ' with query ' . $count);

while ($row_count = $items->fetch_assoc()) {
    $database_length = $row_count['COUNT(appointments.id)'];
}

$appointments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $appointments[] = $row;
}


$title = 'Er is iets mis gegaan.';
if ($show == 'new') {
    $title = 'Nieuwe Afspraken';
}
if ($show == 'deleted') {
    $title = 'Verwijderde Afspraken';
}
if ($show == 'accepted') {
    $title = 'Geaccepteerde Afspraken';
}
if ($show == 'all') {
    $title = 'Alle Afspraken';
}


$pagination = (ceil($database_length / $max_items_per_page));

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
    <title>Check Star-shl</title>
    <link rel="stylesheet" type="text/css" href="css/home.css"/>
    <link rel="stylesheet" type="text/css" href="css/appointments.css"/>
    <link rel="stylesheet" type="text/css" href="css/table.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <li><a><?php echo strtolower($title); ?></a></li>
    </ul>
    <h1>Overzicht <?= $title ?></h1>
    <?php if ($show !== 'deleted') { ?>
    <p>Deze pagina is voor het laatst vernieuwd op <?= $today . ", " . $current_time ?></p>
    <?php } ?>
    <form class="search-area" action="appointments-list.php" method="GET">
        <section class="form-item">
            <input class="search-field" onchange="this.form.submit()" type="text" id="search" name="search"
                   value="<?= $search ?>" placeholder="Zoeken...">
        </section>
        <section class="form-item">
            <select class="<?php if ($search_type == 'all') { ?> no-filter-field <?php } else { ?> filter-field <?php } ?>"
                    onchange="this.form.submit()" id="searchtype" name="searchtype">
                <option <?php if ($search_type == 'all') { ?> selected <?php } ?> value="all">Geen filter</option>
                <option <?php if ($search_type == 'accounts.name') { ?> selected <?php } ?> value="accounts.name">Naam
                </option>
                <option <?php if ($search_type == 'accounts.last_name') { ?> selected <?php } ?>
                        value="accounts.last_name">Achternaam
                </option>
                <option <?php if ($search_type == 'accounts.email') { ?> selected <?php } ?> value="accounts.email">
                    E-email
                </option>
                <option <?php if ($search_type == 'appointments.location') { ?> selected <?php } ?>
                        value="appointments.location">Locatie
                </option>
                <option <?php if ($search_type == 'appointments.date') { ?> selected <?php } ?>
                        value="appointments.date">Datum
                </option>
                <option <?php if ($search_type == 'appointments.time') { ?> selected <?php } ?>
                        value="appointments.time">Tijd
                </option>
                <option <?php if ($search_type == 'appointments.amount') { ?> selected <?php } ?>
                        value="appointments.amount">Hoeveelheid
                </option>
                <option <?php if ($search_type == 'appointments.comments') { ?> selected <?php } ?>
                        value="appointments.comments">Opmerking
                </option>
            </select>
        </section>
        <?php if ($search_type !== 'all') {
            $show = 'all';
        } ?>
        <input type="hidden" id="max-items" name="max-items" value="<?= $max_items_per_page ?>" hidden>
        <input type="hidden" id="show" name="show" value="<?= $show ?>">
    </form>
    <?php if ($show !== 'all' && $search !== '') { ?>
        <p><i>Je hebt misschien meer resultaten als je <a
                        href="appointments-list.php?show=all&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=0&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>">alle
                    afspraken weergeeft.</a></i></p>
    <?php } ?>
    <table class="styled-table center">
        <thead>
        <tr>
            <th class="normal-row"><a class="sort"
                                      href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=id&sort=<?php if ($sort == 'id' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">ID<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
            </th>
            <?php if ($show == 'all') { ?>
                <th class="normal-row"><a class="sort"
                                          href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=status&sort=<?php if ($sort == 'status' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">Status<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
                </th> <?php } ?>
            <th class="normal-row"><a class="sort"
                                      href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=name&sort=<?php if ($sort == 'name' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">Naam<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
            </th>
            <th class="normal-row"><a class="sort"
                                      href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=last_name&sort=<?php if ($sort == 'last_name' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">Achternaam<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
            </th>
            <th class="location"><a class="sort"
                                    href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=location&sort=<?php if ($sort == 'location' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">Locatie<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
            </th>
            <th class="date-table"><a class="sort"
                                      href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=date&sort=<?php if ($sort == 'date' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">Datum<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
            </th>
            <th class="normal-row"><a class="sort"
                                      href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=time&sort=<?php if ($sort == 'time' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">Time<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
            </th>
            <th class="normal-row"><a class="sort"
                                      href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=amount&sort=<?php if ($sort == 'amount' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">Hoeveelheid<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
            </th>
            <th class="comments">Opmerkingen</th>
            <?php if ($show == 'all' || $show == 'deleted') { ?>
                <th class="normal-row"><a class="sort"
                                          href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&sortby=delete_reason&sort=<?php if ($sort == 'delete_reason' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?= $max_items_per_page ?>">Verwijder reden<?php if ($sortmethod == 0) { ?>&#11014; <?php } else { ?>&#11015;<?php } ?></a>
                </th> <?php } ?>
            <th class="button-table">Opties</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="6">
                <section class="pagination" id="pagination">
                    <?php if ($page >= 1 && $page <= $pagination - 2 && $pagination > 2) { ?>
                        <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=0&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">&#10094;
                            &#10094;</a>
                        <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page - 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">&#10094;</a>
                        <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page - 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page ?></a>
                        <a class="active"
                           href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page + 1 ?></a>
                        <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page + 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page + 2 ?></a>
                        <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page + 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">&#10095;</a>
                        <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $pagination - 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">&#10095;
                            &#10095;</a>
                    <?php } else {
                        if ($page < 1 && $pagination > 2) { ?>
                            <a class="active"
                               href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page + 1 ?></a>
                            <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page + 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page + 2 ?></a>
                            <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page + 2 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page + 3 ?></a>
                            <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page + 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">&#10095;</a>
                            <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $pagination - 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">&#10095;
                                &#10095;</a>
                        <?php }
                        if ($page >= 1 && $page <= $pagination - 1 && $pagination > 2) { ?>
                            <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=0&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">&#10094;
                                &#10094;</a>
                            <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page - 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">&#10094;</a>
                            <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page - 2 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page - 1 ?></a>
                            <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page - 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page ?></a>
                            <a class="active"
                               href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination"><?= $page + 1 ?></a>
                        <?php }
                        if ($pagination <= 2 && $pagination != 1) {
                            if ($page == 0) { ?>
                                <a class="active"
                                   href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">1</a>
                                <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page + 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">2</a>
                            <?php } else { ?>
                                <a href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page - 1 ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">1</a>
                                <a class="active"
                                   href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>#pagination">2</a>
                            <?php }
                        }
                    } ?>
                </section>
                <form action="appointments-list.php#pagination" method="get">
                    <section class="form-item">
                        <?php if ($pagination > 3) { ?>
                        <label class="page" for="page">Pagina:</label>
                        <select class="pagination-input pagination-select" onchange="this.form.submit()" id="page"
                                name="page">
                            <?php for ($i = 0; $i < $pagination; $i++) {
                                if ($i == $page) { ?>
                                    <option class="pagination-select" selected value="<?= $i ?>"><?= $i + 1 ?></option>
                                <?php } else { ?>
                                    <option value="<?= $i ?>"><?= $i + 1 ?></option>
                                <?php }
                            }
                            } ?>
                        </select>
                    </section>
                    <section class="form-item">
                        <label class="max-items" for="max-items">Weergave:</label>
                        <input class="pagination-input" onchange="this.form.submit()" class="max-items-field"
                               type="number" id="max-items" name="max-items" min="1" value="<?= $max_items_per_page ?>">
                        <?php if ($max_items_per_page == 1) { ?>
                            <p class="max-items">afspraak per pagina.</p>
                        <?php } else { ?>
                            <p class="max-items">afspraken per pagina.</p>
                        <?php } ?>
                    </section>
                    <input type="hidden" id="sortby" name="sortby" value="<?= $sort ?>">
                    <input type="hidden" id="sort" name="sort" value="<?= $sortmethod ?>">
                    <input type="hidden" id="search" name="search" value="<?= $search ?>">
                    <input type="hidden" id="searchtype" name="searchtype" value="<?= $search_type ?>">
                    <input type="hidden" id="show" name="show" value="<?= $show ?>">
                </form>
                <p>Totaal aantal afspraken met deze criteria: <b><?= $database_length ?></b></p>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <?php if ($database_length == 0) { ?>
            <td colspan="6"><p><b>Geen resultaten.</b></p></td>
        <?php } ?>
        <?php foreach ($appointments as $index => $appointment) {
            if ($appointment['status'] == 1 && $show == 'all') { ?>
                <tr class="accepted">
            <?php }
            if ($appointment['status'] == 2 && $show == 'all') { ?>
                <tr class="deleted">
            <?php }
            if ($appointment['id'] == $view) { ?>
                <tr class="last-viewed">
            <?php }
            if ($appointment['status'] == 0 && $appointment['id'] !== $view) { ?>
                <tr>
            <?php } ?>
            <td id="<?= $index ?>" class="mobile-table-header" data-label="ID"><?= $appointment['id'] ?></td>
            <td data-label="Scroll" class="mobile-only"><a class="button normal"
                                                           href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>#<?= $index + 1 ?>">&#11015;</a><a
                        class="button normal"
                        href="appointments-list.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>#<?= $index - 1 ?>">&#11014;</a>
            </td>
            <?php if ($show == 'all') { ?>
                <td data-label="Status" class="normal-row"><b><?php if ($appointment['status'] == 0) {
                            echo '&#9744;';
                        }
                        if ($appointment['status'] == 1) {
                            echo '&#9745;';
                        }
                        if ($appointment['status'] == 2) {
                            echo '&#9746;';
                        } ?></b></td> <?php } ?>
            <?php if (isset($appointment['name'])) { ?>
                <td data-label="Naam" class="normal-row"><?= htmlentities($appointment['name']) ?> </td>
                <td data-label="Achternaam" class="normal-row"><?= htmlentities($appointment['last_name']) ?></td>
            <?php } else { ?>
                <td colspan="2" data-label="Geen info." class="normal-row web-only">
                    <div class="code">Geen informatie beschikbaar.</div>
                </td>
            <?php } ?>
            <td data-label="Locatie" class="location"><?= htmlentities($appointment['location']) ?></td>
            <td data-label="Datum" class="date-table"><?= htmlentities($appointment['date']) ?></td>
            <td data-label="Tijd" class="normal-row"><?= htmlentities($appointment['time']) ?></td>
            <td data-label="Aantal" class="normal-row"><?= htmlentities($appointment['amount']) ?></td>
            <td data-label="Opmerkingen" class="comments">
                <?php
                if (isset($appointment['comments'])) {
                    $comments = htmlentities($appointment['comments']);
                    if (strlen($comments) > 75)
                        $comments = substr($comments, 0, 60) . '...';
                } else {
                    $comments = '';
                } ?>
                <?= $comments ?>
            </td>
            <?php if ($show == 'all' || $show == 'deleted') { ?>
            <td data-label="Reden van verwijderen" class="delete_reason">
                <?php
                if (isset($appointment['delete_reason'])) {
                    $delete_reason = htmlentities($appointment['delete_reason']);
                    if (strlen($delete_reason) > 75)
                        $delete_reason = substr($delete_reason, 0, 60) . '...';
                } else {
                    $delete_reason = '';
                } ?>
                <?= $delete_reason ?>
                <?php } ?>
            </td>

            <td data-label="Opties" class="button-table">
                <section class="options">
                    <?php if ($appointment['status'] == 0) { ?>
                        <a class="invisible-link" href="accept.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&index=<?= $appointment['id'] ?>&view=<?= $index ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>">
                            <button class="button accept">Accepteer</button>
                        </a>
                    <?php } ?>
                    <a class="invisible-link" href="details.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&index=<?= $appointment['id'] ?>&view=<?= $index ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>">
                        <button class="button normal">Details</button>
                    </a>
                    <a class="invisible-link" href="change.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&index=<?= $appointment['id'] ?>&view=<?= $index ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>">
                        <button class="button normal">Wijzig</button>
                    </a>
                    <a class="invisible-link" href="mailto:<?= $appointment['email'] ?>?subject=<?php if ($appointment['status'] == 0 || $appointment['status'] == 1) { ?> Opmerking ingeplande afspraak op <?= $appointment['date'] . ' ' . $appointment['time'] ?> <?php } else { ?> Opmerking verwijderde afspraak van <?= $appointment['date'] . ' ' . $appointment['time'] ?> <?php } ?> &body=Deze email gaat over de <?php if ($appointment['status'] == 0 || $appointment['status'] == 1) { ?> gemaakte afspraak <?php } else { ?> verwijderde afspraak, aangevraagd <?php } ?>door <?= $appointment['name'] ?> <?= $appointment['last_name'] ?> voor de locatie <?= $appointment['location'] ?> op <?= $appointment['date'] . ' ' . $appointment['time'] ?>. Opmerking: <?= $appointment['comments'] ?>">
                        <button class="button notification">Mail</button>
                        </button></a>
                    <?php if ($appointment['status'] == 0 || $appointment['status'] == 1) { ?>
                        <a class="invisible-link" href="delete.php?show=<?= $show ?>&search=<?= $search ?>&searchtype=<?= $search_type ?>&index=<?= $appointment['id'] ?>&view=<?= $index ?>&page=<?= $page ?>&max-items=<?= $max_items_per_page ?>&sortby=<?= $sort ?>&sort=<?= $sortmethod ?>">
                            <button class="button deny">Verwijder</button>
                        </a>
                    <?php } ?>
                </section>
            </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</section>
</body>
</html>


