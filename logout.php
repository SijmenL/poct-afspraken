<?php
// Start the session.
session_start();
require_once "includes/login_check.php";

// destroy the session.
session_destroy();

// Redirect to login page
header('Location: index.php');
// Exit the code.
exit;

