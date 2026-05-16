<?php
    $css = "../style/styleScores.css";
    require_once(__DIR__. "/../templates/header.php");

    $conexion = conectarDB();

    $valorSeleccionadoCat = "";
    if(isset($_GET['cat'])) {
        $valorSeleccionadoCat = $_GET['cat'];
    }

    $valorSeleccionadoDifc = "";
    if(isset($_GET['difc'])) {
        $valorSeleccionadoDifc = $_GET['difc'];
    }
?>

    <main>
        <div class="scores-top">
            <h1><i class="fa-solid fa-ranking-star"></i> PUNTUACIONES <i class="fa-solid fa-ranking-star"></i></h1>

            <div class="filtros-scores">
                <form method="get">
                    <label for="cat">Filtrar por categoría:</label>
                    
                    <div class="btns-form-scores">
                        <?php 
                            echo generarSelect($conexion, 'categorias', 'nombre', 'cat', $valorSeleccionadoCat);
                        ?>
                        <?php 
                            if($valorSeleccionadoDifc !== "") {
                                echo "<input type='hidden' name='difc' value=" . $valorSeleccionadoDifc . ">";
                            }
                        ?>
                        <button type="submit" class="btn btnFiltro"><i class="fa-solid fa-filter"></i> Filtrar Categoría</button>
                    </div>
                </form>

                <form method="get">
                    <label for="difc">Filtrar por dificultad:</label>
                        
                    <div class="btns-form-scores">
                        <?php 
                            echo generarSelect($conexion, 'dificultades', 'nombre', 'difc', $valorSeleccionadoDifc);
                        ?>
                        <?php 
                            if($valorSeleccionadoCat !== "") {
                                echo "<input type='hidden' name='cat' value=" . $valorSeleccionadoCat . ">";
                            }
                        ?>
                        <button type="submit" class="btn btnFiltro"><i class="fa-solid fa-filter"></i> Filtrar Dificultad</button>
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