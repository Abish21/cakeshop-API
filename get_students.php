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

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM users WHERE id = $id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $student = $result->fetch_assoc();
                echo json_encode($student);
            } else {
                echo json_encode(array("message" => "No student found with ID: $id"));
            }
        } else {
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $rows = array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                echo json_encode($rows);
            } else {
                echo json_encode(array());
            }
        }
        break;
    // Other CRUD operations can follow here...
}

$conn->close();
?>
