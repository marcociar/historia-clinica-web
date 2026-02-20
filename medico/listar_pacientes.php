<?php 
include "seguridad_medico.php";
include "../conexion.php";

$sql = "SELECT * FROM pacientes";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pacientes</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilo propio opcional -->
    <style>
        body {
            background-color: #f4f7fa;
        }
        .card {
            border-radius: 12px;
        }
        .btn-medico {
            background-color: #0d6efd;
            color: white;
        }
        .btn-medico:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">🩺 Lista de Pacientes</h2>
        <a href="panel_medico.php" class="btn btn-secondary">⬅ Volver</a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <?php if(count($pacientes) > 0): ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Edad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($pacientes as $fila): ?>
                        <tr class="text-center">
                            <td><?= $fila["id"] ?></td>
                            <td><?= htmlspecialchars($fila["nombre"]) ?></td>
                            <td><?= htmlspecialchars($fila["dni"]) ?></td>
                            <td><?= htmlspecialchars($fila["edad"]) ?></td>
                            <td>
                                <a href="cargar_historia.php?id=<?= $fila["id"] ?>" 
                                   class="btn btn-sm btn-outline-primary me-2">
                                   📝 Historia
                                </a>

                                <a href="eliminar_paciente.php?id=<?= $fila["id"] ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('¿Seguro que querés eliminar este paciente y TODAS sus historias?')">
                                   🗑 Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <?php else: ?>

                <div class="alert alert-info text-center">
                    No hay pacientes cargados.
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


