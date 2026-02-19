
<?php
include "seguridad_medico.php";

include "../conexion.php";

/* Total pacientes */
$totalPacientes = $pdo->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();

/* Total historias */
$totalHistorias = $pdo->query("SELECT COUNT(*) FROM historias_clinicas")->fetchColumn();

/* Total archivos */
$totalArchivos = $pdo->query("SELECT COUNT(*) FROM archivos_historia")->fetchColumn();

/* Historias este mes */
$totalMes = $pdo->query("
    SELECT COUNT(*) FROM historias_clinicas 
    WHERE MONTH(fecha) = MONTH(CURRENT_DATE())
    AND YEAR(fecha) = YEAR(CURRENT_DATE())
")->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel Médico</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="layout">

    <div class="sidebar">
        <h2>🏥 Clínica</h2>

        <a href="nuevo_paciente.php">➕ Nuevo Paciente</a>
        <a href="listar_pacientes.php">📋 Ver Pacientes</a>
        <a href="cargar_historia.php">📝 Cargar Historia Clinica</a>
        <a href="../logout.php">🚪 Cerrar sesión</a>
    </div>

    <div class="content">
        <div class="header">Panel del Médico</div>

        <p>Bienvenido al sistema de gestión clínica.</p>

        <a href="nuevo_paciente.php" class="btn-hospital">
            Crear nuevo paciente
        </a>
        <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px; margin-top:30px;">

            <div class="card-dashboard">
                <h3><?= $totalPacientes ?></h3>
                <p>Pacientes</p>
            </div>

            <div class="card-dashboard">
                <h3><?= $totalHistorias ?></h3>
                <p>Historias clínicas</p>
            </div>

            <div class="card-dashboard">
                <h3><?= $totalArchivos ?></h3>
                <p>Archivos cargados</p>
            </div>

            <div class="card-dashboard">
                <h3><?= $totalMes ?></h3>
                <p>Historias este mes</p>
            </div>

        </div>

    </div>

</div>

</body>
</html>







