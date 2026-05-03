<?php
    $css = "../style/styleLogin.css";
    require_once(__DIR__. "/../templates/header.php");
?>
    <main class="section-login">
        <div class="login-card">
            <h1>Iniciar sesión</h1>

            <form class="formLogin">
                <div class="nick">
                    <label for="nickname">Nombre de usuario:</label>
                    <input type="text" name="nickname" id="nickname" placeholder="Nombre de usuario ...">
                    <span class="txt-err"></span>
                </div>

                <div class="pwd">
                    <label for="pass">Contraseña:</label>
                    <input type="password" class="pwd-input" name="pass" id="pass" placeholder="Contraseña ...">

                    <button type="button" class="field-toggle" aria-label="Mostrar contraseña" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <span class="txt-err"></span>
                </div>

                <button type="submit" id="btnLogin">Iniciar Sesión</button>
            </form>
        </div>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>