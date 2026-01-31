<?php
header("Content-Type: application/json");

$paciente_id = $_GET["paciente_id"] ?? null;

if (!$paciente_id) {
    echo json_encode([]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "historiaclinicafinal1");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT diagnostico, observaciones, fecha
     FROM historias_clinicas
     WHERE paciente_id = ?
     ORDER BY fecha DESC"
);

$stmt->bind_param("i", $paciente_id);
$stmt->execute();

$result = $stmt->get_result();
$historias = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($historias);
