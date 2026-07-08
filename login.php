<?php
session_start();
include "db.php";

$error = "";

if(isset($_POST['login'])){

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            $_SESSION['login'] = "yes";
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];

            header("Location:index.php");
            exit;

        }else{

            $error = "Username or Password is incorrect.";

        }

    }else{

        $error = "Username or Password is incorrect.";

    }

}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-lg-4 col-md-6 col-sm-10">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3 class="text-center">🔐 Login</h3>

</div>

<div class="card-body">

<?php
if($error!=""){
echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="post">

<label>Username</label>

<input type="text" name="username" class="form-control" required>

<br>

<label>Password</label>

<input type="password" name="password" class="form-control" required>

<br>

<button class="btn btn-primary w-100" name="login">
Login
</button>

<br><br>

<a href="register.php" class="btn btn-success w-100">
👤 Create New Account
</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>