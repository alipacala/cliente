<?php
require "../../inc/header.php";

session_start();

$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado);
?>
<div class="container my-5 main-cont">
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">FORMULARIO DE MODULOS</h2>
    </div>
    <div class="card-body">
      <form id="formulario-modulos">
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="fechaNacimiento">Grupo Modulo:</label>
            <select class="form-select" id="id_grupo_modulo"></select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="apellidoPaterno">Nombre Modulo:</label>
            <input type="text" class="form-control" id="nombre_modulo" />
          </div>
          <div class="form-group col-md-4">
            <label for="apellidoMaterno">descripcion:</label>
            <input type="text" class="form-control" id="descripcion" />
          </div>
          <div class="form-group col-md-4">
            <label for="nombres">Archivo Acceso:</label>
            <div class="input-group">
              <input type="file" class="form-control" id="archivo_acceso" />
              <label class="input-group-text" for="inputGroupFile02"
                >Subir</label
              >
            </div>
          </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-primary">Agregar Modulo</button>
        </div>
      </form>
      <br />
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Grupo</th>
            <th>Modulo</th>
            <th>Descripcion</th>
            <th>Archivo Acceso</th>
          </tr>
        </thead>
        <tbody id="data-table-body"></tbody>
      </table>
    </div>
  </div>
</div>
<script>
  // Función para obtener los datos del API utilizando Fetch
  console.log("<?php echo URL_API_CARLITOS ?>/api-modulos.php");
  function getDataFromAPI() {
    fetch("<?php echo URL_API_CARLITOS ?>/api-modulos.php", {
      method: "INNER",
    })
      .then((response) => response.json())
      .then((data) => {
        //console.log(data);
        // Limpia la tabla antes de agregar nuevos datos
        const tableBody = document.getElementById("data-table-body");
        tableBody.innerHTML = "";

        // Recorre los datos y agrega cada fila a la tabla
        data.forEach((item) => {
          const row = document.createElement("tr");
          row.innerHTML = `
                            <td>${item.id_modulo}</td>
                            <td>${item.nombre_grupo_modulo}</td>
                            <td>${item.nombre_modulo}</td>
                            <td>${item.descripcion}</td>
                            <td>${item.archivo_acceso}</td>
                        `;
          tableBody.appendChild(row);
        });
      })
      .catch((error) =>
        console.error("Error al obtener datos del API:", error)
      );
  }

  // Llama a la función para obtener y mostrar los datos del API
  getDataFromAPI();
</script>
<script>
  // Función para llenar el select con los datos obtenidos del API
  function fillGrupoModuloSelect(data) {
    const selectElement = document.getElementById("id_grupo_modulo");
    data.forEach((item) => {
      const option = document.createElement("option");
      option.value = item.id_grupo_modulo;
      option.textContent = item.nombre_grupo_modulo;
      selectElement.appendChild(option);
    });
  }

  // Llamada al API utilizando Fetch API
  fetch("<?php echo URL_API_CARLITOS ?>/api-grupo_modulo.php", {
    method: "GET",
  })
    .then((response) => response.json())
    .then((data) => {
      fillGrupoModuloSelect(data);
      // console.log(data);
    })
    .catch((error) =>
      console.error("Error al obtener los datos del API:", error)
    );
</script>
<script>
  // Manejar el envío del formulario para agregar un nuevo usuario
  const formularioModulos = document.getElementById("formulario-modulos");
  formularioModulos.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(formularioModulos);
    const nuevoModulos = {
      nombre_modulo: document.getElementById("nombre_modulo").value,
      descripcion: document.getElementById("descripcion").value,
      archivo_acceso: document.getElementById("archivo_acceso").value,
      id_grupo_modulo: document.getElementById("id_grupo_modulo").value,
    };
    //console.log(nuevoModulos);
    agregarModulo(nuevoModulos);
  });

  function agregarModulo(modulos) {
    // Hacer la petición POST a la API para agregar un nuevo usuario
    fetch("<?php echo URL_API_CARLITOS ?>/api-modulos.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(modulos),
    })
      .then((response) => response.text())
      .then((data) => {
        console.log(data); // Mostrar mensaje de éxito o error de la API
        //obtenerUsuarios(); // Actualizar la lista de usuarios después de agregar uno nuevo
      })
      .catch((error) => console.error("Error:", error));
    window.location.reload();
  }
</script>

<?php
require "../../inc/footer.php";
?>
