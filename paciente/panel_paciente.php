<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "paciente") {
    header("Location: ../login.html");
    exit;
}
?>

<h1>Panel Paciente</h1>
<a href="../logout.php">Cerrar sesión</a>

