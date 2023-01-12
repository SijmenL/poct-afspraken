<?php
session_start();
require_once "includes/login_check.php";

//Get name from the SESSION
$account_type = $_SESSION['loggedInUser']['account_type'];
$account_id = $_SESSION['loggedInUser']['id'];

/** @var array $appointments */
/** @var array $db */

require_once 'includes/credentials.php';

// Search type
if (isset($_GET['searchtype'])) {
    $search_type = $_GET['searchtype'];
} else {
    $search_type = 'all';
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

$needed_page = $page * $max_items_per_page;

if ($sortmethod == 0) {
    if ($search_type == 'all') {
            $query = "SELECT appointments.id, accounts.id, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE accounts.id LIKE $account_id AND (appointments.id LIKE '%$search%' OR appointments.location LIKE '%$search%' OR appointments.date LIKE '%$search%' OR appointments.time LIKE '%$search%' OR appointments.amount LIKE '%$search%' OR appointments.comments LIKE '%$search%') ORDER BY $sort LIMIT $max_items_per_page OFFset $needed_page;";
    } else {
            $query = "SELECT appointments.id, accounts.id, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE accounts.id LIKE $account_id AND $search_type LIKE '%$search%' ORDER BY $sort LIMIT $max_items_per_page OFFset $needed_page;";
    }
    // $sortmethod == 1
} else {
    if ($search_type == 'all') {
            $query = "SELECT appointments.id, accounts.id, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE accounts.id LIKE $account_id AND (appointments.id LIKE '%$search%' OR appointments.location LIKE '%$search%' OR appointments.date LIKE '%$search%' OR appointments.time LIKE '%$search%' OR appointments.amount LIKE '%$search%' OR appointments.comments LIKE '%$search%') ORDER BY $sort DESC LIMIT $max_items_per_page OFFset $needed_page;";
    } else {
            $query = "SELECT appointments.id, accounts.id, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id WHERE accounts.id LIKE $account_id AND $search_type LIKE '%$search%' ORDER BY $sort DESC LIMIT $max_items_per_page OFFset $needed_page;";
    }
}

// Pagination
if ($search_type == 'all') {
        $count = "SELECT COUNT(`id`) FROM appointments  WHERE `account_id` LIKE $account_id AND (`id` LIKE '%$search%' OR `location` LIKE '%$search%' OR `date` LIKE '%$search%' OR `time` LIKE '%$search%' OR `amount` LIKE '%$search%' OR `comments` LIKE '%$search%')";
} else {
        $count = "SELECT COUNT(`id`) FROM appointments  WHERE `account_id` LIKE $account_id AND $search_type LIKE '%$search%';";
}

$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);


$items = mysqli_query($db, $count)
or die('Error ' . mysqli_error($db) . ' with query ' . $count);

while ($row_count = $items->fetch_assoc()) {
    $database_length = $row_count['COUNT(`id`)'];
}

$appointments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $appointments[] = $row;
}

$pagination = (ceil($database_length / $max_items_per_page));

//Get name from the SESSION
$account_type = $_SESSION['loggedInUser']['account_type'];

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
        <li><a>mijn afspraken</a></li>
    </ul>
    <h1>Mijn afspraken</h1>
    <form class="search-area" action="my-appointments.php" method="GET">
        <section class="form-item">
            <input class="search-field" onchange="this.form.submit()" type="text" id="search" name="search" value="<?=$search ?>" placeholder="Zoeken...">
        </section>
        <section class="form-item">
            <select class="<?php if ($search_type == 'all') { ?> no-filter-field <?php } else { ?> filter-field <?php } ?>" onchange="this.form.submit()" id="searchtype" name="searchtype" >
                <option <?php if ($search_type == 'all') { ?> selected <?php } ?> value="all">Geen filter</option>
                <option <?php if ($search_type == 'appointments.location') { ?> selected <?php } ?> value="appointments.location">Locatie</option>
                <option <?php if ($search_type == 'appointments.date') { ?> selected <?php } ?> value="appointments.date">Datum</option>
                <option <?php if ($search_type == 'appointments.time') { ?> selected <?php } ?> value="appointments.time">Tijd</option>
                <option <?php if ($search_type == 'appointments.amount') { ?> selected <?php } ?> value="appointments.amount">Hoeveelheid</option>
                <option <?php if ($search_type == 'appointments.comments') { ?> selected <?php } ?> value="appointments.comments">Opmerking</option>
            </select>
        </section>
        <input type="hidden" id="max-items" name="max-items" value="<?=$max_items_per_page?>" hidden>
    </form>
    <table class="styled-table center">
        <thead>
        <tr>
            <th class="normal-row"><a class="sort" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&sortby=id&sort=<?php if ($sort == 'id' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?=$max_items_per_page?>">ID<?php if ($sortmethod == 0) {?>&#11014; <?php } else { ?>&#11015;<?php } ?></a></th>
            <th class="normal-row"><a class="sort" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&sortby=status&sort=<?php if ($sort == 'status' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?=$max_items_per_page?>">Status<?php if ($sortmethod == 0) {?>&#11014; <?php } else { ?>&#11015;<?php } ?></a></th>
            <th class="location"><a class="sort" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&sortby=location&sort=<?php if ($sort == 'location' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?=$max_items_per_page?>">Locatie<?php if ($sortmethod == 0) {?>&#11014; <?php } else { ?>&#11015;<?php } ?></a></th>
            <th class="date-table"><a class="sort" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&sortby=date&sort=<?php if ($sort == 'date' && $sortmethod == 0) { ?>0<?php } else { ?>1<?php } ?>&max-items=<?=$max_items_per_page?>">Datum<?php if ($sortmethod == 0) {?>&#11014; <?php } else { ?>&#11015;<?php } ?></a></th>
            <th class="normal-row"><a class="sort" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&sortby=time&sort=<?php if ($sort == 'time' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?=$max_items_per_page?>">Time<?php if ($sortmethod == 0) {?>&#11014; <?php } else { ?>&#11015;<?php } ?></a></th>
            <th class="normal-row"><a class="sort" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&sortby=amount&sort=<?php if ($sort == 'amount' && $sortmethod == 0) { ?>1<?php } else { ?>0<?php } ?>&max-items=<?=$max_items_per_page?>">Hoeveelheid<?php if ($sortmethod == 0) {?>&#11014; <?php } else { ?>&#11015;<?php } ?></a></th>
            <th class="comments">Opmerkingen</th>
            <th class="button-table">Opties</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="6">
                <section class="pagination" id="pagination">
                    <?php if ($page >= 1 && $page <= $pagination - 2 && $pagination > 2) { ?>
                        <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=0&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">&#10094; &#10094;</a>
                        <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page - 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">&#10094;</a>
                        <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page - 1 ?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page?></a>
                        <a class="active" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page + 1?></a>
                        <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page + 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page + 2?></a>
                        <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page + 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">&#10095;</a>
                        <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$pagination - 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">&#10095; &#10095;</a>
                    <?php } else {
                        if ($page < 1 && $pagination > 2) {?>
                            <a class="active" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page + 1?></a>
                            <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page + 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page + 2?></a>
                            <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page + 2?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page + 3?></a>
                            <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page + 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">&#10095;</a>
                            <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$pagination - 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">&#10095; &#10095;</a>
                        <?php } if ($page >= 1 && $page <= $pagination - 1 && $pagination > 2) { ?>
                            <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=0&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">&#10094; &#10094;</a>
                            <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page - 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">&#10094;</a>
                            <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page - 2?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page - 1?></a>
                            <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page - 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page?></a>
                            <a class="active" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination"><?=$page + 1?></a>
                        <?php }
                        if ($pagination <= 2 && $pagination != 1) {
                            if ($page == 0) { ?>
                                <a class="active" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">1</a>
                                <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page + 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">2</a>
                            <?php } else { ?>
                                <a href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page - 1?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">1</a>
                                <a class="active" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>#pagination">2</a>
                            <?php }}} ?>
                </section>
                <form action="my-appointments.php#pagination" method="get">
                    <section class="form-item">
                        <?php if ($pagination > 3) {?>
                        <label class="page" for="page">Pagina:</label>
                        <select class="pagination-input pagination-select" onchange="this.form.submit()" id="page" name="page" >
                            <?php for ($i = 0; $i < $pagination; $i++) {
                                if ($i == $page) { ?>
                                    <option class="pagination-select" selected value="<?= $i ?>"><?= $i + 1 ?></option>
                                <?php } else { ?>
                                    <option value="<?= $i ?>"><?= $i + 1 ?></option>
                                <?php }
                            }} ?>
                        </select>
                    </section>
                    <section class="form-item">
                        <label class="max-items" for="max-items">Weergave:</label>
                        <input class="pagination-input" onchange="this.form.submit()" class="max-items-field" type="number" id="max-items" name="max-items" min="1" value="<?=$max_items_per_page ?>">
                        <?php if ($max_items_per_page == 1) { ?>
                            <p class="max-items">afspraak per pagina.</p>
                        <?php } else { ?>
                            <p class="max-items">afspraken per pagina.</p>
                        <?php } ?>
                    </section>
                    <input type="hidden" id="sortby" name="sortby" value="<?=$sort ?>">
                    <input type="hidden" id="sort" name="sort" value="<?=$sortmethod ?>">
                    <input type="hidden" id="search" name="search" value="<?=$search ?>">
                    <input type="hidden" id="searchtype" name="searchtype" value="<?=$search_type ?>">
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
            if ($appointment['status'] == 1) { ?>
                <tr class="accepted">
            <?php }
            if ($appointment['status'] == 2) { ?>
                <tr class="deleted">
            <?php } if ($appointment['id'] == $view) {?>
                <tr class="last-viewed">
            <?php } if ($appointment['status'] == 0 && $appointment['id'] !== $view) { ?>
                <tr>
            <?php } ?>
            <td id="<?=$index?>" class="mobile-table-header" data-label="ID"><?= $appointment['id']?></td>
            <td data-label="Scroll" class="mobile-only"><a class="button normal" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>#<?= $index + 1?>">&#11015;</a><a class="button normal" href="my-appointments.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>#<?= $index - 1?>">&#11014;</a> </td>
            <td data-label="Status" class="normal-row"><b><?php if ($appointment['status'] == 0) {echo '&#9744;';} if ($appointment['status'] == 1) {echo '&#9745;';} if ($appointment['status'] == 2) {echo '&#9746;';}?></b></td>
            <td data-label="Locatie" class="location"><?= $appointment['location'] ?></td>
            <td data-label="Datum" class="date-table"><?= $appointment['date']?></td>
            <td data-label="Tijd" class="normal-row"><?= $appointment['time'] ?></td>
            <td data-label="Aantal" class="normal-row"><?= $appointment['amount'] ?></td>
            <td data-label="Opmerkingen" class="comments">
                <?php
                if (isset($appointment['comments'])) {
                    $comments = $appointment['comments'];
                    if (strlen($comments) > 75)
                        $comments = substr($comments, 0, 60) . '...';} else {
                    $comments = '';
                }?>
                <?= $comments ?>
            </td>
            <td data-label="Opties" class="button-table">
                <section class="options">
                    <a href="details.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&index=<?= $appointment['id'] ?>&view=<?= $index ?>&page=<?=$page ?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>"><button class="button normal">Details</button></a>
                    <a href="change.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&index=<?= $appointment['id'] ?>&view=<?= $index ?>&page=<?=$page ?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>"><button class="button normal">Wijzig</button></a>
                    <?php if ($appointment['status'] == 0|| $appointment['status'] == 1) { ?>
                        <a href="delete.php?search=<?=$search ?>&searchtype=<?=$search_type ?>&index=<?= $appointment['id'] ?>&view=<?= $index ?>&page=<?=$page ?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sort ?>&sort=<?=$sortmethod?>"><button class="button deny">Verwijder</button></a>
                        <p id="demo"></p>
                    <?php } ?>
                </section>
            </td>
            <?php } ?>
        </tr>
        </tbody>
    </table>
</section>
</body>
</html>


