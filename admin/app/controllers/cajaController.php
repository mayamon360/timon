<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador caja/
*/
class cajaController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
       	
       	parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        $c = new Model\Caja;

        $metodo = $router->getMethod();
        $fecha = $router->getId();

        if($metodo != '' && $metodo !== null){
            if($metodo == 'descargar_reporte'){
                if($fecha != '' && $fecha !== null){
                    $c->descargarReporteCaja($fecha);
                }
            }
        }else{
            $this->template->display('caja/caja', array(
                'datosModulo' => $datosModulo
            ));
        }		
    }
}