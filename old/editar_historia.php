<?php
session_start();
require_once '../conexion.php';

if (!isset($_GET['id'])) {
    echo "Historia no especificada.";
    exit;
}

$historia_id = (int) $_GET['id'];

$stmt = $conn->prepare("
    SELECT hc.*, p.nombre, p.apellido
    FROM historias_clinicas hc
    JOIN pacientes p ON hc.paciente_id = p.id
    WHERE hc.id = ?
");
$stmt->bind_param("i", $historia_id);
$stmt->execute();
$historia = $stmt->get_result()->fetch_assoc();

if (!$historia) {
    echo "Historia no encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar historia clínica</title>
</head>
<body>

<h1>Editar historia clínica</h1>

<h2>
    <?= htmlspecialchars($historia['nombre']) ?>
    <?= htmlspecialchars($historia['apellido']) ?>
</h2>

<form method="POST" action="actualizar_historia.php">
    <input type="hidden" name="historia_id" value="<?= $historia['id'] ?>">
    <input type="hidden" name="paciente_id" value="<?= $historia['paciente_id'] ?>">

    <p>
        <label>Diagnóstico</label><br>
        <input type="text" name="diagnostico"
               value="<?= htmlspecialchars($historia['diagnostico']) ?>"
               required style="width: 300px;">
    </p>

    <p>
        <label>Observaciones</label><br>
        <textarea name="observaciones" rows="5" cols="50" required><?= htmlspecialchars($historia['observaciones']) ?></textarea>
    </p>

    <button type="submit">Guardar cambios</button>
</form>

<p>
    <a href="ver_paciente.php?id=<?= $historia['paciente_id'] ?>">
        ← Volver a historia clínica
    </a>
</p>

</body>
</html>
