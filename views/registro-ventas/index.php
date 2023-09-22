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
        <div class="col-md-2">
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
              <th class="text-center">FECHA</th>
              <th class="text-center">TIPO DOC</th>
              <th class="text-center">NRO COMPROBANTE</th>
              <th class="text-center">NOMBRE</th>
              <th class="text-center">DNI/RUC</th>
              <th class="text-center">MONTO</th>
              <th class="text-center">ESTADO</th>
              <th class="text-center">USUARIO REG.</th>
              <th class="text-center">FUNCIÓN</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  const apiComprobantesVentasUrl =
    "<?php echo URL_API_NUEVA ?>/comprobantes-ventas";

  async function wrapper() {}

  function buscarComprobantes() {
    const fecha = document.getElementById("fecha").value;
    const mes = document.getElementById("mes").value;
    const anio = document.getElementById("anio").value;
    const soloBolFact = document.getElementById("solo-bol-fact").checked;

    let url;

    if (mes === "0") {
      url = `${apiComprobantesVentasUrl}?fecha=${fecha}`;
    } else {
      url = `${apiComprobantesVentasUrl}?mes=${mes}&anio=${anio}`;
    }

    if (soloBolFact) {
      url += `${soloBolFact ? "&solo_bol_fact" : ""}`;
    }

    cargarComprobantes(url);
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
        monto.innerHTML = comprobante.monto;

        const estado = row.insertCell();
        estado.innerHTML = comprobante.estado;

        const usuarioReg = row.insertCell();
        usuarioReg.innerHTML = comprobante.usuario_reg;

        const funcion = row.insertCell();
        funcion.innerHTML = `
          <button
            class="btn btn-outline-danger"
            onclick="anularComprobante(${comprobante.id})"
          >
            <i class="fas fa-x"></i>
          </button>
        `;
      });
    } catch (error) {
      console.error("No se pudo cargar los comprobantes de venta: ", error);
    }
  }

  async function anularComprobante(id) {
    const url = `${apiComprobantesVentasUrl}/${id}`;

    const options = {
      method: "DELETE",
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      alert(data.mensaje);
      buscarComprobantes();
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
