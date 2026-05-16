<?php
    $css = "../style/styleScores.css";
    require_once(__DIR__. "/../templates/header.php");

    $conexion = conectarDB();
?>

    <main>
        <div class="scores-top">
            <h1>PUNTUACIONES</h1>
        </div>

        <div class="tabla-puntuacion">
            <table class="tabla-pts">
                <thead>
                    <tr>
                        <th>PUNTOS</th>
                        <th>NOMBRE</th>
                        <th>CATEGORÍA</th>
                        <th>DIFICULTAD</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if(isset($_GET['cat']) && isset($_GET['difc'])){
                        generarTabla($conexion, 'partidas', $_GET['cat'], $_GET['difc']);
                    } else if (isset($_GET['cat']) && !isset($_GET['difc'])) {
                        generarTabla($conexion, 'partidas', $_GET['cat'], null);
                    } else if (!isset($_GET['cat']) && isset($_GET['difc'])) {
                        generarTabla($conexion, 'partidas', null, $_GET['difc']);
                    } else {
                        generarTabla($conexion, 'partidas');
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

<?php
    require_once(__DIR__. "/../templates/footer.php");
?>