<?php
session_start();
require_once '../conexion.php';

if (
    !isset($_POST['historia_id']) ||
    !isset($_POST['paciente_id']) ||
    !isset($_POST['diagnostico']) ||
    !isset($_POST['observaciones'])
) {
    echo "Datos incompletos.";
    exit;
}

$historia_id   = (int) $_POST['historia_id'];
$paciente_id   = (int) $_POST['paciente_id'];
$diagnostico   = trim($_POST['diagnostico']);
$observaciones = trim($_POST['observaciones']);

$stmt = $conn->prepare("
    UPDATE historias_clinicas
    SET diagnostico = ?, observaciones = ?
    WHERE id = ?
");
$stmt->bind_param("ssi", $diagnostico, $observaciones, $historia_id);

if ($stmt->execute()) {
    header("Location: ver_paciente.php?id=" . $paciente_id);
    exit;
} else {
    echo "Error al actualizar la historia clínica.";
}
