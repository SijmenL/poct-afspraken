<?php
/** @var array $appointment */
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


$query = "SELECT appointments.id, accounts.name, accounts.last_name, accounts.mail, appointments.location, appointments.date, appointments.time, appointments.amount, appointments.comments, appointments.status FROM appointments LEFT JOIN accounts ON appointments.account_id=accounts.id  WHERE appointments.id = $index";
$result = mysqli_query($db, $query)
or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$data = mysqli_fetch_assoc($result);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Check Star-shl</title>
</head>
<body>
<h1 class="title"> <?= $title;?> </h1>
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
                    <th><?= $data['name'] . " " . $data['last_name']?></th>
                </tr>
                <tr>
                    <th>Mail: </th>
                    <th><?= $data['mail']?></th>
                </tr>
                    <?php } else { ?>
                <tr>
                    <th>Error: </th>
                    <th><div class="code">Geen account gelinkt met afspraak. Neem contact op met de beheerder of met de locatie.</div></th>
                </tr>
                <?php } ?>
                <tr>
                    <th>Locatie: </th>
                    <th><?= $data['location']?></th>
                </tr>
                <tr>
                    <th>Datum:</th>
                    <th><?=$data['date']?></th>
                </tr>
                <tr>
                    <th>Tijd:</th>
                    <th><?=$data['time']?></th>
                </tr>
                <tr>
                    <th>Hoeveelheid:</th>
                    <th><?= $data['amount']?></th>
                </tr>
                <?php if (isset($data['comments'])) { ?>
                <tr>
                    <th>Opmerkingen:</th>
                    <th><?= $data['comments']?></th>
                </tr>
                <?php } ?>
                <tr>
                    <th>ID:</th>
                    <th><?= $data['id'] ?></th>
                </tr>
                <tr class="admin-details">
                    <th>Status:</th>
                    <?php if ($data['status'] == 0){?> <th>Ongeaccepteerd</th> <?php } if ($data['status'] == 1){?> <th>Geaccepteerd</th> <?php } if ($data['status'] == 2){?> <th>Verwijderd</th> <?php } ?>
                </tr>
                <tr class="admin-details">
                    <th>Wergegeven op pagina:</th>
                    <th><?=$page + 1?></th>
                </tr>
                <tr class="admin-details">
                    <th>Index van die pagina:</th>
                    <th><?= $view ?></th>
                </tr>
                </tbody>
            </table>
            <div class="buttons">
                <a href="unaccepted-appointments.php?show=<?=$show ?>&search=<?=$search ?>&searchtype=<?=$search_type ?>&view=<?= $index ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sortmethod ?>&sort=<?=$sort?>#<?= $view ?>"><button class="button normal">Ga terug naar de lijst</button></a>
                <a href="change.php?show=<?=$show ?>&search=<?=$search ?>&searchtype=<?=$search_type ?>&index=<?= $index ?>&view=<?= $view ?>&page=<?=$page?>&max-items=<?=$max_items_per_page?>&sortby=<?=$sortmethod ?>&sort=<?=$sort?>"><button class="button normal">Wijzig</button></a>
            </div>
        </section>

<?php } else { ?>
    <p>Er is iets misgegaan.</p>
    <p><i>De link die je hebt ingetypt verwijst naar een reservering die niet bestaat. Je probeert de details van afspraakwf <b>#<?= $index ?></b> te beijken, maar die is niet bekend.</i></p>
    <a href="unaccepted-appointments.php"><button class="button normal">Ga terug</button></a>
<?php } ?>

</body>
</html>
