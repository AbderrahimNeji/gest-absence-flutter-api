<?php
header("Content-Type: application/json");
include("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT s.id,
                   m.nom as matiere,
                   c.nom as classe,
                   u.nom as enseignant,
                   s.date_seance,
                   s.heure_debut,
                   s.heure_fin
            FROM seances s
            JOIN matieres m ON s.matiere_id = m.id
            JOIN classes c ON s.classe_id = c.id
            JOIN enseignants e ON s.enseignant_id = e.id
            JOIN utilisateurs u ON e.utilisateur_id = u.id";

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
//ajouter une seance
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    $enseignant_id = $data->enseignant_id;
    $classe_id = $data->classe_id;
    $matiere_id = $data->matiere_id;
    $date = $data->date_seance;
    $debut = $data->heure_debut;
    $fin = $data->heure_fin;

    $sql = "INSERT INTO seances
            (enseignant_id, classe_id, matiere_id, date_seance, heure_debut, heure_fin)
            VALUES ($enseignant_id, $classe_id, $matiere_id, '$date', '$debut', '$fin')";

    if ($conn->query($sql)) {
        echo json_encode(["success" => 1]);
    } else {
        echo json_encode(["success" => 0]);
    }
}
?>