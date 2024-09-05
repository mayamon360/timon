<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador clientes/
*/
class clientesController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $datosModulo = (new Model\Menu)->datosModulo($router->getController()); 

		$this->template->display('clientes/clientes', array(
			'datosModulo' => $datosModulo
		));
		
    }
}