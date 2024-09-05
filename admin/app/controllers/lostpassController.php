<?php
        
namespace app\controllers;
        
use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;
        
/**
 * Controlador lostpass/
*/
class lostpassController extends Controllers implements IControllers {
        
    public function __construct(IRouter $router) {
        parent::__construct($router,array(
            'users_not_logged' => true
        ));
        //(new Model\Users)->changeTemporalPass();
    }
}