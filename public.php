<?php 
include 'functions/selects.php';
$currentRonda = 1; // Inicialmente, la primera ronda
//$respuestas = getRespuestas($currentRonda);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuestas y Puntos</title>
    <link rel="stylesheet" href="styles/respuestas_2.css">
    <style>
        /*----------------------Prueba de mensaje---------------------*/
        #messageDiv {
          display: none;
          background-color: lightblue;
          padding: 10px;
          margin-top: 10px;
        }

        /*----------------------TACHA---------------------*/
        #tacha-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10;
            display: none;
        }
        
        .hidden {
            display: none;
        }
        
        .tacha {
            width: 80%;
            height: 80%;
            background-color: transparent;
            position: relative;
            display: none;
        }
        
        .tacha::before, .tacha::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 50px;
            background-color: red;
        }
        
        .tacha::before {
            transform: translate(-50%, -50%) rotate(45deg);
        }
        
        .tacha::after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }
        .answer-container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .points-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }
        .points {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        .answer {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center; /* Centra horizontalmente */
            position: relative; /* Necesario para la animación */
        }
        .answer-number {
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border-radius: 50%;
        }
        .typing-animation {
            overflow: hidden; /* Oculta el exceso de texto que sale del contenedor */
            white-space: nowrap; /* Evita el salto de línea del texto */
            animation: typing 1s steps(10, end); /* Animación de escritura */
        }
        @keyframes typing {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        /*LUCES PARA EL CONTENEDOR DE RESPUESTAS*/
       

      </style>
</head>
<body>
    <div id="messageDiv">Hola esto funciona</div>
    <div class="answer-container">
       
        <!-- Puntos totales -->
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col-4">
                    <div class="points" id="totalPoints">Total de puntos: 0</div>
                </div>
            </div>
        </div>
        <div class="points-container">
            <div class="points" id="team1Points">0</div>
            <div class="points" id="team2Points">0</div>
        </div>
        <!-- # Ronda  -->
        <div class="ronda-info">
             <!-- <h1 id="numero_ronda">Número de Ronda:</h1>-->
            <h1 id="descripcion_ronda">Descripción de Ronda:</h1>
        </div>
        <!-- Respuestas dinámicas -->
        <div class="answers" id="answers">
        </div>
        <!-- Audio para respuesta-->
        <audio id="audio_respuesta_correcta">
        <source src="sounds/correcto.mp3" type="audio/mpeg">
        Tu navegador no soporta el elemento de audio.
        </audio>
    </div>

    <!-- INICIO Stricke-->
    <audio id="audio_stricke">
        <source src="sounds/error.mp3" type="audio/mpeg">
        Tu navegador no soporta el elemento de audio.
    </audio>
    <div id="tacha-container" class="hidden">
        <div id="tacha" class="tacha"></div>
    </div>
    <!-- FIN Stricke-->

    <!-- Audio para ganar-->
    <audio id="audio_ganar">
        <source src="sounds/ganar.mp3" type="audio/mpeg">
        Tu navegador no soporta el elemento de audio.
    </audio>
     
    <script>
      
        //WEBSOCKETS
    const ws = new WebSocket('ws://localhost:8080');

    ws.onopen = function() {
      console.log('Connected to server');
    };
    

    ws.onmessage = function(event) {
    try {
        const action = JSON.parse(event.data);
        console.log('Received from server:', action);

        if (action.type === 'terminar_juego') {
            //document.getElementById('messageDiv').style.display = 'block';
            window.location.href = 'resultado.php';
        }

        if (action.type === 'buttonClicked') {
            const tachaContainer = document.getElementById('tacha-container');
            const tacha = document.getElementById('tacha');
            const audio = document.getElementById('audio_stricke');

            // Mostrar el div de la tacha
            tachaContainer.style.display = 'flex';
            tacha.style.display = 'flex';

            // Reproducir sonido
            audio.play();

            // Ocultar la tacha después de 2 segundos
            setTimeout(() => {
                tachaContainer.style.display = 'none';
                tacha.style.display = 'none';
            }, 2000);
        }

        if (action.type === 'siguienteRonda') {
            nextRonda();
            document.getElementById('totalPoints').textContent = 'Total de puntos: 0';
            document.getElementById('team1Points').textContent = '0';
            document.getElementById('team2Points').textContent = '0';
        }
        
        if (action.type === 'mostrarRespuesta') {
            toggleAnswerEquipo(action.respuesta, action.equipo, action.puntos1, action.puntos2, action.gano);
            if(action.gano==1){
                const audio_ganar = document.getElementById('audio_ganar');
                audio_ganar.play();
                
            }
    }
        
    } catch (e) {
        console.error("Error parsing JSON:", e);
    }
};
    ws.onclose = function() {
      console.log('Disconnected from server');
    };

    ws.onerror = function(error) {
      console.error('WebSocket error:', error);
    };

    
    
    let currentRonda = <?php echo $currentRonda; ?>;

    function loadRonda() {
        fetch('ajax/getRonda.php?idronda=' + currentRonda)
            .then(response => response.json())
            .then(data => {
                if (data) {
                   // document.getElementById('numero_ronda').innerText = 'Número de Ronda: ' + data.numero_ronda;
                    document.getElementById('descripcion_ronda').innerText =  data.descripcion_ronda;
                } else {
                    document.getElementById('numero_ronda').innerText = 'No se encontraron datos para idronda = ' + currentRonda;
                    document.getElementById('descripcion_ronda').innerText = '';
                }

                // Actualizar las respuestas
                fetch('ajax/getSiguientesRespuestas.php?ronda_idronda=' + currentRonda)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('answers').innerHTML = html;
                    })
                    .catch(error => console.error('Error:', error));
            })
            .catch(error => console.error('Error:', error));
    }

    function nextRonda() {
        currentRonda++;
        loadRonda();
    }

    // Cargar la primera ronda al cargar la página
    document.addEventListener('DOMContentLoaded', (event) => {
        loadRonda();
    });

    function toggleAnswerEquipo(respuesta, equipo, puntos1, puntos2) {
            var answer = document.getElementById(`answer${respuesta}`);
            var answerModified = document.getElementById(`answer${respuesta}modified`);

            if (!answer || !answerModified) {
                console.error('Elementos no encontrados en el DOM');
                return;
            }

            answer.style.display = 'none';
            answerModified.style.display = 'flex';

            var typingElement = answerModified.querySelector('.typing-animation');
            typingElement.classList.add('typing');
            const audio = document.getElementById('audio_respuesta_correcta');
            audio.play();

            setTimeout(function() {
                typingElement.classList.remove('typing');
                team1Points = puntos1; 
                team2Points = puntos2; 
                //equipoPoints = equipo;
                document.getElementById(`team1Points`).textContent = team1Points;
                document.getElementById(`team2Points`).textContent = team2Points;
                document.getElementById('totalPoints').textContent = 'Total de puntos: ' + (team1Points + team2Points);
            }, 2000);
        }

      
        

    </script>
</body>
</html>
