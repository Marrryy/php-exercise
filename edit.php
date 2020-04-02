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


if(isset($_POST['make']) && isset($_POST['year']) 
&& isset($_POST['mileage']) && isset($_POST['model'])){

  if ( strlen($_POST['make']) < 1 || ($_POST['year']) < 1 || ($_POST['mileage']) < 1) {
    $_SESSION['error'] = 'All fields are required';
    header("Location: edit.php?auto_id=".$_GET['auto_id']);
    return;
}
  if(!is_numeric($_POST['year'])&& !is_numeric($_POST['mileage'])) {
    $_SESSION['error'] = "Mileage and year must be numeric";
    header("Location:edit.php?auto_id=".$_GET['auto_id']);
    return;
  }else{
    // if(isset($_POST)){
      $stmt = $pdo->prepare('UPDATE autos SET make=:mk,
      model=:md, year=:yr, mileage=:mi
      WHERE auto_id = :id');
      $stmt->execute(array(
      ':mk' => $_POST['make'],
      ':md' => $_POST['model'],
      ':yr' => $_POST['year'],
      ':mi' => $_POST['mileage'],
      ':id' => $_GET['auto_id']));
    //   print_r( $stmt);

      $_SESSION['success'] = "Record inserted";

      header("Location:index.php");
      return;
    // }

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
echo htmlentities($_SESSION['name']);
?>
"</h1>

<?php
if ( isset($_SESSION['error']) ) {
  echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
  unset($_SESSION['error']);
}
?>

</p>
<form method="post">
<p>Make:
<input type="text" name="make" size="60" value=<?php echo htmlentities($row['make'])?> /></p>
<p>Model:
<input type="text" name="model" size="60" value=<?php echo htmlentities($row['model'])?>/></p>
<p>Year:
<input type="text" name="year" value=<?php echo htmlentities($row['year'])?>/></p>
<p>Mileage:
<input type="text" name="mileage" value=<?php echo htmlentities($row['mileage'])?>/></p>
<input type="submit" value="Add">
<a href="index.php">Cancel </a>
</form>

</body>
</html>