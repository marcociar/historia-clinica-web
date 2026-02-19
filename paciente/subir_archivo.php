<?php
session_start();
include "../conexion.php";

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_usuario = $_SESSION["id"];

    // Buscar el paciente vinculado
    $sqlPaciente = "SELECT id FROM pacientes WHERE usuario_id = :usuario_id";
    $stmtPaciente = $pdo->prepare($sqlPaciente);
    $stmtPaciente->execute([":usuario_id" => $id_usuario]);
    $paciente = $stmtPaciente->fetch(PDO::FETCH_ASSOC);

    if (!$paciente) {
        die("Paciente no vinculado.");
    }

    $id_paciente = $paciente["id"];

    if (isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] == 0) {

        $nombreOriginal = $_FILES["archivo"]["name"];
        $nombreGuardado = time() . "_" . $nombreOriginal;

        $ruta = "../uploads/" . $nombreGuardado;

        if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta)) {

            $sql = "INSERT INTO archivos_historia (id_historia, nombre_archivo, archivo, fecha, tipo)
                    VALUES (:id_historia, :nombre_archivo, :archivo, NOW(), 'paciente')";

            // Buscar última historia del paciente
            $sqlHistoria = "SELECT id FROM historias_clinicas 
                            WHERE id_paciente = :id_paciente 
                            ORDER BY fecha DESC LIMIT 1";

            $stmtHistoria = $pdo->prepare($sqlHistoria);
            $stmtHistoria->execute([":id_paciente" => $id_paciente]);
            $historia = $stmtHistoria->fetch(PDO::FETCH_ASSOC);

            if ($historia) {

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ":id_historia" => $historia["id"],
                    ":nombre_archivo" => $nombreOriginal,
                    ":archivo" => $nombreGuardado
                ]);

                header("Location: panel_paciente.php");
                exit;

            } else {
                echo "No tiene historia clínica creada.";
            }

        } else {
            echo "Error al mover el archivo.";
        }

    } else {
        echo "No se recibió archivo.";
    }
}

