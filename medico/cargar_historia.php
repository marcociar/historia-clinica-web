<?php
include "seguridad_medico.php";
include "../conexion.php";

if (!isset($_GET["id"])) {
    header("Location: listar_pacientes.php");
    exit;
}

$id_paciente = $_GET["id"];

// =========================
// 1️⃣ Traer datos del paciente
// =========================
$sql = "SELECT * FROM pacientes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $id_paciente, PDO::PARAM_INT);
$stmt->execute();
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    echo "Paciente no encontrado.";
    exit;
}

// =========================
// 2️⃣ Traer historias clínicas
// =========================
$sql_hist = "SELECT * FROM historias_clinicas 
             WHERE id_paciente = :id 
             ORDER BY fecha DESC";

$stmt_hist = $pdo->prepare($sql_hist);
$stmt_hist->bindParam(":id", $id_paciente, PDO::PARAM_INT);
$stmt_hist->execute();

$historias = $stmt_hist->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Historia Clínica</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<h2>Historia Clínica</h2>

<p><strong>Paciente:</strong> <?= $paciente["nombre"] ?></p>
<a href="generar_pdf.php?id=<?= $paciente["id"] ?>" target="_blank">
    📄 Descargar PDF
</a>


<hr>

<!-- ========================= -->
<!-- FORMULARIO NUEVA HISTORIA -->
<!-- ========================= -->

<h3>Nueva Consulta</h3>

<form action="guardar_historia.php" method="POST">
    <input type="hidden" name="id_paciente" value="<?= $paciente["id"] ?>">

    Diagnóstico:<br>
    <textarea name="diagnostico" required></textarea><br><br>

    Tratamiento:<br>
    <textarea name="tratamiento" required></textarea><br><br>

    <button type="submit">Guardar Historia</button>
</form>

<hr>

<!-- ========================= -->
<!-- HISTORIAL YA CARGADO -->
<!-- ========================= -->

<h3>Historial del Paciente</h3>

<?php if (count($historias) > 0): ?>

    <?php foreach($historias as $historia): ?>
        <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px;">
            
            <strong>Fecha:</strong> <?= $historia["fecha"] ?><br><br>

            <strong>Diagnóstico:</strong><br>
            <?= nl2br($historia["diagnostico"]) ?><br><br>

            <strong>Tratamiento:</strong><br>
            <?= nl2br($historia["tratamiento"]) ?>
            
            
            <br>Adjuntar archivo / imagen:<br>
            <input type="file" name="archivo"><br><br>
            <br><br>
                <a href="editar_historia.php?id=<?= $historia["id"] ?>&paciente=<?= $paciente["id"] ?>">
                    ✏️ Editar
                </a>
            <br>
                <a href="eliminar_historia.php?id=<?= $historia["id"] ?>&paciente=<?= $paciente["id"] ?>"
                onclick="return confirm('¿Seguro que deseas eliminar esta historia clínica?');">
                🗑️ Eliminar
                </a>
    <?php
    $sql_archivos = "SELECT * FROM archivos_historia WHERE id_historia = :id";
    $stmt_archivos = $pdo->prepare($sql_archivos);
    $stmt_archivos->bindParam(":id", $historia["id"]);
    $stmt_archivos->execute();
    $archivos = $stmt_archivos->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if ($archivos): ?>
        <strong>Archivos adjuntos:</strong><br>
        <?php foreach($archivos as $archivo): ?>
            <a href="../uploads/historias/<?= $archivo["archivo"] ?>" target="_blank">
                📎 <?= $archivo["nombre_archivo"] ?>
            </a><br>
        <?php endforeach; ?>
    <?php endif; ?>




        </div>
    <?php endforeach; ?>

<?php else: ?>
    <p>No hay historias clínicas cargadas todavía.</p>
<?php endif; ?>

<br>
<a href="listar_pacientes.php">⬅ Volver</a>

</body>
</html>
