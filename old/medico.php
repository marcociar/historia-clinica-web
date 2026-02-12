
<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'medico') {
    header("Location: login.html");
    exit;
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Médico</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Panel del Médico</h1>

<button onclick="cerrarSesion()">Cerrar sesión</button>

<hr>

<h2>Pacientes</h2>
<button onclick="cargarPacientes()">Cargar pacientes</button>

<ul id="listaPacientes"></ul>

<h3>Nuevo paciente</h3>

<input type="text" id="nombrePaciente" placeholder="Nombre"><br><br>
<input type="text" id="apellidoPaciente" placeholder="Apellido"><br><br>
<input type="date" id="fechaNacimiento"><br><br>

<button onclick="crearPaciente()">Crear paciente</button>


<hr>

<h2>Historia Clínica</h2>

<input type="hidden" id="pacienteId">

<label>Diagnóstico</label><br>
<textarea id="diagnostico" rows="4"></textarea><br><br>

<label>Observaciones</label><br>
<textarea id="observaciones" rows="4"></textarea><br><br>

<button onclick="guardarHistoria()">Guardar historia</button>

<hr>

<!--  ESTO ES LO NUEVO  -->
<h3>Historias cargadas</h3>
<div id="historial"></div>
<!-- ESTO ES LO NUEVO -->

<hr>

<h2>Subir archivos</h2>
<input type="file" id="archivo">
<button onclick="subirArchivo()">Subir</button>

<script src="medico.js"></script>
</body>
</html>

