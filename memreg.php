<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.3.0/css/material-fullpalette.min.css">
<?php
$host = "dbserver.engr.scu.edu";
$user = "mwerner";
$password = "00001013261";
$db = "sdb_mwerner";

// Assumes database format:
// Members(membID, name, email) [I've been using VARCHAR(30) for all columns]
$error;
// Check for blank fields
if($_POST['name'] == '' || $_POST['email'] == '' || $_POST['address'] == '' || $_POST['phone'] == ''){
  $error = "Do not leave any fields blanks!";
}
// Connect to DB and connect
$connection = mysqli_connect($host, $user, $password, $db);
if (!$connection) {
  $error = 'Could not connect: ' . mysqli_error($connection);
}

// Generate the most cryptograhpically insecure Member ID imaginable
// (a careful dictionary attack could probably crack this in less than a minute)
// But hey, this is Web Dev, not Cryptography.
$membID = strtoupper(substr($_POST['name'], 0, 3));
$membID .= dechex(mt_rand(10000, 99999));
$membID = substr($membID, 0, 8);

// SQL Escapes
$membID = mysqli_real_escape_string($connection,$membID);
$name = mysqli_real_escape_string($connection,$_POST['name']);
$email = mysqli_real_escape_string($connection,$_POST['email']);
$address = mysqli_real_escape_string($connection,$_POST['address']);
$phone = mysqli_real_escape_string($connection,$_POST['phone']);


$statement = "INSERT INTO `Members` (`membID`, `name`, `email`, `address`, `phone`)
VALUES ('$membID', '$name', '$email', '$address', '$phone')";

// Try to insert into table
if(!isset($error)){
  $result = $connection->query($statement);
  if (!$result){
    $error = 'Error: ' . mysqli_error($connection);
  }
}

// If an error occurred, display the error message
if(isset($error)){
  ?>
  <div class="alert alert-dismissible alert-danger">
    <strong><?=$error?></strong><br>
  </div>
  <?php
}
// Otherwise, alert success and return the Member ID
else{
  ?>
  <div class="alert alert-dismissible alert-success">
    <strong>Successfully registered:</strong><br>
    <?php
    echo "<strong>Name: </strong>", $name, "<br>";
    echo "<strong>Email: </strong>", $email, "<br>";
    echo "<strong>Phone Number: </strong>", $phone, "<br>";
    echo "<strong>Address: </strong>", $address, "<br>";
    echo "<strong>Member ID: </strong>", $membID, "<br>";
    ?>
  </div>
  <div class="alert alert-dismissible alert-info">
    <strong>Hey!</strong> Make note of your member ID, it will function as your password!<br>
  </div>
  <?php
}
?>
<a href="index.html" class="btn btn-link">Back home</a>
<a href="register.html" class="btn btn-link">Back to registration</a>

<?php
// Close database connection
mysqli_close($connection);
?>
