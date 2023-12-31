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
      <h2 class="text-center">Formulario de Registro de Cliente SPA</h2>
    </div>
    <div class="card-body">
      <form id="form-registrar-cliente-spa">
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="nro_registro_maestro">Nro. Registro</label>
            <input
              type="text"
              class="form-control"
              id="nro_registro_maestro"
              name="nro_registro_maestro"
              disabled
              required
            />
          </div>
          <div class="form-group col-md-4">
            <label for="fecha_in">Fecha</label>
            <input
              type="date"
              class="form-control"
              id="fecha_in"
              name="fecha_in"
              disabled
              required
            />
          </div>
          <div class="form-group col-md-4">
            <label for="hora_in">Hora</label>
            <input
              type="time"
              class="form-control"
              id="hora_in"
              name="hora_in"
              disabled
              required
            />
          </div>
        </div>

        <div class="table-responsive">
          <table
            class="table table-bordered table-hover"
            id="tabla-clientes-spa"
          >
            <thead>
              <tr>
                <th>DNI Titular</th>
                <th>Apellido paterno</th>
                <th>Apellido materno</th>
                <th>Nombres</th>
                <th>Sexo</th>
                <th>Seg. Edad</th>
                <th>Parentesco</th>
                <th>Borrar</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="input-group">
                    <input
                      type="text"
                      class="form-control dni_titular"
                      id="dni_titular"
                    />
                    <div class="input-group-text">
                      <span
                        class="spinner-border spinner-border-sm invisible"
                        id="spinner"
                        role="status"
                      ></span>
                    </div>
                  </div>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    id="apellido_paterno_titular"
                    name="apellido_paterno_titular"
                    onchange="agregarFilaAcompanante()"
                    required
                  />
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    id="apellido_materno_titular"
                    name="apellido_materno_titular"
                    required
                  />
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    id="nombres_titular"
                    name="nombres_titular"
                    required
                  />
                </td>
                <td>
                  <select
                    type="text"
                    class="form-select"
                    id="sexo_titular"
                    name="sexo_titular"
                    required
                  >
                    <option value="M">M</option>
                    <option value="F">F</option>
                  </select>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    id="seg_edad_titular"
                    name="seg_edad_titular"
                    required
                  />
                </td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>

        <input type="submit" class="btn btn-primary" value="Aceptar" />
      </form>
    </div>
  </div>
</div>

<script>
  const apiConfigUrl = "<?php echo URL_API_NUEVA ?>/config";
  const apiCheckingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";
  const apiPersonasUrl = "<?php echo URL_API_NUEVA ?>/personas";

  let idTitular = 0;
  let personaExiste = false;
  let rowId = 1;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarNroRegistro();

    actualizarFechaHora();
    prepararFormulario();
    alCambiarDni();

    // Llama a la función cada segundo para mantener la fecha y hora actualizadas
    setInterval(actualizarFechaHora, 1000);
  }

  async function cargarNroRegistro() {
    const url = `${apiConfigUrl}?codigo=SPA`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      const nroRegistroMaestro = document.getElementById(
        "nro_registro_maestro"
      );
      nroRegistroMaestro.value = data;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar el nro. de registro", "consultar");
    }
  }

  function obtenerDatosInputs() {
    const chekingRegistro = personaExiste
      ? {
          titular: {
            es_nuevo: false,
            nro_documento: document.getElementById("dni_titular").value,
            edad: document.getElementById("seg_edad_titular").value,
          },
          acompanantes: [],
        }
      : {
          titular: {
            es_nuevo: true,
            nro_documento: document.getElementById("dni_titular").value,
            apellidos_y_nombres: `${document.getElementById(
              "apellido_paterno_titular"
            ).value} ${document.getElementById(
              "apellido_materno_titular"
            ).value}, ${document.getElementById("nombres_titular").value}`,
            sexo: document.getElementById("sexo_titular").value,
            edad: document.getElementById("seg_edad_titular").value,
          },
          acompanantes: [],
        };

    let filasAcompanantes = document.querySelectorAll(
      "[data-tipo='acompanante']"
    );

    // borrar los que no tengan nombre
    filasAcompanantes = [...filasAcompanantes].filter(
      (fila) =>
        fila.querySelector("[name='apellido_paterno_acompanante_actual']")
          .value != ""
    );

    chekingRegistro.acompanantes = filasAcompanantes.map(
      (acompanante, index) => ({
        apellidos_y_nombres: `${acompanante.querySelector(
          "[name='apellido_paterno_acompanante_actual']"
        ).value} ${acompanante.querySelector(
          "[name='apellido_materno_acompanante_actual']"
        ).value}, ${acompanante.querySelector(
          "[name='nombres_acompanante_actual']"
        ).value}`,
        sexo: acompanante.querySelector("[name='sexo_acompanante_actual']")
          .value,
        edad: acompanante.querySelector("[name='seg_edad_acompanante_actual']")
          .value,
        parentesco: acompanante.querySelector(
          "[name='parentesco_acompanante_actual']"
        ).value,
      })
    );

    return chekingRegistro;
  }

  async function registrarChecking() {
    const checking = obtenerDatosInputs();
    const url = `${apiCheckingsUrl}/spa`;

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(checking),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();
      console.log(data);

      window.location.href = "./../relacion-clientes-hotel-spa?ok&mensaje=Checking creado correctamente&op=crear";
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al registrar el checking", "crear");
    }
  }

  function alCambiarDni() {
    const dniInput = document.getElementById("dni_titular");
    dniInput.addEventListener("change", async () => {
      const dni = dniInput.value;
      const url = `${apiPersonasUrl}?dni=${dni}`;

      const spinner = document.getElementById("spinner");
      spinner.classList.add("visible");
      spinner.classList.remove("invisible");

      try {
        const response = await fetch(url);
        const data = await response.json();

        spinner.classList.remove("visible");
        spinner.classList.add("invisible");

        if (!data) {
          personaExiste = false;
          desbloquearInputs();
          limpiarInputs();
          return;
        }

        const personaEncontrada = data;

        personaExiste = true;

        document.getElementById("apellido_paterno_titular").value =
          personaEncontrada.apellidos.split(" ")[0];
        document.getElementById("apellido_materno_titular").value =
          personaEncontrada.apellidos.split(" ")[1];
        document.getElementById("nombres_titular").value =
          personaEncontrada.nombres;
        document.getElementById("sexo_titular").value = personaEncontrada.sexo;
        document.getElementById("seg_edad_titular").value =
          personaEncontrada.edad;

        bloquearInputs();
        idTitular = personaEncontrada.id_persona;

        // si no hay filas de acompañantes, agregar una
        if (
          document.querySelectorAll("[data-tipo='acompanante']").length == 0
        ) {
          agregarFilaAcompanante();
        }
      } catch (error) {
        console.error(error);
        mostrarAlert("error", "Error al consultar la persona", "consultar");
      }
    });
  }

  function cargarParentescosEnSelect(select) {
    const parentescos = [
      "Padre/Madre",
      "Hijo/a",
      "Hermano/a",
      "Tío/a",
      "Sobrino/a",
      "Primo/a",
      "Cuñado/a",
      "Suegro/a",
      "Yerno/Nuera",
      "Nieto/a",
      "Abuelo/a",
      "Otro",
    ];

    // Agregar opciones al select
    parentescos.forEach((parentesco) => {
      const option = document.createElement("option");
      option.value = parentesco;
      option.textContent = parentesco;
      select.appendChild(option);
    });
  }

  function agregarFilaAcompanante() {
    const tabla = document.getElementById("tabla-clientes-spa");
    const fila = tabla.insertRow();

    fila.dataset.tipo = "acompanante";
    fila.dataset.rowId = rowId++;

    const dniTitular = fila.insertCell();
    const apellidoPaterno = fila.insertCell();
    const apellidoMaterno = fila.insertCell();
    const nombres = fila.insertCell();
    const sexo = fila.insertCell();
    const edad = fila.insertCell();
    const parentesco = fila.insertCell();
    const acciones = fila.insertCell();

    const apellidoPaternoInput = document.createElement("input");
    apellidoPaternoInput.type = "text";
    apellidoPaternoInput.classList.add("form-control");
    apellidoPaternoInput.id = "apellido_paterno_acompanante_actual";
    apellidoPaternoInput.name = "apellido_paterno_acompanante_actual";
    
    apellidoPaternoInput.addEventListener("change", () => {
      agregarFilaAcompanante();
    });

    const apellidoMaternoInput = document.createElement("input");
    apellidoMaternoInput.type = "text";
    apellidoMaternoInput.classList.add("form-control");
    apellidoMaternoInput.id = "apellido_materno_acompanante_actual";
    apellidoMaternoInput.name = "apellido_materno_acompanante_actual";

    const nombresInput = document.createElement("input");
    nombresInput.type = "text";
    nombresInput.classList.add("form-control");
    nombresInput.id = "nombres_acompanante_actual";
    nombresInput.name = "nombres_acompanante_actual";
    
    const sexoInput = document.createElement("select");
    sexoInput.classList.add("form-select");
    sexoInput.id = "sexo_acompanante_actual";
    sexoInput.name = "sexo_acompanante_actual";

    const optionMasculino = document.createElement("option");
    optionMasculino.value = "M";
    optionMasculino.innerText = "M";

    const optionFemenino = document.createElement("option");
    optionFemenino.value = "F";
    optionFemenino.innerText = "F";

    sexoInput.appendChild(optionMasculino);
    sexoInput.appendChild(optionFemenino);

    const edadInput = document.createElement("input");
    edadInput.type = "text";
    edadInput.classList.add("form-control");
    edadInput.id = "seg_edad_acompanante_actual";
    edadInput.name = "seg_edad_acompanante_actual";

    const parentescoInput = document.createElement("select");
    parentescoInput.classList.add("form-select");
    parentescoInput.id = "parentesco_acompanante_actual";
    parentescoInput.name = "parentesco_acompanante_actual";

    cargarParentescosEnSelect(parentescoInput);

    dniTitular.innerHTML = "";
    apellidoPaterno.appendChild(apellidoPaternoInput);
    apellidoMaterno.appendChild(apellidoMaternoInput);
    nombres.appendChild(nombresInput);
    sexo.appendChild(sexoInput);
    edad.appendChild(edadInput);
    parentesco.appendChild(parentescoInput);
    acciones.innerHTML = `
      <button class="btn btn-outline-danger btn-sm" onclick="eliminarFila(this)">
        <i class="fas fa-trash-alt"></i>
      </button>
    `;
  }

  function bloquearInputs() {
    document.getElementById("apellido_paterno_titular").disabled = true;
    document.getElementById("apellido_materno_titular").disabled = true;
    document.getElementById("nombres_titular").disabled = true;
    document.getElementById("sexo_titular").disabled = true;
  }

  function desbloquearInputs() {
    document.getElementById("apellido_paterno_titular").disabled = false;
    document.getElementById("apellido_materno_titular").disabled = false;
    document.getElementById("nombres_titular").disabled = false;
    document.getElementById("sexo_titular").disabled = false;
  }

  function limpiarInputs() {
    document.getElementById("apellido_paterno_titular").value = "";
    document.getElementById("apellido_materno_titular").value = "";
    document.getElementById("nombres_titular").value = "";
    document.getElementById("sexo_titular").value = "M";
    document.getElementById("seg_edad_titular").value = "";
  }

  // Función para actualizar la fecha y hora
  function actualizarFechaHora() {
    const fechaElemento = document.getElementById("fecha_in");
    const horaElemento = document.getElementById("hora_in");

    const fechaActual = new Date();

    const fechaFormateada = fechaActual.toISOString().substr(0, 10); // Formato YYYY-MM-DD
    const horaFormateada = fechaActual.toTimeString().substr(0, 5); // Formato HH:MM

    fechaElemento.value = fechaFormateada;
    horaElemento.value = horaFormateada;
  }

  function eliminarFila(element) {
    const fila = element.closest("tr");
    fila.remove();

    const rowId = fila.dataset.rowId;
    if (fila.dataset.tipo == "acompanante") {
      chekingRegistro.acompanantes = chekingRegistro.acompanantes.filter(
        (acompanante) => acompanante.rowId != rowId
      );
    } else {
      chekingRegistro.titular = null;
      idTitular = 0;
    }
  }

  function prepararFormulario() {
    const form = document.getElementById("form-registrar-cliente-spa");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      registrarChecking();
    });
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
