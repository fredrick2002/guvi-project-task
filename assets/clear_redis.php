<?php

// Include Redis extension
require 'vendor/autoload.php';  // Path to Redis autoload file

use Predis\Client as RedisClient;

$objectId = $_POST['objectId'];
// Redis connection parameters
$redis = new RedisClient();

// Clear Redis storage
// $redis->flushdb();

$redis->del('user:' . $objectId);


// Respond with a success message
echo "Redis storage cleared successfully.";


?>
