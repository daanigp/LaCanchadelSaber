document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('slider');
    const slides = slider.querySelectorAll('.slide');
    const btnPrev = document.getElementById('sliderPrev');
    const btnNext = document.getElementById('sliderNext');
    const dotsWrap = document.getElementById('sliderDots');

    let current = 0;
    let autoplay = null;
    const total = slides.length;

    /**
     * Crea los puntos de los slides
     */
    slides.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.classList.add('slider-dot');
        dot.setAttribute('aria-label', `Slide ${i + 1}`);
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goTo(i));
        dotsWrap.appendChild(dot);
    });

    const dots = dotsWrap.querySelectorAll('.slider-dot');

    /**
     * Va al slider
     * @param {int} idx 
     */
    function goTo(idx) {
        current = (idx + total) % total;
        slider.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    /**
     * Controles del slider
     */
    btnPrev.addEventListener('click', () => { 
        goTo(current - 1); 
        resetAutoplay(); 
    });
    
    btnNext.addEventListener('click', () => { 
        goTo(current + 1); 
        resetAutoplay(); 
    });

    /**
     * Cada 4segundos se pasa al siguiente slide
     */
    function startAutoplay() {
        autoplay = setInterval(() => goTo(current + 1), 4000);
    }

    /**
     * Resetea el autoplay, para que vuelvan a pasar 4 segundos en la nueva slide
     */
    function resetAutoplay() {
        clearInterval(autoplay);
        startAutoplay();
    }

    startAutoplay();

    /**
     * Se para el autoplay al hacer hover
     */
    slider.closest('.slider-wrap').addEventListener('mouseenter', () => clearInterval(autoplay));
    slider.closest('.slider-wrap').addEventListener('mouseleave', startAutoplay);


    /**
     * Deslizar de forma táctil
     */
    let startX = 0;

    slider.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
    }, { passive: true });

    slider.addEventListener('touchend', e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            goTo(diff > 0 ? current + 1 : current - 1);
            resetAutoplay();
        }
    }, { passive: true });
});