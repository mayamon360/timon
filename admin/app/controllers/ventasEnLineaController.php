<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;
use Stripe\Stripe;

/**
 * Controlador ventasEnLinea/
*/
class ventasEnLineaController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $datosModulo = (new Model\Menu)->datosModulo($router->getController());
        $ruta = $this->method;
        if($ruta != null){
            if($ruta == 'reporteVentas'){
                $ventas = $v->reporteVentas();    
            }else{
                $this->template->display('ventasEnLinea/ventasEnLinea', array(
                    'datosModulo' => $datosModulo
                ));
            }
        }else{
            $this->template->display('ventasEnLinea/ventasEnLinea', array(
                'datosModulo' => $datosModulo
            ));
        }

    }

}