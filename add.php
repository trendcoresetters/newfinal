<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];

if(isset($_POST['save'])){

    $website = $conn->real_escape_string($_POST['website']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $category = $conn->real_escape_string($_POST['category']);
    $notes = $conn->real_escape_string($_POST['notes']);

    $photo = "";

    if(isset($_FILES['photo']) && $_FILES['photo']['name'] != ""){

        if(!is_dir("uploads")){
            mkdir("uploads",0777,true);
        }

        $filename = time()."_".basename($_FILES['photo']['name']);
        $photo = "uploads/".$filename;

        move_uploaded_file($_FILES['photo']['tmp_name'],$photo);
    }

    $sql = "INSERT INTO accounts
    (user_id,website,username,password,photo,category,notes)
    VALUES
    ('$user_id','$website','$username','$password','$photo','$category','$notes')";

    if($conn->query($sql)){
        header("Location: index.php");
        exit;
    }else{
        $error = "Error : ".$conn->error;
    }
}
?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Add Account</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>

<body class="bg-light">

<div class="container mt-4">

<div class="row justify-content-center">

<div class="col-lg-6 col-md-8 col-sm-12">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3 class="mb-0">➕ Add New Account</h3>

</div>

<div class="card-body">

<?php
if(isset($error)){
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="post" enctype="multipart/form-data">

<label class="form-label">🌐 Website</label>

<input
type="text"
name="website"
class="form-control"
required>

<br>

<label class="form-label">👤 Username</label>

<input
type="text"
name="username"
class="form-control"
required>

<br>

<label class="form-label">🔑 Password</label>

<div class="input-group">

<input
type="text"
name="password"
id="password"
class="form-control"
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

<option>Email</option>
<option>Banking</option>
<option>Social Media</option>
<option>Shopping</option>
<option>Office</option>
<option>Others</option>

</select>

<br>

<label class="form-label">📝 Notes</label>

<textarea
name="notes"
class="form-control"
rows="4"
placeholder="Optional Notes"></textarea>

<br>

<label class="form-label">📷 Photo</label>

<input
type="file"
name="photo"
class="form-control">

<br>

<div class="d-grid gap-2 d-md-flex justify-content-md-between">

<button
class="btn btn-success"
name="save">

💾 Save Account

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