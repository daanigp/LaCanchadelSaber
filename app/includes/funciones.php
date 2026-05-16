<?php
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
        $sql = "SELECT DISTINCT $columna FROM $tabla ORDER BY $columna";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
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
            ":nombre" => $campo
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
        if ($filtroCat !== null && $filtroDifc !== null) {
            //Primero obtenemos ids
            $idCat = obtenerIDByName($conexion, 'categorias', $filtroCat);
            $idDifc = obtenerIDByName($conexion, 'dificultades', $filtroDifc);

            //Buscamos en la tabla los que sean con esos ids
            $sql = "SELECT * FROM " . $tabla . " WHERE categoria_id= :cat AND dificultad_id= :difc";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":cat" => $idCat,
                ":difc" => $idDifc
            ]);

        } else if ($filtroCat !== null && $filtroDifc === null) {
            //Primero obtenemos id
            $idCat = obtenerIDByName($conexion, 'categorias', $filtroCat);

            //Buscamos en la tabla los que sean con ese id
            $sql = "SELECT * FROM " . $tabla . " WHERE categoria_id= :cat";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":cat" => $idCat
            ]);

        } else if ($filtroCat === null && $filtroDifc !== null) {
            //Primero obtenemos id
            $idDifc = obtenerIDByName($conexion, 'dificultades', $filtroDifc);

            //Buscamos en la tabla los que sean con ese id
            $sql = "SELECT * FROM " . $tabla . " WHERE dificultad_id= :difc";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ":difc" => $idDifc
            ]);

        } else {
            $sql = "SELECT * FROM " . $tabla;
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
        }


        // PINTAMOS LA TABLA
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) { ?>
            <tr>
                <td><?= $fila['puntuacion'] ?></td>
                <td><?= obtenerNombreById($conexion, "users", $fila['id_user'], "nick") ?></td>
                <td>
                    <?php 
                    if($filtroCat !== null) { 
                        echo $filtroCat;
                    } else {
                        echo obtenerNombreById($conexion, "categorias", $fila['categoria_id']);
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if($filtroDifc !== null) {
                        echo $filtroCat;
                    } else {
                        echo obtenerNombreById($conexion, "dificultades", $fila['dificultad_id']);
                    }
                    ?>
                </td>
            </tr>
        <?php
        }
    }
?>
