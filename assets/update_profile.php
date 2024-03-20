<?php
// Assuming you have a separate file (e.g., config.php) for database credentials
require '../assets/vendor/autoload.php';

use MongoDB\Client;
use Predis\Client as RedisClient;

// Error handling for missing POST data
if (empty($_POST['objectId']) || empty($_POST['dob']) || // Check for required fields
    empty($_POST['gender']) || empty($_POST['phone']) || empty($_POST['street_name']) ||
    empty($_POST['city']) || empty($_POST['state']) || empty($_POST['pincode'])) {
  echo json_encode(['error' => 'Missing required data in POST request']);
  exit;
}

// MongoDB connection parameters
$mongoClient = new Client("mongodb://localhost:27017");
$mongoDatabase = $mongoClient->selectDatabase('Guvi');
// $mongoCollection = $mongoDatabase->selectCollection('Guvi_Users');

// Extract data from POST request
$objectId = $_POST['objectId'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$streetName = $_POST['street_name'];
$city = $_POST['city'];
$state = $_POST['state'];
$pincode = $_POST['pincode'];

$redis = new RedisClient();

// Update MongoDB (asynchronously)
$collection = $mongoDatabase->selectCollection('Guvi_Users');  // Assuming collection name is 'users' (lowercase)
$filter = ['_id' => new MongoDB\BSON\ObjectId($objectId)];
$update = [
    '$set' => [
        'dob' => $dob,
        'gender' => $gender,
        'phone' => $phone,
        'street_name' => $streetName,
        'city' => $city,
        'state' => $state,
        'pincode' => $pincode
    ]
];
$updateResult = $collection->updateOne($filter, $update);

if ($updateResult->getModifiedCount() > 0) {
    $userData = [
        'dob' => $dob,
        'gender' => $gender,
        'phone' => $phone,
        'street_name' => $streetName,
        'city' => $city,
        'state' => $state,
        'pincode' => $pincode
    ];
    $redis->set('user:' . $objectId, json_encode($userData));

} else {
    // Handle potential scenarios for no update (e.g., document not found)
    if ($updateResult->getUpsertedId()) {
        echo json_encode(['message' => 'No document matched the filter. Inserted a new document.']);
    } else {
        echo json_encode(['error' => 'Failed to update user data in MongoDB']);
    }
}
?>
