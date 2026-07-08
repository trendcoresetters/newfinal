<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$message = "";

if(isset($_POST['verify'])){

    $owner = $_POST['owner_password'];
    $friend = $_POST['friend_password'];

    $result = $conn->query("SELECT owner_password, friend_password FROM users WHERE id='$user_id'");
    $user = $result->fetch_assoc();

    if(
        password_verify($owner, $user['owner_password']) &&
        password_verify($friend, $user['friend_password'])
    ){

        $_SESSION['edit_verified'] = $id;

        header("Location: edit.php?id=".$id);
        exit;

    }else{

        $message = "❌ Owner Password or Friend Password is incorrect.";

    }

}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Verify Edit Passwords</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card shadow">

<div class="card-header bg-danger text-white">

<h3>🔐 Double Verification</h3>

</div>

<div class="card-body">

<?php
if($message!=""){
echo "<div class='alert alert-danger'>$message</div>";
}
?>

<form method="post">

<label>Owner Edit Password</label>

<input
type="password"
name="owner_password"
class="form-control"
required>

<br>

<label>Friend Edit Password</label>

<input
type="password"
name="friend_password"
class="form-control"
required>

<br>

<button
type="submit"
name="verify"
class="btn btn-danger w-100">

✅ Verify & Continue

</button>

<br><br>

<a href="index.php" class="btn btn-secondary w-100">

⬅ Cancel

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>