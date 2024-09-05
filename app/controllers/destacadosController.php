<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador destacados/
*/
class destacadosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        $controlador = $router->getController();
        $ruta = $router->getMethod();

        if($ruta == 'novedades'){
            $this->template->display('destacados/novedades');
        }elseif($ruta == 'mas-vendidos'){
            $this->template->display('destacados/mas-vendidos');
        }else{
            Helper\Functions::redir();
        }
		
    }
}