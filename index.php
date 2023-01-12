<?php
session_start();

//May I visit this page? Check the SESSION
if (isset($_SESSION['loggedInUser'])) {
    // Redirect if not logged in
    header("Location: dashboard.php");
    exit;
}

$login = false;
// Is user logged in?

if (isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "includes/credentials.php";

// Get form data
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];
    // Server-side validation
    $errors = [];
    if ($email == '') {
        $errors['email'] = 'E-mail is niet gezet';
    }
    if ($password == '') {
        $errors['password'] = 'Wachtwoord is niet gezet';
    }

    // If data valid
    if (empty($errors)) {
        // SELECT the user from the database, based on the email address.
        $sql = "SELECT * FROM `accounts` WHERE `email` = '$email';";
        $result = mysqli_query($db, $sql);

        // check if the user exists
        if (mysqli_num_rows($result) == 1) {
            // get user data
            $login_data = mysqli_fetch_assoc($result);

            if (password_verify($password, $login_data['password'])) {
                // Check if the provided password matches the stored password in the database
                // Store the user in the session
                $_SESSION['loggedInUser'] = [
                    'id' => $login_data['id'],
                    'name' => $login_data['name'],
                    'last_name' => $login_data['last_name'],
                    'email' => $login_data['email'],
                    'account_type' => $login_data['account_type']
                ];
                // Redirect to secure page
                header('Location: dashboard.php');
            } else {
                // Credentials not valid
                $errors['error'] = "Inlog onsuccesvol.";
            }
            //error incorrect log in
        } else {
            // User doesn't exist
            $errors['error'] = "Inlog onsuccesvol.";
        }
    } else {
        //error incorrect log in
        $errors['error'] = "Inlog onsuccesvol.";
    }
    mysqli_close($db);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name = "viewport" content = "width=device-width, minimum-scale=1.0, maximum-scale = 1.0, user-scalable = no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/login.css"/>
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
<div class="login-field">
    <img class="web-logo" src="img/logos.webp" alt="Star-shl Logo & Check Logo">
    <img class="mobile-logo" src="img/star-shl_logo-RGB_t_.png" alt="Star-shl logo">
    <img class="mobile-logo" src="img/check_logo.webp" alt="Check logo">
    <h1>Inloggen bij Check</h1>
    <p>Heb je geen account of problemen? Neem contact op met een beheerder.</p>
    <form action="" method="post">
        <div class="form-item">
        <label class="label" for="email">Email</label>
            <input class="input styled-field" id="email" type="text" name="email"
                   value="<?= isset($email) ? $email : '' ?>" placeholder="naam@star-shl.nl"/>
        <p class="error">
            <?= isset($errors['email']) ? $errors['email'] : '' ?>
        </p>
        </div>

        <div class="form-item">
        <label class="label" for="password">Wachtwoord</label>
        <div class="password-container">
            <input class="input styled-field" id="password" type="password" name="password"/>
            <?php if (isset($errors['loginFailed'])) { ?>
                    <?= isset($errors['loginFailed']) ? $errors['loginFailed'] : '' ?>
            <?php } ?>
        </div>
        <p class="error">
            <?= isset($errors['password']) ? $errors['password'] : '' ?>
        </p>
        </div>

        <div class="buttons">
        <button class="button normal" type="submit" name="submit">Log in</button>
        <button class="button normal" type="button" onclick="myFunction()">Toon wachtwoord</button>
        </div>
        <b><p class="error">
            <?= isset($errors['error']) ? $errors['error'] : '' ?>
            </p></b>

    </form>
</div>
</body>

<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

</html>


