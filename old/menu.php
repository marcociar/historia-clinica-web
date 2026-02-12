<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "medico") {
    header("Location: ../login.html");
    exit;
}
?>

<nav style="margin-bottom:20px;">
    <strong>Médico</strong> |
    <a href="dashboard.php">Dashboard</a> |
    <a href="pacientes.php">Pacientes</a> |
    <a href="crear_paciente.php">Nuevo paciente</a> |
    <a href="logout.php">Cerrar sesión</a>
</nav>
<hr>
