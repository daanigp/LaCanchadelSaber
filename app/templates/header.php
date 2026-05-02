<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaCanchaDelSaber</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="../assets/icon.ico" type="image/x-icon">
    <?php if(isset($css)) { ?>
        <link rel="stylesheet" href="<?= $css ?>">
    <?php } ?>
</head>
<body>
    <header>
        <h1>La Cancha del Saber</h1>
        <div class="dropdown-menu">
            <input type="checkbox" id="hamburguesa">
            <label for="hamburguesa" class="fa fa-bars" id="icono"></label>
            <ul class="menu">
                <li><a href="../public/index.php">Index</a></li>
                <li><a href="../public/about_us.php">About Us</a></li>
                <li><a href="../public/game.php">Game</a></li>
                <li><a href="../public/login.php">Login</a></li>
            </ul>
        </div>
    </header>