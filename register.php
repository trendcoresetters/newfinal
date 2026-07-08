<?php
include "db.php";

$msg="";

if(isset($_POST['register'])){

$name=$conn->real_escape_string($_POST['name']);
$email=$conn->real_escape_string($_POST['email']);
$username=$conn->real_escape_string($_POST['username']);
$password=$_POST['password'];

$check=$conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");

if($check->num_rows>0){

$msg="<div class='alert alert-danger'>Username or Email already exists.</div>";

}else{

$hash=password_hash($password,PASSWORD_DEFAULT);

$conn->query("INSERT INTO users(name,email,username,password)
VALUES('$name','$email','$username','$hash')");

$msg="<div class='alert alert-success'>
Registration Successful.
<a href='login.php'>Login Now</a>
</div>";

}

}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-5 col-md-6">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3 class="text-center">
👤 Create Account
</h3>

</div>

<div class="card-body">

<?php echo $msg; ?>

<form method="post">

<label>Full Name</label>

<input
type="text"
name="name"
class="form-control"
required>

<br>

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

<br>

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
required>

<br>

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

<br>

<button
class="btn btn-success w-100"
name="register">

Create Account

</button>

<br><br>

<a
href="login.php"
class="btn btn-primary w-100">

Back to Login

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>