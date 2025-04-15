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

// Fetch data for the dashboard
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM clients";
$totalOrdersQuery = "SELECT COUNT(*) AS active_orders FROM orders WHERE status = 'active'";
$totalStockQuery = "SELECT SUM(quantity) AS stock_levels FROM stock";
$totalClassesQuery = "SELECT COUNT(*) AS total_classes FROM classes";

$totalUsers = $conn->query($totalUsersQuery)->fetch_assoc()['total_users'];
$activeOrders = $conn->query($totalOrdersQuery)->fetch_assoc()['active_orders'];
$stockLevels = $conn->query($totalStockQuery)->fetch_assoc()['stock_levels'];
$totalClasses = $conn->query($totalClassesQuery)->fetch_assoc()['total_classes'];

// Return data as JSON for JavaScript
header('Content-Type: application/json');
echo json_encode([
    'total_users' => $totalUsers,
    'active_orders' => $activeOrders,
    'stock_levels' => $stockLevels,
    'total_classes' => $totalClasses,
]);

$conn->close();
?>
