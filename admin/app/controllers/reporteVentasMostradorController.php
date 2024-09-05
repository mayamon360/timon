<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador reporteventasmostrador/
*/
class reporteventasmostradorController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);
        
        # DATOS DEL MODULO
        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        $r = new Model\ReporteVentasMostrador;

        $metodo = $router->getMethod();
        $fi = $router->getId();

        if($metodo != '' && $metodo !== null){
            if($metodo == 'descargar_reporte'){
                if($fi != '' && $fi !== null){
                    $router->setRoute('/ff');
                    $ff = $router->getRoute('/ff');
                    if($ff != '' && $ff !== null){
                        $r->descargarReporteVentas($fi, $ff);
                    }
                }
            }
        }else{
            $this->template->addExtension(new Helper\Strings);
            $this->template->display('reporteventasmostrador/reporteventasmostrador', array(
                'datosModulo' => $datosModulo
            ));
        }
    }
}