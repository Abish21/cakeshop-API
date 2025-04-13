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

// Generate custom USER ID like USER-1, USER-2
function generateUserID($conn) {
    $sql = "SELECT id FROM users ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($sql);
    $latestId = 0;

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idParts = explode("-", $row['id']);
        $latestId = isset($idParts[1]) ? (int)$idParts[1] : 0;
    }

    return "USER-" . ($latestId + 1);
}

// Validate fields
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $id = generateUserID($conn);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure

    // Check for existing email
    $checkSql = "SELECT id FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
        exit();
    }

    // Let DB assign default 'user' role
    $sql = "INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $id, $name, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User registered successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
