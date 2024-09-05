<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador autenticacion/
*/
class autenticacionController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router, $configController = [
            'users_logged' => false,
            'users_not_logged' => true
        ]);
        
        global $http;
        $action = $http->query->get('action');

        $metodo = $router->getMethod();
        if($metodo == 'registro'){
            $this->template->display('autenticacion/registro');
        }elseif($metodo == 'recuperar'){

            $change_password = '';
            if($action == 'changeTemporalPassword'){
                $change_password = (new Model\Usuarios)->$action();
            }
            $this->template->display('autenticacion/recuperar', array(
                'change_password' => $change_password
            ));

        }else{

            $activar_cuenta = '';
            if($action == 'activateAccount'){
                $activar_cuenta = (new Model\Usuarios)->$action();
            }
            $this->template->display('autenticacion/autenticacion', array(
                'activar_cuenta' => $activar_cuenta
            ));

        }
		
    }
}