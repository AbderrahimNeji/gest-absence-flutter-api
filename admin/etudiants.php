<?php
header("Content-Type: application/json");
include("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT e.id, u.nom, u.prenom, c.nom as classe
            FROM etudiants e
            JOIN utilisateurs u ON e.utilisateur_id = u.id
            JOIN classes c ON e.classe_id = c.id";

    $result = $conn->query($sql);

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(["success" => 1, "data" => $data]);
}
//ajouter un etudiant
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    $nom = $data->nom;
    $prenom = $data->prenom;
    $email = $data->email;
    $password = $data->password;
    $classe_id = $data->classe_id;

    // ajouter utilisateur
    $sql1 = "INSERT INTO utilisateurs (nom,prenom,email,password,role)
             VALUES ('$nom','$prenom','$email','$password','etudiant')";

    if ($conn->query($sql1)) {

        $user_id = $conn->insert_id;

        // ajouter etudiant
        $sql2 = "INSERT INTO etudiants (utilisateur_id,classe_id)
                 VALUES ($user_id,$classe_id)";

        $conn->query($sql2);

        echo json_encode(["success" => 1]);
    } else {
        echo json_encode(["success" => 0, "message" => "Erreur ajout"]);
    }
}
//modifier un etudiant
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id;
    $nom = $data->nom;
    $prenom = $data->prenom;
    $email = $data->email;
    $classe_id = $data->classe_id;

    // récupérer utilisateur_id
    $res = $conn->query("SELECT utilisateur_id FROM etudiants WHERE id=$id");
    $row = $res->fetch_assoc();
    $user_id = $row['utilisateur_id'];

    // update utilisateurs
    $conn->query("UPDATE utilisateurs 
                  SET nom='$nom', prenom='$prenom', email='$email'
                  WHERE id=$user_id");

    // update classe
    $conn->query("UPDATE etudiants 
                  SET classe_id=$classe_id
                  WHERE id=$id");

    echo json_encode(["success" => 1]);
}
?>