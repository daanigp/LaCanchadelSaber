<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once('../includes/funciones.php');
    require_once('../includes/conexion.php');

    $conexion = conectarDB();

    $dificultades = [];
    $sql = "SELECT DISTINCT nombre FROM dificultades ORDER BY id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
        $dificultades[] = $fila['nombre'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaCanchaDelSaber</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="../static/icon/logo-LCDS.ico" type="image/x-icon">
    <?php if(isset($css)) { ?>
        <link rel="stylesheet" href="<?= $css ?>">
    <?php } ?>
</head>
<body>
    <header>
        <img 
            src="../static/img/logo-header-LCDS.png" 
            alt="Logo de La Cancha del Saber"
            width="300px"
            height="125px"
        >
        <div class="dropdown-menu">
            <input type="checkbox" id="hamburguesa">
            <label for="hamburguesa" class="fa fa-bars" id="icono"></label>
            <nav>
                <?php
                    $nombrePagina = basename($_SERVER['REQUEST_URI']);
                ?>
                <ul class="menu">
                    <li><a href="../public/index.php" class="<?php echo ($nombrePagina == "index.php") ? "active" : ""  ?>"><i class="fa-solid fa-house"></i> Home</a></li>
                    <li class="dropdown-btn">
                        <button class="dropdown-toggle <?php echo (str_starts_with($nombrePagina, "game.php?difc=") || $nombrePagina == "scores.php") ? "active" : ""  ?>">
                            <i class="fa-solid fa-gamepad"></i> Jugar <span class="flecha">↓</span>
                        </button>
                        <ul class="dropdown-submenu">
                            <li><a href="../public/scores.php?difc=TODAS&cat=TODAS"><i class="fa-solid fa-ranking-star"></i> Puntuaciones</a></li>
                            <li class="dropdown-btn">
                                <button class="dropdown-toggle">
                                    <i class="fa-solid fa-layer-group"></i> Dificultad <span class="flecha">↓</span>
                                </button>
                                <ul class="dropdown-submenu-difc">
                                    <?php for($i = 0; $i < count($dificultades); $i++) { ?>
                                        <li>
                                            <a href="../public_user/game.php?difc=<?= $dificultades[$i] ?>">
                                                <?php for($x = 0; $x <= $i; $x++) { ?>
                                                        <i class="fa-solid fa-star"></i> 
                                                <?php 
                                                } ?>
                                                <?= $dificultades[$i] ?>
                                            </a>
                                        </li>
                                    <?php   
                                    } ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="../public/about_us.php" class="<?php echo ($nombrePagina == "about_us.php") ? "active" : ""  ?>"><i class="fa-solid fa-circle-question"></i> FAQ</a></li>
                    <?php
                        if(isset($_SESSION['id']) && isset($_SESSION['rol'])) {
                            ?>
                            <li class="dropdown-btn">
                                <button class="dropdown-toggle <?php echo ($nombrePagina == "perfil.php" || $nombrePagina == "amigos.php" || $nombrePagina == "logout.php") ? "active" : ""  ?>"">
                                    <i class="fa-solid fa-user-tie"></i> Perfil <span class="flecha">↓</span>
                                </button>
                                <ul class="dropdown-submenu">
                                    <li class="admin-panel">
                                        <a href="../public_user/perfil.php"><i class="fa-solid fa-user-tie"></i> Ver perfil</a>
                                    </li>
                                    <li class="admin-panel">
                                        <a href="../public/amigos.php"><i class="fa-solid fa-user-tie"></i> Amigos</a>
                                    </li>
                                    <li class="cerrar-sesion">
                                        <a href="../public/logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar session</a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        } else {
                            ?>  
                            <li class="login">
                                <a href="../public/login.php" class="<?php echo ($nombrePagina == "login.php") ? "active" : ""  ?>"><i class="fa-solid fa-arrow-right-to-bracket"></i> LOGIN</a>
                            </li>
                        <?php
                        }
                    ?>
                </ul>
            </nav>

            <script src="../js/utils/dropdown-menu.js"></script>
        </div>
    </header>