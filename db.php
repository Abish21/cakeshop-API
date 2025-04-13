<?php 
header("Access-Control-Allow-Origin: *");

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$server = "localhost";
$username = "root";
$password = "";
$dbname = "cakeshopapidb";

$conn = new mysqli($server, $username, $password, $dbname);

if($conn->connect_error){
    die ("Connection Failed :" . $conn->connect_error);
}

?>