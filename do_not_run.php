<?php
// Database connection parameters
$host = 'localhost'; // Your database host
$user = 'root';  // Your database username
$pass = '';  // Your database password
$dbname = 'ea_ra_hardware'; // Your database name

// Create a connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all table names
$result = $conn->query("SHOW TABLES");
if ($result) {
    while ($row = $result->fetch_array()) {
        $tableName = $row[0];
        // Truncate the table
        $truncateQuery = "TRUNCATE TABLE `$tableName`";
        if ($conn->query($truncateQuery) === TRUE) {
            echo "Table $tableName truncated successfully.<br>";
        } else {
            echo "Error truncating table $tableName: " . $conn->error . "<br>";
        }
    }
} else {
    echo "Error retrieving tables: " . $conn->error;
}

// Close the connection
$conn->close();
?>