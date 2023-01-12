<?php
session_start();
require_once "includes/login_check.php";


if (isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "includes/credentials.php";

    // Get form data
    if (isset($_POST['submit'])) {
        $name = mysqli_escape_string($db, $_POST['name']);
        $last_name = mysqli_escape_string($db, $_POST['last_name']);
        $email = mysqli_escape_string($db, $_POST['email']);
        $password = $_POST['password'];
        $account_type = $_POST['accounttype'];

        // Server-side validation
        $errors = [];
        if ($name == '') {
            $errors['name'] = 'Naam is niet gezet';
        }
        if ($last_name == '') {
            $errors['last_name'] = 'Achternaam is niet gezet';
        }
        if ($email == '') {
            $errors['email'] = 'Mail is niet gezet';
        }
        if ($password == '') {
            $errors['password'] = 'Wachtwoord is niet gezet';
        }
        if ($account_type == '') {
            $errors['account_type'] = 'Account type is niet gezet';
        }

        // If data valid
        if (empty($errors)) {
            // create a secure password, with the PHP function password_hash()
            $password = password_hash($password, PASSWORD_DEFAULT);

            // store the new user in the database.
            $sql = "INSERT INTO `accounts` (id, name, last_name, email, password, account_type) VALUES (NULL, '$name', '$last_name', '$email', '$password', '$account_type')";
            // If query succeeded
            $result = mysqli_query($db, $sql);
            // Redirect to login page
            if ($result) {
                header("Location: users.php");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($db);
            }
        } else {
            $errors['error'] = 'Er is iets fout gegaan';
        }
    }
    // Exit the code
    mysqli_close($db);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/home.css"/>
    <title>Check Star-shl</title>
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.webmanifest">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#142d49">
    <meta name="msapplication-TileColor" content="#142d49">
    <meta name="theme-color" content="#142d49">
</head>
<body>

<h2 class="title">Registreer gebruikers met @star-shl mail</h2>
<form id="register" action="" method="post">

    <!-- Name -->
    <label class="label" for="name">Naam</label>
    <div>
        <input class="input" id="name" type="text" name="name" value="<?= $name ?? '' ?>"/>
    </div>
    <p>
        <?= $errors['name'] ?? '' ?>
    </p>

    <!-- Last Name -->
    <label class="label" for="last_name">Achternaam</label>
    <div>
        <input class="input" id="last_name" type="text" name="last_name" value="<?= $last_name ?? '' ?>"/>
    </div>
    <p>
        <?= $errors['last_name'] ?? '' ?>
    </p>

    <!-- Email -->
    <label class="label" for="email">Mail</label>
    <div>
        <input class="input" id="email" type="text" name="email" value="<?= $email ?? '' ?>" autocomplete="off" placeholder="naam@star-shl.nl"/>
    </div>
    <p>
        <?= $errors['email'] ?? '' ?>
    </p>

    <!-- Password -->
    <label class="label" for="password">Wachtwoord</label>
    <div>
        <input class="input" id="password" type="password" name="password" value="<?= $password ?? '' ?>"/>
    </div>
    <p>
        <?= $errors['password'] ?? '' ?>
    </p>

    <!-- Account type -->
    <label class="label" for="accounttype">Account type</label>
    <div>
    <select id="accounttype" name="accounttype" form="register">
        <?php for ($i = 1; $i < 4; $i++) {
            if ($i == $account_type) { ?>
                <option selected value="<?=$i?>"><?php if ($i == 1) { ?> Bloedafname <?php } if ($i == 2) { ?> Teamleider Bloedafname <?php } if ($i == 3) { ?> POCT <?php }?></option>
            <?php } else {?>
                <option value="<?=$i?>"><?php if ($i == 1) { ?> Bloedafname <?php } if ($i == 2) { ?> Teamleider Bloedafname <?php } if ($i == 3) { ?> POCT <?php }?></option>
            <?php } } ?>
    </select>
    </div>
    <p>
        <?= $errors['account_type'] ?? '' ?>
    </p>

    <!-- Submit -->
    <button class="button is-link is-fullwidth" type="submit" name="submit">Register</button>
    <p>
        <?= $errors['error'] ?? '' ?>
    </p>

</form>
</body>
</html>
