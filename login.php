<?php
session_start();
require "conexion.php";

header("Content-Type: application/json");

try {

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Datos inválidos");
    }

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
        $_SESSION["id_paciente"] = $user["id_paciente"]; // 🔥 NUEVO

            // 🔥 LOG DE ACCESO
        $ip = $_SERVER["REMOTE_ADDR"];

        $log = $pdo->prepare("
            INSERT INTO logs_acceso (usuario_id, rol, ip)
            VALUES (?, ?, ?)
        ");

        $log->execute([$user["id"], $user["rol"], $ip]);

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

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}











