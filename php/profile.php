<?php
// Include Redis extension
require '../assets/vendor/autoload.php'; // Path to Redis autoload file

use Predis\Client as RedisClient;

// Redis connection parameters
$redis = new RedisClient();

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve the ObjectId from the query parameters
    $objectId = $_GET["objectId"];

    // Check if the ObjectId is present
    if (!empty($objectId)) {
        // Retrieve data from Redis using the ObjectId as key
        $redisData = $redis->hgetall('user:'.$objectId);

        // Check if data exists in Redis
        if (!empty($redisData)) {
            // Data found in Redis, output the details
            // Convert the associative array to JSON format
            $jsonData = json_encode($redisData);

            // Check if JSON encoding was successful
            if ($jsonData !== false) {
                // Output the JSON data
                echo $jsonData;
            } else {
                // Error in JSON encoding
                echo "Error encoding data to JSON format!";
            }
        } else {
            // Data not found in Redis
            echo "Data not found in Redis!";
        }
    } else {
        // ObjectId is empty or not provided
        echo "ObjectId is empty or not provided!";
    }
} else {
    // If the request method is not GET, return an error
    http_response_code(405); // Method Not Allowed
    echo "Only GET requests are allowed!";
}
?>
