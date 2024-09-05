<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador comercio/
*/
class comercioController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
    	
        parent::__construct($router, $configController = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $c = new Model\Comercio;

        $plantilla = $c->datosPlantilla();
        $comercio = $c->datosComercio();
        $datosModulo = (new Model\Menu)->datosModulo($router->getController()); 

        $redesSociales = json_decode($plantilla["redesSociales"], true);

        $clase = substr($redesSociales[0]["clase"],-1);

		$this->template->display('comercio/comercio', array(
			'plantilla' => $plantilla,
            'redesSociales' => $redesSociales,
            'clase' => $clase,
            'comercio' => $comercio,
            'datosModulo' => $datosModulo
		));
    }
}