<?php
define("ENV", "lan");

switch (ENV) {
  case 'dev':
  case 'lan':
  case 'cloud':
    define("URL_API_NUEVA", "http://localhost/apitest");
    define("URL_API_CARLITOS", "http://localhost/php-api-carlitos");
    break;

  case 'server':
    define("URL_API_NUEVA", "http://192.168.1.11:8080/hotelarenasspa/api");
    define("URL_API_CARLITOS", "http://192.168.1.11:8080/hotelarenasspa/php-api-carlitos");
    define("URL", "http://192.168.1.11:8080/hotelarenasspa/cliente/views");
    break;
    
  case 'vm-prod':
    define("URL_API_NUEVA", "http://20.22.236.117/apitest");
    define("URL_API_CARLITOS", "http://20.22.236.117/php-api-carlitos");
    define("URL", "http://20.22.236.117/cliente/views");
    break;
}

define("TIEMPO_INACTIVIDAD", 3600 * 24); // 1 dia

// establece la zona horaria
date_default_timezone_set("America/Lima");
?>