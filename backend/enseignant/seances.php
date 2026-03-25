<?php
header("Content-Type: application/json");
include("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $enseignant_id = $_GET['id'];

    $sql = "SELECT s.id,
                   m.nom as matiere,
                   c.nom as classe,
                   s.date_seance,
                   s.heure_debut,
                   s.heure_fin
            FROM seances s
            JOIN matieres m ON s.matiere_id = m.id
            JOIN classes c ON s.classe_id = c.id
            WHERE s.enseignant_id = $enseignant_id";

    $result = $conn->query($sql);

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode([
        "success" => 1,
        "data" => $data
    ]);
}
?>