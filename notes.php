<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$user_id = (int)$_SESSION['user_id'];

if(isset($_POST['save'])){

    $note = $conn->real_escape_string($_POST['note']);

    if($note!=""){

        $conn->query("INSERT INTO vault_notes(user_id, note)
        VALUES('$user_id','$note')");

    }

    header("Location: notes.php");
    exit;
}

$result = $conn->query("
SELECT *
FROM vault_notes
WHERE user_id='$user_id'
ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>My Notes</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>

<body class="bg-light">

<div class="container mt-4">

<div class="row justify-content-center">

<div class="col-lg-8 col-md-10">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3 class="mb-0">
📝 My Notes
</h3>

</div>

<div class="card-body">

<form method="post">

<label class="form-label">Write Note</label>

<textarea
name="note"
class="form-control"
rows="4"
placeholder="Type your note here..."
required></textarea>

<br>

<button
class="btn btn-success"
name="save">

💾 Save Note

</button>

<a
href="index.php"
class="btn btn-secondary">

⬅ Back

</a>

</form>

<hr>

<h4>📋 Saved Notes</h4>

<?php while($row=$result->fetch_assoc()){ ?>

<div class="card mb-3">

<div class="card-body">

<div class="text-muted mb-2">

📅 <?php echo date("d-m-Y h:i A",strtotime($row['created_at'])); ?>

</div>

<div>

<?php echo nl2br(htmlspecialchars($row['note'])); ?>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</div>

</div>

</div>

</body>

</html>