<?php
require_once 'vendor/autoload.php';

//use setasign\Fpdf\fpdf;

//echo 111;
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',16);
$pdf->Cell(40,10,$name);

$pdf->SetFont('Arial','',12);
$pdf->Cell(40,10,"\n",0,1);
$pdf->Cell(40,10,$qualification,0,1);

$pdf->Cell(40,10,$email,0,1);
$pdf->Output();
