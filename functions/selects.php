<?php
function getRonda($idronda) {
    include('../connection/connection.php');
    
    try {
        $query = "SELECT * FROM ronda WHERE idronda = :idronda";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':idronda', $idronda, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($stmt->rowCount() > 0) {
            return $resultado[0]; // Return the first row as an associative array
        } else {
            return null;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

/*
function getRespuestasTablero($ronda_idronda){
    include('../connection/connection.php');
    $query = "SELECT * FROM respuesta WHERE ronda_idronda = :ronda_idronda ORDER BY popularidad_respuesta ASC";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    $datos = array();
    $filtered_rows = $stmt->rowCount(); 
    foreach($resultado as $fila){
        ?>
                <div class="col-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="<?php echo $fila['idrespuesta']; ?>" id="<?php echo $fila['popularidad_respuesta']; ?>" name="respuesta">
                        <label class="form-check-label" for="flexCheckDefault">
                        Respuesta <?php echo $fila['popularidad_respuesta']; ?>
                        </label>
                    </div>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" id="descripcionRespuesta<?php echo $fila['popularidad_respuesta']; ?>" value="<?php echo $fila['descripcion_respuesta']; ?>">
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" id="puntosRespuesta<?php echo $fila['popularidad_respuesta']; ?>" value="<?php echo $fila['puntos_respuesta']; ?>">
                </div>
                <br>
        <?php
}
}
*/
function getRespuestasTablero($ronda_idronda){
    include('../connection/connection.php');
    $query = "SELECT * FROM respuesta WHERE ronda_idronda = :ronda_idronda ORDER BY popularidad_respuesta ASC";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':ronda_idronda', $ronda_idronda, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    
    $html = ''; // Usamos una variable para acumular el HTML
    foreach($resultado as $fila){
        $html .= '<div class="col-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="' . htmlspecialchars($fila['popularidad_respuesta']) . '" id="' . htmlspecialchars($fila['popularidad_respuesta']) . '" name="respuesta">
                        <label class="form-check-label" for="flexCheckDefault">
                        Respuesta ' . htmlspecialchars($fila['popularidad_respuesta']) . '
                        </label>
                    </div>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" id="descripcionRespuesta' . htmlspecialchars($fila['popularidad_respuesta']) . '" value="' . htmlspecialchars($fila['descripcion_respuesta']) . '">
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" id="puntosRespuesta' . htmlspecialchars($fila['popularidad_respuesta']) . '" value="' . htmlspecialchars($fila['puntos_respuesta']) . '">
                </div>
                <br>';
    }
    return $html; // Devolver el HTML generado
}
function getRespuestas($ronda_idronda){
    include('connection/connection.php');
    $query = "SELECT * FROM respuesta WHERE ronda_idronda = :ronda_idronda ORDER BY popularidad_respuesta ASC";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':ronda_idronda', $ronda_idronda, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        ?>

        <!-- Respuesta inicial -->
        <div class="answer" id="answer<?php echo $fila['popularidad_respuesta']; ?>">
            <span class="answer-number">Respuesta <?php echo $fila['popularidad_respuesta']; ?></span>
        </div>
        <!-- Respuesta modificada (inicialmente oculta) -->
        <div class="answer" id="answer<?php echo $fila['popularidad_respuesta']; ?>modified" style="display: none;">
            <span class="answer-number"><?php echo $fila['popularidad_respuesta']; ?></span>
            <span class="typing-animation"><?php echo $fila['descripcion_respuesta']; ?></span>
        </div>
        <br>
        <?php
    }
}

function getRespuestas1($ronda_idronda){
    include('../connection/connection.php');
    $query = "SELECT * FROM respuesta WHERE ronda_idronda = :ronda_idronda ORDER BY popularidad_respuesta ASC";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':ronda_idronda', $ronda_idronda, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        ?>

        <!-- Respuesta inicial -->
        <div class="answer" id="answer<?php echo $fila['popularidad_respuesta']; ?>">
            <span class="answer-number"><?php echo $fila['popularidad_respuesta']; ?></span>
        </div>
        <!-- Respuesta modificada (inicialmente oculta) -->
        <div class="answer" id="answer<?php echo $fila['popularidad_respuesta']; ?>modified" style="display: none;">
            <span class="answer-number"><?php echo $fila['popularidad_respuesta']; ?></span>
            <span class="typing-animation"><?php echo "     ".$fila['descripcion_respuesta']."...........".$fila['puntos_respuesta']; ?></span>
            <input type="text" class="form-control" id="puntosrespuesta<?php echo $fila['popularidad_respuesta']; ?>" value="<?php $fila['puntos_respuesta']; ?>" style="display: none;">      
        </div>
        <br>
        <?php
    }
}


?>