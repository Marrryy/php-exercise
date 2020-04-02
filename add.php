<?php
include_once "pdo.php";
session_start();
if(!isset($_SESSION['name'])){
  die("Not logged in");
}


if(isset($_POST['make']) && isset($_POST['year']) 
&& isset($_POST['mileage']) && isset($_POST['model'])){

  if ( strlen($_POST['make']) < 1 || ($_POST['year']) < 1 || ($_POST['mileage']) < 1) {
    $_SESSION['error'] = 'All fields are required';
    header("Location: add.php");
    return;
}
//   $_SESSION['error'] = "Make is required";
//   header("./add.php");
//   return;
// } else{
  // print_r($_POST);
  if(!is_numeric($_POST['year'])&& !is_numeric($_POST['mileage'])) {
    $_SESSION['error'] = "Mileage and year must be numeric";
    header("Location:add.php");
    return;
  }else{
    // if(isset($_POST)){
      $stmt = $pdo->prepare('INSERT INTO autos
      (make,model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
      $stmt->execute(array(
      ':mk' => $_POST['make'],
      ':md' => $_POST['model'],
      ':yr' => $_POST['year'],
      ':mi' => $_POST['mileage'])
      );
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
<input type="text" name="make" size="60"/></p>
<p>Model:
<input type="text" name="model" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="cancel">
</form>

</body>
</html>