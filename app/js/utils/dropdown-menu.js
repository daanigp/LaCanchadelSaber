document.addEventListener('DOMContentLoaded', () => {

    const isMobile = () => window.innerWidth < 768;

    // Cierra todos los submenús
    const closeAll = () => {
        document.querySelectorAll('.dropdown-submenu, .dropdown-submenu-difc').forEach(el => {
            el.classList.remove('open');
        });
        document.querySelectorAll('.flecha').forEach(el => {
            el.classList.remove('rotada');
        });
    };

    document.querySelectorAll('.dropdown-toggle').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();

            if (!isMobile()) return;

            const submenu = btn.nextElementSibling;
            const flecha = btn.querySelector('.flecha');
            const estaAbierto = submenu.classList.contains('open');

            // Cierra solo los del mismo nivel, no los padres
            const padre = btn.closest('ul');
            padre.querySelectorAll(':scope > li > .dropdown-submenu, :scope > li > .dropdown-submenu-difc').forEach(el => {
                el.classList.remove('open');
            });
            padre.querySelectorAll(':scope > li > .dropdown-toggle .flecha').forEach(el => {
                el.classList.remove('rotada');
            });

            if (!estaAbierto) {
                submenu.classList.add('open');
                flecha.classList.add('rotada');
            }
        });
    });

    // Click fuera cierra todo
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown-btn')) {
            closeAll();
        }
    });
});