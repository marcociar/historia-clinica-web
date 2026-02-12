<?php
include "seguridad_medico.php";
include "../conexion.php";

if (!isset($_GET["id"]) || !isset($_GET["paciente"])) {
    header("Location: listar_pacientes.php");
    exit;
}

$id_historia = $_GET["id"];
$id_paciente = $_GET["paciente"];

// Traer historia
$sql = "SELECT * FROM historias_clinicas WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $id_historia, PDO::PARAM_INT);
$stmt->execute();

$historia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$historia) {
    echo "Historia no encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Historia Clínica</title>
</head>
<body>

<h2>Editar Historia Clínica</h2>

<form action="actualizar_historia.php" method="POST">
    <input type="hidden" name="id_historia" value="<?= $historia["id"] ?>">
    <input type="hidden" name="id_paciente" value="<?= $id_paciente ?>">

    Diagnóstico:<br>
    <textarea name="diagnostico" required><?= $historia["diagnostico"] ?></textarea><br><br>

    Tratamiento:<br>
    <textarea name="tratamiento" required><?= $historia["tratamiento"] ?></textarea><br><br>

    <button type="submit">Actualizar</button>
</form>

</body>
</html>
