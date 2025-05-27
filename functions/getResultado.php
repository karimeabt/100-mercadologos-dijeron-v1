<?php

include('../connection/connection.php');

header('Content-Type: application/json');

try {
    $query = "SELECT * FROM resultado WHERE DATE(hora_resultado) = CURDATE() ORDER BY ronda_idronda LIMIT 10";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devolver los resultados como JSON
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'results' => $resultado
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'results' => []
        ]);
    }
} catch (PDOException $e) {
    // Registrar el error y devolver un mensaje de error en JSON
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al realizar la consulta'
    ]);
}
