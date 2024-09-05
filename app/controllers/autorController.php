<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador autor/
*/
class autorController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);

        $metodo = $router->getMethod();
        $autor = (new Model\Autor)->autorPor("estado = 1 AND ruta = '$metodo'");

        if($autor){
		    $this->template->display('autor/autor', array(
                'metodo' => $metodo,
                'autor' => $autor['autor']
            ));
        }else{
            Helper\Functions::redir();
        }
    }
}