<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador busqueda/
*/
class busquedaController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        global $http;

        $consulta = $http->query->get('consulta');

        if($consulta == null || $consulta == '' || Helper\Functions::emp($consulta)){
            Helper\Functions::redir();
        }else{
            $this->template->display('busqueda/busqueda', array(
                'consulta' => $consulta
            ));
        }		
    }
}