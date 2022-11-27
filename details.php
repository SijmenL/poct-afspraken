<?php
/** @var array $reservations */
require_once "array.php";

$index = $_GET['index'];
$lastArrayItem = key(array_slice($reservations, -1, 1, true)) + 1;
$index_number = $index + 1;

if (isset($reservations[$index])) {
    $reservation = $reservations[$index];
    $title = "Details van ongeaccepteerde reservering #" . $index_number;
} else {
    $title = 'Het lijt erop dat deze pagina niet werkt. Probeer het nog een keer of neem contact op met de beheerder.';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1 class="title"> <?= $title;?> </h1>
<?php if (isset($reservations[$index])) { ?>
        <section class="content">
            <table class="styled-table">
                <thead>
                <tr>
                    <th>Gegevens</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Naam: </th>
                    <th><?= $reservation['name'] . " " . $reservation['lastname']?></th>
                </tr>
                <tr>
                    <th>Mail: </th>
                    <th><?= $reservation['mail']?></th>
                </tr>
                <tr>
                    <th>Locatie: </th>
                    <th><?= $reservation['location']?></th>
                </tr>
                <tr>
                    <th>Datum:</th>
                    <th><?=$reservation['day'] . '-' . $reservation['month'] . '-' . $reservation['year']?></th>
                </tr>
                <tr>
                    <th>Tijd:</th>
                    <th><?=$reservation['time']?></th>
                </tr>
                <tr>
                    <th>Hoeveelheid:</th>
                    <th><?= $reservation['amount']?></th>
                </tr>
                <tr>
                    <th>Opmerkingen:</th>
                    <th><?= $reservation['comment']?></th>
                </tr>
                <tr>
                    <th>Reserveringsnummer:</th>
                    <th><?= $index_number ?></th>
                </tr>
                </tbody>
            </table>
            <div class="buttons">
                <a href="index.php"><button class="button normal">Ga terug naar de lijst</button></a>
            <a href="change.php?index=<?= $index ?>"><button class="button normal">Wijzigen</button></a>
            </div>
        </section>
<?php } else { ?>
    <p>Er is iets misgegaan.</p>
    <p><i>De link die je hebt ingetypt verwijst naar een reservering die niet bestaat. Je probeerd de details van reservering <b>#<?= $index_number ?></b> te beijken, maar er zijn maar <b><?= $lastArrayItem ?></b> reserveringen bekend.</i></p>
    <a href="index.php"><button class="button normal">Ga terug</button></a>
<?php } ?>

</body>
</html>
