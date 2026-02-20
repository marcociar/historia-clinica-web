<?php
session_start();
include "seguridad_paciente.php";
include "../conexion.php";

/* Validar sesión */
if (!isset($_SESSION["id"]) || $_SESSION["rol"] !== "paciente") {
    header("Location: ../login.php");
    exit;
}

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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Paciente</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
        }

        .sidebar {
            min-height: 100vh;
            background: #0d2b6b;
            padding: 30px 20px;
        }

        .sidebar h4 {
            color: white;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            background: #1b4cb8;
        }

        .sidebar a:hover {
            background: #2563eb;
        }

        .content-area {
            padding: 30px;
        }

        .historia-card {
            border-left: 5px solid #0d6efd;
        }

        textarea[readonly] {
            background: #f8f9fa !important;
        }

        .archivo-card {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-lg-2 sidebar">
            <h4>👤 Paciente</h4>
            <a href="cambiar_password.php">🔐 Cambiar contraseña</a>
            <a href="#">📄 Mi Historia</a>
            <a href="../logout.php">🚪 Cerrar sesión</a>
        </div>

        <!-- CONTENIDO -->
        <div class="col-lg-10 content-area bg-light">

            <h3 class="mb-4 text-primary">
                Bienvenido <?= htmlspecialchars($paciente["nombre"] . " " . $paciente["apellido"]); ?>
            </h3>

            <?php foreach ($historias as $historia): ?>

            <div class="card shadow mb-4 historia-card">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Diagnóstico</h6>
                            <textarea class="form-control" rows="4" readonly>
<?= htmlspecialchars($historia["diagnostico"]) ?>
                            </textarea>
                        </div>

                        <div class="col-md-6">
                            <h6>Tratamiento</h6>
                            <textarea class="form-control" rows="4" readonly>
<?= htmlspecialchars($historia["tratamiento"]) ?>
                            </textarea>
                        </div>
                    </div>

                    <!-- Comentario guardado -->
                    <?php if (!empty($historia["comentario_paciente"])): ?>
                        <div class="alert alert-info">
                            <strong>💬 Comentario guardado:</strong><br>
                            <?= htmlspecialchars($historia["comentario_paciente"]) ?>
                        </div>
                    <?php endif; ?>

                    <!-- FORM COMENTARIO -->
                    <form class="form-comentario mb-3">
                        <input type="hidden" name="id_historia" value="<?= $historia["id"] ?>">

                        <label class="form-label">Mi comentario</label>
                        <textarea name="comentario" class="form-control mb-3" rows="3">
<?= htmlspecialchars($historia["comentario_paciente"] ?? "") ?>
                        </textarea>

                        <button class="btn btn-primary w-100">
                            <?= empty($historia["comentario_paciente"]) 
                                ? "Guardar comentario" 
                                : "Actualizar comentario" ?>
                        </button>

                        <div class="mensaje-ajax mt-2 fw-bold"></div>
                    </form>

                    <!-- SUBIR ARCHIVO -->
                    <form action="subir_archivo.php" method="POST" enctype="multipart/form-data" class="mb-3">
                        <input type="hidden" name="id_historia" value="<?= $historia["id"] ?>">

                        <div class="row">
                            <div class="col-md-8">
                                <input type="file" name="archivo" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success w-100">
                                    📎 Subir archivo
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- ARCHIVOS -->
                    <?php
                    $sqlArchivos = "SELECT * FROM archivos_historia WHERE id_historia = :id_historia";
                    $stmtArchivos = $pdo->prepare($sqlArchivos);
                    $stmtArchivos->execute([":id_historia" => $historia["id"]]);
                    $archivos = $stmtArchivos->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if ($archivos): ?>
                       

                        <?php if ($archivos): ?>
                        <h6 class="mt-3">📎 Archivos adjuntos</h6>

                        <div class="d-flex flex-wrap gap-3 mt-2">
                        <?php foreach ($archivos as $archivo): ?>

                            <?php
                            $nombreArchivo = $archivo["archivo"];
                            $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

                            // CORRECTO: sin /historias/
                            $rutaWeb = "/historiaclinicafinal1/uploads/" . $nombreArchivo;
                            ?>

                            <?php if (in_array($extension, ['jpg','jpeg','png','gif'])): ?>

                                <div>
                                    <img src="<?= $rutaWeb ?>" 
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height:200px;">
                                </div>

                            <?php elseif ($extension === 'pdf'): ?>

                                <div style="width:100%;">
                                    <embed src="<?= $rutaWeb ?>" 
                                        type="application/pdf"
                                        width="100%"
                                        height="300px" />
                                </div>

                            <?php else: ?>

                                <a href="<?= $rutaWeb ?>" target="_blank"
                                class="btn btn-sm btn-outline-secondary">
                                📎 <?= htmlspecialchars($archivo["nombre_archivo"]) ?>
                                </a>

                            <?php endif; ?>

                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php endif; ?>

                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</div>

<!-- AJAX SCRIPT (igual que el tuyo) -->
<script>
document.querySelectorAll(".form-comentario").forEach(form => {
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(form);
        let mensaje = form.querySelector(".mensaje-ajax");
        mensaje.innerHTML = "⏳ Guardando...";

        fetch("guardar_comentario.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            mensaje.innerHTML = "✅ Comentario guardado correctamente";
            mensaje.style.color = "green";
        })
        .catch(error => {
            mensaje.innerHTML = "❌ Error al guardar";
            mensaje.style.color = "red";
        });
    });
});
</script>

</body>
</html>





