<?php
include "seguridad_medico.php";
include "../conexion.php";

$usuario_id = $_SESSION["usuario_id"]; // médico logueado
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$fecha_nacimiento = $_POST["fecha_nacimiento"];

$sql = "INSERT INTO pacientes (usuario_id, nombre, apellido, fecha_nacimiento)
        VALUES (:usuario_id, :nombre, :apellido, :fecha_nacimiento)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":usuario_id" => $usuario_id,
    ":nombre" => $nombre,
    ":apellido" => $apellido,
    ":fecha_nacimiento" => $fecha_nacimiento
]);

header("Location: listar_pacientes.php");
exit;




