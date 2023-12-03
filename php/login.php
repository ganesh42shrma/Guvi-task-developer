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

// Check if the script is accessed through a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if $_SERVER["REQUEST_METHOD"] is set before using it
    if (isset($_SERVER["REQUEST_METHOD"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        
        if ($stmt) {
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();

                    if (password_verify($password, $user['password'])) {
                        $response['status'] = 'success';
                        $response['message'] = 'Login successful!';

                        // Use the existing token for the user if available
                        $token = $user['token'];

                        // Generate a new token only if the user doesn't have one
                        if (empty($token)) {
                            $token = bin2hex(random_bytes(16));

                            // Update the user's token in the database
                            $updateStmt = $conn->prepare("UPDATE users SET token = ? WHERE username = ?");
                            $updateStmt->bind_param("ss", $token, $username);
                            
                            if ($updateStmt->execute()) {
                                $response['token'] = $token;
                            } else {
                                $response['status'] = 'error';
                                $response['message'] = 'Error updating user token: ' . $updateStmt->error;
                            }

                            $updateStmt->close();
                        } else {
                            $response['token'] = $token;
                        }
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'Incorrect password';
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'User not found';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error executing the statement: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error preparing the statement: ' . $conn->error;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = '$_SERVER["REQUEST_METHOD"] is not set';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
