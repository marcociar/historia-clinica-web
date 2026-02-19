<?php
include "seguridad_medico.php";
include "../conexion.php";

$id_historia = $_POST["id_historia"];
$id_paciente = $_POST["id_paciente"];
$diagnostico = $_POST["diagnostico"];
$tratamiento = $_POST["tratamiento"];

// =================
// 1️⃣ Actualizar texto
// =================

$sql = "UPDATE historias_clinicas 
        SET diagnostico = :diagnostico,
            tratamiento = :tratamiento
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ":diagnostico" => $diagnostico,
    ":tratamiento" => $tratamiento,
    ":id" => $id_historia
]);

// =================
// 2️⃣ Si hay nuevo archivo
// =================

if (isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] == 0) {

    $nombre_original = $_FILES["archivo"]["name"];
    $tmp = $_FILES["archivo"]["tmp_name"];

    $nombre_unico = time() . "_" . $nombre_original;
    $ruta_destino = "../uploads/historias/" . $nombre_unico;

    if (!file_exists("../uploads/historias/")) {
        mkdir("../uploads/historias/", 0777, true);
    }

    if (move_uploaded_file($tmp, $ruta_destino)) {

        $sql_archivo = "INSERT INTO archivos_historia
                        (id_historia, nombre_archivo, archivo)
                        VALUES (:id_historia, :nombre, :archivo)";

        $stmt_archivo = $pdo->prepare($sql_archivo);
        $stmt_archivo->execute([
            ":id_historia" => $id_historia,
            ":nombre" => $nombre_original,
            ":archivo" => $nombre_unico
        ]);
    }
}

// =================
// 3️⃣ Volver
// =================

header("Location: cargar_historia.php?id=" . $id_paciente);
exit;
?>

