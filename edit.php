<?php
$id = intval($_GET['id']);

if (!isset($_SESSION['edit_verified']) || $_SESSION['edit_verified'] != $id) {
    header("Location:index.php");
    exit;
}

unset($_SESSION['edit_verified']);
session_start();
if (!isset($_SESSION['edit_verified'])) {
    header("Location:index.php");
    exit;
}

unset($_SESSION['edit_verified']);
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "db.php";
$user_id = (int)$_SESSION['user_id'];
$id = intval($_GET['id']);

$result = $conn->query("SELECT * FROM accounts WHERE id='$id' AND user_id='$user_id'");

if($result->num_rows==0){
    die("Access Denied");
}

$row = $result->fetch_assoc();

if(isset($_POST['update'])){

    $website = $_POST['website'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $category = $_POST['category'];
    $notes = $_POST['notes'];

    $photo = $row['photo'];

    if($_FILES['photo']['name']!=""){

        if(!is_dir("uploads")){
            mkdir("uploads");
        }

        $photo = "uploads/".time()."_".$_FILES['photo']['name'];

        move_uploaded_file($_FILES['photo']['tmp_name'],$photo);

    }

    $conn->query("UPDATE accounts SET

website='$website',
username='$username',
password='$password',
category='$category',
notes='$notes',
photo='$photo'

WHERE id='$id'
AND user_id='$user_id'");

    header("Location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Account</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>

<body class="bg-light">

<div class="container mt-4">

<div class="row justify-content-center">

<div class="col-lg-6 col-md-8 col-sm-12">

<div class="card shadow">

<div class="card-header bg-warning">

<h3 class="mb-0">✏ Edit Account</h3>

</div>

<div class="card-body">

<form method="post" enctype="multipart/form-data">

<label class="form-label">🌐 Website</label>

<input
type="text"
name="website"
class="form-control"
value="<?php echo htmlspecialchars($row['website']); ?>"
required>

<br>

<label class="form-label">👤 Username</label>

<input
type="text"
name="username"
class="form-control"
value="<?php echo htmlspecialchars($row['username']); ?>"
required>

<br>

<label class="form-label">🔑 Password</label>

<div class="input-group">

<input
type="text"
name="password"
id="password"
class="form-control"
value="<?php echo htmlspecialchars($row['password']); ?>"
required>

<button
type="button"
class="btn btn-secondary"
onclick="generatePassword()">

🎲 Generate

</button>

</div>

<br>

<label class="form-label">🏷 Category</label>

<select
name="category"
class="form-select">

<?php
$categories = ["Email","Banking","Social Media","Shopping","Office","Others"];

foreach($categories as $cat){
    $selected = ($row['category']==$cat) ? "selected" : "";
    echo "<option value='$cat' $selected>$cat</option>";
}
?>

</select>

<br>

<label class="form-label">📝 Notes</label>

<textarea
name="notes"
class="form-control"
rows="4"><?php echo htmlspecialchars($row['notes']); ?></textarea>

<br>

<label class="form-label">📷 Current Photo</label>

<br>

<?php

if($row['photo']!=""){

echo "<img src='".$row['photo']."' width='100' class='rounded mb-3'>";

}else{

echo "<p class='text-muted'>No Photo</p>";

}

?>

<label class="form-label">📷 Change Photo</label>

<input
type="file"
name="photo"
class="form-control">

<br>

<div class="d-grid gap-2 d-md-flex justify-content-md-between">

<button
class="btn btn-warning"
name="update">

💾 Update Account

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

<script>

function generatePassword(){

var chars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%&*!";

var pass="";

for(var i=0;i<14;i++){

pass+=chars.charAt(Math.floor(Math.random()*chars.length));

}

document.getElementById("password").value=pass;

}

</script>

</body>

</html>