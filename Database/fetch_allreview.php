<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "waltzer");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch all products
$product_sql = "SELECT * FROM products";
$product_result = $conn->query($product_sql);

$products = [];
if ($product_result->num_rows > 0) {
    while ($product_row = $product_result->fetch_assoc()) {
        // Fetch reviews for each product
        $reviews_sql = "SELECT * FROM review WHERE productId = " . $product_row['Id'];
        $reviews_result = $conn->query($reviews_sql);

        $reviews = [];
        if ($reviews_result->num_rows > 0) {
            while ($review_row = $reviews_result->fetch_assoc()) {
                $reviews[] = $review_row;
            }
        }

        $product_row['reviews'] = $reviews;
        $products[] = $product_row;
    }
}

echo json_encode($products);

$conn->close();
?>



