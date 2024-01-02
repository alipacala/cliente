<?php
require "../../inc/header.php";

session_start();
mostrarHeader("visitante", false);
$idUnidadNegocio = isset($_GET["un"]) ? $_GET ["un"] : null;

if ($idUnidadNegocio == null) {
  $data = null;
}

// llamar a la api para obtener los datos de la unidad de negocio
$url = URL_API_NUEVA . "/checkings?buscar_escaneo&un=" . $idUnidadNegocio;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
?>

<div class="container my-5 pt-5 main-cont">
  <?php if (!$data) { ?>
  <h1 class="text-center">Bienvenido al Hotel Spa Arenas</h1>
  <p class="lead text-center">Disfrute su estadía.</p>

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center">Consultas en Recepción</h5>
        </div>
      </div>
    </div>
  </div>
  <?php } else { ?>

  <div id="progress-bar" class="d-none">
    <ol class="steps">
      <li class="step is-active" data-step="1">Titular</li>
      <li class="step" data-step="2">Acompañantes</li>
      <li class="step" data-step="3">Comprobantes</li>
    </ol>
  </div>

  <div class="card w-100 mb-3">
    <div class="card-body">
      <div class="step-0 row">
        <div class="col-12">
          <h4 class="text-center" id="titular"></h4>
          <h5 class="text-center">Bienvenido al Hotel Spa Arenas</h5>
        </div>
        <div class="col-12">
          <p class="text-center">Se le han asignado estas Habitaciones:</p>
          <div
            id="habitaciones"
            class="d-flex justify-content-center gap-3"
          ></div>
        </div>
        <div class="col-12">
          <div class="responsive-table">
            <table class="table">
              <thead>
                <tr>
                  <th>Fecha de Ingreso</th>
                  <th>Al</th>
                  <th>Nro Dias</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td id="fecha-ingreso"></td>
                  <td id="fecha-salida"></td>
                  <td id="nro-dias"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-12">
          <button
            class="btn btn-primary w-100"
            id="step-0-to-1"
            onclick="pasarDe0a1()"
          >
            Empecemos...
          </button>
        </div>
      </div>

      <div class="step-1 row d-none">
        <h2 class="col-12">Datos personales del Titular del Hospedaje</h2>

        <div class="col-12 mt-2">Tipo Documento de</div>

        <div class="col-4">
          <label for="tipo_documento" class="form-label">Identidad:</label>
          <select class="form-select" id="tipo_documento">
            <option value="0">--Seleccione--</option>
            <option value="1">DNI</option>
            <option value="2">PASAPORTE</option>
            <option value="3">CEDULA DE IDENTIFICACION</option>
          </select>
        </div>
        <div class="col-8">
          <label for="nro_documento" class="form-label"
            >Nro de Documento ID</label
          >
          <input
            type="text"
            class="form-control"
            id="nro_documento"
            onchange="buscarPersona()"
            required
          />
        </div>

        <div class="col-12 mt-2">Apellidos</div>

        <div class="col-4">
          <label for="apellido_paterno" class="form-label">Paterno:</label>
          <input
            type="text"
            class="form-control"
            id="apellido_paterno"
            required
          />
        </div>
        <div class="col-4">
          <label for="apellido_materno" class="form-label">Materno:</label>
          <input
            type="text"
            class="form-control"
            id="apellido_materno"
            required
          />
        </div>
        <div class="col-4">
          <label for="nombres" class="form-label">Nombres:</label>
          <input type="text" class="form-control" id="nombres" required />
        </div>

        <div class="col-4 mt-2">
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
        <div class="col-4 mt-2">
          <label for="fecha_nacimiento" class="form-label"
            >Fecha Nacimiento:</label
          >
          <input
            type="date"
            class="form-control"
            id="fecha_nacimiento"
            required
          />
        </div>
        <div class="col-4 mt-2">
          <label for="nacionalidad" class="form-label"
            >País de Nacimiento:</label
          >
          <select
            class="form-select"
            id="nacionalidad"
            name="nacionalidad"
            required
          ></select>
        </div>

        <div class="col-4 mt-2">
          <label for="sexo">Sexo:</label>
          <select class="form-select" id="sexo" name="sexo" required>
            <option value="">--Seleccione--</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
          </select>
        </div>

        <div class="col-8 mt-2">
          <label for="ocupacion">Profesión:</label>
          <input
            type="text"
            class="form-control"
            id="ocupacion"
            name="ocupacion"
            required
          />
        </div>
        <div class="col-12 mt-2">
          <label for="direccion">Dirección:</label>
          <input
            type="text"
            class="form-control"
            id="direccion"
            name="direccion"
            required
          />
        </div>
        <div class="col-8 mt-2">
          <label for="ciudad">Ciudad:</label>
          <input
            type="text"
            class="form-control"
            id="ciudad"
            name="ciudad"
            required
          />
        </div>
        <div class="col-4 mt-2">
          <label for="pais">País:</label>
          <select class="form-select" id="pais" name="pais" required></select>
        </div>
        <div class="col-4 mt-2">
          <label for="celular">Celular:</label>
          <input
            type="text"
            class="form-control"
            id="celular"
            name="celular"
            required
          />
        </div>
        <div class="col-8 mt-2">
          <label for="email">Email:</label>
          <input type="text" class="form-control" id="email" name="email" />
        </div>

        <div class="col-4 mt-2 d-flex align-items-end">
          <div class="form-check mb-2">
            <input
              class="form-check-input"
              type="checkbox"
              id="estacionamiento"
            />
            <label class="form-check-label" for="estacionamiento">
              Estacionamiento
            </label>
          </div>
        </div>
        <div class="col-8 mt-2">
          <label for="nro_placa">Nro placa:</label>
          <input
            type="text"
            class="form-control"
            id="nro_placa"
            name="nro_placa"
          />
        </div>

        <div class="col-12 mt-3 d-flex justify-content-end">
          <button
            class="btn btn-primary"
            id="step-1-to-2"
            onclick="pasarDe1a2()"
          >
            Continuar <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>

      <div class="step-2 row d-none">
        <h2 class="col-12">Datos de los Acompañantes</h2>
        <div class="col-8 ms-auto mt-2">
          <button
            type="button"
            class="btn btn-primary w-100"
            data-bs-toggle="modal"
            data-bs-target="#myModal"
          >
            <i class="fas fa-plus"></i> Agregar Acompañante
          </button>
        </div>
        <div class="table-responsive my-3">
          <table class="table" id="tabla-acompanantes">
            <thead>
              <tr>
                <th>Nro</th>
                <th>Acompañante</th>
                <th>Sexo</th>
                <th>Edad</th>
                <th>Parentesco</th>
              </tr>
            </thead>
            <tbody id="table-body"></tbody>
          </table>
        </div>
        <div class="col-4">
          <label for="nro_adultos">Adultos</label>
          <input
            type="number"
            class="form-control"
            id="nro_adultos"
            name="nro_adultos"
            value="0"
            required
          />
        </div>
        <div class="col-4">
          <label for="nro_nino">Niños</label>
          <input
            type="number"
            class="form-control"
            id="nro_nino"
            name="nro_nino"
            value="0"
          />
        </div>
        <div class="col-4">
          <label for="nro_infantes">Infantes</label>
          <input
            type="number"
            class="form-control"
            id="nro_infantes"
            name="nro_infantes"
            value="0"
          />
        </div>

        <div class="col-12 mt-3 d-flex justify-content-end">
          <button
            class="btn btn-primary"
            id="step-2-to-3"
            onclick="pasarDe2a3()"
          >
            Continuar <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>

      <div class="step-3 row d-none">
        <h2 class="col-12">Comprobantes y formas de pago</h2>

        <div class="col-12 mt-2">
          <label for="forma_pago">Forma de Pago:</label>
          <select class="form-select" id="forma_pago">
            <option value="Ninguno">--Seleccione--</option>
            <option value="Transferencia">Transferencia</option>
            <option value="Contado">Contado</option>
            <option value="Paypal">Paypal</option>
            <option value="WesterUnion">WesterUnion</option>
            <option value="Yape">Yape</option>
            <option value="Plin">Plin</option>
            <option value="Tarjeta">Tarjeta</option>
          </select>
        </div>
        <div class="col-12 mt-2">
          <label for="tipo_comprobante">Tipo de Comprobante:</label>
          <select class="form-select" id="tipo_comprobante" onchange="alCambiarTipoComprobante()">
            <option value="Ninguno">--Seleccione--</option>
            <option value="Boleta">Boleta</option>
            <option value="Factura">Factura</option>
          </select>
        </div>
        <div class="col-4 mt-2">
          <label for="tipo_documento_comprobante">Tipo Doc. Id:</label>
          <select class="form-select" id="tipo_documento_comprobante">
            <option value="0">--Seleccione--</option>
            <option value="DNI">DNI</option>
            <option value="RUC">RUC</option>
          </select>
        </div>
        <div class="col-8 mt-2">
          <label for="nro_documento_comprobante" class="form-label"
            >Nro Documento</label
          >
          <input
            type="text"
            class="form-control"
            id="nro_documento_comprobante"
            onchange="buscarEnSunat()"
          />
        </div>
        <div class="col-12 mt-2">
          <label for="razon_social">A nombre de:</label>
          <input
            type="text"
            class="form-control"
            id="razon_social"
            name="razon_social"
          />
        </div>
        <div class="col-12 mt-2">
          <label for="direccion_comprobante">Dirección</label>
          <input
            type="text"
            class="form-control"
            id="direccion_comprobante"
            name="direccion_comprobante"
          />
        </div>
        <div class="col-12 mt-2">
          <label for="lugar_comprobante">Lugar</label>
          <input
            type="text"
            class="form-control"
            id="lugar_comprobante"
            name="lugar_comprobante"
          />
        </div>

        <div class="col-12 mt-3 d-flex justify-content-end">
          <button class="btn btn-primary" id="finalizar" onclick="finalizar()">
            Finalizar...
          </button>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
</div>

<?php if ($data) { ?>
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
        <h5>Ingrese los datos del acompañante</h5>

        <div class="row g-3">
          <div class="col-12 mt-4">Apellidos</div>

          <div class="col-4 mt-0">
            <label for="apellido_paterno_acompanante" class="form-label"
              >Paterno:</label
            >
            <input
              type="text"
              class="form-control"
              id="apellido_paterno_acompanante"
              required
            />
          </div>
          <div class="col-4 mt-0">
            <label for="apellido_materno_acompanante" class="form-label"
              >Materno:</label
            >
            <input
              type="text"
              class="form-control"
              id="apellido_materno_acompanante"
              required
            />
          </div>
          <div class="col-4 mt-0">
            <label for="nombres_acompanante" class="form-label">Nombres:</label>
            <input
              type="text"
              class="form-control"
              id="nombres_acompanante"
              required
            />
          </div>
          <div class="col-4">
            <label for="edad_acompanante" class="form-label">Edad</label>
            <input
              type="text"
              class="form-control"
              id="edad_acompanante"
              required
            />
          </div>
          <div class="col-4">
            <label for="sexo_acompanante" class="form-label">Sexo</label>
            <select class="form-select" id="sexo_acompanante" required>
              <option value="">--Seleccione--</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>
          </div>
          <div class="col-4">
            <label for="parentesco" class="form-label">Parentesco:</label>
            <select class="form-select" id="parentesco">
              <option value="">--Seleccione--</option>
              <option value="Pareja">Pareja</option>
              <option value="Hijo/a">Hijo/a</option>
              <option value="Familiar">Familiar</option>
              <option value="Sobrino/a">Sobrino/a</option>
              <option value="Amigo/a">Amigo/a</option>
              <option value="Otros">Otros</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="d-flex justify-content-end">
          <button
            type="button"
            class="btn btn-primary"
            data-bs-dismiss="modal"
            onclick="agregarRegistro()"
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

<style>
  .steps {
    list-style: none;
    margin: 0;
    padding: 0;
    display: table;
    table-layout: fixed;
    width: 100%;
    color: #929292;
    height: 4rem;
  }
  .steps > .step {
    position: relative;
    display: table-cell;
    text-align: center;
    font-size: 0.875rem;
    color: #6d6875;
  }
  .steps > .step:before {
    content: attr(data-step);
    display: block;
    margin: 0 auto;
    background: #fff;
    border: 2px solid #e6e6e6;
    color: #e6e6e6;
    width: 2rem;
    height: 2rem;
    text-align: center;
    margin-bottom: -4.2rem;
    line-height: 1.9rem;
    border-radius: 100%;
    position: relative;
    z-index: 1;
    font-weight: 700;
    font-size: 1rem;
  }
  .steps > .step:after {
    content: "";
    position: absolute;
    display: block;
    background: #e6e6e6;
    width: 100%;
    height: 0.125rem;
    top: 1rem;
    left: 50%;
  }
  .steps > .step:last-child:after {
    display: none;
  }
  .steps > .step.is-complete {
    color: #6d6875;
  }
  .steps > .step.is-complete:before {
    content: "\2713";
    color: #0d6efd;
    background: #d7eaff;
    border: 2px solid #0d6efd;
  }
  .steps > .step.is-complete:after {
    background: #0d6efd;
  }
  .steps > .step.is-active {
    font-size: 1.5rem;
  }
  .steps > .step.is-active:before {
    color: #fff;
    border: 2px solid #0d6efd;
    background: #0d6efd;
    margin-bottom: -4.9rem;
  }
  /** * Some Generic Styling */
  *,
  *:after,
  *:before {
    box-sizing: border-box;
  }
  h1 {
    margin-bottom: 1.5em;
  }
  .steps {
    margin-bottom: 3em;
  }
</style>

<script>
  const apiAcompanantesUrl = "<?php echo URL_API_NUEVA ?>/acompanantes";
  const apiCheckingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";
  const apiHabitacionesUrl = "<?php echo URL_API_NUEVA ?>/habitaciones";
  const apiSunatUrl = "<?php echo URL_API_NUEVA ?>/sunat";
  const apiPersonasUrl = "<?php echo URL_API_NUEVA ?>/personas";
  const apiPaisesUrl = "<?php echo URL_API_NUEVA ?>/paises";

  let acompanantes = [];
  const nroPersonas = {
    adultos: 0,
    ninos: 0,
    infantes: 0,
  };
  let idChecking = null;
  let nroReserva = null;
  let nroRegistroMaestro = null;

  let iteradorAcompanantes = 1;

  // #region referencias a elementos del DOM
  let titularEl = null;
  let habitacionesEl = null;
  let fechaIngresoEl = null;
  let fechaSalidaEl = null;
  let nroDiasEl = null;

  let tipoDocumentoEl = null;
  let nroDocumentoEl = null;
  let apellidoPaternoEl = null;
  let apellidoMaternoEl = null;
  let nombresEl = null;
  let lugarDeNacimientoEl = null;
  let fechaNacimientoEl = null;
  let nacionalidadEl = null;
  let sexoEl = null;
  let ocupacionEl = null;
  let direccionEl = null;
  let ciudadEl = null;
  let paisEl = null;
  let celularEl = null;
  let emailEl = null;
  let estacionamientoEl = null;
  let nroPlacaEl = null;

  let tablaAcompanantes = null;
  let nroAdultosEl = null;
  let nroNinosEl = null;
  let nroInfantesEl = null;

  let formaPagoEl = null;
  let tipoComprobanteEl = null;
  let tipoDocumentoComprobanteEl = null;
  let nroDocumentoComprobanteEl = null;
  let razonSocialEl = null;
  let direccionComprobanteEl = null;
  let lugarComprobanteEl = null;

  let apellidoPaternoAcompananteEl = null;
  let apellidoMaternoAcompananteEl = null;
  let nombresAcompananteEl = null;
  let edadAcompananteEl = null;
  let sexoAcompananteEl = null;
  let parentescoAcompananteEl = null;
  // #endregion

  async function wrapper() {
    await cargarPaises();

    const params = new URLSearchParams(window.location.search);

    // #region referencias a elementos del DOM
    titularEl = document.getElementById("titular");
    habitacionesEl = document.getElementById("habitaciones");
    fechaIngresoEl = document.getElementById("fecha-ingreso");
    fechaSalidaEl = document.getElementById("fecha-salida");
    nroDiasEl = document.getElementById("nro-dias");

    tipoDocumentoEl = document.getElementById("tipo_documento");
    nroDocumentoEl = document.getElementById("nro_documento");
    apellidoPaternoEl = document.getElementById("apellido_paterno");
    apellidoMaternoEl = document.getElementById("apellido_materno");
    nombresEl = document.getElementById("nombres");
    lugarDeNacimientoEl = document.getElementById("lugar_de_nacimiento");
    fechaNacimientoEl = document.getElementById("fecha_nacimiento");
    nacionalidadEl = document.getElementById("nacionalidad");
    sexoEl = document.getElementById("sexo");
    ocupacionEl = document.getElementById("ocupacion");
    direccionEl = document.getElementById("direccion");
    ciudadEl = document.getElementById("ciudad");
    paisEl = document.getElementById("pais");
    celularEl = document.getElementById("celular");
    emailEl = document.getElementById("email");
    estacionamientoEl = document.getElementById("estacionamiento");
    nroPlacaEl = document.getElementById("nro_placa");

    tablaAcompanantes =
      document.getElementById("tabla-acompanantes").tBodies[0];
    nroAdultosEl = document.getElementById("nro_adultos");
    nroNinosEl = document.getElementById("nro_nino");
    nroInfantesEl = document.getElementById("nro_infantes");

    formaPagoEl = document.getElementById("forma_pago");
    tipoComprobanteEl = document.getElementById("tipo_comprobante");
    tipoDocumentoComprobanteEl = document.getElementById(
      "tipo_documento_comprobante"
    );
    nroDocumentoComprobanteEl = document.getElementById(
      "nro_documento_comprobante"
    );
    razonSocialEl = document.getElementById("razon_social");
    direccionComprobanteEl = document.getElementById("direccion_comprobante");
    lugarComprobanteEl = document.getElementById("lugar_comprobante");

    apellidoPaternoAcompananteEl = document.getElementById(
      "apellido_paterno_acompanante"
    );
    apellidoMaternoAcompananteEl = document.getElementById(
      "apellido_materno_acompanante"
    );
    nombresAcompananteEl = document.getElementById("nombres_acompanante");
    edadAcompananteEl = document.getElementById("edad_acompanante");
    sexoAcompananteEl = document.getElementById("sexo_acompanante");
    parentescoAcompananteEl = document.getElementById("parentesco");
    // #endregion

    const checkingCargado = `<?php echo json_encode($data) ?>`;
    if (checkingCargado) {
      const data = JSON.parse(checkingCargado);
      console.log(data);

      const habitaciones = data.habitaciones;
      const checking = data.checking;

      idChecking = checking.id_checkin;
      nroReserva = checking.nro_reserva;
      nroRegistroMaestro = checking.nro_registro_maestro;

      titularEl.textContent = `¡Hola ${checking.nombre}!`;

      habitaciones.forEach((habitacion) => {
        const cardHabitacion = document.createElement("div");
        cardHabitacion.classList.add("card", "mb-3");
        cardHabitacion.innerHTML = `
          <div class="card-body">
            <h5 class="card-title">${habitacion}</h5>
          </div>
        `;
        habitacionesEl.appendChild(cardHabitacion);
      });

      fechaIngresoEl.textContent = formatearFecha(checking.fecha_in);
      fechaSalidaEl.textContent = formatearFecha(checking.fecha_out);
      nroDiasEl.textContent = checking.nro_dias;
    }
  }

  // #region funciones para pasar de un paso a otro
  function pasarDe0a1() {
    document.querySelector(".step-0").classList.add("d-none");
    document.querySelector(".step-1").classList.remove("d-none");

    const progressBar = document.getElementById("progress-bar");
    progressBar.classList.remove("d-none");
  }

  function pasarDe1a2() {
    document.querySelector(".step-1").classList.add("d-none");
    document.querySelector(".step-2").classList.remove("d-none");

    const paso1 = document.querySelector("#progress-bar li[data-step='1']");
    const paso2 = document.querySelector("#progress-bar li[data-step='2']");

    paso1.classList.remove("is-active");
    paso1.classList.add("is-complete");
    paso2.classList.add("is-active");
  }

  function pasarDe2a3() {
    document.querySelector(".step-2").classList.add("d-none");
    document.querySelector(".step-3").classList.remove("d-none");

    const paso2 = document.querySelector("#progress-bar li[data-step='2']");
    const paso3 = document.querySelector("#progress-bar li[data-step='3']");

    paso2.classList.remove("is-active");
    paso2.classList.add("is-complete");
    paso3.classList.add("is-active");
  }
  // #endregion

  async function cargarPaises() {
    try {
      const response = await fetch(apiPaisesUrl);
      const data = await response.json();

      const selectPais = document.getElementById("pais");
      const selectNacionalidad = document.getElementById("nacionalidad");

      selectPais.innerHTML = "";
      selectNacionalidad.innerHTML = "";

      data.forEach((pais) => {
        const optionPais = document.createElement("option");
        optionPais.value = pais.id_pais;
        optionPais.textContent = pais.pais;
        selectPais.appendChild(optionPais);

        const optionNacionalidad = document.createElement("option");
        optionNacionalidad.value = pais.pais;
        optionNacionalidad.textContent = pais.pais;
        selectNacionalidad.appendChild(optionNacionalidad);
      });

      // seleccionar el pais por defecto Chile
      selectPais.value = 549;
      selectNacionalidad.value = 549;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los paises", "consultar");
    }
  }

  async function buscarEnSunat() {
    if (
      tipoComprobanteEl.value != "Factura" ||
      tipoDocumentoComprobanteEl.value != "RUC" ||
      nroDocumentoComprobanteEl.value == ""
    ) {
      return;
    }

    razonSocialEl.value = "";
    direccionComprobanteEl.value = "";
    lugarComprobanteEl.value = "";

    const url = `${apiSunatUrl}?tipo=${tipoDocumentoComprobanteEl.value}&nro=${nroDocumentoComprobanteEl.value}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      console.log(data);

      razonSocialEl.value =
        data.nombre.replace("-", "") == "" ? "" : data.nombre;
      direccionComprobanteEl.value =
        data.direccion.replace("-", "") == "" ? "" : data.direccion;
      lugarComprobanteEl.value =
        data.lugar.replace("-", "") == "" ? "" : data.lugar;
    } catch (error) {
      console.error(error);
    }
  }

  function agregarRegistro() {
    const nombreCompletoAcompanante = `${apellidoPaternoAcompananteEl.value} ${apellidoMaternoAcompananteEl.value}, ${nombresAcompananteEl.value}`;

    const acompanante = {
      apellidos_y_nombres: nombreCompletoAcompanante,
      sexo: sexoAcompananteEl.value,
      edad: edadAcompananteEl.value,
      parentesco: parentescoAcompananteEl.value,
    };

    if (acompanante.edad < 3) {
      nroPersonas.infantes += 1;
    } else if (acompanante.edad < 12) {
      nroPersonas.ninos += 1;
    } else {
      nroPersonas.adultos += 1;
    }

    actualizarNroPersonas();

    // Agregar el nuevo registro al array
    acompanantes.push(acompanante);

    // agregar al acompanante a la tabla de acompanantes
    const row = tablaAcompanantes.insertRow();
    row.innerHTML = `
        <td>${iteradorAcompanantes}</td>
        <td>${acompanante.apellidos_y_nombres}</td>
        <td>${acompanante.sexo}</td>
        <td>${acompanante.edad}</td>
        <td>${acompanante.parentesco}</td>
    `;

    iteradorAcompanantes += 1;

    // Limpiar campos de entrada después de agregar la fila
    apellidoPaternoAcompananteEl.value = "";
    apellidoMaternoAcompananteEl.value = "";
    nombresAcompananteEl.value = "";
    edadAcompananteEl.value = "";
    sexoAcompananteEl.value = "";
    parentescoAcompananteEl.value = "";
  }

  function actualizarNroPersonas() {
    nroAdultosEl.value = nroPersonas.adultos;
    nroNinosEl.value = nroPersonas.ninos;
    nroInfantesEl.value = nroPersonas.infantes;
  }

  async function finalizar() {
    const payload = {
      persona: {
        nombres: nombresEl.value,
        apellidos: `${apellidoPaternoEl.value} ${apellidoMaternoEl.value}`,
        tipo_documento: tipoDocumentoEl.value,
        nro_documento: nroDocumentoEl.value,
        lugar_de_nacimiento: lugarDeNacimientoEl.value,
        fecha: fechaNacimientoEl.value,
        ocupacion: ocupacionEl.value,
        direccion: direccionEl.value,
        ciudad: ciudadEl.value,
        celular: celularEl.value,
        email: emailEl.value,
        sexo: sexoEl.value,

        nacionalidad: nacionalidadEl.value,
        pais: paisEl.selectedOptions[0].textContent,
        id_pais: paisEl.value,
      },
      checking: {
        id_checkin: idChecking,
        nro_registro_maestro: nroRegistroMaestro,
        nro_reserva: nroReserva,
        nro_adultos: nroAdultosEl.value,
        nro_ninos: nroNinosEl.value,
        nro_infantes: nroInfantesEl.value,
        forma_pago: formaPagoEl.value,
        tipo_comprobante: tipoComprobanteEl.value,
        tipo_documento_comprobante: tipoDocumentoComprobanteEl.value,
        nro_documento_comprobante: nroDocumentoComprobanteEl.value,
        razon_social: razonSocialEl.value,
        direccion_comprobante: direccionComprobanteEl.value,
        estacionamiento: estacionamientoEl.checked ? 1 : 0,
        nro_placa: nroPlacaEl.value,
      },
      acompanantes,
    };

    const url = `${apiCheckingsUrl}/${idChecking}/visitante`;
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

      // recargar la página
      window.location.reload();
    } catch (error) {
      console.error(error);
    }
  }

  async function buscarPersona() {
    const nroDocumento = nroDocumentoEl.value;

    const url = `${apiPersonasUrl}?dni=${nroDocumento}`;

    limpiarDatosPersona();

    try {
      const response = await fetch(url);
      const data = await response.json();

      nombresEl.value = data.nombres;
      apellidoPaternoEl.value = data.apellidos.split(" ")[0];
      apellidoMaternoEl.value = data.apellidos.split(" ")[1];
      lugarDeNacimientoEl.value = data.lugar_de_nacimiento;
      fechaNacimientoEl.value = data.fecha;
      nacionalidadEl.value = data.nacionalidad;
      sexoEl.value = data.sexo;
      ocupacionEl.value = data.ocupacion;
      direccionEl.value = data.direccion;
      ciudadEl.value = data.ciudad;
      paisEl.value = data.id_pais;
      celularEl.value = data.celular;
      emailEl.value = data.email;
    } catch (error) {
      console.error(error);
    }
  }

  function limpiarDatosPersona() {
    nombresEl.value = "";
    apellidoPaternoEl.value = "";
    apellidoMaternoEl.value = "";
    lugarDeNacimientoEl.value = "";
    fechaNacimientoEl.value = "";
    nacionalidadEl.value = "";
    sexoEl.value = "";
    ocupacionEl.value = "";
    direccionEl.value = "";
    ciudadEl.value = "";
    paisEl.value = "";
    celularEl.value = "";
    emailEl.value = "";
  }

  function alCambiarTipoComprobante() {
    if (tipoComprobanteEl.value == "Boleta") {
      tipoDocumentoComprobanteEl.value = "DNI";
      nroDocumentoComprobanteEl.value = nroDocumentoEl.value;
      razonSocialEl.value = `${apellidoPaternoEl.value} ${apellidoMaternoEl.value}, ${nombresEl.value}`;
      direccionComprobanteEl.value = direccionEl.value;
      lugarComprobanteEl.value = ciudadEl.value;
    } else {
      tipoDocumentoComprobanteEl.value = "";
      nroDocumentoComprobanteEl.value = "";
      razonSocialEl.value = "";
      direccionComprobanteEl.value = "";
      lugarComprobanteEl.value = "";
    }
  }

  window.addEventListener("load", wrapper);
</script>
<?php } ?>

<?php
require "../../inc/footer.php";
?>
