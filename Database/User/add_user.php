<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: 
http://waltzify.com/api");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$conn = new mysqli("localhost", "root", "", "waltzer");

if (mysqli_connect_error()) {
    echo json_encode([["result" => "Database connection failed"]]);
    exit();
}
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Check if the request is a POST request with files
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $result = "";




    $query = "UPDATE user SET gender = ? , phone = ? WHERE Id = '$id'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $gender, $phone);
    $res = $stmt->execute();
    if ($res) {
        $result = "Registered Successfully!";
    } else {
        $result = "Not Submitted,Please try again!";
    }
    $stmt->close();

    $conn->close();
    echo json_encode([["result" => $result]]);
} else {
    echo json_encode([["result" => "Invalid input"]]);
}
