<?php
require_once "config.php";

function mostrarHeader($pagina = "", $logueado = false)
{
  $pre = ENV == 'server' ? '/hotelarenasspa/cliente' : '/cliente';

  if ($pagina != "colaborador" && $logueado) {
    if ($pagina == "login") {
      header("Location: ../");
    }
  } else if ($pagina == "colaborador" && $logueado) {
    header("Location: /colaborador");
  } else {
    if ($pagina == "pagina-funcion") {
      header("Location: /login");
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
    <link rel="stylesheet" href="<?php echo $pre ?>/css/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo $pre ?>/css/fontawesome/css/brands.min.css">
    <link rel="stylesheet" href="<?php echo $pre ?>/css/fontawesome/css/solid.min.css">
    <link rel="stylesheet" href="<?php echo $pre ?>/css/styles.css">
    <title>Arenas Hotel y Spa</title>
  </head>

  <body>
    <nav class="navbar navbar-expand-lg bg-primary sticky-top">
      <div class="container">
        <a class="navbar-brand d-flex"
          href="<?php echo $pagina == 'visitante' || $pagina == 'terapeuta' ? "#" : "/menu" ?>">
          <img src="<?php echo $pre ?>/img/logo.webp" alt="logo" class="d-inline-block align-text-top img-fluid w-50"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link active text-white" aria-current="page"
                href="<?php echo $pagina == 'visitante' || $pagina == 'colaborador' || $pagina == 'terapeuta' ? "#" : "/menu" ?>">Inicio</a>
            </li>
            <?php if ($logueado) { ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Spa </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/registrar-cliente-spa">Registro Spa</a></li>
                  <li><a class="dropdown-item" href="/relacion-clientes-hotel-spa">Relación de clientes de Hotel Spa</a>
                  </li>
                  <li><a class="dropdown-item" href="/registro-ventas">Registro de ventas</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Hotel </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/gestionar-reservas">Gestionar Reservas</a>
                  </li>
                  <li><a class="dropdown-item" href="/gestionar-checkin-hotel">Gestionar Checkin de Hotel</a>
                  </li>
                  <li><a class="dropdown-item" href="/listado-reserva">Listado de reservas</a>
                  </li>
                  <li><a class="dropdown-item" href="/listado-rooming">Listado de rooming</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Productos </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/gestionar-grupos-catalogo">Gestionar grupos de catálogo</a></li>
                  <li><a class="dropdown-item" href="/listado-catalogo">Listado de catálogo</a></li>
                  <li><a class="dropdown-item" href="/cambiar-precio-catalogo">Cambiar precios de catálogo</a></li>
                  <li><a class="dropdown-item" href="/crear-producto">Crear producto terminado / insumo</a></li>
                  <li><a class="dropdown-item" href="/crear-producto-hospedaje">Crear producto de hospedaje</a></li>
                  <li><a class="dropdown-item" href="/crear-producto-receta">Crear producto de receta</a></li>
                  <li><a class="dropdown-item" href="/crear-producto-servicio">Crear producto de servicio</a></li>
                  <li><a class="dropdown-item" href="/crear-producto-paquete">Crear producto de paquete / combo</a></li>
                  <li><a class="dropdown-item" href="/petitorio">Petitorio</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Compras </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/relacion-cuentas-por-pagar">Relación de Cuentas por Pagar</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Logística </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/consultar-productos">Consultar productos / insumos</a></li>
                  <li><a class="dropdown-item" href="/registro-ingreso-egreso">Registro de ingreso / egreso</a></li>
                  <li><a class="dropdown-item" href="/listado-movimientos">Listado de movimientos</a></li>
                  <li><a class="dropdown-item" href="/inventario-simple">Inventario simple</a></li>
                  <li><a class="dropdown-item" href="/inventario-valorado">Inventario valorado</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Usuarios </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/gestionar-terapistas">Registro de Personal/Terapistas</a>
                  </li>
                  <li><a class="dropdown-item" href="/gestionar-usuarios">Registro de Usuarios</a>
                  </li>
                  <li><a class="dropdown-item" href="/gestionar-modulos">Registro de Módulos</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Reportes </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/reporte-caja-diario">Reporte diario de caja</a>
                  </li>
                  <li><a class="dropdown-item" href="/reporte-productos-diario">Reporte diario de ventas al detalle</a>
                  </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false"> Tablas </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/gestionar-tipos-gasto">Gestionar Tipos de gastos</a></li>
                  <li><a class="dropdown-item" href="/gestionar-unidades-negocio">Gestionar Unidades de negocio</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <form action="<?php echo $pre ?>/views/login/cerrar_sesion.php" method="POST">
                  <input class="btn btn-outline-danger text-white" type="submit" value="Cerrar sesión">
                </form>
              </li>
            <?php } else if ($pagina == "root") { ?>
                <li class="nav-item">
                  <a class="btn btn-outline-light" aria-current="page" href="/login">Iniciar sesión</a>
                </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>
  <?php } ?>