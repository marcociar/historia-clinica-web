<?php
session_start();
require_once '../conexion.php';

if (!isset($_GET['id'])) {
    echo "Paciente no especificado.";
    exit;
}

$paciente_id = (int) $_GET['id'];

/* Obtener datos del paciente */
$stmt = $conn->prepare("
    SELECT nombre, apellido
    FROM pacientes
    WHERE id = ?
");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();

if (!$paciente) {
    echo "Paciente no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva historia clínica</title>
</head>
<body>

<h1>Nueva historia clínica</h1>

<h2>
    <?= htmlspecialchars($paciente['nombre']) ?>
    <?= htmlspecialchars($paciente['apellido']) ?>
</h2>

<form method="POST" action="guardar_historia.php">
    <input type="hidden" name="paciente_id" value="<?= $paciente_id ?>">

    <p>
        <label>Diagnóstico</label><br>
        <input type="text" name="diagnostico" required style="width: 300px;">
    </p>

    <p>
        <label>Observaciones</label><br>
        <textarea name="observaciones" rows="5" cols="50" required></textarea>
    </p>

    <button type="submit">Guardar historia</button>
</form>

<p>
    <a href="ver_paciente.php?id=<?= $paciente_id ?>">← Volver a historia clínica</a>
</p>

</body>
</html>
