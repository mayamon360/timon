<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador registrarcredito/
*/
class registrarcreditoController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        # DATOS DEL MODULO
        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        $clientes = (new Model\Clientes)->clientes();

        global $config;

		$this->template->display('registrarcredito/registrarcredito', array(
            'datosModulo' => $datosModulo,
            'assets' => $config['build']['urlAssets'], # Para ir a la ruta del audio de error
            'clientes' => $clientes
        ));
    }
}