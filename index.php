<?php

use Ocrend\Kernel\Router\Router;

# Definir el path
define('___ROOT___', '');

# Iniciar la configuracion
require ___ROOT___ . 'Ocrend/Kernel/Config/Config.php';

# Ejecutar controlador solicitado
(new Router)->executeController();