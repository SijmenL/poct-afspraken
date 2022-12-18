<?php

$user = 'Sijmen';
$hour = date('H');

if ($hour >= 20) {
    $greetings = "Goedenacht";
} elseif ($hour > 17) {
    $greetings = "Goedenavond";
} elseif ($hour > 11) {
    $greetings = "Goedemiddag";
} elseif ($hour < 12) {
    $greetings = "Goedenmorgen";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check Star-shl</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>

<header>
    <section class="logos">
    <img class="logo" src="img/check_logo.webp" alt="check logo">
    <p class="role">POCT</p>
    </section>
    <img class="profile-picture" src="img/profile_picture.webp" alt="profile_picture">
</header>

<body>
<section class="text-content">
<h1><?=$greetings?>, <?= $user ?></h1>
<h2>Snel naar:</h2>
<a class="button normal" href="unaccepted-appointments.php">Ongeaccepteerde afspraken</a>
<a class="button normal" href="form.php">Form</a>
</section>
</body>
</html>