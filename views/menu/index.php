<?php
require "../../inc/header.php";

$pre = ENV == 'server' ? '/hotelarenasspa/cliente' : '/cliente';

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false; mostrarHeader("",
$logueado); ?>
<div class="mx-xl-5 main-cont">
  <div class="rounded-3">
    <div class="container text-center container-menu-principal">
      <h1 class="display-5 fw-bold">Menú Principal</h1>
      <?php if ($logueado) { ?>
      <p class="col-md-8 fs-4 mx-auto">
        Bienvenido al sistema de Hotel Arenas y Spa
      </p>

      <div class="row">
        <div class="col-6 col-lg-5">
          <div class="col">
            <h5 class="text-start px-3 py-1 text-light bg-info rounded-3">
              HOTEL
            </h5>
            <div class="row">
              <div class="col-7">
                <div class="row">
                  <div class="col-9 p-0">
                    <a href="/listado-reserva" class="text-decoration-none">
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="<?php echo $pre ?>/img/menu-principal/icono1.png"
                              width="80px"
                              height="80px"
                              alt="gestionar reservas"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text fw-bold">RESERVAS</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-3 p-0">
                    <img
                      src="<?php echo $pre ?>/img/menu-principal/flecha1.png"
                      width="100%"
                      height="180px"
                      alt="reservas"
                    />
                  </div>
                  <div class="col-9 p-0">
                    <a
                      href="/gestionar-checkin-hotel/"
                      class="text-decoration-none"
                    >
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="<?php echo $pre ?>/img/menu-principal/icono2.png"
                              width="80px"
                              height="80px"
                              alt="gestionar check-in hotel"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text fw-bold">CHECK-IN</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-3 p-0">
                    <img
                      src="<?php echo $pre ?>/img/menu-principal/flecha2.png"
                      width="100%"
                      height="180px"
                      alt="reservas"
                    />
                  </div>
                </div>
              </div>
              <div class="col-5 d-flex align-items-center p-0">
                <div class="col">
                  <a href="/listado-rooming/" class="text-decoration-none">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div>
                          <img
                            src="<?php echo $pre ?>/img/menu-principal/icono3.png"
                            width="80px"
                            height="80px"
                            alt="listado rooming"
                          />
                        </div>
                      </div>
                      <div class="card-footer">
                        <p class="card-text fw-bold">ROOMING</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <h5 class="text-start px-3 py-1 text-light bg-info rounded-3">
              SPA
            </h5>
            <div class="row">
              <div class="col">
                <div class="row">
                  <div class="col-5 p-0">
                    <a
                      href="/registrar-cliente-spa/"
                      class="text-decoration-none"
                    >
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="<?php echo $pre ?>/img/menu-principal/icono4.png"
                              width="80px"
                              height="80px"
                              alt="habitaciones"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text fw-bold">CLIENTE SPA</p>
                        </div>
                      </div>
                    </a>
                    <button
                      id="crear-cliente"
                      class="btn btn-outline-secondary link-info mb-2 w-100"
                      onclick="mostrarModalCrearCliente()"
                    >
                      Crear cliente
                    </button>
                  </div>
                  <div class="col-2 p-0">
                    <img
                      src="<?php echo $pre ?>/img/menu-principal/flecha3.png"
                      width="100%"
                      height="180px"
                      alt="reservas"
                    />
                  </div>
                  <div class="col-5 p-0">
                    <a
                      href="/programacion-servicios"
                      class="text-decoration-none"
                    >
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="<?php echo $pre ?>/img/menu-principal/icono5.png"
                              width="80px"
                              height="80px"
                              alt="programación de terapeutas"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text fw-bold">PROG. TERAPEUTAS</p>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <h5 class="text-start px-3 py-1 text-light bg-info rounded-3">
              CAFETERÍA
            </h5>
            <div class="row">
              <div class="col">
                <div class="row">
                  <div class="col-4 p-0">
                    <a href="/agregar-comanda/" class="text-decoration-none">
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="<?php echo $pre ?>/img/menu-principal/icono6.png"
                              width="80px"
                              height="80px"
                              alt="registro de comandas"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text fw-bold">REG. PEDIDO</p>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <h5 class="text-start px-3 py-1 text-light bg-info rounded-3">
              ALERTAS
            </h5>
            <div class="row">
              <div class="col-12 text-start">
                <!-- TODO: faltan agregar los links -->
                <a
                  href="#"
                  class="btn btn-outline-secondary mb-2 w-100 text-start"
                  >CUMPLEAÑOS</a
                >
              </div>
              <div class="col-12 text-start">
                <a
                  href="#"
                  class="btn btn-outline-secondary mb-2 w-100 text-start"
                  >PRECIOS AFECTADOS</a
                >
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-2 p-0">
          <div
            class="float-start position-relative"
            style="top: 26.5%; left: 0"
          >
            <img src="<?php echo $pre ?>/img/menu-principal/flecha4.png" id="flecha4" />
          </div>

          <div
            class="col-6 p-0 float-start position-relative"
            style="top: 30%; left: 0"
          >
            <a
              href="/relacion-clientes-hotel-spa/"
              class="text-decoration-none"
            >
              <div class="card mb-2">
                <div class="card-body">
                  <div>
                    <img
                      src="<?php echo $pre ?>/img/menu-principal/icono7.png"
                      width="80px"
                      height="80px"
                      alt="estado de cuenta de cliente"
                    />
                  </div>
                </div>
                <div class="card-footer">
                  <p class="card-text fw-bold">CUENTA DEL CLIENTE</p>
                </div>
              </div>
            </a>
          </div>
        </div>

        <div class="col-6 col-lg-5">
          <h5 class="text-start px-3 py-1 text-light bg-info rounded-3">
            CAJA
          </h5>
          <div class="row">
            <div class="col-12 text-start">
              <a
                href="/registro-ventas/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REG. VENTAS</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="/relacion-cuentas-por-pagar"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REG. COMPRAS</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="/reporte-caja-diario/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REPORTE DIARIO CAJA</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="/reporte-productos-diario/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REPORTE DIARIO AL DETALLE</a
              >
            </div>
          </div>
          <h5 class="text-start px-3 py-1 text-light bg-info rounded-3">
            CATÁLOGO
          </h5>
          <div class="row">
            <div class="col-12 text-start">
              <a
                href="/listado-catalogo/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >CATÁLOGO DE PRODUCTOS</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="/cambiar-precio-catalogo/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >CAMBIOS DE PRECIO</a
              >
            </div>
          </div>
          <h5 class="text-start px-3 py-1 text-light bg-info rounded-3">
            LOGÍSTICA
          </h5>
          <div class="row">
            <div class="col-12 text-start">
              <a
                href="/consultar-productos/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >PRODUCTOS E INSUMOS / KARDEX</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="/registro-ingreso-egreso/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REGISTRO DE INGRESO / EGRESO</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="/inventario-simple/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >INVENTARIO SIMPLE</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="/inventario-valorado/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >INVENTARIO VALORADO</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="/petitorio/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >PETITORIO</a
              >
            </div>
          </div>
          <h5 class="text-start px-3 py-1 text-light bg-info rounded-3">
            UTLIDADES
          </h5>
          <div class="row">
            <div class="col-12 text-start">
              <a
                href="#"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >CONFIGURACIONES</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="#"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REPORTES DE GESTIÓN</a
              >
            </div>
          </div>
        </div>
      </div>
      <?php } else { ?>
      <p class="col-md-8 fs-4 mx-auto">
        Bienvenido al sistema de Hotel Arenas y Spa<br />Para acceder a las
        funciones del sistema, inicia sesión
      </p>
      <a class="btn btn-primary btn-lg" href="/login"
        >Iniciar sesión
        <nav></nav
      ></a>
      <?php } ?>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-crear"
  tabindex="-1"
  aria-labelledby="modal-crear-label"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-crear-label">Crear nuevo cliente</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="persona" class="form-label">Persona</label>
          <input
            class="form-control"
            list="persona-list"
            id="persona"
            placeholder="Buscar persona..."
          />
          <datalist id="persona-list"> </datalist>
        </div>

        <button
          type="button"
          class="btn btn-primary w-100"
          id="btn-aceptar-servicio"
          data-bs-dismiss="modal"
          onclick="crearCliente()"
        >
          Aceptar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const apiCheckingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";
  const apiPersonasUrl = "<?php echo URL_API_NUEVA ?>/personas";

  let modal = null;
  let personas = [];

  async function wrapper() {
    modal = new bootstrap.Modal(document.getElementById("modal-crear"));
  }

  async function mostrarModalCrearCliente() {
    if (personas.length === 0) {
      await cargarPersonas();
    }
    modal.show();
  }

  async function cargarPersonas() {
    const url = `${apiPersonasUrl}?limite=20`
    try {
      const response = await fetch(url);
      const data = await response.json();
      console.log(data);

      personas = data;
      llenarDatalistPersonas();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar las personas", "cargar");
    }
  }

  function llenarDatalistPersonas() {
    const datalist = document.getElementById("persona-list");
    datalist.innerHTML = "";

    personas.forEach((persona) => {
      const option = document.createElement("option");
      option.value = `${persona.nombres ?? ""} ${persona.apellidos ?? ""} - ${
        persona.nro_documento
      }`;
      datalist.appendChild(option);
    });
  }

  async function crearCliente() {
    const nro_documento = document
      .getElementById("persona")
      .value.split(" - ")[1];

    const checking = {
      titular: {
        nro_documento,
        es_nuevo: false,
      },
      acompanantes: [],
    };
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

      const nroRegistroMaestro = data.resultado.nro_registro_maestro;
      open(`./estado-cuenta-cliente?nro_registro_maestro=${nroRegistroMaestro}`)

    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al registrar el checking", "crear");
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
