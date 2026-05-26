<!DOCTYPE html>
<html lang="es">
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
        
        <nav>
            <?php
                $nombrePagina = basename($_SERVER['REQUEST_URI']);
            ?>
            <ul class="menu-admin">
                <li>
                    <a href="../public/index.php" class="<?php echo ($nombrePagina == "index.php") ? "active" : ""  ?>"><i class="fa-solid fa-house"></i> Inicio</a>
                </li>
            </ul>
        </nav>    
    </header>