$(function () {

    // Muestra un mensaje de error en el elemento con el id dado
    function showTxtErr(id, msg) {
        $('#' + id).text(msg).fadeIn(150);
    }
    // Oculta y limpia el mensaje de error del elemento con el id dado
    function clearTxtErr(id) {
        $('#' + id).text('').hide();
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    const texto = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;


    /**
     * Comprueba si los datos del formulario de registro de usuario son correctos
     * @returns bool
     */
    function validarDatosUsuario() {
        let valido = true;
        const nick = $('#nick-register').val().trim();
        const nombre = $('#name-register').val().trim();
        const ape1 = $('#ape1-register').val().trim();
        const ape2 = $('#ape2-register').val().trim();
        const pais = $('#pais-register').val().trim();
        const email = $('#email-register').val().trim();
        const pass = $('#pass-register').val().trim();
        const passConf = $('#pass-conf-register').val().trim();

        if(nick.length < 2) {
            showTxtErr('nick-txt-err', 'El nick tiene que tener al menos 2 caracteres.');
            valido = false;
        } else {
            clearTxtErr('nick-txt-err');
        }

        if(nombre.length < 2) {
            showTxtErr('nombre-txt-err', 'El nombre tiene que tener al menos 2 caracteres.');
            valido = false;
        } else if(!texto.test(nombre)) {
            showTxtErr('nombre-txt-err', 'El nombre no puede contener números ni símbolos raros.');
            valido = false;
        } else {
            clearTxtErr('nombre-txt-err');
        }

        if(ape1 !== "" && !texto.test(ape1)) {
            showTxtErr('ape1-txt-err', 'El apellido 1 no puede contener números ni símbolos raros.');
            valido = false;
        } else {
            clearTxtErr('ape1-txt-err');
        }

        if(ape2 !== "" && !texto.test(ape2)) {
            showTxtErr('ape2-txt-err', 'El apellido 2 no puede contener números ni símbolos raros.');
            valido = false;
        } else {
            clearTxtErr('ape2-txt-err');
        }

        if(pais !== "" && !texto.test(pais)) {
            showTxtErr('pais-txt-err', 'El pais no puede contener números ni símbolos raros.');
            valido = false;
        } else {
            clearTxtErr('pais-txt-err');
        }

        if (!emailRegex.test(email)) {
            showTxtErr('email-txt-err', 'Introduce un email válido.');
            valido = false;
        } else {
            clearTxtErr('email-txt-err');
        }

        if(!passRegex.test(pass)) {
            showTxtErr('pass-txt-err', 'La contraseña debe contener al menos 1 minúscula, 1 mayúscula, 1 número y mínimo 8 caracteres.');
            valido = false;
        } else {
            clearTxtErr('pass-txt-err');
        }

        if(!passRegex.test(passConf)) {
            showTxtErr('pass-conf-txt-err', 'La contraseña debe contener al menos 1 minúscula, 1 mayúscula, 1 número y mínimo 8 caracteres.');
            valido = false;
        } else {
            clearTxtErr('pass-conf-txt-err');
        }

        if(pass !== passConf) {
            showTxtErr('pass-conf-txt-err', 'Las contraseñas deben coincidir.');
            valido = false;
        } else {
            clearTxtErr('pass-conf-txt-err');
        }

        return valido;
    } 

    /**
     * Comprueba si los datos del formulario de edición de usuario son correctos
     * @returns bool
     */
    function validarDatosEditarUsuario() {
        let valido = true;
        const nick = $('#nick-register').val().trim();
        const nombre = $('#name-register').val().trim();
        const ape1 = $('#ape1-register').val().trim();
        const ape2 = $('#ape2-register').val().trim();
        const pais = $('#pais-register').val().trim();
        const email = $('#email-register').val().trim();
        const pass = $('#pass-register').val().trim();
        const passConf = $('#pass-conf-register').val().trim();

        if(nick.length < 2) {
            showTxtErr('nick-txt-err', 'El nick tiene que tener al menos 2 caracteres.');
            valido = false;
        } else {
            clearTxtErr('nick-txt-err');
        }

        if(nombre.length < 2) {
            showTxtErr('nombre-txt-err', 'El nombre tiene que tener al menos 2 caracteres.');
            valido = false;
        } else if(!texto.test(nombre)) {
            showTxtErr('nombre-txt-err', 'El nombre no puede contener números ni símbolos raros.');
            valido = false;
        } else {
            clearTxtErr('nombre-txt-err');
        }

        if(ape1 !== "" && !texto.test(ape1)) {
            showTxtErr('ape1-txt-err', 'El apellido 1 no puede contener números ni símbolos raros.');
            valido = false;
        } else {
            clearTxtErr('ape1-txt-err');
        }

        if(ape2 !== "" && !texto.test(ape2)) {
            showTxtErr('ape2-txt-err', 'El apellido 2 no puede contener números ni símbolos raros.');
            valido = false;
        } else {
            clearTxtErr('ape2-txt-err');
        }

        if(pais !== "" && !texto.test(pais)) {
            showTxtErr('pais-txt-err', 'El pais no puede contener números ni símbolos raros.');
            valido = false;
        } else {
            clearTxtErr('pais-txt-err');
        }

        if (!emailRegex.test(email)) {
            showTxtErr('email-txt-err', 'Introduce un email válido.');
            valido = false;
        } else {
            clearTxtErr('email-txt-err');
        }

        if (pass !== "") {
            if(!passRegex.test(pass)) {
                showTxtErr('pass-txt-err', 'La contraseña debe contener al menos 1 minúscula, 1 mayúscula, 1 número y mínimo 8 caracteres.');
                valido = false;
            } else {
                clearTxtErr('pass-txt-err');
            }

            if(!passRegex.test(passConf)) {
                showTxtErr('pass-conf-txt-err', 'La contraseña debe contener al menos 1 minúscula, 1 mayúscula, 1 número y mínimo 8 caracteres.');
                valido = false;
            } else {
                clearTxtErr('pass-conf-txt-err');
            }

            if(pass !== passConf) {
                showTxtErr('pass-conf-txt-err', 'Las contraseñas deben coincidir.');
                valido = false;
            } else {
                clearTxtErr('pass-conf-txt-err');
            }
        }

        return valido;
    } 

    /**
     * Comprueba si ha rellenado correctamente los datos del login
     * @returns bool
     */
    function validarDatosLogin() {
        let valido = true;
        const nick = $('#nick-login').val().trim();
        const pass = $('#pass-login').val().trim();

        if(nick.length < 2) {
            showTxtErr('nick-txt-login', 'El nick tiene que tener al menos 2 caracteres.');
            valido = false;
        } else {
            clearTxtErr('nick-txt-login');
        }

        if(!passRegex.test(pass)) {
            showTxtErr('pass-txt-login', 'La contraseña debe contener al menos 1 minúscula, 1 mayúscula, 1 número y mínimo 8 caracteres.');
            valido = false;
        } else {
            clearTxtErr('pass-txt-login');
        }

        return valido;
    }

    /**
     * Comprueba si ha rellenado correctamente los datos de una nueva pregunta
     * @returns bool
     */
    function validarDatosPreguntas() {
        let valida = true;

        const tit = $('#tit-p').val().trim();
        const respA = $('#resp-a-p').val().trim();
        const respB = $('#resp-b-p').val().trim();
        const respC = $('#resp-c-p').val().trim();
        const respD = $('#resp-d-p').val().trim();
        
        if(tit === "") {
            showTxtErr('tit-txt-preg', 'El titulo no puede estar vacío.');
            valida = false;
        } else {
            clearTxtErr('tit-txt-preg');
        }

        if(respA === "") {
            showTxtErr('respA-txt-preg', 'La respuesta A no puede estar vacía.');
            valida = false;
        } else {
            clearTxtErr('respA-txt-preg');
        }

        if(respB === "") {
            showTxtErr('respB-txt-preg', 'La respuesta B no puede estar vacía.');
            valida = false;
        } else {
            clearTxtErr('respB-txt-preg');
        }
        
        if(respC === "") {
            showTxtErr('respC-txt-preg', 'La respuesta C no puede estar vacía.');
            valida = false;
        } else {
            clearTxtErr('respC-txt-preg');
        }

        if(respD === "") {
            showTxtErr('respD-txt-preg', 'La respuesta D no puede estar vacía.');
            valida = false;
        } else {
            clearTxtErr('respD-txt-preg');
        }

        return valida;
    }

    $('#form-register').on('submit', function(e) {
        if (!validarDatosUsuario()) {
            e.preventDefault();
        }
    });

    $('#form-edit').on('submit', function(e) {
        if (!validarDatosEditarUsuario()) {
            e.preventDefault();
        }
    });

    $('#form-login').on('submit', function(e) {
        if (!validarDatosLogin()) {
            e.preventDefault();
        }
    });

    $('#new-pregunta').on('submit', function(e) {
        if (!validarDatosPreguntas()) {
            e.preventDefault();
        } else {
            const hoy = new Date();
            const fecha = hoy.toISOString().split('T')[0];

            $('#fecha-creacion').val(fecha);
        }
    });
    $('#editar-pregunta').on('submit', function(e) {
        if (!validarDatosPreguntas()) {
            e.preventDefault();
        }
    });
});