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
      <h2 class="text-center">Gestionar Tipos de gastos</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="tabla-tipos-gasto" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">Nombre</th>
              <th class="text-center">Código contable</th>
              <th class="text-center">Borrar</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="nombre_gasto">Nombre</label>
            <input
              type="text"
              id="nombre_gasto"
              class="form-control"
              placeholder="Nombre"
            />
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="codigo-contable">Código contable</label>
            <input
              type="text"
              id="codigo-contable"
              class="form-control"
              placeholder="Código contable"
            />
          </div>
        </div>
        <div class="col-12 col-md-4 d-flex align-items-end">
          <button id="btn-agregar-tipo-gasto" class="btn btn-primary w-100">
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
  id="modal-borrar-tipo-gasto"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modal-borrar-tipo-gasto-label"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        ¿Está seguro que desea borrar el Tipo de Gasto?
      </div>

      <div class="modal-footer">
        <div class="row w-100">
          <div class="col-6">
            <button
              type="button"
              class="btn btn-danger w-100"
              id="btn-borrar-tipo-gasto"
              data-bs-dismiss="modal"
              onclick="borrarTipoGasto(idBorrar)"
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
  const apiTiposGastoUrl = "<?php echo URL_API_NUEVA ?>/tipos-gasto";

  let tiposGasto = [];
  let tablaTiposGastoBody = null;
  let modalConfirmar = null;
  let idBorrar = null;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    modalConfirmar = new bootstrap.Modal(
      document.getElementById("modal-borrar-tipo-gasto"),
      {
        backdrop: "static",
      }
    );

    tablaTiposGastoBody = document
      .getElementById("tabla-tipos-gasto")
      .querySelector("tbody");

    await cargarTiposGasto();
    prepararBotonAgregar();
  }

  async function cargarTiposGasto() {
    try {
      const response = await fetch(apiTiposGastoUrl);

      if (!response.ok) {
        throw new Error(response.statusText);
      }

      const data = await response.json();
      tiposGasto = data;

      cargarTiposGastoEnTabla();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los Tipos de gasto", "consultar");
    }
  }

  function cargarTiposGastoEnTabla() {
    tablaTiposGastoBody.innerHTML = "";

    tiposGasto.forEach((tipoGasto) => {
      const idTipoGasto = tipoGasto.id_tipo_de_gasto;

      const tr = tablaTiposGastoBody.insertRow();
      tr.dataset.idTipoGasto = idTipoGasto;

      const tdNombre = tr.insertCell();
      tdNombre.textContent = tipoGasto.nombre_gasto;
      tdNombre.classList.add("text-center");

      const tdCodigoContable = tr.insertCell();
      tdCodigoContable.textContent = tipoGasto.codigo_contable;
      tdCodigoContable.classList.add("text-center");

      const tdBorrar = tr.insertCell();
      tdBorrar.classList.add("text-center");
      const btnBorrar = document.createElement("button");
      btnBorrar.classList.add("btn", "btn-danger");
      btnBorrar.innerHTML = '<i class="fas fa-trash-alt"></i>';
      btnBorrar.addEventListener("click", (event) => {
        idBorrar = idTipoGasto;
        modalConfirmar.show();
      });
      tdBorrar.appendChild(btnBorrar);
    });
  }

  async function borrarTipoGasto(idTipoGasto) {
    const options = {
      method: "DELETE",
    };

    try {
      const response = await fetch(
        `${apiTiposGastoUrl}/${idTipoGasto}`,
        options
      );

      if (!response.ok) {
        throw new Error(response.statusText);
      }

      const data = await response.json();

      cargarTiposGasto();
      mostrarAlert("ok", "Tipo de gasto borrado correctamente", "borrar");
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al borrar el Tipo de gasto", "borrar");
    }
  }

  function prepararBotonAgregar() {
    const btnAgregarProducto = document.getElementById(
      "btn-agregar-tipo-gasto"
    );
    btnAgregarProducto.addEventListener("click", async () => {
      const nombre_gasto = document.getElementById("nombre_gasto").value;
      const codigo_contable = document.getElementById("codigo-contable").value;

      const tipoGasto = {
        nombre_gasto,
        codigo_contable,
      };

      const options = {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(tipoGasto),
      };

      try {
        const response = await fetch(apiTiposGastoUrl, options);

        if (!response.ok) {
          throw new Error(response.statusText);
        }

        const data = await response.json();

        cargarTiposGasto();
        mostrarAlert("ok", "Tipo de gasto agregado correctamente", "crear");
      } catch (error) {
        console.error(error);
        mostrarAlert("error", "Error al crear el Tipo de gasto", "crear");
      }
    });
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
