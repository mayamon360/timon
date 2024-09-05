<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador usuarios/
*/
class usuariosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $u = new Model\Usuarios;

        $ruta = $this->method;

        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        if($ruta != null){
        	if($ruta == 'correos'){
        		$correos = $u->correos(); 
        	}else{
        		$this->template->display('usuarios/usuarios', array(
                    'datosModulo' => $datosModulo
                ));
        	}
        }else{
        	$this->template->display('usuarios/usuarios', array(
                'datosModulo' => $datosModulo
            ));
        }
		
    }
}