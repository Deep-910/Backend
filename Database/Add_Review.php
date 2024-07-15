<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$conn = new mysqli("localhost", "root", "", "waltzer");

if (mysqli_connect_error()) {
    echo json_encode([["result" => "Database connection failed"]]);
    exit();
}

// Check if the request is a POST request with files
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image1']) && isset($_FILES['image2']) && isset($_FILES['image3']) && isset($_FILES['image4'])) {
    $email    = $_POST['email'] ?? '';
    $proId    = $_POST['productId'] ?? '';
    $reviewTitle    = $_POST['reviewTitle'] ?? '';
    $review   = $_POST['review'] ?? '';
    $rating   = $_POST['rating'] ?? '';

    $image1        =  $_FILES['image1']['name'] ?? '';
    $image2        =  $_FILES['image2']['name'] ?? '';
    $image3        =  $_FILES['image3']['name'] ?? '';
    $image4        =  $_FILES['image4']['name'] ?? '';

    $tmp1        =  $_FILES['image1']['tmp_name'] ?? '';
    $tmp2        =  $_FILES['image2']['tmp_name'] ?? '';
    $tmp3        =  $_FILES['image3']['tmp_name'] ?? '';
    $tmp4        =  $_FILES['image4']['tmp_name'] ?? '';

    $folder      =  $_SERVER['DOCUMENT_ROOT'].'/waltzify_copy/Backend/Database/Review/';
  
    $result   = "";
  
    $upload1 = move_uploaded_file($tmp1, $folder . $image1);
    $upload2 = move_uploaded_file($tmp2, $folder . $image2);
    $upload3 = move_uploaded_file($tmp3, $folder . $image3);
    $upload4 = move_uploaded_file($tmp4, $folder . $image4);

    if ($upload1 && $upload2 && $upload3 && $upload4) {

            $query = "INSERT INTO review (email, productId, reviewTitle, review,rating,img1,img2,img3,img4)VALUES (?, ?, ?, ?,?,?,?,?,?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sississss", $email, $proId, $reviewTitle, $review,$rating,$image1,$image2,$image3,$image4);
            $res = $stmt->execute();
            if ($res)
            {
                $result = "Registered Successfully!";
            }
            else
            {
                $result = "Not Submitted,Please try again!";
            }
            $stmt->close();
        }
        else
        {
            $result = "File upload failed";
        }
    $conn->close();
    echo json_encode([["result" => $result]]);
} 
else 
{
    echo json_encode([["result" => "Invalid input"]]);
}
?>