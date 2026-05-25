/* ------------------------------------
   ------ INICIALIZAMOS VALORES  ------
   ------------------------------------ */
let preguntaActual = 0;
let vidasActuales = VIDAS_TOTAL;
let puntosActuales = 0;
let aciertos = 0;
const letras = ["A", "B", "C", "D"];

// Puntos por dificultad
const PUNTOS_POR_ACIERTO = {
    facil: 15,
    medio: 30,
    dificil: 50,
};

/**
 * Carga las vidas
 * @param {int} current --numero de vidas actuales
 * @param {int} total --numero de vidas totales
 */
function cargarVidas(current, total) {
    const cont = document.querySelector(".vidas-tot");
    cont.innerHTML = "";

    for (let i = 0; i < total; i++) {
        const vida = document.createElement("span");
        vida.classList.add("corazon");
        vida.classList.add(i < current ? "vivo" : "muerto");
        vida.textContent = i < current ? "❤️" : "🤍";
        cont.appendChild(vida);
    }
}

/**
 * Actualiza los puntos
 */
function actualizarPuntos() {
    document.querySelector(".puntos").textContent =
        `Puntos: ${puntosActuales.toLocaleString()}`;
}

/**
 * Carga la pregunta con id x
 * @param {int} id --numero (id) de pregunta
 */
function cargarPregunta(id) {
    const p = PREGUNTAS[id];

    document.getElementById("preg-num").textContent = `${id + 1}`;
    document.getElementById("preg-txt").textContent = p.pregunta;

    const cont = document.getElementById("cont-resp");
    cont.innerHTML = "";

    p.respuestas.forEach((r, i) => {
        const div = document.createElement("button");
        div.classList.add("btn-opcion");
        div.dataset.letra = r.letra;
        div.innerHTML = `
                <span class="resp-${r.letra}">${r.letra}</span> 
                ${r.texto}
            `;
        div.addEventListener("click", () => responder(div, r.letra, p.id));
        cont.appendChild(div);
    });
}

/**
 * Guarda la respuesta y comprueba si es correcta del divPulsado (elemento div que contiene la respuesta)
 * @param {Element} divPulsado
 * @param {String} letra
 * @param {Int} preguntaId
 * @returns
 */
async function responder(divPulsado, letra, preguntaId) {
    const divs = document.querySelectorAll(".btn-opcion");
    divs.forEach((d) => {
        d.style.pointerEvents = "none";
        d.classList.add("disabled");
    });

     try {
        console.log(letra);
        const res = await fetch("comprobar_respuesta.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
            pregunta_id: preguntaId,
            letra: letra,
        }),
        });

        const rawText = await res.text();
        console.log("Respuesta raw del PHP:", rawText);

        const data = JSON.parse(rawText);
        //const data = await res.json();

        if (data.correcta) {
            divPulsado.classList.add("correcta");
            aciertos++;
            puntosActuales += PUNTOS_POR_ACIERTO[DIFICULTAD.toLowerCase()] ?? 100;
            actualizarPuntos();
        } else {
            divPulsado.classList.add("incorrecta");

            divs.forEach((d) => {
                if (d.dataset.letra === data.correcta_letra) {
                d.classList.add("correcta");
                }
            });

            loseLife();
        }
    } catch (err) {
        console.error("Error:", err);
    }

    if (vidasActuales <= 0) {
        setTimeout(finPartida, 500);
        return;
    }

    if (preguntaActual >= TOTAL_PREG - 1) {
        setTimeout(finPartida, 500);
        return;
    }

    document.getElementById("cont-btn-next").style.display = "flex";
}

/**
 * Pasa a la siguiente pregunta
 */
function siguientePregunta() {
    preguntaActual++;
    cargarPregunta(preguntaActual);
}

/**
 * Se resta una vida
 */
function loseLife() {
    vidasActuales--;

    const container = document.querySelector(".vidas-tot");
    const hearts = container.querySelectorAll(".corazon.vivo");
    const last = hearts[hearts.length - 1];

    if (last) {
        last.classList.add("perdida");
        setTimeout(() => {
            cargarVidas(vidasActuales, VIDAS_TOTAL);
        }, 400);
    }
}

/**
 * Se acaba la partida
 */
async function finPartida() {
    try {
        const res = await fetch("guardar_resultado.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
            dificultad: DIFICULTAD,
            puntos: puntosActuales,
            aciertos: aciertos,
            total: TOTAL_PREG,
        }),
        });

        const rawText = await res.text();
        console.log("guardar_resultado respuesta:", rawText);
    } catch (err) {
        console.error("Error al guardar resultado:", err);
    }
    console.log("GUARDADA");

    mostrarResultado();
}

/**
 * Muestra el POP-UP de estadísticas de la partida
 */
function mostrarResultado() {
    const pct = Math.round((aciertos / TOTAL_PREG) * 100);
    const victoria = vidasActuales > 0;
    const layoutResult = document.querySelector(".layout-result-game");

    const corazones = vidasActuales > 0 ? "❤️".repeat(vidasActuales) : "💔";

    layoutResult.innerHTML = `
        <div class="result-modal">
            <button class="result-close" id="btnCerrar"
                onclick="window.location.href='../public/index.php'">
                <i class="fa-solid fa-x"></i>
            </button>

            <div class="result-icon">${victoria ? "🏆" : "💀"}</div>
            <h2 class="result-titulo">
                ${victoria ? "¡Partida completada!" : "¡Sin vidas!"}
            </h2>
            <p class="result-sub">
                Dificultad: ${DIFICULTAD} · ${TOTAL_PREG} preguntas
            </p>

            <div class="result-stats">
                <div class="result-stat">
                    <span class="stat-num">${puntosActuales.toLocaleString()}</span>
                    <span class="stat-label">Puntuación</span>
                </div>
                <div class="result-stat">
                    <span class="stat-num">${aciertos}/${TOTAL_PREG}</span>
                    <span class="stat-label">Aciertos</span>
                </div>
                <div class="result-stat">
                    <span class="stat-num">${corazones}</span>
                    <span class="stat-label">Vidas restantes</span>
                </div>
            </div>

            <div class="result-progbar">
                <div class="result-progfill" style="width:0%" id="progFill"></div>
            </div>
            <p class="result-pct">${pct}% de aciertos</p>

            <div class="result-btns">
                <button class="btn-volver" 
                    onclick="window.location.href='../public/index.php'">
                    ← Volver al inicio
                </button>
                <button class="btn-repetir"
                    onclick="window.location.href='../public_user/game.php?difc=${DIFICULTAD}'">
                    🔁 Jugar de nuevo
                </button>
            </div>
        </div>
    `;

    layoutResult.classList.add("active");

    setTimeout(() => {
        document.getElementById("progFill").style.width = `${pct}%`;
    }, 100);

    document.getElementById("btnCerrar").addEventListener("click", () => {
        layoutResult.classList.remove("active");
    });

    layoutResult.addEventListener("click", (e) => {
        if (e.target === layoutResult) {
        layoutResult.classList.remove("active");
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    cargarVidas(vidasActuales, VIDAS_TOTAL);
    actualizarPuntos();
    cargarPregunta(preguntaActual);

    document
        .getElementById("bnt-next-preg")
        .addEventListener("click", siguientePregunta);
    document.getElementById("cont-btn-next").style.display = "none";
});
