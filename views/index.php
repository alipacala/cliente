<?php
require "../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false; mostrarHeader("",
$logueado); ?>
<div class="my-5 mx-0 mx-xl-5 main-cont">
  <div class="bg-body-tertiary rounded-3">
    <div class="container-fluid py-0 text-center">
      <h1 class="display-5 fw-bold">Menú Principal</h1>
      <?php if ($logueado) { ?>
      <p class="col-md-8 fs-4 mx-auto">
        Bienvenido al sistema de Hotel Arenas y Spa
      </p>

      <div class="row">
        <div class="col-6 col-lg-4">
          
          <div class="col">
            <h5 class="text-start p-3 bg-info rounded-3">HOTEL</h5>
            <div class="row">
              <div class="col-7">
                <div class="row">
                  <div class="col-8 p-0">
                    <a href="./listado-reserva" class="text-decoration-none">
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="../img/menu-principal/icono1.png"
                              width="80px"
                              height="80px"
                              alt="gestionar reservas"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text">RESERVAS</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-2 p-0">
                    <img
                      src="../img/menu-principal/flecha1.png"
                      width="90px"
                      height="180px"
                      alt="reservas"
                    />
                  </div>
                  <div class="col-8 p-0">
                    <a href="./gestionar-checkin-hotel/" class="text-decoration-none">
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="../img/menu-principal/icono2.png"
                              width="80px"
                              height="80px"
                              alt="gestionar check-in hotel"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text">CHECK-IN</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-2 p-0">
                    <img
                      src="../img/menu-principal/flecha2.png"
                      width="90px"
                      height="180px"
                      alt="reservas"
                    />
                  </div>
                </div>
              </div>
              <div class="col-5 d-flex align-items-center p-0">
                <div class="col">
                  <a href="./listado-rooming/" class="text-decoration-none">
                    <div class="card mb-2">
                      <div class="card-body">
                        <div>
                          <img
                            src="../img/menu-principal/icono3.png"
                            width="80px"
                            height="80px"
                            alt="listado rooming"
                          />
                        </div>
                      </div>
                      <div class="card-footer">
                        <p class="card-text">ROOMING</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <h5 class="text-start p-3 bg-info rounded-3">SPA</h5>
            <div class="row">
              <div class="col">
                <div class="row">
                  <div class="col-5 p-0">
                    <a href="./registrar-cliente-spa/" class="text-decoration-none">
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="../img/menu-principal/icono4.png"
                              width="80px"
                              height="80px"
                              alt="habitaciones"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text">CLIENTE SPA</p>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-2 p-0">
                    <img
                      src="../img/menu-principal/flecha3.png"
                      width="75px"
                      height="180px"
                      alt="reservas"
                    />
                  </div>
                  <div class="col-5 p-0">
                    <!-- TODO: agregar el link cuando se pueda -->
                    <a href="#" class="text-decoration-none">
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="../img/menu-principal/icono5.png"
                              width="80px"
                              height="80px"
                              alt="programación de terapeutas"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text">PROG. TERAPEUTAS</p>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <h5 class="text-start p-3 bg-info rounded-3">CAFETERÍA</h5>
            <div class="row">
              <div class="col">
                <div class="row">
                  <div class="col-4 p-0">
                    <a href="./agregar-comanda/" class="text-decoration-none">
                      <div class="card mb-2">
                        <div class="card-body">
                          <div>
                            <img
                              src="../img/menu-principal/icono6.png"
                              width="80px"
                              height="80px"
                              alt="registro de comandas"
                            />
                          </div>
                        </div>
                        <div class="card-footer">
                          <p class="card-text">REG. PEDIDO</p>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <h5 class="text-start p-3 bg-info rounded-3">ALERTAS</h5>
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

        <div class="col-6 col-lg-4">
          <div
            class="float-start position-relative"
            style="top: 25.5%; left: 0"
          >
            <img
              src="../img/menu-principal/flecha4.png"
              id="flecha4"
            />
          </div>

          <div
            class="col-4 p-0 float-start position-relative"
            style="top: 30%; left: 0"
          >
            <a href="./relacion-clientes-hotel-spa/" class="text-decoration-none">
              <div class="card mb-2">
                <div class="card-body">
                  <div>
                    <img
                      src="../img/menu-principal/icono7.png"
                      width="80px"
                      height="80px"
                      alt="estado de cuenta de cliente"
                    />
                  </div>
                </div>
                <div class="card-footer">
                  <p class="card-text">ESTADO DE CUENTA</p>
                </div>
              </div>
            </a>
          </div>
        </div>

        <div class="col-6 col-lg-4">
          <h5 class="text-start p-3 bg-info rounded-3">CAJA</h5>
          <div class="row">
            <div class="col-12 text-start">
              <a
                href="./registro-ventas/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REG. VENTAS</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="./relacion-cuentas-por-pagar"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REG. COMPRAS</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="./reporte-caja-diario/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REPORTE DIARIO CAJA</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="./reporte-productos-diario/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >REPORTE DIARIO AL DETALLE</a
              >
            </div>
          </div>
          <h5 class="text-start p-3 bg-info rounded-3">CATÁLOGO</h5>
          <div class="row">
            <div class="col-12 text-start">
              <a
                href="./listado-catalogo/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >CARTAS DE PRODUCTOS</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="./cambiar-precio-catalogo/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >CAMBIOS DE PRECIO</a
              >
            </div>
          </div>
          <h5 class="text-start p-3 bg-info rounded-3">LOGÍSTICA</h5>
          <div class="row">
            <div class="col-12 text-start">
              <a
                href="./consultar-productos/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >PRODUCTOS E INSUMOS / KARDEX</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="./inventario-simple/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >INVENTARIO SIMPLE</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="./inventario-valorado/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >INVENTARIO VALORADO</a
              >
            </div>
            <div class="col-12 text-start">
              <a
                href="./petitorio/"
                class="btn btn-outline-secondary mb-2 w-100 text-start"
                >PETITORIO</a
              >
            </div>
          </div>
          <h5 class="text-start p-3 bg-info rounded-3">UTLIDADES</h5>
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
      <a class="btn btn-primary btn-lg" href="./login"
        >Iniciar sesión
        <nav></nav
      ></a>
      <?php } ?>
    </div>
  </div>
</div>
<?php
require "../inc/footer.php";
?>
