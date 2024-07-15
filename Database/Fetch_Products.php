
<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "waltzer");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Fetch the product details
    $product_sql = "SELECT * FROM products WHERE Id = '$id'";
    $product_result = $conn->query($product_sql);

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();

        // Fetch reviews for the product
        $reviews_sql = "SELECT * FROM review WHERE productId = '$id'";
        $reviews_result = $conn->query($reviews_sql);

        $reviews = [];
        if ($reviews_result->num_rows > 0) {
            while ($review_row = $reviews_result->fetch_assoc()) {
                $reviews[] = $review_row;
            }
        }

        $product['reviews'] = $reviews;

        // Fetch related products based on the same category and brand
        $category = $product['category'];
        $brand = $product['brand'];
        $related_sql = "SELECT * FROM products WHERE category = '$category' AND Id != '$id' LIMIT 4";
        $related_result = $conn->query($related_sql);

        $related_products = [];
        if ($related_result->num_rows > 0) {
            while ($related_row = $related_result->fetch_assoc()) {
                $related_products[] = $related_row;
            }
        }

        $product['related_products'] = $related_products;

        echo json_encode($product);
    } else {
        echo json_encode(["error" => "Product not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid product ID"]);
}

$conn->close();

?>