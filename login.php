<?php

//Synopsis
//This PHP script manages user login authentication for a web application connected to a MySQL database called bookdb. It begins by starting a session to track the user's login state. It then establishes a connection to the database using localhost credentials. The script retrieves the user's submitted email and password from a POST request and queries the users table for a record that matches the provided email. If a user is found, it uses password_verify() to securely compare the submitted password against the hashed password stored in the database. If the login is successful, the user's ID is saved to the session, and a JSON response indicating success is returned to the frontend. If the credentials are invalid, a JSON response is returned with an error message prompting the user to try again or create an account. This script provides a secure and structured way to authenticate users and communicate login results to the frontend.

// Start the session so we can store user login state
session_start();

// Connect to the MySQL database (host, user, password, database)
$conn = new mysqli('localhost', 'root', 'root', 'bookdb');

// Get user-submitted login credentials from the POST request
$email = $_POST['email'];
$password = $_POST['password'];

// Query the database to find a user with the submitted email
$res = $conn->query("SELECT * FROM users WHERE email='$email'");

// Fetch the first matching user record as an associative array
$user = $res->fetch_assoc();

// Check if user exists and the password matches (using secure hash comparison)
if ($user && password_verify($password, $user['password'])) {
	// If successful, store the user's ID in a session variable
	$_SESSION['user_id'] = $user['id'];

	// Return a success response as JSON to the front end
	echo json_encode(['success' => true]);
} else {
	// If credentials are invalid, return an error response
	echo json_encode(['success' => false, 'error' => 'Invalid email or password. Please try again or create an account.']);
}
