<?php
include "seguridad_medico.php";
include "../conexion.php";

$id_paciente = $_POST["id_paciente"];
$diagnostico = $_POST["diagnostico"];
$tratamiento = $_POST["tratamiento"];

$sql = "INSERT INTO historias_clinicas 
        (id_paciente, diagnostico, tratamiento, fecha)
        VALUES (:id_paciente, :diagnostico, :tratamiento, NOW())";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
$stmt->bindParam(":diagnostico", $diagnostico);
$stmt->bindParam(":tratamiento", $tratamiento);

if ($stmt->execute()) {
    header("Location: listar_pacientes.php");
    exit;
} else {
    echo "Error al guardar la historia clínica.";
}

// Obtener el ID de la historia recién creada
$id_historia = $pdo->lastInsertId();

// Verificar si se subió archivo
if (isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] == 0) {

    $nombre_original = $_FILES["archivo"]["name"];
    $tmp = $_FILES["archivo"]["tmp_name"];

    $nombre_unico = time() . "_" . $nombre_original;

    $ruta_destino = "../uploads/historias/" . $nombre_unico;

    move_uploaded_file($tmp, $ruta_destino);

    // Guardar en BD
    $sql_archivo = "INSERT INTO archivos_historia (id_historia, nombre_archivo, archivo)
                    VALUES (:id_historia, :nombre, :archivo)";

    $stmt_archivo = $pdo->prepare($sql_archivo);
    $stmt_archivo->execute([
        ":id_historia" => $id_historia,
        ":nombre" => $nombre_original,
        ":archivo" => $nombre_unico
    ]);
}

?>


