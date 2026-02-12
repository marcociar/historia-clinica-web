<?php
include("seguridad_medico.php");
include("../conexion.php");

// Traer pacientes para el select
$sql = "SELECT * FROM pacientes ORDER BY nombre ASC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nueva Historia Clínica</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Nueva Historia Clínica</h2>

<form action="guardar_historia.php" method="POST">


    <label>Paciente:</label><br>
    <select name="id_paciente" required>
        <option value="">Seleccionar paciente</option>
        <?php while($fila = $resultado->fetch_assoc()) { ?>
            <option value="<?php echo $fila['id']; ?>">
                <?php echo $fila['nombre']; ?>
            </option>
        <?php } ?>
    </select>
    <br><br>

    <label>Diagnóstico:</label><br>
    <textarea name="diagnostico" required></textarea>
    <br><br>

    <label>Tratamiento:</label><br>
    <textarea name="tratamiento" required></textarea>
    <br><br>
    Adjuntar archivo / imagen:<br>
    <input type="file" name="archivo"><br><br>


    <button type="submit">Guardar Historia</button>

</form>

<br>
<a href="panel_medico.php">Volver</a>

</body>
</html>
