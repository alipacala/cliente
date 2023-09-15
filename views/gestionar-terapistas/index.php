<?php
require "../../inc/header.php";

session_start();

$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado);
?>
  <!-- Modal structure -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Selecciona Habilidades:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="checkboxForm">
                        <div id="checkboxContainer" class="mb-3">
                            <!-- Las casillas de verificación se agregarán aquí dinámicamente -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
<!-- fin modal -->

  <div class="container mt-5">
    <form id="formulario-terapistas" class="row g-3 needs-validation">
      <h4>FORMULARIO DE REGISTRO DE TRABAJADOR</h4>
        <div class="col-md-4">
          <label for="apellidoPaterno">Apellido Paterno:</label>
          <input type="text" class="form-control" id="apellidoPaterno" required>
        </div>
        <div class="col-md-4">
          <label for="apellidoMaterno">Apellido Materno:</label>
          <input type="text" class="form-control" id="apellidoMaterno" required>
        </div>
        <div class="col-md-4">
          <label for="nombres">Nombres:</label>
          <input type="text" class="form-control" id="nombres" required>
        </div>
        <div class="col-md-4">
          <label for="nombreConyugue">Nombre del Conyugue:</label>
          <input type="text" class="form-control" id="nombre_del_conyugue" required>
        </div>
        <div class="col-md-4">
          <label for="dni">DNI Nro.:</label>
          <input type="text" class="form-control" id="nro_documento" required>
        </div>
        <div class="col-md-4">
          <label for="fechaNacimiento">Fecha Nacimiento:</label>
          <input type="date" class="form-control" id="fecha_de_nacimiento" required>
        </div>
        <div class="col-md-4">
          <label for="nombreConyugue">Lugar de Nacimiento:</label>
          <input type="text" class="form-control" id="lugar_de_nacimiento" required>
        </div>
        <div class="col-md-4">
          <label for="fechaNacimiento">Estado Civil:</label>
          <select class="form-control" name="estado_civil" id="estado_civil" required>
            <option value="SO">Soltero</option>
            <option value="CA">Casado</option>
            <option value="DI">Divorciado</option>
            <option value="VI">Viudo</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="fechaNacimiento">Sexo:</label>
          <select class="form-control" name="sexo" id="sexo" required>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="fechaNacimiento">Tipo de Cliente:</label>
          <select class="form-control" name="tipo_de_cliente" id="tipo_de_cliente" required>
            <option value="PTC">Pers.Tiempo Completo</option>
            <option value="PTP">Pers.Tiempo Parcial</option>
            <option value="PTE">Pers.Tiempo Expersonal</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="nombreConyugue">Direccion:</label>
          <input type="text" class="form-control" id="direccion" required>
        </div>
        <div class="col-md-4">
          <label for="dni">Distrito:</label>
          <input type="text" class="form-control" id="distrito" required>
        </div>
      <div class="col-md-4">
          <label for="nombreConyugue">Provincia:</label>
          <input type="text" class="form-control" id="provincia" required>
        </div>
        <div class="col-md-4">
          <label for="nombreConyugue">Telefono:</label>
          <input type="text" class="form-control" id="telefono" required>
        </div>
        <div class="col-md-4">
          <label for="dni">Celular:</label>
          <input type="text" class="form-control" id="celular" required>
        </div>
      <div class="col-md-4">
          <label for="nombreConyugue">Email:</label>
          <input type="text" class="form-control" id="Email">
        </div>
        <div class="col-md-4">
          <label for="nombreConyugue">Contacto Emergencia:</label>
          <input type="text" class="form-control" id="contacto_de_Emergencia" required>
        </div>
        <div class="col-md-4">
          <label for="dni">Dir. Familia:</label>
          <input type="text" class="form-control" id="direccion_familia" required>
        </div>
      <div class="col-md-4">
          <label for="nombreConyugue">Telefono Familia:</label required>
          <input type="text" class="form-control" id="telefono_familia">
        </div>
        <div class="col-md-4">
          <label for="dni">Compañia que pertenece:</label>
          <input type="text" class="form-control" id="compania_que_pertenece">
        </div>
        <div class="col-md-4">
              <label for="hora_de_ingreso">Hora de Ingreso 01</label>
              <input type="time" class="form-control" id="hora_ingreso" name="hora_ingreso" required>
          </div>
          <div class="col-md-4">
              <label for="hora_de_salida">Hora de Salida 01</label>
              <input type="time" class="form-control" id="hora_salida" name="hora_salida" required>
          </div>
        <div class="col-md-4">
              <label for="hora_de_ingreso">Hora de Ingreso 02</label>
              <input type="time" class="form-control" id="hora_de_ingreso2" name="hora_de_ingreso2">
          </div>
          <div class="col-md-4">
              <label for="hora_de_salida">Hora de Salida 02</label>
              <input type="time" class="form-control" id="hora_de_salida2" name="hora_de_salida2">
          </div>
        <div class="col-md-4">
          <label for="fechaNacimiento">Dia Considerado Descanso:</label>
          <select class="form-control" name="dia_descanso" id="dia_descanso">
            <option value="1">Lunes</option>
            <option value="2">Martes</option>
            <option value="3">Miercoles</option>
            <option value="4">Jueves</option>
            <option value="5">Viernes</option>
            <option value="6">Sabado</option>
            <option value="7">Domingo</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="nombreConyugue">Area de Trabajo:</label>
          <input type="text" class="form-control" id="area_de_trabajo" required>
        </div>
        <div class="col-md-4">
          <label for="dni">Cargo:</label>
          <input type="text" class="form-control" id="cargo">
        </div>
        <div class="col-md-4">
          <!-- <label for="nombreConyugue">Usuario:</label> -->
          <input type="hidden" class="form-control" id="usuario">
        </div>
        <div class="col-md-4">
          <!-- <label for="nombreConyugue">Clave Acceso:</label> -->
          <input type="hidden" class="form-control" id="clave_acceso">
        </div>
        <div class="col-md-4">
          <label for="dni">Nro AutoGenerado:</label>
          <input type="text" class="form-control" id="nro_autogenerado">
        </div>
      <div class="col-md-4">
          <label for="nombreConyugue">CUSSP:</label>
          <input type="text" class="form-control" id="nro_cussp">
        </div>
        <div class="col-md-4">
          <label for="nombreConyugue">Tipo de Trabajo:</label>
          <input type="text" class="form-control" id="tipo_de_trabajo">
        </div>
        <div class="col-md-4">
          <label for="dni">Haber Basico:</label>
          <input type="number" class="form-control" id="haber_basico">
        </div>
        <div class="col-md-4">
            <label for="fechaNacimiento">Asignacion Familiar:</label>
            <select class="form-control" name="asignacion_familiar" id="asignacion_familiar" required>
              <option value="1">Sí</option>
              <option value="0">No</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="nombreConyugue">Nro Hijos:</label>
            <input type="number" class="form-control" id="nro_hijos">
          </div>
          <div class="col-md-4">
            <label for="fechaNacimiento">Dependiente:</label>
            <select class="form-control" name="dependiente" id="dependiente">
              <option value="1">Sí</option>
              <option value="0">No</option>
            </select>
          </div>
      <br>
      <div class="col-md-4">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><strong>+</strong>Asignar Habilidad a Profesional</button>
      </div>
      <br>
      <table id="resultTable" class="table mt-4">
        <thead>
            <tr>
                <th>ID Habilidad</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Las filas de la tabla se agregarán aquí dinámicamente -->
        </tbody>
    </table>
    <br>
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-primary">Guardar Ficha Trabajador</button>
      </div>
    <br>
  </div>
  </form>
  <script>
    let selectedDataObject = [];
    let apiData = [];
        
        // Función para actualizar la tabla con los datos seleccionados
        function updateTable() {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';
            
            apiData.forEach(item => {
                if (item.selected) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.id_habilidad}</td>
                        <td>${item.descripcion}</td>
                        <td><button class="btn btn-danger btn-sm" data-id="${item.id_habilidad}" onclick="removeRow(this)">Eliminar</button></td>
                    `;
                    tableBody.appendChild(row);
                }
            });
        }
        
        // Función para eliminar una fila de la tabla
        function removeRow(button) {
            const id = button.getAttribute('data-id');
            const item = apiData.find(dataItem => dataItem.id_habilidad == id);
            if (item) {
                item.selected = false;
                updateTable();
            }
        }
        
        // Función para obtener los datos del API utilizando Fetch
        fetch('<?php echo URL_API_CARLITOS ?>/api-habilidadesprofesionales.php', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            apiData = data;
            //console.log(apiData);

            // Una vez que se cargan los datos, agregamos las casillas de verificación al formulario
            const checkboxContainer = document.getElementById('checkboxContainer');

            apiData.forEach(item => {
                const div = document.createElement('div');
                div.classList.add('form-check');
                div.innerHTML = `
                    <input class="form-check-input" type="checkbox" value="${item.id_habilidad}" id="chk_${item.id_habilidad}" onchange="toggleSelection(${item.id_habilidad}, this)">
                    <label class="form-check-label" for="chk_${item.id_habilidad}">
                        ${item.descripcion}
                    </label>
                `;
                checkboxContainer.appendChild(div);
            });
        })
        .catch(error => console.error('Error al obtener los datos del API:', error));
        
        // Función para manejar la selección/deselección de checkbox
        function toggleSelection(id, checkbox) {
            const item = apiData.find(dataItem => dataItem.id_habilidad == id);
            if (item) {
                item.selected = checkbox.checked;
                updateTable();
            }
        }


        function getSelectedDataAsObject() {
            const selectedData = [];
            apiData.forEach(item => {
                if (item.selected) {
                    selectedData.push({
                        id_habilidad: item.id_habilidad,
                        descripcion: item.descripcion
                    });
                }
            });

            return selectedData;
        }
        
        // Ejemplo de cómo usar la función para obtener el objeto con los datos seleccionados
        function guardarTabla() {
            selectedDataObject = getSelectedDataAsObject(); // Puedes cambiar esto para guardar o utilizar el objeto según tus necesidades
        }

    </script>
  
  <script>
    const formularioTerapistas = document.getElementById('formulario-terapistas');
            formularioTerapistas.addEventListener('submit', function(event) {
              guardarTabla();
            event.preventDefault();
        var apellidoP = document.getElementById('apellidoPaterno').value;
        var apellidoM = document.getElementById('apellidoMaterno').value;
            const nuevoTerapista = {
                apellidos: apellidoP +', '+ apellidoM,
                nombres: document.getElementById('nombres').value,
                nombre_del_conyugue: document.getElementById('nombre_del_conyugue').value,
                nro_documento: document.getElementById('nro_documento').value,
                fecha_de_nacimiento: document.getElementById('fecha_de_nacimiento').value,
                lugar_de_nacimiento: document.getElementById('lugar_de_nacimiento').value,
                estado_civil: document.getElementById('estado_civil').value,
                sexo: document.getElementById('sexo').value,
                tipo_de_cliente: document.getElementById('tipo_de_cliente').value,
                direccion: document.getElementById('direccion').value,
                distrito: document.getElementById('distrito').value,
                provincia: document.getElementById('provincia').value,
                telefono: document.getElementById('telefono').value,
                celular: document.getElementById('celular').value,
                Email: document.getElementById('Email').value,
                contacto_de_Emergencia: document.getElementById('contacto_de_Emergencia').value,
                direccion_familia: document.getElementById('direccion_familia').value,
                telefono_familia: document.getElementById('telefono_familia').value,
                compania_que_pertenece: document.getElementById('compania_que_pertenece').value,
                hora_ingreso: document.getElementById('hora_ingreso').value,
                hora_salida: document.getElementById('hora_salida').value,
                hora_de_ingreso2: document.getElementById('hora_de_ingreso2').value,
                hora_de_salida2: document.getElementById('hora_de_salida2').value,
                dia_descanso: document.getElementById('dia_descanso').value,
                area_de_trabajo: document.getElementById('area_de_trabajo').value,
                cargo: document.getElementById('cargo').value,
                usuario: document.getElementById('usuario').value,
                clave_acceso: document.getElementById('clave_acceso').value,
                nro_autogenerado: document.getElementById('nro_autogenerado').value,
                nro_cussp: document.getElementById('nro_cussp').value,
                tipo_de_trabajo: document.getElementById('tipo_de_trabajo').value,
                haber_basico: document.getElementById('haber_basico').value,
                asignacion_familiar: document.getElementById('asignacion_familiar').value,
                nro_hijos: document.getElementById('nro_hijos').value,
                dependiente: document.getElementById('dependiente').value,
                habilidades: selectedDataObject
            };
            AgregarTerapista(nuevoTerapista);
            //console.log(nuevoTerapista);
          });
        function AgregarTerapista(terapista) {
            // Hacer la petición POST a la API para agregar un nuevo terapistas
           //console.log(terapista);
           fetch('<?php echo URL_API_CARLITOS ?>/api-terapistas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(terapista)
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Mostrar mensaje de éxito o error de la API
                //obtenerUsuarios(); // Actualizar la lista de usuarios después de agregar uno nuevo
            })
            .catch(error => console.error('Error:', error));
            window.location.reload();

        }
  </script>
<?php
require "../../inc/footer.php";
?>