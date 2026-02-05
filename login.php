<?php
session_start(); // 🔴 OBLIGATORIO
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "historiaclinicafinal1");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"] ?? "";
$password = $data["password"] ?? "";

$sql = "SELECT username, rol FROM usuarios WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // ✅ GUARDAMOS LA SESIÓN
    $_SESSION['usuario'] = $user['username'];
    $_SESSION['rol'] = $user['rol'];

    echo json_encode([
        "success" => true,
        "rol" => $user["rol"]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Usuario o contraseña incorrectos"
    ]);
}



