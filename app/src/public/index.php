<?php
    $css = "../style/styleIndex.css";
    require_once(__DIR__. "/../templates/header.php");
?>

    <main>
        <div class="top-index">
            <div class="header-index">
                <h1>Index</h1>

                <img src="../static/img/logo-LCDS.png" alt="Img logo">
            </div>

            <p class="txt-index-top">Se trata de una aplicación web en la cual los aficionados al fútbol, donde van a poder demostrar todos sus conocimientos acerca de jugadores, equipos e incluso historia del fútbol.</p>
        </div>

        <div class="slider-layaout">
            <h4>Categorias:</h4>
            <div class="slider-wrap">
                <div class="slider" id="slider">
                    <div class="slide">
                        <img src="../static/img/slider/historia.jpg" alt="Historia del fútbol">
                        <div class="slide-caption">
                            <span class="slide-cat">Historia</span>
                            <p>Demuestra que conoces los grandes momentos del fútbol mundial.</p>
                        </div>
                    </div>

                    <div class="slide">
                        <img src="../static/img/slider/selecciones.jpg" alt="Selecciones">
                        <div class="slide-caption">
                            <span class="slide-cat">Selecciones</span>
                            <p>¿Cuánto sabes sobre las selecciones nacionales y sus estrellas?</p>
                        </div>
                    </div>

                    <div class="slide">
                        <img src="../static/img/slider/ligueras.jpg" alt="Preguntas ligueras">
                        <div class="slide-caption">
                            <span class="slide-cat">Ligueras</span>
                            <p>Preguntas sobre las ligas más importantes del mundo.</p>
                        </div>
                    </div>
                </div>

                <button class="slider-btn slider-btn--prev" id="sliderPrev" aria-label="Anterior">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="slider-btn slider-btn--next" id="sliderNext" aria-label="Siguiente">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <div class="slider-dots" id="sliderDots"></div>
            </div>
        </div>

        <div class="tabla-top5">
            <table class="tabla-ranking">
                <legend>Ranking 5 mejores</legend>

                <thead>
                    <tr>
                        <th>PUNTOS</th>
                        <th>NOMBRE</th>
                        <th>DIFICULTAD</th>
                        <th>FECHA</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        generarTablaRanking5($conexion, 'partidas');
                    ?>
                </tbody>
            </table>
        </div>
        <div class="reglas">
            <h4>Reglas del juego</h4>
            <div class="txt-reglas">
                <span>Dificultades:</span>
                    <p>El juego está dividido en 3 dificultades:</p>
                    <ul>
                        <li>Fácil → El jugador contará con <b>3 vidas</b> pero con una dificultad fácil en las preguntas.</li>
                        <li>Medio → El jugador contará con <b>3 vidas</b> pero con una dificultad media y fácil en las preguntas.</li>
                        <li>Difícil → El jugador contará con <b>3 vidas</b> pero con una dificultad fácil en las preguntas.</li>
                    </ul>
                <span>Puntuación:</span>
                    <p>El método de puntuaciones es el siguiente:  </p> 
                    <ul>
                        <li>Cada jugador sumará <b>30pts</b> a su puntuación total de la partida por cada pregunta acertada en dificultad difícil.</li>
                        <li>Cada jugador sumará <b>20pts</b> a su puntuación total de la partida por cada pregunta acertada en dificultad media.</li>
                        <li>Cada jugador sumará <b>10pts</b> a su puntuación total de la partida por cada pregunta acertada en dificultad fácil.</li>
                    </ul>
            </div>
        </div>
    </main>
<script src="../js/utils/slider.js"></script>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>