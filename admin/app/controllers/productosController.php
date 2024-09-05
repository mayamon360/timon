<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador productos
 * 
*/
class productosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        
        // Control de acceso
        parent::__construct($router, $configController = [
            // A usuarios logeados
        	'users_logged' => true,
        	// A modulo
            'module_access' => true
        ]);
        
        // Datos del modulo
        $datosModulo = (new Model\Menu)->datosModulo($router->getController());
        
        // Llamar a la clase Productos
        $p = new Model\Productos;
        // Llamar a la clase Categorias
        $c = new Model\Categorias;
        
        // Obtener las catgeorias
        $categorias = $c->categorias();
        
        // Metodo en URL despues de la ruta base del modulo https://dominio.com/admin/productos/->
        $metodo = $router->getMethod();
        // ID en URL despues de la ruta base del modulo mas la de metodo https://dominio.com/admin/productos/metodo/->
        $id_producto = (int) $router->getId();

        // Metodo diferente de vacio o null y id diferente de 0
        if( $metodo != '' && $metodo !== null && $id_producto != 0 ){
            // Si e metodo es 'movimientos'
            if($metodo == 'movimientos'){
                // Descargar movimientos del producto
                $p->descargarMovimientos($id_producto);
            }
        // Sin metodo ni id
        }else{
            // Mostrar template productos
            $this->template->display('productos/productos',array(
    			'categorias' => $categorias,            # enviar datos de categorias
                'datosModulo' => $datosModulo           # enviar datos del modulo
    		));   
        }
    }
}