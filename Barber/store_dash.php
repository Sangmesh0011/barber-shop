<?php
// Start the session (if not already started)
session_start();

// Check if the store is logged in
if (!isset($_SESSION['store_id'])) {
    // Redirect to the store login page if not logged in
    header("Location: store_login.php");
    exit;
}

// Get the store_id from the session
$store_id = $_SESSION['store_id'];

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

// Function to get booking details
function getBookingDetails($conn, $booking_id)
{
    $sql = "SELECT book.booking_id, book.user_id, book.slot_id, book.appointment_date, book.status, book_slots.start_time, book_slots.end_time, GROUP_CONCAT(services.service_name) AS service_names, SUM(services.pricing) AS total_price
            FROM book
            INNER JOIN book_slots ON book.slot_id = book_slots.slot_id
            INNER JOIN book_services ON book.booking_id = book_services.booking_id
            INNER JOIN services ON book_services.service_id = services.service_id
            WHERE book.booking_id = $booking_id
            GROUP BY book.booking_id, book.appointment_date, book.status, book_slots.start_time, book_slots.end_time";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Check if the user wants to mark a booking as completed or cancelled
if (isset($_POST['mark_complete'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    // Update the status of the booking in the book table
    $sql_update = "UPDATE book SET status = '$status' WHERE booking_id = $booking_id";
    $conn->query($sql_update);

    // Insert the booking into the book_history table
    $booking_details = getBookingDetails($conn, $booking_id);
    if ($booking_details) {
        $user_id = $booking_details['user_id'] ?? null;
        $appointment_date = $booking_details['appointment_date'] ?? null;
        $slot_id = $booking_details['slot_id'] ?? null;

        // Check if the required data is not null before inserting into book_history table
        if ($user_id !== null && $appointment_date !== null && $slot_id !== null) {
            // Prepare the insert query to avoid SQL injection
            $stmt = $conn->prepare("INSERT INTO book_history (store_id, user_id, slot_id, appointment_date, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $_SESSION['store_id'], $user_id, $slot_id, $appointment_date, $status);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // The booking is successfully marked as completed/cancelled and inserted into book_history
                // Now, delete the booking from the book table
                $sql_delete_booking = "DELETE FROM book WHERE booking_id = $booking_id";
                $conn->query($sql_delete_booking);
            } else {
                echo "Error inserting into book_history: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Missing data for book_history insertion.";
        }
    } else {
        echo "Booking details not found.";
    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>Store Dashboard</title>
    <style>
        /* Reset default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Set body font and background color */
body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    padding: 20px;
}

/* Center align main content */
.container {
    max-width: 800px;
    margin: 0 auto;
}

h1, h2, h3 {
    text-align: center;
}

.booking {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

.booking h3 {
    margin-bottom: 10px;
}

.booking p {
    margin-bottom: 5px;
}

.booking button {
    padding: 8px 15px;
    font-size: 14px;
    border: none;
    cursor: pointer;
}

.complete-btn {
    background-color: #28a745;
    color: #fff;
}

.cancel-btn {
    background-color: #dc3545;
    color: #fff;
}

button {
    margin-top: 10px;
}

/* Style the table for booking history */
.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #007bff;
    color: #fff;
}

table tr:hover {
    background-color: #f2f2f2;
}

    </style>
</head>

<body>
    <h1>Welcome to Your Dashboard</h1>

    <h2>Current Bookings</h2>
    <?php
    // Fetch current bookings for the store from the book table
    $sql_current_bookings = "SELECT * FROM book WHERE store_id = $store_id AND status = 'pending'";
    $result_current_bookings = $conn->query($sql_current_bookings);

    if ($result_current_bookings->num_rows > 0) {
        while ($row = $result_current_bookings->fetch_assoc()) {
            $booking_id = $row['booking_id'];
            $appointment_date = $row['appointment_date'];
            $status = $row['status'];
            $user_id = $row['user_id'];

            // Fetch user details for the booking from the users table
            $sql_user = "SELECT * FROM users WHERE user_id = $user_id";
            $result_user = $conn->query($sql_user);
            $user_details = $result_user->fetch_assoc();
            $user_name = $user_details['username'];

            // Fetch slot details for the booking from the book_slots table
            $slot_id = $row['slot_id'];
            $sql_slot = "SELECT * FROM book_slots WHERE slot_id = $slot_id";
            $result_slot = $conn->query($sql_slot);
            $slot_details = $result_slot->fetch_assoc();
            $start_time = $slot_details['start_time'];
            $end_time = $slot_details['end_time'];

            // Fetch service details for the booking from the book_services table
            $sql_services = "SELECT * FROM book_services INNER JOIN services ON book_services.service_id = services.service_id WHERE booking_id = $booking_id";
            $result_services = $conn->query($sql_services);
            $service_names = [];
            while ($service = $result_services->fetch_assoc()) {
                $service_names[] = $service['service_name'];
            }

            echo "<div class='booking'>";
            echo "<h3>Booking ID: " . $booking_id . "</h3>";
            echo "<p>User: " . $user_name . "</p>";
            echo "<p>Appointment Date: " . $appointment_date . "</p>";
            echo "<p>Time Slot: " . $start_time . " - " . $end_time . "</p>";
            echo "<p>Services: " . implode(", ", $service_names) . "</p>";
            echo "<p>Status: " . $status . "</p>";

            // Display the buttons to mark the booking as completed or cancelled
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='booking_id' value='" . $booking_id . "'>";
            echo "<input type='hidden' name='status' value='completed'>";
            echo "<button type='submit' class='complete-btn' name='mark_complete'>Mark as Complete</button>";
            echo "</form>";

            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='booking_id' value='" . $booking_id . "'>";
            echo "<input type='hidden' name='status' value='cancelled'>";
            echo "<button type='submit' class='cancel-btn' name='mark_complete'>Cancel Booking</button>";
            echo "</form>";

            echo "</div>";
        }
    } else {
        echo "<p>No current bookings found.</p>";
    }
    ?>

    <h2>Booking History</h2>
    <?php
    // Fetch booking history for the store from the book_history table
    $sql_history = "SELECT * FROM book_history WHERE store_id = $store_id";
    $result_history = $conn->query($sql_history);

    if ($result_history->num_rows > 0) {
        while ($row = $result_history->fetch_assoc()) {
            $booking_id = $row['booking_id'];
            $appointment_date = $row['appointment_date'];
            $status = $row['status'];
            $user_id = $row['user_id'];

            // Fetch user details for the booking from the users table
            $sql_user = "SELECT * FROM users WHERE user_id = $user_id";
            $result_user = $conn->query($sql_user);
            $user_details = $result_user->fetch_assoc();
            $user_name = $user_details['username'];

            // Fetch slot details for the booking from the book_slots table
            $slot_id = $row['slot_id'];
            $sql_slot = "SELECT * FROM book_slots WHERE slot_id = $slot_id";
            $result_slot = $conn->query($sql_slot);
            $slot_details = $result_slot->fetch_assoc();
            $start_time = $slot_details['start_time'];
            $end_time = $slot_details['end_time'];

            // Fetch service details for the booking from the book_services table
            $sql_services = "SELECT * FROM book_services INNER JOIN services ON book_services.service_id = services.service_id WHERE booking_id = $booking_id";
            $result_services = $conn->query($sql_services);
            $service_names = [];
            while ($service = $result_services->fetch_assoc()) {
                $service_names[] = $service['service_name'];
            }

            echo "<div class='booking'>";
            echo "<h3>Booking ID: " . $booking_id . "</h3>";
            echo "<p>User: " . $user_name . "</p>";
            echo "<p>Appointment Date: " . $appointment_date . "</p>";
            echo "<p>Time Slot: " . $start_time . " - " . $end_time . "</p>";
            echo "<p>Services: " . implode(", ", $service_names) . "</p>";
            echo "<p>Status: " . $status . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No booking history found.</p>";
    }
    ?>

</body>

</html>
