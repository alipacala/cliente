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
      <h2 class="text-center">Listado de catálogo</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          GRUPO:
          <select id="grupo" name="grupo" class="form-select"></select>
        </div>
        <div class="col-md-6 ms-auto">
          Crear producto:
          <div class="row">
            <div class="col-md-6">
              <select id="tipo" name="tipo" class="form-select">
                <option value="PRD">Producto terminado / insumo</option>
                <option value="SVH">Producto de hospedaje</option>
                <option value="SRV">Producto de servicio</option>
                <option value="RST">Producto de receta</option>
                <option value="PAQ">Producto de paquete / combo</option>
              </select>
            </div>
            <div class="col-md-6">
              <button
                class="btn btn-outline-primary w-100"
                id="btn-crear-producto"
              >
                <i class="fas fa-plus"></i> Crear producto
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="table-container">
        <table
          id="tabla-listado-catalogo"
          class="table table-bordered table-hover"
        >
          <thead>
            <tr>
              <th class="text-center">Grupo</th>
              <th class="text-center">SubGrupo</th>
              <th class="text-center"></th>
              <th class="text-center"></th>
              <th class="text-center"></th>
            </tr>
            <tr>
              <th class="text-center">Nombre del producto</th>
              <th class="text-center">Precio de venta regular</th>
              <th class="text-center">Precio de venta corporativo</th>
              <th class="text-center">Impresora comanda</th>
              <th class="text-center">Editar</th>
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
  let productosCargados = [];
  let gruposConProductos = [];

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarGrupos();
    await cargarProductos();
    await cargarProductosEnTabla();
    prepararBotonCrear();
  }

  function prepararBotonCrear() {
    const btnCrearProducto = document.getElementById("btn-crear-producto");
    btnCrearProducto.addEventListener("click", () => {
      const tipo = document.getElementById("tipo").value;
      switch (tipo) {
        case "PRD":
          window.location.href = `./../crear-producto`;
          break;
        case "SVH":
          window.location.href = `./../crear-producto-hospedaje`;
          break;
        case "SRV":
          window.location.href = `./../crear-producto-servicio`;
          break;
        case "RST":
          window.location.href = `./../crear-producto-receta`;
          break;
        case "PAQ":
          window.location.href = `./../crear-producto-paquete`;
          break;
        default:
          break;
      }
    });
  }

  async function cargarGrupos() {
    try {
      const response = await fetch(apiGruposUrl);
      const data = await response.json();

      const select = document.getElementById("grupo");

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione un grupo";
      defaultOption.selected = true;
      select.appendChild(defaultOption);

      gruposCargados = ordenarGrupos(data);

      gruposCargados.forEach((element) => {
        const option = crearOpcionClasificacionVentas(element);
        select.appendChild(option);
      });

      select.addEventListener("change", cargarProductosEnTabla);
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los grupos", "consultar");
    }
  }

  async function cargarProductos() {
    try {
      const response = await fetch(apiProductosUrl);
      const data = await response.json();
      productosCargados = data;

      // filtrar productos de venta y de consumo
      productosCargados = productosCargados.filter((producto) =>
        [12, 13].includes(producto.id_tipo_de_producto)
      );

      // ordenar los productos por nombre
      productosCargados.sort((a, b) => {
        if (a.nombre_producto > b.nombre_producto) {
          return 1;
        } else if (a.nombre_producto < b.nombre_producto) {
          return -1;
        } else {
          return 0;
        }
      });

      gruposConProductos = gruposCargados
        .filter((grupo) => {
          return grupo.codigo_grupo == grupo.codigo_subgrupo;
        })
        .map((grupo) => {
          return {
            id: grupo.id_grupo,
            nombre: grupo.nombre_grupo,
            productos: productosCargados.filter(
              (producto) => producto.id_grupo == grupo.id_grupo
            ),
            subgrupos: gruposCargados
              .filter(
                (subgrupo) =>
                  subgrupo.codigo_grupo == grupo.codigo_grupo &&
                  subgrupo.codigo_subgrupo != subgrupo.codigo_grupo
              )
              .map((subgrupo) => {
                return {
                  id: subgrupo.id_grupo,
                  nombre: subgrupo.nombre_grupo,
                  productos: productosCargados.filter(
                    (producto) => producto.id_grupo == subgrupo.id_grupo
                  ),
                };
              }),
          };
        });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los productos", "consultar");
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

  async function cargarProductosEnTabla() {
    limpiarTabla();

    const idGrupo = document.getElementById("grupo").value;
    const body = document.getElementById("tabla-listado-catalogo").tBodies[0];

    let gruposConProductosFiltrados;

    if (idGrupo == "") {
      gruposConProductosFiltrados = gruposConProductos;
    } else {
      const grupoSeleccionado = gruposCargados.find(
        (grupo) => grupo.id_grupo == idGrupo
      );

      const esSubgrupo =
        grupoSeleccionado.codigo_grupo != grupoSeleccionado.codigo_subgrupo;

      if (!esSubgrupo) {
        gruposConProductosFiltrados = gruposConProductos.filter(
          (grupo) => grupo.id == idGrupo
        );
      } else {
        const grupoPadre = gruposCargados.find(
          (grupo) =>
            grupo.codigo_grupo == grupoSeleccionado.codigo_grupo &&
            grupo.codigo_subgrupo == grupo.codigo_grupo
        );
        const idGrupoPadre = grupoPadre.id_grupo;

        gruposConProductosFiltrados = gruposConProductos.filter(
          (grupo) => grupo.id == idGrupoPadre
        );

        gruposConProductosFiltrados = [
          {
            id: grupoPadre.id_grupo,
            nombre: grupoPadre.nombre_grupo,
            subgrupos: [
              {
                id: grupoSeleccionado.id_grupo,
                nombre: grupoSeleccionado.nombre_grupo,
                productos: productosCargados.filter(
                  (producto) => producto.id_grupo == grupoSeleccionado.id_grupo
                ),
              },
            ],
          },
        ];
      }
    }

    gruposConProductosFiltrados.forEach((grupo) => {
      const trGrupo = body.insertRow();
      trGrupo.innerHTML = `
              <td>
                <strong>${grupo.nombre}</strong>
              </td>
              <td colspan="4">
              </td>
            `;

      grupo.productos?.forEach((producto) => {
        crearFilaProducto(producto, body);
      });

      grupo.subgrupos?.forEach((subgrupo) => {
        const trSubgrupo = body.insertRow();
        trSubgrupo.innerHTML = `
              <td>
                <strong>${grupo.nombre}</strong>
              </td>
              <td colspan="4">
                <strong>${subgrupo.nombre}</strong>
              </td>
            `;
        subgrupo.productos?.forEach((producto) => {
          crearFilaProducto(producto, body);
        });
      });
    });
  }

  function crearFilaProducto(producto, body) {
    const trProducto = body.insertRow();
    const tdNombreProducto = trProducto.insertCell();
    tdNombreProducto.innerText = producto.nombre_producto;
    const tdPrecioVentaCliente = trProducto.insertCell();
    tdPrecioVentaCliente.classList.add("text-end");
    tdPrecioVentaCliente.innerText = formatearCantidad(
      producto.precio_venta_01
    ) ?? "---";
    const tdPrecioVentaPersonal = trProducto.insertCell();
    tdPrecioVentaPersonal.classList.add("text-end");
    tdPrecioVentaPersonal.innerText = formatearCantidad(
      producto.precio_venta_02
    );
    const tdImpresoraComanda = trProducto.insertCell();
    tdImpresoraComanda.innerText = producto.id_impresora;
    const tdEditar = trProducto.insertCell();
    const btnEditar = document.createElement("button");
    btnEditar.classList.add("btn", "btn-outline-warning");
    btnEditar.innerHTML = '<i class="fas fa-edit"></i>';
    btnEditar.addEventListener("click", () => {
      switch (producto.tipo) {
        case "PRD":
          window.location.href = `./../crear-producto?id=${producto.id_producto}`;
          break;
        case "SVH":
          window.location.href = `./../crear-producto-hospedaje?id=${producto.id_producto}`;
          break;
        case "SRV":
          window.location.href = `./../crear-producto-servicio?id=${producto.id_producto}`;
          break;
        case "RST":
          window.location.href = `./../crear-producto-receta?id=${producto.id_producto}`;
          break;
        case "PAQ":
          window.location.href = `./../crear-producto-paquete?id=${producto.id_producto}`;
          break;
        default:
          break;
      }
    });
    tdEditar.appendChild(btnEditar);
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
