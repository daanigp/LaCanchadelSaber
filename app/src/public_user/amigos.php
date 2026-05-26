<?php
    session_start();
 
    if(!isset($_SESSION['id']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }

    $css = "../style/styleAmigos.css";
    require_once(__DIR__. "/../templates/header.php");

    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');
    $conexion = conectarDB();

    $userId = $_SESSION['id'];
    $errorSolicitud = "";
    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    $usuariosPorPagina = 5;

    $borrado = false;
    $rechazado = false;
    $aceptado = false;
    $enviada = false;

    $mensaje = "";
    $tipo_popup = "";

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        if(isset($_POST['env-solicitud'])) {
            $nombreAmigo = $_POST['buscar-amigo'];
            $idAmigo = obtenerIDByNick($conexion, 'users', $nombreAmigo);

            if ($idAmigo === 0) {
                $_SESSION['textNoEnviada'] = "No se ha encontrado el usuario con nick: " . $nombreAmigo;
            } else {
                $existeSolicitud = comprobarSolicitud($conexion, $userId, $idAmigo);

                if($existeSolicitud) {
                    $_SESSION['textSolicitudExiste'] = "Ya se envió la solicitud a " . $nombreAmigo . "<br>Esperando respuesta...";
                } else {
                    $enviada = enviarSolicitud($conexion, $userId, $idAmigo);
                    if($enviada) {
                        $_SESSION['textEnviada'] = "Ha sido enviada la solicitud a " . $nombreAmigo;
                    }
                }
            }
        }

        if(isset($_POST['borrar-amigo'])) {
            $idAmigo = $_POST['id-amigo'];
            $nombreAmigo = obtenerNombreById($conexion, 'users', $idAmigo, 'nick');
            $borrado = borrarAmigo($conexion, $userId, $idAmigo);
            if($borrado) {
                $_SESSION['textBorrado'] = $nombreAmigo ." ha sido eliminado de tu lista de amigos";
                $mensaje = "<i class='fa-solid fa-user-xmark'></i> " . $nombreAmigo ." ha sido eliminado de tu lista de amigos";
                $tipo_popup = "success";
            }
        }

        if(isset($_POST['rechazar-desktop']) || isset($_POST['rechazar-mobile'])) {
            $idNuevoAmigo = $_POST['id_new_friend'];
            $nombreAmigo = obtenerNombreById($conexion, 'users', $idNuevoAmigo, 'nick');
            $rechazado = rechazarAmigo($conexion, $userId, $idNuevoAmigo);
            if($rechazado) {
                $_SESSION['txtRechazado'] = $nombreAmigo ." ha sido rechazado de tu lista de amigos";
                $mensaje = "<i class='fa-solid fa-user-xmark'></i> " . $nombreAmigo ." ha sido rechazado de tu lista de amigos";
                $tipo_popup = "success";
            }
        }

        if(isset($_POST['aceptar-desktop']) || isset($_POST['aceptar-mobile'])) {
            $idNuevoAmigo = $_POST['id_new_friend'];
            $nombreAmigo = obtenerNombreById($conexion, 'users', $idNuevoAmigo, 'nick');
            $aceptado = aceptarAmigo($conexion, $userId, $idNuevoAmigo);
            if($aceptado) {
                $_SESSION['txtAceptado'] = $nombreAmigo ." ha sido aceptado en tu lista de amigos";
                $mensaje = "<i class='fa-solid fa-user-check'></i> " . $nombreAmigo ." ha sido aceptado en tu lista de amigos";
                $tipo_popup = "success";
            }
        }
    }
?>
    <main class="layout-amigos">
        <div class="ver-amigos">
            <h4>MIS AMIGOS (<?= "NUMERO DE AMIGOS TOTALES DEL USUARIO" ?>)</h4>
            
            <?php
            if(isset($_SESSION['textBorrado'])) {
                echo "<p class='txt-success'>" . $_SESSION['textBorrado'] . "</p>";
                unset($_SESSION['textBorrado']);
            }
            ?>

            <?php veramigos($conexion, $userId) ?>
        </div>

        <div id="overlayPopUp" class="overlay-popup">
            <div class="popup-cont">
                <button class="cerrar-popup" id="btnCerrarPopUp">&times;</button>
                <p id="mensajePopUp"></p>
            </div>
        </div>

        <div class="solicitudes">
            <h4>SOLICITUDES</h4>


            <div class="solicitud">
                <?php
                if(isset($_SESSION['txtRechazado'])) {
                    echo "<p class='txt-err'>" . $_SESSION['txtRechazado'] . "</p>";
                    unset($_SESSION['txtRechazado']);
                }

                if(isset($_SESSION['txtAceptado'])) {
                    echo "<p class='txt-success'>" . $_SESSION['txtAceptado'] . "</p>";
                    unset($_SESSION['txtAceptado']);
                }
                ?>
               <?php solicitudesPendientes($conexion, $userId) ?>
            </div>

            <form action="" class="add-amigo" method="post">
                <label for="add-tit">Añadir amigo</label>

                <div class="search-amigos">
                    <!-- comprobar que no empiece por '@', si empieza por '@', quitarselo -->
                    <input type="text" name="buscar-amigo" id="buscar-amigo" placeholder="nickname ...">

                    <input type="submit" name="env-solicitud" value="Enviar solicitud">
                </div>
            </form>

            <?php
                if(isset($_SESSION['textSolicitudExiste'])) {
                    echo "<p class='txt-err'>" . $_SESSION['textSolicitudExiste'] . "</p>";
                    unset($_SESSION['textSolicitudExiste']);
                }

                if(isset($_SESSION['textNoEnviada'])) {
                    echo "<p class='txt-err'>" . $_SESSION['textNoEnviada'] . "</p>";
                    unset($_SESSION['textNoEnviada']);
                }

                if(isset($_SESSION['textEnviada'])) {
                    echo "<p class='txt-success'>" . $_SESSION['textEnviada'] . "</p>";
                    unset($_SESSION['textEnviada']);
                }
            ?>
        </div>

        <div class="ranking-amigos">
            <table class="tabla-pts">
                <thead>
                    <tr>
                        <th>PUNTOS</th>
                        <th>NICK</th>
                        <th>DIFICULTAD</th>
                        <th>FECHA</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $totalPaginas = generarRankingAmigos($conexion, $userId, 'TODAS', $pagina, $usuariosPorPagina);
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
    require_once(__DIR__. "/../templates/footer.php");
?>