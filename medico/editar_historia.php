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

<form action="actualizar_historia.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id_historia" value="<?= $historia['id'] ?>">
    <input type="hidden" name="id_paciente" value="<?= $_GET['paciente'] ?>">

    <label>Diagnóstico:</label><br>
    <textarea name="diagnostico" required><?= $historia['diagnostico'] ?></textarea><br><br>

    <label>Tratamiento:</label><br>
    <textarea name="tratamiento" required><?= $historia['tratamiento'] ?></textarea><br><br>

    <hr>

    <h4>Adjuntar nuevo archivo</h4>
    <input type="file" name="archivo">

    <br><br>
    <button type="submit">Actualizar</button>

</form>
<h4>Archivos Adjuntos</h4>

<?php
$sql_archivos = "SELECT * FROM archivos_historia WHERE id_historia = :id";
$stmt_archivos = $pdo->prepare($sql_archivos);
$stmt_archivos->execute([":id" => $historia['id']]);
$archivos = $stmt_archivos->fetchAll(PDO::FETCH_ASSOC);

if ($archivos):
    foreach ($archivos as $archivo):
?>

    <div style="margin-bottom:10px;">
        📎 
        <a href="../uploads/historias/<?= $archivo['archivo'] ?>" target="_blank">
            <?= $archivo['nombre_archivo'] ?>
        </a>

        <a href="eliminar_archivo.php?id=<?= $archivo['id'] ?>&historia=<?= $historia['id'] ?>&paciente=<?= $_GET['paciente'] ?>"
           onclick="return confirm('¿Eliminar archivo?')"
           style="color:red;">
           Eliminar
        </a>
    </div>

<?php
    endforeach;
else:
    echo "No hay archivos adjuntos.";
endif;
?>


</body>
</html>
