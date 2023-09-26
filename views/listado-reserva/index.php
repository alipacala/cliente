<?php
require "../../inc/header.php";

session_start();

$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado);
?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Reserva</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form class="row g-3 needs-validation" id="formulario-reservaE">
        <div class="col-md-4">
            <label for="validationCustom01" class="form-label">Nro Reserva</label>
            <input type="text" class="form-control" id="nro_reserva" readonly required>
        </div>
        <div class="col-md-8">
            <label for="validationCustom01" class="form-label">Nom. Cliente</label>
            <input type="text" class="form-control" id="nombre" readonly required>
        </div>
        <div class="col-md-2">
            <label for="validationCustom01" class="form-label">Cantidad Dias</label>
            <input type="text" class="form-control" id="nro_dias" readonly required>
        </div>
        <div class="col-md-2">
            <label for="validationCustom01" class="form-label">Cant. Personas</label>
            <input type="text" class="form-control" id="nro_personas" readonly required>
        </div>
        <div class="col-md-8">
            <label for="validationCustom01" class="form-label">Lugar de Procedencia</label>
            <input type="text" class="form-control" id="lugar_procedencia" readonly required>
        </div>
        <div class="col-md-12">
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Nro Habitacion</th>
                    <th>Nro Personas</th>
                    <th>Nro Noches</th>
                    <th>Fecha Llegada</th>
                    <th>Fecha Salida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla_reservas">
                <!-- Aquí se mostrarán las habitaciones reservadas -->
            </tbody>
        </table>
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Observaciones Hospedaje</label>
          <textarea class="form-control" id="o_hospedaje" rows="2"></textarea>
        </div>
        <div class="mb-3">
          <label for="exampleFormControlTextarea1" class="form-label">Observaciones Pago</label>
          <textarea class="form-control" id="o_pago" rows="2"></textarea>
        </div>
        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Fecha Llegada</label>
            <input type="date" class="form-control" id="fecha_llegada" required>
        </div>
        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Fecha Salida</label>
            <input type="date" class="form-control" id="fecha_salida" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<div class="container-fluid">
<div class="card">
    
      <div class="card-body">
      <h3 class="mb-4">Lista de Reservas:</h3>
      <div class="table-responsive">
      <div class="col-md-4">
      <label for="validationCustom02" class="form-label">Buscar por fecha:</label>
        <div class="input-group mb-3">
            <input type="date" class="form-control" id="fecha_busqueda">
            <button class="btn btn-success" type="button" onclick="buscarPorFecha()">Buscar</button>
        </div>
    </div>
      <table id="pagos" class="table">
            <thead>
                <tr>
                    <th>Nro Reserva</th>
                    <th>F. Llegada</th>
                    <th>F. Salida</th>
                    <th>Nro Dias</th>
                    <th>Cliente</th>
                    <th>Nro Personas</th>
                    <th>Cant. Habitaciones</th>
                    <th>Lugar de Procedencia</th>
                    <th>Estado</th>
                    <th>Nro Maestro</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="pendiente-pago">
            </tbody>
        </table>
      </div>
      </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="ModalChekin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">¿Desea hacer CHECKIN?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form class="row g-3 needs-validation" id="formulario-Chekin">
        <div class="col-md-4">
            <label for="validationCustom01" class="form-label">Nro Reserva</label>
            <input type="text" class="form-control" id="nro_reserva2" readonly>
        </div>
        <div class="col-md-8">
            <label for="validationCustom01" class="form-label">Nom. Cliente</label>
            <input type="text" class="form-control" id="nombre2" readonly>
        </div>
        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Fecha Llegada</label>
            <input type="date" class="form-control" id="fecha_llegada2" readonly>
        </div>
        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Fecha Salida</label>
            <input type="date" class="form-control" id="fecha_salida2" readonly>
        </div>
        <div class="col-md-4">
            <label for="validationCustom01" class="form-label">Cant. Personas</label>
            <input type="text" class="form-control" id="nro_personas2" readonly>
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" onclick="RealizarCheckin()">Si</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
<script>
      function setFechaActual() {
          var inputDate = document.getElementById("fecha_busqueda");
          var fechaActual = new Date().toISOString().slice(0, 10);
          inputDate.value = fechaActual;
      }
    </script>
   <script>
        let habitaciones = []; // Array para almacenar los datos de las habitaciones
        let habitacionesEliminadas = [];
        let id;
        window.onload = function() {
        
            setFechaActual();
            
        };

        function buscarReservas(codigo) {
            const nro_reserva = document.getElementById('nro_reserva').value;
            fetch(`<?php echo URL_API_CARLITOS ?>/api-reservas.php?nro_reserva=${codigo}`)
                .then(response => response.json())
                .then(data => {
                    habitaciones = data; // Almacena los datos de la API en el array habitaciones
                    mostrarTabla(); // Llama a la función para mostrar la tabla
                });
        }

        function mostrarTabla() {
          const tabla = document.getElementById('tabla_reservas');
          tabla.innerHTML = ''; // Limpiar la tabla

          habitaciones.forEach(reserva => {
              const fila = document.createElement('tr');
              fila.innerHTML = `
                  <td>${reserva.nro_habitacion}</td>
                  <td>${reserva.nro_personas}</td>
                  <td>${reserva.nro_noches}</td>
                  <td>${reserva.fecha_ingreso}</td>
                  <td>${reserva.fecha_salida}</td>
                  <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this, ${reserva.id_reserva_habitaciones})">Eliminar</button></td>
              `;
              tabla.appendChild(fila);
          });
      }

      function eliminarFila(button, idReserva) {
          // Obtener la fila que contiene el botón
          const row = button.parentNode.parentNode;

          // Obtener el índice de la fila en la tabla
          const rowIndex = row.rowIndex;

          // Eliminar el registro correspondiente del array habitaciones
          habitaciones.splice(rowIndex - 1, 1);

          // Agregar la idReserva al array habitacionesEliminadas si no existe
          if (!habitacionesEliminadas.includes(idReserva)) {
               // Agregar el objeto con el campo "id" al array habitacionesEliminadas
              habitacionesEliminadas.push({ "id": idReserva });
          }
         
          mostrarTabla();
      }
      function eliminarReserva() {
            fetch('<?php echo URL_API_CARLITOS ?>/api-reservas.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: JSON.stringify(habitacionesEliminadas),
            })
                .then(response => response.text())
                .then(message => {
                    console.log(message);
                
                });
        }
    </script>
<script>
        // Manejar el envío del formulario para agregar un nuevo usuario
        const formularioChekin = document.getElementById('formulario-Chekin');
        formularioChekin.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(formularioChekin);
            const nuevoChekin= {
            nro_reserva: document.getElementById('nro_reserva2').value
            };
            RealizarCheckin(nuevoChekin);
        });

        function RealizarCheckin(checkin) {
            // Hacer la petición POST a la API para agregar un nuevo usuario
            fetch('<?php echo URL_API_CARLITOS ?>/api-reservas.php', {
            method: 'CHECKIN',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(checkin)
            })
            .then(response => response.text())
            .then(data => {
                //console.log(data); // Mostrar mensaje de éxito o error de la API
                //obtenerUsuarios(); // Actualizar la lista de usuarios después de agregar uno nuevo
            })
            .catch(error => console.error('Error:', error));
            window.location.reload();
        }
    </script>
    <script>
        // Manejar el envío del formulario para agregar un nuevo usuario
        const formularioRE = document.getElementById('formulario-reservaE');
        formularioRE.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(formularioRE);
            const nuevoReservaE = {
                nro_reserva: document.getElementById('nro_reserva').value,
                fecha_llegada: document.getElementById('fecha_llegada').value,
                fecha_salida: document.getElementById('fecha_salida').value
            };
            agregarReserva(nuevoReservaE);
            eliminarReserva();
        });

        function agregarReserva(Reserva) {
            // Hacer la petición POST a la API para agregar un nuevo usuario
            fetch('<?php echo URL_API_CARLITOS ?>/api-reservas.php', {
                method: 'UPDATE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Reserva)
            })
            .then(response => response.text())
            .then(data => {
                //console.log(data); // Mostrar mensaje de éxito o error de la API
                //obtenerUsuarios(); // Actualizar la lista de usuarios después de agregar uno nuevo
            })
            .catch(error => console.error('Error:', error));
            window.location.reload();
        }
    </script>
    <script>
       function buscarPorFecha() {
    const fechaBusqueda = document.getElementById('fecha_busqueda').value;
    const url = '<?php echo URL_API_CARLITOS ?>/api-reservas.php'; // Reemplaza con la URL de tu API

    fetch(url,{
                    method: 'INNER2',
                        })
        .then(response => response.json())
        .then(data => {
            const tabla = document.getElementById('pagos').getElementsByTagName('tbody')[0];
            tabla.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos

            data.forEach(item => {
                if (item.fecha_llegada >= fechaBusqueda) {
                    const row = tabla.insertRow();
                    row.innerHTML = `
                    <td>${item.nro_reserva}</td>
                    <td>${item.fecha_llegada}</td>
                    <td>${item.fecha_salida}</td>
                    <td>${item.nro_noches}</td>
                    <td>${item.nombre}</td>
                    <td>${item.nro_personas}</td>
                    <td>${item.cantidad_habitaciones}</td>
                    <td>${item.lugar_procedencia}</td>
                    <td>
                      ${item.estado_pago == 0 ? '<span class="badge rounded-pill text-bg-danger">ADELANTO</span>' : 
                        (item.estado_pago == 1 ? '<span class="badge rounded-pill text-bg-success">CHEKIN</span>' : 
                        '<span class="badge rounded-pill text-bg-info">Adelanto</span>')}
                    </td>
                    <td>${item.nro_registro_maestro}</td>
                    <td><button type="button" class="btn btn-warning" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="ObtenerReserva('${item.nro_reserva}|${item.fecha_llegada}|${item.fecha_salida}|${item.nombre}|${item.nro_noches}|${item.nro_personas}|${item.nro_habitacion}|${item.lugar_procedencia}|${item.observaciones_hospedaje}|${item.observaciones_pago}')">EDITAR</button>
                    <button type="button" class="btn btn-info" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#ModalChekin" onclick="ObtenerID('${item.nro_reserva},${item.nombre},${item.fecha_llegada},${item.fecha_salida},${item.nro_personas}')">CHEKIN</button></td>
                    `;
                }
            });
        })
        .catch(error => console.error('Error al obtener datos de la API:', error));
}
    </script>
    <script>
    // Función para obtener y listar los datos en la tabla
        function listarDatosEnTabla() {
        const url = '<?php echo URL_API_CARLITOS ?>/api-reservas.php'; // Reemplaza 'tu_url_de_la_api' con la URL de tu API

        fetch(url ,{
                    method: 'INNER2',
                        })
            .then(response => response.json())
            .then(data => {
            const tabla = document.getElementById('pagos').getElementsByTagName('tbody')[0];

            // Limpiar la tabla antes de agregar nuevos datos
            //tabla.innerHTML = '';
            //console.log(data);
            // Iterar sobre los datos y construir las filas de la tabla
            data.forEach(item => {
                const row = tabla.insertRow();
                row.innerHTML = `
                    <td>${item.nro_reserva}</td>
                    <td>${item.fecha_llegada}</td>
                    <td>${item.fecha_salida}</td>
                    <td>${item.nro_noches}</td>
                    <td>${item.nombre}</td>
                    <td>${item.nro_personas}</td>
                    <td>${item.cantidad_habitaciones}</td>
                    <td>${item.lugar_procedencia}</td>
                    <td>
                      ${item.estado_pago == 0 ? '<span class="badge rounded-pill text-bg-danger">ADELANTO</span>' : 
                        (item.estado_pago == 1 ? '<span class="badge rounded-pill text-bg-success">CHEKIN</span>' : 
                        '<span class="badge rounded-pill text-bg-info">Adelanto</span>')}
                    </td>
                    <td>${item.nro_registro_maestro}</td>
                    <td><button type="button" class="btn btn-warning" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="ObtenerReserva('${item.nro_reserva}|${item.fecha_llegada}|${item.fecha_salida}|${item.nombre}|${item.nro_noches}|${item.nro_personas}|${item.nro_habitacion}|${item.lugar_procedencia}|${item.observaciones_hospedaje}|${item.observaciones_pago}')">EDITAR</button>
                    <button type="button" class="btn btn-info" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#ModalChekin" onclick="ObtenerID('${item.nro_reserva},${item.nombre},${item.fecha_llegada},${item.fecha_salida},${item.nro_personas}')">CHEKIN</button></td>
                    `;
            });
            })
            .catch(error => console.error('Error al obtener datos de la API:', error));
        }

        // Llamar a la función para listar los datos cuando la página cargue
        window.addEventListener('load', listarDatosEnTabla);
  </script>
<script>
    function ObtenerReserva(Reservas){
        var Reserva = Reservas.split('|');
        buscarReservas(Reserva[0]);
        document.getElementById('nro_reserva').value = Reserva[0];
        document.getElementById('fecha_llegada').value = Reserva[1];
        document.getElementById('fecha_salida').value = Reserva[2];
        document.getElementById('nombre').value = Reserva[3];
        document.getElementById('nro_dias').value = Reserva[4];
        document.getElementById('nro_personas').value = Reserva[5];
        document.getElementById('lugar_procedencia').value = Reserva[7];
        document.getElementById('o_hospedaje').value = Reserva[8];
        document.getElementById('o_pago').value = Reserva[9];
    }
  </script>
  <script>
    function ObtenerID(Chekin){
        var Chekin = Chekin.split(',');
        document.getElementById('nro_reserva2').value = Chekin[0];
        document.getElementById('nombre2').value = Chekin[1];
        document.getElementById('fecha_llegada2').value = Chekin[2];
        document.getElementById('fecha_salida2').value = Chekin[3];
        document.getElementById('nro_personas2').value = Chekin[4];
    }
  </script>
<?php
require "../../inc/footer.php";
?>