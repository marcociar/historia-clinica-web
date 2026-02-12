<?php include "seguridad_medico.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Paciente</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<h2>Nuevo Paciente</h2>

<form action="guardar_paciente.php" method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="apellido" placeholder="Apellido" required>
    <input type="date" name="fecha_nacimiento" required>
    <button type="submit">Guardar</button>
</form>


<a href="panel_medico.php">Volver</a>

</body>
</html>



