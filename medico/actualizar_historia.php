<?php
include "seguridad_medico.php";
include "../conexion.php";

$id_historia = $_POST["id_historia"];
$id_paciente = $_POST["id_paciente"];
$diagnostico = $_POST["diagnostico"];
$tratamiento = $_POST["tratamiento"];

$sql = "UPDATE historias_clinicas 
        SET diagnostico = :diagnostico,
            tratamiento = :tratamiento
        WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(":diagnostico", $diagnostico);
$stmt->bindParam(":tratamiento", $tratamiento);
$stmt->bindParam(":id", $id_historia, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: cargar_historia.php?id=" . $id_paciente);
    exit;
} else {
    echo "Error al actualizar.";
}
?>
