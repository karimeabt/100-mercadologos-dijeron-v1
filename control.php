<?php
include('functions/selects.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Tablero de Control</title>

</head>
<body>
<form method="POST" action="public.php">
  <div class="container">
    <!-- Tres primeras columnas del tablero -->
    <div class="container text-center">
      <div class="row align-items-start">
        <div class="col-4">
          
        </div>
        <div class="col-4">
          <h1>Tablero</h1>
        </div>
        <div class="col-4">
          
        </div>
      </div>
    </div>
    <!-- Termina aquí -->

    <!-- Tres segundas columnas del tablero -->
    <div class="container text-center">
      <div class="row align-items-start">
        <div class="col">
          <div class="form-check">
            <input class="form-check-input" type="radio" value="1" id="e1" name="equipo">
            <label class="form-check-label" for="flexCheckDefault">
              Equipo 1
            </label>
          </div>
          <br>
          <div class="form-check">
            <input class="form-check-input" type="radio" value="2" id="e2" name="equipo">
            <label class="form-check-label" for="flexCheckChecked">
              Equipo 2
            </label>
          </div>
        </div>
        <div class="col">
          <input type="text" class="form-control" id="puntosequipo1" value="0">
          <br>
          <input type="text" class="form-control" id="puntosequipo2"  value="0">
        </div>
        <div class="col">
          <input type="text" class="form-control" id="strickesequipo1" value="0">
          <br>
          <input type="text" class="form-control" id="strickesequipo2" value="0">
        </div>
      </div>
    </div>
    <!-- Termina aquí -->
<br>
    <!-- Tres terceras columnas del tablero -->
    <div class="container text-center">
      <div class="row align-items-start">
        <div class="col-4">
          
        </div>
        <div class="col-4">
          <h1>Ronda</h1>
        </div>
        <div class="col-4">
          
        </div>
      </div>
    </div>
    <!-- Termina aquí -->

    <!-- Tres cuartas columnas del tablero -->
    <div class="container text-center">
      <div class="row align-items-start">

        <!-- Columna para popner las respuestas en el tablero -->
      <div id="resultContainer"></div>
        <?php //getRespuestasTablero($ronda_idronda) ?>
        
      </div>
    </div>
    <br>
    <!-- Termina aquí -->
    <button type="button" id="boton_tacha" class="btn btn-danger">Strick</button>
    <button type="button"  id="boton_mostrar" class="btn btn-primary">Mostrar respuesta</button>
    <button type="button" id="boton_siguiente" class="btn btn-success">Siguiente ronda</button>
    <button type="button" id="terminar_juego" class="btn btn-success">Terminar Juego</button>
    
  <!--<button id="sendButton">Send Message</button>-->
  </div>
  <script>
    let rondaId = 1;
    const ws = new WebSocket('ws://localhost:8080');

    ws.onopen = function() {
      console.log('Connected to server');
    };

    ws.onmessage = function(event) {
      console.log('Received from server:', event.data);
    };

    ws.onclose = function() {
      console.log('Disconnected from server');
    };

    ws.onerror = function(error) {
      console.error('WebSocket error:', error);
    };

    /*document.getElementById('sendButton').addEventListener('click', function() {
      const action = {
        type: 'show_message'
      };
      ws.send(JSON.stringify(action));
    });*/

    document.getElementById('terminar_juego').addEventListener('click', () => {
      const action = {
        type: 'terminar_juego'
      };
      ws.send(JSON.stringify(action));
    });

    document.getElementById('boton_tacha').addEventListener('click', () => {
      // Ver qué equipo está seleccionado para los strikes
      const equipoSeleccionado = document.querySelector('input[name="equipo"]:checked').value;
      // Obtener el input correspondiente al equipo seleccionado
      let inputElement = document.getElementById(`strickesequipo${equipoSeleccionado}`);
      // Convertir el valor del input a número entero
      let stricke = parseInt(inputElement.value, 10);
      stricke += 1;
      if (stricke >= 3) {
        alert('Oportunidad para el otro equipo');
        inputElement.value = stricke;
        console.log(stricke);
      } else {
        inputElement.value = stricke;
        console.log(stricke);
      }

      const action = {
        type: 'buttonClicked',
        strikes: stricke
      };
      ws.send(JSON.stringify(action));
    });


    document.getElementById('boton_siguiente').addEventListener('click', () => {
      guardarRonda();
      rondaId++;
      callPHPFunction();
      
      document.getElementById('puntosequipo1').value = '0';
      document.getElementById('puntosequipo2').value = '0';
      document.getElementById('strickesequipo1').value = '0';
      document.getElementById('strickesequipo2').value = '0';
        const action = {
            type: 'siguienteRonda'
        };
        ws.send(JSON.stringify(action));
    });

    //Mostrar respuesta 
    document.getElementById('boton_mostrar').addEventListener('click', () => {
      sumar(rondaId);
        
    });


    let respuestasPorRonda = {};

function sumar(rondaId) {
    const equipoSeleccionado = document.querySelector('input[name="equipo"]:checked');
    const respuestaSeleccionada = document.querySelector('input[name="respuesta"]:checked');

    if (equipoSeleccionado && respuestaSeleccionada) {
        // id del equipo seleccionado
        const equipoId = equipoSeleccionado.value;
        // puntos del equipo seleccionado
        const puntos = document.getElementById(`puntosequipo${equipoId}`).value;
        // id de la respuesta seleccionada
        const respuestaId = respuestaSeleccionada.id;
        // Inicializa el array para la ronda si no existe
        if (!respuestasPorRonda[rondaId]) {
            respuestasPorRonda[rondaId] = [];
        }

        if (respuestasPorRonda[rondaId].includes(respuestaId)) {
            alert('Esta respuesta ya ha sido seleccionada previamente.');
            return;
        } else {
            respuestasPorRonda[rondaId].push(respuestaId);
            console.log(respuestasPorRonda[rondaId]);
        }

        // Si ya son 4 respuestas reproducimos sonido de ganar y pasamos a siguiente ronda
        let gano = 0;
        if (respuestasPorRonda[rondaId].length == 4) {
            gano = 1;
            console.log(respuestasPorRonda[rondaId].length);
        } else {
            console.log(respuestasPorRonda[rondaId].length);
        }

        const puntosEquipo = document.getElementById(`puntosequipo${equipoId}`);
        const puntosRespuesta = document.getElementById(`puntosRespuesta${respuestaId}`);
        puntosEquipo.value = parseInt(puntosEquipo.value) + parseInt(puntosRespuesta.value);

        //STRICKES
        // Obtener el input correspondiente al equipo seleccionado
        let inputElement = document.getElementById(`strickesequipo${equipoId}`);
        // Obtener el input de score correspondiente equipo 1
        let score1 = document.getElementById('puntosequipo1').value;
        // Obtener el input de score correspondiente equipo 2
        let score2 = document.getElementById('puntosequipo2').value;
        //Obtener el radiobutton 1
        const radioToSelect1 = document.getElementById('e1');
        const radioToSelect2 = document.getElementById('e2');
        // Convertir el valor del input a número entero
        let stricke = parseInt(inputElement.value, 10);
        if (stricke >= 3) {
          gano = 1;
          if(score1=== null || score1 === '' || score1 === '0'){
            document.getElementById('puntosequipo1').value= score2;
            document.getElementById('puntosequipo2').value= '0';
            radioToSelect1.checked =true;
            radioToSelect2.checked= false;

          }else{
            document.getElementById('puntosequipo2').value= score1;
            document.getElementById('puntosequipo1').value= '0';
            radioToSelect1.checked =false;
            radioToSelect2.checked= true;
          }
        }

        // Enviar información de numero de equipo, respuesta y puntos con websockets
        const equipo = document.querySelector('input[name="equipo"]:checked').value;
        const respuesta = document.querySelector('input[name="respuesta"]:checked').value;
        const puntosRespuesta1 = document.getElementById('puntosequipo1');
        const puntosRespuesta2 = document.getElementById('puntosequipo2');
        const puntos1 = parseInt(puntosRespuesta1.value);
        const puntos2 = parseInt(puntosRespuesta2.value);

        const message = {
            equipo: equipo,
            respuesta: respuesta,
            puntos1: puntos1,
            puntos2: puntos2,
            gano: gano,
            type: 'mostrarRespuesta'
        };

        ws.send(JSON.stringify(message));
    } else {
        alert('Por favor selecciona un equipo y una respuesta.');
    }
}


    
    //Funcion de selects para cambiar las respuestas del tablero
    function callPHPFunction() {
     // Actualizar las respuestas
     fetch('functions/getRespuestasTablero.php?ronda_idronda=' + rondaId)
    .then(response => response.text())
    .then(html => {
        document.getElementById('resultContainer').innerHTML = html;
    })
    .catch(error => console.error('Error:', error));
  }

  //Cargar respuestas cuando la pagina termine de cargar
  document.addEventListener('DOMContentLoaded', (event) => {
    callPHPFunction();
    });

    function guardarRonda(){
      //const ronda_idronda = document.getElementById('ronda_idronda').value;
      const equipoSeleccionado = document.querySelector('input[name="equipo"]:checked');
      let score_resultado =0;
      let strickes_resultado = 0;
      const equipoId = equipoSeleccionado.value;
      const equipo_idequipo = equipoId;
      const ronda_idronda = rondaId;
      if(equipo_idequipo==1){
        score_resultado = document.getElementById('puntosequipo1').value;
        strickes_resultado = document.getElementById('strickesequipo1').value;
      }else{
        score_resultado = document.getElementById('puntosequipo2').value;
        strickes_resultado = document.getElementById('strickesequipo2').value;
      }
      

      fetch('functions/guardarRonda.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ronda_idronda, score_resultado, strickes_resultado, equipo_idequipo })
      })
      .then(response => response.text())
      .then(data => {
        alert(data);
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }
  </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</form>
</body>
</html>
