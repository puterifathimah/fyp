<?php
// Start session
session_start();

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

// Process login data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($username) || empty($password)) {
        header("Location: login.html?error=empty_fields");
        exit;
    }

    $stmt = null; // Initialize the statement variable

    // Check credentials in the admins table
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Store session data and redirect to admin dashboard
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = 'admin';
            $stmt->close();
            header("Location: admin_dashboard.html");
            exit;
        } else {
            $stmt->close();
            header("Location: login.html?error=invalid_admin_password");
            exit;
        }
    }

    $stmt->close();

    // Check credentials in the clients table
    $stmt = $conn->prepare("SELECT password FROM clients WHERE username = ?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Store session data and redirect to client homepage
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = 'client';
            $stmt->close();
            header("Location: homepage.html");
            exit;
        } else {
            $stmt->close();
            header("Location: login.html?error=invalid_client_password");
            exit;
        }
    } else {
        $stmt->close();
        header("Location: login.html?error=user_not_found");
        exit;
    }
}

// Close the database connection
$conn->close();
?>
