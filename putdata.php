<?php
header("Content-Type: application/json");
include "db.php";

// Validate required fields
// var_dump($_POST);
// var_dump($_FILES);
// exit();
if (
    isset($_POST['id']) &&
    isset($_POST['name']) &&
    isset($_POST['category_id']) &&
    isset($_POST['flavour_id']) &&
    isset($_POST['weight_kg']) &&
    isset($_POST['price']) &&
    isset($_POST['description'])
) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $flavour_id = $_POST['flavour_id'];
    $weight_kg = $_POST['weight_kg'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $upload_dir = "uploads/";
    $image_path = null;

    // If image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $unique_name = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $target_path = $upload_dir . $unique_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
            $image_path = $target_path;
        } else {
            echo json_encode(["status" => "error", "message" => "Image upload failed"]);
            exit();
        }
    }

    // Build SQL query (with image update if needed)
    if ($image_path !== null) {
        $sql = "UPDATE cakes SET 
                    name = ?, 
                    category_id = ?, 
                    flavour_id = ?, 
                    weight_kg = ?, 
                    price = ?, 
                    description = ?, 
                    image = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssddsss", $name, $category_id, $flavour_id, $weight_kg, $price, $description, $image_path, $id);
    } else {
        $sql = "UPDATE cakes SET 
                    name = ?, 
                    category_id = ?, 
                    flavour_id = ?, 
                    weight_kg = ?, 
                    price = ?, 
                    description = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssddss", $name, $category_id, $flavour_id, $weight_kg, $price, $description, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Cake updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
