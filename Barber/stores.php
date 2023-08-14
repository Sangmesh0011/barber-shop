<?php
// Start the session (if not already started)
session_start();


?>
<!DOCTYPE html>
<html>

<head>
    <title>Barber Stores</title>
</head>
<style>


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

        input[type="text"] {
            padding: 6px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 8px 15px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
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

        a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
<?php include "nav.php" ?>
<body>

   
 <br><br>
    <!-- Search Bar -->
    <form action="stores.php" method="get">
        <label for="search">Search Stores:</label>
        <input type="text" id="search" name="search_query" placeholder="Enter store name, city, or state">
        <input type="submit" value="Search">
    </form>

    <?php
    // PHP code to fetch and display stores
    // Replace the database credentials with your actual database details
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

    // Get the search query from the URL parameter (if provided)
    $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

    // Build the SQL query based on the search query
    $sql = "SELECT * FROM stores";
    if (!empty($search_query)) {
        $sql .= " WHERE store_name LIKE '%$search_query%' OR city LIKE '%$search_query%' OR state LIKE '%$search_query%'";
    }

    // Execute the SQL query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display stores in a table
        echo "<table>";
        echo "<tr><th>Store Name</th><th>Address</th><th>City</th><th>State</th><th>Pincode</th><th>Select</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['store_name'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['city'] . "</td>";
            echo "<td>" . $row['state'] . "</td>";
            echo "<td>" . $row['pincode'] . "</td>";
            // Add the "Select" option as a hyperlink to book.php with store_id as a parameter
            if (isset($_SESSION['user_id'])) {
                // Add the "Select" option as a hyperlink to book.php with store_id and user_id as parameters
                echo "<td><a href='checkslots.php?store_id=" . $row['store_id'] . "&user_id=" . $_SESSION['user_id'] . "'>Select</a></td>";
            } else {
                echo "<td>Login to Book</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No stores found.";
    }

    $conn->close();
    ?>
</body>

</html>