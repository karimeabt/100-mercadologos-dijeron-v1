<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>100 Mercadologos Dijeron</title>
    <style>
        body {
            background-color: #AD4093;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        .spin-image {
            width: 600px;
            height: 600px;
            background-image: url('img/100mercadologosdijeron.png');
            background-size: cover;
            background-position: center;
            animation: spin 5s linear infinite;
        }

        .button-container {
            margin-top: 20px;
        }

        .play-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #FFFFFF;
            color: #AD4093;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        @keyframes spin {
            from { transform: rotateY(0deg); }
            to { transform: rotateY(360deg); }
        }

        .lights-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .light {
            width: 30px; /* Tamaño más grande */
            height: 30px; /* Tamaño más grande */
            background-color: #FFD700;
            border-radius: 50%;
            opacity: 0.5;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .light:nth-child(odd) {
            animation-delay: 0.5s;
        }

        .message {
            position: absolute;
            top: 20px;
            background: #FFFFFF;
            color: #AD4093;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .table-container {
            position: absolute;
            top: 150px;
            width: 80%;
            max-width: 600px;
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .ganador {
            position: absolute;
            top: 100px;
            background: #FFFFFF;
            color: #AD4093;
            padding: 10px;
            border-radius: 5px;
        }
        .p1 {
        
            top: 100px;
            background: #FFFFFF;
            color: #AD4093;
            padding: 10px;
            border-radius: 5px;
        }
        .p2 {
           
            top: 100px;
            background: #FFFFFF;
            color: #AD4093;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="lights-container">
        <!-- Las luces se generarán con JavaScript -->
    </div>
    <div class="spin-image"></div>
    <div class="button-container">
        <button class="play-button" onclick="redirectToGame()">Jugar de nuevo</button>
    </div>

    <div class="message" id="message">Haz clic aquí para comenzar el audio</div>
    <div class="container text-center">
            <div class="row align-items-center">
            <div class="col-4">
                    <div class="p1" id="p1">Puntos Equipo 1: </div>
                </div>
                <div class="col-4">
                    <div class="ganador" id="ganador">Ganador: </div>
                </div>
                <div class="col-4">
                    <div class="p2" id="p2">Puntos Equipo 2: </div>
                </div>
            </div>
        </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ronda</th>
                    <th>Equipo</th>
                    <th>Puntos</th>
                </tr>
            </thead>
            <tbody id="resultTableBody">
                <!-- Filas de la tabla se llenarán con JavaScript -->
            </tbody>
        </table>
        
    </div>

    <script>
        function redirectToGame() {
            // Redirige a otra página (puedes cambiar 'pagina-de-juego.html' a la URL de tu elección)
            window.location.href = 'index.php';
        }

        // Generar luces alrededor del perímetro
        const lightsContainer = document.querySelector('.lights-container');
        const numLights = 100; // Reducir el total de luces
        const lightsPerSide = numLights / 4; // Luces por lado del perímetro
        const sides = ['top', 'right', 'bottom', 'left'];

        sides.forEach(side => {
            for (let i = 0; i < lightsPerSide; i++) {
                const light = document.createElement('div');
                light.classList.add('light');
                light.style.position = 'absolute';
                light.style[side] = '0';

                if (side === 'top' || side === 'bottom') {
                    light.style.left = `${(i / lightsPerSide) * 100}%`;
                } else {
                    light.style.top = `${(i / lightsPerSide) * 100}%`;
                }

                lightsContainer.appendChild(light);
            }
        });

        // Reproducir audio tras la primera interacción del usuario
        const audio = new Audio('sounds/intro.mp3');
        audio.loop = true;

        document.getElementById('message').addEventListener('click', function() {
            audio.play();
            this.style.display = 'none'; // Ocultar el mensaje después de hacer clic
        });

        // Función para cargar datos y determinar el equipo ganador
        async function loadResults() {
            try {
                const response = await fetch('functions/getResultado.php'); // Cambia esta URL a tu API o fuente de datos
                const data = await response.json();

                const resultTableBody = document.getElementById('resultTableBody');
                const teamSelect = document.getElementById('teamSelect');

                let e1Score = 0;
                let e2Score = 0;
                let winningTeam = '';

                data.results.forEach(result => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${result.ronda_idronda}</td>
                        <td>${result.equipo_idequipo}</td>
                        <td>${result.score_resultado}</td>
                    `;
                    resultTableBody.appendChild(row);
                    //Suma de puntuaciones por equipo
                    if (result.equipo_idequipo ===1) {
                        e1Score = e1Score+result.score_resultado;
                        console.log(e1Score)
                     }else{
                        if (result.equipo_idequipo ===2) {
                        e2Score = e2Score+result.score_resultado;
                        console.log(e2Score)
                        }
                     }
                    
                });
                 //verificar que equipo tiene más score
                    if (e2Score>e1Score){
                        winningTeam = "Equipo 2";
                     }else{
                        if(e1Score>e2Score){
                        winningTeam = "Equipo 1";
                        } 
                     }

                     //Colocar el ganador en un input
                     document.getElementById('ganador').textContent = 'Ganador: '+winningTeam;
                     document.getElementById('p1').textContent = 'Puntos Equipo 1: '+e1Score;
                     document.getElementById('p2').textContent = 'Puntos Equipo 2: '+e2Score;

            } catch (error) {
                console.error('Error loading results:', error);
            }
        }

        loadResults();

        
    </script>
</body>
</html>
