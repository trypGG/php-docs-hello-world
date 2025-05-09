<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lectura y Voltaje - Particle</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-doughnutlabel@1.0.0"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 2em;
      background: #f5f5f5;
    }
    .gauge-container {
      display: flex;
      justify-content: center;
      gap: 3em;
      flex-wrap: wrap;
    }
    .gauge {
      width: 250px;
      height: 250px;
    }
    h1 {
      margin-bottom: 1em;
    }
    .value-display {
      font-size: 1.2em;
      margin-top: -1em;
    }
  </style>
</head>
<body>
  <h1>Lectura de Sensor Particle</h1>
  <div class="gauge-container">
    <div>
      <h3>Lectura Cruda</h3>
      <canvas id="gaugeLectura" class="gauge"></canvas>
      <div class="value-display" id="lecturaValor">ADC: 0</div>
    </div>
    <div>
      <h3>Voltaje Estimado</h3>
      <canvas id="gaugeVoltaje" class="gauge"></canvas>
      <div class="value-display" id="voltajeValor">Voltaje: 0.00 V</div>
    </div>
  </div>

  <script>
    const MAX_ADC = 4095;
    const MAX_VOLT = 3.3;

    const lecturaConfig = {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [0, MAX_ADC],
          backgroundColor: ['#ff6384', '#eaeaea'],
          borderWidth: 0
        }]
      },
      options: {
        rotation: -90,
        circumference: 180,
        cutout: '70%',
        plugins: {
          doughnutlabel: {
            labels: [{
              text: '0',
              font: { size: '24' },
              color: '#333'
            }, {
              text: 'ADC'
            }]
          }
        }
      }
    };

    const voltajeConfig = {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [0, MAX_VOLT],
          backgroundColor: ['#36a2eb', '#eaeaea'],
          borderWidth: 0
        }]
      },
      options: {
        rotation: -90,
        circumference: 180,
        cutout: '70%',
        plugins: {
          doughnutlabel: {
            labels: [{
              text: '0.00',
              font: { size: '24' },
              color: '#333'
            }, {
              text: 'Volts'
            }]
          }
        }
      }
    };

    const ctxLectura = document.getElementById('gaugeLectura').getContext('2d');
    const ctxVoltaje = document.getElementById('gaugeVoltaje').getContext('2d');
    const chartLectura = new Chart(ctxLectura, lecturaConfig);
    const chartVoltaje = new Chart(ctxVoltaje, voltajeConfig);

    const lecturaValor = document.getElementById('lecturaValor');
    const voltajeValor = document.getElementById('voltajeValor');

    async function actualizarDatos() {
      try {
        const response = await fetch('https://api.particle.io/v1/devices/0a10aced202194944a055b7c/lectura', {
          headers: {
            Authorization: 'Bearer 18127b0957eaae3d1964daec30548dc867e87367'
          }
        });
        const data = await response.json();
        const lectura = data.result;
        const voltaje = (lectura / MAX_ADC) * MAX_VOLT;

        // Actualiza gauge de lectura
        chartLectura.data.datasets[0].data[0] = lectura;
        chartLectura.data.datasets[0].data[1] = MAX_ADC - lectura;
        chartLectura.options.plugins.doughnutlabel.labels[0].text = lectura.toString();
        chartLectura.update();

        // Actualiza gauge de voltaje
        chartVoltaje.data.datasets[0].data[0] = voltaje;
        chartVoltaje.data.datasets[0].data[1] = MAX_VOLT - voltaje;
        chartVoltaje.options.plugins.doughnutlabel.labels[0].text = voltaje.toFixed(2);
        chartVoltaje.update();

        // Actualiza valores numéricos en pantalla
        lecturaValor.textContent = `ADC: ${lectura}`;
        voltajeValor.textContent = `Voltaje: ${voltaje.toFixed(2)} V`;
      } catch (error) {
        console.error('Error al obtener los datos:', error);
      }
    }

    actualizarDatos(); // Llamada inicial
    setInterval(actualizarDatos, 1000); // Cada 1 segundo
  </script>
</body>
</html>
