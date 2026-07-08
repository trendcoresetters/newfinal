<?php

$conn = new mysqli(
    "sql104.infinityfree.com",
    "if0_42354520",
    "36mNEuG1jDecQt",
    "if0_42354520_passwordvault"
);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>