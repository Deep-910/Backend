<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$category = isset($_GET['category']) ? $_GET['category'] : '';

if ($category) {
    // Fetch products of the provided category
    $product_sql = "SELECT * FROM products WHERE category = '$category' AND discount = 0";
    $product_result = $conn->query($product_sql);

    $products = [];
    if ($product_result->num_rows > 0) {
        while ($product_row = $product_result->fetch_assoc()) {
            $products[] = $product_row;
        }
    } else {
        echo json_encode(["error" => "No products found for the given category"]);
        $conn->close();
        exit();
    }

   /*  // Fetch related products based on the same category
    $related_sql = "SELECT * FROM products WHERE category != '$category' LIMIT 4";
    $related_result = $conn->query($related_sql);

    $related_products = [];
    if ($related_result->num_rows > 0) {
        while ($related_row = $related_result->fetch_assoc()) {
            $related_products[] = $related_row;
        }
    } */

    echo json_encode(["products" => $products]);
} else {
    // Fetch any products if no category is provided
    $product_sql = "SELECT * FROM products LIMIT 4";
    $product_result = $conn->query($product_sql);

    $products = [];
    if ($product_result->num_rows > 0) {
        while ($product_row = $product_result->fetch_assoc()) {
            $products[] = $product_row;
        }
    }

    echo json_encode(["products" => $products]);
}

$conn->close();
