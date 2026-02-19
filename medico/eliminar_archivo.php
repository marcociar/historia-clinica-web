<?php
include "seguridad_medico.php";
include "../conexion.php";

$id_archivo = $_GET["id"];
$id_historia = $_GET["historia"];
$id_paciente = $_GET["paciente"];

// 1️⃣ Obtener nombre del archivo
$sql = "SELECT archivo FROM archivos_historia WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => $id_archivo]);
$archivo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($archivo) {

    $ruta = "../uploads/historias/" . $archivo["archivo"];

    // 2️⃣ Borrar archivo físico si existe
    if (file_exists($ruta)) {
        unlink($ruta);
    }

    // 3️⃣ Borrar registro de BD
    $sql_delete = "DELETE FROM archivos_historia WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([":id" => $id_archivo]);
}

// 4️⃣ Volver a editar
header("Location: editar_historia.php?id=$id_historia&paciente=$id_paciente");
exit;
?>
