<?php
    session_start();
 
    if(!isset($_SESSION['id']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }

    $css = "../style/stylePerfil.css";
    require_once(__DIR__. "/../templates/header.php");
    
    $userId = $_SESSION['id'];

    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');
    $conexion = conectarDB();

    $usuario = null;
    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
?>
    <main>
        <?php 
            if(!isset($_GET['updated'])) {
                $usuario = obtenerDatosUsuarioById($conexion, $userId);
            } else {
                $usuario = obtenerDatosUsuarioById($conexion, $_GET['idUser']);
            }

            if($usuario === null) {
                echo "<p>El usuario " . $userId . " no ha sido encontrado</p>";
                echo "<p>Lo sentimos. Seguiremos trabajando para corregir los fallos</p>";
            } else { 
                $datosRanking = obtenerRankingUsuarioById($conexion, $usuario['id']); ?>
                <div class="perfil">
                    <div class="logo-perfil">
                        <img src="../static/img/profile/<?= $usuario['avatar_url'] ?? "nutria-2.jpg" ?>" alt="Imagen de perfil" width="150px" height="150px">
                    </div>
                    <div class="texto-perfil">
                        <h3><?= $usuario['nombre'] ?> <?= $usuario['apellido1'] ?></h3>
                        <h5>@<?= $usuario['nick'] ?><span class="email-perfil"> <span class="punto-txt-perfil">·</span> <?= $usuario['email'] ?></span></h5>
                        <div class="bottom-text">
                            <h4><i class="fa-solid fa-trophy"></i> <?= "#" .$datosRanking['posicion']  ?? "-" ?> global</h4>
                            <button class="btn-editar"><a href="perfil_edit.php?id=<?= $usuario['id'] ?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a></button>
                        </div>
                    </div>
                </div>

                <div class="perfil-stats-layout">
                    <h4>Estaísticas</h4>
                    <div class="card-stat">
                        <h3><?= $datosRanking['total_partidas'] ?? "-" ?></h3>
                        <p>Partidas</p>
                    </div>
                    <div class="card-stat">
                        <h3><?= $datosRanking['porcentaje_acierto'] . "%" ?? "-" ?></h3>
                        <p>Aciertos</p>
                    </div>
                    <div class="card-stat">
                        <h3><?= $datosRanking['mejor_puntuacion'] ?? "-" ?></h3>
                        <p class="pts-mobile"><i class="fa-solid fa-star" style="color: rgb(255, 212, 59);"></i> Max</p>
                        <p class="pts-tablet"><i class="fa-solid fa-star" style="color: rgb(255, 212, 59);"></i> Max pts.</p>
                        <p class="pts-desktop"><i class="fa-solid fa-star" style="color: rgb(255, 212, 59);"></i> Maxima puntuación</p>
                    </div>
                </div>

                <div class="hist-cont">
                    <h4>Historial</h4>
                    <table class="tabla-hist">
                        <thead>
                            <tr>
                                <th>Deporte</th>
                                <th>Dificultad</th>
                                <th>Puntuación</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $totalPaginas = tablaHistorialPartidas($conexion, $usuario['id'], $pagina); ?>
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
            }
        ?>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>