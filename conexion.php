<?php
$conn = new mysqli("localhost", "root", "", "historiaclinicafinal1");

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "error" => "Error de conexión"
    ]));
}

