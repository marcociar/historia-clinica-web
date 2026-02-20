<?php include "seguridad_medico.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Paciente</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<form action="guardar_paciente.php" method="POST">

    <input type="text" name="nombre" placeholder="Nombre" required>
    <br><br>

    <input type="text" name="apellido" placeholder="Apellido" required>
    <br><br>

    <input type="text" name="dni" placeholder="DNI" required>
    <br><br>

    <input type="number" name="edad" placeholder="Edad" required>
    <br><br>

    <input type="email" name="email" placeholder="Email del paciente" required>
    <br><br>

    <button type="submit">Guardar</button>

</form>


<a href="panel_medico.php">Volver</a>

</body>
</html>



