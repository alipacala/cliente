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
      <h2 class="text-center">Registrar comprobante de compra</h2>
    </div>
    <div class="card-body">
      <form id="form-registrar-comprobante">
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="tipo_comprobante">Tipo de comprobante</label>
            <select
              class="form-select"
              id="tipo_comprobante"
              name="tipo_comprobante"
              required
              onchange="alCambiarTipoComprobante(event)"
            >
              <option value="">Seleccione un tipo de comprobante</option>
              <option value="00">Orden de pedido</option>
              <option value="01" selected>Factura</option>
              <option value="03">Boleta</option>
              <option value="05">Recibo por honorarios</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="nro_comprobante">Nro de Comprobante</label>
            <input
              type="text"
              class="form-control"
              id="nro_comprobante"
              name="nro_comprobante"
              value="F001-09201838"
              required
            />
          </div>
          <div class="form-group col-md-3">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha"
            value="<?php echo date("Y-m-d") ?>" required />
          </div>
          <div class="form-group col-md-3">
            <label for="nro_orden_pedido">Orden de Pedido</label>
            <input
              type="text"
              class="form-control"
              id="nro_orden_pedido"
              name="nro_orden_pedido"
              value="OP001-09201838"
              required
            />
          </div>
        </div>
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="tipo_documento_cliente"
              >Tipo de documento de cliente</label
            >
            <select
              class="form-select"
              id="tipo_documento_cliente"
              name="tipo_documento_cliente"
              required
            >
              <option value="0">Sin Documento</option>
              <option value="1" selected>DNI</option>
              <option value="6">RUC</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="nro_documento_cliente">Nro Documento:</label>
            <div class="input-group">
              <input
                type="text"
                class="form-control nro_documento_cliente"
                value="76368626"
                id="nro_documento_cliente"
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
            <label for="nombre_proveedor">Nombre del proveedor</label>
            <input
              class="form-control"
              id="nombre_proveedor"
              name="nombre_proveedor"
              value="LIPA CALABILLA, ABRAHAM"
              required
            />
          </div>
          <div class="form-group col-md-3">
            <label for="autorizado_por">Autorizado por</label>
            <select
              class="form-select"
              id="autorizado_por"
              name="autorizado_por"
              required
            ></select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="direccion_cliente">Dirección</label>
            <input
              class="form-control"
              id="direccion_cliente"
              name="direccion_cliente"
              value="AV. LOS FRUTALES 123"
              required
            />
          </div>
          <div class="form-group col-md-3">
            <label for="ciudad">Ciudad</label>
            <input
              class="form-control"
              id="ciudad"
              value="TACNA"
              name="ciudad"
              required
            />
          </div>
          <div class="form-group col-md-3">
            <label for="tipo_gasto">Tipo de gasto</label>
            <select
              class="form-select"
              id="tipo_gasto"
              name="tipo_gasto"
              required
            ></select>
          </div>
          <div class="form-group col-md-3">
            <label for="almacenado_en">Almacenado en</label>
            <select
              class="form-select"
              id="almacenado_en"
              name="almacenado_en"
              required
            ></select>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <h4>Productos</h4>
          </div>
          <div class="card-body">
            <button
              type="button"
              class="btn btn-success mb-3"
              data-id="0"
              data-bs-toggle="modal"
              data-bs-target="#modal-detalle"
            >
              Agregar Producto
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
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>

            <div class="row w-50 ms-auto">
              <div class="col-md-6 form-check mb-3 d-flex align-items-center">
                <input
                  class="form-check-input me-2"
                  type="checkbox"
                  id="afecto_percepcion"
                  onchange="alCambiarAfectoPercepcion(event)"
                />
                <label class="form-check-label" for="afecto_percepcion">
                  Afecto a percepción
                </label>
              </div>
              <span class="col-form-label col-md-3 mb-3">Subtotal</span>
              <div class="col-md-3 mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="subtotal_comprobante"
                  name="subtotal_comprobante"
                  disabled
                />
              </div>
              <div class="col-form-label col-md-3 mb-3">
                <span class="fw-bold" id="percepcion">2</span>% Percepción
              </div>
              <div class="col-md-3 mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="percepcion_comprobante"
                  name="percepcion_comprobante"
                  disabled
                />
              </div>
              <div class="col-form-label col-md-3 mb-3">
                IGV <span class="fw-bold" id="igv">18</span>%
              </div>
              <div class="col-md-3 mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="igv_comprobante"
                  name="igv_comprobante"
                  disabled
                />
              </div>
              <span class="col-form-label col-md-3 mb-3">Forma de pago </span>
              <div class="col-md-3 mb-3">
                <select
                  class="form-select"
                  id="forma_pago"
                  name="forma_pago"
                  required
                >
                  <option value="CT">CONTADO</option>
                  <option value="CR">CRÉDITO</option>
                </select>
              </div>
              <span class="col-form-label col-md-3 mb-3">TOTAL</span>
              <div class="col-md-3 mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="total_comprobante"
                  name="total"
                  disabled
                />
              </div>
              <div class="col-md-6 mb-3"></div>
              <span class="col-form-label col-md-3 mb-3">GRAN TOTAL</span>
              <div class="col-md-3 mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="gran_total_comprobante"
                  name="gran_total"
                  disabled
                />
              </div>
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
          <div class="row mb-3">
            <div class="form-group col-md-8">
              <label for="producto">Producto</label>
              <select
                class="form-select"
                id="producto"
                name="producto"
                required
              ></select>
            </div>
            <div class="form-group col-md-4">
              <button
                type="button"
                class="btn btn-success"
                id="btn-registrar-nuevo-producto"
              >
                <i class="fas fa-plus"></i> Nuevo producto
              </button>
            </div>
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
            <label for="tipo_unidad_detalle">T. Unidad</label>
            <input
              type="text"
              class="form-control"
              id="tipo_unidad_detalle"
              name="tipo_unidad_detalle"
              required
            />
          </div>

          <div class="form-group mb-3">
            <label for="precio_unitario_sin_igv">P. Unitario sin IGV</label>
            <input
              type="text"
              class="form-control"
              id="precio_unitario_sin_igv"
              name="precio_unitario_sin_igv"
              onchange="alCambiarPrecioUnitario(event)"
              required
            />
          </div>

          <div class="form-group mb-3">
            <label for="precio_unitario_con_igv">P. Unitario con IGV</label>
            <input
              type="text"
              class="form-control"
              id="precio_unitario_con_igv"
              name="precio_unitario_con_igv"
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
              onchange="alCambiarSubtotal(event)"
              required
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

<div
  class="modal modal-xl fade"
  id="modal-producto"
  tabindex="-1"
  aria-labelledby="modal-producto-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-producto-label">
          Ficha de Producto o Insumo
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-producto"
        ></button>
      </div>
      <div class="modal-body">
        <form id="form-crear-producto">
          <div class="row mb-3">
            <div class="form-group col-md-4">
              <label for="nombre_producto">Nombre del Producto</label>
              <input
                type="text"
                class="form-control"
                id="nombre_producto"
                name="nombre_producto"
                required
              />
            </div>

            <div class="form-group col-md-4">
              <label for="codigo">Código</label>
              <input
                type="text"
                class="form-control"
                id="codigo"
                name="codigo"
                required
              />
            </div>
          </div>

          <div class="row mb-3">
            <div class="form-group col-md-4">
              <label for="clasificacion_ventas"
                >Clasificación en Catálogo de Ventas</label
              >
              <select
                class="form-select"
                id="clasificacion_ventas"
                name="clasificacion_ventas"
                required
              ></select>
            </div>
            <div class="form-group col-md-4">
              <label for="central_costos">Central de Costos</label>
              <select
                class="form-select"
                id="central_costos"
                name="central_costos"
                required
              ></select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="form-group col-md-4">
              <label for="tipo_producto">Tipo de Producto</label>
              <select
                class="form-select"
                id="tipo_producto"
                name="tipo_producto"
              ></select>
            </div>
            <div class="form-group col-md-4">
              <label for="fecha_vigencia">Fecha de Vigencia del Producto</label>
              <input type="date" class="form-control" id="fecha_vigencia"
              name="fecha_vigencia" value="<?php echo date("Y-m-d") ?>" required
              />
            </div>
          </div>
          <div class="row mb-3">
            <div class="form-group col-md-4">
              <label for="cantidad_unidades">Cantidad de Unidades</label>
              <input
                type="text"
                class="form-control"
                id="cantidad_unidades"
                name="cantidad_unidades"
              />
            </div>
            <div class="form-group col-md-4">
              <label for="tipo_unidad">Tipo de Unidad</label>
              <select
                class="form-select"
                id="tipo_unidad"
                name="tipo_unidad"
                required
              >
                <option value="UNIDAD" selected>UNIDAD</option>
                <option value="KILO">KILO</option>
                <option value="GRAMO">GRAMO</option>
                <option value="LITRO">LITRO</option>
                <option value="ONZA">ONZA</option>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="proveedor_asignado">Proveedor Asignado</label>
              <input
                type="text"
                class="form-control"
                id="proveedor_asignado"
                name="proveedor_asignado"
              />
            </div>
          </div>

          <div class="row mb-3">
            <div class="form-group col-md-4">
              <label for="stock_min_temporada_baja"
                >Stock Mínimo Temp. Baja</label
              >
              <input
                type="number"
                class="form-control"
                id="stock_min_temporada_baja"
                name="stock_min_temporada_baja"
                required
              />
            </div>
            <div class="form-group col-md-4">
              <label for="stock_min_temporada_alta"
                >Stock Mínimo Temp. ALTA</label
              >
              <input
                type="number"
                class="form-control"
                id="stock_min_temporada_alta"
                name="stock_min_temporada_alta"
                required
              />
            </div>
          </div>
          <div class="row mb-3">
            <div class="form-group col-md-4">
              <label for="stock_max_temporada_baja"
                >Stock Máximo Temp. Baja</label
              >
              <input
                type="number"
                class="form-control"
                id="stock_max_temporada_baja"
                name="stock_max_temporada_baja"
                required
              />
            </div>
            <div class="form-group col-md-4">
              <label for="stock_max_temporada_alta"
                >Stock Máximo Temp. ALTA</label
              >
              <input
                type="number"
                class="form-control"
                id="stock_max_temporada_alta"
                name="stock_max_temporada_alta"
                required
              />
            </div>
          </div>
          <input
            type="submit"
            class="btn btn-primary"
            id="crear-producto"
            value="Guardar Ficha"
          />
          <button
            type="button"
            class="btn btn-outline-secondary"
            id="cancelar-crear-producto"
            data-bs-dismiss="modal"
          >
            Cancelar
          </button>
        </form>
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
  const apiComprobantesVentasUrl =
    "<?php echo URL_API_NUEVA ?>/comprobantes-ventas";

  let tiposGasto;
  let usuarios;
  let unidadesNegocio;

  let insumosCargados = [];
  let insumosAgregados = [];
  let detallesDeTabla = [];
  let idsInsumosEliminados = [];

  let tablaDetallesBody = null;

  let modalCrearDetalle = null;
  let modalCrearProducto = null;

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarTiposGasto();
    await cargarUsuarios();
    await cargarUnidadesNegocio();

    alCambiarNroDoc();

    tablaDetallesBody = document
      .getElementById("tabla-detalles")
      .querySelector("tbody");

    await cargarProductos();

    modalCrearDetalle = new bootstrap.Modal(
      document.getElementById("modal-detalle")
    );
    modalCrearProducto = new bootstrap.Modal(
      document.getElementById("modal-producto")
    );

    prepararFormularioCrearProducto();
    prepararBotonRegistrarNuevoProducto();
    prepararFormularioReceta();
  }

  let iterador = 1;

  async function cargarTiposGasto() {
    try {
      const response = await fetch(apiTiposGastoUrl);
      const data = await response.json();

      tiposGasto = data;

      const tipoGastoSelect = document.getElementById("tipo_gasto");
      tipoGastoSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione un tipo de gasto";
      tipoGastoSelect.appendChild(defaultOption);

      data.forEach((tipoGasto) => {
        const option = document.createElement("option");
        option.value = tipoGasto.id_tipo_de_gasto;
        option.textContent = tipoGasto.nombre_gasto;
        tipoGastoSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los tipos de gasto", "consultar");
    }
  }

  async function cargarUsuarios() {
    const url = apiUsuariosUrl + "?activos";

    try {
      const response = await fetch(url);
      const data = await response.json();

      usuarios = data;

      const autorizadoPorSelect = document.getElementById("autorizado_por");
      autorizadoPorSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione un usuario";
      autorizadoPorSelect.appendChild(defaultOption);

      data.forEach((usuario) => {
        const option = document.createElement("option");
        option.value = usuario.id_usuario;
        option.textContent =
          !usuario.apellidos && !usuario.nombres
            ? "ADMIN"
            : !usuario.nombres
            ? usuario.apellidos
            : usuario.apellidos + ", " + usuario.nombres;
        autorizadoPorSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los usuarios", "consultar");
    }
  }

  async function cargarUnidadesNegocio() {
    try {
      const response = await fetch(apiUnidadesNegocioUrl);
      const data = await response.json();

      unidadesNegocio = data;

      const almacenadoEnSelect = document.getElementById("almacenado_en");
      almacenadoEnSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione una unidad de negocio";
      almacenadoEnSelect.appendChild(defaultOption);

      data.forEach((unidadNegocio) => {
        const option = document.createElement("option");
        option.value = unidadNegocio.id_unidad_de_negocio;
        option.textContent = unidadNegocio.nombre_unidad_de_negocio;
        almacenadoEnSelect.appendChild(option);
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

  // #region Funciones Formulario de creación de nuevo producto

  async function cargarCodigoProducto() {
    try {
      const url = apiConfigUrl + "/6/codigo"; // 6 es el id de los productos
      const response = await fetch(url);
      const data = await response.json();

      const codigo = document.getElementById("codigo");
      codigo.value = data.codigo;
    } catch (error) {
      mostrarAlert(
        "error",
        "Error al cargar el código del producto",
        "consultar"
      );
    }
  }

  async function cargarClasificacionVentas() {
    try {
      const response = await fetch(apiGruposDeLaCartaUrl);
      const data = await response.json();

      const clasificacionVentasSelect = document.getElementById(
        "clasificacion_ventas"
      );
      clasificacionVentasSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione una clasificación";
      clasificacionVentasSelect.appendChild(defaultOption);

      let grupos = data;
      grupos = ordenarGrupos(grupos);

      grupos.forEach((clasificacionVentas) => {
        const option = crearOpcionClasificacionVentas(clasificacionVentas);
        clasificacionVentasSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las clasificaciones de ventas",
        "consultar"
      );
    }
  }

  function ordenarGrupos(grupos) {
    // ordenar los grupos por nro_orden
    grupos.sort((a, b) => {
      if (+a.nro_orden > +b.nro_orden) {
        return 1;
      } else if (+a.nro_orden < +b.nro_orden) {
        return -1;
      } else {
        return 0;
      }
    });

    // mapear a un array de grupos con un campo array de subgrupos
    grupos = grupos
      .filter((grupo) => grupo.codigo_grupo == grupo.codigo_subgrupo)
      .map((grupo) => {
        grupo.subgrupos = grupos.filter(
          (subgrupo) =>
            subgrupo.codigo_grupo == grupo.codigo_grupo &&
            subgrupo.codigo_subgrupo != subgrupo.codigo_grupo
        );
        return grupo;
      });

    // flat array de grupos y subgrupos
    return grupos.flatMap((grupo) => {
      return [grupo, ...grupo.subgrupos];
    });
  }

  function crearOpcionClasificacionVentas(clasificacionVentas) {
    const option = document.createElement("option");
    option.value = clasificacionVentas.id_grupo;

    if (
      clasificacionVentas.codigo_grupo == clasificacionVentas.codigo_subgrupo
    ) {
      option.textContent = clasificacionVentas.nombre_grupo;
      option.classList.add("fw-bold");
    } else {
      option.style.color = "#6c757d";
      option.innerHTML =
        "&nbsp;&nbsp;&nbsp;&nbsp;" + clasificacionVentas.nombre_grupo;
    }

    return option;
  }

  async function cargarCentralCostos() {
    try {
      const response = await fetch(apiCentralDeCostosUrl);
      const data = await response.json();

      const centralCostosSelect = document.getElementById("central_costos");

      centralCostosSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione una central de costos";
      centralCostosSelect.appendChild(defaultOption);

      data.forEach((centralCostos) => {
        const option = document.createElement("option");
        option.value = centralCostos.id_central_de_costos;
        option.textContent = centralCostos.nombre_del_costo;
        centralCostosSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las centrales de costos",
        "consultar"
      );
    }
  }

  async function cargarTiposDeProducto() {
    try {
      const response = await fetch(apiTipoDeProductoUrl);
      const data = await response.json();
      const tipoDeProductoSelect = document.getElementById("tipo_producto");

      tipoDeProductoSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione un tipo de producto";
      tipoDeProductoSelect.appendChild(defaultOption);

      data.forEach((tipoDeProducto) => {
        const option = document.createElement("option");
        option.value = tipoDeProducto.id_tipo_producto;
        option.textContent = tipoDeProducto.nombre_tipo_de_producto;
        tipoDeProductoSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las centrales de costos",
        "consultar"
      );
    }
  }

  async function crearProducto() {
    const producto = {
      nombre_producto: document.getElementById("nombre_producto").value,
      codigo: document.getElementById("codigo").value,
      tipo_de_unidad: document.getElementById("tipo_unidad").value,
      id_grupo: document.getElementById("clasificacion_ventas").value,
      id_central_de_costos: document.getElementById("central_costos").value,
      id_tipo_de_producto: document.getElementById("tipo_producto").value,
      fecha_de_vigencia: document.getElementById("fecha_vigencia").value,
      stock_min_temporada_baja: document.getElementById(
        "stock_min_temporada_baja"
      ).value,
      stock_max_temporada_baja: document.getElementById(
        "stock_max_temporada_baja"
      ).value,
      stock_min_temporada_alta: document.getElementById(
        "stock_min_temporada_alta"
      ).value,
      stock_max_temporada_alta: document.getElementById(
        "stock_max_temporada_alta"
      ).value,
      cantidad_de_fracciones:
        document.getElementById("cantidad_unidades").value,
    };

    const url = apiProductosUrl + "/insumo-terminado";

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(producto),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();
      console.log(data);

      insumosCargados.push(data.resultado);

      // agregar la opción al select de productos como primera opción (después de la opción por defecto)
      const productoSelect = document.getElementById("producto");
      const option = document.createElement("option");
      option.value = data.resultado.id_producto;
      option.textContent = data.resultado.nombre_producto;
      productoSelect.insertBefore(option, productoSelect.options[1]);

      // seleccionar la opción recién creada
      productoSelect.value = data.resultado.id_producto;
      // lanzar el evento change para que se carguen los datos del producto
      productoSelect.dispatchEvent(new Event("change"));

      modalCrearProducto.hide();
      alert("Producto creado correctamente");
    } catch (error) {
      console.error(error);
      alert("Error al crear el producto");
    }
  }

  function prepararFormularioCrearProducto() {
    const form = document.getElementById("form-crear-producto");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      crearProducto();
    });
  }

  function limpiarFormularioProducto() {
    document.getElementById("nombre_producto").value = "";
    document.getElementById("codigo").value = "";
    document.getElementById("clasificacion_ventas").value = "";
    document.getElementById("central_costos").value = "";
    document.getElementById("tipo_producto").value = "";
    document.getElementById("fecha_vigencia").value = new Date()
      .toISOString()
      .split("T")[0];
    document.getElementById("cantidad_unidades").value = "";
    document.getElementById("tipo_unidad").value = "";
    document.getElementById("proveedor_asignado").value = "";
    document.getElementById("stock_min_temporada_baja").value = "";
    document.getElementById("stock_max_temporada_baja").value = "";
    document.getElementById("stock_min_temporada_alta").value = "";
    document.getElementById("stock_max_temporada_alta").value = "";
  }
  // #endregion

  // #region Funciones de actualización de cantidades y montos

  function alCambiarPrecioUnitario(event) {
    const el = event.target;
    const tipoComprobante = document.getElementById("tipo_comprobante");
    const precioUnitarioConIgv = document.getElementById(
      "precio_unitario_con_igv"
    );
    const precioUnitarioSinIgv = document.getElementById(
      "precio_unitario_sin_igv"
    );

    if (tipoComprobante.value == "01") {
      // factura
      if (el.id == "precio_unitario_sin_igv") {
        precioUnitarioConIgv.value = (el.value * 1.18).toFixed(6);
      } else {
        precioUnitarioSinIgv.value = (el.value / 1.18).toFixed(6);
      }
    } else {
      precioUnitarioConIgv.value = el.value;
      precioUnitarioSinIgv.value = el.value;
    }

    // calcular subtotal
    const cantidad = document.getElementById("cantidad").value;
    const subtotal = document.getElementById("subtotal");

    const precioUnitario = precioUnitarioSinIgv.value;

    subtotal.value = (precioUnitario * cantidad).toFixed(6);
  }

  function alCambiarSubtotal(event) {
    const el = event.target;
    const precioUnitarioSinIgv = document.getElementById(
      "precio_unitario_sin_igv"
    );
    const precioUnitarioConIgv = document.getElementById(
      "precio_unitario_con_igv"
    );
    const tipoComprobante = document.getElementById("tipo_comprobante");

    const cantidad = document.getElementById("cantidad");

    const subtotal = el.value;
    const precioUnitario = subtotal / cantidad.value;

    precioUnitarioSinIgv.value = precioUnitario.toFixed(6);

    if (tipoComprobante.value == "01") {
      // factura
      precioUnitarioConIgv.value = (precioUnitario * 1.18).toFixed(6);
    } else {
      precioUnitarioConIgv.value = precioUnitario.toFixed(6);
    }
  }

  function alCambiarCantidad(event) {
    const el = event.target;
    const cantidad = el.value;
    const precioUnitarioSinIgv = document.getElementById(
      "precio_unitario_sin_igv"
    );
    const precioUnitarioConIgv = document.getElementById(
      "precio_unitario_con_igv"
    );
    const tipoComprobante = document.getElementById("tipo_comprobante");
    const subtotalInput = document.getElementById("subtotal");

    const subtotal = subtotalInput.value;
    const precioUnitario = subtotal / cantidad;

    precioUnitarioSinIgv.value = precioUnitario.toFixed(6);
    if (tipoComprobante.value == "01") {
      // factura
      precioUnitarioConIgv.value = (precioUnitario * 1.18).toFixed(6);
    } else {
      precioUnitarioConIgv.value = precioUnitario.toFixed(6);
    }
    subtotalInput.value = (precioUnitario * cantidad).toFixed(6);
  }

  // #endregion

  async function alCambiarNroDoc() {
    const nroDocumento = document.getElementById("nro_documento_cliente");

    nroDocumento.addEventListener("change", async (event) => {
      const tipoDocumento = document.getElementById(
        "tipo_documento_cliente"
      ).value;
      const nroDocumentoValor = event.target.value;

      if (!nroDocumentoValor || tipoDocumento == 0) {
        return;
      }

      const tiposDocumento = {
        1: "DNI",
        6: "RUC",
      };

      const url = `${apiSunatUrl}?tipo=${tiposDocumento[tipoDocumento]}&nro=${nroDocumentoValor}`;

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
        const direccion = document.getElementById("direccion_cliente");
        const lugar = document.getElementById("ciudad");

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

  function prepararBotonRegistrarNuevoProducto() {
    const btnRegistrarNuevoProducto = document.getElementById(
      "btn-registrar-nuevo-producto"
    );
    btnRegistrarNuevoProducto.addEventListener("click", async () => {
      await cargarClasificacionVentas();
      await cargarCentralCostos();
      await cargarTiposDeProducto();

      limpiarFormularioProducto();

      await cargarCodigoProducto();

      modalCrearProducto.show();
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
      option.textContent = "Seleccione un insumo";
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

      const agregarInsumoButton = document.getElementById("agregar-producto");
      agregarInsumoButton.addEventListener("click", alAgregarInsumo);
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los productos", "consultar");
    }
  }

  function alCambiarInsumo() {
    const productoSelect = document.getElementById("producto");
    const agregarInsumoButton = document.getElementById("agregar-producto");
    const tipoComprobante = document.getElementById("tipo_comprobante");

    if (!productoSelect.value) {
      limpiarFormularioDetalle();
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
    const costoUnitarioSinIGVInput = document.getElementById(
      "precio_unitario_sin_igv"
    );
    const costoUnitarioConIGVInput = document.getElementById(
      "precio_unitario_con_igv"
    );
    const subtotalInput = document.getElementById("subtotal");

    tipoUnidadDetalleInput.value = productoSeleccionado.tipo_de_unidad;
    costoUnitarioSinIGVInput.value = productoSeleccionado.costo_unitario;

    if (tipoComprobante.value == "01") {
      costoUnitarioConIGVInput.value = (
        productoSeleccionado.costo_unitario * 1.18
      ).toFixed(6);
    } else {
      costoUnitarioConIGVInput.value = productoSeleccionado.costo_unitario;
    }

    subtotalInput.value =
      +productoSeleccionado.costo_unitario * +cantidadInput.value;

    agregarInsumoButton.disabled = false;
  }

  async function cargarClasificacionVentas() {
    try {
      const response = await fetch(apiGruposDeLaCartaUrl);
      const data = await response.json();

      const clasificacionVentasSelect = document.getElementById(
        "clasificacion_ventas"
      );

      clasificacionVentasSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione una clasificación";
      clasificacionVentasSelect.appendChild(defaultOption);

      let grupos = data;
      grupos = ordenarGrupos(grupos);

      grupos.forEach((clasificacionVentas) => {
        const option = crearOpcionClasificacionVentas(clasificacionVentas);
        clasificacionVentasSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las clasificaciones de ventas",
        "consultar"
      );
    }
  }

  function ordenarGrupos(grupos) {
    // ordenar los grupos por nro_orden
    grupos.sort((a, b) => {
      if (+a.nro_orden > +b.nro_orden) {
        return 1;
      } else if (+a.nro_orden < +b.nro_orden) {
        return -1;
      } else {
        return 0;
      }
    });

    // mapear a un array de grupos con un campo array de subgrupos
    grupos = grupos
      .filter((grupo) => grupo.codigo_grupo == grupo.codigo_subgrupo)
      .map((grupo) => {
        grupo.subgrupos = grupos.filter(
          (subgrupo) =>
            subgrupo.codigo_grupo == grupo.codigo_grupo &&
            subgrupo.codigo_subgrupo != subgrupo.codigo_grupo
        );
        return grupo;
      });

    // flat array de grupos y subgrupos
    return grupos.flatMap((grupo) => {
      return [grupo, ...grupo.subgrupos];
    });
  }

  function crearOpcionClasificacionVentas(clasificacionVentas) {
    const option = document.createElement("option");
    option.value = clasificacionVentas.id_grupo;

    if (
      clasificacionVentas.codigo_grupo == clasificacionVentas.codigo_subgrupo
    ) {
      option.textContent = clasificacionVentas.nombre_grupo;
      option.classList.add("fw-bold");
    } else {
      option.style.color = "#6c757d";
      option.innerHTML =
        "&nbsp;&nbsp;&nbsp;&nbsp;" + clasificacionVentas.nombre_grupo;
    }

    return option;
  }

  async function cargarCentralesCostos() {
    try {
      const response = await fetch(apiCentralesDeCostosUrl);
      const data = await response.json();

      const centralCostosSelect = document.getElementById("central_costos");
      centralCostosSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione una central de costos";
      centralCostosSelect.appendChild(defaultOption);

      data.forEach((centralCostos) => {
        const option = document.createElement("option");
        option.value = centralCostos.id_central_de_costos;
        option.textContent = centralCostos.nombre_del_costo;
        centralCostosSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las centrales de costos",
        "consultar"
      );
    }
  }

  function actualizarTabla() {
    tablaDetallesBody.innerHTML = "";

    detallesDeTabla.forEach((insumo) => {
      agregarInsumoATabla(insumo);
    });
  }

  async function registrarComprobanteCompra() {
    const comprobante = {
      nombre_cliente: document.getElementById("nombre_proveedor").value,
      lugar_cliente: document.getElementById("ciudad").value,
      direccion_cliente: document.getElementById("direccion_cliente").value,

      tipo_comprobante: document.getElementById("tipo_comprobante").value,
      nro_comprobante: document.getElementById("nro_comprobante").value,
      fecha_documento: document.getElementById("fecha").value,
      tipo_documento_cliente: document.getElementById("tipo_documento_cliente")
        .value,
      nro_documento_cliente: document.getElementById("nro_documento_cliente")
        .value,
      nro_orden_pedido: document.getElementById("nro_orden_pedido").value,

      id_usuario_responsable: document.getElementById("autorizado_por").value,
      id_tipo_de_gasto: document.getElementById("tipo_gasto").value,
      id_unidad_de_negocio: document.getElementById("almacenado_en").value,
      afecto_percepcion: document.getElementById("afecto_percepcion").checked,
      forma_de_pago: document.getElementById("forma_pago").value,
    };

    comprobante.detalles = detallesDeTabla.map((detalle) => {
      return {
        id_producto: detalle.id_insumo,
        tipo_de_unidad: detalle.tipo_de_unidad,
        cantidad: detalle.cantidad,
        precio_unitario: detalle.costo_unitario,
      };
    });

    const url = apiComprobantesVentasUrl + "/compra";

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(comprobante),
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

  function prepararFormularioReceta() {
    const form = document.getElementById("form-registrar-comprobante");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      registrarComprobanteCompra();
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
      costo_unitario: document.getElementById("precio_unitario_sin_igv").value,
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
    const subtotal = detallesDeTabla.reduce(
      (acumulador, insumo) => +(acumulador + +insumo.costo).toFixed(2),
      0
    );
    const tipoComprobante = document.getElementById("tipo_comprobante");
    const afectoPercepcion = document.getElementById("afecto_percepcion");

    const igv =
      tipoComprobante.value == "01" ? (subtotal * 0.18).toFixed(2) : 0;
    const percepcion = afectoPercepcion.checked
      ? (subtotal * 0.02).toFixed(2)
      : 0;
    const total = +subtotal + +igv;
    const granTotal = +total + +percepcion;

    document.getElementById("subtotal_comprobante").value =
      formatearCantidad(subtotal);
    document.getElementById("igv_comprobante").value = formatearCantidad(igv);
    document.getElementById("percepcion_comprobante").value =
      formatearCantidad(percepcion);
    document.getElementById("total_comprobante").value =
      formatearCantidad(total);
    document.getElementById("gran_total_comprobante").value =
      formatearCantidad(granTotal);
  }

  function alCambiarAfectoPercepcion(event) {
    calcularCostoTotal();
  }

  async function alCambiarTipoComprobante(event) {
    const tipoComprobante = event.target.value;
    const nroComprobante = document.getElementById("nro_comprobante");

    if (tipoComprobante == "00") {
      // cargar el codigo de pedido
      nroComprobante.value = await cargarCodigoPedido();
      nroComprobante.disabled = true;
    } else {
      nroComprobante.value = "";
      nroComprobante.disabled = false;
    }

    calcularCostoTotal();
  }

  async function cargarCodigoPedido() {
    const url = apiConfigUrl + "/20/codigo"; // 7 es el id de los pedidos

    try {
      const response = await fetch(url);
      const data = await response.json();

      return data.codigo;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar el código del pedido", "crear");
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

    celdaCantidad.classList.add("text-center");
    celdaUnidad.classList.add("text-center");
    celdaPrecioUnitario.classList.add("text-end");
    celdaSubtotal.classList.add("text-end");

    // Asignar valores a las celdas
    celdaProducto.textContent = insumo.nombre_insumo;
    celdaCantidad.textContent = insumo.cantidad;
    celdaUnidad.textContent = insumo.tipo_de_unidad;
    celdaPrecioUnitario.textContent = formatearCantidad(insumo.costo_unitario);
    celdaSubtotal.textContent = formatearCantidad(
      insumo.cantidad * insumo.costo_unitario
    );

    row.dataset.idProducto = insumo.id_insumo;
    row.dataset.idFila = insumo.idFila;

    if (insumo.id_receta) {
      row.dataset.idReceta = insumo.id_receta;
    }
  }

  function limpiarFormularioDetalle() {
    document.getElementById("producto").value = "";
    document.getElementById("cantidad").value = "1";
    document.getElementById("tipo_unidad").value = "";
    document.getElementById("precio_unitario_sin_igv").value = "0";
    document.getElementById("precio_unitario_con_igv").value = "0";
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
