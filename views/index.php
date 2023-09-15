<?php
require "../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido > TIEMPO_INACTIVIDAD)) {
  session_unset();
  session_destroy();
}
$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("", $logueado);
?>
<div class="container my-5 main-cont">
  <div class="p-5 bg-body-tertiary rounded-3">
    <div class="container-fluid py-5 text-center">
      <h1 class="display-5 fw-bold">Bienvenido</h1>
      <?php if ($logueado) { ?>
        <p class="col-md-8 fs-4 mx-auto">Bienvenido al sistema de Hotel Arenas y Spa<br>Acceda a las funciones del sistema
          mediante los menús de arriba</p>
      <?php } else { ?>
        <p class="col-md-8 fs-4 mx-auto">Bienvenido al sistema de Hotel Arenas y Spa<br>Para acceder a las funciones del
          sistema, inicia sesión</p>
        <a class="btn btn-primary btn-lg" href="./login">Iniciar sesión<nav></nav></a>
      <?php } ?>
    </div>
  </div>
</div>
<?php
require "../inc/footer.php";
?>