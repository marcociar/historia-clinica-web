<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$paciente_id = $data["paciente_id"] ?? null;
$diagnostico = $data["diagnostico"] ?? "";
$observaciones = $data["observaciones"] ?? "";

if (!$paciente_id) {
    echo json_encode(["error" => "Paciente no recibido"]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "historiaclinicafinal1");

if ($conn->connect_error) {
    echo json_encode(["error" => $conn->connect_error]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO historias_clinicas (paciente_id, diagnostico, observaciones)
     VALUES (?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$stmt->bind_param("iss", $paciente_id, $diagnostico, $observaciones);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Historia clínica guardada"]);
} else {
    echo json_encode(["error" => $stmt->error]);
}



