<?php
// Include MongoDB extension
require '../assets/vendor/autoload.php';
require '../assets/sqlConfig.php';

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
    $streetName = $_POST['street_name'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $gender = $_POST['gender'];

    // Connect to MongoDB
    $client = new Client("mongodb://localhost:27017");
    $collection = $client->selectCollection('Guvi', 'Guvi_Users');

    // Prepare data to be inserted into MongoDB
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
        'gender' => $gender
    ];

    // Insert data into MongoDB
    $result = $collection->insertOne($data);

    // Connect to MySQL
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO user_login (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);

    // Execute SQL statement
    $stmt->execute();

    // Close prepared statement
    $stmt->close();

    // Close MySQL connection
    $conn->close();

    // Check if insert into MongoDB was successful
    if ($result->getInsertedCount() == 1) {
        echo "Data inserted successfully";
        
    } else {
        echo "Failed to insert data";
    }
} else {
    echo "No data submitted";
}
?>
