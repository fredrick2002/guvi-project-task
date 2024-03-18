<?php
// Include MongoDB extension
require 'vendor/autoload.php'; // Adjust the path as necessary

use MongoDB\Client;

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];

    // Connect to MongoDB
    $client = new Client("mongodb://localhost:27017");
    $collection = $client->selectCollection('Guvi', 'Guvi_Users');

    // Prepare data to be inserted
    $data = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'password' => $password,
        'dob' => $dob,
        'phone' => $phone,
    ];

    // Insert data into MongoDB
    $result = $collection->insertOne($data);

    // Check if insert was successful
    if ($result->getInsertedCount() == 1) {
        echo "Data inserted successfully";
    } else {
        echo "Failed to insert data";
    }
} else {
    echo "No data submitted";
}
?>
