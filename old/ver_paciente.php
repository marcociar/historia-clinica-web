<?php
session_start();
require_once '../conexion.php';

if (!isset($_GET['id'])) {
    echo "Paciente no especificado.";
    exit;
}

$paciente_id = (int) $_GET['id'];

/* =========================
   DATOS DEL PACIENTE
========================= */
$stmt = $conn->prepare("
    SELECT nombre, apellido, fecha_nacimiento
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

/* =========================
   HISTORIAS CLÍNICAS
========================= */
$stmt = $conn->prepare("
    SELECT id, diagnostico, observaciones, fecha
    FROM historias_clinicas
    WHERE paciente_id = ?
    ORDER BY fecha DESC
");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$historias = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historia clínica</title>
</head>
<body>

<h1>Historia clínica</h1>

<h2>
    <?= htmlspecialchars($paciente['nombre']) ?>
    <?= htmlspecialchars($paciente['apellido']) ?>
</h2>

<p>
    <strong>Fecha de nacimiento:</strong>
    <?= htmlspecialchars($paciente['fecha_nacimiento']) ?>
</p>

<hr>

<h3>Historias registradas</h3>

<?php if ($historias->num_rows > 0): ?>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Diagnóstico</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($h = $historias->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($h['fecha']) ?></td>
                    <td><?= htmlspecialchars($h['diagnostico']) ?></td>
                    <td><?= htmlspecialchars($h['observaciones']) ?></td>
                    <td>
                        <a href="editar_historia.php?id=<?= $h['id'] ?>">Editar</a> |
                        <a href="eliminar_historia.php?id=<?= $h['id'] ?>&paciente_id=<?= $paciente_id ?>"
                        onclick="return confirm('¿Seguro que desea eliminar esta historia clínica? Esta acción no se puede deshacer.')">
                        Eliminar
                        </a>
                    </td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay historias clínicas registradas.</p>
<?php endif; ?>

<br>

<p>
    <a href="nueva_historia.php?id=<?= $paciente_id ?>">➕ Nueva historia</a>
</p>

<p>
    <a href="pacientes.php">← Volver a pacientes</a>
</p>

</body>
</html>


