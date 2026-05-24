<?php
    $css = "../style/styleScores.css";
    require_once(__DIR__. "/../templates/header.php");

    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');
    $conexion = conectarDB();

    $valorSeleccionadoDifc = "";
    if(isset($_GET['difc'])) {
        $valorSeleccionadoDifc = $_GET['difc'];
    }

    $userID = 0;
    if(isset($_SESSION['id'])) {
        $userID = $_SESSION['id'];
    }

    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
?>
    <main>
        <h1 class="tit-scores"><i class="fa-solid fa-ranking-star"></i> PUNTUACIONES <i class="fa-solid fa-ranking-star"></i></h1>

        <div class="scores-top">
            <div class="filtros-scores">
                <form method="get">
                    <div class="btns-form-scores">
                        <div class="filtro-btns">
                            <div class="filtro">
                                <label for="difc">Filtrar por dificultad:</label>
                                <?php 
                                    echo generarSelect($conexion, 'dificultades', 'nombre', 'difc', $valorSeleccionadoDifc);
                                ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btnFiltro"><i class="fa-solid fa-filter"></i> Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="tabla-puntuacion">
            <table class="tabla-pts">
                <thead>
                    <tr>
                        <th>PUNTOS</th>
                        <th>NOMBRE</th>
                        <th>DIFICULTAD</th>
                        <th>FECHA</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if(isset($_GET['difc'])){
                        $totalPaginas = generarTabla($conexion, $userID, 'partidas', $_GET['difc'], $pagina);
                    } else {
                        $totalPaginas = generarTabla($conexion, $userID, 'partidas', 'TODAS', $pagina);
                    }
                    ?>
                </tbody>
            </table>

            <?php 
                if ($totalPaginas > 1) {?>
                    <div class="paginacion">
                        <?php
                        // Construimos los params GET conservando el filtro
                        $params = $_GET;

                        for ($i = 1; $i <= $totalPaginas; $i++) {
                            $params['pagina'] = $i;
                            $url = '?' . http_build_query($params);
                            $activa = ($i === $pagina) ? 'activa' : '';
                        ?>
                            <a href="<?= $url ?>" class="btn-pagina <?= $activa ?>"><?= $i ?></a>
                        <?php 
                        } 
                        ?>
                    </div>
        <?php   }   ?>
        </div>
    </main>

<?php
    require_once(__DIR__. "/../templates/footer.php");
?>