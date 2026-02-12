
<?php
include "seguridad_medico.php";
include "../conexion.php";

if (!isset($_GET["id"]) || !isset($_GET["paciente"])) {
    header("Location: listar_pacientes.php");
    exit;
}

$id_historia = $_GET["id"];
$id_paciente = $_GET["paciente"];

// Eliminar con PDO
$sql = "DELETE FROM historias_clinicas WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $id_historia, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: cargar_historia.php?id=" . $id_paciente);
    exit;
} else {
    echo "Error al eliminar.";
}
?>
