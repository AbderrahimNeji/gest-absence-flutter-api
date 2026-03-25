<?php
header("Content-Type: application/json");
include("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    $seance_id = $data->seance_id;
    $absences = $data->absences;

    foreach ($absences as $a) {

        $etudiant_id = $a->etudiant_id;
        $statut = $a->statut;

        $sql = "INSERT INTO absences (seance_id, etudiant_id, statut)
                VALUES ($seance_id, $etudiant_id, '$statut')
                ON DUPLICATE KEY UPDATE statut='$statut'";

        $conn->query($sql);
    }

    echo json_encode(["success" => 1]);
}
?>