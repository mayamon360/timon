<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador informacion/
*/
class informacionController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);
        
        $controlador = $router->GetController();
        $metodo = $router->getMethod();
        
        switch ($metodo) {
            case 'contacto':
                $this->template->display('informacion/informacion');
                break;
            case 'ayuda':
                $this->template->display('informacion/ayuda');
                break;
            default:
                Helper\Functions::redir();
                break;
        }
    }
}