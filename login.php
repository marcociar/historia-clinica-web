<?php
header("Content-Type: application/json");
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
    exit;
}

require "conexion.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"] ?? "";
$password = $data["password"] ?? "";

$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {

    $_SESSION["id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["rol"] = $user["rol"];

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










