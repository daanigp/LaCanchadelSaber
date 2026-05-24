<?php
require_once __DIR__ . '/../assets/lib/fpdf.php';
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');
$conexion = conectarDB();

$dificultad = $_GET['difc'] ?? null;
$idDificultad = obtenerIDByName($conexion, 'dificultades', $dificultad);

// Construir WHERE dinámico
$where = [];
$params = [];

if ($dificultad) {
    $where[] = "d.id = ?";
    $params[] = $idDificultad;
}

$sql = "
    SELECT 
        p.id,
        u.nombre AS usuario,
        p.puntuacion,
        d.nombre AS dificultad,
        p.fecha
    FROM partidas p
    JOIN users u ON p.id_user = u.id
    JOIN dificultades d ON p.dificultad_id = d.id
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY p.puntuacion DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF en apaisado
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// Título
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Listado de Partidas', 0, 1, 'C');
$pdf->Ln(4);

// Anchos de columna (total ~277mm en A4 landscape)
$widths = [15, 60, 35, 60, 40, 60];

// Cabecera
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(50, 50, 50);
$pdf->SetTextColor(255, 255, 255);

$widths  = [15, 70, 40, 50, 70];
$headers = ['ID', 'Usuario', 'Puntuacion', 'Dificultad', 'Fecha'];
foreach ($headers as $i => $h) {
    $pdf->Cell($widths[$i], 9, $h, 1, 0, 'C', true);
}
$pdf->Ln();

// Filas
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$fill = false;

foreach ($partidas as $row) {
    // Filas alternadas para mejor lectura
    $pdf->SetFillColor($fill ? 230 : 255, $fill ? 230 : 255, $fill ? 230 : 255);

    $pdf->Cell($widths[0], 8, $row['id'],          1, 0, 'C', $fill);
	$pdf->Cell($widths[1], 8, $row['usuario'],      1, 0, 'L', $fill);
	$pdf->Cell($widths[2], 8, $row['puntuacion'],   1, 0, 'C', $fill);
	$pdf->Cell($widths[3], 8, $row['dificultad'],   1, 0, 'C', $fill);
	$pdf->Cell($widths[4], 8, $row['fecha'],        1, 0, 'C', $fill);
	$pdf->Ln();

    $fill = !$fill;
}

// Total de registros al pie
$pdf->Ln(3);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 8, 'Total de partidas: ' . count($partidas), 0, 1, 'R');

// Descargar
$pdf->Output('D', 'partidas.pdf');
?>
