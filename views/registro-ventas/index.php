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
    <div class="card-header py-3">
      <h2 class="text-center">REGISTRO DE VENTAS</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-3">
          FECHA: <input type="date" class="form-control" id="fecha" value="<?php echo date("Y-m-d"); ?>"/>
        </div>
        <div class="col-md-3">
          MES:
          <select id="mes" class="form-select">
            <option value="0">Solo fecha</option>
            <option value="1">ENERO</option>
            <option value="2">FEBRERO</option>
            <option value="3">MARZO</option>
            <option value="4">ABRIL</option>
            <option value="5">MAYO</option>
            <option value="6">JUNIO</option>
            <option value="7">JULIO</option>
            <option value="8">AGOSTO</option>
            <option value="9">SETIEMBRE</option>
            <option value="10">OCTUBRE</option>
            <option value="11">NOVIEMBRE</option>
            <option value="12">DICIEMBRE</option>
          </select>
        </div>
        <div class="col-md-2">
          AÑO:
          <select id="anio" class="form-select">
            <option value="2023" selected>2023</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
          </select>
        </div>
        <div class="col-md-2 my-auto">
          <div class="form-check form-switch">
            <input
              class="form-check-input"
              type="checkbox"
              id="solo-bol-fact"
            />
            <label class="form-check-label" for="solo-bol-fact">
              Solo BOL/FACT
            </label>
          </div>
        </div>
        <div class="col-md-2">
          <button
            class="btn btn-outline-primary w-100 mt-3"
            id="btn-buscar"
            onclick="buscarComprobantes()"
          >
            <i class="fas fa-search"></i> Buscar
          </button>
        </div>
      </div>

      <h5>Registro de ventas</h5>

      <div class="table-container">
        <table id="tabla-comprobantes" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center" style="min-width: 100px">FECHA</th>
              <th class="text-center">TIPO DOC</th>
              <th class="text-center">NRO COMPROBANTE</th>
              <th class="text-center">NOMBRE</th>
              <th class="text-center">DNI/RUC</th>
              <th class="text-center" style="min-width: 100px">MONTO</th>
              <th class="text-center">ESTADO</th>
              <th class="text-center">USUARIO REG.</th>
              <th class="text-center">FUNCIÓN</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <button
        class="btn btn-outline-secondary"
        onclick="imprimirRegistroVentas(event)"
      >
        <i class="fas fa-print"></i> Imprimir
      </button>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-confirmar-anulado"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modal-confirmar-anulado-label"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-confirmar-anulado-label">
          Detalles del Comprobante
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
        <div class="row w-75 mx-auto">
          <div class="col-md-12">
            <label for="nro-voucher">Cliente:</label>
            <input type="text" class="form-control" id="cliente" disabled />
          </div>
          <div class="col-md-12">
            <label for="nro-voucher">Nro Comprobante:</label>
            <input
              type="text"
              class="form-control"
              id="nro-comprobante"
              disabled
            />
          </div>
          <div class="col-md-12">
            <label for="nro-voucher">Fecha:</label>
            <input
              type="text"
              class="form-control"
              id="fecha-confirmar"
              disabled
            />
          </div>
          <div class="col-md-12">
            <label for="nro-voucher">Monto TOTAL:</label>
            <input type="text" class="form-control" id="monto-total" disabled />
          </div>
        </div>

        <form id="form-confirmar-anulado">
          <div class="row w-75 mx-auto p-3 border border-1 rounded-2 mt-3">
            <div class="col-md-12 mb-3">
              <label for="nro-voucher">Ingrese Usuario:</label>
              <input type="text" class="form-control" id="usuario" required />
            </div>
            <div class="col-md-12 mb-3">
              <label for="nro-voucher">Ingrese contraseña:</label>
              <input
                type="password"
                class="form-control"
                id="contraseña"
                required
              />
            </div>
            <input
              type="submit"
              class="btn btn-danger me-auto mb-3"
              id="anular-comprobante"
              value="ANULAR"
            />
            <button
              class="btn btn-outline-secondary mb-3"
              data-bs-dismiss="modal"
            >
              SALIR
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  const apiComprobantesVentasUrl =
    "<?php echo URL_API_NUEVA ?>/comprobantes-ventas";
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";

  let modal;

  async function wrapper() {
    modal = new bootstrap.Modal(
      document.getElementById("modal-confirmar-anulado")
    );

    prepararFormulario();
  }

  function imprimirRegistroVentas(event) {
    event.preventDefault();
    const url = `${apiReportesUrl}?tipo=registro-ventas&${prepararUrlParams()}`;
    open(url, "_blank");
  }

  function agregarFilaTotal() {
    const tbody = document.getElementById("tabla-comprobantes").tBodies[0];
    const row = tbody.insertRow();

    const cell = row.insertCell();
    cell.colSpan = 4;

    const total = row.insertCell();
    total.innerHTML = "TOTAL";

    const montoTotal = row.insertCell();
    montoTotal.innerHTML = `S/ ${calcularTotal()}`;
    montoTotal.classList.add("text-end");

    const celdaVacia = row.insertCell();
    celdaVacia.colSpan = 3;
  }

  function calcularTotal() {
    const tbody = document.getElementById("tabla-comprobantes").tBodies[0];
    const rows = tbody.querySelectorAll("tr:not(:last-child)");

    let total = 0;

    rows.forEach((row) => {
      console.log(row.dataset.monto);
      total += parseFloat(row.dataset.monto);
    });

    return formatearCantidad(total);
  }

  function formatearCantidad(numero) {
    const numeroFormateado = parseFloat(numero).toFixed(2);
    const partes = numeroFormateado.toString().split(".");
    partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return partes.join(".");
  }

  function prepararFormulario() {
    const formAnularComprobante = document.getElementById(
      "form-confirmar-anulado"
    );

    formAnularComprobante.addEventListener("submit", (event) => {
      event.preventDefault();
      anularComprobante();
    });
  }

  function prepararUrlParams() {
    const fecha = document.getElementById("fecha").value;
    const mes = document.getElementById("mes").value;
    const anio = document.getElementById("anio").value;
    const soloBolFact = document.getElementById("solo-bol-fact").checked;
    const usuario = '<?php echo $_SESSION["usuario"]["id_usuario"] ?>';

    let urlParams;

    if (mes === "0") {
      urlParams = `fecha=${fecha}`;
    } else {
      urlParams = `mes=${mes}&anio=${anio}`;
    }

    if (soloBolFact) {
      urlParams += `${soloBolFact ? "&solo_bol_fact" : ""}`;
    }

    urlParams += `&id_usuario=${usuario}`;

    return urlParams;
  }

  async function buscarComprobantes() {
    const url = apiComprobantesVentasUrl + "?" + prepararUrlParams();

    await cargarComprobantes(url);
    agregarFilaTotal();
  }

  async function cargarComprobantes(url) {
    console.log(url);
    try {
      const response = await fetch(url);
      const comprobantes = await response.json();

      limpiarTabla();

      const tbody = document.getElementById("tabla-comprobantes").tBodies[0];
      comprobantes.forEach((comprobante) => {
        const row = tbody.insertRow();

        row.dataset.id = comprobante.id;
        row.dataset.nombre = comprobante.nombre;
        row.dataset.nro_comprobante = comprobante.nro_comprobante;
        row.dataset.fecha = comprobante.fecha;
        row.dataset.monto = comprobante.monto;

        const fecha = row.insertCell();
        fecha.innerHTML = comprobante.fecha;

        const tipoDoc = row.insertCell();
        tipoDoc.innerHTML = comprobante.tipo_doc;

        const nroComprobante = row.insertCell();
        nroComprobante.innerHTML = comprobante.nro_comprobante;

        const nombre = row.insertCell();
        nombre.innerHTML = comprobante.nombre;

        const dniRuc = row.insertCell();
        dniRuc.innerHTML = comprobante.dni_ruc;

        const monto = row.insertCell();
        monto.classList.add("text-end");
        monto.innerHTML = formatearCantidad(comprobante.monto);

        const estado = row.insertCell();
        estado.innerHTML = comprobante.estado;

        const usuarioReg = row.insertCell();
        usuarioReg.innerHTML = comprobante.usuario_reg;

        const funcion = row.insertCell();

        if (comprobante.estado != "ANULADO") {
          funcion.innerHTML = `
          <button
          class="btn btn-outline-danger"
          onclick="mostrarModalConfirmarAnulado(event)"
          >
          <i class="fas fa-x"></i>
          </button>
          `;
        }
      });
    } catch (error) {
      console.error("No se pudo cargar los comprobantes de venta: ", error);
    }
  }

  function mostrarModalConfirmarAnulado(event) {
    const row = event.target.closest("tr");

    const cliente = document.getElementById("cliente");
    const nroComprobante = document.getElementById("nro-comprobante");
    const fecha = document.getElementById("fecha-confirmar");
    const montoTotal = document.getElementById("monto-total");
    const form = document.getElementById("form-confirmar-anulado");

    cliente.value = row.dataset.nombre;
    nroComprobante.value = row.dataset.nro_comprobante;
    fecha.value = row.dataset.fecha;
    montoTotal.value = row.dataset.monto;
    form.dataset.id = row.dataset.id;

    modal.show();
  }

  async function anularComprobante() {
    const id = document.getElementById("form-confirmar-anulado").dataset.id;
    const usuario = document.getElementById("usuario").value;
    const clave = document.getElementById("contraseña").value;

    // limpiar formulario
    document.getElementById("usuario").value = "";
    document.getElementById("contraseña").value = "";

    const url = `${apiComprobantesVentasUrl}/${id}/anular`;

    const body = {
      usuario,
      clave,
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

      alert(data.mensaje);
      buscarComprobantes();

      modal.hide();
    } catch (error) {
      console.error("No se pudo anular el comprobante: ", error);
    }
  }

  function limpiarTabla() {
    const tbody = document.getElementById("tabla-comprobantes").tBodies[0];
    tbody.innerHTML = "";
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
