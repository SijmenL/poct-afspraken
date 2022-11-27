<?php
/** @var array $reservations */
require_once "array.php";

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nieuwe afspraken</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

</head>
<body>
<h1>Overzicht Nieuwe Afspraken</h1>
<table class="styled-table">
    <thead>
    <tr>
        <th>#</th>
        <th>Naam</th>
        <th>Achternaam</th>
        <th>Locatie</th>
        <th class="date-table">Datum</th>
        <th>Tijd</th>
        <th>Aantal</th>
        <th>Opmerkingen</th>
        <th class="button-table">Opties</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="6">&copy;Sijmen Lokers</td>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach ($reservations as $index => $reservation) { ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= $reservation['name'] ?></td>
            <td><?= $reservation['lastname'] ?></td>
            <td><?= $reservation['location'] ?></td>
            <td><?= $reservation['day'] . '-' . $reservation['month'] . '-' . $reservation['year'] ?></td>
            <td><?= $reservation['time'] ?></td>
            <td><?= $reservation['amount'] ?></td>
            <td><?= $reservation['comment'] ?></td>

            <td>
                <a href=><button class="button accept">Accepteer</button></a>
                <a href="details.php?index=<?= $index ?>"><button class="button normal">Details</button></a>
                <a href="change.php?index=<?= $index ?>"><button class="button normal">Wijzigen</button></a>
                <a href="mailto:<?= $reservation['mail'] ?>?subject=Opmerking ingeplande afspraak op <?= $reservation['date'] ?> <?= $reservation['time'] ?>&body=Deze mail gaat over de gemaakte afspraak door <?=$reservation['name'] ?> <?= $reservation['lastname']?> voor de locatie <?= $reservation['location'] ?> op <?= $reservation['date'] ?> <?= $reservation['time'] ?>. Opmerking: <?= $reservation['comment'] ?>"><button class="button notification" >Mail</button></button></a>
                <a href=""><button class="button deny">Verwijder</button></a>
            </td>
        </tr>
    <?php } ?>
    </tbody></table>
</body>
</html>
