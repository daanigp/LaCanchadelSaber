<?php
    session_start();
    if(isset($_SESSION['id'])) {
        header("Location: ../public/index.php");
        exit;
    }  

    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');

    $conexion = conectarDB();
    $imagenesPermitidas = ['image/jpeg', 'image/jpg', 'image/png'];
    $imgActual = "nutria-2.jpg";
    $nacionalidad = "España";

    $errores = "";
    $guardado = "";

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        if(isset($_POST['cancelar'])) {
            header("Location: login.php");
            exit;
        }

        if(isset($_POST['saveChanges'])) {
            $errores = [];
            $nick = $_POST['nick-register'] ?? "";
            $nombre = $_POST['name-register'] ?? "";
            $ape1 = $_POST['ape1-register'] ?? "";
            $ape2 = $_POST['ape2-register'] ?? "";
            $pais = $_POST['pais-register'] ?? "";
            $email = $_POST['email-register'] ?? "";
            $pwd = $_POST['pass-register'] ?? "1111";
            $imagenNueva = "";
            $existeImagen = false;

            if(!empty($_FILES['img']['name'])) {
                if(!in_array($_FILES['img']['type'], $imagenesPermitidas)) {
                    $errores = "La imagen seleccionada no tiene el tipo necesario (jpeg, png, jpg).";
                }
                $imagenNueva = $_FILES['img']['name'];
                $existeImagen = true;
            }

            if(empty($errores)) {
                if($existeImagen) {
                    //Cambiar el nombre de la imagen a un nombre único:
                    $nombreUnicoIMG = date("Y-m-d"). "_" . uniqid() . "_" . $imagenNueva;
                    if(is_uploaded_file($_FILES['img']['tmp_name'])) {
                        if(!move_uploaded_file($_FILES['img']['tmp_name'], "../static/img/profile/$nombreUnicoIMG")) {
                            $errores[]= "Error al mover el archivo al directorio de destino";
                        }
                    } else {
                        $errores = "No se ha seleccionado ningún archivo, o se ha producido un error.";
                    }
                } else {
                    $nombreUnicoIMG = '';
                }

                if(empty($errores)) {
                    
                    $update = createUser($conexion,
                        $nick, 
                        $nombre, 
                        $ape1, 
                        $ape2,
                        $email,
                        $pwd,
                        $pais,
                        $nombreUnicoIMG);

                    //Editamos el usuario y añadimos la nueva img:
                    if ($update) {
                        $guardado = "SE HA GUARDADO OK";
                    } else {
                        $errores = "Nick o email existentes.";
                    }
                }
            }
        }
    }

    $css = "../style/styleRegister.css";
    require_once(__DIR__. "/../templates/header.php");  

?>
    <main class="section-register-profile">
        <form action="" class="cancelar-register" method="post">
            <button type="submit" id="btnCancelar" name="cancelar">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </button>
        </form>

       <?php
        if($guardado !== "") {
            echo "<p style='color: green;'>$guardado</p>";
        }

        if(!empty($errores)) {
            echo "<p style='color: red;'>$errores</p>";
        }
        ?>

        <div class="register-profile-card">
            <div class="header-register-profile">
                <h3>Crear usuario</h3>
            </div>

            <form action="" enctype="multipart/form-data" class="formRegister" id="form-register" method="post">
                <div class="img-rgstr">
                    <img src="../static/img/profile/<?= $imgActual ?>" alt="Imagen de perfil del usuario por defecto">
                    <input type="file" name="img" id="img">
                </div>

                <div class="nick-rgstr">
                    <label for="nick-register">Nickname:</label>
                    <input type="text" name="nick-register" id="nick-register" placeholder="Nickname ..." require>
                    <span class="txt-err" id="nick-txt-err"></span>
                </div>

                <div class="name-rgstr">
                    <label for="name-register">Nombre:</label>
                    <input type="text" name="name-register" id="name-register" placeholder="Nombre de usuario ..." const packageName = require('packageName');>
                    <span class="txt-err" id="nombre-txt-err"></span>
                </div>

                <div class="ape1-rgstr">
                    <label for="ape1-register">Apellido 1:</label>
                    <input type="text" name="ape1-register" id="ape1-register" placeholder="Apellido 1 ...">
                    <span class="txt-err" id="ape1-txt-err"></span>
                </div>

                <div class="ape2-rgstr">
                    <label for="ape2-register">Apellido 2:</label>
                    <input type="text" name="ape2-register" id="ape2-register" placeholder="Apellido 2 ...">
                    <span class="txt-err" id="ape2-txt-err"></span>
                </div>

                <div class="pais-rgstr">
                    <label for="pais-register">Nacionalidad:</label>
                    <input type="text" name="pais-register" id="pais-register" placeholder="Nacionalidad ...">
                    <span class="txt-err" id="pais-txt-err"></span>
                </div>

                <div class="email-rgstr">
                    <label for="email-register">Email:</label>
                    <input type="email" name="email-register" id="email-register" placeholder="tuemail@gmail.com" require>
                    <span class="txt-err" id="email-txt-err"></span>
                </div>

                <div class="pwd-rgstr">
                    <label for="pass-register">Contraseña:</label>
                    <input type="password" class="pwd-input-register" name="pass-register" id="pass-register" placeholder="Nueva Contraseña ..." require>

                    <button type="button" class="field-toggle" aria-label="Mostrar contraseña" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="txt-err" id="pass-txt-err"></span>
                </div>

                <div class="pwd-conf-rgstr">
                    <label for="pass-conf-register">Confirmar Contraseña:</label>
                    <input type="password" class="pwd-input-conf-register" name="pass-conf-register" id="pass-conf-register" placeholder="Confirmar Contraseña ..." require>

                    <button type="button" class="field-toggle" aria-label="Mostrar contraseña" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="txt-err" id="pass-conf-txt-err"></span>
                </div>

                <input type="submit" id="btnSaveChanges" value="Guardar cambios" name="saveChanges">
                
            </form>
        </div>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>