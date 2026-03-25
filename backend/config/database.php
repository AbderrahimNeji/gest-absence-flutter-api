<?php
$host = "localhost";
$db_name = "gest_absence";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Erreur connexion");
}
?>