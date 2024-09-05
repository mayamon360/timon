<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador registrarcompras/
*/
class registrarcomprasController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        
        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        # DATOS DEL MODULO
        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        global $config;

		$this->template->display('registrarcompras/registrarcompras', array(
            'assets' => $config['build']['urlAssets'],
            'datosModulo' => $datosModulo,
            'factura' => strtoupper(uniqid())
        ));
	}
}