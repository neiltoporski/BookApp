<?php
// Start a session to manage user state
session_start();

//Synopsis
//This PHP script handles the user registration process for a web application connected to a MySQL database called bookdb. It starts by initiating a session to manage user state and connects to the database using local credentials. The script retrieves the user-submitted form data, including first name, last name, email, and password, from a POST request. To protect against SQL injection, the submitted email is sanitized using real_escape_string(). It then checks if the email is already registered by querying the users table. If a matching record is found, the script returns a JSON error response indicating the email is already in use and stops execution. If the email is available, the password is securely hashed using PHP's password_hash() function before storage. A parameterized SQL statement is then prepared to safely insert the new user into the database, and the input values are bound to the query. After executing the insertion, the script returns a JSON response indicating the registration was successful. This process ensures new users are registered securely and prevents duplicate accounts or injection vulnerabilities.

// ================================
// CONNECT TO MYSQL DATABASE
// Connect to the MySQL database (host, username, password, database name)
// ================================

$conn = new mysqli('localhost', 'root', 'root', 'bookdb');

// ================================
// GET FORM DATA
// Get all submitted form data (firstName, lastName, email, password)
// ================================

$data = $_POST;

// Sanitize the email to prevent SQL injection
$email = $conn->real_escape_string($data['email']);

// ================================
// DOES USER EXIST?
// Check if a user with this email already exists
// ================================

$exists = $conn->query("SELECT id FROM users WHERE email='$email'");

// If the email is already taken, return an error response
if ($exists->num_rows > 0) {
	echo json_encode(['success' => false, 'error' => 'Email already registered']);
	exit; // Stop further script execution
}

// ================================
// HASH PASSWORD
// Securely hash the password before saving it in the database
// ================================

$hash = password_hash($data['password'], PASSWORD_DEFAULT);

// Prepare a parameterized SQL statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");

// Bind the user input to the prepared statement
$stmt->bind_param("ssss", $data['firstName'], $data['lastName'], $email, $hash);

// Execute the SQL statement to insert the new user
$stmt->execute();

// Return a success response as JSON
echo json_encode(['success' => true]);
