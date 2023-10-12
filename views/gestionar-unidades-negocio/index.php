<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); ?>

<div class="container my-5 main-cont">
  <div id="alert-place"></div>
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">Gestionar Unidades de negocio</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table
          id="tabla-unidades-negocio"
          class="table table-bordered table-hover"
        >
          <thead>
            <tr>
              <th class="text-center">Código</th>
              <th class="text-center">Nombre Unidad de Negocio</th>
              <th class="text-center">Borrar</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="codigo_unidad_de_negocio">Código</label>
            <input
              type="text"
              id="codigo_unidad_de_negocio"
              class="form-control"
              placeholder="Código"
            />
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="nombre_unidad_de_negocio"
              >Nombre Unidad de Negocio</label
            >
            <input
              type="text"
              id="nombre_unidad_de_negocio"
              class="form-control"
              placeholder="Nombre Unidad de Negocio"
            />
          </div>
        </div>
        <div class="col-12 col-md-4 d-flex align-items-end">
          <button id="btn-agregar-unidad-negocio" class="btn btn-primary w-100">
            Agregar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- modal para confirmar el borrado -->

<div
  class="modal fade"
  id="modal-borrar-unidad-negocio"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modal-borrar-unidad-negocio-label"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        ¿Está seguro que desea borrar la Unidad de Negocio?
      </div>

      <div class="modal-footer">
        <div class="row w-100">
          <div class="col-6">
            <button
              type="button"
              class="btn btn-danger w-100"
              id="btn-borrar-unidad-negocio"
              data-bs-dismiss="modal"
              onclick="borrarUnidadNegocio(idBorrar)"
            >
              Sí
            </button>
          </div>
          <div class="col-6">
            <button
              type="button"
              class="btn btn-outline-secondary w-100"
              data-bs-dismiss="modal"
            >
              No
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiUnidadesNegocioUrl = "<?php echo URL_API_NUEVA ?>/unidades-negocio";

  let unidadesNegocio = [];
  let tablaUnidadesNegocioBody = null;
  let modalConfirmar = null;
  let idBorrar = null;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    modalConfirmar = new bootstrap.Modal(
      document.getElementById("modal-borrar-unidad-negocio"),
      {
        backdrop: "static",
      }
    );

    tablaUnidadesNegocioBody = document
      .getElementById("tabla-unidades-negocio")
      .querySelector("tbody");

    await cargarUnidadesNegocio();
    prepararBotonAgregar();
  }

  async function cargarUnidadesNegocio() {
    try {
      const response = await fetch(apiUnidadesNegocioUrl);

      if (!response.ok) {
        throw new Error(response.statusText);
      }

      const data = await response.json();
      unidadesNegocio = data;

      cargarUnidadesNegocioEnTabla();
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las Unidades de Negocio",
        "consultar"
      );
    }
  }

  function cargarUnidadesNegocioEnTabla() {
    tablaUnidadesNegocioBody.innerHTML = "";

    unidadesNegocio.forEach((unidadNegocio) => {
      const idUnidadNegocio = unidadNegocio.id_unidad_de_negocio;

      const tr = tablaUnidadesNegocioBody.insertRow();
      tr.dataset.idUnidadNegocio = idUnidadNegocio;

      const tdCodigo = tr.insertCell();
      tdCodigo.textContent = unidadNegocio.codigo_unidad_de_negocio;
      tdCodigo.classList.add("text-center");

      const tdNombre = tr.insertCell();
      tdNombre.textContent = unidadNegocio.nombre_unidad_de_negocio;
      tdNombre.classList.add("text-center");

      const tdBorrar = tr.insertCell();
      tdBorrar.classList.add("text-center");
      const btnBorrar = document.createElement("button");
      btnBorrar.classList.add("btn", "btn-danger");
      btnBorrar.innerHTML = '<i class="fas fa-trash-alt"></i>';
      btnBorrar.addEventListener("click", (event) => {
        idBorrar = idUnidadNegocio;
        modalConfirmar.show();
      });
      tdBorrar.appendChild(btnBorrar);
    });
  }

  async function borrarUnidadNegocio(idUnidadNegocio) {
    const options = {
      method: "DELETE",
    };

    try {
      const response = await fetch(
        `${apiUnidadesNegocioUrl}/${idUnidadNegocio}`,
        options
      );

      if (!response.ok) {
        throw new Error(response.statusText);
      }

      const data = await response.json();
      cargarUnidadesNegocio();
      mostrarAlert("ok", "Unidad de negocio borrada correctamente", "borrar");
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al borrar la Unidad de negocio", "borrar");
    }
  }

  function prepararBotonAgregar() {
    const btnAgregarUnidadNegocio = document.getElementById(
      "btn-agregar-unidad-negocio"
    );
    btnAgregarUnidadNegocio.addEventListener("click", async () => {
      const codigo_unidad_de_negocio = document.getElementById(
        "codigo_unidad_de_negocio"
      ).value;
      const nombre_unidad_de_negocio = document.getElementById(
        "nombre_unidad_de_negocio"
      ).value;

      const unidadNegocio = {
        codigo_unidad_de_negocio,
        nombre_unidad_de_negocio,
      };

      const options = {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(unidadNegocio),
      };

      try {
        const response = await fetch(apiUnidadesNegocioUrl, options);

        if (!response.ok) {
          throw new Error(response.statusText);
        }

        const data = await response.json();
        cargarUnidadesNegocio();
        mostrarAlert("ok", "Unidad de Negocio agregada correctamente", "crear");
      } catch (error) {
        console.error(error);
        mostrarAlert("error", "Error al crear la Unidad de Negocio", "crear");
      }
    });
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
