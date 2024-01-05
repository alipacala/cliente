<?php
require_once "../../inc/config.php";

function iniciarSesion($usuario, $clave)
{
  require_once('config.php');
  
  $pre = ENV == 'server' ? '/hotelarenasspa/cliente' : '/cliente';

  $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    session_start();
    $_SESSION['ultima_actividad'] = time();
    $_SESSION['logueado'] = true;
    
    $usuarioRespuesta = $result->fetch_assoc();
    unset($usuarioRespuesta['clave']);

    $_SESSION['usuario'] = $usuarioRespuesta;
    header("Location: /menu");
  }
}

function iniciarSesionColaborador($usuario, $clave)
{
  require_once('config.php');
  
  $pre = ENV == 'server' ? '/hotelarenasspa/cliente' : '/cliente';

  $sql = "SELECT id_usuario, p.nombres, p.apellidos, p.id_persona
  FROM usuarios us
  INNER JOIN personanaturaljuridica p ON p.id_persona = us.id_persona
  WHERE us.usuario = '$usuario' AND us.clave = '$clave' AND us.id_tipo_de_usuario = 2";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    session_start();
    $_SESSION['ultima_actividad'] = time();
    $_SESSION['logueado'] = true;
    
    $usuarioRespuesta = $result->fetch_assoc();
    unset($usuarioRespuesta['clave']);

    $_SESSION['usuario'] = $usuarioRespuesta;

    header("Location: /colaborador");
  }
  else {
    header("Location: /colaborador?error&mensaje=Credenciales incorrectos&op=consultar");
  }
}

function cerrarSesion()
{
  session_start();
  session_destroy();

  header("Location: /login");
}
?>