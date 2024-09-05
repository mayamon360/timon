<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador slider/
*/
class sliderController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configController = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $s = new Model\Slider;

        $sliders = $s->datosSliders();

        $datosModulo = (new Model\Menu)->datosModulo($router->getController()); 

		$this->template->display('slider/slider', array(
			'sliders' => $sliders,
            'datosModulo' => $datosModulo
		));
    }
}