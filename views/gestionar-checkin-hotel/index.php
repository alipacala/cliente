<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); ?>

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
            <label for="validationCustom02" class="form-label"
              >Apellidos:</label
            >
            <input type="text" class="form-control" id="apellido" required />
          </div>
          <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Nombres:</label>
            <input type="text" class="form-control" id="nombre" required />
          </div>
          <div class="col-md-4">
            <label for="validationCustom04" class="form-label">Edad:</label>
            <input type="text" class="form-control" id="edad2" required />
          </div>
          <div class="col-md-4">
            <label for="validationCustom03" class="form-label"
              >Parentesco:</label
            >
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
            <label for="validationCustom03" class="form-label">Sexo:</label>
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
          onclick="agregarRegistro()"
        >
          Agregar
        </button>
      </div>
    </div>
  </div>
</div>

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
          <label for="validationCustom02" class="form-label"
            >Nro. Registro:</label
          >
          <input type="text" class="form-control" id="nro_registro" readonly />
        </div>
        <div class="col-md-6">
          <label for="validationCustom02" class="form-label"
            >Nro. Reserva:</label
          >
          <input type="text" class="form-control" id="nro_reserva" readonly />
        </div>
        <div class="col-md-4">
          <label for="validationCustom04" class="form-label"
            >Tipo Documento:</label
          >
          <select class="form-select" id="tipo_documento">
            <option value="0">--Seleccione--</option>
            <option value="1">DNI</option>
            <option value="2">PASAPORTE</option>
            <option value="3">CEDULA DE IDENTIFICACION</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="validationCustom02" class="form-label">Buscar DNI</label>
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
        <div class="col-md-4">
          <label for="validationCustom04" class="form-label">Apellidos:</label>
          <input type="text" class="form-control" id="apellidos" required />
        </div>
        <div class="col-md-4">
          <label for="validationCustom03" class="form-label">Nombres:</label>
          <input type="text" class="form-control" id="nombres" required />
        </div>
        <div class="col-md-4">
          <label for="validationCustom01" class="form-label"
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
          <label for="validationCustom02" class="form-label"
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
          <label for="validationCustom02" class="form-label">Edad:</label>
          <input type="number" class="form-control" id="edad" required />
        </div>
        <div class="col-md-4">
          <label for="validationCustom03" class="form-label">Sexo:</label>
          <select class="form-select" id="sexo">
            <option value="0">--Seleccione--</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
            <option value="O">Otros</option>
          </select>
        </div>
        <div class="col-md-12">
          <label for="hora_de_ingreso">Ocupacion:</label>
          <textarea
            name="ocupacion"
            id="ocupacion"
            class="form-control"
          ></textarea>
        </div>
        <div class="col-md-6">
          <label for="hora_de_salida">Direccion:</label>
          <input
            type="text"
            class="form-control"
            id="direccion"
            name="direccion"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="activo">Ciudad:</label>
          <input
            type="text"
            class="form-control"
            id="ciudad"
            name="ciudad"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="hora_de_salida">Celular:</label>
          <input
            type="text"
            class="form-control"
            id="celular"
            name="celular"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="activo">Email:</label>
          <input type="text" class="form-control" id="email" name="email" />
        </div>
        <div class="col-md-12">
          <label for="validationCustom04" class="form-label"
            >Requiere Estacionamiento No/Si</label
          >
          <select class="form-select" id="estacionamiento">
            <option value="0">No</option>
            <option value="1">Sí</option>
          </select>
        </div>
        <div class="col-md-12">
          <label for="hora_de_salida">Nro. Placa:</label>
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
          <label for="activo">Fecha IN:</label>
          <input
            type="date"
            class="form-control"
            id="fecha_in"
            name="fecha_in"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="activo">Hora IN</label>
          <input
            type="time"
            class="form-control"
            id="hora_in"
            name="hora_in"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="activo">Fecha OUT:</label>
          <input
            type="date"
            class="form-control"
            id="fecha_out"
            name="fecha_out"
            required
          />
        </div>
        <div class="col-md-6">
          <label for="activo">Hora OUT</label>
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
        <div class="col-md-4">
          <label for="activo">Nro Adultos:</label>
          <input
            type="number"
            class="form-control"
            id="nro_adultos"
            name="nro_adultos"
            value="0"
            required
          />
        </div>
        <div class="col-md-4">
          <label for="activo">Nro Niños</label>
          <input
            type="number"
            class="form-control"
            id="nro_nino"
            name="nro_nino"
            value="0"
          />
        </div>
        <div class="col-md-4">
          <label for="activo">Nro Infantes</label>
          <input
            type="number"
            class="form-control"
            id="nro_infantes"
            name="nro_infantes"
            value="0"
          />
        </div>
        <button
          type="button"
          class="btn btn-primary"
          data-bs-toggle="modal"
          data-bs-target="#myModal"
        >
          <strong>+</strong>Agregar Acompañante</button
        ><br /><br />
        <div class="row">
          <div class="col-sm-12 mb-3 mb-sm-0">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">LISTA DE ACOMPAÑANTES</h5>
                <table class="table" id="tabla-acompanantes">
                  <thead>
                    <tr>
                      <th>Apellido y Nombres</th>
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
                    <label for="activo">Forma de Pago:</label>
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
                    <label for="activo">Tipo de Comprobante:</label>
                    <select class="form-select" id="tipo_comprobante">
                      <option value="Ninguno">--Seleccione--</option>
                      <option value="Boleta">Boleta</option>
                      <option value="Factura">Factura</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="activo">Tipo Documento Comprobante:</label>
                    <select class="form-select" id="tipo_documento_comprobante">
                      <option value="0">--Seleccione--</option>
                      <option value="1">DNI</option>
                      <option value="2">RUC</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="validationCustom02" class="form-label"
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
                    <label for="activo">Razon Social:</label>
                    <input
                      type="text"
                      class="form-control"
                      id="razon_social"
                      name="razon_social"
                    />
                  </div>
                  <div class="col-md-4">
                    <label for="activo">Direccion:</label>
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

<script>
  const apiAcompanantesUrl = "<?php echo URL_API_NUEVA ?>/acompanantes";
  const apiCheckingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";

  let fechas = [];
  let objfechas = [];

  function generar_objetofechas() {
    let fechas = [];
    let fechaIn = document.getElementById("fecha_in").value;
    let fechaOut = document.getElementById("fecha_out").value;
    let currentDate = new Date(fechaIn);
    // Convierte las fechas en objetos Date
    var dateIn = new Date(fechaIn);
    var dateOut = new Date(fechaOut);

    // Obtiene la fecha actual
    var fechaActual = new Date();
    // Verifica si fecha_out es mayor que la fecha actual antes de continuar
    if (dateOut <= fechaActual) {
      alert("La fecha de salida debe ser mayor que la fecha actual.");
      return null; // Retorna null para indicar que no se pudo generar el objeto
    }
    while (currentDate < new Date(fechaOut)) {
      fechas.push({ fecha: currentDate.toISOString().split("T")[0] });
      currentDate.setDate(currentDate.getDate() + 1);
    }
    var codigo = document.getElementById("id_checkin").value;
    // Enviar las fechas al servidor
    fetch("<?php echo URL_API_CARLITOS ?>/api-rooming.php", {
      method: "FECHA",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        codigo: codigo, // Reemplaza 'tu_codigo' con el valor correcto
        fechasJson: fechas,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
      })
      .catch((error) => console.error("Error:", error));
  }
</script>
<script>
  // #region referencias a elementos del DOM
  let formChecking = null;
  // #endregion

  async function wrapper() {
    const checkinId = new URLSearchParams(window.location.search).get(
      "id_checkin"
    );
    const idCheckin = document.getElementById("id_checkin");
    idCheckin.value = checkinId;

    guardarReferencias();

    await cargarDatos();
    await cargarAcompanantes();
  }

  function guardarReferencias() {
    formChecking = document.getElementById("form-checking");
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

      const selectedOption =
        formChecking.habitacion.options[formChecking.habitacion.selectedIndex];
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
      document.getElementById("apellidos").value = data[0].apellidos;
      document.getElementById("tipo_documento").value = data[0].tipo_documento;
      document.getElementById("ciudad").value = data[0].lugar_procedencia;
      document.getElementById("celular").value = data[0].telefono;
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

      // Iterar sobre las opciones y deseleccionarlas todas
      selectedOption.textContent = data[0].nro_habitacion;
      //console.log(data);
    } catch (error) {
      console.error("Error:", error);
    }
  }

  async function cargarAcompanantes() {
    const nroRegistro = document.getElementById("nro_registro").value;
    const url = `${apiAcompanantesUrl}?nro_registro_maestro=${nroRegistro}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      console.log(data);
      // actualizar la tabla de acompanantes
      const tablaAcompanantes =
        document.getElementById("tabla-acompanantes").tBodies[0];
      tablaAcompanantes.innerHTML = "";

      data.forEach((acompanante) => {
        const row = tablaAcompanantes.insertRow();
        row.innerHTML = `
          <td>${acompanante.apellidos_y_nombres}</td>
          <td>${acompanante.edad}</td>
          <td>${acompanante.sexo}</td>
          <td>${acompanante.parentesco ? acompanante.parentesco : "TITULAR"}</td>
          <td>
            <button class="btn btn-danger" onclick="eliminarAcompanante(event, ${acompanante.id_acompanante})">
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

  // Llamar a la función cargarDatos cuando la página se cargue
  window.addEventListener("load", wrapper);
</script>
<script>
  function BuscarReniec() {
    // Obtén los valores de los campos de entrada
    var tipoDocumento = document.getElementById(
      "tipo_documento_comprobante"
    ).value;
    var numeroDocumento = document.getElementById(
      "nro_documento_comprobante"
    ).value;
    var direccion_comprobante = document.getElementById(
      "direccion_comprobante"
    );
    var razon_social = document.getElementById("razon_social");

    razon_social.value = "";
    direccion_comprobante.value = "";
    // Verifica si ambos campos están llenos
    if (tipoDocumento === "0" || numeroDocumento.trim() === "") {
      alert(
        "Por favor, complete los campos de Tipo Documento Comprobante y Nro DNI/RUC antes de buscar."
      );
      return; // No continúes con la búsqueda si falta información.
    }

    // Construye la URL con los valores
    var url =
      "<?php echo URL_API_CARLITOS ?>/api-reniec.php?tipo=" +
      tipoDocumento +
      "&doc=" +
      numeroDocumento;

    // Realiza la solicitud fetch
    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error al buscar en Reniec");
        }
        return response.json();
      })
      .then((data2) => {
        // Muestra la respuesta en el console.log
        //console.log(data2);

        direccion_comprobante.value = data2.direccion || "";
        razon_social.value = data2.nombre || "";
        // Aquí puedes realizar cualquier otro procesamiento que necesites con los datos.
      })
      .catch((error) => {
        console.error("Error al buscar en Reniec:", error);
        // Maneja el error si ocurre algún problema durante la solicitud.
      });
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
  fetch("<?php echo URL_API_CARLITOS ?>/api-reservas.php", {
    method: "INNER",
  })
    .then((response) => response.json())
    .then((productosData) => {
      llenarSelect(productosData);
    })
    .catch((error) =>
      console.error("Error al obtener los datos del API:", error)
    );
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
    if (contador === 0) {
      // Obtener valores de los campos de entrada fuera de la función
      var apellidos2 = document.getElementById("apellidos").value;
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

      // Actualizar la tabla
      actualizarTabla();
    }

    // Obtener valores de los campos de entrada
    var apellidos = document.getElementById("apellido").value;
    var nombres = document.getElementById("nombre").value;
    var edad = document.getElementById("edad2").value;
    var parentesco = document.getElementById("parentesco").value;
    var sexo = document.getElementById("sexo2").value;
    var nombre_completo = apellidos + ", " + nombres;

    // Crear un objeto para representar el registro
    var nuevoRegistro = {
      nombre: nombre_completo,
      edad: edad,
      sexo: sexo,
      parentesco: parentesco,
    };

    // Agregar el nuevo registro al array
    registros.push(nuevoRegistro);

    // Actualizar la tabla
    actualizarTabla();
    contador += 1;
    // Limpiar campos de entrada después de agregar la fila
    document.getElementById("apellido").value = "";
    document.getElementById("nombre").value = "";
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
      .addEventListener("submit", function (event) {
        event.preventDefault();
        const habitacionInput = document.getElementById("habitacion");
        const selectedOption =
          habitacionInput.options[habitacionInput.selectedIndex];
        // recibe todos los datos del formulario
        if (registros.length === 0) {
          var apellidos2 = document.getElementById("apellidos").value;
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
            apellidos: document.getElementById("apellidos").value,
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
            apellidos: document.getElementById("apellidos").value,
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

        /* const payload = {};

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
          nro_nino: formData.nro_nino,
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

        payload.acompanantes = formData.acompanantes; */

        // borrar el acompañante 0
        formData.acompanantes.shift();

        // agrega un acompañante 0 con los datos del formulario
        formData.acompanantes.unshift({
          nombre: `${document.getElementById("apellidos").value}, ${
            document.getElementById("nombres").value
          }`,
          edad: document.getElementById("edad").value,
          sexo: document.getElementById("sexo").value,
          parentesco: null,
        });

        generar_objetofechas();

        //Make a fetch POST request to your PHP API endpoint
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
            })
            .catch((error) => {
              console.error(error);
            });
        } else {
          fetch("<?php echo URL_API_CARLITOS ?>/api-huespedes.php", {
            method: "PUT",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
          })
            .then((response) => response.json())
            .then((data) => {
              console.log(data);
            })
            .catch((error) => {
              console.error(error);
            });
        }
      });
  });
</script>
<script>
  function Buscar() {
    let codigo = document.getElementById("nro_documento").value;
    buscarUsuarios(codigo);
    document.getElementById("nombres").value = "";
    document.getElementById("apellidos").value = "";
  }
  function buscarUsuarios(codigo) {
    const apiUrl1 = `<?php echo URL_API_CARLITOS ?>/api-personanaturaljuridica.php?codigo=${codigo}`;
    const apiUrl2 = `<?php echo URL_API_CARLITOS ?>/api-terapistas.php?codigo=${codigo}`;

    // Solicitud a la primera API
    fetch(apiUrl1)
      .then((response) => response.json())
      .then((dataApi1) => {
        // Procesar los datos de la primera API
        if (dataApi1.length > 0) {
          console.log("Datos de API 2:");
          console.log(dataApi1);
          // Procesar los datos de la segunda API
          if (dataApi1.length > 0) {
            const primerUsuarioApi1 = dataApi1[0];
            document.getElementById("nombres").value =
              primerUsuarioApi1.nombres;
            document.getElementById("apellidos").value =
              primerUsuarioApi1.apellidos;
            document.getElementById("id_persona").value =
              primerUsuarioApi1.id_persona;
          } else {
            document.getElementById("nombres").value = "";
            document.getElementById("apellidos").value = "";
            document.getElementById("id_persona").value = "";
          }
        } else {
          console.log("No se encontraron datos en API 2.");
        }
      })
      .catch((error) => {});

    // Solicitud a la segunda API
    fetch(apiUrl2)
      .then((response) => response.json())
      .then((dataApi2) => {
        // Procesar los datos de la segunda API
        if (dataApi2.length > 0) {
          console.log("Datos de API 5:");
          console.log(dataApi2);
          // Procesar los datos de la segunda API
          if (dataApi2.length > 0) {
            const primerUsuarioApi2 = dataApi2[0];
            document.getElementById("nombres").value =
              primerUsuarioApi2.nombres;
            document.getElementById("apellidos").value =
              primerUsuarioApi2.apellidos;
            document.getElementById("id_persona").value =
              primerUsuarioApi2.id_profesional;
          } else {
            document.getElementById("nombres").value = "";
            document.getElementById("apellidos").value = "";
            document.getElementById("id_persona").value = "";
          }
        } else {
          console.log("No se encontraron datos en API 2.");
        }
      })
      .catch((error) => {
        console.error("Error al obtener datos de la segunda API:", error);
      });
  }
</script>

<?php
require "../../inc/footer.php";
?>
