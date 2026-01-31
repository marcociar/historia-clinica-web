<?php
header("Content-Type: application/json");

$archivo = $_FILES["archivo"];
$pacienteId = $_POST["pacienteId"];

$nombre = time() . "_" . $archivo["name"];
move_uploaded_file($archivo["tmp_name"], "archivos/$nombre");

$conn = new mysqli("localhost", "root", "", "historiaclinicafinal1");
$conn->query("
    INSERT INTO archivos (paciente_id, nombre)
    VALUES ($pacienteId, '$nombre')
");

echo json_encode(["mensaje" => "Archivo subido correctamente"]);
