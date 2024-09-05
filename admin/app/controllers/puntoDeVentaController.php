<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador puntodeventa/
*/
class puntodeventaController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        $almacen = (new Model\Almacenes)->almacenPrincipal($this->user['id_user']);
        $caja = (new Model\Caja)->cargarCaja('id_almacen', $almacen['id_almacen']);

        $cajaAbiertaHoy = ($caja) ? 'si' : 'no';

        # DATOS DEL MODULO
        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        $proveedores = (new Model\Proveedores)->proveedores();

        global $config;

	$this->template->display('puntodeventa/puntodeventa', array(
            'datosModulo' => $datosModulo,
            'assets' => $config['build']['urlAssets'], # Para ir a la ruta del audio de error
            'cajaAbiertaHoy' => $cajaAbiertaHoy,
            'proveedores' => $proveedores
        ));

    }
    
}