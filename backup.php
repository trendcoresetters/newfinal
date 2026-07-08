<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=password_vault_backup_'.date('Y-m-d').'.csv');

$output = fopen('php://output', 'w');

fputcsv($output, array(
    'Website',
    'Username',
    'Password',
    'Category',
    'Notes'
));

$result = $conn->query("
SELECT website, username, password, category, notes
FROM accounts
WHERE user_id='$user_id'
ORDER BY favorite DESC, id DESC
");

while($row = $result->fetch_assoc()){

    fputcsv($output, array(
        $row['website'],
        $row['username'],
        $row['password'],
        $row['category'],
        $row['notes']
    ));

}

fclose($output);
exit;
?>