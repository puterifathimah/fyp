<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "orcakes_patisserie";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $className = $_POST['class-name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $instructor = $_POST['instructor'];
    $totalCost = $_POST['total-cost'];

    $sql = "INSERT INTO ClassSchedule (className, date, time, instructor, totalCost)
            VALUES ('$className', '$date', '$time', '$instructor', '$totalCost')";

    if ($conn->query($sql) === TRUE) {
        echo "Class added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
