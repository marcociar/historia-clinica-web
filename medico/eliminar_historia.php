
<?php
include "seguridad_medico.php";
include "../conexion.php";

$id_historia = $_GET["id"];
$id_paciente = $_GET["paciente"];

// ==========================
// 1️⃣ Obtener archivos asociados
// ==========================

$sql_archivos = "SELECT archivo FROM archivos_historia WHERE id_historia = :id";
$stmt_archivos = $pdo->prepare($sql_archivos);
$stmt_archivos->execute([":id" => $id_historia]);
$archivos = $stmt_archivos->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// 2️⃣ Borrar archivos físicos
// ==========================

foreach ($archivos as $archivo) {
    $ruta = "../uploads/historias/" . $archivo["archivo"];
    if (file_exists($ruta)) {
        unlink($ruta);
    }
}

// ==========================
// 3️⃣ Borrar historia (BD)
// ==========================

$sql_delete = "DELETE FROM historias_clinicas WHERE id = :id";
$stmt_delete = $pdo->prepare($sql_delete);
$stmt_delete->execute([":id" => $id_historia]);

// ==========================
// 4️⃣ Volver
// ==========================

header("Location: cargar_historia.php?id=" . $id_paciente);
exit;
?>

