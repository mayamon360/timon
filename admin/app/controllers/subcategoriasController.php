<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador subcategorias/
*/
class subcategoriasController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configController = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $c = new Model\Categorias;
        
        $categorias = $c->categorias();

        $datosModulo = (new Model\Menu)->datosModulo($router->getController()); 

		$this->template->display('subcategorias/subcategorias',array(
			'categorias' => $categorias,
            'datosModulo' => $datosModulo
		));
    }
}