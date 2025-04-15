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
    $clientName = $_POST['name'];
    $contactNumber = $_POST['contact'];
    $paymentMethod = $_POST['payment'];

    // Insert into database
    $sql = "INSERT INTO ClassRegistrations (class_name, date, time, client_name, contact_number, payment_method)
            VALUES ('$className', '$date', '$time', '$clientName', '$contactNumber', '$paymentMethod')";

    if ($conn->query($sql) === TRUE) {
        // Send confirmation email
        $to = "client@example.com"; // Replace with the client's email (fetch from database if available)
        $subject = "Class Registration Confirmation";
        $message = "
        Dear $clientName,

        Thank you for registering for a class at Orcakes Patisserie.

        Here are your registration details:
        - Class Name: $className
        - Date: $date
        - Time: $time
        - Payment Method: $paymentMethod

        We look forward to seeing you at the class!

        Best regards,
        Orcakes Patisserie Team
        ";
        $headers = "From: noreply@orcakespatisserie.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "Registration successful! A confirmation email has been sent.";
        } else {
            echo "Registration successful, but the email could not be sent.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
