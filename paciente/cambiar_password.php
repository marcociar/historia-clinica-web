<?php
include "seguridad_paciente.php";
include "../conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_usuario = $_SESSION["id"];
    $actual = $_POST["actual"];
    $nueva = $_POST["nueva"];
    $confirmar = $_POST["confirmar"];

    if ($nueva !== $confirmar) {
        $mensaje = "Las contraseñas nuevas no coinciden.";
    } else {

        $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$id_usuario]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($actual, $usuario["password"])) {

            $nuevo_hash = password_hash($nueva, PASSWORD_DEFAULT);

            $update = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $update->execute([$nuevo_hash, $id_usuario]);

            $mensaje = "Contraseña actualizada correctamente.";
        } else {
            $mensaje = "La contraseña actual es incorrecta.";
        }
    }
}
?>

<h2>Cambiar contraseña</h2>

<form method="POST">
    <input type="password" name="actual" placeholder="Contraseña actual" required><br><br>
    <input type="password" name="nueva" placeholder="Nueva contraseña" required><br><br>
    <input type="password" name="confirmar" placeholder="Confirmar nueva contraseña" required><br><br>
    <button type="submit">Actualizar contraseña</button>
</form>

<p><?= $mensaje ?></p>

<a href="panel_paciente.php">Volver</a>