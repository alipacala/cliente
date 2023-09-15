<?php
require "../../db/auth.php";

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'POST') {
  cerrarSesion();
}
?>