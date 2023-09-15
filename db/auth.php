<?php
function iniciarSesion($usuario, $clave)
{
  require_once('config.php');

  $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    session_start();
    $_SESSION['ultima_actividad'] = time();
    $_SESSION['logueado'] = true;
    
    $usuarioRespuesta = $result->fetch_assoc();
    unset($usuarioRespuesta['clave']);

    $_SESSION['usuario'] = $usuarioRespuesta;
    header("Location: ./../");
  }
}

function cerrarSesion()
{
  session_start();
  session_destroy();
  
  header("Location: ./../");
}
?>