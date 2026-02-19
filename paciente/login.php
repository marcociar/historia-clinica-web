<?php
session_start();
include "../conexion.php";

$email = $_POST["usuario"];
$password = $_POST["password"];

$sql = "SELECT * FROM pacientes WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([":email" => $email]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($paciente && $password == $paciente["password"]) {

    $_SESSION["id_paciente"] = $paciente["id"];
    $_SESSION["rol"] = "paciente";

    header("Location: panel_paciente.php");
    exit;

} else {
    echo "Datos incorrectos";
}
