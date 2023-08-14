<?php
// Start the session to access user_id
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

// Check if the booking_id is provided in the query parameters
if (isset($_GET['booking_id'])) {
    // Get the booking_id from the query parameters
    $booking_id = $_GET['booking_id'];

    // Assuming you have a connection to the database with your credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "barber-styles";

    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the appointment_date for the given booking_id
    $sql_get_date = "SELECT appointment_date FROM book WHERE booking_id = $booking_id";
    $result_get_date = $conn->query($sql_get_date);

    if ($result_get_date->num_rows > 0) {
        $row = $result_get_date->fetch_assoc();
        $appointment_date = $row['appointment_date'];

        // Get the current date
        $current_date = date('Y-m-d');

        // Check if the appointment date is at least one day before the current date
        if ($appointment_date >= date('Y-m-d', strtotime('+1 day'))) {
            // Delete the booking from the book table
            $sql_delete_booking = "DELETE FROM book WHERE booking_id = $booking_id";
            $conn->query($sql_delete_booking);

            // Delete the corresponding entries from book_services table
            $sql_delete_book_services = "DELETE FROM book_services WHERE booking_id = $booking_id";
            $conn->query($sql_delete_book_services);

            // Redirect back to the user dashboard with a success message
            header("Location: user_dash.php?cancelled=true");
            exit;
        } else {
            // Redirect back to the user dashboard with an error message
            header("Location: user_dash.php?error=not_allowed");
            exit;
        }
    } else {
        // Redirect back to the user dashboard with an error message
        header("Location: user_dash.php?error=not_found");
        exit;
    }
} else {
    // Redirect back to the user dashboard with an error message
    header("Location: user_dash.php?error=missing_booking_id");
    exit;
}
?>
