<?php
// Start the session to access user_id and user_sel_date
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

// Get the store_id and user_sel_date from the query parameters
if (isset($_GET['store_id']) && isset($_GET['user_sel_date'])) {
    $store_id = $_GET['store_id'];
    $user_sel_date = $_GET['user_sel_date'];
} else {
    // Redirect back to stores.php if store_id and user_sel_date are not provided
    header("Location: stores.php");
    exit;
}

// Fetch available time slots for the selected store_id
$sql_slots = "SELECT * FROM book_slots WHERE store_id = $store_id";
$result_slots = $conn->query($sql_slots);

// Get the current date and time in Asia/Kolkata timezone
date_default_timezone_set('Asia/Kolkata');
$current_date = date('Y-m-d');

// Get the next day date
$next_day = date('Y-m-d', strtotime('+1 day'));

// Check if the user-selected date is valid (not today or past dates)
if ($user_sel_date <= $current_date) {
    // Redirect back to stores.php with an error message if the selected date is invalid
    header("Location: stores.php?error=invalid_date");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Appointment</title>
   
</head>

<style>
    body {
  font-family: Arial, sans-serif;
  background-color: #f8f8f8;
  color: #333;
  line-height: 1.6;
  margin: 0;
  padding: 0;
}

.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  text-align: center;
  color: #007bff;
}

form {
  text-align: center;
  margin-bottom: 20px;
}

label {
  font-weight: bold;
}

input[type="date"],
input[type="submit"] {
  padding: 8px 15px;
  font-size: 16px;
  background-color: #007bff;
  color: #fff;
  border: none;
  cursor: pointer;
}

input[type="date"] {
  margin-right: 10px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #007bff;
  color: #fff;
}

tr:hover {
  background-color: #f2f2f2;
}

.booking-container {
  margin-top: 30px;
}

.booking-date {
  text-align: center;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 20px;
}

.booking-slots {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.booking-slot {
  padding: 10px;
  margin: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  cursor: pointer;
}

.booking-slot.disabled {
  opacity: 0.6;
  pointer-events: none;
}

.booking-slot.selected {
  background-color: #007bff;
  color: #fff;
}

.services-container {
  margin-top: 30px;
}

.services-header {
  text-align: center;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 20px;
}

.service {
  margin: 10px;
}

.booking-btn {
  display: block;
  margin: 30px auto;
  padding: 10px 20px;
  font-size: 16px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.booking-btn:hover {
  background-color: #0056b3;
}

.error-message {
  text-align: center;
  color: red;
  font-weight: bold;
}

</style>
<?php include "nav.php" ?>
<body>
    <h1>Welcome to Your Appointment Booking</h1>

    <div class="container">
        <?php
        echo "<h2>Booking Appointment on $user_sel_date</h2>";
        echo "<form action='process_booking.php' method='post'>";
        echo "<input type='hidden' name='store_id' value='" . $store_id . "'>";
        echo "<input type='hidden' name='user_id' value='" . $user_id . "'>";
        echo "<input type='hidden' name='appointment_date' value='" . $user_sel_date . "'>";
        echo "<label>Select Services:</label><br>";

        // Fetch services from the "services" table to display options in the form
        $sql_services = "SELECT * FROM services";
        $result_services = $conn->query($sql_services);

        while ($row = $result_services->fetch_assoc()) {
            echo "<input type='checkbox' name='services[]' value='" . $row['service_id'] . "'>";
            echo $row['service_name'] . " - $" . $row['pricing'] . "<br>";
        }

        echo "<br>";
        echo "<label for='appointment_time'>Select Time Slot:</label><br>";

        while ($row = $result_slots->fetch_assoc()) {
            $slot_id = $row['slot_id'];
            $start_time = date('h:i A', strtotime($row['start_time']));
            $end_time = date('h:i A', strtotime($row['end_time']));

            // Check if the slot is already booked for the selected date and time
            $sql_check_booking = "SELECT * FROM book WHERE store_id = $store_id AND appointment_date = '$user_sel_date' AND slot_id = $slot_id";
            $result_check_booking = $conn->query($sql_check_booking);

            if ($result_check_booking->num_rows > 0) {
                // Slot is already booked, so disable it
                echo "<input type='radio' name='time_slot' value='$slot_id' disabled>";
            } else {
                // Slot is available for booking
                echo "<input type='radio' name='time_slot' value='$slot_id'>";
            }

            echo "$start_time to $end_time<br>";
        }
        ?>
        <br>
        <input type="submit" value="Book">
        </form>
    </div>
</body>

</html>
