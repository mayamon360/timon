<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador pedidoscompras/
*/
class pedidoscomprasController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        
        parent::__construct($router, $configController = [
        	'users_logged' => true,
        	'module_access' => true
        ]);

        $almacen = (new Model\Almacenes)->almacenPrincipal($this->user['id_user']);
        $caja = (new Model\Caja)->cargarCaja('id_almacen', $almacen['id_almacen']);

        $cajaAbiertaHoy = ($caja) ? 'si' : 'no';

        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

		$this->template->display('pedidoscompras/pedidoscompras', array(
            'datosModulo' => $datosModulo,
            'cajaAbiertaHoy' => $cajaAbiertaHoy
		));
    }
}