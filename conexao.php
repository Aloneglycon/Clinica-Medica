<?php
$servername = "localhost";
$username = "root"; // Altere conforme necessário
$password = ""; // Altere conforme necessário
$dbname = "clinica_medica";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
