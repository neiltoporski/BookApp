<?php

// Synopsis
//This PHP script retrieves a logged-in user's saved books from a MySQL database named bookdb and returns them as a JSON array. It begins by starting a session to access session variables and checks if the user is logged in by verifying the presence of a user_id in the session. If the user is not logged in, it returns an empty JSON array and exits. If the user is authenticated, the script connects to the database and retrieves the user's ID from the session. It then runs a SQL query to select the id, title, author, genre, and comments of all books associated with that user. The results are looped through and stored in an array, which is finally encoded into JSON and sent to the frontend. This script ensures that only the authenticated user’s book data is accessed and securely returned.

// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
	echo json_encode([]);
	exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', 'root', 'bookdb');

// Get the current user's ID
$id = $_SESSION['user_id'];

// Query: include `id` in the result set
$res = $conn->query("SELECT id, title, author, genre, comments FROM books WHERE user_id = $id");

// Prepare the result
$books = [];
while ($row = $res->fetch_assoc()) {
	$books[] = $row;
}

// Return JSON
echo json_encode($books);

