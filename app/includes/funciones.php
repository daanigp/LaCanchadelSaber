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
        $nombre = 0;
        $sql = "SELECT * FROM " . $tabla;
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        foreach($stmt->fetchAll() as $fila) {
            if($fila['id'] === $filtro) {
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
     * @param String $tabla
     * @param String $filtroDifc (por defecto = null)
     * @param Int $pagina (por defecto = 1)
     * @param Int $porPagina (por defecto = 10)
     */
    function generarTabla($conexion, $tabla, $filtroDifc = null, $pagina = 1, $porPagina = 10) {
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
                <tr>
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
?>
