<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido > TIEMPO_INACTIVIDAD)) {
  session_unset();
  session_destroy();
}
$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado);

$editar = isset($_GET["id"]) ? $_GET["id"] : false;
?>

<div class="container my-5 main-cont">
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">
        <?php echo $editar ? "Editar" : "Crear" ?> Ficha de Receta
      </h2>
    </div>
    <div class="card-body">
      <form id="form-crear-producto">
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="nombre_producto">Nombre de la Receta</label>
            <input
              type="text"
              class="form-control"
              id="nombre_producto"
              name="nombre_producto"
              required
            />
          </div>
          <div class="form-group col-md-6">
            <label for="descripcion_del_producto">Descripción</label>
            <input
              type="text"
              class="form-control"
              id="descripcion_del_producto"
              name="descripcion_del_producto"
              required
            />
          </div>
          <div class="form-group col-md-3">
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
          <div class="form-group col-md-4">
            <label for="fecha_vigencia">Fecha de Vigencia</label>
            <input
              type="date"
              class="form-control"
              id="fecha_vigencia"
              name="fecha_vigencia"
              value=<?php echo date("Y-m-d") ?>
              required
            />
          </div>
        </div>
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="tiempo_preparacion">Tiempo de preparación</label>
            <input
              type="number"
              min="0"
              max="99999"
              class="form-control"
              id="tiempo_preparacion"
              name="tiempo_preparacion"
              required
            />
          </div>
        </div>
        <div class="form-group">
          <label for="preparacion">Preparación</label>
          <textarea
            name="preparacion"
            class="form-control mb-3"
            id="preparacion"
            cols="30"
            rows="5"
            required
          ></textarea
          >
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <h4>Ingredientes</h4>
          </div>
          <div class="card-body">
            <button
              type="button"
              class="btn btn-success mb-3"
              data-id="0"
              onclick="mostrarModalInsumo(this)"
            >
              Agregar Insumo
            </button>

            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="tabla-insumos">
                <thead class="thead-dark">
                  <tr class="text-center">
                    <th>Insumo</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>P.COSTO</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="3" class="text-right fw-bold fs-5">COSTO INSUMOS:</td>
                    <td id="total" class="text-end fw-bold fs-5">0.00</td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <input type="submit" class="btn
        <?php echo $editar ? "btn-warning" : "btn-primary" ?>"
        id="crear-producto" value="Guardar Ficha" />
        <a class="btn btn-warning" href="./../listado-catalogo">Salir</a>
      </form>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-insumo"
  tabindex="-1"
  aria-labelledby="modal-insumo-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-insumo-label">
          Agregar Insumo de Receta
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-insumo"
        ></button>
      </div>
      <div class="modal-body">
        <form id="form-crear-insumo">
          <div class="form-group mb-3">
            <label for="insumo">Insumo</label>
            <select
              class="form-select"
              id="insumo"
              name="insumo"
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
              required
            />
          </div>

          <div class="form-group mb-3">
            <label for="unidad">Unidad</label>
            <input
              type="text"
              class="form-control"
              id="unidad"
              name="unidad"
              required
              disabled
            />
          </div>

          <div class="form-group mb-3">
            <label for="costo-unitario">Costo Unitario</label>
            <input
              type="text"
              class="form-control"
              id="costo-unitario"
              name="costo-unitario"
              required
              disabled
            />
          </div>

          <div class="form-group mb-3">
            <label for="costo">P.COSTO</label>
            <input
              type="text"
              class="form-control"
              id="costo"
              name="costo"
              required
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
            id="agregar-insumo"
            disabled
          >
            Agregar Ingrediente
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiGruposDeLaCartaUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";
  const apiCentralesDeCostosUrl =
    "<?php echo URL_API_NUEVA ?>/centrales-costos";
  const apiProductosRecetaUrl = "<?php echo URL_API_NUEVA ?>/productos-receta";
  const apiConfigUrl = "<?php echo URL_API_NUEVA ?>/config";

  let insumosCargados = [];
  let insumosAgregados = [];
  let insumosTabla = [];
  let idsInsumosEliminados = [];

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  async function wrapper() {
    await cargarProductos();
    await cargarClasificacionVentas();
    await cargarCentralesCostos();

    if (id) {
      await cargarProductoReceta();
    } else {
      await cargarCodigoProductoReceta();
    }

    prepararFormularioReceta();
  }

  let iterador = 1;

  async function cargarCodigoProductoReceta() {
    try {
      const url = apiConfigUrl + "/7/codigo"; // 7 es el id de las recetas
      const response = await fetch(url);
      const data = await response.json();

      const codigo = document.getElementById("codigo");
      codigo.value = data.codigo;
    } catch (error) {
      console.error("Error al cargar el código de la receta: ", error);
    }
  }

  // formatea las cantidades en soles, ejemplo: 1000 -> 1000.00
  function formatearCantidad(numero) {
    if (!numero) return "";
    const numeroFormateado = parseFloat(numero).toFixed(2);
    const partes = numeroFormateado.toString().split(".");
    partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return partes.join(".");
  }

  async function cargarProductoReceta() {
    try {
      const response = await fetch(`${apiProductosUrl}/${id}/con-insumos`);
      const data = await response.json();

      const insumos = data.insumos;

      const nombreProducto = document.getElementById("nombre_producto");
      const descripcionProducto = document.getElementById(
        "descripcion_del_producto"
      );
      const codigoProducto = document.getElementById("codigo");
      const clasificacionVentas = document.getElementById(
        "clasificacion_ventas"
      );
      const centralCostos = document.getElementById("central_costos");
      const fechaVigencia = document.getElementById("fecha_vigencia");
      const tiempoPreparacion = document.getElementById("tiempo_preparacion");
      const preparacion = document.getElementById("preparacion");

      nombreProducto.value = data.nombre_producto;
      descripcionProducto.value = data.descripcion_del_producto;
      codigoProducto.value = data.codigo;
      clasificacionVentas.value = data.id_grupo;
      centralCostos.value = data.id_central_de_costos;
      fechaVigencia.value = data.fecha_de_vigencia;
      tiempoPreparacion.value = data.tiempo_estimado;
      preparacion.value = data.preparacion;

      const tablaInsumos = document.getElementById("tabla-insumos");
      const tbody = tablaInsumos.querySelector("tbody");

      if (insumos) {
        insumos.forEach((insumo) => {
          const productoInsumo = insumosCargados.find(
            (producto) => producto.id_producto == insumo.id_producto_insumo
          );

          const insumoAgregado = {
            id_receta: insumo.id_receta,
            id_producto: insumo.id_producto,
            id_insumo: insumo.id_producto_insumo,
            nombre_insumo: productoInsumo.nombre_producto,
            cantidad: insumo.cantidad,
            tipo_de_unidad: insumo.tipo_de_unidad,
            costo: productoInsumo.costo_unitario * insumo.cantidad,
            idFila: iterador++,
          };

          insumosTabla.push(insumoAgregado);
        });
      }

      actualizarTabla();
      calcularCostoTotal();
    } catch (error) {
      console.error("Error al cargar el producto receta: ", error);
    }
  }

  function mostrarModalInsumo(e) {
    const modalInsumo = new bootstrap.Modal(
      document.getElementById("modal-insumo")
    );
    const modalInsumoLabel = document.querySelector("#modal-insumo-label");
    const agregarInsumo = document.querySelector("#agregar-insumo");

    limpiarFormulario();

    modalInsumo.show();
  }

  async function cargarProductos() {
    try {
      const response = await fetch(apiProductosUrl);
      let data = await response.json();

      data = data.filter((producto) => producto.id_tipo_de_producto == 10 && producto.tipo != 'RST');

      const insumoSelect = document.getElementById("insumo");
      insumoSelect.innerHTML = "";

      const option = document.createElement("option");
      option.value = "";
      option.textContent = "Seleccione un insumo";
      insumoSelect.appendChild(option);

      data.forEach((insumo) => {
        const option = document.createElement("option");

        insumosCargados = [...insumosCargados, insumo];

        option.value = insumo.id_producto;
        option.textContent = insumo.nombre_producto;
        insumoSelect.appendChild(option);
      });

      limpiarFormulario();

      const cantidadInput = document.getElementById("cantidad");
      cantidadInput.addEventListener("change", alCambiarCantidad);

      insumoSelect.addEventListener("change", alCambiarInsumo);

      const agregarInsumoButton = document.getElementById("agregar-insumo");
      agregarInsumoButton.addEventListener("click", alAgregarInsumo);
    } catch (error) {
      console.error("Error al cargar los productos: ", error);
    }
  }

  function alCambiarInsumo() {
    const insumoSelect = document.getElementById("insumo");
    const agregarInsumoButton = document.getElementById("agregar-insumo");

    if (!insumoSelect.value) {
      limpiarFormulario();
      agregarInsumoButton.disabled = true;
      return;
    }

    const insumoSeleccionado = insumosCargados.find(
      (insumo) => insumo.id_producto == insumoSelect.value
    );

    const unidadInput = document.getElementById("unidad");
    const costoInput = document.getElementById("costo");
    const costoUnitarioInput = document.getElementById("costo-unitario");
    const cantidadInput = document.getElementById("cantidad");

    unidadInput.value = insumoSeleccionado.tipo_de_unidad;
    costoUnitarioInput.value = insumoSeleccionado.costo_unitario;
    costoInput.value =
      +insumoSeleccionado.costo_unitario * +cantidadInput.value;

    agregarInsumoButton.disabled = false;
  }

  function alCambiarCantidad() {
    const cantidadInput = document.getElementById("cantidad");
    const costoUnitarioInput = document.getElementById("costo-unitario");
    const costoInput = document.getElementById("costo");

    costoInput.value = +(
      +cantidadInput.value * +costoUnitarioInput.value
    ).toFixed(2);
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
      console.error("Error al cargar los grupos de la carta: ", error);
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
      console.error("Error al cargar las centrales de costos: ", error);
    }
  }

  function actualizarTabla() {
    const tablaInsumos = document.getElementById("tabla-insumos");
    const tbody = tablaInsumos.querySelector("tbody");

    // eliminar todas las filas de la tabla excepto la última
    while (tbody.rows.length > 1) {
      tbody.deleteRow(0);
    }

    insumosTabla.forEach((insumo) => {
      agregarInsumoATabla(insumo, tablaInsumos);
    });
  }

  async function crearProducto(editar) {
    const producto = {
      nombre_producto: document.getElementById("nombre_producto").value,
      descripcion_del_producto: document.getElementById(
        "descripcion_del_producto"
      ).value,
      codigo: document.getElementById("codigo").value,
      id_grupo: document.getElementById("clasificacion_ventas").value,
      id_central_de_costos: document.getElementById("central_costos").value,
      fecha_de_vigencia: document.getElementById("fecha_vigencia").value,
      tiempo_estimado: document.getElementById("tiempo_preparacion").value,
      preparacion: document.getElementById("preparacion").value,
    };

    if (id) {
      producto.insumos_agregados = insumosAgregados.map((insumo) => {
        return {
          id_producto_insumo: insumo.id_insumo,
          cantidad: insumo.cantidad,
          tipo_de_unidad: insumo.tipo_de_unidad,
        };
      });
      producto.ids_insumos_eliminados = idsInsumosEliminados;
    } else {
      producto.insumos = insumosTabla.map((insumo) => {
        return {
          id_producto_insumo: insumo.id_insumo,
          cantidad: insumo.cantidad,
          tipo_de_unidad: insumo.tipo_de_unidad,
        };
      });
    }

    const url = id
      ? apiProductosUrl + "/" + id + "/con-insumos"
      : apiProductosUrl + "/receta";

    const options = {
      method: id ? "PATCH" : "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(producto),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();
      console.log(data);
      
      if (editar) {
        window.location.href = "./../listado-catalogo";
      } else {
        window.location.href = "./../listado-catalogo";
      }
    } catch (error) {
      console.error("Error al crear el producto receta e insumos: ", error);
    }
  }

  function prepararFormularioReceta() {
    const form = document.getElementById("form-crear-producto");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      crearProducto(id);
    });

    const tiempoPreparacion = document.getElementById("tiempo_preparacion");
    tiempoPreparacion.addEventListener("keypress", (event) => {
      if (isNaN(event.key) || tiempoPreparacion.value.length >= 5) {
        event.preventDefault();
      }
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
    const tablaInsumos = document.getElementById("tabla-insumos");
    const insumo = {
      id_insumo: document.getElementById("insumo").value,
      nombre_insumo:
        document.getElementById("insumo").options[
          document.getElementById("insumo").selectedIndex
        ].text,
      cantidad: document.getElementById("cantidad").value,
      tipo_de_unidad: document.getElementById("unidad").value,
      costo_unitario: document.getElementById("costo-unitario").value,
      costo: document.getElementById("costo").value,
      idFila: iterador++,
    };

    insumosTabla.push(insumo);
    insumosAgregados.push(insumo);

    actualizarTabla();
    ocultarModalInsumo();
    calcularCostoTotal();
    limpiarFormulario();
  }

  function ocultarModalInsumo() {
    const modalInsumo = document.getElementById("modal-insumo");
    const modal = bootstrap.Modal.getInstance(modalInsumo);
    modal.hide();
  }

  function calcularCostoTotal() {
    const costoTotal = insumosTabla.reduce(
      (acumulador, insumo) => +(acumulador + +insumo.costo).toFixed(2),
      0
    );

    document.getElementById("total").textContent =
      formatearCantidad(costoTotal);
  }

  function agregarInsumoATabla(insumo, tablaInsumos) {
    const tbody = tablaInsumos.querySelector("tbody");

    // Crear una nueva fila y celdas
    const row = tbody.insertRow(tablaInsumos.rows.length - 2);
    const celdaInsumo = row.insertCell(0);
    const celdaCantidad = row.insertCell(1);
    const celdaUnidad = row.insertCell(2);
    const celdaCosto = row.insertCell(3);
    const celdaEliminar = row.insertCell(4);

    celdaCantidad.classList.add("text-center");
    celdaUnidad.classList.add("text-center");
    celdaCosto.classList.add("text-end");
    celdaEliminar.classList.add("text-center");
    
    const productoInsumo = insumosCargados.find(
      (producto) => producto.id_producto == insumo.id_insumo
    );

    // Asignar valores a las celdas
    celdaInsumo.textContent = productoInsumo.nombre_producto;
    celdaCantidad.textContent = insumo.cantidad;
    celdaUnidad.textContent = insumo.tipo_de_unidad;
    celdaCosto.textContent = formatearCantidad(
      insumo.cantidad * productoInsumo.costo_unitario
    );
    celdaEliminar.innerHTML = `<button type="button" class="btn btn-danger" onclick="eliminarInsumo(this)"><i class="fas fa-trash-alt"></i></button>`;

    row.dataset.idProducto = insumo.id_insumo;
    row.dataset.idFila = insumo.idFila;

    if (insumo.id_receta) {
      row.dataset.idReceta = insumo.id_receta;
    }
  }

  function eliminarInsumo(e) {
    const tablaInsumos = document.getElementById("tabla-insumos");
    const tbody = tablaInsumos.querySelector("tbody");
    const fila = e.parentNode.parentNode;

    const idInsumo = fila.dataset.idProducto;
    const idReceta = fila.dataset.idReceta;
    const idFila = fila.dataset.idFila;

    insumosTabla = insumosTabla.filter((insumo) => insumo.idFila != idFila);

    if (idReceta) {
      idsInsumosEliminados.push(idReceta);
    } else {
      insumosAgregados = insumosAgregados.filter(
        (insumo) => insumo.id_insumo != idInsumo
      );
    }

    actualizarTabla();
    calcularCostoTotal();
  }

  function limpiarFormulario() {
    document.getElementById("insumo").value = "";
    document.getElementById("unidad").value = "";
    document.getElementById("costo-unitario").value = "";
    document.getElementById("costo").value = "";
    document.getElementById("cantidad").value = "1";
    
    document.getElementById("agregar-insumo").disabled = true;
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
