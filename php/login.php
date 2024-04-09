<?php
require '../assets/sqlConfig.php';

// Include MongoDB extension
require '../assets/vendor/autoload.php';

use MongoDB\Client;
use Predis\Client as RedisClient;

// MongoDB connection parameters
$mongoClient = new Client("mongodb://localhost:27017");
$mongoDatabase = $mongoClient->selectDatabase('Guvi');
$mongoCollection = $mongoDatabase->selectCollection('Guvi_Users');

$redis = new RedisClient();

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["email"]) && isset($_GET["pwd"])) {
    // Retrieve the email and password from the GET parameters
    $email = $_GET["email"];
    $password = $_GET["pwd"];
    
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
                echo json_encode(['status' => 'success', 'objectId' => $objectId]);
                $hashKey = 'user:' . $objectId;
                foreach ($mongoDocument as $field => $value) {
                    // Use HSET command to set field in Redis hash
                    $redis->hset($hashKey, $field, $value);
                }
        
            } else {
                // User not found in MongoDB
                echo json_encode(['status' => 'User not found in MongoDB!']);
            }
        } else {
            // Authentication failed (password mismatch)
            echo json_encode(['status' => 'Invalid email or password!']);
        }
    } else {
        // User with the provided email not found in MySQL
        echo json_encode(['status' => 'User not found!']);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the request method is not GET or if email and password are not provided, return an error
    echo json_encode(['status' => 'Invalid request!']);
}
?>
