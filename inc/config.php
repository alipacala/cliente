<?php
define("ENV", "prod");

switch (ENV) {
  case 'dev':
  case 'lan':
  case 'cloud':
    define("URL_API_NUEVA", "http://localhost/apitest");
    define("URL_API_CARLITOS", "http://localhost/php-api-carlitos");
    define("URL", "http://localhost");
    break;

  case 'server':
    define("URL_API_NUEVA", "http://192.168.1.11:8080/hotelarenasspa/api");
    define("URL_API_CARLITOS", "http://192.168.1.11:8080/hotelarenasspa/php-api-carlitos");
    define("URL", "http://192.168.1.11:8080/hotelarenasspa");
    break;

  case 'vm-prod':
    define("URL_API_NUEVA", "http://20.22.236.117/apitest");
    define("URL_API_CARLITOS", "http://20.22.236.117/php-api-carlitos");
    define("URL", "http://20.22.236.117");
    break;

  case 'prod':
    define("URL_API_NUEVA", "https://arenashotelspa.online/apitest");
    define("URL_API_CARLITOS", "https://arenashotelspa.online/php-api-carlitos");
    define("URL", "https://arenashotelspa.online");
    break;
}

define("TIEMPO_INACTIVIDAD", 3600 * 24); // 1 dia

// establece la zona horaria
date_default_timezone_set("America/Lima");
?>