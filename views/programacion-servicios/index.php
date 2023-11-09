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
      <h2 class="text-center">PROGRAMACIÓN DE SERVICIOS DE SPA</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-3">
          FECHA: <input type="date" class="form-control" id="fecha"
          onchange="buscarServicios()" value="<?php echo date("Y-m-d"); ?>"/>
        </div>
      </div>

      <div class="table-responsive">
        <table id="tabla-servicios" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">Hora Inicio</th>
              <th class="text-center">Hora Final</th>
              <th class="text-center">Nombre del Cliente</th>
              <th class="text-center">T.Cliente</th>
              <th class="text-center">INSUMOS</th>
              <th class="text-center">Servicio</th>
              <th class="text-center">P.VENTA</th>
              <th class="text-center">Profesional Asignado</th>
              <th class="text-center">Estado</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <a href="./../liquidacion/" class="btn btn-primary ms-auto"
        >Ver Liquidación</a
      >
    </div>
  </div>
</div>

<div
  class="modal modal-sm fade"
  id="modal-cambiar-estado"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modal-cambiar-estado-label"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-cambiar-estado-label">
          Cambiar estado
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-recibo"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <label for="cliente">Nombre del Cliente:</label>
            <input type="text" class="form-control" id="cliente" disabled />
          </div>
          <div class="col-md-12">
            <label for="servicio-cambiar-estado">Servicio:</label>
            <input type="text" class="form-control" id="servicio-cambiar-estado" disabled />
          </div>
          <div class="col-md-12">
            <label for="profesional-asignado">Profesional asignado:</label>
            <input
              type="text"
              class="form-control"
              id="profesional-asignado"
              disabled
            />
          </div>
          <div class="col-md-12">
            <label for="estado">Estado:</label>
            <select class="form-select" id="estado">
              <option value="null">Seleccione un estado</option>
              <option value="0">Anulado</option>
              <option value="1">Realizado</option>
              <option value="2">Por realizar</option>
              <option value="3">Rechazado</option>
            </select>
          </div>
          <div class="col-md-12">
            <button
              class="btn btn-primary w-100 mt-3"
              onclick="cambiarEstado()"
            >
              Cambiar estado
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
          Cambio de servicio
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="servicio" class="form-label">Servicio</label>
          <input
            class="form-control"
            list="servicio-list"
            id="servicio"
            placeholder="Buscar servicio..."
            onchange="llenarOpcionesTerapistas()"
          />
          <datalist id="servicio-list"> </datalist>
        </div>

        <label for="servicio" class="form-label">Aplicado a</label>
        <select class="form-select mb-3" id="aplicado" name="aplicado"></select>

        <label for="preferencia" class="form-label"
          >Preferencia de terapeuta</label
        >
        <select
          class="form-select mb-3"
          id="preferencia"
          name="preferencia"
          onchange="llenarOpcionesTerapistas()"
        >
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
            <label for="fecha-servicio" class="form-label">Fecha</label>
            <input type="date" class="form-control mb-3" id="fecha-servicio" name="fecha" />
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

<script>
  const apiDocumentosDetallesUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-detalles";
  const apiTerapistasUrl = "<?php echo URL_API_NUEVA ?>/terapistas";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiAcompanantesUrl = "<?php echo URL_API_NUEVA ?>/acompanantes";

  let modal;
  let modalTerapista;

  let terapistas = [];
  let serviciosTerapistas = [];

  const estados = {
    null: "",
    0: "Anulado",
    1: "Realizado",
    2: "Por realizar",
    3: "Rechazado",
  };

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    cargarDatosTerapistas();

    llenarDatosServiciosTerapistas();

    modal = new bootstrap.Modal(
      document.getElementById("modal-cambiar-estado")
    );
    modalTerapista = new bootstrap.Modal(
      document.getElementById("modal-terapista")
    );

    buscarServicios();
  }

  async function cargarAcompanantes(nroRegistroMaestro) {
    const url =
      apiAcompanantesUrl + "?nro_registro_maestro=" + nroRegistroMaestro;

    try {
      const response = await fetch(url);
      const data = await response.json();

      const selectAcompanante = document.getElementById("aplicado");
      selectAcompanante.innerHTML = "";

      data.forEach((acompanante) => {
        const option = document.createElement("option");
        option.value = acompanante.nro_registro;
        option.textContent = acompanante.apellidos_y_nombres;
        selectAcompanante.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los acompañantes", "consultar");
    }
  }

  async function llenarDatosServiciosTerapistas() {
    const datalist = document.getElementById("servicio-list");
    const inputBuscar = document.getElementById("servicio");

    const url = apiProductosUrl + "?servicios-terapistas";

    try {
      const response = await fetch(url);
      const data = await response.json();

      serviciosTerapistas = data;

      serviciosTerapistas.forEach((servicio) => {
        const option = document.createElement("option");
        option.value = `${servicio.nombre_producto} - ${servicio.id_producto}`;
        datalist.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los servicios de terapistas",
        "consultar"
      );
    }
  }

  // #region Funciones de servicios

  async function buscarServicios() {
    const fecha = document.getElementById("fecha").value;
    const url = apiDocumentosDetallesUrl + "?servicios&fecha=" + fecha;

    await cargarServicios(url);
  }

  async function cargarServicios(url) {
    try {
      const response = await fetch(url);
      const servicios = await response.json();

      limpiarTabla();

      const tbody = document.getElementById("tabla-servicios").tBodies[0];
      servicios.forEach((servicio) => {
        const row = tbody.insertRow();

        row.dataset.id = servicio.id_documentos_detalle;
        row.dataset.nro_registro_maestro = servicio.nro_registro_maestro;
        row.dataset.id_producto = servicio.id_producto;

        row.dataset.nombre_cliente = servicio.nombre_cliente;
        row.dataset.servicio = servicio.servicio;
        row.dataset.profesional_asignado = servicio.profesional_asignado;
        row.dataset.estado = servicio.estado;
        row.dataset.hora_inicio = servicio.hora_inicio;

        const horaInicio = row.insertCell();
        horaInicio.innerHTML = servicio.hora_inicio;

        const horaFinal = row.insertCell();
        horaFinal.innerHTML = servicio.hora_final;

        const nombre = row.insertCell();
        nombre.innerHTML = servicio.nombre_cliente;

        const tipoCliente = row.insertCell();
        tipoCliente.innerHTML = servicio.tipo_cliente;

        const conInsumos = row.insertCell();
        conInsumos.innerHTML = servicio.con_insumos;

        const servicioNombre = row.insertCell();
        servicioNombre.classList.add(
          "text-decoration-underline",
          "text-primary"
        );
        servicioNombre.style.cursor = "pointer";
        servicioNombre.innerHTML = servicio.servicio;
        servicioNombre.addEventListener("click", mostrarModalTerapista);

        const precioVenta = row.insertCell();
        precioVenta.classList.add("text-end");
        precioVenta.innerHTML = formatearCantidad(servicio.precio_venta);

        const profesional = row.insertCell();
        profesional.innerHTML = servicio.profesional_asignado;

        const estado = row.insertCell();
        estado.classList.add(
          "text-center",
          "text-decoration-underline",
          "text-primary"
        );
        estado.style.cursor = "pointer";
        estado.innerHTML = estados[servicio.estado];
        estado.addEventListener("click", mostrarModalCambiarEstado);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar los comprobantes", "consultar");
    }
  }

  // #endregion

  function mostrarModalTerapista(event) {
    const row = event.target.closest("tr");

    const modalEl = document.getElementById("modal-cambiar-estado");
    modalEl.dataset.id = row.dataset.id;
    modalEl.dataset.nro_registro_maestro = row.dataset.nro_registro_maestro;
    modalEl.dataset.id_producto = row.dataset.id_producto;

    const cliente = document.getElementById("cliente");
    const servicio = document.getElementById("servicio");
    const profesionalAsignado = document.getElementById("profesional-asignado");
    const fecha = document.getElementById("fecha-servicio");
    const hora = document.getElementById("hora");

    cliente.value = row.dataset.nombre_cliente;
    servicio.value = row.dataset.servicio + " - " + row.dataset.id_producto;
    profesionalAsignado.value = row.dataset.profesional_asignado;
    fecha.value = document.getElementById("fecha").value;
    hora.value = row.dataset.hora_inicio;

    cargarAcompanantes(row.dataset.nro_registro_maestro);
    llenarOpcionesTerapistas();

    modalTerapista.show();
  }

  function mostrarModalCambiarEstado(event) {
    const row = event.target.closest("tr");

    const modalEl = document.getElementById("modal-cambiar-estado");
    modalEl.dataset.id = row.dataset.id;

    const servicio = document.getElementById("servicio-cambiar-estado");
    const cliente = document.getElementById("cliente");
    const profesionalAsignado = document.getElementById("profesional-asignado");
    const estado = document.getElementById("estado");

    cliente.value = row.dataset.nombre_cliente;
    servicio.value = row.dataset.servicio;
    profesionalAsignado.value = row.dataset.profesional_asignado;
    estado.value = row.dataset.estado;

    modal.show();
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

  async function llenarOpcionesTerapistas() {
    const servicioSeleccionado = document.getElementById("servicio").value;
    console.log(servicioSeleccionado);

    const idServicio = servicioSeleccionado.split(" - ")[1];
    console.log(idServicio);

    const servicio = serviciosTerapistas.find((servicio) => {
      return servicio.id_producto == idServicio;
    });
    console.log(servicio);

    const codigoHabilidad = servicio.codigo_habilidad;
    const sexo = document.getElementById("preferencia").value;

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

  async function cambiarEstado() {
    const modalEl = document.getElementById("modal-cambiar-estado");
    const id = modalEl.dataset.id;
    const estado = document.getElementById("estado").value;

    console.log(id);

    const url = `${apiDocumentosDetallesUrl}/${id}/estado`;

    const body = {
      estado_servicio: estado,
    };

    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      mostrarAlert("ok", "Estado cambiado correctamente", "editar");

      buscarServicios();

      modal.hide();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cambiar el estado", "editar");
    }
  }

  function limpiarTabla() {
    const tbody = document.getElementById("tabla-servicios").tBodies[0];
    tbody.innerHTML = "";
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
