<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); ?>

<div class="container my-5 main-cont">
  <style>
    #total-final-label {
      text-align: center;
      font-weight: bold;
    }
    #total-personas {
      text-align: center;
      font-weight: bold;
    }
    #adelanto {
      text-align: center;
      font-weight: bold;
    }
  </style>

  <div class="container mt-5">
    <form id="formulario-reservas">
      <h4>FORMULARIO REGISTRO DE RESERVA</h4>
      <div class="row">
        <div class="col-md-4">
          <label for="nro_reserva" class="form-label">Nro Reserva:</label>
          <input type="text" class="form-control" id="nro_reserva" readonly />
        </div>
        <div class="col-md-4">
          <label for="nombre" class="form-label">Nombre:</label>
          <input type="text" class="form-control" id="nombre" />
        </div>
        <div class="col-md-4">
          <label for="lugar_de_procedencia" class="form-label"
            >Lugar de Procedencia:</label
          >
          <input type="text" class="form-control" id="lugar_de_procedencia" />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="id_modalidad" class="form-label"
            >Modalidad del Cliente:</label
          >
          <select class="form-select" name="id_modalidad" id="id_modalidad">
            <option value="0">--Seleccione--</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="fecha_llegada" class="form-label"
            >Fecha de Llegada:</label
          >
          <input
            type="date"
            class="form-control"
            id="fecha_llegada"
            value="<?php echo date('Y-m-d'); ?>"
            onchange="alCambiarFechaLlegada(event)"
          />
        </div>
        <div class="col-md-4">
          <label for="hora_llegada" class="form-label">Hora de Llegada:</label>
          <input
            type="time"
            class="form-control"
            id="hora_llegada"
            value="<?php echo date('H:i'); ?>"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="tipo_transporte" class="form-label"
            >Tipo Transporte:</label
          >
          <select
            class="form-select"
            name="tipo_transporte"
            id="tipo_transporte"
          >
            <option value="0">--Seleccione--</option>
            <option value="AUTO">AUTO</option>
            <option value="BUS">BUS</option>
            <option value="AVION">AVION</option>
            <option value="TREN">TREN</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="telefono" class="form-label">Celular:</label>
          <input type="text" class="form-control" id="telefono" />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="observaciones_hospedaje" class="form-label"
            >Observaciones Referente al Hospedaje:</label
          >
          <input
            type="text"
            class="form-control"
            id="observaciones_hospedaje"
          />
        </div>
        <div class="col-md-4">
          <label for="observaciones_pago" class="form-label"
            >Observaciones Referente al Pago:</label
          >
          <input type="text" class="form-control" id="observaciones_pago" />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="provincia" class="form-label">Nro Adultos:</label>
          <input
            type="text"
            class="form-control"
            id="nro_adultos"
            value="1"
            required
          />
        </div>
        <div class="col-md-4">
          <label for="telefono" class="form-label">Nro de Niños:</label>
          <input type="text" class="form-control" id="nro_niños" value="0" />
        </div>
        <div class="col-md-4">
          <label for="celular" class="form-label">Nro de Infantes:</label>
          <input type="text" class="form-control" id="nro_infantes" value="0" />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="fecha_ingreso" class="form-label">Fecha Ingreso:</label>
          <input
            type="date"
            class="form-control"
            id="fecha_ingreso"
            value="<?php echo date('Y-m-d'); ?>"
            onchange="alCambiarFechaIngreso(event)"
          />
        </div>
        <div class="col-md-4">
          <label for="fecha_salida" class="form-label">Fecha Salida:</label>
          <input
            type="date"
            class="form-control"
            id="fecha_salida"
            value="<?php echo date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 day')); ?>"
            onchange="alCambiarFechaSalida(event)"
          />
        </div>
      </div>
    </form>

    <div class="row">
      <div class="col-sm-12 my-3 mb-sm-0">
        <div class="card">
          <div class="card-body">
            <h3 class="mb-4">Preparación de Habitaciones:</h3>
            <form id="formulario-carrito" class="mb-3">
              <div class="row">
                <div class="col-md-2">
                  <label for="tipo">Nro Habitacion</label>
                  <select class="form-select" name="habitacion" id="habitacion">
                    <option value="0">--Seleccione--</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="habitacion">Tipo Habitacion:</label>
                  <select class="form-select" name="tipo" id="tipo">
                    <option value="0">-Seleccione-</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="tipo">Tipo Precios</label>
                  <select class="form-select" id="selectPrecios">
                    <option value="precio_venta_01">Precio Normal</option>
                    <option value="precio_venta_02">Precio Coorporativo</option>
                    <option value="precio_venta_03">
                      Precio Cliente Premiun
                    </option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="personas">Personas:</label>
                  <input
                    type="number"
                    id="personas"
                    class="form-control"
                    value="1"
                    required
                  />
                </div>
                <div class="col-md-2">
                  <label for="monto">Monto:</label>
                  <input
                    type="number"
                    step="0.01"
                    id="monto"
                    class="form-control"
                    required
                  />
                </div>
                <div class="col-md-2">
                  <label for="noches">Noches:</label>
                  <input
                    type="number"
                    id="noches"
                    class="form-control"
                    readonly
                  />
                </div>

                <div class="col-md-4">
                  <button
                    type="button"
                    class="btn btn-primary mt-3"
                    onclick="agregarHabitacion()"
                  >
                    <Strong>+</Strong>Agregar Habitacion
                  </button>
                </div>
              </div>
            </form>

            <table id="carrito" class="table table-striped">
              <thead>
                <tr>
                  <th>TIPO</th>
                  <th>Nro.HABITACION</th>
                  <th>PERSONAS</th>
                  <th>MONTO</th>
                  <th>NOCHES</th>
                  <th>TOTAL</th>
                  <th>ACCION</th>
                </tr>
              </thead>
              <tbody id="carrito-body"></tbody>
            </table>
            <div class="col-md-4">
              <input
                type="hidden"
                id="lista-habitaciones"
                class="form-control"
                readonly
              />
            </div>
            <div class="col-md-4">
              <label for="habitacion">Total Personas:</label>
              <input
                type="text"
                id="total-personas"
                class="form-control"
                disabled
              />
            </div>
            <div class="col-md-4">
              <label for="habitacion">Total Final: S/</label>
              <input
                type="text"
                id="total-final-label"
                class="form-control"
                readonly
              />
              <input type="hidden" id="total2" class="form-control" readonly />
            </div>
            <hr />
            <div class="col-md-4">
              <label for="nombre" class="form-label">Porcentaje:</label>
              <input type="number" class="form-control" id="porcentaje" />
            </div>
            <div class="col-md-4">
              <label for="lugar_de_procedencia" class="form-label"
                >Adelanto:</label
              >
              <input type="text" class="form-control" id="adelanto" readonly />
              <input
                type="hidden"
                class="form-control"
                id="adelanto2"
                readonly
              />
            </div>
          </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button
            type="button"
            class="btn btn-primary mt-3"
            onclick="registrarReserva()"
          >
            Confirmar Reserva
          </button>
        </div>
      </div>
    </div>
    <div class="col-sm-6"></div>
  </div>
</div>

<script>
  // #region Constantes y variables globales

  const apiModalidadesUrl = "<?php echo URL_API_NUEVA ?>/modalidades-cliente";
  const apiConfigUrl = "<?php echo URL_API_NUEVA ?>/config";
  const apiHabitacionesUrl = "<?php echo URL_API_NUEVA ?>/habitaciones";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiReservasUrl = "<?php echo URL_API_NUEVA ?>/reservas";

  const unDia = 24 * 60 * 60 * 1000;

  let nombreEl = null;
  let lugarProcedenciaEl = null;
  let modalidadesEl = null;
  let tipoTransporteEl = null;
  let telefonoEl = null;

  let fechaLlegadaEl = null;
  let fechaIngresoEl = null;
  let fechaSalidaEl = null;
  let adelantoEl = null;
  let porcentajeEl = null;

  let observacionesHospedajeEl = null;
  let observacionesPagoEl = null;

  let nroAdultosEl = null;
  let nroNiñosEl = null;
  let nroInfantesEl = null;

  let habitacionesEl = null;
  let tiposHabitacionEl = null;
  let tiposPrecioEl = null;
  let personasEl = null;
  let montoEl = null;
  let nochesEl = null;

  let totalPersonasEl = null;
  let totalFinalEl = null;

  let tablaCarritoBody = null;

  let nroReservaEl = null;

  // #endregion

  let hospedajesCargados = [];
  let habitacionesCargadas = [];

  let adelanto = null;
  let carritoItems = [];

  async function wrapper() {
    nombreEl = document.getElementById("nombre");
    lugarProcedenciaEl = document.getElementById("lugar_de_procedencia");
    tipoTransporteEl = document.getElementById("tipo_transporte");
    telefonoEl = document.getElementById("telefono");

    fechaLlegadaEl = document.getElementById("fecha_llegada");
    fechaIngresoEl = document.getElementById("fecha_ingreso");
    fechaSalidaEl = document.getElementById("fecha_salida");

    adelantoEl = document.getElementById("adelanto");
    porcentajeEl = document.getElementById("porcentaje");
    modalidadesEl = document.getElementById("id_modalidad");

    observacionesHospedajeEl = document.getElementById(
      "observaciones_hospedaje"
    );
    observacionesPagoEl = document.getElementById("observaciones_pago");

    nroAdultosEl = document.getElementById("nro_adultos");
    nroNiñosEl = document.getElementById("nro_niños");
    nroInfantesEl = document.getElementById("nro_infantes");

    habitacionesEl = document.getElementById("habitacion");
    tiposHabitacionEl = document.getElementById("tipo");
    tiposPrecioEl = document.getElementById("selectPrecios");
    personasEl = document.getElementById("personas");
    montoEl = document.getElementById("monto");
    nochesEl = document.getElementById("noches");

    totalPersonasEl = document.getElementById("total-personas");
    totalFinalEl = document.getElementById("total-final-label");

    tablaCarritoBody = document.getElementById("carrito").tBodies[0];

    nroReservaEl = document.getElementById("nro_reserva");

    actualizarNroNoches();

    cargarModalidades();
    cargarCodigoReserva();
    await cargarHabitaciones();
    await cargarTiposHabitacion();

    prepararModalidades();
    prepararTiposPrecio();
    prepararPorcentajeAdelanto();

    actualizarCarrito();

    prepararTiposHabitacion();
    prepararHabitaciones();
  }

  function prepararModalidades() {
    modalidadesEl.addEventListener("change", (event) => {
      montoEl.disabled = event.target.value == 1 || event.target.value == 3;

      carritoItems = [];
      actualizarCarrito();
      habitacionesEl.selectedIndex = 0;
    });
  }

  function prepararTiposPrecio() {
    tiposPrecioEl.addEventListener("change", (event) => {
      const tipoPrecioSeleccionado = event.target.value;
      const precioSeleccionado = hospedajesCargados.find(
        (hospedaje) => hospedaje.id_producto == tiposHabitacionEl.value
      )[tipoPrecioSeleccionado];
      montoEl.value = precioSeleccionado;
    });
  }

  function prepararPorcentajeAdelanto() {
    porcentajeEl.addEventListener("change", (event) => {
      const porcentaje = event.target.value;
      const { totalFinal } = actualizarTotales();

      const adelanto = (porcentaje / 100) * totalFinal;
      adelantoEl.value = formatearCantidad(adelanto);
    });
  }

  function prepararTiposHabitacion() {
    tiposHabitacionEl.addEventListener("change", async (event) => {
      const tipoHabitacionSeleccionada = event.target.value;
      tiposPrecioEl.dispatchEvent(new Event("change"));
    });
  }

  function prepararHabitaciones() {
    habitacionesEl.addEventListener("change", async (event) => {
      const nroHabitacionSeleccionada = event.target.value;

      tiposHabitacionEl.value = habitacionesCargadas.find(
        (habitacion) => habitacion.nro_habitacion == nroHabitacionSeleccionada
      ).id_producto;
      tiposHabitacionEl.dispatchEvent(new Event("change"));
    });
  }

  async function cargarCodigoReserva() {
    const url = `${apiConfigUrl}/2/codigo`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      nroReservaEl.value = data.codigo;
    } catch (error) {
      console.error(error);
    }
  }

  async function cargarHabitaciones() {
    const url = `${apiHabitacionesUrl}?con-disponibilidad&fecha_ingreso=${fechaIngresoEl.value}&fecha_salida=${fechaSalidaEl.value}`;
    
    try {
      const response = await fetch(url);
      const data = await response.json();

      habitacionesCargadas = data;

      habitacionesEl.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.innerText = "--Seleccione--";
      habitacionesEl.appendChild(defaultOption);

      data.forEach((item) => {
        const option = document.createElement("option");
        option.value = item.nro_habitacion;
        option.innerText = item.nro_habitacion;
        habitacionesEl.appendChild(option);
      });
    } catch (error) {
      console.error(error);
    }
  }

  async function cargarTiposHabitacion() {
    const url = `${apiProductosUrl}?hospedajes`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      hospedajesCargados = data;
      console.log(hospedajesCargados);

      tiposHabitacionEl.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.innerText = "--Seleccione--";
      tiposHabitacionEl.appendChild(defaultOption);

      data.forEach((item) => {
        const option = document.createElement("option");
        option.value = item.id_producto;
        option.innerText = item.nombre_producto;
        tiposHabitacionEl.appendChild(option);
      });
    } catch (error) {
      console.error(error);
    }
  }

  async function cargarModalidades() {
    try {
      const response = await fetch(apiModalidadesUrl);
      const data = await response.json();

      data.forEach((item) => {
        const option = document.createElement("option");
        option.value = item.id_modalidad;
        option.textContent = item.nombre_modalidad;
        modalidadesEl.appendChild(option);
      });
    } catch (error) {
      console.error(error);
    }
  }

  // #region Funciones para el cálculo de las noches y cambio de fechas

  function alCambiarFechaLlegada(event) {
    let fechaLlegada = new Date(event.target.value);
    let fechaIngreso = new Date(fechaIngresoEl.value);
    let fechaSalida = new Date(fechaSalidaEl.value);

    fechaIngreso = fechaLlegada;
    fechaSalida = new Date(fechaIngreso.getTime() + unDia);

    fechaIngresoEl.valueAsDate = fechaIngreso;
    fechaSalidaEl.valueAsDate = fechaSalida;

    actualizarNroNoches();
    cargarHabitaciones();
  }

  function alCambiarFechaIngreso(event) {
    let fechaIngreso = new Date(event.target.value);
    let fechaSalida = new Date(fechaSalidaEl.value);
    let fechaLlegada = new Date(fechaLlegadaEl.value);

    if (fechaIngreso < fechaLlegada) {
      fechaLlegada = fechaIngreso;
    }

    fechaSalida = new Date(fechaIngreso.getTime() + unDia);

    fechaSalidaEl.valueAsDate = fechaSalida;
    fechaLlegadaEl.valueAsDate = fechaLlegada;

    actualizarNroNoches();
    cargarHabitaciones();
  }

  function alCambiarFechaSalida(event) {
    let fechaSalida = new Date(event.target.value);
    let fechaIngreso = new Date(fechaIngresoEl.value);
    let fechaLlegada = new Date(fechaLlegadaEl.value);

    if (fechaIngreso >= fechaSalida) {
      fechaSalida = new Date(fechaIngreso.getTime() + unDia);
    }

    fechaSalidaEl.valueAsDate = fechaSalida;

    actualizarNroNoches();
    cargarHabitaciones();
  }

  function actualizarNroNoches() {
    const fechaIngreso = new Date(fechaIngresoEl.value);
    const fechaSalida = new Date(fechaSalidaEl.value);

    const diferenciaEnMs = fechaSalida - fechaIngreso;
    const numNoches = Math.round(diferenciaEnMs / unDia);

    document.getElementById("noches").value = numNoches;
  }

  // #endregion

  function agregarHabitacion() {
    const nuevoItem = {
      id_producto: tiposHabitacionEl.value,
      nro_habitacion: habitacionesEl.value,
      nro_personas: personasEl.value,
      precio_unitario: montoEl.value,
      nro_noches: nochesEl.value,
    };

    carritoItems.push(nuevoItem);
    actualizarCarrito();
    porcentajeEl.dispatchEvent(new Event("change"));
  }

  function actualizarCarrito() {
    tablaCarritoBody.innerHTML = "";

    carritoItems.forEach((item, index) => {
      const row = tablaCarritoBody.insertRow();

      const tipoCell = row.insertCell();
      tipoCell.textContent = item.id_producto;

      const habitacionCell = row.insertCell();
      habitacionCell.textContent = item.nro_habitacion;

      const personasCell = row.insertCell();
      personasCell.textContent = item.nro_personas;

      const montoCell = row.insertCell();
      montoCell.textContent = formatearCantidad(item.precio_unitario);

      const nochesCell = row.insertCell();
      nochesCell.textContent = item.nro_noches;

      const totalCell = row.insertCell();
      totalCell.textContent = (item.precio_unitario * item.nro_noches).toFixed(2);

      const eliminarCell = row.insertCell();

      eliminarCell.innerHTML = `
        <button class="btn btn-danger" onclick="eliminarProducto(${index})">
          Eliminar
        </button>
      `;
    });

    actualizarTotales();
  }

  function actualizarTotales() {
    const totalPersonas = carritoItems.reduce(
      (total, item) => total + +item.nro_personas,
      0
    );
    const totalFinal = carritoItems.reduce(
      (total, item) => total + item.precio_unitario * item.nro_noches,
      0
    );

    document.getElementById("total-personas").value = totalPersonas;
    document.getElementById("total-final-label").value =
      formatearCantidad(totalFinal);

    return {
      totalPersonas,
      totalFinal,
    };
  }

  function eliminarProducto(index) {
    carritoItems.splice(index, 1);
    actualizarCarrito();    
    porcentajeEl.dispatchEvent(new Event("change"));
  }

  async function registrarReserva() {
    const reserva = {
      nombre: nombreEl.value,
      lugar_procedencia: lugarProcedenciaEl.value,
      id_modalidad: modalidadesEl.value,

      fecha_llegada: fechaLlegadaEl.value,
      hora_llegada: hora_llegada.value,
      fecha_salida: fechaSalidaEl.value,

      tipo_transporte: tipoTransporteEl.value,
      telefono: telefonoEl.value,

      observaciones_hospedaje: observacionesHospedajeEl.value,
      observaciones_pago: observacionesPagoEl.value,

      nro_adultos: nroAdultosEl.value,
      nro_ninos: nroNiñosEl.value,
      nro_infantes: nroInfantesEl.value,

      porcentaje_pago: porcentajeEl.value,
    };

    const reservaHabitaciones = {
      reserva,
      habitaciones: carritoItems,
    };

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(reservaHabitaciones),
    };

    try {
      const response = await fetch(apiReservasUrl, options);
      const data = await response.json();

      console.log(data);
      open("../listado-reserva", "_self");
    } catch (error) {
      console.error(error);
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
