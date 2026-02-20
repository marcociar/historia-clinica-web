<?php
include "seguridad_medico.php";
include "../conexion.php";

try {

    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $edad = $_POST["edad"];
    $email = $_POST["email"];

    // Verificar si el email ya existe
    $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmtCheck->execute([$email]);

    if ($stmtCheck->fetch()) {
        die("Ese email ya está registrado.");
    }

    // Generar contraseña automática (8 caracteres seguros)
    $password_plana = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 8);

    // Encriptar contraseña
    $password_hash = password_hash($password_plana, PASSWORD_DEFAULT);

    // 1️⃣ Crear usuario paciente
    $stmtUser = $pdo->prepare("
        INSERT INTO usuarios (username, password, rol)
        VALUES (?, ?, 'paciente')
    ");
    $stmtUser->execute([$email, $password_hash]);

    $usuario_id = $pdo->lastInsertId();

    // 2️⃣ Crear paciente vinculado al usuario creado
    $stmtPaciente = $pdo->prepare("
        INSERT INTO pacientes (nombre, apellido, dni, edad, usuario_id)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmtPaciente->execute([$nombre, $apellido, $dni, $edad, $usuario_id]);

    echo "<h2>Paciente creado correctamente</h2>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Contraseña generada:</strong> $password_plana</p>";
    echo "<br><a href='listar_pacientes.php'>Volver a la lista</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}




