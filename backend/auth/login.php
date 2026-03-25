<?php
header("Content-Type: application/json");
include("../config/database.php");

$data = json_decode(file_get_contents("php://input"));

$email = $data->email;
$password = $data->password;

$sql = "SELECT * FROM utilisateurs WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    echo json_encode([
        "success" => 1,
        "data" => [
            "id" => $user['id'],
            "role" => $user['role']
        ]
    ]);
} else {
    echo json_encode([
        "success" => 0,
        "message" => "Login incorrect"
    ]);
}
?>