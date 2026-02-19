<?php
include "seguridad_medico.php";
include "../conexion.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Paciente no encontrado.");
}

$id_paciente = intval($_GET["id"]);

// ==========================
// 1️⃣ Obtener historias del paciente
// ==========================

$sql_historias = "SELECT id FROM historias_clinicas WHERE id_paciente = :id_paciente";
$stmt_historias = $pdo->prepare($sql_historias);
$stmt_historias->execute([":id_paciente" => $id_paciente]);
$historias = $stmt_historias->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// 2️⃣ Por cada historia borrar archivos físicos
// ==========================

foreach ($historias as $historia) {

    $id_historia = $historia["id"];

    $sql_archivos = "SELECT archivo FROM archivos_historia WHERE id_historia = :id_historia";
    $stmt_archivos = $pdo->prepare($sql_archivos);
    $stmt_archivos->execute([":id_historia" => $id_historia]);
    $archivos = $stmt_archivos->fetchAll(PDO::FETCH_ASSOC);

    foreach ($archivos as $archivo) {
        $ruta = "../uploads/historias/" . $archivo["archivo"];
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }

    // Borrar registros de archivos
    $pdo->prepare("DELETE FROM archivos_historia WHERE id_historia = :id_historia")
        ->execute([":id_historia" => $id_historia]);
}

// ==========================
// 3️⃣ Borrar historias clínicas
// ==========================

$pdo->prepare("DELETE FROM historias_clinicas WHERE id_paciente = :id_paciente")
    ->execute([":id_paciente" => $id_paciente]);

// ==========================
// 4️⃣ Borrar paciente
// ==========================

$pdo->prepare("DELETE FROM pacientes WHERE id = :id")
    ->execute([":id" => $id_paciente]);

// ==========================
// 5️⃣ Volver al listado
// ==========================

header("Location: listar_pacientes.php");
exit;
?>

