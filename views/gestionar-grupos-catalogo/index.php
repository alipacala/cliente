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
      <h2 class="text-center">Módulo Grupos del Catálogo</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4 ms-auto">
          <button
            class="btn btn-outline-primary w-100"
            id="btn-crear-producto"
            data-id="0"
            onclick="mostrarModalGrupo(this)"
          >
            <i class="fas fa-plus"></i> Crear grupo/subgrupo
          </button>
        </div>
      </div>

      <div class="table-container">
        <table
          id="tabla-listado-catalogo"
          class="table table-bordered table-hover"
        >
          <thead>
            <tr>
              <th class="text-center" style="width: 100px">Nro Orden</th>
              <th class="text-center">Grupo / Subgrupo</th>
              <th class="text-center" style="width: 100px">EDITAR</th>
              <th class="text-center" style="width: 100px">BORRAR</th>
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
  id="modal-grupo"
  tabindex="-1"
  aria-labelledby="modal-grupo-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-grupo-label">
          Nuevo grupo del catálogo
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-grupo"
        ></button>
      </div>
      <div class="modal-body">
        <form id="form-crear-grupo">
          <div class="form-group mb-3">
            <label for="nombre">NOMBRE GRUPO:</label>
            <input
              type="text"
              class="form-control"
              id="nombre"
              name="nombre"
              value=""
              required
            />
          </div>

          <div class="form-group mb-3">
            <label for="pertenece">PERTENECE AL GRUPO:</label>
            <select
              class="form-select"
              id="pertenece"
              name="pertenece"
            ></select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            data-bs-dismiss="modal"
          >
            Salir
          </button>
          <button
            type="submit"
            class="btn btn-primary col-md-6"
            id="crear-grupo"
          >
            Aceptar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal modal-sm fade"
  id="noti"
  tabindex="-1"
  aria-labelledby="noti-label"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fa-solid fa-plus fs-3 me-3" id="icono"></i>
        <h1 class="modal-title fs-5" id="noti-label">Gestionar grupos de catálogo</h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Aceptar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const apiGruposUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";

  let gruposCargados = [];
  let idGrupo = 0;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarGrupos();
    prepararBotonCrearGrupo();
  }

  function mostrarModalGrupo(e) {
    const modalGrupo = new bootstrap.Modal(
      document.getElementById("modal-grupo")
    );
    const boton = document.getElementById("crear-grupo");

    idGrupo = +e.dataset.id;

    if (idGrupo != 0) {
      const grupo = gruposCargados.find((grupo) => grupo.id_grupo == idGrupo);

      document.getElementById("nombre").value = grupo.nombre_grupo;

      const esSubgrupo = grupo.codigo_subgrupo != grupo.codigo_grupo;
      document.getElementById("pertenece").value = esSubgrupo
        ? grupo.codigo_grupo
        : "";

      boton.classList.add("btn-warning");
      boton.classList.remove("btn-primary");

      document.getElementById("modal-grupo-label").textContent =
        "Editar grupo del catálogo";
    } else {
      limpiarFormulario();

      boton.classList.add("btn-primary");
      boton.classList.remove("btn-warning");

      document.getElementById("modal-grupo-label").textContent =
        "Nuevo grupo del catálogo";
    }

    modalGrupo.show();
  }

  async function cargarGrupos() {
    try {
      const response = await fetch(apiGruposUrl);
      const data = await response.json();

      let grupos = data;

      grupos = ordenarGrupos(grupos);

      const tabla = document.getElementById("tabla-listado-catalogo")
        .tBodies[0];

      grupos.forEach((grupo) => {
        const esSubgrupo = grupo.codigo_subgrupo != grupo.codigo_grupo;

        const fila = tabla.insertRow();

        fila.insertCell(
          0
        ).innerHTML = `<input type="text" class="form-control nro_orden" value="${grupo.nro_orden}" data-id="${grupo.id_grupo}" onchange="actualizarGrupo(this, 'nro_orden')">`;
        fila.insertCell(1).innerHTML =
          (esSubgrupo ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "") +
          grupo.nombre_grupo;
        fila.insertCell(
          2
        ).innerHTML = `<button class="btn btn-outline-warning btn-sm w-100" onclick="mostrarModalGrupo(this)" data-id="${grupo.id_grupo}"><i class="fas fa-edit"></i></button>`;
        fila.insertCell(
          3
        ).innerHTML = `<button class="btn btn-outline-danger btn-sm w-100" onclick="borrarGrupo(${grupo.id_grupo})"><i class="fas fa-trash"></i></button>`;
      });

      gruposCargados = data;
      cargarGruposSelect();
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

  async function borrarGrupo(id) {
    const url = `${apiGruposUrl}/${id}`;

    const options = {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      if (response.status != 200) throw data;

      mostrarAlert("ok", data.mensaje, "borrar");
      console.log(data);

      limpiarTabla();
      await cargarGrupos();
    } catch (error) {
      console.log(error);
      mostrarAlert("error", error.mensaje, "borrar");
    }
  }

  async function actualizarGrupo(e, campo) {
    const grupo =
      campo == "nro_orden"
        ? {
            nro_orden: +e.value,
          }
        : {
            nombre_grupo: document.getElementById("nombre").value,
            codigo_grupo: document.getElementById("pertenece").value,
          };

    if (campo == "nro_orden") {
      idGrupo = +e.dataset.id;
    }

    if (grupo.codigo_grupo == "") delete grupo.codigo_grupo;

    const url = `${apiGruposUrl}/${idGrupo}`;

    const options = {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(grupo),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      if (response.status != 200) throw data;

      console.log(data);
      mostrarAlert("ok", data.mensaje, "editar");

      limpiarTabla();
      await cargarGrupos();

      if (campo != "nro_orden") {
        limpiarFormulario();
        ocultarModalGrupo();
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", error.mensaje, "editar");
    }
  }

  function cargarGruposSelect() {
    const select = document.getElementById("pertenece");
    select.innerHTML = "";

    // agregar opcion por defecto
    const option = document.createElement("option");
    option.value = "";
    option.text = "Ninguno";
    select.appendChild(option);

    gruposCargados.forEach((grupo) => {
      const esSubgrupo = grupo.codigo_subgrupo != grupo.codigo_grupo;

      if (!esSubgrupo) {
        const option = document.createElement("option");
        option.value = grupo.codigo_grupo;
        option.text = grupo.nombre_grupo;
        select.appendChild(option);
      }
    });
  }

  function prepararBotonCrearGrupo() {
    const boton = document.getElementById("crear-grupo");

    boton.addEventListener("click", async (e) => {
      e.preventDefault();

      if (idGrupo != 0) {
        actualizarGrupo(e, "nombre_y_pertenece");
      } else {
        crearGrupo();
      }
    });
  }

  async function crearGrupo() {
    const grupo = {};
    grupo.nombre_grupo = document.getElementById("nombre").value;
    if (document.getElementById("pertenece").value != "")
      grupo.codigo_grupo = document.getElementById("pertenece").value;

    try {
      const response = await fetch(apiGruposUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(grupo),
      });

      const data = await response.json();

      if (response.status != 201) throw data;

      console.log(data);
      mostrarAlert("ok", data.mensaje, "crear");

      limpiarTabla();
      await cargarGrupos();
      limpiarFormulario();
      ocultarModalGrupo();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", error.mensaje, "crear");
    }
  }

  function ocultarModalGrupo() {
    const modalGrupo = document.getElementById("modal-grupo");
    const modal = bootstrap.Modal.getInstance(modalGrupo);
    modal.hide();
  }

  function limpiarTabla() {
    const tbody = document.getElementById("tabla-listado-catalogo").tBodies[0];
    tbody.innerHTML = "";
  }

  function limpiarFormulario() {
    document.getElementById("nombre").value = "";
    document.getElementById("pertenece").value = "";

    idGrupo = 0;
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
