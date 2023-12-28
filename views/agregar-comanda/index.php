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
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="nro_comanda">Nro de Comanda</label>
            <input
              type="text"
              class="form-control"
              id="nro_comanda"
              name="nro_comanda"
              disabled
            />
          </div>
          <div class="form-group ms-auto col-md-5">
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
        </div>
        <div class="row">
          <div class="table-responsive col-md-7">
            <table class="table table-hover" id="tabla-comandas">
              <thead>
                <tr>
                  <th style="width: 100px">Fecha</th>
                  <th>Producto</th>
                  <th style="width: 100px">Cantidad</th>
                  <th style="width: 100px">P. Venta</th>
                  <th style="width: 100px">P. Total</th>
                  <th style="width: 100px">Eliminar</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="col-md-5">
            <div class="row">
              <div class="col-12 px-0">
                <div
                  class="list-group"
                  id="lista-grupos"
                  data-page="agregar-comanda"
                >
                  <div
                    class="button-group list-group-item list-group-item-action"
                    id="cabecera-grupos"
                  >
                    <span class="fw-bold ms-2">GRUPOS</span>
                  </div>
                </div>
              </div>
              <div
                class="col-12 table-responsive"
                id="tabla-productos-wrapper"
                data-page="agregar-comanda"
              >
                <table
                  class="table table-bordered table-hover"
                  id="tabla-productos"
                  data-page="agregar-comanda"
                >
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th>P. Unitario</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="2" class="text-center text-muted">
                        Debe seleccionar un grupo
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="card-footer">
      <button
        class="btn btn-success"
        id="btn-guardar-comanda"
        data-bs-toggle="modal"
      >
        Aceptar
      </button>
      <button type="button" class="btn btn-outline-danger" id="btn-salir">
        Salir
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
          <div class="col">
            <input
              type="number"
              class="form-control"
              id="cantidad"
              name="cantidad"
              min="1"
              value="1"
            />
          </div>
          <div class="col">
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
  id="modal-terapista"
  tabindex="-1"
  aria-labelledby="modal-terapista-label"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-terapista-label">
          Asignación de servicio
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <label for="servicio" class="form-label">Servicio</label>
        <input
          type="text"
          class="form-control mb-3"
          id="servicio"
          name="servicio"
          disabled
        />

        <label for="servicio" class="form-label">Aplicado a</label>
        <select class="form-select mb-3" id="aplicado" name="aplicado"></select>

        <label for="preferencia" class="form-label"
          >Preferencia de terapeuta</label
        >
        <select class="form-select mb-3" id="preferencia" name="preferencia">
          <option value="A" selected>Ambos</option>
          <option value="F">Femenino</option>
          <option value="M">Masculino</option>
        </select>

        <label for="terapista" class="form-label">Profesional asignado</label>
        <select
          class="form-select mb-3"
          id="terapista"
          name="terapista"
        ></select>

        <div class="row">
          <div class="col">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control mb-3" id="fecha" name="fecha"
            value="<?php echo date("Y-m-d"); ?>" />
          </div>
          <div class="col">
            <label for="hora" class="form-label">Hora</label>
            <input
              type="time"
              class="form-control mb-3"
              id="hora"
              name="hora"
            />
          </div>
        </div>

        <button
          type="button"
          class="btn btn-primary w-100"
          id="btn-aceptar-servicio"
          data-bs-dismiss="modal"
        >
          Aceptar
        </button>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-comanda"
  tabindex="-1"
  aria-labelledby="modal-comanda-label"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-comanda-label">
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
            <label for="cliente" class="form-label">Cliente</label>
            <select class="form-select" id="cliente"></select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
        <button type="button" class="btn btn-primary" id="guardar-comanda">
          Aceptar
        </button>
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
  const apiConfigCodigoUrl = "<?php echo URL_API_NUEVA ?>/config";
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

    mostrarCodigoComanda();
    await cargarDatosGruposYProductos();

    llenarDatalistProductos();

    await cargarDatosTerapistas();

    const selectCliente = document.getElementById("cliente");
    const selectAplicado = document.getElementById("aplicado");

    await cargarDatosAcompanantes(selectCliente);
    await cargarDatosAcompanantes(selectAplicado);

    await prepararTablaProductos();
    prepararModalProducto();
    prepararFormularioCrearComanda();
    prepararModalServicio();

    prepararBotonSalir();

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

  function prepararBotonSalir() {
    const btnSalir = document.getElementById("btn-salir");
    btnSalir.addEventListener("click", function (event) {
      event.preventDefault();

      const params = new URLSearchParams(window.location.search);
      const nroRegistroMaestro = params.get("nro_registro_maestro");

      window.location.href = `./../estado-cuenta-cliente?nro_registro_maestro=${nroRegistroMaestro}`;
    });
  }

  async function cargarDatosTerapistas() {
    const selectTerapista = document.getElementById("terapista");

    const url = apiTerapistasUrl + "?con_habilidades";

    try {
      const response = await fetch(url);
      const data = await response.json();

      terapistas = data;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los terapistas", "consultar");
    }
  }

  function actualizarTabla() {
    const tablaComandas = document.getElementById("tabla-comandas");
    const tbody = tablaComandas.querySelector("tbody");
    tbody.innerHTML = "";

    detalles.forEach((detalle) => {
      const tr = document.createElement("tr");
      const tdFecha = document.createElement("td");
      const tdProducto = document.createElement("td");
      const tdCantidad = document.createElement("td");
      const tdPrecioVenta = document.createElement("td");
      const tdPrecioTotal = document.createElement("td");
      const tdEliminar = document.createElement("td");

      const fecha = new Date(detalle.fecha_servicio || Date.now());
      const fechaFormateada = fecha.toLocaleDateString("es-PE", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
      });

      tdCantidad.classList.add("text-center");
      tdPrecioVenta.classList.add("text-end");
      tdPrecioTotal.classList.add("text-end");

      tdFecha.textContent = fechaFormateada;
      tdProducto.textContent = detalle.producto;
      tdCantidad.textContent = detalle.cantidad;
      tdPrecioVenta.textContent = formatearCantidad(detalle.precio_unitario);
      tdPrecioTotal.textContent = formatearCantidad(
        detalle.precio_unitario * detalle.cantidad
      );
      tdEliminar.innerHTML = `<button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>`;

      const btnEliminar = tdEliminar.querySelector("button");

      tr.appendChild(tdFecha);
      tr.appendChild(tdProducto);
      tr.appendChild(tdCantidad);
      tr.appendChild(tdPrecioVenta);
      tr.appendChild(tdPrecioTotal);
      tr.appendChild(tdEliminar);

      btnEliminar.addEventListener("click", elminarFila);

      function elminarFila(event) {
        event.preventDefault();
        event.stopPropagation();

        btnEliminar.removeEventListener("click", elminarFila);
        tr.remove();

        detalles = detalles.filter((d) => d.id_producto != detalle.id_producto);
      }

      tbody.appendChild(tr);
    });
  }

  async function llenarOpcionesTerapistas(codigoHabilidad, sexo = "A") {
    const selectTerapista = document.getElementById("terapista");
    selectTerapista.innerHTML = "";

    const terapistasConHabilidad = terapistas.filter((terapista) => {
      return terapista.habilidades
        .map((habilidad) => habilidad.codigo_habilidad)
        .includes(codigoHabilidad);
    });

    const terapistasPreferentes =
      sexo != "A"
        ? terapistasConHabilidad.filter((terapista) => terapista.sexo === sexo)
        : terapistasConHabilidad;

    terapistasPreferentes.forEach((terapista) => {
      const option = document.createElement("option");
      option.value = terapista.id_profesional;
      option.textContent = `${terapista.nombre}`;
      selectTerapista.appendChild(option);
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

  async function cargarDatosAcompanantes(selectCliente) {
    const params = new URLSearchParams(window.location.search);
    const nroRegistroMaestro = params.get("nro_registro_maestro");

    const url = `${apiAcompanantesUrl}?nro_registro_maestro=${nroRegistroMaestro}`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      data.forEach((acompanante) => {
        const option = document.createElement("option");
        option.value = acompanante.id_acompanante;
        option.textContent = acompanante.apellidos_y_nombres;
        selectCliente.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los acompañantes", "consultar");
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

  function prepararModalServicio() {
    const btnAceptarServicio = document.getElementById("btn-aceptar-servicio");
    const selectPreferencia = document.getElementById("preferencia");
    const selectTerapista = document.getElementById("terapista");

    selectPreferencia.addEventListener("change", async function (event) {
      const preferencia = selectPreferencia.value;

      selectTerapista.innerHTML = "";
      await llenarOpcionesTerapistas(
        productoSeleccionado.codigo_habilidad,
        preferencia
      );
    });

    btnAceptarServicio.addEventListener("click", function (event) {
      const servicio = document.getElementById("servicio").value;
      const aplicado = document.getElementById("aplicado").value;
      const preferencia = document.getElementById("preferencia").value;
      const terapista = document.getElementById("terapista").value;
      const fecha = document.getElementById("fecha").value;
      const hora = document.getElementById("hora").value;

      detalles.push({
        id_producto: productoSeleccionado.id_producto,
        producto: servicio,
        cantidad: 1,
        precio_unitario: productoSeleccionado.precio_venta_01,

        id_acompanate: aplicado,
        id_profesional: terapista,
        fecha_servicio: fecha,
        hora_servicio: hora,
      });

      const inputProducto = document.getElementById("nombre_producto");
      inputProducto.focus();

      actualizarTabla();
    });
  }

  async function mostrarCodigoComanda() {
    const nroComanda = document.getElementById("nro_comanda");
    const url = apiConfigCodigoUrl + "/5/codigo";

    try {
      const response = await fetch(url);
      const data = await response.json();

      nroComanda.value = data.codigo;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar el código de la comanda", "crear");
    }
  }

  async function cargarDatosGruposYProductos() {
    const params = new URLSearchParams(window.location.search);
    const nroRegistroMaestro = params.get("nro_registro_maestro");

    try {
      const responseGrupos = await fetch(apiGruposUrl);
      const responseProductos = await fetch(apiProductosUrl);

      const dataGrupos = await responseGrupos.json();
      let dataProductos = await responseProductos.json();

      dataProductos = dataProductos.filter(
        (producto) =>
          ["SRV", "PAQ", "RST"].includes(producto.tipo) ||
          [12, 13].includes(producto.id_tipo_de_producto)
      );

      productos = dataProductos;

      grupos = dataGrupos.filter(
        (grupo) => grupo.codigo_grupo === grupo.codigo_subgrupo
      );

      grupos = grupos.map((grupo) => {
        const subgrupos = dataGrupos
          .filter(
            (subgrupo) =>
              subgrupo.codigo_grupo != subgrupo.codigo_subgrupo &&
              subgrupo.codigo_grupo === grupo.codigo_grupo
          )
          .map((subgrupo) => ({
            id_subgrupo: subgrupo.id_grupo,
            subgrupo: subgrupo.nombre_grupo,
            productos: dataProductos.filter(
              (producto) => producto.id_grupo === subgrupo.id_grupo
            ),
          }));
        return {
          id_grupo: grupo.id_grupo,
          grupo: grupo.nombre_grupo,
          productos: dataProductos.filter(
            (producto) => producto.id_grupo === grupo.id_grupo
          ),
          subgrupos: subgrupos,
        };
      });

      cargarGrupos();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los grupos y productos", "crear");
    }
  }

  function cargarGrupos() {
    const listaGrupos = document.getElementById("lista-grupos");

    // borrar los botones que ya estaban
    listaGrupos.querySelectorAll("button").forEach((boton) => {
      boton.remove();
    });

    grupos.forEach((grupo) => {
      const botonGrupo = document.createElement("button");
      botonGrupo.classList.add("list-group-item", "list-group-item-action");
      botonGrupo.textContent = grupo.grupo;

      botonGrupo.addEventListener("click", function (event) {
        event.preventDefault();

        const textoCabecera = document.querySelector("#cabecera-grupos span");
        textoCabecera.textContent = "GRUPO: " + grupo.grupo;

        cargarProductos(grupo.productos);
        cargarSubgrupos(grupo.subgrupos);
      });
      listaGrupos.appendChild(botonGrupo);
    });
  }

  function cargarSubgrupos(subgrupos) {
    const listaGrupos = document.getElementById("lista-grupos");

    console.log(subgrupos);

    // borrar los botones que ya estaban
    listaGrupos.querySelectorAll("button").forEach((boton) => {
      boton.remove();
    });

    if (subgrupos.length > 0) {
      subgrupos.forEach((subgrupo) => {
        const botonSubgrupo = document.createElement("button");
        botonSubgrupo.classList.add(
          "list-group-item",
          "list-group-item-action"
        );
        botonSubgrupo.textContent = subgrupo.subgrupo;

        botonSubgrupo.addEventListener("click", function (event) {
          event.preventDefault();
          cargarProductos(subgrupo.productos);
        });
        listaGrupos.appendChild(botonSubgrupo);
      });
    } else {
      // mostrar un mensaje que diga que no hay subgrupos
      const noSubgrupos = document.createElement("button");
      noSubgrupos.classList.add("list-group-item", "text-center", "text-muted");
      noSubgrupos.textContent = "No hay subgrupos";

      listaGrupos.appendChild(noSubgrupos);
    }

    const cabeceraGrupos = document.getElementById("cabecera-grupos");
    const textoCabecera = cabeceraGrupos.querySelector("span");

    const botonVolver = document.createElement("button");
    botonVolver.classList.add("btn", "btn-outline-primary");
    botonVolver.innerHTML =
      `<i class="fas fa-arrow-left"></i>` + botonVolver.innerHTML;
    botonVolver.addEventListener("click", function (event) {
      event.preventDefault();
      cargarGrupos();

      const tablaProductos = document.getElementById("tabla-productos");
      const tbody = tablaProductos.querySelector("tbody");
      tbody.innerHTML = "";

      // debería cambiar el texto a "Grupos"
      const texto = document.querySelector("#cabecera-grupos span");
      texto.textContent = "GRUPOS";

      // mostrar un mensaje que diga que debe seleccionar un grupo
      const noProductos = document.createElement("tr");
      noProductos.classList.add("text-center", "text-muted");
      noProductos.innerHTML = `<td colspan="2">Debe seleccionar un grupo</td>`;
      tbody.appendChild(noProductos);
    });
    cabeceraGrupos.insertBefore(botonVolver, textoCabecera);
  }

  function cargarProductos(productos) {
    const tablaProductos = document.getElementById("tabla-productos");
    const tbody = tablaProductos.querySelector("tbody");
    tbody.innerHTML = "";

    if (productos.length > 0) {
      productos.forEach((producto) => {
        const tr = tbody.insertRow();
        const tdProducto = tr.insertCell();
        const tdPrecio = tr.insertCell();

        tdPrecio.classList.add("text-end");
        tdProducto.innerHTML = `<a href="#" class="text-decoration-none text-body">${producto.nombre_producto}</a>`;
        tdPrecio.innerHTML = `<a href="#" class="text-decoration-none text-body">${
          producto.precio_venta_01
            ? formatearCantidad(producto.precio_venta_01)
            : ""
        }</a>`;

        tr.dataset.idProducto = producto.id_producto;
        tr.dataset.tipo = producto.tipo;
        tr.dataset.requiereProgramacion = producto.requiere_programacion;
      });
    } else {
      // mostrar un mensaje que diga que no hay productos
      const noProductos = document.createElement("tr");
      noProductos.classList.add("text-center", "text-muted");
      noProductos.innerHTML = `<td colspan="2">No hay productos</td>`;
      tbody.appendChild(noProductos);
    }
  }
  async function prepararTablaProductos() {
    const tablaProductos = document.getElementById("tabla-productos");
    const tbody = tablaProductos.querySelector("tbody");

    tbody.addEventListener("click", function (event) {
      event.preventDefault();
      const tr = event.target.closest("tr");

      if (!tr.dataset.idProducto) return;

      const idProducto = tr.dataset.idProducto;

      const producto = productos.find(
        (producto) => producto.id_producto == idProducto
      );
      agregarProducto(producto);
    });
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
