<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador reportecreditos/
*/
class reportecreditosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);
		//$this->template->display('reportecreditos/reportecreditos');
    }
}