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
      <h2 class="text-center">Registro de ingresos / Registro de egresos</h2>
    </div>
    <div class="card-body">
      <form id="form-registrar-comprobante">
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="tipo_movimiento">Movimiento de</label>
            <select
              class="form-select"
              id="tipo_movimiento"
              name="tipo_movimiento"
              required
            >
              <option value="">Seleccione un tipo de movimiento</option>
              <option value="IN">Ingreso</option>
              <option value="SA">Egreso</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="tipo_documento">Tipo de documento</label>
            <select
              class="form-select"
              id="tipo_documento"
              name="tipo_documento"
              required
              onchange="alCambiarTipoDocumento(event)"
            >
              <option value="">Seleccione un tipo de comprobante</option>
              <option value="GR">Guía de remisión</option>
              <option value="GI">Guía interna</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="nro_documento">Nro de Documento</label>
            <input
              type="text"
              class="form-control"
              id="nro_documento"
              name="nro_documento"
              disabled
            />
          </div>
          <div class="form-group col-md-3">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha"
            value="<?php echo date("Y-m-d") ?>" required />
          </div>
        </div>
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="nro_documento_proveedor">Nro Documento Proveedor</label>
            <div class="input-group">
              <input
                type="text"
                class="form-control"
                id="nro_documento_proveedor"
                name="nro_documento_proveedor"
                disabled
              />
              <div class="input-group-text">
                <span
                  class="spinner-border spinner-border-sm invisible"
                  id="spinner"
                  role="status"
                ></span>
              </div>
            </div>
          </div>
          <div class="form-group col-md-3">
            <label for="nombre_proveedor">Nombre Razón Social Proveedor</label>
            <input
              class="form-control"
              id="nombre_proveedor"
              name="nombre_proveedor"
              disabled
            />
          </div>
          <div class="form-group col-md-3">
            <label for="nro_orden_compra">Nro Orden de Compra</label>
            <input
              type="text"
              class="form-control"
              id="nro_orden_compra"
              name="nro_orden_compra"
              required
            />
          </div>
          <div class="form-group col-md-3">
            <label for="fecha_recepcion">Fecha de Recepción</label>
            <input type="date" class="form-control" id="fecha_recepcion"
            name="fecha_recepcion" value="<?php echo date("Y-m-d") ?>" required
            />
          </div>
        </div>
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="origen">Origen</label>
            <select
              class="form-select"
              id="origen"
              name="origen"
              required
            ></select>
          </div>
          <div class="form-group col-md-3">
            <label for="destino">Destino</label>
            <select
              class="form-select"
              id="destino"
              name="destino"
              required
            ></select>
          </div>
          <div class="form-group col-md-3">
            <label for="motivo">Motivo</label>
            <input
              type="text"
              class="form-control"
              id="motivo"
              name="motivo"
              required
            />
          </div>
          <div class="form-group col-md-3">
            <label for="observaciones">Observaciones</label>
            <input
              type="text"
              class="form-control"
              id="observaciones"
              name="observaciones"
              required
            />
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <h4>Productos / Insumos</h4>
          </div>
          <div class="card-body">
            <button
              type="button"
              class="btn btn-success mb-3"
              data-id="0"
              data-bs-toggle="modal"
              data-bs-target="#modal-detalle"
            >
              Agregar Producto / Insumo
            </button>

            <div class="table-responsive">
              <table
                class="table table-bordered table-hover"
                id="tabla-detalles"
              >
                <thead class="thead-dark">
                  <tr class="text-center">
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>T. Unidad</th>
                    <th>P. Unitario</th>
                    <th>Subtotal</th>
                    <th>Borrar</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>

        <input
          type="submit"
          class="btn btn-primary"
          id="registrar-comprobante"
          value="Registrar comprobante de compra"
        />
        <a class="btn btn-warning" href="./../relacion-cuentas-por-pagar/"
          >Salir</a
        >
      </form>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-detalle"
  tabindex="-1"
  aria-labelledby="modal-detalle-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-detalle-label">Agregar Producto</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-detalle"
        ></button>
      </div>
      <div class="modal-body">
        <form id="form-crear-insumo">
          <div class="form-group col-md-8">
            <label for="producto">Producto</label>
            <select
              class="form-select"
              id="producto"
              name="producto"
              required
            ></select>
          </div>

          <div class="form-group mb-3">
            <label for="cantidad">Cantidad</label>
            <input
              type="number"
              class="form-control"
              id="cantidad"
              name="cantidad"
              value="1"
              onchange="alCambiarCantidad(event)"
              required
            />
          </div>

          <div class="form-group mb-3">
            <label for="tipo_unidad_detalle">Tipo de Unidad</label>
            <select
              class="form-select"
              id="tipo_unidad_detalle"
              name="tipo_unidad_detalle"
              required
            >
              <option value="UN" selected>UNIDAD</option>
              <option value="KG">KILO</option>
              <option value="GR">GRAMO</option>
              <option value="LT">LITRO</option>
              <option value="ML">MILILITRO</option>
              <option value="OZ">ONZA</option>
            </select>
          </div>

          <div class="form-group mb-3">
            <label for="precio_unitario">P. Unitario</label>
            <input
              type="text"
              class="form-control"
              id="precio_unitario"
              name="precio_unitario"
              onchange="alCambiarPrecioUnitario(event)"
              required
            />
          </div>

          <div class="form-group mb-3">
            <label for="subtotal">Subtotal</label>
            <input
              type="text"
              class="form-control"
              id="subtotal"
              name="subtotal"
              disabled
            />
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            data-bs-dismiss="modal"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="btn btn-primary col-md-6"
            id="agregar-producto"
            disabled
          >
            Agregar Producto
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiConfigUrl = "<?php echo URL_API_NUEVA ?>/config";

  const apiTipoDeProductoUrl = "<?php echo URL_API_NUEVA ?>/tipos-productos";
  const apiGruposDeLaCartaUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";
  const apiCentralDeCostosUrl = "<?php echo URL_API_NUEVA ?>/centrales-costos";
  const apiTiposGastoUrl = "<?php echo URL_API_NUEVA ?>/tipos-gasto";
  const apiUsuariosUrl = "<?php echo URL_API_NUEVA ?>/usuarios";
  const apiUnidadesNegocioUrl = "<?php echo URL_API_NUEVA ?>/unidades-negocio";
  const apiSunatUrl = "<?php echo URL_API_NUEVA ?>/sunat";
  const apiDocumentosMovimientoUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-movimiento";

  let tiposGasto;
  let usuarios;
  let unidadesNegocio;

  let insumosCargados = [];
  let insumosAgregados = [];
  let detallesDeTabla = [];
  let idsInsumosEliminados = [];

  let tablaDetallesBody = null;

  let modalCrearDetalle = null;

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarUnidadesNegocio();

    alCambiarNroDoc();

    tablaDetallesBody = document
      .getElementById("tabla-detalles")
      .querySelector("tbody");

    await cargarProductos();

    modalCrearDetalle = new bootstrap.Modal(
      document.getElementById("modal-detalle")
    );

    prepararFormularioComprobante();
  }

  let iterador = 1;

  async function cargarUnidadesNegocio() {
    try {
      const response = await fetch(apiUnidadesNegocioUrl);
      const data = await response.json();

      unidadesNegocio = data;

      const origenSelect = document.getElementById("origen");
      const destinoSelect = document.getElementById("destino");
      origenSelect.innerHTML = "";
      destinoSelect.innerHTML = "";

      const defaultOption1 = document.createElement("option");
      defaultOption1.value = "";
      defaultOption1.text = "Seleccione una unidad de negocio";
      const defaultOption2 = defaultOption1.cloneNode(true);

      origenSelect.appendChild(defaultOption1);
      destinoSelect.appendChild(defaultOption2);

      data.forEach((unidadNegocio) => {
        const option1 = document.createElement("option");
        option1.value = unidadNegocio.id_unidad_de_negocio;
        option1.textContent = unidadNegocio.nombre_unidad_de_negocio;
        const option2 = option1.cloneNode(true);

        origenSelect.appendChild(option1);
        destinoSelect.appendChild(option2);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las unidades de negocio",
        "consultar"
      );
    }
  }

  async function cargarCodigoPedido() {
    const url = apiConfigUrl + "/25/codigo"; // 25 es el id de las guias internas

    try {
      const response = await fetch(url);
      const data = await response.json();

      return data.codigo;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar el código del pedido", "crear");
    }
  }

  async function alCambiarNroDoc() {
    const nroDocumento = document.getElementById("nro_documento_proveedor");

    nroDocumento.addEventListener("change", async (event) => {
      const nroDocumentoValor = event.target.value;
      if (!nroDocumentoValor) {
        return;
      }

      const url = `${apiSunatUrl}?tipo=RUC&nro=${nroDocumentoValor}`;

      const spinner = document.getElementById("spinner");
      spinner.classList.add("visible");
      spinner.classList.remove("invisible");

      try {
        const response = await fetch(url);
        const data = await response.json();
        const personaNaturalJuridica = data;

        spinner.classList.remove("visible");
        spinner.classList.add("invisible");

        const nombre = document.getElementById("nombre_proveedor");

        nombre.value = personaNaturalJuridica.nombre;

        if (!limpiarGuiones(personaNaturalJuridica.direccion) == "") {
          direccion.value = limpiarGuiones(personaNaturalJuridica.direccion);
        }

        if (!limpiarGuiones(personaNaturalJuridica.lugar) == "") {
          lugar.value = limpiarGuiones(personaNaturalJuridica.lugar);
        }
      } catch (error) {
        console.error(error);
        mostrarAlert(
          "error",
          "Error al cargar los datos de la persona",
          "consultar"
        );
      }
    });
  }

  async function cargarProductos() {
    try {
      const response = await fetch(apiProductosUrl);
      let data = await response.json();

      data = data.filter(
        (producto) =>
          producto.id_tipo_de_producto == 10 && producto.tipo != "RST"
      );

      const productosSelect = document.getElementById("producto");
      productosSelect.innerHTML = "";

      const option = document.createElement("option");
      option.value = "";
      option.textContent = "Seleccione un producto";
      productosSelect.appendChild(option);

      data.forEach((insumo) => {
        const option = document.createElement("option");

        insumosCargados = [...insumosCargados, insumo];

        option.value = insumo.id_producto;
        option.textContent = insumo.nombre_producto;
        productosSelect.appendChild(option);
      });

      limpiarFormularioDetalle();

      productosSelect.addEventListener("change", alCambiarInsumo);

      // seleccionar la opción 0
      productosSelect.value = "0";
      // lanzar el evento change para que se carguen los datos del producto
      productosSelect.dispatchEvent(new Event("change"));

      const agregarInsumoButton = document.getElementById("agregar-producto");
      agregarInsumoButton.addEventListener("click", alAgregarInsumo);
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los productos", "consultar");
    }
  }

  function alCambiarCantidad(event) {
    const cantidad = event.target.value;
    const precioUnitario = document.getElementById("precio_unitario").value;
    const subtotal = document.getElementById("subtotal");

    subtotal.value = cantidad * precioUnitario;
  }

  function alCambiarPrecioUnitario(event) {
    const precioUnitario = event.target.value;
    const cantidad = document.getElementById("cantidad").value;
    const subtotal = document.getElementById("subtotal");

    subtotal.value = cantidad * precioUnitario;
  }

  function alCambiarInsumo() {
    const productoSelect = document.getElementById("producto");
    const agregarInsumoButton = document.getElementById("agregar-producto");

    if (productoSelect.value) {
      agregarInsumoButton.disabled = false;
    } else {
      agregarInsumoButton.disabled = true;
      return;
    }

    const productoSeleccionado = insumosCargados.find(
      (insumo) => insumo.id_producto == productoSelect.value
    );

    // si el producto no tiene costo unitario, muestra 0
    productoSeleccionado.costo_unitario = productoSeleccionado.costo_unitario
      ? productoSeleccionado.costo_unitario
      : 0;

    const cantidadInput = document.getElementById("cantidad");
    const tipoUnidadDetalleInput = document.getElementById(
      "tipo_unidad_detalle"
    );
    const costoUnitarioInput = document.getElementById("precio_unitario");
    const subtotalInput = document.getElementById("subtotal");

    tipoUnidadDetalleInput.value = productoSeleccionado.tipo_de_unidad;
    costoUnitarioInput.value = productoSeleccionado.costo_unitario;

    subtotalInput.value =
      +productoSeleccionado.costo_unitario * +cantidadInput.value;
  }

  function actualizarTabla() {
    tablaDetallesBody.innerHTML = "";

    detallesDeTabla.forEach((insumo) => {
      agregarInsumoATabla(insumo);
    });
  }

  async function registrarDocumentoMovimiento() {
    const documentoMovimiento = {
      tipo_movimiento: document.getElementById("tipo_movimiento").value,
      tipo_documento: document.getElementById("tipo_documento").value,

      fecha_recepcion: document.getElementById("fecha_recepcion").value,

      id_unidad_de_negocio: document.getElementById("origen").value,
      motivo: document.getElementById("motivo").value,
      observaciones: document.getElementById("observaciones").value,

      id_usuario: '<?php echo $_SESSION["usuario"]["id_usuario"] ?>',
    };

    if (documentoMovimiento.tipo_documento == "GR") {
      documentoMovimiento.nro_documento =
        document.getElementById("nro_documento").value;
      documentoMovimiento.fecha_documento =
        document.getElementById("fecha").value;
      documentoMovimiento.nro_documento_proveedor = document.getElementById(
        "nro_documento_proveedor"
      ).value;
      documentoMovimiento.nombre_proveedor =
        document.getElementById("nombre_proveedor").value;
      documentoMovimiento.nro_orden_compra =
        document.getElementById("nro_orden_compra").value;
    } else if (documentoMovimiento.tipo_documento == "GI") {
      documentoMovimiento.destino = document.getElementById("destino").value;
    }

    documentoMovimiento.detalles = detallesDeTabla.map((detalle) => {
      return {
        id_producto: detalle.id_insumo,
        tipo_de_unidad: detalle.tipo_de_unidad,
        cantidad: detalle.cantidad,
        precio_unitario: detalle.costo_unitario,
      };
    });

    const url = apiDocumentosMovimientoUrl + "/ingreso-egreso";

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(documentoMovimiento),
    };

    try {
      const response = await fetch(url, options);

      if (!response.ok) {
        const data = await response.json();
        console.log(data);
        mostrarAlert(
          "error",
          "Error al registrar el Comprobante de compra",
          "crear"
        );
        return;
      }

      window.location.href =
        "./../relacion-cuentas-por-pagar/?ok&mensaje=Comprobante de compra registrada correctamente&op=crear";
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al registrar el Comprobante de compra",
        "crear"
      );
    }
  }

  function prepararFormularioComprobante() {
    const form = document.getElementById("form-registrar-comprobante");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      registrarDocumentoMovimiento();
    });
  }

  function prepararFormularioInsumo() {
    const form = document.getElementById("form-crear-insumo");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      alAgregarInsumo();
    });
  }

  function alAgregarInsumo() {
    const selectProducto = document.getElementById("producto");

    const detalle = {
      id_insumo: selectProducto.value,
      nombre_insumo: selectProducto.options[selectProducto.selectedIndex].text,
      cantidad: document.getElementById("cantidad").value,
      tipo_de_unidad: document.getElementById("tipo_unidad_detalle").value,
      costo_unitario: document.getElementById("precio_unitario").value,
      costo: document.getElementById("subtotal").value,
      idFila: iterador++,
    };

    detallesDeTabla.push(detalle);
    insumosAgregados.push(detalle);

    actualizarTabla();
    modalCrearDetalle.hide();
    calcularCostoTotal();
    limpiarFormularioDetalle();
  }

  function calcularCostoTotal() {
    const total = detallesDeTabla.reduce(
      (acumulador, insumo) => +(acumulador + +insumo.costo).toFixed(2),
      0
    );

    // agregar fila de total
    const filaTotal = tablaDetallesBody.insertRow();

    const celdaTotal = filaTotal.insertCell(0);
    celdaTotal.colSpan = 4;
    celdaTotal.classList.add("text-end");
    celdaTotal.textContent = "TOTAL";

    const celdaTotalValor = filaTotal.insertCell(1);
    celdaTotalValor.classList.add("text-end");
    celdaTotalValor.textContent = formatearCantidad(total);
  }

  function alCambiarAfectoPercepcion(event) {
    calcularCostoTotal();
  }

  async function alCambiarTipoDocumento(event) {
    const tipoComprobante = event.target.value;
    const nroComprobante = document.getElementById("nro_documento");
    const nroDocumentoProveedor = document.getElementById(
      "nro_documento_proveedor"
    );
    const nombreProveedor = document.getElementById("nombre_proveedor");
    const destino = document.getElementById("destino");

    if (tipoComprobante == "GR") {
      nroComprobante.value = "";
      nroComprobante.disabled = false;

      nroDocumentoProveedor.disabled = false;
      nombreProveedor.disabled = false;
      destino.disabled = false;
    } else if (tipoComprobante == "GI") {
      nroComprobante.value = await cargarCodigoPedido();
      nroComprobante.disabled = true;

      nroDocumentoProveedor.value = "";
      nroDocumentoProveedor.disabled = true;

      nombreProveedor.value = "";
      nombreProveedor.disabled = true;

      destino.disabled = true;
    } else {
      nroComprobante.value = "";
      nroComprobante.disabled = true;
      nroDocumentoProveedor.disabled = true;
      nombreProveedor.disabled = true;
      destino.disabled = true;
    }
  }

  function agregarInsumoATabla(insumo) {
    // Crear una nueva fila y celdas
    const row = tablaDetallesBody.insertRow();
    const celdaProducto = row.insertCell(0);
    const celdaCantidad = row.insertCell(1);
    const celdaUnidad = row.insertCell(2);
    const celdaPrecioUnitario = row.insertCell(3);
    const celdaSubtotal = row.insertCell(4);

    const celdaBorrar = row.insertCell(5);

    celdaCantidad.classList.add("text-center");
    celdaUnidad.classList.add("text-center");
    celdaPrecioUnitario.classList.add("text-end");
    celdaSubtotal.classList.add("text-end");
    celdaBorrar.classList.add("text-center");

    // Asignar valores a las celdas
    celdaProducto.textContent = insumo.nombre_insumo;
    celdaCantidad.textContent = insumo.cantidad;
    celdaUnidad.textContent = insumo.tipo_de_unidad;
    celdaPrecioUnitario.textContent = (+insumo.costo_unitario).toFixed(6);
    celdaSubtotal.textContent = (
      insumo.cantidad * insumo.costo_unitario
    ).toFixed(6);

    // Agregar el botón de borrar
    const botonBorrar = document.createElement("button");
    botonBorrar.classList.add("btn", "btn-danger", "btn-sm");
    botonBorrar.innerHTML = '<i class="fas fa-minus"></i>';
    botonBorrar.onclick = () => {
      borrarInsumo(insumo.idFila);
    };
    celdaBorrar.appendChild(botonBorrar);

    row.dataset.idProducto = insumo.id_insumo;
    row.dataset.idFila = insumo.idFila;

    if (insumo.id_receta) {
      row.dataset.idReceta = insumo.id_receta;
    }
  }

  function borrarInsumo(idFila) {
    const detalles = detallesDeTabla.filter(
      (detalle) => detalle.idFila != idFila
    );
    detallesDeTabla = detalles;

    const insumos = insumosAgregados.filter(
      (insumo) => insumo.idFila != idFila
    );
    insumosAgregados = insumos;

    actualizarTabla();
    calcularCostoTotal();
  }

  function limpiarFormularioDetalle() {
    document.getElementById("producto").value = "";
    document.getElementById("cantidad").value = "1";
    document.getElementById("tipo_unidad_detalle").value = "";
    document.getElementById("precio_unitario").value = "0";
    document.getElementById("subtotal").value = "0";

    document.getElementById("agregar-producto").disabled = true;
  }

  // función que comprueba que no sea solo varios guiones como por ejemplo "----"
  function limpiarGuiones(cadena) {
    if (/^[ -]*$/.test(cadena)) {
      return "";
    }
    return cadena;
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
