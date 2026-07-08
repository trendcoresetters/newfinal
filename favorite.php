<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];
$id = (int)$_GET['id'];

/* இந்த user-க்கு சொந்தமான account மட்டும் எடுக்க */
$result = $conn->query("SELECT favorite FROM accounts WHERE id='$id' AND user_id='$user_id'");

if($result->num_rows == 0){
    die("Access Denied");
}

$row = $result->fetch_assoc();

/* Favorite Toggle */
if($row['favorite'] == 1){

    $conn->query("UPDATE accounts SET favorite=0 WHERE id='$id' AND user_id='$user_id'");

}else{

    $conn->query("UPDATE accounts SET favorite=1 WHERE id='$id' AND user_id='$user_id'");

}

header("Location: index.php");
exit;
?>