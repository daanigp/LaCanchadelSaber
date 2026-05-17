<?php
    $css = "../style/styleGame.css";
    require_once(__DIR__. "/../templates/header.php");
 
    if(!isset($_SESSION['nick']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }
    
    $user = $_SESSION['nick'];

    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');
    $conexion = conectarDB();
    /*
    SELECT posicion, nick, mejor_puntuacion
    FROM (
        SELECT 
            u.id,
            u.nick,
            u.avatar_url,
            MAX(p.puntuacion) AS mejor_puntuacion,
            RANK() OVER (ORDER BY MAX(p.puntuacion) DESC) AS posicion
        FROM users u
        JOIN partidas p ON p.id_user = u.id
        //WHERE u.activo = TRUE
        GROUP BY u.id
    ) AS ranking
    WHERE id = 1;
    */
?>
    <main>
        <?php 
            $usuario = obtenerDatosUsuario($conexion, $user);
            
            if($usuario === null) {
                echo "<p>El usuario " . $user . " no ha sido encontrado</p>";
                echo "<p>Lo sentimos. Seguiremos trabajando para corregir los fallos</p>";
            } else { ?>
                <div class="perfil">
                    <div class="logo-perfil">
                        <img src="../static/img/nutria-1.jpg" alt="Imagen de perfil">
                    </div>
                    <div class="texto-perfil">
                        <h3><?= $usuario['nombre'] ?></h3>
                        <h5>@<?= $user ?><span class="email-perfil"> · <?= $usuario['email'] ?></span></h5>
                        <h4><?= obtenerRanking(); ?></h4>
                    </div>
                </div>

                <div class="perfil-stats">
                    <div class="card-stat">
                        <h3><?= $numPartidasJugadas ?></h3>
                        <p>Partidas</p>
                    </div>
                    <div class="card-stat">
                        <h3><?= $porcentAciertos ?></h3>
                        <p>Aciertos</p>
                    </div>
                    <div class="card-stat">
                        <h3><?= $mxPts ?></h3>
                        <p class="pts-mobile"><i class="fa-solid fa-star" style="color: rgb(255, 212, 59);"></i> Max</p>
                        <p class="pts-tablet"><i class="fa-solid fa-star" style="color: rgb(255, 212, 59);"></i> Max pts.</p>
                        <p class="pts-desktop"><i class="fa-solid fa-star" style="color: rgb(255, 212, 59);"></i> Maxima puntuación</p>
                    </div>
                </div>

                <div class="hist-cont">
                    <?= tablaHistorialPartidas(); ?>
                </div>
        <?php
            }
        ?>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>