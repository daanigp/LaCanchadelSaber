<?php
session_start();

if(!isset($_SESSION['id']) && !isset($_SESSION['rol'])) {
    header("Location: ../public/login.php?redirigido=true");
    exit;
}

if (
    !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    header("Location: ../public/index.php?redirigido=true");
    exit;
}

// Indicamos que va a devolver un tipo json
header('Content-Type: application/json');

// Recogemos los datos obtenidos en la partida
$data = json_decode(file_get_contents('php://input'), true);

require_once('../includes/conexion.php');
require_once('../includes/funciones.php');
$conexion = conectarDB();

$userId = $_SESSION['id'];
$puntuacion = $data['puntos'];
$dificultadId = $_SESSION['dificultad_id'];

try {
    $partidaId = guardarPartida($conexion, $userId, $puntuacion, $dificultadId);

    if($partidaId != null) {
        // Guardamos las respuestas en la BBDD (para estadísticas)
        $sql = "INSERT INTO partida_respuestas 
                (id_partida, id_pregunta, respuesta_dada, es_correcta)
                VALUES (
                    :id_partida,
                    :id_pregunta,
                    :respuesta_dada,
                    :es_correcta
                )
        ";
        $stmtResp = $conexion->prepare($sql);

        foreach ($_SESSION['respuestas_dadas'] as $pregId => $resp) {
            $stmtResp->execute([
                ":id_partida" => $partidaId,
                ":id_pregunta" => $pregId,
                ":respuesta_dada" => $resp['letra'],
                ":es_correcta" => $resp['es_correcta'] ? 1 : 0
            ]);
        }

        // Limpiamos la sesión de juego
        unset($_SESSION['correctas']);
        unset($_SESSION['respuestas_dadas']);
        unset($_SESSION['dificultad_id']);

        echo json_encode(['ok' => true, 'partida_id' => $partidaId]);
    } else {
        echo json_encode(['err' => false, 'partida_id' => null]);
    }

} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>