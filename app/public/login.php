<?php
    $css = "../style/styleLogin.css";
    require_once(__DIR__. "/../templates/header.php");
?>
    <main>
        <fieldset>
            <h1>Login</h1>
            <form class="formLogin">
                <div class="nick">
                    <label for="nickname">Nombre de usuario:</label>
                    <input type="text" name="nickname" id="nickname">
                </div>

                <div class="pwd">
                    <label for="pass">Contraseña:</label>
                    <input type="password" name="pass" id="pass">
                </div>
            </form>
        </fieldset>
    </main>
<?php
    require_once(__DIR__. "/../templates/footer.php");
?>