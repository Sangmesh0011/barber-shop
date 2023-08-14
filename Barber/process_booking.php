<?php
// Start the session to access user_id
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $store_id = $_POST['store_id'];
    $user_id = $_POST['user_id'];
    $appointment_date = $_POST['appointment_date'];
    $time_slot = $_POST['time_slot'];
    $services = isset($_POST['services']) ? $_POST['services'] : array();

    // Validate the form data (you can add more validation as needed)
    if (empty($time_slot) || empty($services)) {
        echo "Error: Please select one time slot and at least one service.";
        exit;
    }

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

    // Create the booking in the book table
    $sql_create_booking = "INSERT INTO book (store_id, user_id, appointment_date, slot_id) VALUES ('$store_id', '$user_id', '$appointment_date', '$time_slot')";
    if ($conn->query($sql_create_booking) === TRUE) {
        $booking_id = $conn->insert_id;

        // Create the booking in the book_services table
        foreach ($services as $service_id) {
            $sql_create_booking_service = "INSERT INTO book_services (booking_id, service_id) VALUES ('$booking_id', '$service_id')";
            $conn->query($sql_create_booking_service);
        }

        echo "Booking successful! Your booking ID is: " . $booking_id;
        header( "Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the connection after inserting data
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
