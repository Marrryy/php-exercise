<?php
include_once "pdo.php";
session_start();
if(!isset($_SESSION['name'])){
    die("ACCESS DENIED");
}

if(!isset($_GET['auto_id'])){
    $_SESSION['error']="Bad value for id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM autos WHERE auto_id=:aid");
$stmt->execute(array(':aid'=> $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row === false){
    $_SESSION['error']="Bad value for id";
    header('Location: index.php');
    return;
}

if(isset($_POST['delete'])&& isset($_POST['auto_id'])){
    $stmt = $pdo->prepare("DELETE FROM autos WHERE auto_id=:aid");
    $stmt->execute(array(':aid'=> $_POST['auto_id']));
    $_SESSION['success']="Record deleted";
    header('Location: index.php');
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete item</title>
</head>
<body>
<p>
Confirm: Deleting <?=htmlentities($row["make"])?> ?
</p>


<form method="post">
<input type="hidden" name="auto_id" value="<?= $row['auto_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>


</body>
</html>


