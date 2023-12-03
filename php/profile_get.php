<?php
require '../vendor/autoload.php'; // Include Composer's autoloader

// MongoDB connection setup
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->selectDatabase("Guvi_Profile");
$collection = $database->selectCollection("bios");

// Assuming you have a user identifier, replace 'user_id' with your actual identifier
$user_id = 'user_id'; // Replace with your actual user identifier

// Fetch user data from MongoDB
$filter = ['_id' => $user_id];
$userProfile = $collection->findOne($filter);

// Send user data as JSON response
header('Content-Type: application/json');
echo json_encode($userProfile);
?>
