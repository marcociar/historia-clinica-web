<?php
session_start();
require_once '../conexion.php';

if (!isset($_GET['id'], $_GET['paciente_id'])) {
    echo "Datos incompletos.";
    exit;
}

$historia_id = (int) $_GET['id'];
$paciente_id = (int) $_GET['paciente_id'];

/* =========================
   ELIMINAR HISTORIA
========================= */
$stmt = $conn->prepare("
    DELETE FROM historias_clinicas
    WHERE id = ?
");
$stmt->bind_param("i", $historia_id);
$stmt->execute();

/* =========================
   VOLVER AL PACIENTE
========================= */
header("Location: ver_paciente.php?id=" . $paciente_id);
exit;
