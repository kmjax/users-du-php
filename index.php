<?php

// Attempt a database connection before doing anything else!

// Define database connection variables (login credentials to the MySQL database)
// NOTE: I know this is hardcoded and in GitHub... It is just for learning and this
//       is a temporary database runs locally on a training PC, not over the Internet!
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jaxcode";

// Create the database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection attempt. Fail with an error if no connection was made
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?>

<!-- ======================== BEGIN HTML PAGE ======================= -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
      name="description"
      content="PHP Homework Assignment - 2020-12-03"
    />
    <meta name="author" content="Kelsey McClanahan" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Delete/Update via PHP</title>
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<h3>Create a New User:</h3>

<form action="index.php" method="POST">
  <table>
    <tr>
      <td>
        <span class="input-text-label">First Name:</span>
      </td>
      <td>
        <input type="text" name="first_name" size="10" class="input-text">
      </td>
    </tr>
    <tr>
      <td>
        <span class="input-text-label">Last Name:</span>
      </td>
      <td>
        <input type="text" name="last_name" size="15" class="input-text">
      </td>
    </tr>
    <tr>
      <td>
        <span class="input-text-label">Email:</span>
      </td>
      <td>
        <input type="text" name="email" size="20" class="input-text">
      </td>
     </tr>
    <tr>
      <td>
        <span class="input-text-label">Password:</span>
      </td>
      <td>
        <input type="text" name="password" size="15" class="input-text">
      </td>
    </tr>
  </table>
  <button type="submit" name="addUserBtn" id="submit-button">Add User</button>
</form>

<?php

// Check and see if a $_POST record exists. It should after the Add User button 
// is Clicked. If a $_POST record exists, do the database insert. Otherwise, 
// just display the list of users below.

if (isset($_POST['addUserBtn'])) {
  // Grab the data from the  $_POST record to use it in the INSERT statement
  $first_name = $_POST['first_name'];
  $last_name  = $_POST['last_name'];
  $email      = $_POST['email'];
  $pword      = $_POST['password'];

  // Create the SQL INSERT statement that will add the new user to the database table
  $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$pword' )";

  // Now run the SQL statement; fail if there is an error
  if (mysqli_query($conn, $sql)) {
      echo "<p id='p-new-user'><span id='new-user-msg'>New user (" . $last_name . ", " . $first_name . ") added successfully!</span></p>";
  } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);        
  }
}

// The following line is simply a bit of HTML for testing the new user message...
// print "<p id='p-new-user'><span id='new-user-msg'>New user (Lastnamus, Firsty) added successfully!</span></p>";

// =====================================================
// List of Users Section
print "<hr><h3>Current List of Users:</h3>";

// Create the SQL SELECT statement to read the users
// Note: Data is returned sorted by last name, then first name
$sql = "SELECT id, first_name, last_name, email, password FROM users ORDER BY last_name, first_name";
$result = mysqli_query($conn, $sql);

// If the query returned rows from the database, display all of the records in an HTML table
if (mysqli_num_rows($result) > 0) {
  // Start the table and output the table header names
  print "<table id='users-table'>";
  print "<tr><th id='th-id'>ID</th><th id='th-last-name'>Last Name</th>";
  print "<th id='th-first-name'>First Name</th><th id='th-email'>Email</th>";
  print "<th id='th-password'>Password</th></tr>";
  
  // Loop through and output each returned database row in the form of an HTML row
  while($row = mysqli_fetch_assoc($result)) {
    // Grab the data from the result dataset
    $id          = $row['id'];
    $first_name  = $row['first_name'];
    $last_name   = $row['last_name'];
    $email       = $row['email'];
    $pword       = $row['password'];

    // Start the HTML table row
    print "<tr>";
    // Now output each table cell
    echo "<td class='col-id'>$id</td>";
    echo "<td class='col-last-name'>$last_name</td>";
    echo "<td class='col-first-name'>$first_name</td>";
    echo "<td class='col-email'>$email</td>";
    echo "<td class='col-password'>$pword</td>";
    // And close out the table row
    print "</tr>";
  }

  // After looping through all of the users, close out the table
  print "</table>";
} else {
  echo "0 results";
}

// Close the database collection
mysqli_close($conn);

?>

    
</body>
</html>
