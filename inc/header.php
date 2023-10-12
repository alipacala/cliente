<?php
require_once "config.php";

function mostrarHeader($pagina, $logueado)
{
  $pre = ENV == 'server' ? '/hotelarenasspa' : '';

  if ($logueado) {
    if ($pagina == "login") {
      header("Location: ../");
    }
  } else {
    if ($pagina == "pagina-funcion") {
      header("Location: $pre/cliente/views/login");
    }
  }

  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?php echo $pre ?>/cliente/css/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo $pre ?>/cliente/css/fontawesome/css/brands.min.css">
    <link rel="stylesheet" href="<?php echo $pre ?>/cliente/css/fontawesome/css/solid.min.css">
    <link rel="stylesheet" href="<?php echo $pre ?>/cliente/css/styles.css">
    <title>Arenas Hotel y Spa</title>
  </head>

  <body>
    <nav class="navbar navbar-expand-lg bg-primary sticky-top">
      <div class="container">
        <a class="navbar-brand d-flex" href="<?php echo $pre ?>/cliente/views">
          <img src="<?php echo $pre ?>/cliente/img/logo.webp" alt="logo"
            class="d-inline-block align-text-top img-fluid w-50"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link active text-white" aria-current="page" href="<?php echo $pre ?>/cliente/views">Inicio</a>
            </li>
            <?php if ($logueado) { ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Spa </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/registrar-cliente-spa">Registro
                      Spa</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/relacion-clientes-hotel-spa">Relación
                      de clientes de Hotel Spa</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/registro-ventas">Registro de ventas</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Hotel </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/gestionar-reservas">Gestionar
                      Reservas</a>
                  </li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/gestionar-checkin-hotel">Gestionar
                      Checkin de Hotel</a>
                  </li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/listado-reserva">Listado de
                      reservas</a>
                  </li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/listado-rooming">Listado de
                      rooming</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Productos </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/gestionar-grupos-catalogo">Gestionar
                      grupos de catálogo</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/listado-catalogo">Listado de
                      catálogo</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/cambiar-precio-catalogo">Cambiar
                      precios de catálogo</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/crear-producto">Crear producto
                      terminado / insumo</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/crear-producto-hospedaje">Crear
                      producto de hospedaje</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/crear-producto-receta">Crear producto
                      de receta</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/crear-producto-servicio">Crear
                      producto de servicio</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/crear-producto-paquete">Crear producto
                      de paquete / combo</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Compras </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/relacion-cuentas-por-pagar">Relación de Cuentas por Pagar</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/registro-ingreso-egreso">Registro de ingreso / egreso</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Kardexes </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/consultar-productos">Consultar productos / insumos</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Usuarios </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/gestionar-terapistas">Registro de
                      Personal/Terapistas</a>
                  </li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/gestionar-usuarios">Registro de
                      Usuarios</a>
                  </li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/gestionar-modulos">Registro de
                      Módulos</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Reportes </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/reporte-caja-diario">Reporte diario de caja</a>
                  </li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/reporte-productos-diario">Reporte diario de ventas al detalle</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Tablas </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/gestionar-tipos-gasto">Gestionar Tipos de gastos</a></li>
                  <li><a class="dropdown-item" href="<?php echo $pre ?>/cliente/views/gestionar-unidades-negocio">Gestionar Unidades de negocio</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <form action="<?php echo $pre ?>/cliente/views/login/cerrar_sesion.php" method="POST">
                  <input class="btn btn-outline-danger text-white" type="submit" value="Cerrar sesión">
                </form>
              </li>
            <?php } else if ($pagina == "root") { ?>
                <li class="nav-item">
                  <a class="btn btn-outline-light" aria-current="page" href="<?php echo $pre ?>/cliente/views/login">Iniciar
                    sesión</a>
                </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>
  <?php } ?>