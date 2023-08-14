<?php
// Start the session (if not already started)
session_start();

// Check if user_id and store_id are set in the URL parameters
if (isset($_GET['user_id']) && isset($_GET['store_id'])) {
    // Get the user_id and store_id from the URL parameters
    $user_id = $_GET['user_id'];
    $store_id = $_GET['store_id'];

    // Check if the user has selected a date and it's not today's date
    if (isset($_POST['user_sel_date'])) {
        $user_sel_date = $_POST['user_sel_date'];
        // Get the current date and time in Asia/Kolkata timezone
        date_default_timezone_set('Asia/Kolkata');
        $current_date = date('Y-m-d');

        // Check if the selected date is not today's date and within the next seven days
        if ($user_sel_date >= $current_date && $user_sel_date <= date('Y-m-d', strtotime('+7 day'))) {
            // Redirect to book.php with user_id, store_id, and user_sel_date as parameters
            header("Location: book.php?user_id=$user_id&store_id=$store_id&user_sel_date=$user_sel_date");
            exit();
        } else {
            // Invalid date selection, redirect back to the previous page (stores.php)
            header("Location: stores.php");
            exit();
        }
    }
} else {
    // Redirect to the stores.php page if user_id and store_id are not set in the URL parameters
    header("Location: stores.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Check Slots</title>
</head>
<style>
    /* Your CSS styles go here */
</style>
<?php include "nav.php" ?>

<body>
    <div class="container">
        <h1>Check Available Slots</h1>
        <form action="" method="post">
            <label for="user_sel_date">Select Date:</label>
            <input type="date" id="user_sel_date" name="user_sel_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                max="<?php echo date('Y-m-d', strtotime('+7 day')); ?>">
            <input type="submit" value="Check">
        </form>
    </div>
</body>

</html>
