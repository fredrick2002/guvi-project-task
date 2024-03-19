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
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $streetName = $_POST['street_name'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $gender = $_POST['gender']; // Add this line to retrieve gender

    // Connect to MongoDB
    $client = new Client("mongodb://localhost:27017");
    $collection = $client->selectCollection('Guvi', 'Guvi_Users');

    // Prepare data to be inserted
    $data = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'dob' => $dob,
        'phone' => $phone,
        'street_name' => $streetName,
        'city' => $city,
        'state' => $state,
        'pincode' => $pincode,
        'gender' => $gender // Include gender in the data array
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
