<?php
         
namespace app\controllers;
        
use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;
        
/**
 * Controlador login/
*/
class loginController extends Controllers implements IControllers {
        
    public function __construct(IRouter $router) {
        parent::__construct($router,array(
            'users_not_logged' => true
        ));
        
        global $config;
        
        $pass = Helper\Strings::hash("smmc130813");
        //echo $pass;
        
        $m = new Model\MovilDeteccion;
        if ( $m->isMobile() ) {
     	    # REDIRECCIONAR
            Helper\Functions::redir($config['build']['urlAssetsPagina']);
    	}else{
    	    $appName = $config['build']['name'];

            $this->template->display('login/login',array(
        	'appName' => $appName
            ));
    	}

    }
}