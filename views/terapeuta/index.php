<?php
require "../../inc/header.php";

session_start();
mostrarHeader("terapeuta", false);
$idUnidadNegocio = isset($_GET["un"]) ? $_GET ["un"] : null;

$prePath = ENV == 'server' ? '/hotelarenasspa/cliente' : '/cliente';
?>

<div class="container my-5 pt-5 main-cont">
  <div id="alert-place"></div>

  <div class="card w-50 mx-auto" id="card-login">
    <div class="card-header py-3">
      <h2 class="h3 fw-normal text-center">Inicio de sesión de terapeutas</h2>
    </div>
    <div class="card-body">
      <main class="form-signin w-100 m-auto">
        <div class="row">
          <img
            src="<?php echo $prePath ?>/img/logo.webp"
            alt="logo"
            class="d-inline-block align-text-top img-fluid w-25 mb-3 mx-auto"
          />
        </div>
        <div class="form-group mb-3">
          <label for="usuario">Nombre de usuario</label>
          <input
            type="text"
            class="form-control"
            id="usuario"
            placeholder="Ingrese su nombre de usuario"
          />
        </div>
        <div class="form-group mb-3">
          <label for="contraseña">Contraseña</label>
          <input
            type="password"
            class="form-control"
            id="contraseña"
            placeholder="Ingrese su contraseña"
          />
        </div>
        <button class="btn btn-primary w-100 py-2" onclick="loginTerapeuta()">
          Iniciar sesión
        </button>
      </main>
    </div>
  </div>

  <div class="card w-100 mb-3 d-none" id="card-servicios">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12">
          Profesional:
          <input
            type="text"
            class="form-control"
            id="nombre_profesional"
            disabled
          />
        </div>
        <div class="col-12">
          Fecha: <input type="date" class="form-control" id="fecha"
          onchange="buscarServicios()" value="<?php echo date("Y-m-d"); ?>"/>
        </div>
      </div>

      <div class="table-responsive">
        <table id="tabla-servicios" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">Hora</th>
              <th class="text-center">Producto</th>
              <th class="text-center">Paciente</th>
              <th class="text-center">PRECIO</th>
              <th class="text-center">Estado</th>
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
  id="cambiar-estado-servicio-inicio-modal"
  tabindex="-1"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5>INICIO DEL SERVICIO</h5>

        <div class="row g-3">
          <div class="col-12">
            <label for="servicio-estado-inicio" class="form-label"
              >Servicio:</label
            >
            <input
              type="text"
              class="form-control"
              id="servicio-estado-inicio"
              disabled
            />
          </div>
          <div class="col-12">
            <label for="cliente-estado-inicio" class="form-label"
              >Cliente:</label
            >
            <input
              type="text"
              class="form-control"
              id="cliente-estado-inicio"
              disabled
            />
          </div>
          <div class="col-12">
            <label for="hora_inicio" class="form-label">Hora de Inicio:</label>
            <input type="time" class="form-control" id="hora_inicio" disabled />
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="d-flex justify-content-end">
          <button
            type="button"
            class="btn btn-primary"
            data-bs-dismiss="modal"
            onclick="cambiarEstadoServicio(3)"
          >
            ACEPTO
          </button>
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            SALIR
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="cambiar-estado-servicio-termino-modal"
  tabindex="-1"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5>SERVICIO TERMINADO</h5>

        <div class="row g-3">
          <div class="col-12">
            <label for="servicio-estado-terminado" class="form-label"
              >Servicio:</label
            >
            <input
              type="text"
              class="form-control"
              id="servicio-estado-terminado"
              disabled
            />
          </div>
          <div class="col-12">
            <label for="cliente-estado-terminado" class="form-label"
              >Cliente:</label
            >
            <input
              type="text"
              class="form-control"
              id="cliente-estado-terminado"
              disabled
            />
          </div>
          <div class="col-12">
            <label for="hora_termino" class="form-label">Hora Término:</label>
            <input
              type="time"
              class="form-control"
              id="hora_termino"
              disabled
            />
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="d-flex justify-content-end">
          <button
            type="button"
            class="btn btn-primary"
            data-bs-dismiss="modal"
            onclick="cambiarEstadoServicio(1)"
          >
            ACEPTO
          </button>
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            SALIR
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="cambiar-estado-servicio-no-se-realizo-modal"
  tabindex="-1"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5>NO SE REALIZÓ EL SERVICIO</h5>

        <div class="row g-3">
          <div class="col-12">
            <label for="servicio-estado-no-se-realizo" class="form-label"
              >Servicio:</label
            >
            <input
              type="text"
              class="form-control"
              id="servicio-estado-no-se-realizo"
              disabled
            />
          </div>
          <div class="col-12">
            <label for="cliente-estado-no-se-realizo" class="form-label"
              >Cliente:</label
            >
            <input
              type="text"
              class="form-control"
              id="cliente-estado-no-se-realizo"
              disabled
            />
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="d-flex justify-content-end">
          <button
            type="button"
            class="btn btn-primary"
            data-bs-dismiss="modal"
            onclick="cambiarEstadoServicio(4)"
          >
            ACEPTO
          </button>
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            SALIR
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-cambio-servicio"
  tabindex="-1"
  aria-labelledby="modal-cambio-servicio-label"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-cambio-servicio-label">
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
        <div class="row">
          <div class="col-12">
            <h5>Modificación de servicio</h5>
          </div>
          <div class="col-12">
            <label for="servicio-anterior-modal-cambio" class="form-label"
              >Servicio Anterior:</label
            >
            <input
              type="text"
              class="form-control"
              id="servicio-anterior-modal-cambio"
              disabled
            />
          </div>

          <div class="col-12">
            <label for="precio-anterior-modal-cambio" class="form-label"
              >Precio anterior:</label
            >
            <input
              type="text"
              class="form-control"
              id="precio-anterior-modal-cambio"
              disabled
            />
          </div>

          <div class="col-12">
            <label for="cliente-modal-cambio" class="form-label">Cliente</label>
            <input
              type="text"
              class="form-control"
              id="cliente-modal-cambio"
              disabled
            />
          </div>

          <div class="col-12">
            <label for="servicio-modal-cambio" class="form-label"
              >Nuevo Servicio:</label
            >
            <input
              class="form-control"
              list="servicio-list"
              id="servicio-modal-cambio"
              placeholder="Buscar servicio..."
              onchange="cargarPrecioServicio(event)"
            />
            <datalist id="servicio-list"> </datalist>
          </div>

          <div class="col-12">
            <label for="precio-modal-cambio" class="form-label"
              >Precio del producto:</label
            >
            <input
              type="text"
              class="form-control"
              id="precio-modal-cambio"
              disabled
            />
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-primary w-100"
            id="btn-aceptar-servicio"
            data-bs-dismiss="modal"
            onclick="cambiarServicio()"
          >
            Aceptar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiUsuariosUrl = "<?php echo URL_API_NUEVA ?>/usuarios";
  const apiDocumentosDetallesUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-detalles";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";

  let idProfesional = null;
  let datosServicio = null;

  const estados = ["ANULADO", "OK", "POR REALIZAR", "INICIO", "NO SE REALIZÓ"];

  // #region referencias a elementos del DOM
  let modalCambiarEstadoServicioInicio = null;
  let modalCambiarEstadoServicioTermino = null;
  let modalCambiarEstadoServicioNoSeRealizo = null;
  let modalCambioServicio = null;

  let tablaServiciosBody = null;

  let fechaEl = null;
  let usuarioEl = null;
  let contraseñaEl = null;
  let nombreProfesionalEl = null;

  let clienteEstadoInicioEl = null;
  let servicioEstadoInicioEl = null;
  let horaInicioEl = null;

  let clienteEstadoTerminadoEl = null;
  let servicioEstadoTerminadoEl = null;
  let horaTerminoEl = null;

  let clienteEstadoNoSeRealizoEl = null;
  let servicioEstadoNoSeRealizoEl = null;

  let servicioAnteriorModalCambioEl = null;
  let precioAnteriorModalCambioEl = null;
  let clienteModalCambioEl = null;
  let servicioModalCambioEl = null;
  let precioModalCambioEl = null;
  // #endregion

  async function wrapper() {
    // #region referencias a elementos del DOM
    modalCambiarEstadoServicioInicio = new bootstrap.Modal(
      document.getElementById("cambiar-estado-servicio-inicio-modal")
    );
    modalCambiarEstadoServicioTermino = new bootstrap.Modal(
      document.getElementById("cambiar-estado-servicio-termino-modal")
    );
    modalCambiarEstadoServicioNoSeRealizo = new bootstrap.Modal(
      document.getElementById("cambiar-estado-servicio-no-se-realizo-modal")
    );
    modalCambioServicio = new bootstrap.Modal(
      document.getElementById("modal-cambio-servicio")
    );
    tablaServiciosBody = document.getElementById("tabla-servicios").tBodies[0];

    fechaEl = document.getElementById("fecha");
    usuarioEl = document.getElementById("usuario");
    contraseñaEl = document.getElementById("contraseña");
    nombreProfesionalEl = document.getElementById("nombre_profesional");

    clienteEstadoInicioEl = document.getElementById("cliente-estado-inicio");
    servicioEstadoInicioEl = document.getElementById("servicio-estado-inicio");
    horaInicioEl = document.getElementById("hora_inicio");

    clienteEstadoTerminadoEl = document.getElementById(
      "cliente-estado-terminado"
    );
    servicioEstadoTerminadoEl = document.getElementById(
      "servicio-estado-terminado"
    );
    horaTerminoEl = document.getElementById("hora_termino");

    clienteEstadoNoSeRealizoEl = document.getElementById(
      "cliente-estado-no-se-realizo"
    );
    servicioEstadoNoSeRealizoEl = document.getElementById(
      "servicio-estado-no-se-realizo"
    );

    servicioAnteriorModalCambioEl = document.getElementById(
      "servicio-anterior-modal-cambio"
    );
    precioAnteriorModalCambioEl = document.getElementById(
      "precio-anterior-modal-cambio"
    );
    clienteModalCambioEl = document.getElementById("cliente-modal-cambio");
    servicioModalCambioEl = document.getElementById("servicio-modal-cambio");
    precioModalCambioEl = document.getElementById("precio-modal-cambio");
    // #endregion
  }

  function cargarPrecioServicio(event) {
    const servicio = event.target.value;
    // cortar el string por el guión pero desde el final
    const idProducto = servicio.split(" - ").slice(-1)[0];
    const precio = servicio.split(" - ").slice(-2)[0];

    precioModalCambioEl.value = precio;
    servicioModalCambioEl.dataset.id = idProducto;
  }

  async function buscarServicios() {
    const fecha = fechaEl.value;
    const url = `${apiDocumentosDetallesUrl}?servicios-terapista&fecha=${fecha}&id_profesional=${idProfesional}`;

    await cargarServicios(url);
  }

  async function cargarServicios(url) {
    try {
      const response = await fetch(url);
      const servicios = await response.json();

      tablaServiciosBody.innerHTML = "";

      servicios.forEach((servicio) => {
        const row = tablaServiciosBody.insertRow();

        row.dataset.id = servicio.id_documentos_detalle;
        row.dataset.paciente = servicio.paciente;
        row.dataset.producto = servicio.producto;
        row.dataset.precio = servicio.precio;

        const hora = row.insertCell();
        hora.textContent = servicio.hora;

        const producto = row.insertCell();
        producto.textContent = servicio.producto;

        const paciente = row.insertCell();
        paciente.textContent = servicio.paciente;

        const precio = row.insertCell();
        precio.textContent = formatearCantidad(servicio.precio);

        const estado = row.insertCell();
        estado.textContent = estados[servicio.estado] ?? "POR REALIZAR";

        row.dataset.bsToggle = "popover";
        row.dataset.bsTitle = "Menú";
        row.dataset.bsContent = `
          <button class="btn btn-sm text-start w-100 opciones-servicio" onclick="mostrarModalEstadoServicioInicio(event)">INICIO</button>
          <button class="btn btn-sm text-start w-100 opciones-servicio" onclick="mostrarModalEstadoServicioTermino(event)">TERMINO</button>
          <button class="btn btn-sm text-start w-100 opciones-servicio" onclick="mostrarModalCambiarServicio(event)">Cambiar Servicio</button>
          <button class="btn btn-sm text-start w-100 opciones-servicio" onclick="mostrarModalEstadoServicioNoSeRealizo(event)">No se realizó</button>`;

        row.addEventListener("click", (event) => {
          datosServicio = row.dataset;
        });

        habilitarPopover();
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar los servicios", "consultar");
    }
  }

  function mostrarModalEstadoServicioInicio() {
    clienteEstadoInicioEl.value = datosServicio.paciente;
    servicioEstadoInicioEl.value = datosServicio.producto;
    horaInicioEl.value = "<?php echo date('H:i'); ?>";

    modalCambiarEstadoServicioInicio.show();
  }

  function mostrarModalEstadoServicioTermino() {
    clienteEstadoTerminadoEl.value = datosServicio.paciente;
    servicioEstadoTerminadoEl.value = datosServicio.producto;
    horaTerminoEl.value = "<?php echo date('H:i'); ?>";

    modalCambiarEstadoServicioTermino.show();
  }

  function mostrarModalEstadoServicioNoSeRealizo() {
    clienteEstadoNoSeRealizoEl.value = datosServicio.paciente;
    servicioEstadoNoSeRealizoEl.value = datosServicio.producto;

    modalCambiarEstadoServicioNoSeRealizo.show();
  }

  function mostrarModalCambiarServicio() {
    cargarServiciosDeTerapista();

    servicioAnteriorModalCambioEl.value = datosServicio.producto;
    precioAnteriorModalCambioEl.value = formatearCantidad(datosServicio.precio);
    clienteModalCambioEl.value = datosServicio.paciente;
    servicioModalCambioEl.value = "";
    precioModalCambioEl.value = "";

    modalCambioServicio.show();
  }

  async function cargarServiciosDeTerapista() {
    const url = `${apiProductosUrl}?servicios-de-terapista&id_profesional=${idProfesional}`;

    try {
      const response = await fetch(url);
      const servicios = await response.json();

      cargarServiciosEnDatalist(servicios);
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar los servicios", "consultar");
    }
  }

  function cargarServiciosEnDatalist(servicios) {
    const datalist = document.getElementById("servicio-list");
    datalist.innerHTML = "";

    servicios.forEach((servicio) => {
      const option = document.createElement("option");
      option.value = `${servicio.nombre} - ${formatearCantidad(
        servicio.precio
      )} - ${servicio.id}`;
      datalist.appendChild(option);
    });
  }

  async function cambiarEstadoServicio(estado) {
    const idServicio = datosServicio.id;

    const estadosErrores = [
      "No se pudo anular el servicio",
      "No se pudo terminar el servicio",
      null,
      "No se pudo iniciar el servicio",
      "No se pudo cambiar el estado a 'No se realizó'",
    ];

    const url = `${apiDocumentosDetallesUrl}/${idServicio}/estado`;
    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ estado_servicio: estado }),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      if (data) {
        mostrarAlert("ok", data.mensaje, "editar");
        buscarServicios();
      } else {
        mostrarAlert("error", estadosErrores[estado], "editar");
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", estadosErrores[estado], "editar");
    }
  }

  function habilitarPopover() {
    const popoverTriggerList = document.querySelectorAll(
      '[data-bs-toggle="popover"]'
    );
    const popoverList = [...popoverTriggerList].map(
      (popoverTriggerEl) =>
        new bootstrap.Popover(popoverTriggerEl, {
          html: true,
          sanitize: false,
          placement: "bottom",
          trigger: "click focus",
          container: "body",
        })
    );

    // al hacer click afuera del popover, cerrarlo
    document.addEventListener("click", (event) => {
      const isClickInside = popoverList.some((popover) =>
        popover._element.contains(event.target)
      );

      const otrosPopovers = popoverList.filter(
        (popover) => popover._element != event.target
      );

      if (otrosPopovers) otrosPopovers.forEach((popover) => popover.hide());
    });
  }

  async function loginTerapeuta() {
    const usuario = usuarioEl.value;
    const contrasena = contraseñaEl.value;

    if (usuario == "" || contrasena == "") {
      mostrarAlert("error", "Ingrese usuario y contraseña", "login");
      return;
    }

    const url = `${apiUsuariosUrl}/login-terapeutas`;
    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ usuario, contrasena }),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();
      console.log(data);

      if (data) {
        idProfesional = data.resultado.id_profesional;
        nombreProfesionalEl.value =
          data.resultado.nombres + " " + data.resultado.apellidos;

        pasarDeLoginAServicios();
      } else {
        mostrarAlert("error", "Usuario o contraseña incorrectos", "login");
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al iniciar sesión", "login");
    }
  }

  function pasarDeLoginAServicios() {
    document.querySelector("#card-login").classList.add("d-none");
    document.querySelector("#card-servicios").classList.remove("d-none");

    buscarServicios();
  }

  async function cambiarServicio() {
    const idServicio = datosServicio.id;
    const idProducto = servicioModalCambioEl.dataset.id;
    const precio = precioModalCambioEl.value;

    const url = `${apiDocumentosDetallesUrl}/${idServicio}/servicio`;
    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id_producto: idProducto }),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      if (data) {
        mostrarAlert("ok", data.mensaje, "editar");
        buscarServicios();
      } else {
        mostrarAlert("error", "No se pudo cambiar el servicio", "editar");
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cambiar el servicio", "editar");
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
