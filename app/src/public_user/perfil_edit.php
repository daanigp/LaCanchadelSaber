<?php
    session_start();
    if(!isset($_SESSION['id']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }  

    
    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');

    $conexion = conectarDB();
    $imagenesPermitidas = ['image/jpeg', 'image/jpg', 'image/png'];
    $nick = "";
    $nombre = "";
    $apellido1 = "";
    $apellido2 = "";
    $pais = "";
    $email = "";
    $contraseña = "";
    $imgActual = "nutria-2.jpg";
    $mensaje = "";
    $tipo_popup = "";
    $pass = "";

    $idusuario = 0;
    if(isset($_GET['id'])) {
        $idusuario = $_GET['id'];

        // Obtenemos los datos del usuario de la bbdd
        $usuario = obtenerDatosUsuarioById($conexion, $idusuario);
        $nick = $usuario['nick'];
        $pass = $usuario['password'];
        $nombre = $usuario['nombre'];
        $apellido1 = $usuario['apellido1'];
        $apellido2 = $usuario['apellido2'];
        $pais = $usuario['nacionalidad'];
        $email = $usuario['email'];
        $imgActual = $usuario['avatar_url'] ?? "nutria-2.jpg";
    }

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        if(isset($_POST['cancelar'])) {
            if($mensaje !== ""){
                header("Location: perfil.php");
                exit;
            } else {
                header("Location: perfil.php?updated=true&idUser=" . urlencode($idusuario));
                exit;
            }
        }

        if(isset($_POST['userID-img'])) {
            $errores = [];
            $userID = $_POST['userID-img'];
            $imagenAntigua = $_POST['nombreImagenAntigua'];
            $imagenNueva = "";
            $existeImagen = false;

            if(!empty($_FILES['img']['name'])) {
                if(!in_array($_FILES['img']['type'], $imagenesPermitidas)) {
                    $errores[] = "La imagen seleccionada no tiene el tipo necesario (jpeg, png, jpg).";
                    $mensaje = "<i class='fa-solid fa-person-circle-exclamation'></i> La imagen seleccionada no tiene el tipo necesario (jpeg, png, jpg).";
                    $tipo_popup = "err";
                } else {
                    $imagenNueva = $_FILES['img']['name'];
                    $existeImagen = true;
                }
            }

            if(empty($errores)) {
                if($existeImagen) {
                    //Cambiar el nombre de la imagen a un nombre único:
                    $nombreUnicoIMG = date("Y-m-d"). "_" . uniqid() . "_" . $imagenNueva;
                    if(is_uploaded_file($_FILES['img']['tmp_name'])) {
                        if(!move_uploaded_file($_FILES['img']['tmp_name'], "../static/img/profile/$nombreUnicoIMG")) {
                            $errores[]= "Error al mover el archivo al directorio de destino";
                            $mensaje = "<i class='fa-solid fa-person-circle-exclamation'></i> Error al mover el archivo al directorio de destino";
                            $tipo_popup = "err";
                        }
                    } else {
                        $errores[] = "No se ha seleccionado ningún archivo, o se ha producido un error.";
                        $mensaje = "<i class='fa-solid fa-person-circle-exclamation'></i> No se ha seleccionado ningún archivo, o se ha producido un error.";
                        $tipo_popup = "err";
                    }
                } else {
                    $nombreUnicoIMG = '';
                }

                if(empty($errores)) {
                    //ELIMINAMOS LA IMAGEN DEL SERVIDOR
                    //Borrar la ruta de la imagen
                    if($imagenAntigua && file_exists("../static/img/profile/".$imagenAntigua)) {
                        unlink("../static/img/profile/".$imagenAntigua);
                    }

                    $updateIMG = updateUserImage($conexion, $userID, $nombreUnicoIMG);

                    //Editamos el usuario y añadimos la nueva img:
                    if ($updateIMG) {
                        $imgActual = $nombreUnicoIMG;
                        $mensaje = "<i class='fa-solid fa-person-circle-check'></i> Se ha editado la imagen correctamente.";
                        $tipo_popup = "success";
                    } else {
                        $mensaje = "<i class='fa-solid fa-person-circle-exclamation'></i> Ha ocurrido un error inesperado en el guardado de la imagen, lo sentimos :(.";
                        $tipo_popup = "err";
                    }
                }
            }
        }

        if(isset($_POST['saveChanges'])) {
            $usuarioId = $_POST['userID'];
            $nickNuevo = $_POST['nick-edit'] ?? "";
            $nombreNuevo = $_POST['name-edit'] ?? "";
            $ape1Nuevo = $_POST['ape1-edit'] ?? "";
            $ape2Nuevo = $_POST['ape2-edit'] ?? "";
            $paisNuevo = $_POST['pais-edit'] ?? "";
            $emailNuevo = $_POST['email-edit'] ?? "";
            $pwdNueva = $_POST['pass-edit'] ?? "";

            if($pwdNueva === "") {
                $pwdNueva = $pass;
            }

            $update = updateUserInfo($conexion, 
                        $usuarioId,
                        $nickNuevo, 
                        $nombreNuevo, 
                        $ape1Nuevo, 
                        $ape2Nuevo,
                        $paisNuevo,
                        $emailNuevo,
                        $pwdNueva);

            if ($update) {
                $nick = $nickNuevo;
                $nombre = $nombreNuevo;
                $apellido1 = $ape1Nuevo;
                $apellido2 = $ape2Nuevo;
                $pais = $paisNuevo;
                $email = $emailNuevo;
                $mensaje = "<i class='fa-solid fa-person-circle-check'></i> Se ha editado el perfil correctamente.";
                $tipo_popup = "success";
            } else {
                $mensaje = "<i class='fa-solid fa-person-circle-check'></i> Ha ocurrido un error inesperado en el guardado del usuario, lo sentimos :(.";
                $tipo_popup = "err";
            }
        }
    }

    
    $css = "../style/styleProfileEdit.css";
    require_once(__DIR__. "/../templates/header.php");  

?>
    <main class="section-edit-profile">
        <form action="" class="cancelar-edit" method="post">
            <button type="submit" id="btnCancelar" name="cancelar">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </button>
        </form>

        <div id="overlayPopUp" class="overlay-popup">
            <div class="popup-cont">
                <button class="cerrar-popup" id="btnCerrarPopUp">&times;</button>
                <p id="mensajePopUp"></p>
            </div>
        </div>

        <div class="edit-profile-card">
            <div class="header-edit-profile">
                <div class="edit-img">
                    <img src="../static/img/profile/<?= $imgActual ?>" alt="Imagen de perfil del usuario">
                    <form action="" enctype="multipart/form-data" method="post" class="form-Img" id="form-img-edit">
                        <input type="hidden" name="nombreImagenAntigua" value="<?= $imgActual ?>">
                        <input type="hidden" name="userID-img" value="<?= $idusuario ?>">
                        <input type="file" name="img" id="img-edit" style="opacity:0; position:absolute; width:0; height:0;">
                        <button name="editar-img" type="button" onclick="document.getElementById('img-edit').click()">
                            <i class="fa-solid fa-camera"></i> Editar
                        </button>
                    </form>
                </div>
                <h3>Editar perfil</h3>
            </div>

            <form action="" class="formEdit" method="post" id="form-edit">
                <div class="nick-edt">
                    <label for="nick-edit">Nickname:</label>
                    <input type="text" name="nick-edit" id="nick-edit" placeholder="Nickname ..." value="<?= $nick ?>">
                    <span class="txt-err" id="nick-txt-err"></span>
                </div>

                <div class="name-edt">
                    <label for="name-edit">Nombre:</label>
                    <input type="text" name="name-edit" id="name-edit" placeholder="Nombre de usuario ..." value="<?= $nombre ?>">
                    <span class="txt-err" id="nombre-txt-err"></span>
                </div>

                <div class="ape1-edt">
                    <label for="ape1-edit">Apellido 1:</label>
                    <input type="text" name="ape1-edit" id="ape1-edit" placeholder="Apellido 1 ..." value="<?= $apellido1 ?>">
                    <span class="txt-err" id="ape1-txt-err"></span>
                </div>

                <div class="ape2-edt">
                    <label for="ape2-edit">Apellido 2:</label>
                    <input type="text" name="ape2-edit" id="ape2-edit" placeholder="Apellido 2 ..." value="<?= $apellido2 ?>">
                    <span class="txt-err" id="ape2-txt-err"></span>
                </div>

                <div class="pais-edt">
                    <label for="pais-edit">Nacionalidad:</label>
                    <input type="text" name="pais-edit" id="pais-edit" placeholder="Nacionalidad ..." value="<?= $pais ?>">
                    <span class="txt-err" id="pais-txt-err"></span>
                </div>

                <div class="email-edt">
                    <label for="email-edit">Email:</label>
                    <input type="email" name="email-edit" id="email-edit" placeholder="tuemail@gmail.com" value="<?= $email ?>">
                    <span class="txt-err" id="email-txt-err"></span>
                </div>

                <div class="pwd-edt">
                    <label for="pass-edit">Nueva Contraseña:</label>
                    <input type="password" class="pwd-input-edit" name="pass-edit" id="pass-edit" placeholder="Nueva Contraseña ...">

                    <button type="button" class="field-toggle" aria-label="Mostrar contraseña" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="txt-err" id="pass-txt-err"></span>
                </div>

                <div class="pwd-conf-edt">
                    <label for="pass-conf-edit">Confirmar Contraseña:</label>
                    <input type="password" class="pwd-input-conf-edit" name="pass-conf-edit" id="pass-conf-edit" placeholder="Confirmar Contraseña ...">

                    <button type="button" class="field-toggle" aria-label="Mostrar contraseña" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="txt-err" id="pass-conf-txt-err"></span>
                </div>

                <input type="hidden" name="userID" value="<?= $idusuario ?>">
                <input type="submit" id="btnSaveChanges" value="Guardar cambios" name="saveChanges">
                
            </form>
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