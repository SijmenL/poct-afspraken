<?php
// vul de betreffende velden van het formulier met de data uit de database voor dit specifieke album
/** @var array $db */

require_once 'includes/credentials.php';

$index = $_GET['index'];
$view = $_GET['view'];
$page = $_GET['page'];
$max_items_per_page = $_GET['max-items'];
$sort = $_GET['sort'];
$sortmethod = $_GET['sortby'];
$search = $_GET['search'];
$search_type = $_GET['searchtype'];
$show = $_GET['show'];

$errors = [
    [
        "error" => ""
    ],
    [
        "error" => ""
    ],
    [
        "error" => ""
    ],
    [
        "error" => ""
    ],
    [
        "error" => ""
    ]
];

if (!isset($index)) {
    $index = $_POST['id'];
}

$query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.mail, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id  WHERE appointments.id = $index";

$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$data = mysqli_fetch_assoc($result);

if (!isset($data['id'])) {
    header("Location: details.php?show=$show&search=$search&searchtype=$search_type&index=$index&view=$index&page=$page&max-items=$max_items_per_page&sortby=$sortmethod&sort=$sort");
}

if(isset($_POST['submit'])) {
    $location = mysqli_escape_string($db, $_POST['location']);
    $date = mysqli_escape_string($db, $_POST['date']);
    $time = mysqli_escape_string($db, $_POST['time']);
    $amount = mysqli_escape_string($db, $_POST['amount']);
    $comments = mysqli_escape_string($db, $_POST['comments']);
    $status = mysqli_escape_string($db, $_POST['status']);


    if ($location == '') {
        $errors[0]["error"] = "De locatie is niet gespecificeerd.";
        $error[] = 'Er is iets fout gegaan';
    }
    if ($date == '') {
        $errors[1]["error"] = "De datum is niet gespecificeerd.";
        $error[] = 'Er is iets fout gegaan';
    }
    if ($time == '') {
        $errors[2]["error"] = "De tijd is niet gespecificeerd.";
        $error[] = 'Er is iets fout gegaan';
    }
    if ($amount == '') {
        $errors[3]["error"] = "De hoeveelheid is niet gespecificeerd.";
        $error[] = 'Er is iets fout gegaan';
    }
    if ($status == '') {
        $errors[4]["error"] = "De status is niet gespecificeerd.";
        $error[] = 'Er is iets fout gegaan';
    }

    if (empty($error)) {
        $sql = "UPDATE `appointments` SET location = '$location', date = '$date', time = '$time', amount = '$amount', comments = '$comments', status = '$status' WHERE id = '$index';";
        if (mysqli_query($db, $sql)) {
            echo "Data is toegevoegd aan de database.";
            header("Location: details.php?show=$show&search=$search&searchtype=$search_type&index=$index&view=$index&page=$page&max-items=$max_items_per_page&sortby=$sortmethod&sort=$sort");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
        exit;
    } else {
        $error[] = 'Er is iets fout gegaan';
    }
}

if (isset($data['id'])) {
    $title = "Details van ongeaccepteerde reservering met ID " . $data['id'];
} else {
    $title = 'Het lijkt erop dat deze pagina niet werkt. Probeer het nog een keer of neem contact op met de beheerder.';
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
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <title>Check Star-shl</title>
</head>
<body>
    <h1 class="title"><?=$title ?></h1>

<section class="content">
    <form action="" method="post">
    <table class="styled-table detail-table">
        <thead>
        <tr>
            <th class="detail-table">Gegevens</th>
            <th class="detail-table">Data</th>
            <?php if (isset($error)) { ?>
            <th class="detail-table">Error</th>
            <?php } ?>
        </tr>
        </thead>
        <th>
        <?php if(isset($data['name'])) { ?>
            <tr>
                <th>Naam: </th>
                <th><?= $data['name'] . " " . $data['last_name']?></th>
                <?php if (isset($error)) { ?>
                    <th class="errors"></th>
                <?php } ?>
            </tr>
            <tr>
                <th>Mail: </th>
                <th><?= $data['mail']?></th>
                <?php if (isset($error)) { ?>
                    <th class="errors"></th>
                <?php } ?>
            </tr>
        <?php } else { ?>
            <tr>
                <th>Error: </th>
                <th><div class="code">Geen account gelinkt met afspraak. Neem contact op met de beheerder of met de locatie.</div></th>
                <?php if (isset($error)) { ?>
                    <th class="errors"></th>
                <?php } ?>
            </tr>
        <?php } ?>
        <tr>
            <th><label class="label" for="location">Locatie</label></th>
            <th><input class="input" id="location" type="text" name="location" value="<?= $data['location'] ?>"/></th>
            <?php if (isset($error)) { ?>
                <th class="errors detail-table"><?= $errors[0]["error"] ?></th>
            <?php } ?>
        </tr>
        <tr>
            <th><label class="label" for="date">Datum</label></th>
            <th><input class="input" id="date" type="text" name="date" value="<?= $data['date'] ?>"/></th>
            <?php if (isset($error)) { ?>
                <th class="errors detail-table"><?= $errors[1]["error"] ?></th>
            <?php } ?>
        </tr>
        <tr>
            <th><label class="label" for="time">Tijd</label></th>
            <th><input class="input" id="time" type="text" name="time" value="<?= $data['time'] ?>"/></th>
            <?php if (isset($error)) { ?>
                <th class="errors detail-table"><?= $errors[2]["error"] ?></th>
            <?php } ?>
        </tr>
        <tr>
            <th><label class="label" for="amount">Hoeveelheid</label></th>
            <th><input class="input" id="year" type="amount" name="amount" value="<?= $data['amount'] ?>"/></th>
            <?php if (isset($error)) { ?>
                <th class="errors detail-table"><?= $errors[3]["error"] ?></th>
            <?php } ?>
        </tr>
            <tr>
                <th><label class="label" for="comments">Opmerkingen</label></th>
                <th><input class="input" id="comments" type="text" name="comments" value="<?= $data['comments'] ?>"/></th>
                <?php if (isset($error)) { ?>
                    <th class="errors"></th>
                <?php } ?>
            </tr>
        <tr>
            <th>ID:</th>
            <th><?= $data['id'] ?></th>
            <?php if (isset($error)) { ?>
                <th class="errors"></th>
            <?php } ?>
        </tr>
        <tr class="admin-details">
            <th><label class="label" for="status">Status</label></th>
            <th><select name="status" id="status">
            <?php for ($i = 0; $i < 3; $i++) {
            if ($i == $data['status']) { ?>
                <option selected value="<?=$i?>"><?php if ($i == 0) { ?> Ongeaccepteerd <?php } if ($i == 1) { ?> Geaccepteerd <?php } if ($i == 2) { ?> Verwijderd <?php }?></option>
            <?php } else {?>
                <option value="<?=$i?>"><?php if ($i == 0) { ?> Ongeaccepteerd <?php } if ($i == 1) { ?> Geaccepteerd <?php } if ($i == 2) { ?> Verwijderd <?php }?></option>
            <?php } } ?>
                </select></th>
            <?php if (isset($error)) { ?>
                <th class="errors detail-table"><?= $errors[4]["error"] ?></th>
            <?php } ?>
        </tr>
        <tr class="admin-details">
            <th>Wergegeven op pagina:</th>
            <th><?=$page + 1?></th>
            <?php if (isset($error)) { ?>
                <th class="errors"></th>
            <?php } ?>
        </tr>
        <tr class="admin-details">
            <th>Index van die pagina:</th>
            <th><?= $view ?></th>
            <?php if (isset($error)) { ?>
                <th class="errors"></th>
            <?php } ?>
        </tr>
        </tbody>
    </table>
    <input id="id" name="id" hidden value="<?=$index?>">

        <button class="button accept" type="submit" name="submit">Opslaan</button>
    </form>

    <a href="details.php?show=<?=$show ?>&search=<?=$search ?>&searchtype=<?=$search_type ?>&index=<?= $index ?>&view=<?= $view ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sortmethod ?>&sort=<?=$sort?>"><button class="button deny">Niet opslaan.</button></a>
</section>
</body>
</html>
