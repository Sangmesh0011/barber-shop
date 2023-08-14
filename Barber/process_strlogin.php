<?php
// Start a session to store user login information
session_start();

// Replace these credentials with your MySQL server details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barber-styles";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the login form
    $inputUsername = $_POST["username1"];
    $inputPassword = $_POST["password1"];

    // Create a new MySQL connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username exists in the database
    $sql = "SELECT store_id, password1 FROM stores WHERE store_id='$inputUsername'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Verify the password
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password1"];
        if ($inputPassword==$hashedPassword) {
            // Store the user's information in the session
            $_SESSION['store_id'] = $row['store_id'];
            $_SESSION['username1'] = $inputUsername;
            header("Location: store_dash.php");
        } else {
            echo "Invalid password. Please try again.";
        }
    } else {
        echo "Username not found. Please sign up first.";
    }

    $conn->close();
}
?>
