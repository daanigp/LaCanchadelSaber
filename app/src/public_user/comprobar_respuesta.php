<?php
    session_start();

    // Si entra sin loguearse se expulsa
    if(!isset($_SESSION['id']) && !isset($_SESSION['rol'])) {
        http_response_code(401);
        echo json_encode(['error' => '1. No autorizado']);
        exit();
    }

    // Si entra forzando la url se expulsa
    if (
        !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
    ) {
        http_response_code(401);
        echo json_encode(['error' => '2. No autorizado']);
        exit();
    }

    // Indicamos que va a devolver un tipo json
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);
    $pregunta_id = (int) $data['pregunta_id'];
    $letra_dada = strtoupper(trim($data['letra']));

    $correcta = $_SESSION['correctas'][$pregunta_id] ?? null;

    if (!$correcta) {
        echo json_encode(['error' => 'Pregunta no válida']);
        exit;
    }

    $es_correcta = $letra_dada === $correcta;

    // Guarda la respuesta
    $_SESSION['respuestas_dadas'][$pregunta_id] = [
        'letra' => $letra_dada,
        'es_correcta' => $es_correcta
    ];

    // Se devuelve la letra correcta y si se ha acertado
    echo json_encode([
        'correcta' => $es_correcta,
        'correcta_letra' => $correcta
    ]);
?>