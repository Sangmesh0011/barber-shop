<?php
// Start the session to access user_id
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

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

// Get the bookings for the user from the database
$sql = "SELECT book.booking_id, book.appointment_date, book.status, book_slots.start_time, book_slots.end_time, GROUP_CONCAT(services.service_name) AS service_names, SUM(services.pricing) AS total_price, book.store_id, stores.str_contact
        FROM book
        INNER JOIN book_slots ON book.slot_id = book_slots.slot_id
        INNER JOIN book_services ON book.booking_id = book_services.booking_id
        INNER JOIN services ON book_services.service_id = services.service_id
        INNER JOIN stores ON book.store_id = stores.store_id
        WHERE book.user_id = $user_id
        GROUP BY book.booking_id, book.appointment_date, book.status, book_slots.start_time, book_slots.end_time, book.store_id, stores.str_contact
        ORDER BY book.appointment_date DESC, book_slots.start_time ASC";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>
    <style>
       body {
  font-family: Arial, sans-serif;
  background-color: #f8f8f8;
  color: #333;
  line-height: 1.6;
  margin: 0;
  padding: 0;
}

h1 {
  text-align: center;
  padding: 20px 0;
  background-color: #333;
  color: #fff;
}

.booking {
  border: 1px solid #ccc;
  background-color: #fff;
  padding: 20px;
  margin: 20px auto;
  max-width: 600px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.booking h2 {
  margin: 0;
  color: #333;
}

.booking p {
  margin: 10px 0;
}

.booking p:first-child {
  font-size: 1.2rem;
}

.booking p:last-child {
  font-size: 1.1rem;
  color: #555;
}

.cancel-btn {
  display: inline-block;
  padding: 10px 20px;
  background-color: #e74c3c;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

.cancel-btn:hover {
  background-color: #c0392b;
}

.call-store-btn {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #2ecc71;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-decoration: none;
}

.call-store-btn:hover {
  background-color: #27ae60;
}

    </style>
</head>
<?php include "nav.php"?>
<body>
    <h1>Welcome to Your Dashboard</h1>

    <?php
    if ($result->num_rows > 0) {
        // Display the bookings
        while ($row = $result->fetch_assoc()) {
            echo "<div class='booking'>";
            echo "<h2>Booking ID: " . $row['booking_id'] . "</h2>";
            echo "<p>Appointment Date: " . $row['appointment_date'] . "</p>";
            echo "<p>Time Slot: " . $row['start_time'] . " - " . $row['end_time'] . "</p>";
            echo "<p>Services: " . $row['service_names'] . "</p>";
            echo "<p>Total Price: $" . $row['total_price'] . "</p>";
            echo "<p>Status: " . $row['status'] . "</p>";

            // Check if the cancellation is allowed (i.e., before the appointment date)
            $current_date = date('Y-m-d');
            $appointment_date = $row['appointment_date'];
            if ($current_date < $appointment_date) {
                // Display the "Cancel Booking" button
                echo "<form action='process_cancellation.php' method='post'>";
                echo "<input type='hidden' name='booking_id' value='" . $row['booking_id'] . "'>";
                echo "<a href='process_cancellation.php?booking_id=" . $row['booking_id'] . "' class='cancel-btn'>Cancel Booking</a>";
                echo "</form>";
            } else {
                // Cancellation not allowed, show an error message or handle as needed
                echo "<p>Booking cancellation is not allowed as the appointment date has passed.</p>";
            }

            // Display the "Call Store" button
            echo "<a href='tel:" . $row['str_contact'] . "' class='call-store-btn'>Call Store</a>";

            echo "</div>";
        }
    } else {
        echo "<p>No bookings found.</p>";
    }
    ?>

</body>

</html>
