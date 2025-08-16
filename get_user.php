<<?php
session_start();

// Synopsis
// This PHP script retrieves the first name of a logged-in user from a MySQL database named bookdb. 
// It begins by starting a session and establishing a connection to the database using local credentials. 
// The script then checks if the user is authenticated by verifying the presence of a user_id in the session. 
// If the user is not logged in, it returns a JSON error message and halts execution. 
// If the user is authenticated, the script uses the stored session ID to query the users table for the corresponding firstName. 
// If a matching user record is found, it returns the user's first name in a JSON response indicating success. 
// If no matching user is found, it returns an error message. 
// This script enables personalized interaction on the front end by securely retrieving and displaying the logged-in user's name.

// ================================
// CONNECT TO MYSQL DATABASE
// Connect to the MySQL database using host, username, password, and database name
// ================================

$conn = new mysqli('localhost', 'root', 'root', 'bookdb');

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['user_id'])) {
  // If not logged in, return an error response as JSON and stop execution
  echo json_encode(['success' => false, 'error' => 'Not logged in']);
  exit;
}

// ================================
// GET USER ID
// Get the logged-in user's ID from the session
// ================================

$id = $_SESSION['user_id'];

// Query the database to retrieve the user's first name using their ID
$res = $conn->query("SELECT firstName FROM users WHERE id = $id");

// ================================
// GET RESULTS
// Fetch the result as an associative array
// ================================

$user = $res->fetch_assoc();

// If the user is found, return the first name in a success response
if ($user) {
  echo json_encode(['success' => true, 'firstName' => $user['firstName']]);
} else {
  // If no user is found with the given ID, return an error message
  echo json_encode(['success' => false, 'error' => 'User not found']);
}
?>
