<?php

// Assuming you have a separate file (e.g., config.php) for database credentials
require '../assets/vendor/autoload.php';

use MongoDB\Client;
use Predis\Client as RedisClient;

// Error handling for missing PATCH data
if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only PATCH requests are allowed']);
    exit;
}

// Read the input from PATCH request
$patchData = file_get_contents('php://input');
$data = json_decode($patchData, true);
echo $patchData;

// Error handling for missing data
if (!$data || empty($data['objectId'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing required data in PATCH request']);
    exit;
}

// Extract data from PATCH request
$objectId = $data['objectId'];

// MongoDB connection parameters
$mongoClient = new Client("mongodb://localhost:27017");
$mongoDatabase = $mongoClient->selectDatabase('Guvi');
$collection = $mongoDatabase->selectCollection('Guvi_Users'); // Assuming collection name is 'Guvi_Users'

// Build update query
$updateQuery = ['$set' => []];

// Check if other fields are provided in PATCH request and add them to update query
foreach ($data as $key => $value) {
    if ($key !== 'objectId') {
        $updateQuery['$set'][$key] = $value;
    }
}

// Update MongoDB
$filter = ['_id' => new MongoDB\BSON\ObjectId($objectId)];
$updateResult = $collection->updateOne($filter, $updateQuery);

// Handle update result
if ($updateResult->getModifiedCount() > 0) {
    // If any field was updated, update Redis as well
    $redis = new RedisClient();
    $userData = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($objectId)]);
    $redis->set('user:' . $objectId, json_encode($userData));
    
    echo json_encode(['message' => 'Profile updated successfully']);
} else {
    // Handle potential scenarios for no update (e.g., document not found)
    if ($updateResult->getUpsertedId()) {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'No document matched the filter. Inserted a new document.']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to update user data in MongoDB']);
    }
}

?>
