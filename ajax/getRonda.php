<?php
/*include('../funcitons/selects.php');

if (isset($_GET['idronda'])) {
    $idronda = intval($_GET['idronda']);
    $ronda = getRonda($idronda);
    echo json_encode($ronda);
}
*/
include('../functions/selects.php');

header('Content-Type: application/json');

try {
    if (isset($_GET['idronda'])) {
        $idronda = intval($_GET['idronda']);
        $ronda = getRonda($idronda);
        if ($ronda) {
            echo json_encode($ronda);
        } else {
            echo json_encode(['error' => 'No se encontraron datos para idronda = ' . $idronda]);
        }
    } else {
        echo json_encode(['error' => 'Parámetro idronda no proporcionado']);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['error' => 'Ocurrió un error en el servidor']);
}
?>
