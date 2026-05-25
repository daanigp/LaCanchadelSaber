<?php
    session_start();

    if(isset($_SESSION['id'])) {
        header("Location: index.php");
        exit;
    }
    $error = "";

    require_once('../includes/funciones.php');
    require_once('../includes/conexion.php');
    $conexion = conectarDB();

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["iniSesion"])) {
            $userForm = $_POST["nickname"] ?? "";
            $passForm = $_POST["pass"] ?? "";
            if(loginDB($conexion, $userForm, $passForm)) {
                $_SESSION['id'] = obtenerIDByNick($conexion, 'users', $userForm);
                $_SESSION['rol'] = comprobarRol($conexion, $_SESSION['id']);
                header('Location: index.php');
                exit;
            } else {
                $error = "Debes rellenar los campos con el nombre y la contraseña correctos";
            }
        }

        if(isset($_POST["cancelar"])) {
            header('Location: index.php');
            exit;
        }
    }

    $css = "../style/styleLogin.css";
    require_once(__DIR__. "/../templates/header.php");

?>
    <main class="section-login">
        <div class="login-card">
            <h1>Iniciar sesión</h1>

            <?php
                if($error !== "") {
                    ?>
                    <p class="txt-err"><?= $error ?></p>
                    <?php
                }

                if(isset($_GET["redirigido"])) {
                    ?>
                    <p class="txt-err">Por favor, identifícate para poder acceder a esa página.</p>
                    <?php
                }
            ?>

            <form action="" class="formLogin" id="form-login" method="post">
                <div class="nick">
                    <label for="nickname">Nombre de usuario:</label>
                    <input type="text" name="nickname" id="nick-login" placeholder="Nombre de usuario ...">
                    <span class="txt-err" id="nick-txt-login"></span>
                </div>

                <div class="pwd">
                    <label for="pass">Contraseña:</label>
                    <input type="password" class="pwd-input" name="pass" id="pass-login" placeholder="Contraseña ...">

                    <button type="button" class="field-toggle" aria-label="Mostrar contraseña" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="txt-err" id="pass-txt-login"></span>
                </div>

                <input type="submit" id="btnLogin" value="Iniciar Sesión" name="iniSesion">
                <input type="submit" id="btnCancelar" value="CANCELAR" name="cancelar">
            </form>

            <p class="bottom-login">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>