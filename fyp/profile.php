<?php
// Start session
session_start();

// Database connection
$host = "localhost";
$dbname = "orcakes_patisserie";
$username = "root";
$password = "";
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

$user = $_SESSION['username'];
$stmt = $conn->prepare("SELECT email, full_name, phone, address, profile_pic FROM clients WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($email, $full_name, $phone, $address, $profile_pic);
$stmt->fetch();
$stmt->close();

// Update user data
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $full_name = $_POST['fullName'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Handle image upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $imagePath = "uploads/" . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $imagePath);
    } else {
        $imagePath = $profile_pic; // Retain existing image if not updated
    }

    $stmt = $conn->prepare("UPDATE clients SET email = ?, full_name = ?, phone = ?, address = ?, profile_pic = ? WHERE username = ?");
    $stmt->bind_param("ssssss", $email, $full_name, $phone, $address, $imagePath, $user);

    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile.";
    }

    $stmt->close();

    // Refresh the page to show updated data
    header("Location: profile.php");
    exit;
}

$conn->close();
?>
