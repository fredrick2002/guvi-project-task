<?php
require '../assets/sqlConfig.php';

// Include MongoDB extension
require '../assets/vendor/autoload.php';

use MongoDB\Client;

// MongoDB connection parameters
$mongoClient = new Client("mongodb://localhost:27017");
$mongoDatabase = $mongoClient->selectDatabase('Guvi');
$mongoCollection = $mongoDatabase->selectCollection('Guvi_Users');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the email and password from the POST data
    $email = $_POST["email"];
    $password = $_POST["pwd"];
    
    // Database connection parameters
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to retrieve user information based on email
    $sql = "SELECT * FROM user_login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user with the provided email exists in MySQL
    if ($result->num_rows == 1) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify password (plain text comparison)
        if ($password === $user["password"]) {
            // Authentication successful
            // Retrieve ObjectId from MongoDB based on email
            
            $mongoDocument = $mongoCollection->findOne(['email' => $email]);
            if ($mongoDocument !== null) {
                // Retrieve ObjectId from MongoDB document
                $objectId = (string) $mongoDocument['_id'];
                // Output success message along with ObjectId
                echo json_encode(['status' => 'Login successful!', 'objectId' => $objectId]);
            } else {
                // User not found in MongoDB
                echo "User not found in MongoDB!";
            }
        } else {
            // Authentication failed (password mismatch)
            echo "Invalid email or password!";
        }
    } else {
        // User with the provided email not found in MySQL
        echo "User not found!";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the request method is not POST, return an error
    http_response_code(405); // Method Not Allowed
    echo "Only POST requests are allowed!";
}
?>
