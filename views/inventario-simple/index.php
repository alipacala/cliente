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
      <h2 class="text-center">Inventario simple</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-2">
          <label for="ubicacion">Ubicación:</label>
        </div>
        <div class="col-md-3">
          <select
            name="ubicacion"
            id="ubicacion"
            class="form-select"
            onchange="buscarProductos()"
          ></select>
        </div>

        <div class="col-md-6 ms-auto d-flex align-items-center">
          <div class="row">
            <div class="col-auto d-flex align-items-center">
              <span>Rango de Fechas del:</span>
            </div>
            <div class="col-auto">
              <input type="date" class="form-control" id="fecha-inicio"
              name="fecha-inicio" onchange="buscarProductos()" value="<?php echo date("Y-m-d") ?>"
              />
            </div>
            <div class="col-auto d-flex align-items-center">
              <span>al:</span>
            </div>
            <div class="col-auto">
              <input type="date" class="form-control" id="fecha-fin"
              name="fecha-fin" onchange="buscarProductos()" value="<?php echo date("Y-m-d") ?>"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-2">
          <label for="tipo_producto">Tipo de Producto:</label>
        </div>
        <div class="col-md-3">
          <select
            name="tipo_producto"
            id="tipo_producto"
            class="form-select"
            onchange="buscarProductos(event)"
          ></select>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-2">
          <label for="grupos_catalogo">Grupos del Catálogo:</label>
        </div>
        <div class="col-md-3">
          <select
            name="grupos_catalogo"
            id="grupos_catalogo"
            class="form-select"
            onchange="buscarProductos(event)"
          ></select>
        </div>
      </div>

      <h5>Listado de documentos movimiento</h5>

      <div class="table-responsive">
        <table id="tabla-documentos" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center" rowspan="2">TIPO / GRUPOS / PRODUCTO</th>
              <th class="text-center" rowspan="2">ANT</th>
              <th class="text-center" rowspan="2">INGRESO</th>
              <th class="text-center" colspan="2">SALIDAS</th>
              <th class="text-center" rowspan="2">EXISTENCIA</th>
              <th class="text-center" rowspan="2">UNIDAD</th>
            </tr>
            <tr>
              <th class="text-center">OTROS</th>
              <th class="text-center">VENTAS</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-borrar-compra"
  tabindex="-1"
  aria-labelledby="modal-borrar-compra-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="modal-borrar-compra-label">
          ¿Está seguro que desea borrar este documento movimiento?
        </h5>
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <button
            type="button"
            class="btn btn-danger col-md-6"
            onclick="borrarDocMovimiento(event)"
            data-bs-dismiss="modal"
          >
            Sí
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            id="confirmar-borrar-compra"
            data-bs-dismiss="modal"
          >
            No
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";
  const apiUnidadesNegocioUrl = "<?php echo URL_API_NUEVA ?>/unidades-negocio";
  const apiTiposProductoUrl = "<?php echo URL_API_NUEVA ?>/tipos-productos";
  const apiGruposCartaUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";

  let tablaDocumentosBody = null;

  let modalBorrarCompra = null;

  let idDocumento = null;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    cargarUnidadesNegocio();
    cargarTiposProductos();
    cargarGruposCarta();

    tablaDocumentosBody = document
      .getElementById("tabla-documentos")
      .querySelector("tbody");

    modalBorrarCompra = new bootstrap.Modal(
      document.getElementById("modal-borrar-compra")
    );

    prepararSelectUnidad();
    buscarProductos();
  }

  function prepararSelectUnidad() {
    const selectUnidad = document.getElementById("ubicacion");
    const idUnidadNegocioUsuario =
      '<?php echo $_SESSION["usuario"]["id_unidad_de_negocio"] ?>';

    selectUnidad.value = idUnidadNegocioUsuario;
  }

  async function cargarUnidadesNegocio() {
    const selectUnidadesNegocio = document.getElementById("ubicacion");
    const idUnidadNegocioUsuario =
      '<?php echo $_SESSION["usuario"]["id_unidad_de_negocio"] ?>';

    try {
      const response = await fetch(apiUnidadesNegocioUrl);
      const unidadesNegocio = await response.json();

      unidadesNegocio.forEach((unidadNegocio) => {
        const option = document.createElement("option");
        option.value = unidadNegocio.id_unidad_de_negocio;
        option.innerHTML = unidadNegocio.nombre_unidad_de_negocio;

        selectUnidadesNegocio.appendChild(option);
      });

      selectUnidadesNegocio.value = idUnidadNegocioUsuario;
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo cargar las unidades de negocio",
        "consultar"
      );
    }
  }

  async function cargarTiposProductos() {
    const selectTiposProducto = document.getElementById("tipo_producto");

    try {
      const response = await fetch(apiTiposProductoUrl);
      const tiposProducto = await response.json();

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.innerHTML = "Seleccione un tipo de producto";

      selectTiposProducto.appendChild(defaultOption);

      tiposProducto.forEach((tipoProducto) => {
        const option = document.createElement("option");
        option.value = tipoProducto.id_tipo_producto;
        option.innerHTML = tipoProducto.nombre_tipo_de_producto;

        selectTiposProducto.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo cargar los tipos de producto",
        "consultar"
      );
    }
  }

  async function cargarGruposCarta() {
    const selectGruposCarta = document.getElementById("grupos_catalogo");

    try {
      const response = await fetch(apiGruposCartaUrl);
      const gruposCarta = await response.json();

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.innerHTML = "Seleccione un grupo de carta";

      selectGruposCarta.appendChild(defaultOption);

      gruposCarta.forEach((grupoCarta) => {
        const option = document.createElement("option");
        option.value = grupoCarta.codigo_grupo;
        option.innerHTML = grupoCarta.nombre_grupo;

        selectGruposCarta.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo cargar los grupos de carta",
        "consultar"
      );
    }
  }

  function prepararInputsFechas() {
    const fechaInicio = document.getElementById("fecha-inicio");
    const fechaFin = document.getElementById("fecha-fin");

    fechaInicio.addEventListener("change", buscarProductos);
    fechaFin.addEventListener("change", buscarProductos);
  }

  function prepararUrlParams(reporte = false) {
    const fechaInicio = document.getElementById("fecha-inicio").value;
    const fechaFin = document.getElementById("fecha-fin").value;
    const ubicacion = document.getElementById("ubicacion").value;
    const tipoProducto = document.getElementById("tipo_producto").value;
    const grupoCarta = document.getElementById("grupos_catalogo").value;

    const usuario = '<?php echo $_SESSION["usuario"]["id_usuario"] ?>';

    let urlParams = reporte ? "tipo=inventario&" : "inventario&";

    if (fechaInicio && fechaFin) {
      urlParams += `fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }
    if (ubicacion) urlParams += `&unidad_negocio=${ubicacion}`;
    else urlParams += `&unidad_negocio=3`;
    if (tipoProducto) urlParams += `&tipo_producto=${tipoProducto}`;
    if (grupoCarta) urlParams += `&grupo=${grupoCarta}`;

    urlParams += `&id_usuario=${usuario}`;

    return urlParams;
  }

  async function buscarProductos(event = null) {
    const tipoProducto = document.getElementById("tipo_producto");
    const grupoCarta = document.getElementById("grupos_catalogo");

    if (event != null) {
      if (event.target.id === "tipo_producto") {
        grupoCarta.value = "";
      } else if (event.target.id === "grupos_catalogo") {
        tipoProducto.value = "";
      }
    }

    const url = apiProductosUrl + "?" + prepararUrlParams();
    await cargarProductos(url);
  }

  async function borrarDocMovimiento(event) {
    event.preventDefault();

    const url = `${apiProductosUrl}/${idDocumento}/detalles`;
    const options = {
      method: "DELETE",
    };

    try {
      const response = await fetch(url, options);

      if (response.ok) {
        mostrarAlert(
          "ok",
          "Documento movimiento borrado correctamente",
          "borrar"
        );
        buscarProductos();
      } else {
        mostrarAlert(
          "error",
          "No se pudo borrar el documento movimiento",
          "borrar"
        );
      }
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo borrar el documento movimiento",
        "borrar"
      );
    }
  }

  async function cargarProductos(url) {
    try {
      const response = await fetch(url);
      const tipos = await response.json();

      tablaDocumentosBody.innerHTML = "";

      for (let tipo in tipos) {
        const rowTipoProducto = tablaDocumentosBody.insertRow();
        rowTipoProducto.innerHTML = `
          <td colspan="7">
            <strong>${tipo}</strong>
          </td>
        `;

        for (let grupo in tipos[tipo]) {
          const rowGrupoCarta = tablaDocumentosBody.insertRow();
          rowGrupoCarta.innerHTML = `
            <td colspan="7">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>${grupo}</strong>
            </td>
          `;

          tipos[tipo][grupo].forEach((producto) => {
            const rowProductoCarta = tablaDocumentosBody.insertRow();
            rowProductoCarta.innerHTML = ` 
              <td>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${producto.nombre_producto}
              </td>
              <td class="text-center">${(+producto.ANT).toFixed(0)}</td>
              <td class="text-center">${(+producto.INGRESO).toFixed(0)}</td>
              <td class="text-center">${(+producto.SALIDAS_OTROS).toFixed(0)}</td>
              <td class="text-center">${(+producto.SALIDAS_VENTAS).toFixed(0)}</td>
              <td class="text-center fw-bold">${(+producto.EXISTENCIA).toFixed(0)}</td>
              <td class="text-center">${producto.tipo_de_unidad ?? ""}</td>
            `;
          });
        }
      }
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo cargar los documentos movimiento",
        "consultar"
      );
    }
  }

  function alBorrarDocumento(event, id) {
    event.preventDefault();
    event.stopPropagation();

    idDocumento = id;
    modalBorrarCompra.show();
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
