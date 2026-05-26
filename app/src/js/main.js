// POPUP
$(function () {

    /**
     * Se cargan las variables del mensaje
     */
    if (typeof phpMensaje !== 'undefined') {
        $('#mensajePopUp').html(phpMensaje);
        $('#overlayPopUp').addClass('activo-popup');
        $('#overlayPopUp').find('.popup-cont').addClass(phpTipo);
    }

    /**
     * Se cierra de la X
     */
    $('#btnCerrarPopUp').on('click', function () {
        $('#overlayPopUp').removeClass('activo-popup');
        $('#overlayPopUp').find('.popup-cont').removeClass('success err');
    });

    /**
     * Se cierra al hacer click fuera
     */
    $('#overlayPopUp').on('click', function (e) {
        if ($(e.target).is('#overlayPopUp')) {
            $('#overlayPopUp').removeClass('activo-popup');
            $('#overlayPopUp').find('.popup-cont').removeClass('success err');
        }
    });

    /**
     * Se cierra al pusar la tecla "ESC"
     */
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            $('#overlayPopUp').removeClass('activo-popup');
            $('#overlayPopUp').find('.popup-cont').removeClass('success err');
        }
    });
});

/**
 * Para que el botón de editar imágen sea un elemento button, 
 * y el input de seleccionar la imágen esté invisible
 */
document.getElementById('img-edit').addEventListener('change', function() {
    if (this.files.length > 0) {
        document.getElementById('form-img-edit').submit();
    }
});