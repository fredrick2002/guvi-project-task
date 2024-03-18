<?php
require 'vendor/autoload.php'; // If using Composer

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $serverStatus = $client->admin->command(['serverStatus' => 1]);
    echo "Connected successfully to server";
    print_r($serverStatus);
} catch (Exception $e) {
    echo "Failed to connect to MongoDB: " . $e->getMessage();
}
?>