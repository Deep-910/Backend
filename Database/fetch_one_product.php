<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products ORDER BY Id DESC LIMIT 1";
$result = $conn->query($sql);

$product = null;
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo json_encode(["error" => "No product found"]);
    $conn->close();
    exit();
}

$conn->close();
echo json_encode($product);
