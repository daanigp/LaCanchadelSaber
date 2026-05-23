<?php
    session_start();
 
    if(!isset($_SESSION['id']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }

    $css = "../style/styleGame.css";
    require_once(__DIR__. "/../templates/header.php");

    $valorSeleccionadoDifc = "Facil";
    if(isset($_GET['difc'])) {
        $valorSeleccionadoDifc = $_GET['difc'];
    }
    $numPregFaciles = 0;
    $numPregMedia = 0;
    $numPregDificiles = 0;

    if ($valorSeleccionadoDifc === "Facil") {
        $numPregFaciles = 10;
        $numPregMedia = 6;
        $numPregDificiles = 4;
    } else if ($valorSeleccionadoDifc === "Media") {
        $numPregFaciles = 6;
        $numPregMedia = 10;
        $numPregDificiles = 4;
    } else if ($valorSeleccionadoDifc === "Dificil") {
        $numPregFaciles = 6;
        $numPregMedia = 4;
        $numPregDificiles = 10;
    }

    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');
    $conexion = conectarDB();

    $difID = obtenerIDByName($conexion, 'dificultades', $valorSeleccionadoDifc);

    $preguntasFaciles = obtenerPreguntasAleatorias($conexion, 'preguntas', $difID, $numPregFaciles);
    $preguntasMedias = obtenerPreguntasAleatorias($conexion, 'preguntas', $difID, $numPregMedia);
    $preguntasDificiles = obtenerPreguntasAleatorias($conexion, 'preguntas', $difID, $numPregDificiles);
    $preguntasListas = [];
    $error = "";

    if ($preguntasFaciles !== null && $preguntasMedias !== null && $preguntasDificiles !== null) {
        $preguntasListas = array_merge(
            $preguntasFaciles, $preguntasMedias, $preguntasDificiles
        );
        shuffle($preguntasListas);
    } else {
        $error = "Estamos teniendo una serie de problemas, trataremos de resolverlo lo antes posible. Lo sentimos.";
    }

    $preguntasParaJS = "";
 
    if(!empty($preguntasListas)) {
        $preguntasParaJS = array_map(fn($p) => [
            'id' => $p['id'],
            'pregunta' => $p['titulo'],
            'respuestas' => [
                ['letra' => 'A', 'texto' => $p['respuesta_A']],
                ['letra' => 'B', 'texto' => $p['respuesta_B']],
                ['letra' => 'C', 'texto' => $p['respuesta_C']],
                ['letra' => 'D', 'texto' => $p['respuesta_D']]
            ]
        ], $preguntasListas);
    } else {
        $error = "Estamos teniendo una serie de problemas, trataremos de resolverlo lo antes posible. Lo sentimos.";
    }

    $maxPreguntas = 20;
    $vidas = 3;


    $_SESSION['correctas'] = array_column(
        array_map(fn($p) => [
            'id' => $p['id'],
            'correcta' => $p['respuesta_correcta']
        ], $preguntasListas),
        'correcta',
        'id'
    );

    $_SESSION['dificultad_id'] = $difID;
    $_SESSION['dificultad_nombre'] = $valorSeleccionadoDifc;
    
?>
    <main>
        <?php 
            if ($error !== "") {
                echo "<p class='txt-err'>$error</p>";
            }
        ?>

        <div class="layout-game">
            <h1><i class="fa-solid fa-trophy" style="color: gold;"></i> Game · <?= strtoupper($valorSeleccionadoDifc) ?></h1>

            <div class="puntos-vidas">
                <div class="vidas-tot">
                </div>

                <div class="puntos-tot">
                    <span class="puntos"></span>
                </div>
            </div>

            <div class="pregunta-game">
                <h4 class="pregunta-numero">PREGUNTA <span id="preg-num"></span> DE <?= $maxPreguntas ?></h4>
                <p class="pregunta-txt" id="preg-txt"></p>
            </div>

            <div class="cont-respuestas" id="cont-resp">

            </div>

            <div class="cont-btn" id="cont-btn-next">
                <button class="bnt-next" id="bnt-next-preg">
                    Siguiente pregunta <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <div class="layout-result-game">

        </div>
    </main>

    <script>
        const PREGUNTAS = <?= json_encode($preguntasParaJS) ?>;
        const VIDAS_TOTAL = <?= $vidas ?>;
        const DIFICULTAD = "<?= $valorSeleccionadoDifc ?>";
        const TOTAL_PREG = <?= $maxPreguntas ?>;
    </script>
    <script src="../js/utils/game.js"></script>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>