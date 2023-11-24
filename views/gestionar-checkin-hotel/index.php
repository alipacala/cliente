<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); ?>

<div class="container my-5 main-cont">
  <div class="card w-100 mb-3">
    <div class="card-body">
      <h1>Formulario Huespedes y Acompañantes</h1>
      <form class="row g-3 needs-validation" id="form-checking">
        <input
          type="hidden"
          class="form-control"
          name="id_persona"
          id="id_persona"
          readonly
        />
        <input
          type="hidden"
          class="form-control"
          name="id_checkin"
          id="id_checkin"
          readonly
        />
        <div class="col-md-6">
          <label for="nro_registro" class="form-label">Nro. Registro:</label>
          <input type="text" class="form-control" id="nro_registro" readonly />
        </div>
        <div class="col-md-6">
          <label for="nro_reserva" class="form-label">Nro. Reserva:</label>
          <input type="text" class="form-control" id="nro_reserva" readonly />
        </div>
        <div class="col-md-4">
          <label for="tipo_documento" class="form-label">Tipo Documento:</label>
          <select class="form-select" id="tipo_documento">
            <option value="0">--Seleccione--</option>
            <option value="1">DNI</option>
            <option value="2">PASAPORTE</option>
            <option value="3">CEDULA DE IDENTIFICACION</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="nro_documento" class="form-label">Buscar DNI</label>
          <div class="input-group">
            <span class="input-group-text">Nro DNI</span>
            <input
              type="text"
              class="form-control"
              id="nro_documento"
              required
            />
            <button type="button" class="btn btn-info" onclick="Buscar()">
              Buscar
            </button>
          </div>
        </div>
        <div class="col-md-2">
          <label for="apellido_paterno" class="form-label"
            >Apellido paterno:</label
          >
          <input
            type="text"
            class="form-control"
            id="apellido_paterno"
            required
          />
        </div>
        <div class="col-md-2">
          <label for="apellido_materno" class="form-label"
            >Apellido materno:</label
          >
          <input
            type="text"
            class="form-control"
            id="apellido_materno"
            required
          />
        </div>
        <div class="col-md-4">
          <label for="nombres" class="form-label">Nombres:</label>
          <input type="text" class="form-control" id="nombres" required />
        </div>
        <div class="col-md-4">
          <label for="lugar_de_nacimiento" class="form-label"
            >Lugar de Nacimiento:</label
          >
          <input
            type="text"
            class="form-control"
            id="lugar_de_nacimiento"
            required
          />
        </div>
        <div class="col-md-4">
          <label for="fecha_nacimiento" class="form-label"
            >Fecha_Nacimiento:</label
          >
          <input
            type="date"
            class="form-control"
            id="fecha_nacimiento"
            required
          />
        </div>
        <div class="col-md-4">
          <label for="edad" class="form-label">Edad:</label>
          <input type="number" class="form-control" id="edad" required />
        </div>
        <div class="col-md-4">
          <label for="sexo" class="form-label">Sexo:</label>
          <select class="form-select" id="sexo">
            <option value="0">--Seleccione--</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
            <option value="O">Otros</option>
          </select>
        </div>
        <div class="col-md-12">
          <label for="ocupacion">Ocupacion:</label>
          <textarea
            name="ocupacion"
            id="ocupacion"
            class="form-control"
          ></textarea>
        </div>
        <div class="col-md-6">
          <label for="direccion">Direccion:</label>
          <input
            type="text"
            class="form-control"
            id="direccion"
            name="direccion"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="ciudad">Ciudad:</label>
          <input
            type="text"
            class="form-control"
            id="ciudad"
            name="ciudad"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="celular">Celular:</label>
          <input
            type="text"
            class="form-control"
            id="celular"
            name="celular"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="email">Email:</label>
          <input type="text" class="form-control" id="email" name="email" />
        </div>
        <div class="col-md-12">
          <label for="estacionamiento" class="form-label"
            >Requiere Estacionamiento No/Si</label
          >
          <select class="form-select" id="estacionamiento">
            <option value="0">No</option>
            <option value="1">Sí</option>
          </select>
        </div>
        <div class="col-md-12">
          <label for="nro_placa">Nro. Placa:</label>
          <input
            type="text"
            class="form-control"
            id="nro_placa"
            name="nro_placa"
          />
        </div>
        <div class="col-md-3">
          <label for="habitacion">Nro Habitacion</label>
          <select
            class="form-select"
            name="habitacion"
            id="habitacion"
            readonly
          >
            <option value="0">--Seleccione--</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="tipo">Tipo Habitacion:</label>
          <select class="form-select" name="tipo" id="tipo">
            <option value="0">--Seleccione--</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="selectPrecios">Tipo Precios</label>
          <select class="form-select" id="selectPrecios">
            <option value="0">--Seleccione--</option>
            <option value="precio_venta_01">Precio Normal</option>
            <option value="precio_venta_02">Precio Coorporativo</option>
            <option value="precio_venta_03">Precio Cliente Premiun</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="monto">Monto:</label>
          <input
            type="number"
            step="0.01"
            id="monto"
            class="form-control"
            required
            readonly
          />
        </div>
        <div class="col-md-6">
          <label for="fecha_in">Fecha IN:</label>
          <input
            type="date"
            class="form-control"
            id="fecha_in"
            name="fecha_in"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="hora_in">Hora IN</label>
          <input
            type="time"
            class="form-control"
            id="hora_in"
            name="hora_in"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="fecha_out">Fecha OUT:</label>
          <input
            type="date"
            class="form-control"
            id="fecha_out"
            name="fecha_out"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="hora_out">Hora OUT</label>
          <input
            type="time"
            class="form-control"
            id="hora_out"
            name="hora_out"
          />
        </div>
        <br />
        <h4>Acompañantes</h4>
        <hr />
        <br />
        <div class="col-md-3">
          <label for="nro_adultos">Nro Adultos:</label>
          <input
            type="number"
            class="form-control"
            id="nro_adultos"
            name="nro_adultos"
            value="0"
            required
          />
        </div>
        <div class="col-md-3">
          <label for="nro_nino">Nro Niños</label>
          <input
            type="number"
            class="form-control"
            id="nro_nino"
            name="nro_nino"
            value="0"
          />
        </div>
        <div class="col-md-3">
          <label for="nro_infantes">Nro Infantes</label>
          <input
            type="number"
            class="form-control"
            id="nro_infantes"
            name="nro_infantes"
            value="0"
          />
        </div>
        <div class="row">
          <div class="col-sm-12 mb-3 mb-sm-0 mt-3">
            <div class="card">
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-md-9">
                    <h5 class="card-title">LISTA DE ACOMPAÑANTES</h5>
                  </div>
                  <div class="col-md-3 ms-auto">
                    <button
                      type="button"
                      class="btn btn-primary w-100"
                      data-bs-toggle="modal"
                      data-bs-target="#myModal"
                    >
                      <i class="fas fa-plus"></i> Agregar Acompañante
                    </button>
                  </div>
                </div>
                <table class="table" id="tabla-acompanantes">
                  <thead>
                    <tr>
                      <th>Apellido paterno</th>
                      <th>Apellido materno</th>
                      <th>Nombres</th>
                      <th>Edad</th>
                      <th>Sexo</th>
                      <th>Parentesco</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="table-body"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <br />
        <br />
        <div class="row">
          <div class="col-sm-12 mb-3 mb-sm-0">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">FORMAS DE PAGO</h5>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label for="forma_pago">Forma de Pago:</label>
                    <select class="form-select" id="forma_pago">
                      <option value="Ninguno">--Seleccione--</option>
                      <option value="Transaccion">Transaccion</option>
                      <option value="Contado">Contado</option>
                      <option value="Paypal">Paypal</option>
                      <option value="WesterUnion">WesterUnion</option>
                      <option value="Yape">Yape</option>
                      <option value="Plin">Plin</option>
                      <option value="Tarjeta">Tarjeta</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="tipo_comprobante">Tipo de Comprobante:</label>
                    <select class="form-select" id="tipo_comprobante">
                      <option value="Ninguno">--Seleccione--</option>
                      <option value="Boleta">Boleta</option>
                      <option value="Factura">Factura</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="tipo_documento_comprobante"
                      >Tipo Documento Comprobante:</label
                    >
                    <select class="form-select" id="tipo_documento_comprobante">
                      <option value="0">--Seleccione--</option>
                      <option value="DNI">DNI</option>
                      <option value="RUC">RUC</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="nro_documento_comprobante" class="form-label"
                      >Buscar DNI</label
                    >
                    <div class="input-group">
                      <span class="input-group-text">Nro DNI/RUC</span>
                      <input
                        type="text"
                        class="form-control"
                        id="nro_documento_comprobante"
                      />
                      <button
                        type="button"
                        class="btn btn-info"
                        onclick="BuscarReniec()"
                      >
                        Buscar
                      </button>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="razon_social">Razon Social:</label>
                    <input
                      type="text"
                      class="form-control"
                      id="razon_social"
                      name="razon_social"
                    />
                  </div>
                  <div class="col-md-4">
                    <label for="direccion_comprobante">Direccion:</label>
                    <input
                      type="text"
                      class="form-control"
                      id="direccion_comprobante"
                      name="direccion_comprobante"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br />
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button
            class="btn btn-primary"
            id="selectAllRowsButton"
            type="submit"
          >
            Registro de Checking
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="myModal"
  tabindex="-1"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <label for="id_grupo_modulo"
          ><strong>Agregar Acompañantes</strong></label
        >
        <br /><br />
        <div class="row g-3">
          <div class="col-md-6">
            <label for="apellido_paterno_acompanante" class="form-label"
              >Apellido paterno:</label
            >
            <input
              type="text"
              class="form-control"
              id="apellido_paterno_acompanante"
              required
            />
          </div>
          <div class="col-md-6">
            <label for="apellido_materno_acompanante" class="form-label"
              >Apellido materno:</label
            >
            <input
              type="text"
              class="form-control"
              id="apellido_materno_acompanante"
              required
            />
          </div>
          <div class="col-md-6">
            <label for="nombres_acompanante" class="form-label">Nombres:</label>
            <input
              type="text"
              class="form-control"
              id="nombres_acompanante"
              required
            />
          </div>
          <div class="col-md-4">
            <label for="edad2" class="form-label">Edad:</label>
            <input type="text" class="form-control" id="edad2" required />
          </div>
          <div class="col-md-4">
            <label for="parentesco" class="form-label">Parentesco:</label>
            <select class="form-select" id="parentesco">
              <option value="0">--Seleccione--</option>
              <option value="Padre/Madre">Padre/Madre</option>
              <option value="Hijo/a">Hijo/a</option>
              <option value="Primo/a">Primo/a</option>
              <option value="Tio/a">Tio/a</option>
              <option value="Hermano/a">Hermano/a</option>
              <option value="Sobrino/a">Sobrino/a</option>
              <option value="Abuelo/a">Abuelo/a</option>
              <option value="Nieto/a">Nieto/a</option>
              <option value="Esposo/a">Esposo/a</option>
              <option value="Amigo/a">Amigo/a</option>
              <option value="Novio/a">Novio/a</option>
              <option value="Enamorado/a">Enamorado/a</option>
              <option value="Otros">Otros</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="sexo2" class="form-label">Sexo:</label>
            <select class="form-select" id="sexo2">
              <option value="0">--Seleccione--</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
              <option value="O">Otros</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
        <button
          type="button"
          class="btn btn-primary"
          data-bs-dismiss="modal"
          onclick="agregarRegistro()"
        >
          Agregar
        </button>
      </div>
    </div>
  </div>
</div>


<div
  class="modal fade"
  id="myModal2"
  tabindex="-1"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <label for="id_grupo_modulo"
          ><strong>Agregar Acompañantes</strong></label
        >
        <br /><br />
        <div class="row g-3">
          <div class="col-4">
            <label for="apellido_paterno_acompanante" class="form-label"
              >Apellido paterno:</label
            >
            <input
              type="text"
              class="form-control"
              id="apellido_paterno_acompanante"
              required
            />
          </div>
          <div class="col-4">
            <label for="apellido_materno_acompanante" class="form-label"
              >Apellido materno:</label
            >
            <input
              type="text"
              class="form-control"
              id="apellido_materno_acompanante"
              required
            />
          </div>
          <div class="col-4">
            <label for="nombres_acompanante" class="form-label">Nombres:</label>
            <input
              type="text"
              class="form-control"
              id="nombres_acompanante"
              required
            />
          </div>
          <div class="col-md-4">
            <label for="edad2" class="form-label">Edad:</label>
            <input type="text" class="form-control" id="edad2" required />
          </div>
          <div class="col-md-4">
            <label for="parentesco" class="form-label">Parentesco:</label>
            <select class="form-select" id="parentesco">
              <option value="0">--Seleccione--</option>
              <option value="Padre/Madre">Padre/Madre</option>
              <option value="Hijo/a">Hijo/a</option>
              <option value="Primo/a">Primo/a</option>
              <option value="Tio/a">Tio/a</option>
              <option value="Hermano/a">Hermano/a</option>
              <option value="Sobrino/a">Sobrino/a</option>
              <option value="Abuelo/a">Abuelo/a</option>
              <option value="Nieto/a">Nieto/a</option>
              <option value="Esposo/a">Esposo/a</option>
              <option value="Amigo/a">Amigo/a</option>
              <option value="Novio/a">Novio/a</option>
              <option value="Enamorado/a">Enamorado/a</option>
              <option value="Otros">Otros</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="sexo2" class="form-label">Sexo:</label>
            <select class="form-select" id="sexo2">
              <option value="0">--Seleccione--</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
              <option value="O">Otros</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
        <button
          type="button"
          class="btn btn-primary"
          data-bs-dismiss="modal"
          onclick="agregarRegistro()"
        >
          Agregar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const apiAcompanantesUrl = "<?php echo URL_API_NUEVA ?>/acompanantes";
  const apiCheckingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";
  const apiHabitacionesUrl = "<?php echo URL_API_NUEVA ?>/habitaciones";
  const apiSunatUrl = "<?php echo URL_API_NUEVA ?>/sunat";
  const apiPersonasUrl = "<?php echo URL_API_NUEVA ?>/personas";

  let fechas = [];
  let objfechas = [];

  // #region referencias a elementos del DOM
  let formChecking = null;
  let idCheckinEl = null;
  let habitacionEl = null;
  let tablaAcompanantes = null;

  let tipoDocumentoEl = null;
  let nroDocumentoEl = null;
  let direccionComprobanteEl = null;
  let razonSocialEl = null;

  // #endregion

  async function wrapper() {
    await cargarHabitaciones();

    const modal2 = new bootstrap.Modal(document.getElementById("myModal2"), {
      keyboard: false,
    });
    modal2.show();

    const params = new URLSearchParams(window.location.search);
    const checkinId = params.get("id_checkin");
    const nroHabitacion = params.get("nro_habitacion");

    formChecking = document.getElementById("form-checking");
    idCheckinEl = document.getElementById("id_checkin");
    habitacionEl = document.getElementById("habitacion");
    tablaAcompanantes =
      document.getElementById("tabla-acompanantes").tBodies[0];

    tipoDocumentoEl = document.getElementById("tipo_documento_comprobante");
    nroDocumentoEl = document.getElementById("nro_documento_comprobante");
    direccionComprobanteEl = document.getElementById("direccion_comprobante");
    razonSocialEl = document.getElementById("razon_social");

    if (checkinId) {
      idCheckinEl.value = checkinId;
    }
    if (nroHabitacion) {
      habitacionEl.value = nroHabitacion;
    }

    // lanzar evento change para cargar los datos
    habitacionEl.dispatchEvent(new Event("change"));

    if (checkinId) {
      await cargarDatos();
      await cargarAcompanantes();
    }
  }

  function obtenerAcompanantesDeTabla() {
    const rows = tablaAcompanantes.querySelectorAll("tr");
    const acompanantes = [];

    rows.forEach((row) => {
      acompanantes.push({
        id_acompanante: row.dataset.id ?? null,
        nro_de_orden_unico: row.dataset.nro_de_orden_unico ?? null,
        apellidos_y_nombres: row.cells[0].textContent,
        edad: row.cells[1].textContent,
        sexo: row.cells[2].textContent,
        parentesco: row.cells[3].textContent,
      });
    });

    return acompanantes;
  }

  async function cargarHabitaciones() {
    const fechaInEl = document.getElementById("fecha_in");
    const fechaOutEl = document.getElementById("fecha_out");
    const url = `${apiHabitacionesUrl}?con-disponibilidad&fecha_ingreso=${fechaInEl.value}&fecha_salida=${fechaOutEl.value}`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      const selectElement = document.getElementById("habitacion");

      data.forEach((item) => {
        const option = document.createElement("option");
        option.value = item.nro_habitacion;
        option.textContent = item.nro_habitacion;
        selectElement.appendChild(option);
      });
    } catch (error) {
      console.error("Error:", error);
    }
  }

  // Función para cargar los datos de la API y actualizar los inputs
  async function cargarDatos() {
    //obtenemos el numero de registro maestro del forumlario
    var codigo = formChecking.id_checkin.value;
    // Construir la URL de la API
    const url = `${apiCheckingsUrl}?con_tipo_precio&id_checking=${codigo}`;
    // Realizar una solicitud HTTP GET a la URL
    try {
      const response = await fetch(url);
      const data = await response.json();

      console.log("data", data);

      var selectElementtipohabitacion = document.getElementById("tipo");
      const selectedOptiontipoproducto =
        selectElementtipohabitacion.options[
          selectElementtipohabitacion.selectedIndex
        ];
      var selectElementprecios = document.getElementById("selectPrecios");
      const selectedOptionprecios =
        selectElementprecios.options[selectElementprecios.selectedIndex];
      document.getElementById("nombres").value = data[0].nombres;
      document.getElementById("nro_reserva").value = data[0].nro_reserva;
      document.getElementById("id_persona").value = data[0].id_persona;
      document.getElementById("nro_registro").value =
        data[0].nro_registro_maestro;
      document.getElementById("apellido_paterno").value =
        data[0].apellidos?.split(" ")[0] ?? "";
      document.getElementById("apellido_materno").value =
        data[0].apellidos?.split(" ")[1] ?? "";
      document.getElementById("tipo_documento").value = data[0].tipo_documento;
      document.getElementById("ciudad").value = data[0].lugar_procedencia;
      document.getElementById("celular").value = data[0].celular;
      document.getElementById("sexo").value = data[0].sexo;
      document.getElementById("fecha_in").value = data[0].fecha_in;
      document.getElementById("fecha_out").value = data[0].fecha_out;
      document.getElementById("hora_in").value = data[0].hora_in;
      document.getElementById("hora_out").value = data[0].hora_out;
      document.getElementById("nro_documento").value = data[0].nro_documento;
      document.getElementById("nro_adultos").value = data[0].nro_adultos;
      document.getElementById("nro_nino").value = data[0].nro_ninos;
      document.getElementById("nro_infantes").value = data[0].nro_infantes;
      document.getElementById("estacionamiento").value =
        data[0].estacionamiento;
      document.getElementById("nro_placa").value = data[0].nro_placa;
      document.getElementById("ocupacion").value = data[0].ocupacion;
      document.getElementById("email").value = data[0].email;
      document.getElementById("direccion").value = data[0].direccion;
      document.getElementById("edad").value = data[0].edad;
      document.getElementById("lugar_de_nacimiento").value =
        data[0].lugar_de_nacimiento;
      document.getElementById("fecha_nacimiento").value = data[0].fecha;
      document.getElementById("tipo_comprobante").value =
        data[0].tipo_comprobante;
      document.getElementById("tipo_documento_comprobante").value =
        data[0].tipo_documento_comprobante;
      document.getElementById("nro_documento_comprobante").value =
        data[0].nro_documento_comprobante;
      document.getElementById("razon_social").value = data[0].razon_social;
      document.getElementById("direccion_comprobante").value =
        data[0].direccion_comprobante;
      document.getElementById("forma_pago").value = data[0].forma_pago;
      document.getElementById("monto").value = data[0].tarifa;

      selectedOptionprecios.textContent = data[0].tipo_precio;
      selectedOptiontipoproducto.textContent = data[0].nombre_producto;
    } catch (error) {
      console.error("Error:", error);
    }
  }

  async function cargarAcompanantes() {
    const nroRegistro = document.getElementById("nro_registro").value;
    const url = `${apiAcompanantesUrl}?nro_registro_maestro=${nroRegistro}`;

    try {
      const response = await fetch(url);
      let data = await response.json();

      tablaAcompanantes.innerHTML = "";

      // eliminar el titular de la lista de acompanantes
      data = data.filter((acompanante) => acompanante.nro_de_orden_unico != 0);

      // actualizar la tabla de acompanantes
      data.forEach((acompanante) => {
        const row = tablaAcompanantes.insertRow();
        row.dataset.id = acompanante.id_acompanante;
        row.dataset.nro_de_orden_unico = acompanante.nro_de_orden_unico;

        const nombres = acompanante.apellidos_y_nombres.split(", ")[1];
        const apellidoPaterno = acompanante.apellidos_y_nombres.split(" ")[0];
        const apellidoMaterno = acompanante.apellidos_y_nombres
          .split(", ")[0]
          .split(" ")[1];

        row.innerHTML = `
          <td>${apellidoPaterno}</td>
          <td>${apellidoMaterno}</td>
          <td>${nombres}</td>
          <td>${acompanante.edad}</td>
          <td>${acompanante.sexo}</td>
          <td>${
            acompanante.parentesco ? acompanante.parentesco : "TITULAR"
          }</td>
          <td>
            <button class="btn btn-danger" onclick="eliminarAcompanante(event, ${
              acompanante.id_acompanante
            })">
              Eliminar
            </button>
          </td>
          `;
      });
    } catch (error) {
      console.error("Error:", error);
    }
  }

  async function eliminarAcompanante(event, id) {
    event.preventDefault();

    const url = `${apiAcompanantesUrl}/${id}`;
    const options = {
      method: "DELETE",
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();
      console.log(data);
      cargarAcompanantes();
    } catch (error) {
      console.error("Error:", error);
    }
  }

  async function BuscarReniec() {
    razonSocialEl.value = "";
    direccionComprobanteEl.value = "";

    // Verifica si ambos campos están llenos
    if (tipoDocumentoEl.value == "0" || !nroDocumentoEl.value.trim()) {
      mostrarAlert(
        "error",
        "Debe ingresar el tipo y número de documento",
        "consultar"
      );
      return; // No continúes con la búsqueda si falta información.
    }

    const url = `${apiSunatUrl}?tipo=${tipoDocumentoEl.value}&nro=${nroDocumentoEl.value}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      console.log(data);

      direccionComprobanteEl.value = data.direccion || "";
      razonSocialEl.value = data.nombre || "";
    } catch (error) {
      console.error("Error:", error);
    }
  }
</script>
<script>
  const selectTipo = document.getElementById("tipo");
  fetch("<?php echo URL_API_CARLITOS ?>/api-reservas.php", {
    method: "GET3",
  })
    .then((response) => response.json())
    .then((productosData2) => {
      llenarSelectHabitaciones(productosData2);
    })
    .catch((error) =>
      console.error("Error al obtener los datos del API:", error)
    );
  // Función para llenar el select con los datos del objeto
  function llenarSelectHabitaciones(data) {
    var selectElement = document.getElementById("habitacion");
    data.forEach(function (producto) {
      var option = document.createElement("option");
      option.value = producto.nombre_producto;
      option.textContent = producto.nombre_producto;
      selectTipo.appendChild(option);
    });
  }
</script>
<script>
  // Objeto con datos de productos
  let productos = [];
  const precioVentaInput = document.getElementById("monto");
  const selectElement = document.getElementById("habitacion");
  const selectPrecios = document.getElementById("selectPrecios");

  // Función para llenar el select con los datos del objeto
  function llenarSelect(data) {
    var selectElement = document.getElementById("habitacion");
    data.forEach(function (producto) {
      var option = document.createElement("option");
      option.value = producto.id_habitacion;
      option.textContent = producto.nro_habitacion;
      selectElement.appendChild(option);
    });
  }
  document.addEventListener("DOMContentLoaded", function () {
    selectTipo.addEventListener("change", function () {
      precioVentaInput.value = " ";
      selectPrecios.selectedIndex = 0;
      var selectedOption = selectTipo.options[selectTipo.selectedIndex];
      var codigo = selectedOption.text;
      // Realizar la solicitud a la API en PHP
      fetch(
        `<?php echo URL_API_CARLITOS ?>/api-reservas.php?codigo=${codigo}`,
        {
          method: "GET4",
        }
      )
        .then((response) => response.json())
        .then((data) => {
          // Manipular los datos devueltos por la API
          JSON.stringify(data);
          productos = data;
        })
        .catch((error) => {
          console.error("Error en la solicitud a la API:", error);
        });
    });
  });
  document.addEventListener("DOMContentLoaded", function () {
    selectPrecios.addEventListener("change", function () {
      const selectedPrecioKey = selectPrecios.value;
      const selectedPrecio = productos[0][selectedPrecioKey]; // Suponiendo que solo hay un producto en productos[]
      precioVentaInput.value = selectedPrecio;
    });
  });
</script>
<script>
  // Obtén la URL actual
  var url = window.location.href;
  // Obtén la URL actual
  const params = new URLSearchParams(window.location.search);
  let id = params.get("id_checkin");
  console.log(id);
  // Comprueba si los parámetros tienen el valor "null" o vacio
  if (id === "null" || id == null) {
    // Si ambos parámetros tienen el valor "null", ejecuta una función
    funcionSinParametros();
  } else if (url === "<?php echo URL ?>/gestionar-checkin-hotel/") {
    funcionSinParametros();
  } else {
    // Si la URL es igual a la URL específica, haz algo
    // Si al menos uno de los parámetros no tiene el valor "null", ejecuta otra función
    funcionConParametros();
  }
  // Define las funciones que deseas ejecutar
  function funcionConParametros() {
    // asignar el valor de id a un campo de entrada
    document.getElementById("id_checkin").value = id;
  }

  function funcionSinParametros() {
    fetch("<?php echo URL_API_CARLITOS ?>/api-config.php", {
      method: "HOTEL",
    })
      .then((response) => response.json())
      .then((data) => {
        // Concatenar los valores obtenidos (supongamos que es un array de nombres)
        var concatenatedData = "";
        var concatenatedDataArray = [];
        data.forEach((item) => {
          // Sumar uno al valor de numero_correlativo antes de la concatenación
          let numeroCorrelativo = parseInt(item.numero_correlativo) + 1;
          var concatenatedData =
            item.codigo + numeroCorrelativo.toString().padStart(6, "0");
          concatenatedDataArray.push(concatenatedData);
        });
        // Actualizar el valor del campo de entrada
        document.getElementById("nro_registro").value = concatenatedDataArray;
      })
      .catch((error) => {
        console.error("Error en la solicitud a la API:", error);
      });
  }
</script>
<script>
  var nro_reserva = document.getElementById("nro_reserva").value;
  // Crear un array para almacenar los registros
  let registros = [];
  let contador = 0;
  function agregarRegistro() {
    // Obtener valores de los campos de entrada
    var apellidoPaternoAcompanante = document.getElementById(
      "apellido_paterno_acompanante"
    ).value;
    var apellidoMaternoAcompanante = document.getElementById(
      "apellido_materno_acompanante"
    ).value;
    var nombresAcompanante = document.getElementById(
      "nombres_acompanante"
    ).value;
    var edad2 = document.getElementById("edad2").value;
    var parentesco2 = document.getElementById("parentesco").value;
    var sexo2 = document.getElementById("sexo2").value;
    var nombre_completo2 = `${apellidoPaternoAcompanante} ${apellidoMaternoAcompanante}, ${nombresAcompanante}`;

    // Crear un objeto para representar el registro de afuera
    var registroFuera = {
      nombre: nombre_completo2,
      edad: edad2,
      sexo: sexo2,
      parentesco: parentesco2,
    };

    // Agregar el nuevo registro al array
    registros.push(registroFuera);

    // agregar al acompanante a la tabla de acompanantes
    var tableBody = document.getElementById("table-body");
    var newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td>${registroFuera.nombre}</td>
        <td>${registroFuera.edad}</td>
        <td>${registroFuera.sexo}</td>
        <td>${registroFuera.parentesco}</td>
        <td><button class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td>
    `;
    tableBody.appendChild(newRow);

    // Actualizar la tabla
    // actualizarTabla();
    contador += 1;
    // Limpiar campos de entrada después de agregar la fila
    document.getElementById("apellido_paterno_acompanante").value = "";
    document.getElementById("apellido_materno_acompanante").value = "";
    document.getElementById("nombres_acompanante").value = "";
    document.getElementById("edad2").value = "";
    document.getElementById("sexo2").value = "";
    document.getElementById("parentesco").value = "0";
  }

  function eliminarFila(button) {
    // Obtener la fila que contiene el botón
    var row = button.parentNode.parentNode;

    // Obtener el índice de la fila en la tabla
    var rowIndex = row.rowIndex;

    // Eliminar el registro correspondiente del array
    registros.splice(rowIndex - 1, 1);

    // Actualizar la tabla
    actualizarTabla();
  }

  function actualizarTabla() {
    // Obtener la tabla
    var tableBody = document.getElementById("table-body");

    // Limpiar la tabla existente
    tableBody.innerHTML = "";

    // Recorrer el array de registros y agregar filas a la tabla
    registros.forEach(function (registro, index) {
      // Omitir el primer registro (índice 0)
      if (index !== 0) {
        var newRow = document.createElement("tr");
        newRow.innerHTML = `
                <td>${registro.nombre}</td>
                <td>${registro.edad}</td>
                <td>${registro.sexo}</td>
                <td>${registro.parentesco}</td>
                <td><button class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button></td>
            `;
        tableBody.appendChild(newRow);
      }
    });
  }
</script>
<script>
  let formData = [];
  document.addEventListener("DOMContentLoaded", function () {
    document
      .getElementById("form-checking")
      .addEventListener("submit", async function (event) {
        event.preventDefault();
        const habitacionInput = document.getElementById("habitacion");
        const selectedOption =
          habitacionInput.options[habitacionInput.selectedIndex];
        // recibe todos los datos del formulario
        if (registros.length === 0) {
          var apellidos2 = `${
            document.getElementById("apellido_paterno").value
          } ${document.getElementById("apellido_materno").value}`;
          var nombres2 = document.getElementById("nombres").value;
          var edad2 = document.getElementById("edad").value;
          var parentesco2 = "";
          var sexo2 = document.getElementById("sexo").value;
          var nombre_completo2 = apellidos2 + ", " + nombres2;

          // Crear un objeto para representar el registro de afuera
          var registroFuera = {
            nombre: nombre_completo2,
            edad: edad2,
            sexo: sexo2,
            parentesco: parentesco2,
          };

          // Agregar el registro de afuera al array
          registros.push(registroFuera);
          formData = {
            nro_registro: document.getElementById("nro_registro").value,
            nro_reserva: document.getElementById("nro_reserva").value,
            apellidos: `${document.getElementById("apellido_paterno").value} ${
              document.getElementById("apellido_materno").value
            }`,
            nombres: document.getElementById("nombres").value,
            tipo_documento: document.getElementById("tipo_documento").value,
            nro_documento: document.getElementById("nro_documento").value,
            lugar_de_nacimiento: document.getElementById("lugar_de_nacimiento")
              .value,
            fecha_nacimiento: document.getElementById("fecha_nacimiento").value,
            edad: document.getElementById("edad").value,
            ocupacion: document.getElementById("ocupacion").value,
            direccion: document.getElementById("direccion").value,
            ciudad: document.getElementById("ciudad").value,
            celular: document.getElementById("celular").value,
            email: document.getElementById("email").value,
            estacionamiento: document.getElementById("estacionamiento").value,
            nro_placa: document.getElementById("nro_placa").value,
            nro_habitacion: selectedOption.textContent,
            valor: document.getElementById("monto").value,
            fecha_in: document.getElementById("fecha_in").value,
            hora_in: document.getElementById("hora_in").value,
            fecha_out: document.getElementById("fecha_out").value,
            hora_out: document.getElementById("hora_out").value,
            nro_adultos: document.getElementById("nro_adultos").value,
            nro_nino: document.getElementById("nro_nino").value,
            nro_infantes: document.getElementById("nro_infantes").value,
            forma_pago: document.getElementById("forma_pago").value,
            tipo_comprobante: document.getElementById("tipo_comprobante").value,
            tipo_documento_comprobante: document.getElementById(
              "tipo_documento_comprobante"
            ).value,
            nro_documento_comprobante: document.getElementById(
              "nro_documento_comprobante"
            ).value,
            razon_social: document.getElementById("razon_social").value,
            direccion_comprobante: document.getElementById(
              "direccion_comprobante"
            ).value,
            sexo: document.getElementById("sexo").value,
            id_persona: document.getElementById("id_persona").value,
            acompanantes: registros,
          };
        } else {
          formData = {
            nro_registro: document.getElementById("nro_registro").value,
            nro_reserva: document.getElementById("nro_reserva").value,
            apellidos: `${document.getElementById("apellido_paterno").value} ${
              document.getElementById("apellido_materno").value
            } `,
            nombres: document.getElementById("nombres").value,
            tipo_documento: document.getElementById("tipo_documento").value,
            nro_documento: document.getElementById("nro_documento").value,
            lugar_de_nacimiento: document.getElementById("lugar_de_nacimiento")
              .value,
            fecha_nacimiento: document.getElementById("fecha_nacimiento").value,
            edad: document.getElementById("edad").value,
            ocupacion: document.getElementById("ocupacion").value,
            direccion: document.getElementById("direccion").value,
            ciudad: document.getElementById("ciudad").value,
            celular: document.getElementById("celular").value,
            email: document.getElementById("email").value,
            estacionamiento: document.getElementById("estacionamiento").value,
            nro_placa: document.getElementById("nro_placa").value,
            nro_habitacion: selectedOption.textContent,
            valor: document.getElementById("monto").value,
            fecha_in: document.getElementById("fecha_in").value,
            hora_in: document.getElementById("hora_in").value,
            fecha_out: document.getElementById("fecha_out").value,
            hora_out: document.getElementById("hora_out").value,
            nro_adultos: document.getElementById("nro_adultos").value,
            nro_nino: document.getElementById("nro_nino").value,
            nro_infantes: document.getElementById("nro_infantes").value,
            forma_pago: document.getElementById("forma_pago").value,
            tipo_comprobante: document.getElementById("tipo_comprobante").value,
            tipo_documento_comprobante: document.getElementById(
              "tipo_documento_comprobante"
            ).value,
            nro_documento_comprobante: document.getElementById(
              "nro_documento_comprobante"
            ).value,
            razon_social: document.getElementById("razon_social").value,
            direccion_comprobante: document.getElementById(
              "direccion_comprobante"
            ).value,
            sexo: document.getElementById("sexo").value,
            id_persona: document.getElementById("id_persona").value,
            acompanantes: registros,
          };
        }
        //almacenar id_checkin en una variable
        var id_checkin = document.getElementById("id_checkin").value;

        const payload = {};

        // preparar datos de persona
        payload.persona = {
          id_persona: formData.id_persona,
          nombres: formData.nombres,
          apellidos: formData.apellidos,
          tipo_documento: formData.tipo_documento,
          nro_documento: formData.nro_documento,
          lugar_de_nacimiento: formData.lugar_de_nacimiento,
          fecha: formData.fecha_nacimiento,
          edad: formData.edad,
          ocupacion: formData.ocupacion,
          direccion: formData.direccion,
          ciudad: formData.ciudad,
          celular: formData.celular,
          email: formData.email,
          sexo: formData.sexo,
        };

        // preparar datos de checkin
        payload.checking = {
          id_checkin: id_checkin,
          nro_registro_maestro: formData.nro_registro,
          nro_reserva: formData.nro_reserva,
          nro_habitacion: formData.nro_habitacion,
          fecha_in: formData.fecha_in,
          hora_in: formData.hora_in,
          fecha_out: formData.fecha_out,
          hora_out: formData.hora_out,
          nro_adultos: formData.nro_adultos,
          nro_ninos: formData.nro_nino,
          nro_infantes: formData.nro_infantes,
          forma_pago: formData.forma_pago,
          tipo_comprobante: formData.tipo_comprobante,
          tipo_documento_comprobante: formData.tipo_documento_comprobante,
          nro_documento_comprobante: formData.nro_documento_comprobante,
          razon_social: formData.razon_social,
          direccion_comprobante: formData.direccion_comprobante,
          estacionamiento: formData.estacionamiento,
          nro_placa: formData.nro_placa,
        };

        payload.precio_unitario = formData.valor;

        payload.acompanantes = obtenerAcompanantesDeTabla();

        if (id_checkin === "" || id_checkin.trim() === "") {
          fetch("<?php echo URL_API_CARLITOS ?>/api-huespedes.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
          })
            .then((response) => response.json())
            .then((data) => {
              console.log(data);
              open("../listado-rooming", "_self");
            })
            .catch((error) => {
              console.error(error);
              open("../listado-rooming", "_self");
            });
        } else {
          const url = `${apiCheckingsUrl}/${id_checkin}/normal`;
          const options = {
            method: "PATCH",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
          };

          try {
            const response = await fetch(url, options);
            const data = await response.json();
            console.log(data);
            open("../listado-rooming", "_self");
          } catch (error) {
            console.error("Error:", error);
            open("../listado-rooming", "_self");
          }
        }
      });
  });

  async function buscarPersona() {
    let codigo = document.getElementById("nro_documento").value;
    document.getElementById("nombres").value = "";
    document.getElementById("apellido_paterno").value = "";
    document.getElementById("apellido_materno").value = "";

    const url = `${apiPersonasUrl}?dni=${codigo}`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      document.getElementById("nombres").value = data.nombres;
      document.getElementById("apellido_paterno").value =
        data.apellidos.split(" ")[0];
      document.getElementById("apellido_materno").value =
        data.apellidos.split(" ")[1];
      document.getElementById("id_persona").value = data.id_persona;
      document.getElementById("edad").value = data.edad;
      document.getElementById("sexo").value = data.sexo;
      document.getElementById("lugar_de_nacimiento").value =
        data.lugar_de_nacimiento;
      document.getElementById("fecha_nacimiento").value = data.fecha;
      document.getElementById("ocupacion").value = data.ocupacion;
      document.getElementById("direccion").value = data.direccion;
      document.getElementById("ciudad").value = data.ciudad;
      document.getElementById("celular").value = data.celular;
      document.getElementById("email").value = data.email;
    } catch (error) {
      console.error("Error:", error);
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
