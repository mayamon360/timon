<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador vistas/
*/
class visitasController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $h = new Model\Home;
        $v = new Model\Visitas;

        $ruta = $this->method;

        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        if($ruta != null){
            if($ruta == 'reporteVisitas'){
                $visitas = $v->reporteVisitas();    
            }else{
                $visitasMX = $v->visitasMX();
                $totalVisitasMX = $v->totalVisitasMX();
                $this->template->display('visitas/visitas', array(
                    'visitasMX' => $visitasMX,
                    'totalVisitasMX' => (real) $totalVisitasMX,
                    'datosModulo' => $datosModulo
                ));
            }
        }else{
			$visitasMX = $v->visitasMX();
            $totalVisitasMX = $v->totalVisitasMX();
            $this->template->display('visitas/visitas', array(
                'visitasMX' => $visitasMX,
                'totalVisitasMX' => (real) $totalVisitasMX,
                'datosModulo' => $datosModulo
            ));
        }

    }
}