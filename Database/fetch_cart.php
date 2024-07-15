<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$dbname = 'waltzer';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get userId from query parameter
$userId = isset($_GET['userId']) ? $_GET['userId'] : null;

if (empty($userId)) {
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

// Fetch cart items
try {
    // Log the userId for debugging
    error_log("Fetching cart items for userId: " . $userId);

    $stmt = $pdo->prepare("SELECT products.*
        FROM user
        INNER JOIN cart ON user.Id = cart.UserId
        INNER JOIN products ON cart.ProductId = products.Id
        WHERE user.Id = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Log the number of cart items fetched for debugging
    error_log("Number of cart items fetched: " . count($cartItems));

    if (empty($cartItems)) {
        echo json_encode(['cartItems' => [], 'message' => 'No items found in cart for this user']);
    } else {
        echo json_encode(['cartItems' => $cartItems]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch cart items: ' . $e->getMessage()]);
}
