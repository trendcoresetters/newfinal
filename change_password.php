<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];

$message = "";
$error = "";

$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

if(isset($_POST['change'])){

    $current_login = $_POST['current_login_password'];
    $new_login = $_POST['new_login_password'];
    $confirm_login = $_POST['confirm_login_password'];

    $owner_password = $_POST['owner_password'];
    $confirm_owner = $_POST['confirm_owner_password'];

    $friend_password = $_POST['friend_password'];
    $confirm_friend = $_POST['confirm_friend_password'];

    if(!password_verify($current_login, $user['password'])){

        $error = "Current Login Password is incorrect.";

    }elseif($new_login != $confirm_login){

        $error = "New Login Password does not match.";

    }elseif($owner_password != $confirm_owner){

        $error = "Owner Edit Password does not match.";

    }elseif($friend_password != $confirm_friend){

        $error = "Friend Edit Password does not match.";

    }else{

        $login_hash = password_hash($new_login, PASSWORD_DEFAULT);
        $owner_hash = password_hash($owner_password, PASSWORD_DEFAULT);
        $friend_hash = password_hash($friend_password, PASSWORD_DEFAULT);

        $conn->query("
        UPDATE users SET
        password='$login_hash',
        owner_password='$owner_hash',
        friend_password='$friend_hash'
        WHERE id='$user_id'
        ");

        $message = "All Passwords Updated Successfully.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Change Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>

<body class="bg-light">

<div class="container mt-4">

<div class="row justify-content-center">

<div class="col-lg-6 col-md-8 col-sm-12">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

<h3 class="mb-0">🔐 Security Password Settings</h3>

</div>

<div class="card-body">

<?php
if($message!=""){
echo "<div class='alert alert-success'>$message</div>";
}

if($error!=""){
echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="post">

<h5 class="text-primary">🔑 Login Password</h5>

<label>Current Login Password</label>

<input
type="password"
name="current_login_password"
class="form-control"
required>

<br>

<label>New Login Password</label>

<input
type="password"
name="new_login_password"
class="form-control"
required>

<br>

<label>Confirm Login Password</label>

<input
type="password"
name="confirm_login_password"
class="form-control"
required>

<hr>

<h5 class="text-success">👤 Owner Edit Password</h5>

<label>Owner Edit Password</label>

<input
type="password"
name="owner_password"
class="form-control"
required>

<br>

<label>Confirm Owner Password</label>

<input
type="password"
name="confirm_owner_password"
class="form-control"
required>

<hr>

<h5 class="text-danger">👥 Friend Edit Password</h5>

<label>Friend Edit Password</label>

<input
type="password"
name="friend_password"
class="form-control"
required>

<br>

<label>Confirm Friend Password</label>

<input
type="password"
name="confirm_friend_password"
class="form-control"
required>

<br>
<div class="d-grid gap-2 d-md-flex justify-content-md-between">

<button
type="submit"
name="change"
class="btn btn-warning">

💾 Save All Passwords

</button>

<a
href="index.php"
class="btn btn-secondary">

⬅ Back

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>