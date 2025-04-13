<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "db.php";

// Custom ID generator (e.g., CAKE-1, CAKE-2)
function generateCustomID($conn, $table, $prefix) {
    $sql = "SELECT id FROM $table ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($sql);
    $latestId = 0;

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idParts = explode("-", $row['id']);
        $latestId = isset($idParts[1]) ? (int)$idParts[1] : 0;
    }

    return $prefix . "-" . ($latestId + 1);
}

// Validate required fields
// var_dump($_POST);
// var_dump($_FILES);
// exit();
if (
    isset($_POST['name']) &&
    isset($_POST['category_id']) &&
    isset($_POST['flavour_id']) &&
    isset($_POST['weight_kg']) &&
    isset($_POST['price']) &&
    isset($_POST['description']) &&
    isset($_FILES['image'])
) {
    $id = generateCustomID($conn, 'cakes', 'CAKE');
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $flavour_id = $_POST['flavour_id'];
    $weight_kg = $_POST['weight_kg'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle image upload
    $image_name = uniqid() . "_" . basename($_FILES['image']['name']);
    $image_tmp = $_FILES['image']['tmp_name'];
    $upload_path = "uploads/";

    if (move_uploaded_file($image_tmp, $upload_path . $image_name)) {
        $sql = "INSERT INTO cakes (id, name, category_id, flavour_id, weight_kg, price, description, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $image_path = $upload_path . $image_name;
        $stmt->bind_param("ssssddss", $id, $name, $category_id, $flavour_id, $weight_kg, $price, $description, $image_name);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Cake added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to insert into database"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Image upload failed"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
