<?php
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

// Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_code'] ?? ''; // Product ID from form
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Ensure quantity is an integer

    // Validate inputs
    if (!empty($product_id) && $quantity > 0) {
        // Fetch product details from the database
        $stmt = $conn->prepare("SELECT id, product_name, price FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        if ($product) {
            // Initialize the cart if it doesn't exist
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Check if the product already exists in the cart
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['product_id'] === $product['id']) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }

            // Add the product to the cart if not already present
            if (!$found) {
                $_SESSION['cart'][] = [
                    'product_id' => $product['id'],
                    'product_name' => $product['product_name'],
                    'price' => $product['price'],
                    'quantity' => $quantity
                ];
            }
        }
    }

    // Redirect to the cart display page
    header("Location: cart_display.php");
    exit();
}
?>
