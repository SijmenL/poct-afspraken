
<?php
// Check of de persoon is ingelogd.
    // Als de gebruiker op 'submit' heeft gedrukt:
        //Als de data klopt
            //invoeren in de database
            //stuur de gebruiker terug naar de vorige pagina
        // Als de data niet klopt
            // Check per veld wat er is misgegaan
            // Als het veld leeg is, geef een error
            // Laat een error zien wat er is fout gegaan
            // Vul de oude invoer terug in de velden

require_once 'includes/credentials.php';
/** @var array $db */

$errors = [];
if(isset($_POST['submit'])) {
    $location = $_POST['location'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $amount = $_POST['amount'];
    $comments = $_POST['comments'];

    if ($location == '') {
        $errors[] = 'Locatie is niet ingevuld';
    }
    if ($date == '') {
        $errors[] = 'Datum is niet gezet';
    }
    if ($time == '') {
        $errors[] = 'Tijd is niet gezet';
    }
    if ($amount == '') {
        $errors[] = 'Hoeveelheid is niet ingevuld';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO `appointments` (id, location, date, time, amount, comments, status) VALUES (NULL, '$location', '$date', '$time', '$amount', '$comments', 0)";
        if (mysqli_query($db, $sql)) {
            echo "Data is toegevoegd aan de database.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
        header("Location: unaccepted-appointments.php");
        exit;
    } else {
        $errors[] = 'Er is iets fout gegaan';
    }
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
    <title>Afspraak Inplannen</title>
</head>
<body>
    <h1 class="title">Afspraak Inplannen</h1>
    <?php if (!empty($errors)): ?>
    <section class="error">
    <ul class="error">
                <?php foreach ($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
    </section>

    <?php endif; ?>
    <section class="form">
        <form class="column is-6" action="form.php" method="post">
            <section class="form-item">
            <section class="form-item">
            <label class="label" for="location">Location</label>
            <input class="input" id="location" type="location" name="location" value="<?= isset($location) ? $location : ''?>"/>
            </section>
            <section class="form-item">
            <label class="label" for="date">Datum</label>
            <input class="input" id="date" type="date" name="date" value="<?= isset($date) ? $date : ''?>"/>
            </section>
            <section class="form-item">
            <label class="label" for="time">Tijd</label>
            <input type="time" id="time" name="time" value="<?= isset($time) ? $time : ''?>">
            </section>
            <section class="form-item">
            <label class="label" for="amount">Hoeveelheid</label>
            <input class="input" id="amount" type="number" name="amount" value="<?= isset($amount) ? $amount : ''?>"/>
            </section>
            <section class="form-item">
            <label class="label" for="comments">Opmerkingen</label>
            <input class="input" id="comments" type="text" name="comments" value="<?= isset($comments) ? $comments : ''?>"/>
            </section>
            <button class="button normal" type="submit" name="submit">Save</button>
        </form>
    </section>
    <a class="button mt-4" href="index.php">&laquo; Go back to the list</a>
</body>
</html>
