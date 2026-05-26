# Proyecto Intermodular - La Cancha del Saber

## Documentación Oficial

### Páginas

#### Index

La página index es la primera página que ve un usuario cuando entra en la web, en ella el usuario puede ver una breve explicación sobre como jugar, y cómo funciona el sistema de puntuación.  
También podrá ver el ranking de los 5 mejores usuarios/puntuaciones del juego.

#### About us

![AboutUs](imgs/page/abput_us.png)

En esta página hay un poco de información sobre la aplicación, sobre el autor y sobre futuras mejoras.

#### Perfil

![Perfil](imgs/page/verPerfil.png.png)

En la página del perfil, el usuario podrá ver sus estadísticas del _número de partidas jugadas_, el _porcentaje de aciertos_ y el _número de puntuación máximo_.  
Podrá ver su historial de partidas, en él, podrá ver los siguientes datos:

* Puntuación obtenida.
* Dificultad.
* Puntuación obtenida.
* Fecha de la partida.

También tendrá la opción, si así lo desea, de editar el perfil.

#### Editar Perfil

![EditarPerfil](imgs/page/editar_perfil.png)

En editar perfil, el usuario será capaz de editar los datos que desee de su perfil.  
Se autocompletaran los campos con los valores del perfil del usuario, en caso de que el usuario no desee cambiar algún valor, se guardará el mismo valor que tenía antes.
También podrá editar la imagen de perfil.

#### Amigos

![IMG](imgs/page/amigos.png)

Cada usuario podrá observar un listado de los amigos que tiene agregados.  
Podrá agregar amigos nuevos, y también podrá borrar amigos.  
A su vez, podrá ver el ranking de puntuaciones de amigos.  

#### Puntuaciones

![Puntuaciones](imgs/page/puntuaciones.png)

En esta página se podrá ver un listado con las puntuaciones globales, es decir, el ranking global del juego.  
Existe un filtro para mostrar las puntuaciones por dificultad si así lo desea el usuario.

#### Juego

![Juego](imgs/page/juego.png)

El juego consiste en tratar de responder las preguntas sumando la mayor cantidad de puntos posibles.  
El jugador contará con un total de 3 vidas, que irá perdiendo a raíz de ir respondiendo de manera errónea las preguntas.  
Esta página está en 3 distintas versiones:

* **Juego en dificultad fácil:** Para este modo de juego, el sistema de puntuación se basa en que el jugador obtendrá 10 puntos por cada pregunta que acierte.

* **Juego en dificultad medio:"** Para este modo de juego, el sistema de puntuación se basa en que el jugador obtendrá 20 puntos por cada pregunta que acierte.

* **Juego en dificultad difícil:** Para este modo de juego, el sistema de puntuación se basa en que el jugador obtendrá 30 puntos por cada pregunta que acierte.

#### Panel

![Panel](imgs/page/panelPreguntas.png)

Sólo los usuarios administradores serán capaces de ver esta página.  
En esta página se verá un panel con todas las pregunas existentes en el juego, pudiendo filtrarlas por categoría y por dificultad.  
En cada pregunta, tiene dos opciones, que són editar o borrar.  
La de editar, le lleva al usuario administrador al formulario en el que podrá editar los valores de la pregunta.  
La de borrar, llevará a una página "modo confirmación" para borrar la pregunta o rechazar el borrado si ha pulsado por error.
También existe un botón para añadir nuevas preguntas.
