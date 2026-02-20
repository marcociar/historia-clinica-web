<?php
include "seguridad_medico.php";
include "../conexion.php";

$id_paciente = $_POST["id_paciente"];
$diagnostico = $_POST["diagnostico"];
$tratamiento = $_POST["tratamiento"];

// =========================
// 1️⃣ Guardar historia clínica
// =========================

$sql = "INSERT INTO historias_clinicas 
        (id_paciente, diagnostico, tratamiento, fecha)
        VALUES (:id_paciente, :diagnostico, :tratamiento, NOW())";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
$stmt->bindParam(":diagnostico", $diagnostico);
$stmt->bindParam(":tratamiento", $tratamiento);

if (!$stmt->execute()) {
    die("Error al guardar la historia clínica.");
}

// Obtener ID recién creado
$id_historia = $pdo->lastInsertId();


// =========================
// 2️⃣ Guardar archivo (si existe)
// =========================

if (isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] == 0) {

    $nombre_original = $_FILES["archivo"]["name"];
    $tmp = $_FILES["archivo"]["tmp_name"];

    // Crear nombre único
    $nombre_unico = time() . "_" . $nombre_original;

    $ruta_destino = "../uploads/" . $nombre_unico;

    // Crear carpeta si no existe
    if (!file_exists("../uploads/")) {
        mkdir("../uploads/", 0777, true);
    }

    if (move_uploaded_file($tmp, $ruta_destino)) {

        // Guardar en BD
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

// =========================
// 3️⃣ Redirigir al volver
// =========================

header("Location: cargar_historia.php?id=" . $id_paciente);
exit;
?>



