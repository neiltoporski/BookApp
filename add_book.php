<?php

//Synopsis
//This PHP script allows a logged-in user to add a new book to their personal collection in a MySQL database called bookdb. It begins by starting a session to access the user's session data and checks if the user is logged in by verifying the presence of a user_id in the session. If the user is not authenticated, the script returns a JSON error message and stops execution. If the user is logged in, the script connects to the database and retrieves the submitted book data (title, author, genre, and comments) from a POST request. It then prepares a parameterized SQL statement to securely insert the new book record into the books table, associating it with the logged-in user's ID from the session. The parameters are bound to the statement to prevent SQL injection, and the statement is executed to insert the data. Finally, the script returns a JSON response indicating that the operation was successful. This ensures that only authenticated users can add books, and that data is stored securely and accurately.

// Start the session to access the logged-in user's session data
session_start();

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['user_id'])) {
	// If the user is not logged in, return an error and stop execution
	echo json_encode(['success' => false, 'error' => 'Not logged in']);
	exit;
}

// Connect to the MySQL database (host, username, password, database name)
$conn = new mysqli('localhost', 'root', 'root', 'bookdb');

// Get the submitted form data (title, author, genre, comments)
$data = $_POST;

// Prepare a parameterized SQL statement to insert the book record
// The `user_id` is retrieved from the session to associate the book with the current user
$stmt = $conn->prepare("INSERT INTO books (user_id, title, author, genre, comments) VALUES (?, ?, ?, ?, ?)");

// Bind the parameters to the prepared statement:
// - i = integer (user_id)
// - s = string (title, author, genre, comments)
$stmt->bind_param(
	"issss", 
	$_SESSION['user_id'], 
	$data['title'], 
	$data['author'], 
	$data['genre'], 
	$data['comments']
);

// Execute the SQL statement to insert the book into the database
$stmt->execute();

// Return a success response in JSON format
echo json_encode(['success' => true]);
