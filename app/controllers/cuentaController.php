<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

use MercadoPago;

/**
 * Controlador cuenta/
*/
class cuentaController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router, $configController = [
        	'users_logged' => true
        ]);

        global $http,$config;
        $controlador = $router->GetController();
        $metodo = $router->getMethod();
        $folio = $router->getId();

        switch ($metodo) {
            case 'mis-datos':
                $this->template->display('cuenta/mis-datos', array(
                    'controlador' => $controlador,
                    'metodo' => $metodo
                ));
                break;
            case 'lista-deseos':
                $this->template->display('cuenta/lista-deseos', array(
                    'controlador' => $controlador,
                    'metodo' => $metodo
                ));
                break;
            case 'mis-compras':
                if($http->query->get('compra') == 'nueva'){
                    (new Model\Compra)->limpiarCarrito();
                    Helper\Functions::redir($config['build']['url'].'cuenta/mis-compras');
                }else{
                    $this->template->display('cuenta/mis-compras', array(
                        'controlador' => $controlador,
                        'metodo' => $metodo
                    ));
                }
                break;
            case 'cambiar-contrasena':
                $this->template->display('cuenta/cambiar-contrasena', array(
                    'controlador' => $controlador,
                    'metodo' => $metodo
                ));
                break;
            case 'eliminar-cuenta':
                $this->template->display('cuenta/eliminar-cuenta', array(
                    'controlador' => $controlador,
                    'metodo' => $metodo
                ));
                break;
                
            case 'ticket':
                
                $c = new Model\Compra;
                $compra = $c->compra($folio);  // compra
                
                if ($compra){
                    $c->imprimirTicket($folio);
                } else {
                    # REDIRECCIONAR
                    Helper\Functions::redir($config['build']['url'].'cuenta/mis-compras');
                }
                
                break;
            
            default:
                Helper\Functions::redir();
                break;
        }
    }
}