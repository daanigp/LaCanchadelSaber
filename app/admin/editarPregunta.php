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
    $titulo = "";
    $respuestaCorrecta = "";
    $respuestaA = "";
    $respuestaB = "";
    $respuestaC = "";
    $respuestaD = "";
    $categoria = "";
    $categoriaId = 1;
    $dificultad = "";
    $dificultadId = 1;
    $autor = $_SESSION['id'];
    $validadaPor = $_SESSION['id'];
    $validada = true;
    
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        if(isset($_GET['id'])) {
            $idPregunta = $_GET['id'];

            $pregunta = obtenerDatosPregunta($conexion, $idPregunta);

            if ($pregunta) {
                $titulo = $pregunta['titulo'];
                $respuestaCorrecta = $pregunta['respuesta_correcta'];
                $respuestaA = $pregunta['respuesta_A'];
                $respuestaB = $pregunta['respuesta_B'];
                $respuestaC = $pregunta['respuesta_C'];
                $respuestaD = $pregunta['respuesta_D'];
                $categoriaId = $pregunta['categoria_id'];
                $categoria = obtenerNombreById($conexion, 'categorias', $categoriaId);
                $dificultadId = $pregunta['dificultad_id'];
                $dificultad = obtenerNombreById($conexion, 'dificultades', $dificultadId);
                $autor = $pregunta['autor_id'];
                $validadaPor = $pregunta['validada_por'];
                $validada = $pregunta['validada'];
            }
        }
    }

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        if(isset($_POST['cancelar'])) {
            header('Location: panelPreguntas.php');
            exit;
        }

        if(isset($_POST['editar-pregunta'])) {
            $idPregunta = $_POST['idPregunta'] ?? null;
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

            $guardada = actualizarPregunta(
                $conexion,
                $idPregunta,
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
                $error = "No se ha podido guardar la info de la pregunta correctametne";
            } else {
                header('Location: panelPreguntas.php');
                exit;
            }
        }
    }
?>
    <main>
        <h1><i class="fa-solid fa-file-pen"></i>  EDITAR PREGUNTA</h1>
        <?php
            if($error !== "") {
                echo "<p class='txt-err'>" . $error . "</p>";
            }
        ?>
        <form action="" class="edit-pregunta" id="editar-pregunta" method="post">
            <div class="titulo-preg">
                <label for="tit">Titulo</label>
                <textarea id="tit-p" name="tit" rows="4" cols="50"><?= $titulo ?></textarea>
                <span class="txt-err" id="tit-txt-preg"></span>
            </div>

            <div class="resp-correct">
                <label for="res-correct">Respuesta Correcta</label>
                <select name="res-correct">
                    <option value="A" <?= $respuestaCorrecta === "A" ? "selected" : "" ?>>A</option>
                    <option value="B" <?= $respuestaCorrecta === "B" ? "selected" : "" ?>>B</option>
                    <option value="C" <?= $respuestaCorrecta === "C" ? "selected" : "" ?>>C</option>
                    <option value="D" <?= $respuestaCorrecta === "D" ? "selected" : "" ?>>D</option>
                </select>
            </div>

            <div class="respuestas-p-layout">
                <div class="resp-p">
                    <label for="resp-a">Respuesta A</label>
                    <input type="text" name="resp-a" id="resp-a-p" value="<?= $respuestaA ?>">
                    <span class="txt-err" id="respA-txt-preg"></span>
                </div>
                <div class="resp-p">
                    <label for="resp-b">Respuesta B</label>
                    <input type="text" name="resp-b" id="resp-b-p" value="<?= $respuestaB ?>">
                    <span class="txt-err" id="respB-txt-preg"></span>
                </div>
                <div class="resp-p">
                    <label for="resp-c">Respuesta C</label>
                    <input type="text" name="resp-c" id="resp-c-p" value="<?= $respuestaC ?>">
                    <span class="txt-err" id="respC-txt-preg"></span>
                </div>
                <div class="resp-p">
                    <label for="resp-d">Respuesta D</label>
                    <input type="text" name="resp-d" id="resp-d-p" value="<?= $respuestaD ?>">
                    <span class="txt-err" id="respD-txt-preg"></span>
                </div>
            </div>

            <div class="cate-p">
                <label for="cat">Categoria</label>
                <?php 
                    echo generarSelect($conexion, 'categorias', 'nombre', 'cat', $categoria, false);
                ?>
            </div>

            <div class="dific-p">
                <label for="difc">Dificultad</label>
                <?php 
                    echo generarSelect($conexion, 'dificultades', 'nombre', 'difc', $dificultad, false);
                ?>
            </div>

            <input type="hidden" name="idPregunta" value="<?= $idPregunta ?>">
            <input type="submit" id="btnCancelar" value="Cancelar" name="cancelar">
            <input type="submit" id="btnGuardar" value="Guardar cambios" name="editar-pregunta">
        </form>
    </main>
<?php
    include("../templates/footer_admin.php");
?>