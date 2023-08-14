<?php
// Replace these credentials with your MySQL server details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barber-styles";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the signup form
    $newUsername = $_POST["username"];
    $newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Create a new MySQL connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username already exists in the database
    $sql = "SELECT user_id FROM users WHERE username='$newUsername'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "Username already exists. Please choose a different username.";
    } else {
        // Insert the new user data into the database
        $sql = "INSERT INTO users (username, password) VALUES ('$newUsername', '$newPassword')";
        if ($conn->query($sql) === TRUE) {
            echo "Signup successful! You can now login.";
            header("Location: login.html");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
