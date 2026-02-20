<?php
include "seguridad_medico.php";
include "../conexion.php";

if (!isset($_GET["id"])) {
    header("Location: listar_pacientes.php");
    exit;
}

$id_paciente = $_GET["id"];

// =========================
// 1️⃣ Traer datos del paciente
// =========================
$sql = "SELECT * FROM pacientes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $id_paciente, PDO::PARAM_INT);
$stmt->execute();
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    echo "Paciente no encontrado.";
    exit;
}

// =========================
// 2️⃣ Traer historias clínicas
// =========================
$sql_hist = "SELECT * FROM historias_clinicas 
             WHERE id_paciente = :id 
             ORDER BY fecha DESC";

$stmt_hist = $pdo->prepare($sql_hist);
$stmt_hist->bindParam(":id", $id_paciente, PDO::PARAM_INT);
$stmt_hist->execute();
$historias = $stmt_hist->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historia Clínica</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7fa;
        }
        .card {
            border-radius: 12px;
        }
        .historia-card {
            border-left: 5px solid #0d6efd;
        }
        .comentario-box {
            background: #fff3cd;
            border-left: 5px solid #f39c12;
            padding: 10px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">🩺 Historia Clínica</h2>
        <a href="listar_pacientes.php" class="btn btn-secondary">⬅ Volver</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h5><strong>Paciente:</strong> <?= htmlspecialchars($paciente["nombre"]) ?></h5>

            <a href="generar_pdf.php?id=<?= $paciente["id"] ?>" 
               target="_blank"
               class="btn btn-outline-primary mt-2">
               📄 Descargar PDF
            </a>
        </div>
    </div>

    <!-- ========================= -->
    <!-- NUEVA CONSULTA -->
    <!-- ========================= -->

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            Nueva Consulta
        </div>

        <div class="card-body">

            <form action="guardar_historia.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id_paciente" value="<?= $paciente["id"] ?>">

                <div class="mb-3">
                    <label class="form-label">Diagnóstico</label>
                    <textarea name="diagnostico" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tratamiento</label>
                    <textarea name="tratamiento" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adjuntar archivo / imagen</label>
                    <input type="file" name="archivo" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">
                    💾 Guardar Historia
                </button>

            </form>

        </div>
    </div>

    <!-- ========================= -->
    <!-- HISTORIAL -->
    <!-- ========================= -->

    <h4 class="mb-3">📚 Historial del Paciente</h4>

    <?php if (count($historias) > 0): ?>

        <?php foreach($historias as $historia): ?>

            <div class="card shadow mb-4 historia-card">
                <div class="card-body">

                    <h6 class="text-muted">
                        📅 <?= $historia["fecha"] ?>
                    </h6>

                    <hr>

                    <p><strong>Diagnóstico:</strong><br>
                        <?= nl2br(htmlspecialchars($historia["diagnostico"])) ?>
                    </p>

                    <p><strong>Tratamiento:</strong><br>
                        <?= nl2br(htmlspecialchars($historia["tratamiento"])) ?>
                    </p>

                    <!-- Comentario paciente -->
                    <?php if (!empty($historia["comentario_paciente"])): ?>
                        <div class="comentario-box mt-3">
                            <strong>💬 Comentario del paciente:</strong><br>
                            <?= htmlspecialchars($historia["comentario_paciente"]) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Archivos -->
                    <?php
                    $sql_archivos = "SELECT * FROM archivos_historia WHERE id_historia = :id";
                    $stmt_archivos = $pdo->prepare($sql_archivos);
                    $stmt_archivos->bindParam(":id", $historia["id"]);
                    $stmt_archivos->execute();
                    $archivos = $stmt_archivos->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if ($archivos): ?>
                        <div class="mt-3">
                            <strong>📎 Archivos adjuntos:</strong><br><br>

                            <?php foreach($archivos as $archivo): ?>

                                <?php
                                $ruta = "../uploads/" . $archivo["archivo"];
                                $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                                ?>

                                <?php if(in_array($extension, ['jpg','jpeg','png','gif'])): ?>
                                    <img src="<?= $ruta ?>" class="img-thumbnail mb-2" width="200">
                                <?php else: ?>
                                    <a href="<?= $ruta ?>" target="_blank" 
                                       class="btn btn-sm btn-outline-secondary mb-2">
                                       📄 <?= htmlspecialchars($archivo["nombre_archivo"]) ?>
                                    </a>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- BOTONES -->
                    <div class="mt-3">
                        <a href="editar_historia.php?id=<?= $historia["id"] ?>&paciente=<?= $paciente["id"] ?>" 
                           class="btn btn-sm btn-outline-primary me-2">
                           ✏️ Editar
                        </a>

                        <a href="eliminar_historia.php?id=<?= $historia["id"] ?>&paciente=<?= $paciente["id"] ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('¿Seguro que deseas eliminar esta historia clínica?');">
                           🗑️ Eliminar
                        </a>
                    </div>

                </div>
            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="alert alert-info">
            No hay historias clínicas cargadas todavía.
        </div>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

