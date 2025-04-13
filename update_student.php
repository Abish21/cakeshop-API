<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(200);
    exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'PUT':
        parse_str(file_get_contents("php://input"), $put_vars);
        $id = $put_vars['id'] ?? null;
        $name = $put_vars['name'] ?? null;
        $email = $put_vars['email'] ?? null;
        $age = $put_vars['age'] ?? null;
        echo json_encode(array("message" => $id));
        // if ($id !== null && ($name !== null || $email !== null || $age !== null)) {
        //     $updateFields = [];
        //     if ($name !== null) {
        //         $updateFields[] = "name='$name'";
        //     }
        //     if ($email !== null) {
        //         $updateFields[] = "email='$email'";
        //     }
        //     if ($age !== null) {
        //         $updateFields[] = "age= '$age'";
        //     }
        //     $updateStr = implode(", ", $updateFields);

        //     $sql = "UPDATE users SET $updateStr WHERE id=$id";

        //     if ($conn->query($sql) === TRUE) {
        //         echo json_encode(array("message" => "Record updated successfully"));
        //     } else {
        //         echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
        //     }
        // } else {
        //     echo json_encode(array("message" => "Invalid or missing parameter5"));
        // }
        break;

    default:
        echo json_encode(array("message" => "Invalid request method"));
        break;
    // Other CRUD operations can be added similarly...
}

$conn->close();
?>
