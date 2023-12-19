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
      <h2 class="text-center">CAMBIO DE PRECIO</h2>
    </div>
    <div class="card-body">
      GRUPO:
      <select
        id="grupo"
        name="grupo"
        class="form-select mb-3"
        onchange="cargarProductos()"
      ></select>

      <div class="table-responsive">
        <table id="tabla-listado-catalogo" class="table table-hover">
          <thead>
            <tr>
              <th style="background-color: lightsalmon">Grupo</th>
              <th style="background-color: lightsalmon" colspan="8">
                SubGrupo
              </th>
              <th style="background-color: lightblue" colspan="4">
                PRECIO VENTA
              </th>
            </tr>
            <tr>
              <th style="min-width: 180px">Nombre del producto</th>
              <th style="min-width: 90px">Precio de Costo</th>
              <th style="min-width: 90px">Mano de obra</th>
              <th>Costos adicionales</th>
              <th>Costo</th>
              <th style="min-width: 90px">% Margen</th>
              <th>Valor Venta</th>
              <th>IGV</th>
              <th>Precio Sugerido</th>
              <th style="min-width: 120px">PRECIO LISTA</th>
              <th style="min-width: 120px">PRECIO CORP.</th>
              <th style="min-width: 120px">PRECIO ESPECIAL</th>
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  const apiGruposUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";

  let gruposCargados = [];

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarGrupos();
  }

  async function cargarGrupos() {
    try {
      const response = await fetch(apiGruposUrl);
      const data = await response.json();

      const select = document.getElementById("grupo");

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione un grupo";
      select.appendChild(defaultOption);

      let grupos = data;
      grupos = ordenarGrupos(grupos);

      data.forEach((element) => {
        const option = crearOpcionClasificacionVentas(element);
        select.appendChild(option);
      });

      gruposCargados = data;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los grupos", "consultar");
    }
  }

  function ordenarGrupos(grupos) {
    // ordenar los grupos por nro_orden
    grupos.sort((a, b) => {
      if (+a.nro_orden > +b.nro_orden) {
        return 1;
      } else if (+a.nro_orden < +b.nro_orden) {
        return -1;
      } else {
        return 0;
      }
    });

    // mapear a un array de grupos con un campo array de subgrupos
    grupos = grupos
      .filter((grupo) => grupo.codigo_grupo == grupo.codigo_subgrupo)
      .map((grupo) => {
        grupo.subgrupos = grupos.filter(
          (subgrupo) =>
            subgrupo.codigo_grupo == grupo.codigo_grupo &&
            subgrupo.codigo_subgrupo != subgrupo.codigo_grupo
        );
        return grupo;
      });

    // flat array de grupos y subgrupos
    return grupos.flatMap((grupo) => {
      return [grupo, ...grupo.subgrupos];
    });
  }

  function crearOpcionClasificacionVentas(clasificacionVentas) {
    const option = document.createElement("option");
    option.value = clasificacionVentas.id_grupo;

    if (
      clasificacionVentas.codigo_grupo == clasificacionVentas.codigo_subgrupo
    ) {
      option.textContent = clasificacionVentas.nombre_grupo;
      option.classList.add("fw-bold");
    } else {
      option.style.color = "#6c757d";
      option.innerHTML =
        "&nbsp;&nbsp;&nbsp;&nbsp;" + clasificacionVentas.nombre_grupo;
    }

    return option;
  }

  async function cargarProductos() {
    limpiarTabla();

    const idGrupo = document.getElementById("grupo").value;

    if (idGrupo === "") return;

    const grupoSeleccionado = gruposCargados.find(
      (grupo) => grupo.id_grupo == idGrupo
    );

    const esSubgrupo =
      grupoSeleccionado.codigo_grupo != grupoSeleccionado.codigo_subgrupo;

    // filtrar los subgrupos del grupo seleccionado
    const subgrupos = gruposCargados.filter((grupo) => {
      const mismoGrupo = grupo.codigo_grupo === grupoSeleccionado.codigo_grupo;
      const mismoGrupoySubgrupo =
        grupo.codigo_subgrupo === grupoSeleccionado.codigo_subgrupo &&
        mismoGrupo;
      return esSubgrupo ? mismoGrupoySubgrupo : mismoGrupo;
    });

    const url = `${apiProductosUrl}?grupos=${subgrupos.map(
      (subgrupo) => subgrupo.id_grupo
    )}`;

    if (idGrupo === "") {
      limpiarTabla();
      return;
    }

    try {
      const response = await fetch(url);
      const data = await response.json();

      const tbody = document.getElementById("tabla-listado-catalogo")
        .tBodies[0];
      tbody.innerHTML = "";

      const productos = data.filter(
        (producto) =>
          producto.id_tipo_de_producto == 12 ||
          producto.id_tipo_de_producto == 13
      );

      // agrupar los productos por subgrupo
      const productosPorSubgrupo = subgrupos.map((subgrupo) => {
        return {
          subgrupo: subgrupo.nombre_grupo,
          productos: productos.filter(
            (producto) => producto.id_grupo == subgrupo.id_grupo
          ),
        };
      });

      productosPorSubgrupo.forEach((subgrupo) => {
        // imprime una fila con el grupo y subgrupo
        const trSubgrupo = tbody.insertRow();

        const tdGrupo = trSubgrupo.insertCell();
        tdGrupo.classList.add("fw-bold");
        tdGrupo.style.backgroundColor = "lightsalmon";
        tdGrupo.innerText = grupoSeleccionado.nombre_grupo;

        const tdSubgrupo = trSubgrupo.insertCell();
        tdSubgrupo.classList.add("text-start", "fw-bold");
        tdSubgrupo.style.backgroundColor = "lightsalmon";
        tdSubgrupo.colSpan = 8;
        tdSubgrupo.innerText = subgrupo.subgrupo;

        const tdPrecioVenta = trSubgrupo.insertCell();
        tdPrecioVenta.colSpan = 4;
        tdPrecioVenta.style.backgroundColor = "lightblue";

        // imprime las filas con los productos de cada subgrupo

        subgrupo.productos.forEach((producto) => {
          const tr = tbody.insertRow();
          tr.dataset.idProducto = producto.id_producto;

          const tdNombreProducto = tr.insertCell();
          tdNombreProducto.classList.add("nombre_producto", "align-middle");
          tdNombreProducto.innerText = producto.nombre_producto;

          const tdPrecioCosto = tr.insertCell();
          tdPrecioCosto.classList.add("costo_unitario", "align-middle");
          tdPrecioCosto.innerText = producto.costo_unitario ?? 0;

          const tdManoDeObra = tr.insertCell();
          tdManoDeObra.classList.add("align-middle");
          const inputManoDeObra = document.createElement("input");
          inputManoDeObra.classList.add("form-control", "costo_mano_de_obra");
          inputManoDeObra.value = producto.costo_mano_de_obra ?? 0;
          tdManoDeObra.appendChild(inputManoDeObra);

          inputManoDeObra.addEventListener("change", (e) =>
            alCambiarUnNumero(e.target.closest("tr"))
          );

          const tdCostosAdicionales = tr.insertCell();
          tdCostosAdicionales.classList.add("align-middle");
          const inputCostosAdicionales = document.createElement("input");
          inputCostosAdicionales.classList.add(
            "form-control",
            "costos_adicionales"
          );
          inputCostosAdicionales.value = producto.costo_adicional ?? 0;
          tdCostosAdicionales.appendChild(inputCostosAdicionales);

          inputCostosAdicionales.addEventListener("change", (e) =>
            alCambiarUnNumero(e.target.closest("tr"))
          );

          const tdCosto = tr.insertCell();
          tdCosto.classList.add("costo", "align-middle");

          const tdMargen = tr.insertCell();
          tdMargen.classList.add("align-middle");
          const inputMargen = document.createElement("input");
          inputMargen.classList.add("form-control", "porcentaje_margen");
          inputMargen.value = producto.porcentaje_margen ?? 0;
          tdMargen.appendChild(inputMargen);

          inputMargen.addEventListener("change", (e) =>
            alCambiarUnNumero(e.target.closest("tr"))
          );

          const tdPrecioVenta = tr.insertCell();
          tdPrecioVenta.classList.add("precio_venta", "align-middle");
          tdPrecioVenta.innerText = producto.precio_venta ?? 0;

          const tdIGV = tr.insertCell();
          tdIGV.classList.add("igv", "align-middle");
          tdIGV.innerText = 0;

          const tdPrecioFinal = tr.insertCell();
          tdPrecioFinal.classList.add("precio_final", "align-middle");
          tdPrecioFinal.innerText = 0;

          const tdPrecioVentaLista = tr.insertCell();
          tdPrecioVentaLista.classList.add("align-middle");
          const inputPrecioVentaLista = document.createElement("input");
          inputPrecioVentaLista.classList.add(
            "form-control",
            "precio_venta_lista",
            "align-middle"
          );
          inputPrecioVentaLista.value = producto.precio_venta_01 ?? 0;
          tdPrecioVentaLista.appendChild(inputPrecioVentaLista);

          const tdPrecioCorporativo = tr.insertCell();
          tdPrecioCorporativo.classList.add("align-middle");
          const inputPrecioCorporativo = document.createElement("input");
          inputPrecioCorporativo.classList.add(
            "form-control",
            "precio_corporativo"
          );
          inputPrecioCorporativo.value = producto.precio_venta_02 ?? 0;
          tdPrecioCorporativo.appendChild(inputPrecioCorporativo);

          const tdPrecioAPersonal = tr.insertCell();
          tdPrecioAPersonal.classList.add("align-middle");
          const inputPrecioAPersonal = document.createElement("input");
          inputPrecioAPersonal.classList.add(
            "form-control",
            "precio_a_personal"
          );
          inputPrecioAPersonal.value = producto.precio_venta_03 ?? 0;
          tdPrecioAPersonal.appendChild(inputPrecioAPersonal);

          const tdGuardar = tr.insertCell();
          const btnGuardar = document.createElement("button");
          btnGuardar.classList.add("btn", "btn-primary");
          btnGuardar.innerText = "Guardar";
          btnGuardar.addEventListener("click", cambiarPreciosyCostos);
          tdGuardar.appendChild(btnGuardar);

          alCambiarUnNumero(tr);
        });
      });

      alinearCeldasALaDerecha();
      formatearCeldasNumericas();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los productos", "consultar");
    }

    prepararInputs();
  }

  function alinearCeldasALaDerecha() {
    // seleccionar todas las celdas de la tabla excepto las del encabezado y la primera columna
    const celdas = document.querySelectorAll(
      "#tabla-listado-catalogo tbody td:not(:first-child):not(:nth-child(1)):not(:last-child):not(.text-start)"
    );

    celdas.forEach((celda) => {
      celda.classList.add("text-end");
      if (celda.querySelector("input"))
        celda.querySelector("input").classList.add("text-end");
    });
  }

  function formatearCeldasNumericas() {
    // seleccionar todas las celdas de la tabla excepto las del encabezado y la primera columna
    const celdas = document.querySelectorAll(
      "#tabla-listado-catalogo tbody tr:not(:first-child) td:not(:first-child):not(:nth-child(1)):not(:last-child):not(.text-start)"
    );

    celdas.forEach((celda) => {
      if (celda.querySelector("input")) {
        celda.querySelector("input").value = formatearCantidad(
          celda.querySelector("input").value
        );
      } else {
        celda.innerText = formatearCantidad(celda.innerText);
      }
    });
  }

  function alCambiarUnNumero(row) {
    const costoUnitario =
      row.getElementsByClassName("costo_unitario")[0].innerText;
    const costoManoDeObra =
      row.getElementsByClassName("costo_mano_de_obra")[0].value;
    const costoAdicional =
      row.getElementsByClassName("costos_adicionales")[0].value;

    const costo = +(
      parseFloat(costoUnitario) +
      parseFloat(costoManoDeObra) +
      parseFloat(costoAdicional)
    ).toFixed(2);

    row.getElementsByClassName("costo")[0].innerText = costo;

    const margen = row.getElementsByClassName("porcentaje_margen")[0].value;

    const precioVenta = costo * (1 + margen / 100);
    const igv = precioVenta * 0.1; // IGV del 10%
    const precioFinal = precioVenta + igv;

    row.getElementsByClassName("precio_venta")[0].innerText =
      precioVenta.toFixed(2);
    row.getElementsByClassName("igv")[0].innerText = igv.toFixed(2);
    row.getElementsByClassName("precio_final")[0].innerText =
      precioFinal.toFixed(2);
  }

  async function cambiarPreciosyCostos(event) {
    const fila = event.target.closest("tr");
    const id = fila.dataset.idProducto;

    const producto = {
      costo_mano_de_obra:
        fila.getElementsByClassName("costo_mano_de_obra")[0].value,
      costo_adicional:
        fila.getElementsByClassName("costos_adicionales")[0].value,
      porcentaje_margen:
        fila.getElementsByClassName("porcentaje_margen")[0].value,
      precio_venta_01:
        fila.getElementsByClassName("precio_venta_lista")[0].value,
      precio_venta_02:
        fila.getElementsByClassName("precio_corporativo")[0].value,
      precio_venta_03:
        fila.getElementsByClassName("precio_a_personal")[0].value,
    };

    const url = `${apiProductosUrl}/${id}/costos-precios`;

    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(producto),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      mostrarAlert("ok", "Se guardaron los cambios correctamente", "editar");
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cambiar los precios y costos", "editar");
    }
  }

  function prepararInputs() {
    const inputs = document.querySelectorAll("input[type=number]");

    inputs.forEach((input) => {
      input.addEventListener.addEventListener("keypress", (event) => {
        if (isNaN(event.key) || tiempoEstimado.value.length >= 5) {
          event.preventDefault();
        }
      });
    });
  }

  function limpiarTabla() {
    const tbody = document.getElementById("tabla-listado-catalogo").tBodies[0];
    tbody.innerHTML = "";
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
