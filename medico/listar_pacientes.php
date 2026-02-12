<?php 
include "seguridad_medico.php";
include "../conexion.php";

$sql = "SELECT * FROM pacientes";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Pacientes</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<h2>Lista de Pacientes</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>DNI</th>
        <th>Edad</th>
        <th>Acción</th>
    </tr>

    <?php foreach($pacientes as $fila): ?>
        <tr>
            <td><?= $fila["id"] ?></td>
            <td><?= $fila["nombre"] ?></td>
            <td><?= $fila["dni"] ?></td>
            <td><?= $fila["edad"] ?></td>
            <td>
                <a href="cargar_historia.php?id=<?= $fila["id"] ?>">
                    📝 Cargar Historia
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<br>
<a href="panel_medico.php">⬅ Volver</a>

</body>
</html>


