<?php
include "seguridad_paciente.php";
include "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_historia = $_POST["id_historia"];
    $comentario = $_POST["comentario"];

    $sql = "UPDATE historias_clinicas
            SET comentario_paciente = :comentario
            WHERE id = :id_historia";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":comentario" => $comentario,
        ":id_historia" => $id_historia
    ]);

    header("Location: panel_paciente.php");
    exit;
}

