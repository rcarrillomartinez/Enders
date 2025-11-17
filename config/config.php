<?php
// Base de datos centralizada
// Edita estos valores para que coincidan con tu entorno local

if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_PORT')) define('DB_PORT', 3306);
if (!defined('DB_NAME')) define('DB_NAME', 'uoc_transfers');
if (!defined('DB_USER')) define('DB_USER', 'roqueCM');
if (!defined('DB_PASS')) define('DB_PASS', '8591RCM');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

// Define la ruta base para asegurar imports correctos
define('ROOT_PATH', __DIR__ . '/'); 

// Otras configuraciones, como el modo de desarrollo, etc.
define('DEBUG_MODE', true);