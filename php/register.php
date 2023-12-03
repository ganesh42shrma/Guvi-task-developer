<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guvi_registration";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize response array
$response = array();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if the username already exists
    $stmt_check_username = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt_check_username->bind_param("s", $username);
    $stmt_check_username->execute();
    $result_check_username = $stmt_check_username->get_result();

    if ($result_check_username->num_rows > 0) {
        // Username already taken
        $response['status'] = 'error';
        $response['message'] = 'Username already taken';
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $username, $email, $password);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Registration successful!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check_username->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
