<?php

        $ronda_idronda = $_GET['ronda_idronda'];
        include('../functions/selects.php');

        header('Content-Type: application/json');
        
        try {
            if (isset($_GET['ronda_idronda'])) {
                $ronda_idronda = intval($_GET['ronda_idronda']);
                $respuesta = getRespuestas1($ronda_idronda);
                if ($ronda_idronda) {
                    echo json_encode($ronda_idronda);
                } else {
                    echo json_encode(['error' => 'No se encontraron datos para ronda_idronda = ' . $ronda_idronda]);
                }
            } else {
                echo json_encode(['error' => 'Parámetro ronda_idronda no proporcionado']);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['error' => 'Ocurrió un error en el servidor']);
        }
        ?>

