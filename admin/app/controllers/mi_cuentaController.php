<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador mi_cuenta/
*/
class mi_cuentaController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true
        ]);
        
		$this->template->display('mi_cuenta/mi_cuenta', array(
            'datosPerfil' => $this->user
        ));

    }
}