<?php
require "../../inc/header.php";

session_start();

$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado);
?>
<!-- Modal structure -->
<!-- Modal structure -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <label for="id_grupo_modulo">Seleccion de Modulos:</label>
                <select id="id_grupo_modulo" class="form-select">
                <option value="">Seleccione una opcion</option>
                    <!-- Options will be dynamically added here -->
                </select>
                <h6>Selecciona los modulo:</h6>
                <ul class="list-group" id="list-group">
                <!-- The list items with checkboxes will be dynamically added here -->
                </ul>
            </div>

            <!-- ... -->

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="addRowToTable()">Agregar Permisos</button>
            </div>
        </div>
    </div>
</div>

    <br>

    <br>
<div class="container">
<div class="card w-100 mb-3">
  <div class="card-body">
    <h4 class="card-title">Formulario Ficha del usuario</h4><br>
    <form class="row g-3 needs-validation" id="formulario-usuarios">
    <input type="hidden" class="form-control" id="id_persona" readonly>
    <div class="col-md-4">
    <label for="validationCustom02" class="form-label">Buscar DNI</label>
      <div class="input-group">
      <span class="input-group-text">Nro DNI</span>
        <input type="text" class="form-control" id="nro_doc" required>
        <button type="button" class="btn btn-info" onclick="Buscar()">Buscar</button>
    </div>
</div>
  <div class="col-md-4">
    <label for="validationCustom02" class="form-label">Nombres</label>
    <input type="text" class="form-control" id="nombres" readonly>
  </div>
  <div class="col-md-4">
    <label for="validationCustom02" class="form-label">Apellidos</label>
    <input type="text" class="form-control" id="apellidos" readonly>
  </div>
  <div class="col-md-12">
    <label for="validationCustom04" class="form-label">Unidad de Negocio</label>
    <select class="form-select" id="id_unidad_de_negocio">
        <option value="">Seleccione un undiad de negocio</option>
    </select>
  </div>
  <div class="col-md-12">
    <label for="validationCustom03" class="form-label">Cargo</label>
    <input type="text" class="form-control" id="cargo" required>
  </div>
  <div class="col-md-12">
    <label for="validationCustom04" class="form-label">Tipo de Usuario</label>
    <select class="form-select" id="id_tipo_de_usuario">
        <option value="">Seleccione un tipo de usuario</option>
    </select>
  </div>
  <div class="col-md-4">
    <label for="validationCustom01" class="form-label">Nombre Usuario</label>
    <input type="text" class="form-control" id="usuario" required>
  </div>
  <div class="col-md-4">
    <label for="validationCustom02" class="form-label">Clave</label>
    <input type="password" class="form-control" id="clave" required>
  </div>
  <div class="col-md-4">
    <label for="validationCustom02" class="form-label">Clave Apertura</label>
    <input type="password" class="form-control" id="clave_apertura" required>
  </div>
  <div class="col-md-4">
    <label for="hora_de_ingreso">Hora de Ingreso</label>
    <input type="time" class="form-control" id="hora_ingreso" name="hora_ingreso" required>
  </div>
  <div class="col-md-4">
    <label for="hora_de_salida">Hora de Salida</label>
    <input type="time" class="form-control" id="hora_salida" name="hora_salida" required>
  </div>
  <div class="col-md-4">
    <label for="activo">Activo</label>
    <select class="form-control" id="activo" name="activo" required>
        <option value="1">Sí</option>
        <option value="0">No</option>
    </select>
  </div>
  <div class="col-md-4">
    <label for="fecha_cese">Fecha de Cese</label>
    <input type="date" class="form-control" id="fecha_cese" name="fecha_cese">
  </div>
  <div class="col-12">
 
  </div>

  </div>
</div>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><strong>+</strong>Asignar Modulos Usuario</button><br><br>
<div class="row">
  <div class="col-sm-12 mb-3 mb-sm-0">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">LISTA DE ACCESOS</h5>
        <table id="dataTable" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Modulo</th>
                <th>Tiene Acceso</th>
                <th>Acceso Consulta</th>
                <th>Acceso Modificacion</th>
                <th>Acceso Creacion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be dynamically added here -->
        </tbody>
    </table>
      </div>
    </div>
  </div>

</div>
<br>
<div class="d-grid gap-2 d-md-flex justify-content-md-end">
  <button class="btn btn-primary" id="selectAllRowsButton" type="submit">Guardar Ficha Usuario</button>
</div>
</div>
</form>
    <script>
    // Objeto para almacenar las filas seleccionadas
    let selectedRows = [];

    // Función para guardar las filas en un objeto
    document.getElementById('selectAllRowsButton').addEventListener('click', function() {
        const tableRows = document.querySelectorAll('#dataTable tbody tr');
        selectedRows.length = 0; // Limpiar el objeto antes de guardar las filas nuevamente

        tableRows.forEach(row => {
            const moduleId = row.getAttribute('data-module-id');
            const moduleName = row.querySelector('td:nth-child(2)').textContent;
            const accessCheckboxes = row.querySelectorAll('input[type="checkbox"]');
            const tieneAcceso = accessCheckboxes[0].checked ? 1 : 0;
            const accesoConsulta = accessCheckboxes[1].checked ? 1 : 0;
            const accesoModificacion = accessCheckboxes[2].checked ? 1 : 0;
            const accesoCreacion = accessCheckboxes[3].checked ? 1 : 0;

            selectedRows.push({
                moduleId: moduleId,
                moduleName: moduleName,
                tieneAcceso: tieneAcceso,
                accesoConsulta: accesoConsulta,
                accesoModificacion: accesoModificacion,
                accesoCreacion: accesoCreacion
            });
        });

        console.log(selectedRows); // Mostrar en la consola las filas seleccionadas
    });
</script>

    <script>
    function addRowToTable() {
        const selectedCheckboxes = document.querySelectorAll('#list-group input[type="checkbox"]:checked');
        const dataTableBody = document.getElementById('dataTable').querySelector('tbody');
        
            selectedCheckboxes.forEach(checkbox => {
                const moduleId = checkbox.value;
                const moduleName = checkbox.nextElementSibling.textContent;
                
                // Check if a row for this module already exists
                const existingRow = dataTableBody.querySelector(`tr[data-module-id="${moduleId}"]`);
                if (!existingRow) {
                    const newRow = dataTableBody.insertRow();
                    newRow.setAttribute('data-module-id', moduleId);

                    const moduleIdCell = newRow.insertCell();
                    moduleIdCell.textContent = moduleId;
                    
                    const moduleNameCell = newRow.insertCell();
                    moduleNameCell.textContent = moduleName;

                    const checkboxCell = newRow.insertCell();
                    const checkboxInput = document.createElement('input');
                    checkboxInput.className = 'form-check-input';
                    checkboxInput.type = 'checkbox';
                    checkboxCell.appendChild(checkboxInput);

                    const checkboxCell2 = newRow.insertCell();
                    const checkboxInput2 = document.createElement('input');
                    checkboxInput2.className = 'form-check-input';
                    checkboxInput2.type = 'checkbox';
                    checkboxCell2.appendChild(checkboxInput2);

                    const checkboxCell3 = newRow.insertCell();
                    const checkboxInput3 = document.createElement('input');
                    checkboxInput3.className = 'form-check-input';
                    checkboxInput3.type = 'checkbox';
                    checkboxCell3.appendChild(checkboxInput3);

                    const checkboxCell4 = newRow.insertCell();
                    const checkboxInput4 = document.createElement('input');
                    checkboxInput4.className = 'form-check-input';
                    checkboxInput4.type = 'checkbox';
                    checkboxCell4.appendChild(checkboxInput4);
                    
                    // Add more cells with appropriate content if needed
                    
                    const deleteButtonCell = newRow.insertCell();
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Eliminar';
                    deleteButton.className = 'btn btn-danger btn-sm delete-row';
                    deleteButtonCell.appendChild(deleteButton);
                }
            });
        
        // Close the modal
        const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
        modal.hide();
        
        // Clear selected checkboxes in the modal
        selectedCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ... Your other code ...

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-row')) {
                const row = event.target.closest('tr');
                row.remove();
            }
        });
    });
</script>


<script>
    // Función para llenar el select con los datos obtenidos del API
function fillGrupoModuloSelect(data) {
  const selectElement = document.getElementById("id_grupo_modulo");
  data.forEach(item => {
    const option = document.createElement("option");
    option.value = item.id_grupo_modulo;
    option.textContent = item.nombre_grupo_modulo;
    selectElement.appendChild(option);
  });

  // Agregar evento "change" al select para realizar la búsqueda y listado al seleccionar un valor
  selectElement.addEventListener('change', function () {
    const codigo = this.value;
    fetch(`<?php echo URL_API_CARLITOS ?>/api-modulos.php?codigo=${codigo}`, {
      method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
      fillListGroup(data);
    })
    .catch(error => console.error('Error al obtener los datos del API:', error));
  });
}


// Función para llenar el list group con los datos obtenidos del API
function fillListGroup(data) {
  const listGroupElement = document.getElementById("list-group");
  listGroupElement.innerHTML = '';

  data.forEach(item => {
    const listItem = document.createElement("li");
    listItem.className = "list-group-item d-flex align-items-center";
    listItem.innerHTML = `
      <input type="checkbox" id="checkbox-${item.id_modulo}"  value="${item.id_modulo}" class="form-check-input" />
      <label for="checkbox-${item.id_modulo}" class="form-check-label p-2">${item.nombre_modulo}</label>
    `;
    listGroupElement.appendChild(listItem);
  });
}

// Llamada al API utilizando Fetch API
fetch('<?php echo URL_API_CARLITOS ?>/api-grupo_modulo.php', {
  method: 'GET',
})
.then(response => response.json())
.then(data => {
  fillGrupoModuloSelect(data);
})
.catch(error => console.error('Error al obtener los datos del API:', error));
</script>

<script>
  function Buscar() {
         let codigo = document.getElementById("nro_doc").value;
            buscarUsuarios(codigo);
            //console.log(variable);
            
        }
  function buscarUsuarios(codigo) {
    const apiUrl1 = `<?php echo URL_API_CARLITOS ?>/api-personanaturaljuridica.php?codigo=${codigo}`;
    const apiUrl2 = `<?php echo URL_API_CARLITOS ?>/api-terapistas.php?codigo=${codigo}`;
    
    // Solicitud a la primera API
    fetch(apiUrl1)
      .then(response => response.json())
      .then(dataApi1 => {
        // Procesar los datos de la primera API
        if (dataApi1.length > 0) {
          console.log("Datos de API 2:");
          //console.log(dataApi1);
          // Procesar los datos de la segunda API
          if (dataApi1.length > 0) {
                const primerUsuarioApi1 = dataApi1[0];
                document.getElementById("nombres").value = primerUsuarioApi1.nombres;
                document.getElementById("apellidos").value = primerUsuarioApi1.apellidos;
                document.getElementById("id_persona").value = primerUsuarioApi1.id_persona;
            } else {
                document.getElementById("nombres").value = "";
                document.getElementById("apellidos").value = "";
                document.getElementById("id_persona").value = "";
            }
        } else {
          console.log("No se encontraron datos en API 2.");
        }
      })
      .catch(error => {
        alert ('Ya hay un usuario registrado con ese DOCUMENTO!.');
        document.getElementById("nombres").value = "";
        document.getElementById("apellidos").value = "";
      });

    // Solicitud a la segunda API
    fetch(apiUrl2)
      .then(response => response.json())
      .then(dataApi2 => {
        // Procesar los datos de la segunda API
        if (dataApi2.length > 0) {
          console.log("Datos de API 5:");
         // console.log(dataApi2);
          // Procesar los datos de la segunda API
          if (dataApi2.length > 0) {
                const primerUsuarioApi2 = dataApi2[0];
                document.getElementById("nombres").value = primerUsuarioApi2.nombres;
                document.getElementById("apellidos").value = primerUsuarioApi2.apellidos;
                document.getElementById("id_persona").value = primerUsuarioApi2.id_profesional;
            } else {
                document.getElementById("nombres").value = "";
                document.getElementById("apellidos").value = "";
                document.getElementById("id_persona").value = "";
            }
        } else {
          console.log("No se encontraron datos en API 2.");
        }
      })
      .catch(error => {
        console.error('Error al obtener datos de la segunda API:', error);
      });
    }
    </script>
    <script>
         // Función para llenar el select con los datos obtenidos del API
         function fillTipoUsuarioSelect(data) {
            const selectElement = document.getElementById("id_tipo_de_usuario");
            data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.id_tipo_de_usuario;
                option.textContent = item.tipo_de_usuario;
                selectElement.appendChild(option);
            });
        }

        // Llamada al API utilizando Fetch API
        fetch('<?php echo URL_API_CARLITOS ?>/api-tipodeusuario.php', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            fillTipoUsuarioSelect(data);
            // console.log(data);
        })
        .catch(error => console.error('Error al obtener los datos del API:', error));

        // Función para llenar el select con los datos obtenidos del API
        function fillUnidaddeNegocioelect(data) {
            const selectElement = document.getElementById("id_unidad_de_negocio");
            data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.id_unidad_de_negocio;
                option.textContent = item.nombre_unidad_de_negocio;
                selectElement.appendChild(option);
            });
        }

        // Llamada al API utilizando Fetch API
        fetch('<?php echo URL_API_CARLITOS ?>/api-unidaddenegocio.php', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            fillUnidaddeNegocioelect(data);
            // console.log(data);
        })
        .catch(error => console.error('Error al obtener los datos del API:', error));


        // Manejar el envío del formulario para agregar un nuevo usuario
        const formularioUsuarios = document.getElementById('formulario-usuarios');
        formularioUsuarios.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(formularioUsuarios);
            const nuevoUsuario = {
                nro_doc: document.getElementById('nro_doc').value,
                usuario: document.getElementById('usuario').value,
                id_persona: document.getElementById('id_persona').value,
                clave: document.getElementById('clave').value,
                clave_pertura: document.getElementById('clave_apertura').value,
                id_unidad_de_negocio: document.getElementById('id_unidad_de_negocio').value,
                cargo: document.getElementById('cargo').value,
                id_tipo_de_usuario: document.getElementById('id_tipo_de_usuario').value,
                hora_ingreso: formData.get('hora_ingreso'),
                hora_salida: formData.get('hora_salida'),
                activo: formData.get('activo'),
                fecha_cese: formData.get('fecha_cese'),
                permisos: selectedRows
            };
            //console.log(nuevoUsuario);
            agregarUsuario(nuevoUsuario);
        });

        function agregarUsuario(usuario) {
            // Hacer la petición POST a la API para agregar un nuevo usuario
            fetch('<?php echo URL_API_CARLITOS ?>/api-usuario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(usuario)
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