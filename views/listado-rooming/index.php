<?php
require "../../inc/header.php";

session_start();

$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado);
?>
<div class="container-fluid">
<div class="card">
    
      <div class="card-body">
      <h3 class="mb-4">LISTADO DE ROOMIN - REGISTRO DE HUESPEDES</h3>
      <div class="table-responsive">
      <div class="col-md-4">
      <label for="validationCustom02" class="form-label">Buscar por fecha:</label>
        <div class="input-group mb-3">
            <input type="date" class="form-control" id="fecha_busqueda">
            <button class="btn btn-success" type="button" onclick="buscarPorFecha()">Buscar</button>
        </div>
    </div>
      <table id="rooming" class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Nro Habitacion</th>
                    <th>Nro. Reg. Maestro</th>
                    <th>Nro Reserva</th>
                    <th>Cliente</th>
                    <th>Nro Personas</th>
                    <th>Fecha Llegada</th>
                    <th>Fecha Salida</th>
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
<script>
    // Función para obtener y listar los datos en la tabla
        function listarDatosEnTabla() {
        const url = '<?php echo URL_API_CARLITOS ?>/api-rooming.php'; // Reemplaza 'tu_url_de_la_api' con la URL de tu API

        fetch(url ,{
                    method: 'INNER',
                        })
            .then(response => response.json())
            .then(data => {
            const tabla = document.getElementById('rooming').getElementsByTagName('tbody')[0];

            // Limpiar la tabla antes de agregar nuevos datos
            //tabla.innerHTML = '';
            //console.log(data);
            // Iterar sobre los datos y construir las filas de la tabla
            data.forEach(item => {
                const row = tabla.insertRow();
                row.innerHTML = `
                <td>${item.nombre_producto || ''}</td>
                <td>${item.nro_habitacion || ''}</td>
                <td>${item.nro_registro_maestro || ''}</td>
                <td>${item.nro_reserva || ''}</td>
                <td>${item.nombre || ''}</td>
                <td>${item.nro_personas || ''}</td>
                <td>${item.fecha_in || ''}</td>
                <td>${item.fecha_out || ''}</td>
                <td><a href="http://192.168.1.11:8080/hotelarenasspa/cliente/views/gestionar-checkin-hotel?parametro1=${item.nro_reserva}&parametro2=${item.nro_registro_maestro}" class="btn btn-warning" style="--bs-btn-padding-y: .25rem;">EDITAR</a>
                `;
            });
            })
            .catch(error => console.error('Error al obtener datos de la API:', error));
        }

        // Llamar a la función para listar los datos cuando la página cargue
        window.addEventListener('load', listarDatosEnTabla);
  </script>
  <script>
    function buscarPorFecha() {
    const fechaBusqueda = document.getElementById('fecha_busqueda').value;
    
    const url = `<?php echo URL_API_CARLITOS ?>/api-rooming.php`;
    const data = { fecha: fechaBusqueda };

    fetch(url, {
        method: 'POST2',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        const tabla = document.getElementById('rooming').getElementsByTagName('tbody')[0];
        tabla.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos

        data.forEach(item => {
            const row = tabla.insertRow();
            row.innerHTML = `
                <td>${item.nombre_producto || ''}</td>
                <td>${item.nro_habitacion || ''}</td>
                <td>${item.nro_registro_maestro || ''}</td>
                <td>${item.nro_reserva || ''}</td>
                <td>${item.nombre || ''}</td>
                <td>${item.nro_personas || ''}</td>
                <td>${item.fecha_in || ''}</td>
                <td>${item.fecha_out || ''}</td>
                <td><a href="http://192.168.1.11:8080/hotelarenasspa/cliente/views/gestionar-checkin-hotel?parametro1=${item.nro_reserva}&parametro2=${item.nro_registro_maestro}" class="btn btn-warning" style="--bs-btn-padding-y: .25rem;">EDITAR</a>
            `;
        });
    })
    .catch(error => console.error('Error al obtener datos de la API:', error));
}
</script>
<?php
require "../../inc/footer.php";
?>