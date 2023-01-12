<?php
//May I visit this page? Check the SESSION
if (!isset($_SESSION['loggedInUser'])) {
    // Redirect if not logged in
    header("Location: index.php");
    exit;
}
?>