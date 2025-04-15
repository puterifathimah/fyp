<?php
// Database connection parameters
$host = "localhost";
$dbname = "orcakes_patisserie";
$username = "root";
$password = "";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'client'; // Default to 'client'

    // Validate inputs
    if (empty($user) || empty($email) || empty($pass) || empty($confirm_pass)) {
        die("All fields are required!");
    }

    if ($pass !== $confirm_pass) {
        die("Passwords do not match!");
    }

    // Hash the password for security
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Insert into the appropriate table based on role
    if ($role === 'admin') {
        $stmt = $conn->prepare("INSERT INTO admins (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    } else {
        $stmt = $conn->prepare("INSERT INTO clients (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    }

    $stmt->bind_param("sss", $user, $email, $hashed_password);

    if ($stmt->execute()) {
        // Redirect to login page
        header("Location: login.html");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
