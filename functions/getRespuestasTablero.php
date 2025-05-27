<?php

$ronda_idronda = $_GET['ronda_idronda'];
include('../functions/selects.php');

header('Content-Type: text/html');

try {
    if (isset($_GET['ronda_idronda'])) {
        $ronda_idronda = intval($_GET['ronda_idronda']);
        $html = getRespuestasTablero($ronda_idronda);
        if ($html) {
            echo $html;
        } else {
            echo '<div class="error">No se encontraron datos para ronda_idronda = ' . htmlspecialchars($ronda_idronda) . '</div>';
        }
    } else {
        echo '<div class="error">Parámetro ronda_idronda no proporcionado</div>';
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo '<div class="error">Ocurrió un error en el servidor</div>';
}
?>
