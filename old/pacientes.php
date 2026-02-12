<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "historiaclinicafinal1");

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$result = $conn->query("SELECT id, nombre, apellido FROM pacientes");

$pacientes = [];
while ($row = $result->fetch_assoc()) {
    $pacientes[] = $row;
}

echo json_encode($pacientes);

