<?php
    session_start();
    if(!isset($_SESSION['id']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }

    if(isset($_SESSION['rol']) && $_SESSION['rol'] !== 'ADMIN') {
        header("Location: ../public/index.php?redirigidoAdmin=true");
        exit;
    }

    $css = "../style/stylePanelPreguntas.css";
    include('../templates/header_admin.php');

    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');
    $conexion = conectarDB();

    $mensaje = "";
    $tipo_popup = "";

    $userID = 0;
    if(isset($_SESSION['id'])) {
        $userID = $_SESSION['id'];
    }

    $catSeleccionada = "";
    if(isset($_GET['cat'])) {
        $catSeleccionada = $_GET['cat'];
    }

    $difcSeleccionada = "";
    if(isset($_GET['difc'])) {
        $difcSeleccionada = $_GET['difc'];
    }

    // POPUP cunado se borra la pregunta
    if(isset($_GET['borrada'])) {
        $mensaje = "<i class='fa-solid fa-file-circle-check'></i> Se ha borrado la pregunta correctamente";
        $tipo_popup = "success";
    }

    // POPUP cunado se edita la pregunta
    if(isset($_GET['editada'])) {
        $mensaje = "<i class='fa-solid fa-file-circle-check'></i> Se ha editado la pregunta correctamente";
        $tipo_popup = "success";
    }

    // POPUP cunado se añade una nueva pregunta
    if(isset($_GET['added'])) {
        $mensaje = "<i class='fa-solid fa-file-circle-check'></i> Se ha añadido la pregunta correctamente";
        $tipo_popup = "success";
    }

    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
?>

    <main>
        <h1><i class="fa-solid fa-user-tie"></i> Administrador de preguntas (admin) <i class="fa-solid fa-user-tie"></i></h1>

        <div id="overlayPopUp" class="overlay-popup">
            <div class="popup-cont">
                <button class="cerrar-popup" id="btnCerrarPopUp">&times;</button>
                <p id="mensajePopUp"></p>
            </div>
        </div>

        <div class="filtros-preguntas">
            <form method="get">
                <div class="btns-form-preguntas">
                    <div class="filtro-btns">
                        <div class="filtro">
                            <label for="cat">Filtrar por categoria:</label>
                            <?php 
                                echo generarSelect($conexion, 'categorias', 'nombre', 'cat', $catSeleccionada);
                            ?>
                        </div>
                        <div class="filtro">
                            <label for="difc">Filtrar por dificultad:</label>
                            <?php 
                                echo generarSelect($conexion, 'dificultades', 'nombre', 'difc', $difcSeleccionada);
                            ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btnFiltro"><i class="fa-solid fa-filter"></i> Filtrar</button>
                </div>
            </form>

            <div class="acciones-preg">
                <a href="addPregunta.php" class="btn btn-new"><i class="fa-solid fa-file-circle-plus"></i> Nueva pregunta</a>
            </div>
        </div>

        <div class="tabla-preguntas">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>TITULO</th>
                        <th>RESPUESTA CORRECTA</th>
                        <th>RESPUESTA A</th>
                        <th>RESPUESTA B</th>
                        <th>RESPUESTA C</th>
                        <th>RESPUESTA D</th>
                        <th>CATEGORÍA</th>
                        <th>DIFICULTAD</th>
                        <th>CREADA POR</th>
                        <th>FECHA</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if(isset($_GET['cat']) && isset($_GET['difc'])){
                        $totalPaginas = generarTablaPanel($conexion, 'preguntas', $_GET['cat'], $_GET['difc'], $pagina);
                    } else if (isset($_GET['cat']) && !isset($_GET['difc'])) {
                        $totalPaginas = generarTablaPanel($conexion, 'preguntas', $_GET['cat'], 'TODAS', $pagina);
                    } else if (!isset($_GET['cat']) && isset($_GET['difc'])) {
                        $totalPaginas = generarTablaPanel($conexion, 'preguntas', 'TODAS', $_GET['difc'], $pagina);
                    } else {
                        $totalPaginas = generarTablaPanel($conexion, 'preguntas', 'TODAS', 'TODAS', $pagina);
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

    <?php 
        if($mensaje) { ?>
            <script>
                const phpMensaje = <?= json_encode($mensaje) ?>;
                const phpTipo = <?= json_encode($tipo_popup) ?>;
            </script>
    <?php
        }
    ?>
    </main>

<?php
    include("../templates/footer_admin.php");
?>