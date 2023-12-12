<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); ?>

<div class="container my-5 main-cont">
  <div class="card">
    <div class="card-body">
      <h3 class="mb-4">LISTADO DE ROOMIN - REGISTRO DE HUESPEDES</h3>
      <div class="table-responsive">
        <div class="col-md-4">
          <label for="validationCustom02" class="form-label"
            >Buscar por fecha:</label
          >
          <div class="input-group mb-3">
            <input
              type="date"
              class="form-control"
              id="fecha_busqueda"
              onchange="buscarPorFecha()"
              value="<?php echo date('Y-m-d'); ?>"
            />

            <button
              class="btn btn-success"
              type="button"
              onclick="buscarPorFecha()"
            >
              Buscar
            </button>
          </div>
        </div>
        <div class="table-responsive">
          <table id="rooming" class="table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Nro Habitacion</th>
                <th style="min-width: 300px">Cliente</th>
                <th>Nro Personas</th>
                <th>Fecha Llegada</th>
                <th>Fecha Salida</th>
                <th>F. Huesped</th>
                <th>Mantenimientos</th>
                <th>Colaborador</th>
                <th style="min-width: 300px">Observaciones</th>
                <th>Nro. Reg. Maestro</th>
                <th>Nro Reserva</th>
              </tr>
            </thead>
            <tbody id="pendiente-pago"></tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-auto">
          <button
            class="btn btn-outline-secondary"
            onclick="imprimirDesayunos()"
          >
            PROG. DESAYUNOS
          </button>
        </div>
        <div class="col-md-auto">
          <button
            class="btn btn-outline-secondary"
            onclick="imprimirDesayunos()"
          >
            PROG. MANTENIMIENTOS
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal modal fade"
  id="modal-cambio-habitacion"
  tabindex="-1"
  aria-labelledby="modal-cambio-habitacion-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-cambio-habitacion-label">
          Cambio de habitación
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-cambio-habitacion"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row mt-3">
          <div class="col-md-6 mb-3">
            <label for="nro_registro_maestro">Nro de Registro Maestro:</label>
            <input
              type="text"
              class="form-control"
              id="nro_registro_maestro"
              disabled
            />
          </div>
          <div class="col-md-6 mb-3">
            <label for="nombre_cliente">Nombre del cliente:</label>
            <input
              type="text"
              class="form-control"
              id="nombre_cliente"
              disabled
            />
          </div>
          <div class="col-md-12 mb-3 d-flex align-items-center">
            <div class="col-auto d-flex align-items-center">
              <span>Desde:</span>
            </div>
            <div class="col-auto mx-2">
              <input type="date" class="form-control" id="fecha_in" disabled />
            </div>
            <div class="col-auto d-flex align-items-center">hasta el:</div>
            <div class="col-auto mx-2">
              <input type="date" class="form-control" id="fecha_out" disabled />
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="nro_habitacion_anterior"
              >Nro de habitación anterior:</label
            >
            <input
              type="text"
              class="form-control"
              id="nro_habitacion_anterior"
              disabled
            />
          </div>
          <div class="col-md-6 mb-3">
            <label for="nro_habitacion">Nro de habitación a cambiar:</label>
            <select class="form-select" id="nro_habitacion"></select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-md-6">
          <div class="d-flex justify-content-end">
            <button
              type="button"
              class="btn btn-primary me-2"
              id="cambiar-habitacion"
              onclick="cambiarHabitacion()"
            >
              Aceptar
            </button>
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Salir
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal modal-sm fade"
  id="modal-checkout"
  tabindex="-1"
  aria-labelledby="modal-checkout-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title mb-3" id="modal-checkout-label">
          ¿Realmente desea hacer CHECKOUT?
        </h5>
        <div class="row">
          <div class="col-md-6">
            <button
              type="button"
              class="btn btn-danger"
              id="confirmar-checkout"
              data-bs-dismiss="modal"
              onclick="checkout()"
            >
              CHECKOUT
            </button>
          </div>
          <div class="col-md-6">
            <button
              type="button"
              class="btn btn-outline-secondary w-100 h-100"
              data-bs-dismiss="modal"
            >
              Salir
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiRoomingUrl = "<?php echo URL_API_NUEVA ?>/rooming";
  const apiCheckingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";
  const apiHabitacionesUrl = "<?php echo URL_API_NUEVA ?>/habitaciones";
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";

  let fechaBusqueda = null;
  let selectHabitaciones = null;

  let nroHabitacionEl = null;
  let nroRegistroMaestroEl = null;
  let nombreClienteEl = null;
  let fechaInEl = null;
  let fechaOutEl = null;

  let tablaRooming = null;
  let modalCambioHabitacion = null;

  let datosCheckin = null;

  let modalCheckout = null;

  function wrapper() {
    fechaBusqueda = document.getElementById("fecha_busqueda");
    selectHabitaciones = document.getElementById("nro_habitacion");

    nroHabitacionEl = document.getElementById("nro_habitacion_anterior");
    nroRegistroMaestroEl = document.getElementById("nro_registro_maestro");
    nombreClienteEl = document.getElementById("nombre_cliente");
    fechaInEl = document.getElementById("fecha_in");
    fechaOutEl = document.getElementById("fecha_out");

    tablaRooming = document.getElementById("rooming").tBodies[0];
    modalCambioHabitacion = new bootstrap.Modal(
      document.getElementById("modal-cambio-habitacion")
    );

    modalCheckout = new bootstrap.Modal(
      document.getElementById("modal-checkout")
    );

    buscarPorFecha();
  }

  function imprimirDesayunos() {
    const url = `${apiReportesUrl}?tipo=desayunos&fecha=${fechaBusqueda.value}`;
    window.open(url, "_blank");
  }

  async function cambiarHabitacion() {
    const nroHabitacion = selectHabitaciones.value;

    const data = {
      nro_habitacion: nroHabitacion,
      prev_nro_habitacion: datosCheckin.nro_habitacion,
      fecha: fechaBusqueda.value,
    };

    const url = `${apiCheckingsUrl}/${datosCheckin.id}/habitacion`;
    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      console.log(data);

      modalCambioHabitacion.hide();
      buscarPorFecha();
    } catch (error) {
      console.error(error);
    }
  }

  async function cargarHabitaciones() {
    const url = `${apiHabitacionesUrl}?con-disponibilidad&fecha=${fechaBusqueda.value}`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      selectHabitaciones.innerHTML = "";

      data.forEach((item) => {
        const option = document.createElement("option");
        option.value = item.nro_habitacion;
        option.innerText = item.nro_habitacion;
        selectHabitaciones.appendChild(option);
      });
    } catch (error) {
      console.error(error);
    }

    modalCambioHabitacion.show();
    buscarPorFecha();
  }

  async function buscarPorFecha() {
    const url = `${apiRoomingUrl}?con-datos&fecha=${fechaBusqueda.value}`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      console.log(data);

      // agrupar por nro_habitacion
      let habitaciones = data.reduce((acc, item) => {
        const nroHabitacion = item.nro_habitacion;

        if (!acc[nroHabitacion]) {
          acc[nroHabitacion] = [];
        }

        acc[nroHabitacion].push(item);

        return acc;
      }, {});

      // elegir el checkin (el que tiene nro_registro_maestro) antes que la reserva (el que tiene nro_reserva pero no nro_registro_maestro) y finalmente el que no tiene nro_registro_maestro ni nro_reserva
      habitaciones = Object.values(habitaciones);
      habitaciones.forEach((item) => {
        item.sort((a, b) => {
          if (a.nro_registro_maestro && !b.nro_registro_maestro) {
            return -1;
          }
          if (!a.nro_registro_maestro && b.nro_registro_maestro) {
            return 1;
          }

          if (a.nro_reserva && !b.nro_reserva) {
            return -1;
          }
          if (!a.nro_reserva && b.nro_reserva) {
            return 1;
          }

          return 0;
        });
      });

      habitaciones = Object.values(habitaciones);

      // ordenar de modo que si el registro está de salida y hay otro registro que es reserva o está ocupado, el registro de salida quede al final
      habitaciones.forEach((item) => {
        item.sort((a, b) => {
          if (a.de_salida && !b.reservado && !b.ocupado) {
            return -1; // Muestra a como prioritario si es de salida y b no está reservada ni ocupada
          } else if (b.de_salida && !a.reservado && !a.ocupado) {
            return 1; // Muestra b como prioritario si es de salida y a no está reservada ni ocupada
          } else {
            return 0; // No hay preferencia, deja el orden como está
          }
        });
      });

      // borrar el elemento que tenga reservado_pero_ocupado
      habitaciones.forEach((item) => {
        const index = item.findIndex(
          (element) => element.reservado_pero_ocupado
        );

        if (index > -1) {
          item.splice(index, 1);
        }
      });

      // elegir el primer registro de cada habitacion
      habitaciones = habitaciones.map((item) => item[0]);

      habitaciones = Object.values(habitaciones).flat();

      tablaRooming.innerHTML = "";
      habitaciones.forEach((item) => {
        const row = tablaRooming.insertRow();

        const fechaActual = new Date().toISOString().split("T")[0];
        const fechaSeleccionadaEsFuturaUHoy =
          fechaBusqueda.value >= fechaActual;

        row.dataset.id = item.id_checkin;
        row.dataset.nro_habitacion = item.nro_habitacion;
        row.dataset.nro_registro_maestro = item.nro_registro_maestro;
        row.dataset.nombre_cliente = item.nombre;
        row.dataset.fecha_out = item.fecha_out;

        const estaOculto =
          item.estado_pago == 3 ||
          item.estado_pago == 5 ||
          item.estado_pago == null;

        if (!estaOculto) {
          row.classList.add(
            item.de_salida
              ? "de_salida"
              : item.ocupado
              ? "ocupado"
              : item.reservado
              ? "reservado"
              : "libre"
          );
        }

        const estadosCheckingColores = {
          "S/DATOS": "success",
          ESCANEO: "primary",
          "EN PROCESO": "warning",
          GUARDADO: "secondary",
          "VISTO BUENO TERMINADO": "info",
        };

        row.innerHTML = `
          <td class="text-center">${item.abreviatura_producto || ""}</td>
          <td class="text-center">${item.nro_habitacion || ""}</td>
          <td>${estaOculto ? "" : item.nombre ?? ""}</td>
          <td class="text-center">${
            estaOculto ? "" : item.nro_personas ?? ""
          }</td>
          <td>${estaOculto ? "" : formatearFecha(item.fecha_in) ?? ""}</td>
          <td>${estaOculto ? "" : formatearFecha(item.fecha_out) ?? ""}</td>
          <td>
            ${
              !estaOculto &&
              fechaSeleccionadaEsFuturaUHoy &&
              item.nro_registro_maestro &&
              item.estado != "OU"
                ? `<span class="badge rounded-pill text-bg-${
                    estadosCheckingColores[item.estado_cheking]
                  }">${
                    item.estado_cheking == "VISTO BUENO TERMINADO"
                      ? '<i class="fa-solid fa-check"></i>'
                      : item.estado_cheking
                  }</span>`
                : ""
            }
          </td>
          <td>
            <select class="form-select tipo" id="mantenimiento">
              <option value="1">ASEO HABITACIÓN - 30</option>
              <option value="2">MANT. PROFUNDA - 40</option>
              <option value="3">MANT. PROFUNDA - 45</option>
              <option value="4">REPASO - 10</option>
              <option value="5">REPARACIÓN</option>
            </select>
          </td>
          <td>
            <select class="form-select tipo" id="colaborador">
              <option value="1">JENNIFER</option>
              <option value="2">TANIA</option>
              <option value="3">AMELIA</option>
              <option value="4">ELVIS</option>
              <option value="5">VICTOR</option>
            </select>
          </td>
          <td style="width: 400px;">
            <input type="text" class="form-control" id="observaciones" />
          </td>
          <td>${estaOculto ? "" : item.nro_registro_maestro ?? ""}</td>
          <td>${estaOculto ? "" : item.nro_reserva ?? ""}</td>
      `;

        if (
          !estaOculto &&
          fechaSeleccionadaEsFuturaUHoy &&
          item.nro_registro_maestro &&
          item.estado != "OU"
        ) {
          row.dataset.bsToggle = "popover";
          row.dataset.bsTitle = "Opciones - Habitación " + item.nro_habitacion;
          row.dataset.bsContent = `
          <p class="mb-2">Funciones:</p>

          <a href="../gestionar-checkin-hotel?id_checkin=${item.id_checkin}&nro_habitacion=${item.nro_habitacion}" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;">EDITAR</a>

          <button id="cambiar-habitacion" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;" onclick="prepararCambiarHabitacion()">CAMBIAR HAB</button>
          
          <button id="checkout" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;" onclick="mostrarModalCheckout()">CHECKOUT</button>

          <button id="imprimir-ficha" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;" onclick="imprimirFicha()">IMPRIMIR FICHA</button>

          <p class="mb-2 mt-3">Cambiar estado checking:</p>
          
          <button id="creado" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;" onclick="cambiarEstado(event)">S/DATOS</button>
          <button id="scaneado" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;" onclick="cambiarEstado(event)">ESCANEO</button>
          <button id="en-proceso" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;" onclick="cambiarEstado(event)">EN PROCESO</button>
          <button id="guardado" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;" onclick="cambiarEstado(event)">GUARDADO</button>
          <button id="visto-bueno-terminado" class="btn btn-sm text-start opciones-rooming" style="width: 90%; margin-left: 10%;" onclick="cambiarEstado(event)">VISTO BUENO TERMINADO</button>`;

          row.addEventListener("click", (event) => {
            datosCheckin = row.dataset;
          });
        }
      });

      habilitarPopover();
    } catch (error) {
      console.error(error);
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

  async function cambiarEstado(event) {
    const idCheckin = datosCheckin.id;
    const accion = event.target.id;

    const accionesMetodos = {
      creado: "estado-por-defecto",
      scaneado: "activar-escaneo",
      "en-proceso": "en-progreso",
      guardado: "guardar",
      "visto-bueno-terminado": "visto-bueno-terminado",
    };

    const url = `${apiCheckingsUrl}/${idCheckin}/${accionesMetodos[accion]}`;
    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      console.log(data);

      buscarPorFecha();
    } catch (error) {
      console.error(error);
    }
  }

  function mostrarModalCheckout(event) {
    const modalCheckoutEl = document.getElementById("modal-checkout");
    const idCheckin = datosCheckin.id;
    const nroHabitacion = datosCheckin.nro_habitacion;

    modalCheckoutEl.dataset.id = idCheckin;
    modalCheckoutEl.dataset.nro_habitacion = nroHabitacion;

    modalCheckout.show();
  }

  async function checkout() {
    const modalCheckoutEl = document.getElementById("modal-checkout");
    const idCheckin = modalCheckoutEl.dataset.id;
    const nroHabitacion = modalCheckoutEl.dataset.nro_habitacion;
    const fecha = fechaBusqueda.value;

    const url = `${apiCheckingsUrl}/${idCheckin}/checkout`;
    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        nro_habitacion: nroHabitacion,
        fecha_checkout: fecha,
      }),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      console.log(data);

      modalCheckout.hide();
      buscarPorFecha();
    } catch (error) {
      console.error(error);
    }
  }

  function prepararCambiarHabitacion() {
    nroHabitacionEl.value = datosCheckin.nro_habitacion;
    nroRegistroMaestroEl.value = datosCheckin.nro_registro_maestro;
    nombreClienteEl.value = datosCheckin.nombre_cliente;
    fechaInEl.value = fechaBusqueda.value;
    fechaOutEl.value = datosCheckin.fecha_out;

    cargarHabitaciones();
    modalCambioHabitacion.show();
  }

  function imprimirFicha() {
    const nroRegistroMaestro = datosCheckin.nro_registro_maestro;
    const url = `${apiReportesUrl}?tipo=ficha-checkin&nro_registro_maestro=${nroRegistroMaestro}`;
    window.open(url, "_blank");
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
