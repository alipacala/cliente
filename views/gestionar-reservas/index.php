<?php
require "../../inc/header.php";

session_start();

$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado);
?>
<div class="container my-5 main-cont">
 
<style>
  #total-final-label {
      text-align: center;
      font-weight: bold;
  }
  #total-personas
  {
      text-align: center;
      font-weight: bold;
  }
  #adelanto
  {
      text-align: center;
      font-weight: bold;
  }
</style>


<div class="container mt-5">
    <form id="formulario-reservas">
    <h4>FORMULARIO REGISTRO DE RESERVA</h4>
    <div class="row">
        <div class="col-md-4">
        <label for="nro_reserva" class="form-label">Nro Reserva:</label>
        <input type="text" class="form-control" id="nro_reserva" readonly>
        </div>
        <div class="col-md-4">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" id="nombre">
        </div>
        <div class="col-md-4">
        <label for="lugar_de_procedencia" class="form-label">Lugar de Procedencia:</label>
        <input type="text" class="form-control" id="lugar_de_procedencia">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
        <label for="id_modalidad" class="form-label">Modalidad del Cliente:</label>
        <select class="form-select" name="id_modalidad" id="id_modalidad">
        <option value="0">--Seleccione--</option>
        </select>
        </div>
        <div class="col-md-4">
        <label for="fecha_llegada" class="form-label">Fecha de Llegada:</label>
        <input type="date" class="form-control" id="fecha_llegada">
        </div>
        <div class="col-md-4">
        <label for="hora_llegada" class="form-label">Hora de Llegada:</label>
        <input type="time" class="form-control" id="hora_llegada">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
        <label for="tipo_transporte" class="form-label">Tipo Transporte:</label>
        <select class="form-select" name="tipo_transporte" id="tipo_transporte">
        <option value="0">--Seleccione--</option>
        <option value="AUTO">AUTO</option>
        <option value="BUS">BUS</option>
        <option value="AVION">AVION</option>
        <option value="TREN">TREN</option>
        </select>
        </div>
        <div class="col-md-4">
        <label for="telefono" class="form-label">Telefono:</label>
        <input type="text" class="form-control" id="telefono">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
        <label for="observaciones_hospedaje" class="form-label">Observaciones Referente al Hospedaje:</label>
        <input type="text" class="form-control" id="observaciones_hospedaje">
        </div>
        <div class="col-md-4">
        <label for="observaciones_pago" class="form-label">Observaciones Referente al Pago:</label>
        <input type="text" class="form-control" id="observaciones_pago">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
        <label for="provincia" class="form-label">Nro Adultos:</label>
        <input type="text" class="form-control" id="nro_adultos">
        </div>
        <div class="col-md-4">
        <label for="telefono" class="form-label">Nro de Niños:</label>
        <input type="text" class="form-control" id="nro_niños">
        </div>
        <div class="col-md-4">
        <label for="celular" class="form-label">Nro de Infantes:</label>
        <input type="text" class="form-control" id="nro_infantes">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
        <label for="fecha_ingreso" class="form-label">Fecha Ingreso:</label>
        <input type="date" class="form-control" id="fecha_ingreso" readonly>
        </div>
        <div class="col-md-4">
        <label for="fecha_salida" class="form-label">Fecha Salida:</label>
        <input type="date" class="form-control" id="fecha_salida">
        </div>
    </div> 
    </form>
    
  <br>
    <div class="row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <div class="card">
      <div class="card-body">
      <h3 class="mb-4">Preparación de Habitaciones:</h3>
                    <form id="formulario-carrito" class="mb-3">
                    <div class="row">
                        <div class="col-md-2">
                          <label for="tipo">Nro Habitacion</label>
                          <select class="form-select" name="habitacion" id="habitacion">
                              <option value="0">--Seleccione--</option>
                          </select>
                        </div>
                        <div class="col-md-2">
                          <label for="habitacion">Tipo Habitacion:</label>
                          <select class="form-select" name="tipo" id="tipo">
                                <option value="0">--Seleccione--</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                          <label for="tipo">Tipo Precios</label>
                          <select class="form-select" id="selectPrecios">
                              <option value="0">--Seleccione--</option>
                              <option value="precio_venta_01">Precio Normal</option>
                              <option value="precio_venta_02">Precio Coorporativo</option>
                              <option value="precio_venta_03">Precio Cliente Premiun</option>
                          </select>
                        </div>
                        <div class="col-md-2">
                        <label for="personas">Personas:</label>
                        <input type="number" id="personas" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                        <label for="monto">Monto:</label>
                        <input type="number" step="0.01" id="monto" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                        <label for="noches">Noches:</label>
                        <input type="number" id="noches" class="form-control" readonly>
                        </div>
                        
                        <div class="col-md-4">
                        <button type="button" class="btn btn-primary mt-3" onclick="agregarProducto()"><Strong>+</Strong>Agregar Habitacion</button>
                        </div>
                        </form>

                        <table id="carrito" class="table table-striped">
                        <thead>
                            <tr>
                            <th>TIPO</th>
                            <th>Nro.HABITACION</th>
                            <th>PERSONAS</th>
                            <th>MONTO</th>
                            <th>NOCHES</th>
                            <th>TOTAL</th>
                            <th>ACCION</th>
                            </tr>
                        </thead>
                        <tbody id="carrito-body">
                        </tbody>
                        </table>
                        <div class="col-md-4">
                        <input type="hidden" id="lista-habitaciones" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                        <label for="habitacion">Total Personas:</label>
                        <input type="number" id="total-personas" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                        <label for="habitacion">Total Final: S/</label>
                        <input type="text" id="total-final-label" class="form-control" readonly><br>
                        <input type="hidden" id="total2" class="form-control" readonly><br>
                        </div>
                        <hr>
                        <div class="col-md-4">
                        <label for="nombre" class="form-label">Porcentaje:</label>
                        <input type="number" class="form-control" id="porcentaje">
                        </div>
                        <div class="col-md-4">
                        <label for="lugar_de_procedencia" class="form-label">Adelanto:</label>
                        <input type="text" class="form-control" id="adelanto" readonly>
                        <input type="hidden" class="form-control" id="adelanto2" readonly>
                        </div>
                      </div>
      </div>
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <button type="button" class="btn btn-primary mt-3" onclick="RegistrarDatos()">Confirmar Reserva</button>
      </div>
    </div>
    <br>
    <br>
  </div>
  <div class="col-sm-6">
    
  </div>
</div>
</div>
<script>
        // Función para cargar los datos de la API y actualizar los inputs
        function cargarDatos() {
          //obtenemos el numero de registro maestro del forumlario
          var codigo = document.getElementById('nro_registro').value;
          const url = `api-reservas.php?codigo=${codigo}`;
            // Realizar una solicitud HTTP GET a la URL
            fetch(url, {
                  method: 'GET4',
                })
                .then(response => response.json())
                .then(data => {
                    // Actualizar los inputs con los datos de la API
                    document.getElementById('ciudad').value = data[0].lugar_procedencia;
                    document.getElementById('celular').value = data[0].telefono;
                    document.getElementById('nombres').value = data[0].nombre;
                    document.getElementById('fecha_in').value = data[0].fecha_llegada;
                    document.getElementById('fecha_out').value = data[0].fecha_salida;
                    document.getElementById('hora_in').value = data[0].hora_llegada;
                    
                })
                .catch(error => console.error('Error:', error));
        }

        // Llamar a la función cargarDatos cuando la página se cargue
        window.addEventListener('load', cargarDatos);
    </script>
<script>
  const inputFecha1 = document.getElementById('fecha_llegada');
  const inputFecha2 = document.getElementById('fecha_ingreso');

  // Agregar un evento al cambio del primer input date
  inputFecha1.addEventListener('input', () => {
    // Obtener el valor del primer input date
    const fechaSeleccionada = inputFecha1.value;
    
    // Actualizar el valor del segundo input date
    inputFecha2.value = fechaSeleccionada;
  });
</script>
  <script>
        // Realizar la solicitud GET a la API usando Fetch API
            fetch("<?php echo URL_API_CARLITOS ?>/api-config.php", {
                method: "RESERVAS"
            })
            .then(response => response.json())
            .then(data => {
                // Concatenar los valores obtenidos (supongamos que es un array de nombres)
                var concatenatedData = "";
                var concatenatedDataArray = []; 
                data.forEach(item => {
                    // Sumar uno al valor de numero_correlativo antes de la concatenación
                    let numeroCorrelativo = parseInt(item.numero_correlativo) + 1;
                    var concatenatedData = item.codigo + numeroCorrelativo.toString().padStart(6, '0');
                    concatenatedDataArray.push(concatenatedData);
                });
                // Actualizar el valor del campo de entrada
                document.getElementById("nro_reserva").value = concatenatedDataArray;
            })
            .catch(error => {
                console.error("Error en la solicitud a la API:", error);
            });
    </script>
  
  <script>
    function limpiarCarritoYTabla() {
        carritoItems = [];
        actualizarCarrito();
        const selectTipo = document.getElementById('habitacion');
        selectTipo.selectedIndex = 0;
        }
       
        document.getElementById('id_modalidad').addEventListener('change', limpiarCarritoYTabla);
  </script>
  <script>
  const selectTipo = document.getElementById("tipo");
    fetch('<?php echo URL_API_CARLITOS ?>/api-reservas.php', {
      method: 'GET3',
    })
    .then(response => response.json())
    .then(productosData2 => {
      llenarSelectHabitaciones(productosData2);
    })
    .catch(error => console.error('Error al obtener los datos del API:', error));
    // Función para llenar el select con los datos del objeto
    function llenarSelectHabitaciones(data) {
        var selectElement = document.getElementById('habitacion');
        data.forEach(function(producto) {
            var option = document.createElement('option');
            option.value = producto.nombre_producto;
            option.textContent = producto.nombre_producto;
            selectTipo.appendChild(option);
        });
        }
  </script>
  <script>
    // Objeto con datos de productos
    let productos = [];
    const precioVentaInput = document.getElementById("monto");
    const selectElement = document.getElementById("habitacion");
    const selectPrecios = document.getElementById("selectPrecios");
    fetch('<?php echo URL_API_CARLITOS ?>/api-reservas.php', {
      method: 'INNER',
    })
    .then(response => response.json())
    .then(productosData => {
      llenarSelect(productosData);
    })
    .catch(error => console.error('Error al obtener los datos del API:', error));
    // Función para llenar el select con los datos del objeto
    function llenarSelect(data) {
        var selectElement = document.getElementById('habitacion');
        data.forEach(function(producto) {
            var option = document.createElement('option');
            option.value = producto.id_habitacion;
            option.textContent = producto.nro_habitacion;
            selectElement.appendChild(option);
        });
        }
            document.addEventListener("DOMContentLoaded", function() {
              selectTipo.addEventListener("change", function() {
              precioVentaInput.value = " ";
              selectPrecios.selectedIndex = 0
                var selectedOption = selectTipo.options[selectTipo.selectedIndex];
                var codigo = selectedOption.text;
                // Realizar la solicitud a la API en PHP
                fetch(`<?php echo URL_API_CARLITOS ?>/api-reservas.php?codigo=${codigo}`, {
                        method: 'GET4',
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Manipular los datos devueltos por la API
                    JSON.stringify(data);
                        console.log(data);
                        productos = data;
                      
                    })
                    .catch(error => {
                        console.error("Error en la solicitud a la API:", error);
                    });
            });
        });
      document.addEventListener("DOMContentLoaded", function() {
          
          selectPrecios.addEventListener("change", function() {
            const selectedPrecioKey = selectPrecios.value;
            const selectedPrecio = productos[0][selectedPrecioKey]; // Suponiendo que solo hay un producto en productos[]
            precioVentaInput.value = selectedPrecio;
        });

      });
    </script>
  <script>
     // Función para llenar el select con los datos obtenidos del API
     function fillModalidadSelect(data) {
            const selectElement = document.getElementById("id_modalidad");
            data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.id_modalidad;
                option.textContent = item.nombre_modalidad;
                selectElement.appendChild(option);
            });
        }

        // Llamada al API utilizando Fetch API
        fetch('<?php echo URL_API_CARLITOS ?>/api-modalidadcliente.php', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            fillModalidadSelect(data);
            // console.log(data);
        })
        .catch(error => console.error('Error al obtener los datos del API:', error));

  </script>
  <script>
    // Escuchar el evento de cambio en el input de fecha de fin
    document.getElementById('fecha_salida').addEventListener('change', function() {
  // Obtener las fechas de inicio y fin
  var fechaInicio = new Date(document.getElementById('fecha_ingreso').value);
  var fechaFin = new Date(this.value);

  // Calcular la diferencia en días entre las fechas
  var diferenciaEnMs = fechaFin - fechaInicio;
  var numNoches = Math.round(diferenciaEnMs / (1000 * 60 * 60 * 24));

  // Mostrar el resultado en el input de número de noches
  document.getElementById('noches').value = numNoches;
});
  </script>
  <script>
   // Escuchar el evento de cambio en el select
    document.getElementById('id_modalidad').addEventListener('change', function() {
    // Obtener el valor seleccionado del select
    var selectedValue = this.value;

    // Obtener el input que se desea bloquear/deshabilitar
    var otroInput = document.getElementById('monto');
    // Desseleccionar el select
    // Verificar si el valor seleccionado no es igual a 2
    if (selectedValue !== '2') {
        // Deshabilitar el input
        otroInput.disabled = true;
    } else {
        // Habilitar el input
        otroInput.disabled = false;
    }
    }); 
  </script>
  <script>
    let totalFinal;
    let carritoItems = [];

function agregarProducto() {
  // Obtener los elementos de entrada por su ID
  var porcentajeInput = document.getElementById("porcentaje");
  var adelantoInput = document.getElementById("adelanto");
  
  // Limpiar los valores de los elementos de entrada
  porcentajeInput.value = '';
  adelantoInput.value = '';
  const tipoInput = document.getElementById('tipo');
  const habitacionInput = document.getElementById('habitacion');
  const personasInput = document.getElementById('personas');
  const montoInput = document.getElementById('monto');
  const nochesInput = document.getElementById('noches');
  const selectedOption = habitacionInput.options[habitacionInput.selectedIndex];
  const tipo = tipoInput.value;
  const habitacion = selectedOption.textContent;
  const personas = parseInt(personasInput.value);
  const monto = parseFloat(montoInput.value);
  const noches = parseInt(nochesInput.value);

  if (tipo.trim() === '' || isNaN(habitacion) || isNaN(personas) || isNaN(monto) || isNaN(noches) || monto <= 0 || noches <= 0) {
    alert('Por favor, ingresa valores válidos en el formulario.');
    return;
  }

  const newItem = { tipo, habitacion, personas, monto, noches };
  carritoItems.push(newItem);
  actualizarCarrito();
}

function actualizarCarrito() {
  const carritoBody = document.getElementById('carrito-body');
  carritoBody.innerHTML = '';
  let totalPersonas = 0;
  carritoItems.forEach((item, index) => {
    const row = document.createElement('tr');

    const tipoCell = document.createElement('td');
    tipoCell.textContent = item.tipo;
    row.appendChild(tipoCell);

    const habitacionCell = document.createElement('td');
    habitacionCell.textContent = item.habitacion;
    row.appendChild(habitacionCell);

    const personasCell = document.createElement('td');
    personasCell.textContent = item.personas;
    row.appendChild(personasCell);

    const montoCell = document.createElement('td');
    montoCell.textContent = item.monto.toFixed(2);
    row.appendChild(montoCell);

    const nochesCell = document.createElement('td');
    nochesCell.textContent = item.noches;
    row.appendChild(nochesCell);

    const totalCell = document.createElement('td');
    const total = item.monto * item.noches;
    totalCell.textContent = total.toFixed(2);
    row.appendChild(totalCell);

    const eliminarCell = document.createElement('td');
    const eliminarButton = document.createElement('button');
    eliminarButton.textContent = 'Eliminar';
    eliminarButton.classList.add('btn', 'btn-danger'); // Agregamos la clase al botón
    eliminarButton.addEventListener('click', () => eliminarProducto(index));
    eliminarCell.appendChild(eliminarButton);
    row.appendChild(eliminarCell);
    totalPersonas += parseInt(item.personas);
    carritoBody.appendChild(row);
  });
   // Calcular el total final
   
   totalFinal = carritoItems.reduce((total, item) => total + (item.monto * item.noches), 0);
   const totalFinalLabel = document.getElementById('total-final-label');
   const totalFinalLabel2 = document.getElementById('total2');
   totalFinalLabel.value = totalFinal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
   totalFinalLabel2.value = totalFinal.toFixed(2);
    // Mostrar el total de personas en el input correspondiente
    const totalPersonasInput = document.getElementById('total-personas');
    totalPersonasInput.value = totalPersonas;
    
}

document.addEventListener("DOMContentLoaded", function() {
            var porcentajeInput = document.getElementById("porcentaje");
            var adelantoInput = document.getElementById("adelanto");
            var adelantoInput2 = document.getElementById("adelanto2");

            porcentajeInput.addEventListener("change", function() {
                var porcentaje = parseFloat(porcentajeInput.value);
                if (!isNaN(porcentaje)) {
                    var result = (porcentaje / 100) * totalFinal;
                    var total2 = result.toFixed(2);
                    var total = result.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    adelantoInput.value = total;
                    adelantoInput2.value = total2;
                }
            });
});

function eliminarProducto(index) {
  // Obtener los elementos de entrada por su ID
  var porcentajeInput = document.getElementById("porcentaje");
  var adelantoInput = document.getElementById("adelanto");
  
  // Limpiar los valores de los elementos de entrada
  porcentajeInput.value = '';
  adelantoInput.value = '';
  
  // Eliminar el elemento del carrito en el índice proporcionado
  carritoItems.splice(index, 1);
  
  // Actualizar el carrito de compras (esto probablemente llamará a una función que actualiza la visualización)
  actualizarCarrito();
}
// Mostrar productos predeterminados al cargar la página
document.addEventListener('DOMContentLoaded', actualizarCarrito);
  </script>
  <script>
        function RegistrarDatos() {
            var nro_reserva = document.getElementById("nro_reserva").value;
            var nombre = document.getElementById("nombre").value;
            var lugar_procedencia = document.getElementById("lugar_de_procedencia").value;
            var id_modalidad = document.getElementById("id_modalidad").value;
            var fecha_llegada = document.getElementById("fecha_llegada").value;
            var hora_llegada = document.getElementById("hora_llegada").value;
            var fecha_salida = document.getElementById("fecha_salida").value;
            var tipo_transporte = document.getElementById("tipo_transporte").value;
            var telefono = document.getElementById("telefono").value;
            var observaciones_hospedaje = document.getElementById("observaciones_hospedaje").value;
            var observaciones_pago = document.getElementById("observaciones_pago").value;
            var nro_adultos = document.getElementById("nro_adultos").value;
            var nro_niños = document.getElementById("nro_niños").value;
            var nro_infantes = document.getElementById("nro_infantes").value;
            var nro_personas = document.getElementById("total-personas").value;
            var monto_total = document.getElementById("total2").value;
            var adelanto = document.getElementById("adelanto2").value;
            var porcentaje_pago = document.getElementById("porcentaje").value;
            var noches = document.getElementById("noches").value;

            var datosReserva = {
                nro_reserva: nro_reserva,
                nombre: nombre,
                lugar_procedencia: lugar_procedencia,
                id_modalidad: id_modalidad,
                fecha_llegada: fecha_llegada,
                hora_llegada: hora_llegada,
                fecha_salida: fecha_salida,
                tipo_transporte: tipo_transporte,
                telefono: telefono,
                observaciones_hospedaje: observaciones_hospedaje,
                observaciones_pago: observaciones_pago,
                nro_personas: nro_personas,
                nro_adultos: nro_adultos,
                nro_niños: nro_niños,
                nro_infantes: nro_infantes,
                porcentaje_pago: porcentaje_pago,
                adelanto: adelanto,
                monto_total: monto_total
            };

            var datosAdelanto = {
              nro_reserva: nro_reserva,
              habitacion: habitacion,
              fecha_llegada: fecha_llegada,
              fecha_salida: fecha_salida,
              personas: personas,
              noches: noches,
              monto_total: monto_total,
              carritoItems: carritoItems

            };
            //console.log(datosAdelanto);
            // Realizar la solicitud POST a la API
            fetch('<?php echo URL_API_CARLITOS ?>/api-reservas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datosReserva)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta de la API:');
                //window.location.reload();
                // Aquí puedes realizar acciones adicionales con la respuesta de la API
            })
            .catch(error => {
                console.error('Error al enviar la solicitud:', error);
                // Realizar la solicitud POST a la API
                  fetch('<?php echo URL_API_CARLITOS ?>/api-reservahabitaciones.php', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify(datosAdelanto)
                  })
                  .then(response => response.json())
                  .then(data => {
                      console.log('Respuesta de la API:');
                      // Aquí puedes realizar acciones adicionales con la respuesta de la API
                  })
                  .catch(error => {
                      console.error('Error al enviar la solicitud:', error);
                  });
              
            });
            window.location.reload();
        }
    </script>
    
<?php
require "../../inc/footer.php";
?>