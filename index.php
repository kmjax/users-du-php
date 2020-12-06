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

<!-- =============================================================
 ========================= BEGIN HTML PAGE =======================
 ================================================================= -->

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

<?php


// Only display the CREATE NEW USER fields on the screen IF we are not in
// a mode to update an existing user's data. In otherwords, hide CREATE 
// NEW USER if we are updating user data or changing a user's password.

if (!(isset($_POST['updateBtn']) or isset($_POST['changePwordBtn']))) {
  print "<h3>Create a New User:</h3>";
  print "<form action='index.php' method='POST'>";
  print "<table>";
  print "<tr><td><span class='input-text-label'>First Name:</span></td>";
  print "<td><input type='text' name='first_name' size='10' class='input-text'></td>";
  print "</tr>";
  print "<tr><td><span class='input-text-label'>Last Name:</span></td>";
  print "<td><input type='text' name='last_name' size='15' class='input-text'></td>";
  print "</tr>";
  print "<tr><td><span class='input-text-label'>Email:</span></td>";
  print "<td><input type='text' name='email' size='20' class='input-text'></td>";
  print "</tr>";
  print "<tr><td><span class='input-text-label'>Password:</span></td>";
  print "<td><input type='text' name='password' size='15' class='input-text'></td>";
  print "</tr>";
  print "</table>";
  print "<button type='submit' name='addBtn' id='add-btn'>Add User</button>";
  print "</form>";
}



/*************************************************
 INSERT STATEMENT (CREATE)
 ************************************************/

// Check and see if a $_POST record exists. It should after the Add User button 
// is Clicked. If a $_POST record exists, grab the fields and do the database 
// insert. Otherwise, check for other button actions.

if (isset($_POST['addBtn'])) {
  // Grab the data from the  $_POST record to use it in the INSERT statement
  $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
  $last_name  = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
  $email      = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $pword      = $_POST['password'];
  $hashPword = password_hash($pword, PASSWORD_DEFAULT);

  // Create the SQL INSERT statement that will add the new user to the database table
  $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$hashPword' )";

  // Now run the SQL statement; fail if there is an error
  if (mysqli_query($conn, $sql)) {
      print "<p id='p-action-msg'><span id='action-msg'>New user ($first_name $last_name) added successfully!</span></p>";
  } else {
      print "Error: $sql <br><br>" . mysqli_error($conn);
  }
}

/*************************************************
 DELETE STATEMENT (DELETE)
 ************************************************/
// Check to see if the delete button was pushed, if so delete the row
// associated with the user ID from the database

 if (isset($_POST['deleteBtn'])) {
  
  $userID = $_POST['userID'];
  $sql = "DELETE FROM users WHERE id = $userID";

    if (mysqli_query($conn, $sql)) {
      print "<p id='p-action-msg'><span id='action-msg'>User (ID=" . $userID . ") deleted successfully!</span></p>";
    } else {
      print "Error: $sql <br><br>" . mysqli_error($conn);
    }
}


/*************************************************
 UPDATE STATEMENT (UPDATE)
 ************************************************/

// Check to see if the update button was pushed, if so, display the 
// text fields for updating the user's info. Place the previous info
// into the fields to make it easier for them to edit.

if (isset($_POST['updateBtn'])) {
  $userID =    $_POST['userID'];
  $firstName = $_POST['firstName'];
  $lastName =  $_POST['lastName'];
  $email =     $_POST['email'];

  // Display textboxes and confirm/cancel buttons to update the user info
  print "<h3>Edit exist user's information:</h3>";
  print "<form action='index.php' method='POST'>";
  print "<input type='hidden' name='userID' value='$userID'>";
  print "<table><tr>";
  print "<td><span class='input-text-label'>User ID:</span></td>";
  print "<td>$userID</td>";
  print "</tr>";
  print "<td><span class='input-text-label'>First Name:</span></td>";
  print "<td><input type='text' name='firstName' size='10' class='update-text' value='$firstName'></td>";
  print "</tr>";
  print "<tr><td><span class='input-text-label'>Last Name:</span></td>";
  print "<td><input type='text' name='lastName' size='15' class='update-text' value='$lastName'></td>";
  print "</tr>";
  print "<tr><td><span class='input-text-label'>Email:</span></td>";
  print "<td><input type='text' name='email' size='20' class='update-text' value='$email'></td>";
  print "</tr>";
  print "</tr></table>";
  print "<button type='submit' name='confirmUpdateBtn' id='confirm-update-btn'>Confirm Update</button>";
  print "</form>";

  print "<form action='index.php' method='POST'>";
  print "<button type='submit' name='cancelUpdateBtn' id='cancel-update-btn'>Cancel Update</button>";
  print "</form>";
}

// If the CONFIRM was pushed, update the user's data in the database!
if (isset($_POST['confirmUpdateBtn'])) {
  $userID =    $_POST['userID'];
  $firstName = filter_var($_POST['firstName'], FILTER_SANITIZE_STRING);
  $lastName =  filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);
  $email =     filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

  $sql = "UPDATE users SET first_name = '$firstName', last_name = '$lastName', email = '$email' WHERE id = $userID";

    if (mysqli_query($conn, $sql)) {
      print "<p id='p-action-msg'><span id='action-msg'>User data updated successfully!</span></p>";
    } else {
      print "Error: $sql <br><br>" . mysqli_error($conn);
    }
}

if (isset($_POST['cancelUpdateBtn'])) {
  print "<p id='p-action-msg'><span id='action-msg'>Update cancelled. No user data was modified.</span></p>";
}


/*************************************************
 UPDATE STATEMENT #2 (UPDATE) - CHANGE PASSWORD
 ************************************************/

// Check to see if the change password button was pushed, if so, display a 
// text field for updating the user's password. 

if (isset($_POST['changePwordBtn'])) {
  $userID =    $_POST['userID'];
  $firstName = $_POST['firstName'];
  $lastName =  $_POST['lastName'];
  
  // Display textboxes and confirm/cancel buttons to update the user info
  print "<h3>Change the user's password:</h3>";
  print "<form action='index.php' method='POST'>";
  print "<input type='hidden' name='userID' value='$userID'>";
  print "<table><tr>";
  print "<td><span class='input-text-label'>User:</span></td>";
  print "<td>ID=$userID, $lastName, $firstName</td>";
  print "</tr>";
  print "<tr><td><span class='input-text-label'>Password:</span></td>";
  print "<td><input type='text' name='pword' size='15' class='update-text' placeholder='Enter New Password'></td>";
  print "</tr>";
  print "</tr></table>";
  print "<button type='submit' name='confirmChangePwordBtn' id='confirm-update-btn'>Confirm Change</button>";
  print "</form>";

  print "<form action='index.php' method='POST'>";
  print "<button type='submit' name='cancelChangePwordBtn' id='cancel-update-btn'>Cancel Change</button>";
  print "</form>";
}

// If the CONFIRM CHANGE was pushed, update the user's data in the database!
if (isset($_POST['confirmChangePwordBtn'])) {
  $userID =    $_POST['userID'];
  $pword =     $_POST['pword'];
  $hashPword = password_hash($pword, PASSWORD_DEFAULT);
  
  $sql = "UPDATE users SET password = '$hashPword' WHERE id = $userID";

    if (mysqli_query($conn, $sql)) {
      print "<p id='p-action-msg'><span id='action-msg'>User's password changed successfully!</span></p>";
    } else {
      print "Error: $sql <br><br>" . mysqli_error($conn);
    }
}

if (isset($_POST['cancelChangePwordBtn'])) {
  print "<p id='p-action-msg'><span id='action-msg'>Change cancelled. The user's password was not changed.</span></p>";
}


/*************************************************
 LIST OF USERS (SELECT)
 ************************************************/

print "<hr><h3>Current List of Users:</h3>";

// Create the SQL SELECT statement to read the users
// Note: Data is returned sorted by last name, then first name
$sql = "SELECT id, first_name, last_name, email, password FROM users ORDER BY last_name, first_name";
$result = mysqli_query($conn, $sql);
$userCount = 0;

// If the query returned rows from the database, display all of the records in an HTML table
if (mysqli_num_rows($result) > 0) {
  // Start the table and output the table header names
  print "<table id='users-table'>";
  print "<tr>";
  print "<th id='th-id'>ID</th>";
  print "<th id='th-last-name'>Last Name</th>";
  print "<th id='th-first-name'>First Name</th>";
  print "<th id='th-email'>Email</th>";
  print "<th id='th-password' width='50'>Password</th>";
  print "<th id='th-actions'>Actions</th>";
  print "</tr>";
  
  // Loop through and output each returned database row in the form of an HTML row
  while($row = mysqli_fetch_assoc($result)) {
    // Grab the data from the result dataset
    $userID     = $row['id'];
    $firstName  = $row['first_name'];
    $lastName   = $row['last_name'];
    $email      = $row['email'];
    $pword      = "&lt;&#8727;&#8727;&#8727;&#8727;&gt;";

    // Start the HTML table row
    print "<tr>";
    // Now output each table cell
    print "<td class='col-id'>$userID</td>";
    print "<td class='col-last-name'>$lastName</td>";
    print "<td class='col-first-name'>$firstName</td>";
    print "<td class='col-email'>$email</td>";
    print "<td class='col-password'>$pword</td>";
    
    // Now show the actions column buttons
    print "<td class='col-actions'><div style='display: flex'>";
    // UPDATE Button
    print "<div><form action='index.php' method='POST'>";
    print "<input type='hidden' name='userID' value='$userID'>";
    print "<input type='hidden' name='firstName' value='$firstName'>";
    print "<input type='hidden' name='lastName' value='$lastName'>";
    print "<input type='hidden' name='email' value='$email'>";
    print "<button type='submit' name='updateBtn' class='update-btn'>UPDATE</button>";
    print "</form></div>";

    // Change Password Button
    print "<div><form action='index.php' method='POST'>";
    print "<input type='hidden' name='userID' value='$userID'>";
    print "<input type='hidden' name='firstName' value='$firstName'>";
    print "<input type='hidden' name='lastName' value='$lastName'>";
    print "<button type='submit' name='changePwordBtn' class='update-btn'>CHANGE PASSWORD</button>";
    print "</form></div>";

    // DELETE Button
        print "<div><form action='index.php' method='POST'>";
    print "<input type='hidden' name='userID' value='$userID'>";
    print "<button type='submit' name='deleteBtn' class='delete-btn'>DELETE</button>";
    print "</form></div>";
    print "</div></td>";
    // And close out the table row
    print "</tr>";

    // Increment the user count
    $userCount++;
  }

  // After looping through all of the users, close out the table
  // and print out the user count
  print "</table>";
  print "<br><br>User count: $userCount <br><hr><br>";
} else {
  print "0 results; the database is empty.<br>Add some users!";
}

// Close the database collection
mysqli_close($conn);

?>

    
</body>
</html>
