<?php
include "seguridad_medico.php";
include "../conexion.php";
require("../fpdf/fpdf.php");

if (!isset($_GET["id"])) {
    exit;
}

$id_paciente = $_GET["id"];

// Traer paciente
$sql = "SELECT * FROM pacientes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $id_paciente);
$stmt->execute();
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

// Traer historias
$sql_hist = "SELECT * FROM historias_clinicas 
             WHERE id_paciente = :id
             ORDER BY fecha DESC";

$stmt_hist = $pdo->prepare($sql_hist);
$stmt_hist->bindParam(":id", $id_paciente);
$stmt_hist->execute();
$historias = $stmt_hist->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial","B",16);

$pdf->Cell(0,10,"Historia Clinica",0,1,"C");
$pdf->Ln(5);

$pdf->SetFont("Arial","",12);
$pdf->Cell(0,10,"Paciente: ".$paciente["nombre"],0,1);

$pdf->Ln(5);

foreach($historias as $historia){
    $pdf->SetFont("Arial","B",12);
    $pdf->Cell(0,10,"Fecha: ".$historia["fecha"],0,1);

    $pdf->SetFont("Arial","",12);
    $pdf->MultiCell(0,8,"Diagnostico: ".$historia["diagnostico"]);
    $pdf->MultiCell(0,8,"Tratamiento: ".$historia["tratamiento"]);
    $pdf->Ln(5);
}

$pdf->Output();
