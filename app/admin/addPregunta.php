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
    
    $css = "../style/styleAddPreguntas.css";
    include('../templates/header_admin.php');
    require_once('../includes/funciones.php');
    require_once('../includes/conexion.php');
    $conexion = conectarDB();

    $error = "";

    $autor = $_SESSION['id'];
    $validadaPor = $_SESSION['id'];
    $validada = true;

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        if(isset($_POST['cancelar'])) {
            header('Location: panelPreguntas.php');
            exit;
        }

        if(isset($_POST['editar-pregunta'])) {
            $titulo = $_POST['tit'];
            $respuestaCorrecta = $_POST['res-correct'];
            $respuestaA = $_POST['resp-a'];
            $respuestaB = $_POST['resp-b'];
            $respuestaC = $_POST['resp-c'];
            $respuestaD = $_POST['resp-d'];
            $categoria = $_POST['cat'];
            $dificultad = $_POST['difc'];
            $autor = $_SESSION['id'];
            $validadaPor = $_SESSION['id'];
            $validada = true;

            $guardada = guardarNuevaPregunta(
                $conexion,
                $titulo,
                $respuestaCorrecta,
                $respuestaA,
                $respuestaB,
                $respuestaC,
                $respuestaD,
                $categoria,
                $dificultad,
                $autor,
                $validadaPor,
                $validada
            );

            if(!$guardada) {
                $error = "No se ha podido crear la pregunta correctamente.";
            } else {
                header('Location: panelPreguntas.php');
                exit;
            }
        }
    }
?>
    <main>
        <h1><i class="fa-solid fa-file-circle-plus"></i> CREAR PREGUNTA</h1>
        <?php
            if($error !== "") {
                echo "<p class='txt-err'>" . $error . "</p>";
            }
        ?>
        <form action="" class="edit-pregunta" method="post">
            <div class="titulo-preg">
                <label for="tit">Titulo</label>
                <textarea id="tit-p" name="tit" rows="4" cols="50" placeholder="Introduce el titulo de la pregunta ..."></textarea>
                <span class="txt-err"></span>
            </div>

            <div class="resp-correct">
                <label for="res-correct">Respuesta Correcta</label>
                <select name="res-correct">
                    <option value="A" selected>A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>

            <div class="respuestas-p-layout">
                <div class="resp-p">
                    <label for="resp-a">Respuesta A</label>
                    <input type="text" name="resp-a" id="resp-a-p" placeholder="Respuesta A ...">
                    <span class="txt-err"></span>
                </div>
                <div class="resp-p">
                    <label for="resp-b">Respuesta B</label>
                    <input type="text" name="resp-b" id="resp-b-p" placeholder="Respuesta B ...">
                    <span class="txt-err"></span>
                </div>
                <div class="resp-p">
                    <label for="resp-c">Respuesta C</label>
                    <input type="text" name="resp-c" id="resp-c-p" placeholder="Respuesta C ...">
                    <span class="txt-err"></span>
                </div>
                <div class="resp-p">
                    <label for="resp-d">Respuesta D</label>
                    <input type="text" name="resp-d" id="resp-d-p" placeholder="Respuesta D ...">
                    <span class="txt-err"></span>
                </div>
            </div>

            <div class="cate-p">
                <label for="cat">Categoria</label>
                <?php 
                    echo generarSelect($conexion, 'categorias', 'nombre', 'cat', '', false);
                ?>
            </div>

            <div class="dific-p">
                <label for="difc">Categoria</label>
                <?php 
                    echo generarSelect($conexion, 'dificultades', 'nombre', 'difc', '', false);
                ?>
            </div>

            <input type="submit" id="btnCancelar" value="Cancelar" name="cancelar">
            <input type="submit" id="btnGuardar" value="Guardar cambios" name="editar-pregunta">
        </form>
    </main>
<?php
    include("../templates/footer_admin.php");
?>