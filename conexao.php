<?php
$servername ="127.0.0.1";
$username ="root"; // Substitua pelo seu usuário do banco
$password ="";
$dbname = "a";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>