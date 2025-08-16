<?php
session_start();

//Synopsis
//This PHP script handles the deletion of a book from a user's personal collection in the bookdb MySQL database. It starts by initiating a session and verifying that the user is logged in by checking for a user_id in the session data. If the user is not authenticated, it returns a JSON error and halts execution. The script then reads and decodes raw JSON input from the request body and checks whether a book ID (id) is provided. If not, it responds with a relevant error message. Assuming the ID is present, it converts the book ID to an integer and retrieves the user's ID from the session. The script establishes a connection to the database and prepares a secure, parameterized SQL DELETE statement that ensures only books belonging to the logged-in user can be deleted. After executing the statement, it returns a JSON response indicating whether the deletion was successful or if the book was not found (or had already been deleted). This ensures secure, user-specific deletion of data with proper input validation and feedback.

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
	echo json_encode(['success' => false, 'error' => 'Not logged in']);
	exit;
}

// Get raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if 'id' is present in the request
if (!isset($data['id'])) {
	echo json_encode(['success' => false, 'error' => 'No book ID provided']);
	exit;
}

$bookId = intval($data['id']);
$userId = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli('localhost', 'root', 'root', 'bookdb');

// Prepare and execute a delete query with user verification
$stmt = $conn->prepare("DELETE FROM books WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $bookId, $userId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
	echo json_encode(['success' => true]);
} else {
	echo json_encode(['success' => false, 'error' => 'Book not found or already deleted']);
}
