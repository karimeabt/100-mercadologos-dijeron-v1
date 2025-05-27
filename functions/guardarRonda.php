<?php

function guardarRonda($ronda_idronda, $score_resultado, $strickes_resultado, $equipo_idequipo) {
    include('../connection/connection.php');
    //rondaId, score_resultado, stricke_resultado, equipoId
    //`idresultado`, `score_resultado`, `strickes_resultado`, `ronda_idronda`, `equipo_idequipo`
    $query = "INSERT INTO resultado (score_resultado, strickes_resultado, ronda_idronda, equipo_idequipo) 
              VALUES (:score_resultado, :strickes_resultado, :ronda_idronda, :equipo_idequipo)";
              
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':score_resultado', $score_resultado, PDO::PARAM_INT);
    $stmt->bindParam(':strickes_resultado', $strickes_resultado, PDO::PARAM_INT);
    $stmt->bindParam(':ronda_idronda', $ronda_idronda, PDO::PARAM_INT);
    $stmt->bindParam(':equipo_idequipo', $equipo_idequipo, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return "Datos insertados correctamente.";
    } else {
        return "Error al insertar datos.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $ronda_idronda = $data['ronda_idronda'];
    $score_resultado = $data['score_resultado'];
    $strickes_resultado = $data['strickes_resultado'];
    $equipo_idequipo = $data['equipo_idequipo'];

    echo guardarRonda($ronda_idronda, $score_resultado, $strickes_resultado, $equipo_idequipo);
}
?>