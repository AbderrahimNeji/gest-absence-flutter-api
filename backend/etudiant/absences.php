<?php
header("Content-Type: application/json");
include("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $id = $_GET['id'];

    $sql = "SELECT m.nom as matiere,
                   s.date_seance,
                   s.heure_debut,
                   s.heure_fin,
                   a.statut
            FROM absences a
            JOIN etudiants e ON a.etudiant_id = e.id
            JOIN utilisateurs u ON e.utilisateur_id = u.id
            JOIN seances s ON a.seance_id = s.id
            JOIN matieres m ON s.matiere_id = m.id
            WHERE u.id = $id";

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