<?php
include_once "pdo.php";
if(!isset($_GET['name'])){
  die("Name parameter missing");
}

$message="";

if(!isset($_POST['make']) && !isset($_POST['year']) && !isset($_POST['mileage']) ){
  $message= "Make is required";
} else{
  print_r($_POST);
  if(is_numeric($_POST['year'])&& is_numeric($_POST['mileage'])) {
    $message = "Mileage and year must be numeric";
  }else{

    if(isset($_POST)){
      $stmt = $pdo->prepare('INSERT INTO autos
      (make, year, mileage) VALUES ( :mk, :yr, :mi)');
      $stmt->execute(array(
      ':mk' => $_POST['make'],
      ':yr' => $_POST['year'],
      ':mi' => $_POST['mileage'])
      );
    }

  } 
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mary Franklin - Automobile Tracker</title>
</head>
<body>
<div class="container">
<h1>Tracking Autos for "
<?php 
echo htmlentities($_GET['name']);
?>
"</h1>
<p style="color: red;">
<?  echo $message; ?>


</p>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT * FROM autos");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  echo "<li>".$row['year']." ".$row['make']." / ".$row['mileage']."</li>";
}

?>
</ul>
</body>
</html>