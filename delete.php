<?php 
header("content-type:application/json");
include "db.php";

$data = json_decode(file_get_content("php://input"));

if(isset($data->id)){
    $stmt = $conn->prepere("DELETE FROM myguests WHERE id=?");
    $stmt->bind_param("i",$data->id);


    if($stmt->execute()){
        echo json_ecnode(["message"=>"Data Deleted"]);
    }else{
        echo json_ecnode(["message"=>"Faild to delete"]);
    }
    $stmt->close();
}else{
    echo json_encode(["message"=>"Invalid given Id"]);
}

?>