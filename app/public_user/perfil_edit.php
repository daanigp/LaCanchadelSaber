<?php
    session_start();
    if(!isset($_SESSION['nick']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }  

    
    require_once('../includes/conexion.php');
    require_once('../includes/funciones.php');

    $conexion = conectarDB();
    $imagenesPermitidas = ['image/jpeg', 'image/jpg', 'image/png'];
    $titulo = "";
    $categoria = "";
    $categoriaId = 1;
    $descripcion = "";
    $tecnologiasId = [];
    $tecnologias = [];
    $imgActual = "";

    $idusuario = 0;
    if(isset($_GET['id'])) {
        $idusuario = $_GET['id'];
    }

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        if(isset($_POST['cancelar'])) {
            header("Location: perfil.php");
            exit;
        }
    }

    
    $css = "../style/styleProfileEdit.css";
    require_once(__DIR__. "/../templates/header.php");  

?>
    <main class="section-edit-profile">
        <p><?= $idusuario ?></p>
        <form action="" class="cancelar-edit" method="post">
            <button type="submit" id="btnCancelar" name="cancelar">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </button>
        </form>


        <div class="edit-profile-card">
            <h1>Editar perfil</h1>

            <form action="" class="formEdit" method="post">
                <div class="nick-edt">
                    <label for="nickname">Nickname:</label>
                    <input type="text" name="nickname" id="nick-edit" placeholder="Nombre de usuario ...">
                    <span class="txt-err"></span>
                </div>

                <div class="name-edt">
                    <label for="name">Nombre:</label>
                    <input type="text" name="name" id="name-edit">
                    <span class="txt-err"></span>
                </div>

                <div class="ape1-edt">
                    <label for="ape1">Apellido 1:</label>
                    <input type="email" name="ape1" id="ape1-edit">
                    <span class="txt-err"></span>
                </div>

                <div class="ape2-edt">
                    <label for="ape1">Apellido 2:</label>
                    <input type="email" name="ape2" id="ape2-edit">
                    <span class="txt-err"></span>
                </div>

                <div class="email-edt">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email-edit">
                    <span class="txt-err"></span>
                </div>

                <div class="pwd-edt">
                    <label for="pass">Nueva Contraseña:</label>
                    <input type="password" class="pwd-input-edit" name="pass" id="pass-edit" placeholder="Nueva Contraseña ...">

                    <button type="button" class="field-toggle" aria-label="Mostrar contraseña" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="txt-err"></span>
                </div>

                <div class="pwd-conf-edt">
                    <label for="pass-conf">Contraseña:</label>
                    <input type="password" class="pwd-input-conf-edit" name="pass-conf" id="pass-conf-edit" placeholder="Confirmar Contraseña ...">

                    <button type="button" class="field-toggle" aria-label="Mostrar contraseña" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="txt-err"></span>
                </div>

                <input type="submit" id="btnSaveChanges" value="Guardar cambios" name="saveChanges">
                
            </form>
        </div>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>