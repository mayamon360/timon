<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador editorial/
*/
class editorialController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        $metodo = $router->getMethod();
        $editorial = (new Model\Editorial)->editorialPor("estado = 1 AND ruta = '$metodo'");

        if($editorial){
            $this->template->display('editorial/editorial', array(
                'metodo' => $metodo,
                'editorial' => $editorial['editorial']
            ));
        }else{
            Helper\Functions::redir();
        }
    }
}