<?php
// Include configuration file
require 'sqlConfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    // Get email from POST data
    $email = $_POST['email'];

    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to check if email exists
    $stmt = $conn->prepare("SELECT * FROM user_login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if email exists
    if ($result->num_rows > 0) {
        // Email exists
        echo 'false';

    } else {
        // Email is available
        echo 'true';
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    echo 'Invalid request';
}
?>
