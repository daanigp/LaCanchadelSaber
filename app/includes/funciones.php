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
     * Genera filas para una tabla html
     * @param PDO $conexion
     * @param String $tabla
     * @param String $filtroCat (por defecto = null)
     * @param String $filtroDifc (por defecto = null)
     */
    function generarTabla($conexion, $tabla, $filtroCat = null, $filtroDifc = null) {
        if($filtroCat === "TODAS") {
            $filtroCat = null;
        }
        if($filtroDifc === "TODAS") {
            $filtroDifc = null;
        }

        if ($filtroCat !== null && $filtroDifc !== null) {
            //Primero obtenemos ids
            $idCat = obtenerIDByName($conexion, 'categorias', $filtroCat);
            $idDifc = obtenerIDByName($conexion, 'dificultades', $filtroDifc);

            //Buscamos en la tabla los que sean con esos ids
            $sql = "SELECT * FROM " . $tabla . " WHERE categoria_id= :cat AND dificultad_id= :difc ORDER BY puntuacion DESC";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":cat" => $idCat,
                ":difc" => $idDifc
            ]);

        } else if ($filtroCat !== null && $filtroDifc === null) {
            //Primero obtenemos id
            $idCat = obtenerIDByName($conexion, 'categorias', $filtroCat);

            //Buscamos en la tabla los que sean con ese id
            $sql = "SELECT * FROM " . $tabla . " WHERE categoria_id= :cat ORDER BY puntuacion DESC";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":cat" => $idCat
            ]);

        } else if ($filtroCat === null && $filtroDifc !== null) {
            //Primero obtenemos id
            $idDifc = obtenerIDByName($conexion, 'dificultades', $filtroDifc);

            //Buscamos en la tabla los que sean con ese id
            $sql = "SELECT * FROM " . $tabla . " WHERE dificultad_id= :difc ORDER BY puntuacion DESC";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":difc" => $idDifc
            ]);

        } else {
            $sql = "SELECT * FROM " . $tabla . " ORDER BY puntuacion DESC";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
        }

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
                    <td><?= str_replace(['-', ' '], ['/', ' - '], $fila['fecha']) ?></td>
                </tr>
            <?php
            }
        }
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
     */
    function tablaHistorialPartidas($conexion, $userId){
        $sql = "SELECT * FROM partidas WHERE id_user= :id ORDER BY puntuacion DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":id" => $userId
        ]);

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
                    <td><?= obtenerNombreById($conexion, "categorias", $fila['categoria_id']) ?></td>
                    <td><?= $fila['puntuacion'] ?></td>
                    <td><?= str_replace(['-', ' '], ['/', ' - '], $fila['fecha']) ?></td>
                </tr>
            <?php
            }
        }
    }
?>
