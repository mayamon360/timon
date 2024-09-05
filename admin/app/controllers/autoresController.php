<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador autores/
*/
class autoresController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
    	
        parent::__construct($router, $configController = [ //agregamos el arreglo de configuraci贸n sobre el controlador para solo ser visto por usuarios logeados
        	'users_logged' => true,
        	'module_access' => true
        ]);

        $datosModulo = (new Model\Menu)->datosModulo($router->getController()); 

		$this->template->display('autores/autores', array(
			'datosModulo' => $datosModulo
		));
		
    }
}