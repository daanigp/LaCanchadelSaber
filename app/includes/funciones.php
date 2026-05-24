<?php
    /**
     * Comprueba si existe un usuario y contraseña (para hacer login)
     * @param PDO $conexion
     * @param String $user
     * @param String $pass
     */
    function loginDB($conexion, $user, $pass) {
        $sql = "SELECT * FROM users WHERE nick= :nickname AND password= :pwd";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":nickname" => $user,
            ":pwd" => $pass
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Comprueba el rol del usuario
     * @param PDO $conexion
     * @param Int $userId
     */
    function comprobarRol($conexion, $userId) {
        $sql = "SELECT 
                    rn.nombre_rol
                FROM users u
                INNER JOIN user_role ur ON u.id = ur.id_user
                INNER JOIN role_names rn ON ur.id_role = rn.id
                WHERE u.id = :iduser";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":iduser" => $userId
        ]);

        $rol = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rol) {
            return $rol['nombre_rol'];
        }

        return null;
    }



    /**
     * Genera un select a partir de ciertos datos
     * @param PDO $conexion
     * @param String $tabla
     * @param String $columna
     * @param String $nombreSelector
     * @param String $valorSeleccionado (por defecto = '')
     * @param Boolean $mostrarTodas (por defecto = true)
     */
    function generarSelect($conexion, $tabla, $columna, $nombreSelector, $valorSeleccionado = '', $mostrarTodas = true) {
        $html = "<select name='$nombreSelector'>\n";
        if ($mostrarTodas) {
            $html .= " <option value='TODAS'>TODAS</option>\n";
        }

        // Consulta usando PDO
        $sql = "SELECT DISTINCT $columna FROM $tabla";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        foreach ($stmt->fetchAll() as $fila) {
            $opcion = htmlspecialchars($fila[$columna]);
            $selected = ($valorSeleccionado === $opcion) ? " selected" : "";
            $html .= " <option value='$opcion'$selected>$opcion</option>\n";
        }

        $html .= "</select>\n";
        return $html;
    }

    /**
     * Obtener el nombre del valor del $filtro de la $tabla según el id
     * $campo -> el campo que se quiere obtener
     * @param PDO $conexion
     * @param String $tabla
     * @param String $filtro
     * @param String $campo (por defecto = 'nombre')
     */
    function obtenerNombreById($conexion, $tabla, $filtro, $campo = 'nombre') {
        $nombre = "";
        $sql = "SELECT * FROM " . $tabla;
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        foreach($stmt->fetchAll() as $fila) {
            if($fila['id'] == $filtro) {
                $nombre = $fila[$campo];
            }
        }
        return $nombre;
    }

    /**
     * Obtener el id del valor del $filtro de la $tabla según el nombre
     * $campo -> el campo que se quiere obtener
     * @param PDO $conexion
     * @param String $tabla
     * @param String $filtro
     * @param String $campo (por defecto = 'id')
     */
    function obtenerIDByName($conexion, $tabla, $filtro, $campo = 'id') {
        $id = 0;
        $sql = "SELECT * FROM " . $tabla . " WHERE nombre= :nombre";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":nombre" => $filtro
        ]);

        foreach($stmt->fetchAll() as $fila) {
            if($fila['nombre'] === $filtro) {
                $id = $fila[$campo];
            }
        }

        return $id;
    }

    /**
     * Obtener el id del valor del $filtro de la $tabla según el nombre
     * $campo -> el campo que se quiere obtener
     * @param PDO $conexion
     * @param String $tabla
     * @param String $filtro
     * @param String $campo (por defecto = 'id')
     */
    function obtenerIDByNick($conexion, $tabla, $filtro, $campo = 'id') {
        $id = 0;
        $sql = "SELECT * FROM " . $tabla . " WHERE nick= :nick";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":nick" => $filtro
        ]);

        foreach($stmt->fetchAll() as $fila) {
            if($fila['nick'] === $filtro) {
                $id = $fila[$campo];
            }
        }

        return $id;
    }

    /**
     * Genera filas para una tabla html
     * @param PDO $conexion
     * @param Int $userID
     * @param String $tabla
     * @param String $filtroDifc (por defecto = null)
     * @param Int $pagina (por defecto = 1)
     * @param Int $porPagina (por defecto = 10)
     */
    function generarTabla($conexion, $userID = 0, $tabla, $filtroDifc = null, $pagina = 1, $porPagina = 10) {
        if($filtroDifc === "TODAS") {
            $filtroDifc = null;
        }
        
        $offset = ($pagina - 1) * $porPagina;

        if ($filtroDifc !== null) {
            //Primero obtenemos id
            $idDifc = obtenerIDByName($conexion, 'dificultades', $filtroDifc);

            //Buscamos en la tabla los que sean con ese id
            $sqlTotalFilas = "SELECT COUNT(*) FROM " . $tabla . " WHERE dificultad_id= :difc ORDER BY puntuacion DESC";
            $stmtTotalFIlas = $conexion->prepare($sqlTotalFilas);
            $stmtTotalFIlas->execute([
                ":difc" => $idDifc
            ]);

            // Paginamos los elementos
            $sql = "SELECT * FROM " . $tabla . " 
                        WHERE dificultad_id= :difc 
                        ORDER BY puntuacion DESC
                        LIMIT :limite
                        OFFSET :offset";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(":difc", $idDifc);

        } else {
            $sqlTotalFilas = "SELECT COUNT(*) FROM " . $tabla . " ORDER BY puntuacion DESC";
            $stmtTotalFIlas = $conexion->prepare($sqlTotalFilas);
            $stmtTotalFIlas->execute();

            $sql = "SELECT * FROM " . $tabla . " 
                        ORDER BY puntuacion DESC
                        LIMIT :limite
                        OFFSET :offset";
            $stmt = $conexion->prepare($sql);
        }

        $filasTotal = $stmtTotalFIlas->fetchColumn();
        $totalPaginas = ceil($filasTotal / $porPagina);

        $stmt->bindValue(":limite", $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($filas)) {
            echo "<tr>";
            echo "<td colspan='4'>No se han encontrado resultados.</td>";
            echo "</tr>";
        } else {
            // PINTAMOS LA TABLA
            foreach ($filas as $fila) { ?>
                <tr class="<?= $fila['id_user'] === $userID ? "userActual": "" ?>">
                    <td><?= $fila['puntuacion'] ?></td>
                    <td><?= obtenerNombreById($conexion, "users", $fila['id_user'], "nick") ?></td>
                    <td>
                        <?php
                        if($filtroDifc !== null) {
                            echo "" . $filtroDifc;
                        } else {
                            echo obtenerNombreById($conexion, "dificultades", $fila['dificultad_id']);
                        }
                        ?>
                    </td>
                    <td><?= str_replace(['-', ' '], ['/', ' - '], $fila['fecha']) ?></td>
                </tr>
            <?php
            }
        }

        return $totalPaginas;
    }

    /**
     * Genera filas para una tabla html
     * @param PDO $conexion
     * @param String $user
     */
    function obtenerDatosUsuario($conexion, $user) {
        //Buscamos los datos del usuario $user
        $sql = "SELECT * FROM users WHERE nick= :nickname";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":nickname" => $user
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return $usuario;
        } else {
            return null;
        }
    }

     /**
     * Genera filas para una tabla html
     * @param PDO $conexion
     * @param Int $id
     */
    function obtenerDatosUsuarioById($conexion, $id) {
        //Buscamos los datos del usuario $user
        $sql = "SELECT * FROM users WHERE id= :idUser";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":idUser" => $id
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return $usuario;
        } else {
            return null;
        }
    }

    /**
     * Genera filas para una tabla html
     * @param PDO $conexion
     * @param Int $id
     */
    function obtenerRankingUsuarioById($conexion, $id){
        $sql = "SELECT 
                    posicion,
                    nick,
                    avatar_url,
                    mejor_puntuacion,
                    total_partidas,
                    total_respuestas,
                    total_aciertos,
                    CASE 
                        WHEN total_respuestas = 0 OR total_respuestas IS NULL 
                        THEN 0 
                        ELSE ROUND((total_aciertos / total_respuestas) * 100, 1)
                    END AS porcentaje_acierto
                FROM (
                    SELECT 
                        u.id,
                        u.nick,
                        u.avatar_url,
                        COALESCE(MAX(p.puntuacion), 0)                        AS mejor_puntuacion,
                        RANK() OVER (ORDER BY COALESCE(MAX(p.puntuacion), 0) DESC) AS posicion,
                        COUNT(DISTINCT p.id)                                  AS total_partidas,
                        COUNT(pr.id)                                          AS total_respuestas,
                        COALESCE(SUM(pr.es_correcta), 0)                      AS total_aciertos
                    FROM users u
                    LEFT JOIN partidas p            ON p.id_user    = u.id
                    LEFT JOIN partida_respuestas pr ON pr.id_partida = p.id
                    GROUP BY u.id
                ) AS ranking
                WHERE id = :id";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":id" => $id
        ]);

        $resultado = $stmt->fetch();

        if($resultado) {
            return $resultado;
        } else {
            return null;
        }
    }

    /**
     * Genera filas para una tabla html
     * @param PDO $conexion
     * @param Int $userId
     * @param Int $pagina (por defecto = 1)
     * @param Int $porPagina (por defecto = 5)
     */
    function tablaHistorialPartidas($conexion, $userId, $pagina = 1, $porPagina = 5){
        $offset = ($pagina - 1) * $porPagina;
    
        $sqlTotalFilas = "SELECT COUNT(*) FROM partidas 
                                WHERE id_user= :id
                                ORDER BY puntuacion DESC";
        $stmtTotalFIlas = $conexion->prepare($sqlTotalFilas);
        $stmtTotalFIlas->execute([
            ":id" => $userId
        ]);

        $sql = "SELECT * FROM partidas 
                    WHERE id_user= :id 
                    ORDER BY puntuacion DESC
                    LIMIT :limite
                    OFFSET :offset";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(":id", $userId);

        $filasTotal = $stmtTotalFIlas->fetchColumn();
        $totalPaginas = ceil($filasTotal / $porPagina);

        $stmt->bindValue(":limite", $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($filas)) {
            echo "<tr>";
            echo "<td colspan='4'>No se han encontrado resultados.</td>";
            echo "</tr>";
        } else {
            // PINTAMOS LA TABLA
            foreach ($filas as $fila) { ?>
                <tr>
                    <td>Fútbol</td>
                    <td><?= obtenerNombreById($conexion, "dificultades", $fila['dificultad_id']) ?></td>
                    <td><?= $fila['puntuacion'] ?></td>
                    <td><?= str_replace(['-', ' '], ['/', ' - '], $fila['fecha']) ?></td>
                </tr>
            <?php
            }
        }

        return $totalPaginas;
    }
    
    /**
     * Actualiza la información del usuario en la bbdd
     * @param PDO $conexion
     * @param Int $id
     * @param String $nickNuevo
     * @param String $nombreNuevo
     * @param String $ape1Nuevo
     * @param String $ape2Nuevo
     * @param String $paisNuevo
     * @param String $emailNuevo
     * @param String $pwdNueva
     */
    function  updateUserInfo($conexion, $id, $nickNuevo, $nombreNuevo, $ape1Nuevo, $ape2Nuevo, $paisNuevo, $emailNuevo, $pwdNueva) {
        $sql = "UPDATE users
            SET nick = :nick,
                email = :email,
                password = :pass,
                nombre = :nombre,
                apellido1 = :apellido1,
                apellido2 = :apellido2,
                nacionalidad = :pais
            WHERE id = :idUser";
        $stmt = $conexion->prepare($sql);
        $update = $stmt->execute([
            ":nick" => $nickNuevo,
            ":email" => $emailNuevo,
            ":pass" => $pwdNueva,
            ":nombre" => $nombreNuevo,
            ":apellido1" => $ape1Nuevo,
            ":apellido2" => $ape2Nuevo,
            ":pais" => $paisNuevo,
            ":idUser" => $id
        ]);

        if (!$update) {
            return false;
        }

        return $stmt->rowCount() > 0;


    }

    /**
     * Actualiza la información del usuario en la bbdd
     * @param PDO $conexion
     * @param Int $id
     * @param String $image
     */
    function  updateUserImage($conexion, $id, $image) {
        $sql = "UPDATE users
            SET avatar_url = :imageName
            WHERE id = :idUser";
        $stmt = $conexion->prepare($sql);
        $update = $stmt->execute([
            ":imageName" => $image,
            ":idUser" => $id
        ]);

        if (!$update) {
            return false;
        }

        return $stmt->rowCount() > 0;


    }

    /**
     * Actualiza la información del usuario en la bbdd
     * @param PDO $conexion
     * @param String $nick
     * @param String $nombre
     * @param String $ape1
     * @param String $ape2
     * @param String $email
     * @param String $pwd
     * @param String $pais
     * @param String $imageURL
     */
    function  createUser($conexion, $nick, $nombre, $ape1, $ape2, $email, $pwd, $pais, $imageURL) {
        $sql = "INSERT INTO users (nick, email, password, nombre, apellido1, apellido2, nacionalidad, avatar_url)
            VALUES (:nick,
                :email,
                :pass,
                :nombre,
                :apellido1,
                :apellido2,
                :nacionalidad,
                :imagen)";
        $stmt = $conexion->prepare($sql);
        $update = $stmt->execute([
            ":nick" => $nick,
            ":email" => $email,
            ":pass" => $pwd,
            ":nombre" => $nombre,
            ":apellido1" => $ape1,
            ":apellido2" => $ape2,
            ":nacionalidad" => $pais,
            ":imagen" => $imageURL
        ]);

        if (!$update) {
            return false;
        }

        return $stmt->rowCount() > 0;
    }

    /**
     * Obtiene las preguntas que sean de dificultad $dificultadID de la tabla $tabla
     * @param PDO $conexion
     * @param String $tabla
     * @param Int $dificultadID
     * @param Int $limite
     */
    function obtenerPreguntasAleatorias($conexion, $tabla, $dificultadID, $limite) {
        $sql = "SELECT * FROM $tabla 
            WHERE dificultad_id=:dificultad 
            ORDER BY RAND() 
            LIMIT $limite";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":dificultad" => $dificultadID
        ]);

        $preguntas = $stmt->fetchAll();

        if ($preguntas) {
            return $preguntas;
        } else {
            return null;
        }
    }

    /**
     * Guarda la partida
     * @param PDO $conexion
     * @param Int $id_user
     * @param Int $puntuacion
     * @param Int $dificultad_id
     */
    function guardarPartida($conexion, $id_user, $puntuacion, $dificultad_id) {
        $sql = "INSERT INTO partidas (id_user, puntuacion, dificultad_id)
            VALUES (
                :iduser,
                :puntuacion,
                :dificultad
            )";
        $stmt = $conexion->prepare($sql);
        $guardada = $stmt->execute([
            ":iduser" => $id_user,
            ":puntuacion" => $puntuacion,
            ":dificultad" => $dificultad_id
        ]);

        $partidaId = $conexion->lastInsertId();

        if($guardada) {
            return $partidaId;
        } else {
            return null;
        }
    }

    /**
     * Listado de amigos
     * @param PDO $conexion
     * @param Int $id_user
     * @param Int $pagina (por defecto = 1)
     * @param Int $porPagina (por defecto = 5)
     */
    function verAmigos($conexion, $id_user, $pagina = 1, $porPagina = 5) {
        $estado = "aceptada";
        $sql = "SELECT * FROM amistades
            WHERE (id_user1=:id OR id_user2=:id)
            AND estado = :estado";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":id" => $id_user,
            ":estado" => $estado
        ]);
        $filas = $stmt->fetchAll();

        if (empty($filas)) {
            echo "<p class='err-amigos'>No se han encontrado resultados.</p>";
        } else { 
            $usuario = null;
            foreach($filas as $fila) {
                $idAmigo = ($fila['id_user1'] == $id_user) ? $fila['id_user2'] : $fila['id_user1'];
                $usuario = obtenerDatosUsuarioById($conexion, $idAmigo);
                if($usuario !== null) { ?>
                    <div class="card-amigo">
                        <img src="../static/img/profile/<?= $usuario['avatar_url'] ?? "nutria-2.jpg" ?>" alt="Foto perfil amigo">
                        <div class="texto-info">
                            <p class="nombre-amigo"><?= $usuario['nombre'] ?></p>
                            <span class="nick-amigo"><?= $usuario['nick'] ?></span>
                        </div>
                        <form action="" method="post">
                            <input type="hidden" name="id-amigo" id="id-amigo" value="<?= $idAmigo ?>">
                            <button type="submit" name="borrar-amigo" id="borrar-amigo">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
        <?php   } else {
                    echo "<p class='err-amigos'>No se han encontrado el amigo con id " . $fila['id_user2'] . "</p>";
                }
            }
        }
        
    }

    /**
     * Listado de solicitudes pendientes
     * @param PDO $conexion
     * @param Int $id_user
     * @param Int $pagina (por defecto = 1)
     * @param Int $porPagina (por defecto = 5)
     */
    function solicitudesPendientes($conexion, $id_user, $pagina = 1, $porPagina = 5) {
        $estado = "pendiente";
        $sql = "SELECT * FROM amistades
            WHERE id_user2=:id
            AND estado = :estado";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":id" => $id_user,
            ":estado" => $estado
        ]);
        $filas = $stmt->fetchAll();

        if (empty($filas)) {
            echo "<p class='err-amigos'>No hay solicitudes pendientes </p>";
        } else { 
            $usuario = null;
            foreach($filas as $fila) {
                $idAmigo = $fila['id_user1'];
                $usuario = obtenerDatosUsuarioById($conexion, $idAmigo);
                if($usuario !== null) { ?>
                    <div class="card-solicitud">
                        <img src="../static/img/profile/<?= $usuario['avatar_url'] ?? "nutria-2.jpg" ?>" alt="Foto perfil amigo solicitado">
                        <div class="texto-info-solicitud">
                            <p class="nombre-solicitud"><?= $usuario['nombre'] ?></p>
                            <span class="nick-solicitud"><?= $usuario['nick'] ?></span>
                        </div>
                        <form class="btns-solicitud" action="" method="post">
                            <input type="hidden" name="id_new_friend" value="<?= $idAmigo ?>">

                            <button type="submit" name="aceptar-mobile" id="aceptar-mobile">
                                <i class="fa-solid fa-user-check"></i>
                            </button>
                            <button type="submit" name="aceptar-desktop" id="aceptar-desktop">
                                Aceptar
                            </button>
                            <button type="submit" name="rechazar-mobile" id="rechazar-mobile">
                                <i class="fa-solid fa-user-xmark"></i>
                            </button>
                            <button type="submit" name="rechazar-desktop" id="rechazar-desktop">
                                Rechazar
                            </button>
                        </form>
                    </div>
        <?php   } else {
                    echo "<p class='err-amigos'>No se han encontrado el amigo con id " . $fila['id_user2'] . "</p>";
                }
            }
        }
    }

    /**
     * Genera el ranking de amigos
     * @param PDO $conexion
     * @param Int $id_user
     * @param String $filtroDifc (por defecto = null)
     * @param Int $pagina (por defecto = 1)
     * @param Int $porPagina (por defecto = 10)
     */
    function generarRankingAmigos($conexion, $id_user, $filtroDifc = null, $pagina = 1, $porPagina = 10) {
        $idAmigos = obtenerAmigos($conexion, $id_user);
        array_push($idAmigos, $id_user);

        $placeholders = implode(',', array_fill(0, count($idAmigos), '?'));
        $offset = ($pagina - 1) * $porPagina;
    
        $sqlTotalFilas = "SELECT COUNT(*) FROM partidas 
                            WHERE id_user IN ($placeholders) 
                            ORDER BY puntuacion DESC";
        $stmtTotalFIlas = $conexion->prepare($sqlTotalFilas);
        $stmtTotalFIlas->execute($idAmigos);
        
        $filasTotal = $stmtTotalFIlas->fetchColumn();
        $totalPaginas = ceil($filasTotal / $porPagina);

        $sql = "SELECT * FROM partidas 
            WHERE id_user IN ($placeholders) 
            ORDER BY puntuacion DESC
            LIMIT $porPagina
            OFFSET $offset";
        $stmt = $conexion->prepare($sql);
        $stmt->execute($idAmigos);

        $partidas = $stmt->fetchAll();

        if(empty($idAmigos)) {
            echo "<tr>";
            echo "<td colspan='4'>No se han encontrado resultados.</td>";
            echo "</tr>";
        } else {
            // PINTAMOS LA TABLA
            foreach ($partidas as $fila) { ?>
                <tr class="<?= $fila['id_user'] === $id_user ? "userActual": "" ?>">
                    <td><?= $fila['puntuacion'] ?></td>
                    <td><?= obtenerNombreById($conexion, "users", $fila['id_user'], "nick") ?></td>
                    <td>
                        <?php
                        if($filtroDifc !== null && $filtroDifc !== "TODAS") {
                            echo "" . $filtroDifc;
                        } else {
                            echo obtenerNombreById($conexion, "dificultades", $fila['dificultad_id']);
                        }
                        ?>
                    </td>
                    <td><?= str_replace(['-', ' '], ['/', ' - '], $fila['fecha']) ?></td>
                </tr>
            <?php
            }
        }

        return $totalPaginas;       
    }

    /**
     * Obtiene todos los amigos de $id_user
     * @param PDO $conexion
     * @param Int $id_user
     */
    function obtenerAmigos($conexion, $id_user) {
        $idAmigos = [];

        $estado = "aceptada";
        $sql = "SELECT * FROM amistades
            WHERE (id_user1=:id OR id_user2=:id)
            AND estado = :estado";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":id" => $id_user,
            ":estado" => $estado
        ]);
        $filas = $stmt->fetchAll();

        if (!empty($filas)) {
            foreach($filas as $fila) {
                $idAmigo = ($fila['id_user1'] == $id_user) ? $fila['id_user2'] : $fila['id_user1'];
                array_push($idAmigos, $idAmigo);
            }
        }

        return $idAmigos;
    }

    /**
     * Borra la amistad entre $idUser y $idAmigo
     * @param PDO $conexion
     * @param Int $idUser
     * @param Int $idAmigo
     */
    function borrarAmigo($conexion, $idUser, $idAmigo) {
        $sql = "DELETE FROM amistades 
                WHERE (id_user1 = :user1 AND id_user2 = :user2)
                OR (id_user1 = :user2 AND id_user2 = :user1)";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":user1" => $idUser,
            ":user2" => $idAmigo
        ]);

        $borrado = $stmt->rowCount() > 0;

        return $borrado;
    }

    /**
     * Rechaza la amistad entre $idUser y $idAmigo
     * @param PDO $conexion
     * @param Int $idUser
     * @param Int $idAmigo
     */
    function rechazarAmigo($conexion, $idUser, $idAmigo) {
        $estado = "pendiente";
        $sql = "DELETE FROM amistades
                WHERE id_user1 = :user1 
                AND id_user2 = :user2
                AND estado = :estado";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":estado" => $estado,
            ":user1" => $idAmigo,
            ":user2" => $idUser
        ]);

        $rechazado = $stmt->rowCount() > 0;

        return $rechazado;
    }
    
    /**
     * Acepta la amistad entre $idUser y $idAmigo
     * @param PDO $conexion
     * @param Int $idUser
     * @param Int $idAmigo
     */
    function aceptarAmigo($conexion, $idUser, $idAmigo) {
        $estado = "pendiente";
        $newEstado = "aceptada";
        $sql = "UPDATE amistades
                SET estado = :nuevoEstado
                WHERE id_user1 = :user1 
                AND id_user2 = :user2
                AND estado = :estado";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":estado" => $estado,
            ":user1" => $idAmigo,
            ":user2" => $idUser,
            ":nuevoEstado" => $newEstado
        ]);

        $aceptado = $stmt->rowCount() > 0;

        return $aceptado;
    }

    /**
     * Envia solicitud de amistad entre $idUser y $idAmigo
     * @param PDO $conexion
     * @param Int $idUser
     * @param Int $idAmigo
     */
    function enviarSolicitud($conexion, $idUser, $idAmigo) {
        $estado = "pendiente";
        $sql = "INSERT INTO amistades (id_user1, id_user2, estado)
                VALUES (
                    :user1, 
                    :user2, 
                    :estado
                )";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":user1" => $idUser,
            ":user2" => $idAmigo,
            ":estado" => $estado
        ]);

        $enviada = $stmt->rowCount() > 0;

        return $enviada;
    }

    /**
     * Comprueba si existe una solicitud de amistad entre $idUser y $idAmigo
     * @param PDO $conexion
     * @param Int $idUser
     * @param Int $idAmigo
     */
    function comprobarSolicitud($conexion, $idUser, $idAmigo) {
        // Evitar duplicados
        $sql = "SELECT * FROM amistades
                    WHERE id_user1 = :user1 
                    AND id_user2 = :user2";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":user1" => $idUser,
            ":user2" => $idAmigo
        ]);

        $existe = $stmt->rowCount() > 0;

        return $existe;
    }

    /**
     * Genera filas para una tabla html
     * @param PDO $conexion
     * @param String $tabla
     * @param String $filtroCat (por defecto = null)
     * @param String $filtroDifc (por defecto = null)
     * @param Int $pagina (por defecto = 1)
     * @param Int $porPagina (por defecto = 15)
     */
    function generarTablaPanel($conexion, $tabla, $filtroCat = null, $filtroDifc = null, $pagina = 1, $porPagina = 15) {
        if($filtroCat === "TODAS") {
            $filtroCat = null;
        }
        if($filtroDifc === "TODAS") {
            $filtroDifc = null;
        }

        $offset = ($pagina - 1) * $porPagina;

        if ($filtroCat !== null && $filtroDifc !== null) {
            //Primero obtenemos ids
            $idCat = obtenerIDByName($conexion, 'categorias', $filtroCat);
            $idDifc = obtenerIDByName($conexion, 'dificultades', $filtroDifc);

            $sqlTotalFilas = "SELECT COUNT(*) FROM " . $tabla . " 
                                WHERE categoria_id= :cat 
                                AND dificultad_id= :difc 
                                ORDER BY id ASC";
            $stmtTotalFIlas = $conexion->prepare($sqlTotalFilas);
            $stmtTotalFIlas->execute([
                ":difc" => $idDifc
            ]);

            //Buscamos en la tabla los que sean con esos ids
            $sql = "SELECT * FROM " . $tabla . " 
                        WHERE categoria_id= :cat 
                        AND dificultad_id= :difc 
                        ORDER BY id ASC 
                        LIMIT :limite
                        OFFSET :offset";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(":cat", $idCat);
            $stmt->bindValue(":difc", $idDifc);

        } else if ($filtroCat !== null && $filtroDifc === null) {
            //Primero obtenemos id
            $idCat = obtenerIDByName($conexion, 'categorias', $filtroCat);

            $sqlTotalFilas = "SELECT COUNT(*) FROM " . $tabla . " 
                                WHERE categoria_id= :cat 
                                ORDER BY id ASC";
            $stmtTotalFIlas = $conexion->prepare($sqlTotalFilas);
            $stmtTotalFIlas->execute([
                ":cat" => $idCat
            ]);

            //Buscamos en la tabla los que sean con ese id
            $sql = "SELECT * FROM " . $tabla . " 
                        WHERE categoria_id= :cat 
                        ORDER BY id ASC 
                        LIMIT :limite
                        OFFSET :offset";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(":cat", $idCat);

        } else if ($filtroCat === null && $filtroDifc !== null) {
            //Primero obtenemos id
            $idDifc = obtenerIDByName($conexion, 'dificultades', $filtroDifc);

            $sqlTotalFilas = "SELECT COUNT(*) FROM " . $tabla . " 
                                WHERE dificultad_id= :difc 
                                ORDER BY id ASC";
            $stmtTotalFIlas = $conexion->prepare($sqlTotalFilas);
            $stmtTotalFIlas->execute([
                ":difc" => $idDifc
            ]);

            //Buscamos en la tabla los que sean con ese id
            $sql = "SELECT * FROM " . $tabla . " 
                        WHERE dificultad_id= :difc 
                        ORDER BY id ASC 
                        LIMIT :limite
                        OFFSET :offset";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(":difc", $idDifc);

        } else {
            $sqlTotalFilas = "SELECT COUNT(*) FROM " . $tabla . " 
                                ORDER BY id ASC";
            $stmtTotalFIlas = $conexion->prepare($sqlTotalFilas);
            $stmtTotalFIlas->execute();

            $sql = "SELECT * FROM " . $tabla . " 
                        ORDER BY id ASC 
                        LIMIT :limite
                        OFFSET :offset";
            $stmt = $conexion->prepare($sql);
        }

        $filasTotal = $stmtTotalFIlas->fetchColumn();
        $totalPaginas = ceil($filasTotal / $porPagina);

        $stmt->bindValue(":limite", $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($filas)) {
            echo "<tr>";
            echo "<td colspan='11'>No se han encontrado resultados.</td>";
            echo "</tr>";
        } else {
            // PINTAMOS LA TABLA
            foreach ($filas as $fila) { ?>
                <tr>
                    <td><?= $fila['titulo'] ?></td>
                    <td><?= $fila['respuesta_correcta'] ?></td>
                    <td><?= $fila['respuesta_A'] ?></td>
                    <td><?= $fila['respuesta_B'] ?></td>
                    <td><?= $fila['respuesta_C'] ?></td>
                    <td><?= $fila['respuesta_D'] ?></td>
                    <td>
                        <?php 
                        if($filtroCat !== null) { 
                            echo "" . $filtroCat;
                        } else {
                            echo obtenerNombreById($conexion, "categorias", $fila['categoria_id']);
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if($filtroDifc !== null) {
                            echo "" . $filtroDifc;
                        } else {
                            echo obtenerNombreById($conexion, "dificultades", $fila['dificultad_id']);
                        }
                        ?>
                    </td>
                    <td><?= obtenerNombreById($conexion, 'users', $fila['autor_id'], 'nick') ?></td>
                    <td><?= str_replace(['-', ' '], ['/', ' - '], $fila['fecha_creacion']) ?></td>
                    <td>
                        <div class="btnAcciones">
                            <a href="editarPregunta.php?id=<?= $fila['id'] ?>" class="btn btnEditar"><i class="fa-solid fa-file-pen"></i> <span>Editar</span></a>
                            <a href="borrarPregunta.php?id=<?= $fila['id'] ?>" class="btn btnBorrar"><i class="fa-solid fa-file-circle-xmark"></i> <span>Borrar</span></a>
                        </div>
                    </td>
                </tr>
            <?php
            }
        }

        return $totalPaginas;
    }
?>
