<?php
/** @var array $reservations */
require_once "array.php";

$index = $_GET['index'];
$lastArrayItem = key(array_slice($reservations, -1, 1, true)) + 1;
$index_number = $index + 1;

if (isset($reservations[$index])) {
    $reservation = $reservations[$index];
    $title = "Wijzig ongeaccepteerde reservering #" . $index_number;
} else {
    $title = 'Het lijkt erop dat deze pagina niet werkt. Probeer het nog een keer of neem contact op met de beheerder.';
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
</head>
    <h1 class="title"> <?= $title;?> </h1>
<?php if (isset($reservations[$index])) { ?>
    <form action="details.php?index=<?= $index ?>" method="post">
        <table class="styled-table">
            <thead>
            <tr>
                <th>Gegevens</th>
                <th>Wijzigen</th>
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
                <th><label for="location">Locatie:</label></th>
                <th><input type="location" id="location" name="location" required value="<?= $reservation['location']?>"></th>
            </tr>
            <tr>
                <th><label for="date">Datum:</label></th>
                <th><input type="date" id="date" name="date" required value="<?= $reservation['date']?>""></th>
            </tr>
            <th><label for="time">Tijd:</label></th>
            <th><input type="time" id="time" name="time" required value="<?= $reservation['time']?>"></th>
            <tr>
                <th><label for="number">Hoeveelheid:</label></th>
                <th><input type="number" id="number" name="number" required value="<?= $reservation['amount']?>"></th>
            </tr>
            <tr>
                <th><label for="comment">Opmerkingen:</label></th>
                <th><input type="comment" id="comment" name="comment" required value="<?= $reservation['comment']?>"></th>
            </tr>
            <tr>
                <th>Reserveringsnummer:</th>
                <th><?= $index_number ?></th>
            </tr>
            </tbody>
        </table>

        <div class="buttons">
        <input class="button accept" type="submit" value="Oplsaan">
    </form>
    <a href="index.php"><button class="button deny">Niet oplsaan</button></a>
    </div>
<?php } else { ?>
    <p>Er is iets misgegaan.</p>
    <p><i>De link die je hebt ingetypt verwijst naar een reservering die niet bestaat. Je probeerd reservring <b>#<?= $index_number ?></b> te beijken, maar er zijn maar <b><?= $lastArrayItem ?></b> reserveringen bekend.</i></p>
    <a href="index.php"><button class="button normal">Ga terug</button></a>
<?php } ?>



</body>
</html>
