<?php
header("Content-Type: application/json");
include "db.php";

$sql = "SELECT * FROM cakes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cakes = [];
    while ($row = $result->fetch_assoc()) {
        $cakes[] = $row; // Append each row
    }
    echo json_encode($cakes);
} else {
    echo json_encode(["message" => "Data is Empty"]);
}
?>
