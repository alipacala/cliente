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
  <div id="alert-place"></div>
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">
        <?php echo $editar ? "Editar" : "Crear" ?>
        Ficha de Paquete
      </h2>
    </div>
    <div class="card-body">
      <form id="form-crear-producto">
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="nombre_paquete">Nombre del Paquete</label>
            <input
              type="text"
              class="form-control"
              id="nombre_paquete"
              name="nombre_paquete"
              required
            />
          </div>
          <div class="form-group col-md-6">
            <label for="descripcion_paquete">Descripción</label>
            <input
              type="text"
              class="form-control"
              id="descripcion_paquete"
              name="descripcion_paquete"
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
              class="form-control"
              id="clasificacion_ventas"
              name="clasificacion_ventas"
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

        <div class="card mb-3">
          <div class="card-header">
            <h4>Productos que componen el Paquete:</h4>
          </div>
          <div class="card-body">
            <button
              type="button"
              class="btn btn-success mb-3"
              data-id="0"
              onclick="mostrarModalInsumo(this)"
            >
              Agregar Producto
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
                    <td colspan="3" class="text-right">
                      <strong>COSTO INSUMOS:</strong>
                    </td>
                    <td id="total" class="text-end">0.00</td>
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
          Agregar Producto de Paquete
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
            <label for="insumo">Producto</label>
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
            Agregar Producto
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiGruposDeLaCartaUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";
  const apiCentralDeCostosUrl = "<?php echo URL_API_NUEVA ?>/centrales-costos";
  const apiProductosPaqueteUrl =
    "<?php echo URL_API_NUEVA ?>/productos-paquete";
  const apiConfigUrl = "<?php echo URL_API_NUEVA ?>/config";

  let insumosCargados = [];
  let insumosAgregados = [];
  let insumosTabla = [];
  let idsInsumosEliminados = [];

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarProductos();
    await cargarClasificacionVentas();

    if (id) {
      await cargarProductoPaquete();
    } else {
      await cargarCodigoProductoPaquete();
    }

    prepararFormularioPaquete();
  }

  let iterador = 1;

  async function cargarCodigoProductoPaquete() {
    try {
      const url = apiConfigUrl + "/10/codigo"; // 10 es el id de los productos
      const response = await fetch(url);
      const data = await response.json();

      const codigo = document.getElementById("codigo");
      codigo.value = data.codigo;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar el código del producto", "consultar");
    }
  }

  async function cargarProductoPaquete() {
    try {
      const response = await fetch(`${apiProductosUrl}/${id}/con-insumos`);
      const data = await response.json();

      const insumos = data.insumos;

      const nombrePaquete = document.getElementById("nombre_paquete");
      const descripcionPaquete = document.getElementById("descripcion_paquete");
      const codigo = document.getElementById("codigo");
      const clasificacionVentas = document.getElementById(
        "clasificacion_ventas"
      );
      const fechaVigencia = document.getElementById("fecha_vigencia");

      nombrePaquete.value = data.nombre_producto;
      descripcionPaquete.value = data.descripcion_del_producto;
      codigo.value = data.codigo;
      clasificacionVentas.value = data.id_grupo;
      fechaVigencia.value = data.fecha_de_vigencia;

      const tablaInsumos = document.getElementById("tabla-insumos");
      const tbody = tablaInsumos.querySelector("tbody");

      if (insumos) {
        insumos.forEach((insumo) => {
          const productoInsumo = insumosCargados.find(
            (producto) => producto.id_producto == insumo.id_producto_producto
          );

          const insumoAgregado = {
            id_paquete: insumo.id_paquete,
            id_producto: insumo.id_producto,
            id_insumo: insumo.id_producto_producto,
            nombre_insumo: productoInsumo.nombre_producto,
            cantidad: insumo.cantidad,
            tipo_de_unidad: insumo.tipo_de_unidad,
            costo: productoInsumo.precio_venta_03 * insumo.cantidad,
            idFila: iterador++,
          };

          insumosTabla.push(insumoAgregado);
        });
      }

      actualizarTabla();
      calcularCostoTotal();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar el producto", "consultar");
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

      data = data.filter((producto) => producto.id_tipo_de_producto != 10 && producto.id_tipo_de_producto != 11 && producto.tipo != 'PAQ' && producto.tipo != 'SVH');

      const insumoSelect = document.getElementById("insumo");
      insumoSelect.innerHTML = "";

      const option = document.createElement("option");
      option.value = "";
      option.textContent = "Seleccione un producto";
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
      console.error(error);
      mostrarAlert("error", "Error al cargar los productos", "consultar");
    }
  }

  function alCambiarInsumo() {
    const insumoSelect = document.getElementById("insumo");
    const botonAgregarInsumo = document.getElementById("agregar-insumo");

    if (!insumoSelect.value) {
      limpiarFormulario();
      botonAgregarInsumo.disabled = true;
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
    costoUnitarioInput.value = insumoSeleccionado.precio_venta_03;
    costoInput.value =
      +insumoSeleccionado.precio_venta_03 * +cantidadInput.value;

    botonAgregarInsumo.disabled = false;
  }

  function alCambiarCantidad() {
    const cantidadInput = document.getElementById("cantidad");
    const costoUnitarioInput = document.getElementById("costo-unitario");
    const costoInput = document.getElementById("costo");

    costoInput.value = (
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

      let grupos = data;
      grupos = ordenarGrupos(grupos);

      data.forEach((clasificacionVentas) => {
        const option = crearOpcionClasificacionVentas(clasificacionVentas);
        clasificacionVentasSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los grupos de la carta", "consultar");
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
      nombre_producto: document.getElementById("nombre_paquete").value,
      descripcion_del_producto: document.getElementById("descripcion_paquete")
        .value,
      codigo: document.getElementById("codigo").value,
      id_grupo: document.getElementById("clasificacion_ventas").value,
      fecha_de_vigencia: document.getElementById("fecha_vigencia").value,
    };

    if (id) { // TODO: Lo he cambiado mil veces... no se si esta bien
      producto.insumos_agregados = insumosAgregados.map((insumo) => {
        return {
          id_producto_producto: insumo.id_insumo,
          cantidad: insumo.cantidad,
          tipo_de_unidad: insumo.tipo_de_unidad,
        };
      });
      producto.ids_insumos_eliminados = idsInsumosEliminados;
    } else { 
      producto.insumos = insumosTabla.map((insumo) => {
        return {
          id_producto_producto: insumo.id_insumo,
          cantidad: insumo.cantidad,
          tipo_de_unidad: insumo.tipo_de_unidad,
        };
      });
    }

    const url = id
      ? apiProductosUrl + "/" + id + "/con-subproductos"
      : apiProductosUrl + "/paquete";

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

      window.location.href = `./../listado-catalogo/?ok&mensaje=Paquete ${editar ? "actualizado" : "creado"} correctamente&op=${
        editar ? "editar" : "crear"
      }`;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al crear el producto", "crear");
    }
  }

  function prepararFormularioPaquete() {
    const form = document.getElementById("form-crear-producto");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      crearProducto(id);
    });
  }

  function prepararFormularioInsumo() {
    const form = document.getElementById("form-crear-insumo");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      alAgregarInsumo();
    });
  }

  async function alAgregarInsumo() {
    const tablaInsumos = document.getElementById("tabla-insumos");
    const insumo = {
      id_insumo: document.getElementById("insumo").value,
      nombre_insumo:
        document.getElementById("insumo").options[
          document.getElementById("insumo").selectedIndex
        ].text,
      cantidad: document.getElementById("cantidad").value,
      tipo_de_unidad: document.getElementById("unidad").value,
      precio_venta_03: document.getElementById("costo-unitario").value,
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
      insumo.cantidad * productoInsumo.precio_venta_03
    );
    celdaEliminar.innerHTML = `<button type="button" class="btn btn-danger" onclick="eliminarInsumo(this)"><i class="fas fa-trash-alt"></i></button>`;

    row.dataset.idProducto = insumo.id_insumo;
    row.dataset.idFila = insumo.idFila;

    if (insumo.id_paquete) {
      row.dataset.idPaquete = insumo.id_paquete;
    }
  }

  function eliminarInsumo(e) {
    const tablaInsumos = document.getElementById("tabla-insumos");
    const tbody = tablaInsumos.querySelector("tbody");
    const fila = e.parentNode.parentNode;

    const idInsumo = fila.dataset.idProducto;
    const idPaquete = fila.dataset.idPaquete;
    const idFila = fila.dataset.idFila;

    insumosTabla = insumosTabla.filter((insumo) => insumo.idFila != idFila);

    if (idPaquete) {
      idsInsumosEliminados.push(idPaquete);
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
