<?php

// Include Redis extension
require 'vendor/autoload.php';  // Path to Redis autoload file

use Predis\Client as RedisClient;

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get objectId from URL parameters
    parse_str(file_get_contents("php://input"), $data);
    $objectId = $data['objectId'];

    // Redis connection parameters
    $redis = new RedisClient();

    // Clear Redis storage for the specified objectId
    $redis->del('user:' . $objectId);

    // Respond with a success message
    echo "Redis storage cleared successfully.";
} else {
    // If the request method is not DELETE, return an error
    http_response_code(405); // Method Not Allowed
    echo "Only DELETE requests are allowed!";
}

?>
