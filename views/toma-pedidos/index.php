<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false; $idUsuario =
$_SESSION["usuario"]["id_usuario"]; mostrarHeader("pagina-funcion", $logueado);
?>
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
          <input
            type="text"
            class="form-control"
            list="producto-list"
            id="nombre_producto"
            placeholder="Buscar producto..."
            onchange="alCambiarProducto()"
          />
          <datalist id="producto-list"> </datalist>
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
          Asignación de Comanda
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
            />
            <datalist id="cliente-buscar-list"> </datalist>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
        <button
          type="button"
          class="btn btn-primary"
          onclick="guardarComanda()"
        >
          Aceptar
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

  let productoSeleccionado = null;
  let cantidadSeleccionada = null;

  let iterador = 1;
  let detalles = [];

  let desdeEstadoCuenta = false;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    const params = new URLSearchParams(window.location.search);
    const nroRegistroMaestro = params.get("nro_registro_maestro");
    desdeEstadoCuenta = nroRegistroMaestro != null;

    if (!desdeEstadoCuenta) {
      const datalist = document.getElementById("cliente-buscar-list");
      const inputBuscar = document.getElementById("cliente-buscar");

      const url = apiAcompanantesUrl + "?cuentas_abiertas";

      try {
        const response = await fetch(url);
        const data = await response.json();

        acompanantes = data;

        acompanantes.forEach((acompanante) => {
          const option = document.createElement("option");
          option.value = `${acompanante.apellidos_y_nombres} - ${acompanante.nro_registro_maestro} - ${acompanante.id_acompanante}`;
          datalist.appendChild(option);
        });
      } catch (error) {
        console.error(error);
        mostrarAlert("error", "Error al cargar los acompañantes", "consultar");
      }
    }

    await prepararBotonAceptar();

    await cargarDatosGruposYProductos();

    llenarDatalistProductos();

    const selectCliente = document.getElementById("cliente");
    const selectAplicado = document.getElementById("aplicado");

    prepararModalProducto();
    prepararFormularioCrearComanda();

    const btnGuardarComanda = document.getElementById("guardar-comanda");
    btnGuardarComanda.addEventListener("click", function (event) {
      event.preventDefault();
      guardarComanda();
    });
  }

  function prepararBotonAceptar() {
    const btnAceptar = document.getElementById("btn-guardar-comanda");
    btnAceptar.setAttribute(
      "data-bs-target",
      desdeEstadoCuenta ? "#modal-comanda" : "#modal-comanda-buscar"
    );
  }

  function actualizarTabla() {
    const tablaComandas = document.getElementById("tabla-comandas");
    const tbody = tablaComandas.querySelector("tbody");
    tbody.innerHTML = "";

    detalles.forEach((detalle) => {
      const tr = tbody.insertRow();
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
    let nroRegistroMaestro = null;
    let idAcompanante = null;
    const idUsuario = "<?php echo $idUsuario ?>";

    if (desdeEstadoCuenta) {
      const params = new URLSearchParams(window.location.search);
      nroRegistroMaestro = params.get("nro_registro_maestro");
      idAcompanante = document.getElementById("cliente").value;
    } else {
      const clienteBuscarList = document.getElementById("cliente-buscar-list");
      const inputBuscar = document.getElementById("cliente-buscar");

      nroRegistroMaestro = inputBuscar.value.split(" - ")[1];
      idAcompanante = inputBuscar.value.split(" - ")[2];
    }

    const url = `${apiDocumentosMovimientosUrl}/detalles`;

    // si no tiene acompañante, agregar el id del acompañante
    for (const detalle of detalles) {
      if (!("id_acompanate" in detalle)) {
        detalle["id_acompanate"] = idAcompanante;
      }
    }

    const data = {
      nro_registro_maestro: nroRegistroMaestro,
      id_usuario: idUsuario,
      detalles: detalles,
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
        window.location.href = desdeEstadoCuenta
          ? `./../estado-cuenta-cliente?nro_registro_maestro=${nroRegistroMaestro}&ok&mensaje=Comanda guardada correctamente&op=crear`
          : "../";
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
      cantidadSeleccionada = document.getElementById("cantidad").value;

      detalles.push({
        id_producto: productoSeleccionado.id_producto,
        producto: productoSeleccionado.nombre_producto,
        cantidad: cantidadSeleccionada,
        precio_unitario: productoSeleccionado.precio_venta_01,
      });

      const inputProducto = document.getElementById("nombre_producto");
      inputProducto.focus();

      actualizarTabla();
    });
  }

  async function cargarDatosGruposYProductos() {
    const params = new URLSearchParams(window.location.search);

    try {
      const responseProductos = await fetch(apiProductosUrl);
      let dataProductos = await responseProductos.json();

      productos = dataProductos;

    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los grupos y productos", "crear");
    }
  }
  
  async function agregarProducto(producto) {
    const modalCantidad = document.getElementById("modal-cantidad");
    const modalTerapista = document.getElementById("modal-terapista");
    const modalC = new bootstrap.Modal(modalCantidad);
    const modalT = new bootstrap.Modal(modalTerapista);

    const btnAceptarCantidad = document.getElementById("btn-aceptar-cantidad");
    const cantidad = document.getElementById("cantidad");

    productoSeleccionado = producto;
    cantidadSeleccionada = cantidad.value;

    if (producto.tipo === "SRV" && producto.requiere_programacion == 1) {
      const servicio = document.getElementById("servicio");
      servicio.value = producto.nombre_producto;

      await llenarOpcionesTerapistas(productoSeleccionado.codigo_habilidad);

      modalT.show();
    } else {
      modalC.show();
    }
  }

  function llenarDatalistProductos() {
    const datalist = document.getElementById("producto-list");
    datalist.innerHTML = "";

    console.log(productos);

    productos.forEach((producto) => {
      const option = document.createElement("option");
      option.value = `${producto.id_producto} - ${producto.nombre_producto}`;
      datalist.appendChild(option);
    });
  }

  function alCambiarProducto() {
    const inputProducto = document.getElementById("nombre_producto");
    const idProducto = inputProducto.value.split(" - ")[0];

    const producto = productos.find(
      (producto) => producto.id_producto == idProducto
    );

    inputProducto.value = "";

    if (producto) {
      agregarProducto(producto);
    } else {
      inputProducto.focus();
    }
  }
  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
