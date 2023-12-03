<?php

require '../vendor/autoload.php';

// MongoDB connection setup
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->selectDatabase("Guvi_Profile");
$collection = $database->selectCollection("bios");

// Initialize response array
$response = array();

// Check if the script is accessed through a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the necessary keys are set in the $_POST array
    if (
        isset($_POST['name']) &&
        isset($_POST['gender']) &&
        isset($_POST['age']) &&
        isset($_POST['dob']) &&
        isset($_POST['contact']) &&
        isset($_POST['bio'])
    ) {
        // Get data from the POST request
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $age = (int)$_POST['age'];
        $dob = $_POST['dob'];
        $contact = $_POST['contact'];
        $bio = $_POST['bio'];

        // Assuming you have a user identifier, replace 'user_id' with your actual identifier
        $user_id = 'user_id'; // Replace with your actual user identifier

        // Update or insert the user profile data
        $filter = ['_id' => $user_id];
        $update = [
            '$set' => [
                'name' => $name,
                'gender' => $gender,
                'age' => $age,
                'dob' => $dob,
                'contact' => $contact,
                'bio' => $bio,
            ],
        ];

        $options = ['upsert' => true];

        $result = $collection->updateOne($filter, $update, $options);

        if ($result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0) {
            // Successful update or insert
            $response['status'] = 'success';
            $response['message'] = 'Profile updated successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to update profile';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Missing required parameters';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>
