<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];
$id = (int)$_GET['id'];

$conn->query("DELETE FROM accounts WHERE id='$id' AND user_id='$user_id'");

header("Location: index.php");
exit;
?>