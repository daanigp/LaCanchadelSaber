<?php
    $css = "../style/styleAboutUs.css";
    require_once(__DIR__. "/../templates/header.php");
?>
    <main class="main-about_us">
        <h1 class="tit-about_us">Sobre nosotros</h1>

        <div class="faq">
            <div class="top-card">
                <div class="logo">
                    <img src="../static/img/logo-LCDS.png" alt="Logo WEB" width="200px" height="200px">
                </div>
                <div class="top-texto">
                    <h5>TFG · 2026</h5>
                    <h3>La Cancha Del Saber</h3>
                    <p>Quiz de fútbol para poner a prueba tus conocimientos 🤓</p>
                </div>
            </div>

            <div class="layout-minicard">
                <div class="mini-card">
                    <span>3</span>
                    <p> dificultades</p>
                </div>

                <div class="mini-card">
                    <span>+100</span>
                    <p> preguntas</p>
                </div>

                <div class="mini-card">
                    <span>1</span>
                    <p> desarrollador</p>
                </div>
            </div>

            <hr>

            <div class="paralelas">
                <div class="card-faq">
                    <h4> <i class="fa-solid fa-circle-info"></i> ¿Que es este proyecto?</h4>
                    <p>Se trata de una aplicación web en la cual los aficionados al fútbol, donde van a poder demostrar todos sus conocimientos acerca de jugadores, equipos e incluso historia del fútbol.</p>
                </div>

                <hr>

                <div class="card-faq">
                    <h4><i class="fa-solid fa-lightbulb"></i> ¿Porque nació?(Motivación del proyecto)</h4>
                    <p>Este proyectó nació por la necesidad de realizar el trabajo de Fin de Grado del ciclo superior Desarrollo de Aplicaciones Web.</p>
                    <p>Aprovechando el motivo del proyecto, quise combinar mi pasión por el fútbol con mis conocimientos de desarrollo web.</p>
                </div>
            </div>

            <hr>

            <div class="card-faq">
                <h4><i class="fa-solid fa-code"></i> Tecnologías usadas</h4>
                <p>Las tecnologías que he utilizado son:</p>
                <ul class="tecnologias">
                    <li class="tech"><i class="fa-brands fa-html5"></i> HTML5</li>
                    <li class="tech"><i class="fa-brands fa-css"></i> CSS3</li>
                    <li class="tech"><i class="fa-brands fa-js"></i> JavaScript</li>
                    <li class="tech"><i class="fa-brands fa-php"></i> PhP</li>
                    <li class="tech"><i class="fa-solid fa-database"></i> MySQL</li>
                </ul>
            </div>

            <hr>

            <div class="card-faq improvements">
                <h4><i class="fa-solid fa-robot"></i> Futuras mejoras para la web 👀🔜</h4>
                <p>Algunas de las posibles mejoras que he pensado para la web:</p>
                <ul>
                    <li>Poner distintos deportes</li>
                </ul>

                <!--
                FORMULARIO DE NUEVAS SUGERENCIAS
                <form action="" class="form-newsugest">
                    <legend>Si tienes alguna sugerencia para la web, no dudes en hacernosla llegar 😉.</legend>
                    
                    <label for="email-usr">Email:</label>
                    <input type="text" name="email-usr" id="email-usr">

                    <label for="question">Pregunta:</label>
                    <input type="text" name="question" id="question">

                    <div class="answers">
                        <div class="answ">
                            <label for="answ-1">Respuesta A:</label>
                            <input type="text" name="answ-1" id="answ-1">
                        </div>
                        <div class="answ">
                            <label for="answ-2">Respuesta B:</label>
                            <input type="text" name="answ-2" id="answ-2">
                        </div>
                        <div class="answ">
                            <label for="answ-3">Respuesta C:</label>
                            <input type="text" name="answ-3" id="answ-3">
                        </div>
                        <div class="answ">
                            <label for="answ-4">Respuesta D:</label>
                            <input type="text" name="answ-4" id="answ-4">
                        </div>
                    </div>

                    <label for="comments">Comentarios:</label>
                    <textarea name="comments" id="comments" placeholder="Comentarios (opcional) ..." rows="5" cols="50"></textarea>

                </form>
                -->
            </div>

            <hr>

            <div class="card-faq">
                <h4><i class="fa-solid fa-user"></i> Autor</h4>
                <div class="author">
                    <img src="../static/img/nutria-1.jpg" alt="Imágen de una nutria" width="75px" height="75px">
                    <div class="text-auth">
                        <p>Daniel García Pascual</p>
                        <span>Estudiante de desarrollo de aplicaciobes web.</span>
                    </div>
                </div>
                
                <hr>
                
                <p>Soy un apasionado al fútbol y a la tecnología. Este proyecto nace de querer combinar ambas pasiones y crear algo funcional, entretenido y en el cual demuestre lo aprendido durante el grado de dearrollo web.</p>
            </div>

        </div>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>