<?php
header("Content-Type: application/json");
include("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT e.id, u.nom, u.prenom, u.email, e.specialite
            FROM enseignants e
            JOIN utilisateurs u ON e.utilisateur_id = u.id";

    $result = $conn->query($sql);

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(["success" => 1, "data" => $data]);
}
//ajouter un enseignant
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    $nom = $data->nom;
    $prenom = $data->prenom;
    $email = $data->email;
    $password = $data->password;
    $specialite = $data->specialite;

    $sql1 = "INSERT INTO utilisateurs (nom,prenom,email,password,role)
             VALUES ('$nom','$prenom','$email','$password','enseignant')";

    if ($conn->query($sql1)) {

        $user_id = $conn->insert_id;

        $sql2 = "INSERT INTO enseignants (utilisateur_id,specialite)
                 VALUES ($user_id,'$specialite')";

        $conn->query($sql2);

        echo json_encode(["success" => 1]);
    } else {
        echo json_encode(["success" => 0]);
    }
}
//modifier un enseignant
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id;
    $nom = $data->nom;
    $prenom = $data->prenom;
    $email = $data->email;
    $specialite = $data->specialite;

    // récupérer utilisateur_id
    $res = $conn->query("SELECT utilisateur_id FROM enseignants WHERE id=$id");
    $row = $res->fetch_assoc();
    $user_id = $row['utilisateur_id'];

    // update utilisateurs
    $conn->query("UPDATE utilisateurs 
                  SET nom='$nom', prenom='$prenom', email='$email'
                  WHERE id=$user_id");

    // update spécialité
    $conn->query("UPDATE enseignants 
                  SET specialite='$specialite'
                  WHERE id=$id");

    echo json_encode(["success" => 1]);
}
?>