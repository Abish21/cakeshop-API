<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require __DIR__ . '/vendor/autoload.php'; // Include JWT

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include "db.php";

// JWT settings
$secret_key = "(@kE$|/|0p";
$issuer = "http://localhost";
$audience = "http://localhost";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // Token valid for 1 hour

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check required fields
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $email, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $payload = [
                "iss" => $issuer,
                "aud" => $audience,
                "iat" => $issued_at,
                "exp" => $expiration_time,
                "data" => [
                    "id" => $id,
                    "name" => $name,
                    "email" => $email,
                    "role" => $role
                ]
            ];

            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "token" => $jwt,
                "user" => $payload["data"]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Email and password required"]);
}
?>
