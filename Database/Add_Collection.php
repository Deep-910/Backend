<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if (mysqli_connect_error()) {
    echo json_encode(["result" => "Database connection failed"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['images1']) && isset($_FILES['images2']) && isset($_FILES['images3'])) {

    $collectionName = $_POST['collectionName'] ?? '';
    //$productId      = $_POST['productId'] ?? '';
    $img1 = $_FILES['images1']['name'] ?? '';
    $img2 = $_FILES['images2']['name'] ?? '';
    $img3 = $_FILES['images3']['name'] ?? '';

    $tmp1 = $_FILES['images1']['tmp_name'] ?? '';
    $tmp2 = $_FILES['images2']['tmp_name'] ?? '';
    $tmp3 = $_FILES['images3']['tmp_name'] ?? '';

    $folder = $_SERVER['DOCUMENT_ROOT'].'/waltzify_copy/Backend/Database/Collection/';
    $result = "";

    $upload1 = move_uploaded_file($tmp1, $folder . $img1);
    $upload2 = move_uploaded_file($tmp2, $folder . $img2);
    $upload3 = move_uploaded_file($tmp3, $folder . $img3);

    if ($upload1 && $upload2 && $upload3) {
        $query = "INSERT INTO collection (collectionName, images1, images2, images3) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $collectionName, $img1, $img2, $img3);
        $res = $stmt->execute();

        if ($res) {
            $result = "Collection Added Successfully!";
        } else {
            $result = "Not Submitted, Please try again!";
        }
        $stmt->close();
    } else {
        $result = "File upload failed";
    }

    $conn->close();
    echo json_encode(["result" => $result]);
} else {
    echo json_encode(["result" => "Invalid input"]);
}
