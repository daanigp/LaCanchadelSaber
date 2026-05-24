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
    
    include('../templates/header_admin.php');
    require_once('../includes/funciones.php');
    require_once('../includes/conexion.php');
    $conexion = conectarDB();

    $titulo = "";
    $respuestaCorrecta = "";
    $respeustaA = "";
    $respeustaB = "";
    $respeustaC = "";
    $respeustaD = "";
    $autor = $_SESSION['id'];
    $validadaPor = $_SESSION['id'];
    $validada = true;

    $categoria = "";
    $categoriaId = 1;
    $dificultad = "";
    $dificultadId = 1;
    
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        if(isset($_GET['id'])) {
            $idPregunta = $_GET['id'];

            $pregunta = obtenerDatosPregunta($conexion, $idPregunta);
        }
    }

?>
    AAAA
<?php
    include("../templates/footer_admin.php");
?>