<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['edit_verified'])) {
    header("Location:index.php");
    exit;
}

unset($_SESSION['edit_verified']);
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];

/* Dashboard */

$total = $conn->query("
SELECT COUNT(*) AS total
FROM accounts
WHERE user_id='$user_id'
")->fetch_assoc()['total'];

$email = $conn->query("
SELECT COUNT(*) AS total
FROM accounts
WHERE user_id='$user_id'
AND category='Email'
")->fetch_assoc()['total'];

$bank = $conn->query("
SELECT COUNT(*) AS total
FROM accounts
WHERE user_id='$user_id'
AND category='Banking'
")->fetch_assoc()['total'];

$social = $conn->query("
SELECT COUNT(*) AS total
FROM accounts
WHERE user_id='$user_id'
AND category='Social Media'
")->fetch_assoc()['total'];

$shopping = $conn->query("
SELECT COUNT(*) AS total
FROM accounts
WHERE user_id='$user_id'
AND category='Shopping'
")->fetch_assoc()['total'];

/* Search */

$search="";

if(isset($_GET['search'])){

    $search=$conn->real_escape_string($_GET['search']);

    $sql="
    SELECT *
    FROM accounts
    WHERE user_id='$user_id'
    AND
    (
        website LIKE '%$search%'
        OR username LIKE '%$search%'
        OR category LIKE '%$search%'
    )
    ORDER BY favorite DESC,id DESC
    ";

}else{

    $sql="
    SELECT *
    FROM accounts
    WHERE user_id='$user_id'
    ORDER BY favorite DESC,id DESC
    ";

}

$result=$conn->query($sql);

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Password Vault</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

<style>

body{

background:#f5f7fb;

}

.card{

border:none;

border-radius:15px;

}

table img{

object-fit:cover;

}

.badge{

font-size:14px;

}

</style>

</head>

<body>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between">

<h3>
    🔐 My Password Vault
    <br>
    <small style="font-size:15px;font-weight:normal;">
        Welcome,
        <?php echo htmlspecialchars($_SESSION['name']); ?>
        (<?php echo htmlspecialchars($_SESSION['username']); ?>)
    </small>
</h3>

<div>

<a href="add.php" class="btn btn-success btn-sm">

➕ Add
<a href="notes.php" class="btn btn-dark btn-sm">
📝 Notes
</a>

</a>
<a href="backup.php" class="btn btn-info btn-sm">
💾 Backup
</a>
<a href="restore.php" class="btn btn-primary btn-sm">
📥 Restore
</a>

<a href="change_password.php" class="btn btn-warning btn-sm">

🔑 Password

</a>

<a href="logout.php" class="btn btn-danger btn-sm">

Logout

</a>

</div>

</div>

<div class="card-body">

<div class="row mb-4">

<div class="col">

<div class="card bg-primary text-white">

<div class="card-body text-center">

<h6>Total</h6>

<h2><?php echo $total; ?></h2>

</div>

</div>

</div>

<div class="col">

<div class="card bg-success text-white">

<div class="card-body text-center">

<h6>Email</h6>

<h2><?php echo $email; ?></h2>

</div>

</div>

</div>

<div class="col">

<div class="card bg-warning">

<div class="card-body text-center">

<h6>Bank</h6>

<h2><?php echo $bank; ?></h2>

</div>

</div>

</div>

<div class="col">

<div class="card bg-info text-white">

<div class="card-body text-center">

<h6>Social</h6>

<h2><?php echo $social; ?></h2>

</div>

</div>

</div>

<div class="col">

<div class="card bg-dark text-white">

<div class="card-body text-center">

<h6>Shopping</h6>

<h2><?php echo $shopping; ?></h2>

</div>

</div>

</div>

</div>

<form method="GET">

<div class="input-group mb-3">

<input

type="text"

name="search"

class="form-control"

placeholder="Search Website / Username / Category"

value="<?php echo htmlspecialchars($search); ?>">

<button class="btn btn-primary">

Search

</button>

</div>

</form>

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>⭐</th>

<th>Photo</th>

<th>Website</th>

<th>Category</th>

<th>Username</th>

<th>Password</th>

<th>Action</th>

</tr>

</thead>

<tbody>
<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td align="center">

<a href="favorite.php?id=<?php echo $row['id']; ?>" style="text-decoration:none;font-size:22px;">

<?php
if($row['favorite']==1){
    echo "⭐";
}else{
    echo "☆";
}
?>

</a>

</td>

<td>

<?php

if($row['photo']!=""){

echo "<img src='".$row['photo']."' width='60' height='60' class='rounded'>";

}else{

echo "No Photo";

}

?>

</td>

<td>

<b><?php echo htmlspecialchars($row['website']); ?></b>

<?php

if($row['notes']!=""){

echo "<br><small class='text-muted'>".htmlspecialchars(substr($row['notes'],0,40))."</small>";

}

?>

</td>

<td>

<span class="badge bg-primary">

<?php echo htmlspecialchars($row['category']); ?>

</span>

</td>

<td>

<div class="input-group">

<input
type="text"
id="user<?php echo $row['id']; ?>"
value="<?php echo htmlspecialchars($row['username']); ?>"
class="form-control"
readonly>

<button
class="btn btn-info"
type="button"
onclick="copyUsername('user<?php echo $row['id']; ?>')">

📋

</button>

</div>

</td>

<td>

<div class="input-group">

<input
type="password"
id="pass<?php echo $row['id']; ?>"
value="<?php echo htmlspecialchars($row['password']); ?>"
class="form-control"
readonly>

<button
class="btn btn-secondary"
type="button"
onclick="togglePassword('pass<?php echo $row['id']; ?>')">

👁

</button>

<button
class="btn btn-info"
type="button"
onclick="copyPassword('pass<?php echo $row['id']; ?>')">

📋

</button>

</div>

</td>

<td>

<a
href="verify_edit.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm">

✏ Edit

</a>

<a
href="delete.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this account?')">

🗑 Delete

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script>

function togglePassword(id){

var x=document.getElementById(id);

if(x.type==="password"){

x.type="text";

}else{

x.type="password";

}

}

function copyPassword(id){

var x=document.getElementById(id);

var old=x.type;

x.type="text";

x.select();

document.execCommand("copy");

x.type=old;

alert("Password Copied!");

}

function copyUsername(id){

var x=document.getElementById(id);

x.select();

document.execCommand("copy");

alert("Username Copied!");

}

</script>

</body>

</html>