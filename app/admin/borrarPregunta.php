<?php
    session_start();
    if(!isset($_SESSION['id']) && !isset($_SESSION['rol'])) {
        header("Location: ../public/login.php?redirigido=true");
        exit;
    }

    if(isset($_SESSION['rol']) && $_SESSION['rol'] !== 'ADMIN') {
        header("Location: ../public/index.php?redirigidoAdmin=true");
        exit;
    }
    
    $css = "../style/styleEditarPreguntas.css";
    include('../templates/header_admin.php');
    require_once('../includes/funciones.php');
    require_once('../includes/conexion.php');
    $conexion = conectarDB();

    $error = "";

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        if(isset($_GET['id'])) {
            $idPreg = $_GET['id'];
            $titulo = obtenerNombreById($conexion, 'preguntas', $idPreg, 'titulo');
        }
    }

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        if(isset($_POST['cancelar'])) {
            header('Location: panelPreguntas.php');
            exit;
        }

        if(isset($_POST['borrar'])) {
            $idPregunta = $_POST['idPregunta'];

            $borrado = borrarPregunta($conexion, $idPregunta);

            if($borrado) {
                header('Location: panelPreguntas.php?borrado=true');
                exit;
            } else {
                $error = "Ha ocurrido un error mientras intentábamos borrar la pregunta, lo sentimos.";
            }
        }
    }
?>

<main>
    <div class="panel-borrarPregunta">
        <h1><i class="fa-regular fa-trash-can"></i> BORRAR PREGUNTA</h1>
        <div class="mensajeBorrado">
            <p>¿Estás seguro de que deseas borrar la pregunta <span><?= $titulo ?></span> (ID: <?= $idPreg ?>)?</p>
        </div>
        <form action="" method="post">
            <div class="botones">
                <input type="hidden" name="idPregunta" value="<?= $idPreg ?>">
                <input type="submit" id="btnCancelar" value="CANCELAR" name="cancelar">
                <input type="submit" id="btnBorrar" value="BORRAR" name="borrar">
            </div>
        </form>
        
    </div>
</main>

<?php
    include("../templates/footer_admin.php");
?>