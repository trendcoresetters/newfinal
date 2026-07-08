<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];

$msg = "";
$error = "";

if(isset($_POST['restore'])){

    if($_FILES['file']['name']!=""){

        $file = $_FILES['file']['tmp_name'];

        $handle = fopen($file,"r");

        // Skip Header
        fgetcsv($handle);

        while(($data = fgetcsv($handle,1000,",")) !== FALSE){

            $website = $conn->real_escape_string($data[0]);
            $username = $conn->real_escape_string($data[1]);
            $password = $conn->real_escape_string($data[2]);
            $category = $conn->real_escape_string($data[3]);
            $notes = $conn->real_escape_string($data[4]);

            $conn->query("INSERT INTO accounts
            (user_id, website, username, password, category, notes)
            VALUES
            ('$user_id','$website','$username','$password','$category','$notes')");
        }

        fclose($handle);

        $msg = "Backup Restored Successfully.";

    }else{

        $error = "Please Select CSV File.";

    }

}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Restore Backup</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3>📥 Restore Backup</h3>

</div>

<div class="card-body">

<?php

if($msg!=""){
    echo "<div class='alert alert-success'>$msg</div>";
}

if($error!=""){
    echo "<div class='alert alert-danger'>$error</div>";
}

?>

<form method="post" enctype="multipart/form-data">

<label>Select Backup CSV File</label>

<input
type="file"
name="file"
class="form-control"
accept=".csv"
required>

<br>

<button
class="btn btn-success"
name="restore">

📥 Restore

</button>

<a
href="index.php"
class="btn btn-secondary">

⬅ Back

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>