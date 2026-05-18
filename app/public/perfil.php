<?php
    $css = "../style/stylePerfil.css";
    require_once(__DIR__. "/../templates/header.php");
 
    if(!isset($_SESSION['nick']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }
    
    $user = $_SESSION['nick'];

    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');
    $conexion = conectarDB();
?>
    <main>
        <?php 
            $usuario = obtenerDatosUsuario($conexion, $user);
            $datosRanking = obtenerRankingUsuarioById($conexion, $usuario['id']);

            if($usuario === null) {
                echo "<p>El usuario " . $user . " no ha sido encontrado</p>";
                echo "<p>Lo sentimos. Seguiremos trabajando para corregir los fallos</p>";
            } else { ?>
                <div class="perfil">
                    <div class="logo-perfil">
                        <img src="../static/img/nutria-1.jpg" alt="Imagen de perfil" width="150px" height="150px">
                    </div>
                    <div class="texto-perfil">
                        <h3><?= $usuario['nombre'] ?> <?= $usuario['apellido1'] ?></h3>
                        <h5>@<?= $user ?><span class="email-perfil"> <span class="punto-txt-perfil">·</span> <?= $usuario['email'] ?></span></h5>
                        <div class="bottom-text">
                            <h4><i class="fa-solid fa-trophy"></i> <?= "#" .$datosRanking['posicion']  ?? "-" ?> global</h4>
                            <button><i class="fa-solid fa-pen-to-square"></i> Editar</button>
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
                    <table>
                        <thead>
                            <tr>
                                <td>Deporte</td>
                                <td>Dificultad</td>
                                <td>Categoría</td>
                                <td>Puntuación</td>
                                <td>Fecha</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php tablaHistorialPartidas($conexion, $usuario['id']); ?>
                        </tbody>
                    </table>
                </div>
        <?php
            }
        ?>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>