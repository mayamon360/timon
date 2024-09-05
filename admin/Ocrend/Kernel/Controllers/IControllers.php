<?php

namespace Ocrend\Kernel\Controllers;

use Ocrend\Kernel\Router\IRouter;

/**
 * Estructura elemental para el correcto funcionamiento de cualquier controlador en el sistema.    
 *
 */
interface IControllers {
    public function __construct(IRouter $router);
}