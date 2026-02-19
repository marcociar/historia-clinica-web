<?php
include "seguridad_paciente.php";
include "../conexion.php";

$id_usuario = $_SESSION["id"];

/* Buscar paciente vinculado al usuario */
$sqlPaciente = "SELECT id, nombre, apellido 
                FROM pacientes 
                WHERE usuario_id = :id_usuario";

$stmtPaciente = $pdo->prepare($sqlPaciente);
$stmtPaciente->execute(["id_usuario" => $id_usuario]);
$paciente = $stmtPaciente->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    die("Paciente no vinculado al usuario.");
}

$id_paciente = $paciente["id"];

/* Obtener historias */
$sql = "SELECT * FROM historias_clinicas
        WHERE id_paciente = :id_paciente
        ORDER BY fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(["id_paciente" => $id_paciente]);
$historias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel Paciente</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="layout">

    <div class="sidebar">
        <h2>👤 Paciente</h2>
        <a href="#">📄 Mi Historia</a>
        <a href="../logout.php">🚪 Cerrar sesión</a>
    </div>

    <div class="content">
        <div class="header">
            Bienvenido <?= $paciente["nombre"] . " " . $paciente["apellido"]; ?>
        </div>

        <?php foreach ($historias as $historia): ?>
        <div class="card historia-card">
            <div class="historia-grid">

                <div>
                    <strong>Diagnóstico:</strong><br>
                    <textarea readonly><?= $historia["diagnostico"] ?></textarea>
                </div>

                <div>
                    <strong>Tratamiento:</strong><br>
                    <textarea readonly><?= $historia["tratamiento"] ?></textarea>
                </div>
            <?php if (!empty($historia["comentario_paciente"])): ?>
                <div style="margin-top:10px; padding:10px; background:#f0f8ff; border-radius:8px;">
                    <strong>Comentario guardado:</strong><br>
                    <?= htmlspecialchars($historia["comentario_paciente"]) ?>
                </div>
            <?php endif; ?>


            </div>
           <!-- Comentario del paciente -->
            <form action="guardar_comentario.php" method="POST">
                <input type="hidden" name="id_historia" value="<?= $historia["id"] ?>">

                <strong>Mi comentario:</strong><br>

                <textarea name="comentario" style="width:100%; height:80px; border-radius:8px; padding:10px;">
            <?= htmlspecialchars($historia["comentario_paciente"] ?? "") ?>
                </textarea><br><br>

                <button type="submit" style="
                    background:#2e86de;
                    color:white;
                    border:none;
                    padding:10px 20px;
                    border-radius:8px;
                    cursor:pointer;
                ">
                    <?= empty($historia["comentario_paciente"]) ? "Guardar comentario" : "Actualizar comentario" ?>
                </button>
            </form>


            <br>

            <!-- Subir archivo -->
            <form action="subir_archivo.php" method="POST" enctype="multipart/form-data" class="form-box">
                <input type="hidden" name="id_historia" value="<?= $historia["id"] ?>">

                <input type="file" name="archivo" required>
                <button type="submit">Subir archivo</button>
            </form>

            <br>

            <?php
            $sqlArchivos = "SELECT * FROM archivos_historia WHERE id_historia = :id_historia";
            $stmtArchivos = $pdo->prepare($sqlArchivos);
            $stmtArchivos->execute([":id_historia" => $historia["id"]]);
            $archivos = $stmtArchivos->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($archivos): ?>
                <div class="archivos-section">
                    <strong>📎 Archivos adjuntos</strong>

                    <div class="archivos-grid">
                        <?php foreach ($archivos as $archivo): ?>
                            <a class="archivo-card"
                            href="../uploads/<?= $archivo["archivo"] ?>"
                            target="_blank">
                            <?= $archivo["nombre_archivo"] ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>


</div>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>




