<?php
// vul de betreffende velden van het formulier met de data uit de database voor dit specifieke album
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


if (!isset($index)) {
    $index = $_POST['id'];
}

if (!isset($data['id'])) {
    header("Location: unaccepted-appointments.php?show=$show&search=$search&searchtype=$search_type&index=$index&view=$index&page=$page&max-items=$max_items_per_page&sortby=$sortmethod&sort=$sort");
}

$sql = "UPDATE `appointments` SET status = 1 WHERE id = '$index';";
if (mysqli_query($db, $sql)) {
    echo "Wijziging doorgevoerd.";
    header("Location: unaccepted-appointments.php?show=$show&search=$search&searchtype=$search_type&index=$index&view=$index&page=$page&max-items=$max_items_per_page&sortby=$sortmethod&sort=$sort");
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
}

mysqli_close($db);
?>

