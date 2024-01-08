<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false; $idUsuario =
$_SESSION["usuario"]["id_usuario"]; $pre = ENV == 'server' ?
'/hotelarenasspa/cliente' : '/cliente'; mostrarHeader("toma-pedidos"); ?>
<div class="container my-5 main-cont">
  <div id="alert-place"></div>

  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">Agregar comanda</h2>
    </div>
    <div class="card-body">
      <form id="form-crear-comanda">
        <div class="form-group">
          <label for="fecha">Fecha: </label>
          <input type="text" class="form-control" id="fecha" name="fecha"
          value="<?php echo date("d/m/Y"); ?>" disabled />
        </div>
        <div class="form-group">
          <label for="nombre_producto">Buscar por nombre</label>
          <div class="d-flex">
            <input
              type="text"
              class="form-control"
              list="producto-list"
              id="nombre_producto"
              placeholder="Buscar producto..."
              onchange="alCambiarProducto()"
            />

            <div class="dropdown">
              <button
                class="btn btn-secondary dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              ></button>
              <ul class="dropdown-menu" id="productos-list"></ul>
            </div>
          </div>
        </div>

        <div class="table-responsive mt-4">
          <table class="table table-hover" id="tabla-comandas">
            <thead>
              <tr>
                <th>DESCRIPCION</th>
                <th>CANT.</th>
                <th>P. VENTA</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </form>
    </div>
    <div class="card-footer">
      <button
        class="btn btn-success"
        id="btn-guardar-comanda"
        data-bs-toggle="modal"
      >
        SELECCIONE AL CLIENTE
      </button>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-cantidad"
  tabindex="-1"
  aria-labelledby="modal-cantidad-label"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-cantidad-label">Cantidad</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input
              type="number"
              class="form-control"
              id="cantidad"
              name="cantidad"
              min="1"
              value="1"
            />
          </div>
          <div class="col-12 mt-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea
              class="form-control"
              id="observaciones"
              name="observaciones"
              rows="3"
            ></textarea>
          </div>
          <div class="col mt-3">
            <button
              type="button"
              class="btn btn-primary w-100"
              id="btn-aceptar-cantidad"
              data-bs-dismiss="modal"
            >
              Aceptar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-comanda-buscar"
  tabindex="-1"
  aria-labelledby="modal-comanda-buscar-label"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-comanda-buscar-label">
          Asignar al cliente
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="cliente-buscar" class="form-label">Cliente</label>
            <input
              class="form-control"
              list="cliente-buscar-list"
              id="cliente-buscar"
              placeholder="Buscar cliente..."
              onchange="alCambiarCliente(event)"
            />
            <datalist id="cliente-buscar-list"> </datalist>
          </div>
          <div class="row">
            <div class="col-3">
              <label for="nombre-asignar" class="form-label">Nombre</label>
            </div>
            <div class="col-9 mb-3">
              <input class="form-control" id="nombre-asignar" disabled />
            </div>
            <div class="col-3">
              <label for="tipo-asignar" class="form-label">Tipo</label>
            </div>
            <div class="col-9 mb-3">
              <input class="form-control" id="tipo-asignar" disabled />
            </div>
            <div class="col-3">
              <label for="habitacion-asignar" class="form-label"
                >Habitación</label
              >
            </div>
            <div class="col-9 mb-3">
              <input class="form-control" id="habitacion-asignar" disabled />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-primary mx-auto"
          onclick="guardarComanda()"
        >
          REGISTRO DE PEDIDO
        </button>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-confirmar-borrado"
  tabindex="-1"
  aria-labelledby="modal-confirmar-borrado-label"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <p>¿Está seguro que desea eliminar este producto?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
        <button
          type="button"
          class="btn btn-danger"
          onclick="borrarDetalle()"
          data-bs-dismiss="modal"
        >
          Eliminar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const apiGruposUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiAcompanantesUrl = "<?php echo URL_API_NUEVA ?>/acompanantes";
  const apiDocumentosMovimientosUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-movimientos";
  const apiTerapistasUrl = "<?php echo URL_API_NUEVA ?>/terapistas";

  let grupos = [];
  let productos = [];
  let terapistas = [];
  let acompanantes = [];
  let detalles = [];

  let productoSeleccionado = null;
  let cantidadSeleccionada = null;

  let nroRegistroMaestro = null;
  let idAcompanante = null;

  let iterador = 1;

  let nombreProductoEl = null;
  let borrarModal = null;

  let tiempoInicio = 0;
  let tiempoLimite = 500;

  let idProductoBorrar = null;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    const datalist = document.getElementById("cliente-buscar-list");
    const inputBuscar = document.getElementById("cliente-buscar");

    await cargarAcompanantes();
    await prepararBotonAceptar();
    await cargarDatosGruposYProductos();

    const selectCliente = document.getElementById("cliente");
    const selectAplicado = document.getElementById("aplicado");

    nombreProductoEl = document.getElementById("nombre_producto");

    prepararModalProducto();
    prepararFormularioCrearComanda();
  }

  function prepararLongPress(target) {
    target.addEventListener("mousedown", (event) => {
      event.preventDefault();

      tiempoInicio = Date.now();

      setTimeout(() => {
        const tiempoTranscurrido = Date.now() - tiempoInicio;
        if (tiempoTranscurrido >= tiempoLimite) {
          idProductoBorrar = target.dataset.id;

          const modalConfirmarBorrado = document.getElementById(
            "modal-confirmar-borrado"
          );
          const modalCB = new bootstrap.Modal(modalConfirmarBorrado);

          modalCB.show();
        }
      }, tiempoLimite);
    });

    target.addEventListener("mouseup", () => {
      clearTimeout(tiempoInicio);
    });
  }

  async function cargarAcompanantes() {
    const url = apiAcompanantesUrl + "?cuentas_abiertas";

    try {
      const response = await fetch(url);
      const data = await response.json();

      acompanantes = data;

      const datalist = document.getElementById("cliente-buscar-list");

      acompanantes.forEach((acompanante) => {
        const option = document.createElement("option");
        option.value = `${acompanante.apellidos_y_nombres} - ${acompanante.id_acompanante} - ${acompanante.nro_registro_maestro}`;
        datalist.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los acompañantes", "consultar");
    }
  }

  function alCambiarCliente() {
    const inputBuscar = document.getElementById("cliente-buscar");

    idAcompanante = inputBuscar.value.split(" - ")[1];

    const acompanante = acompanantes.find(
      (acompanante) => acompanante.id_acompanante == idAcompanante
    );

    if (acompanante) {
      const inputNombre = document.getElementById("nombre-asignar");
      const inputTipo = document.getElementById("tipo-asignar");
      const inputHabitacion = document.getElementById("habitacion-asignar");

      nroRegistroMaestro = acompanante.nro_registro_maestro;

      inputNombre.value = acompanante.apellidos_y_nombres;
      inputTipo.value = acompanante.tipo_de_servicio;
      inputHabitacion.value = acompanante.nro_habitacion;

      inputBuscar.value = "";
    }
  }

  function prepararBotonAceptar() {
    const btnAceptar = document.getElementById("btn-guardar-comanda");
    btnAceptar.setAttribute("data-bs-target", "#modal-comanda-buscar");
  }

  function actualizarTabla() {
    const tablaComandas = document.getElementById("tabla-comandas");
    const tbody = tablaComandas.querySelector("tbody");
    tbody.innerHTML = "";

    iterador = 1;

    detalles.forEach((detalle) => {
      const tr = tbody.insertRow();
      prepararLongPress(tr);

      tr.dataset.id = detalle.id_producto;

      const tdProducto = tr.insertCell();
      const tdCantidad = tr.insertCell();
      const tdPrecioVenta = tr.insertCell();

      tdCantidad.classList.add("text-center");
      tdPrecioVenta.classList.add("text-end");

      tdProducto.textContent = detalle.producto;
      tdCantidad.textContent = detalle.cantidad;
      tdPrecioVenta.textContent = formatearCantidad(detalle.precio_unitario);
    });
  }

  async function guardarComanda() {
    const idUsuario = "<?php echo $idUsuario ?>";

    const clienteBuscarList = document.getElementById("cliente-buscar-list");
    const inputBuscar = document.getElementById("cliente-buscar");

    const url = `${apiDocumentosMovimientosUrl}/detalles`;

    const data = {
      nro_registro_maestro: nroRegistroMaestro,
      id_usuario: idUsuario,
      detalles: detalles.map((detalle) => ({
        ...detalle,
        id_acompanate: idAcompanante,
      })),
    };

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      if (data.resultado) {
        window.location.href = `./../colaborador?ok&mensaje=Comanda guardada correctamente&op=crear`;
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al guardar la comanda", "crear");
    }
  }

  function prepararFormularioCrearComanda() {
    const formCrearComanda = document.getElementById("form-crear-comanda");

    formCrearComanda.addEventListener("submit", function (event) {
      event.preventDefault();
    });
  }

  function prepararModalProducto() {
    const btnAceptarCantidad = document.getElementById("btn-aceptar-cantidad");

    btnAceptarCantidad.addEventListener("click", function (event) {
      cantidadSeleccionada = document.getElementById("cantidad");
      observaciones = document.getElementById("observaciones");

      detalles.push({
        id_producto: productoSeleccionado.id_producto,
        producto: productoSeleccionado.nombre_producto,
        cantidad: cantidadSeleccionada.value,
        precio_unitario: productoSeleccionado.precio_venta_01,
        observaciones: observaciones.value,
      });

      const inputProducto = document.getElementById("nombre_producto");
      inputProducto.focus();

      cantidadSeleccionada.value = 1;
      observaciones.value = "";

      // limpiar input
      nombreProductoEl.value = "";

      actualizarTabla();
    });
  }

  async function cargarDatosGruposYProductos() {
    const url = apiProductosUrl + "?solo-productos";

    try {
      const responseProductos = await fetch(url);
      let dataProductos = await responseProductos.json();

      productos = dataProductos;

      // ordenar por nombre
      productos.sort((a, b) => {
        if (a.nombre_producto < b.nombre_producto) {
          return -1;
        }
        if (a.nombre_producto > b.nombre_producto) {
          return 1;
        }
        return 0;
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los grupos y productos", "crear");
    }
  }

  async function agregarProducto(producto) {
    const modalCantidad = document.getElementById("modal-cantidad");
    const modalC = new bootstrap.Modal(modalCantidad);

    const btnAceptarCantidad = document.getElementById("btn-aceptar-cantidad");
    const cantidad = document.getElementById("cantidad");

    productoSeleccionado = producto;
    cantidadSeleccionada = cantidad.value;

    modalC.show();
  }

  function alCambiarProducto() {
    const inputProducto = document.getElementById("nombre_producto");

    const listProductosFiltrados = document.getElementById("productos-list");
    listProductosFiltrados.innerHTML = "";

    const productosFiltrados = productos.filter((producto) =>
      producto.nombre_producto
        .toLowerCase()
        .includes(inputProducto.value.toLowerCase())
    );

    productosFiltrados.forEach((producto) => {
      const listItem = document.createElement("li");
      const linkItem = document.createElement("a");
      linkItem.classList.add("dropdown-item");

      linkItem.textContent = producto.nombre_producto;
      linkItem.href = "#";
      linkItem.addEventListener("click", function (event) {
        event.preventDefault();
        agregarProducto(producto);
      });

      listItem.appendChild(linkItem);
      listProductosFiltrados.appendChild(listItem);
    });
  }

  function borrarDetalle() {
    detalles = detalles.filter(
      (detalle) => detalle.id_producto != idProductoBorrar
    );
    actualizarTabla();
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
