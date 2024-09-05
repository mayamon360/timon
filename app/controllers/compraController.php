<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

/**
 * Controlador compra/
*/
class compraController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);
        
        global $config,$session,$http;
        
        $metodo = $router->getMethod();
        
        // Si el metodo esta vacio
        if($metodo == '' || $metodo === null){
            
            $this->template->display('compra/compra', [
                "error_get" => $http->query->get('error')
            ]);
        
        // Si el metodo es 'procesar'
        }elseif($metodo == 'procesar'){
            
            // Si el usuario no esta conectado
            if(empty($this->user)){
                
                // Redireccionar a login
                Helper\Functions::redir($config['build']['url'].'autenticacion');
            
            // Si el usuario esta conectado 
            }else{

                /*if($this->user["id_cliente"] != 6){
                    Helper\Functions::redir($config['build']['url']);
                }*/
                
                // Cantidad total de los productos en la lista (suma de cantidades)
                $cantidad_compra = (int) (new Model\Compra)->cantidadCarrito();
                
                // Si la cantidad en compra es mayor a 0
                if($cantidad_compra > 0){
                    
                    // Validar si cambio el stock de alguno de los productos
                    $cambioStock = (new Model\Compra)->cambioStock();
                    // Si el stock cambio redirigir a  
                    if($cambioStock){
                        Helper\Functions::redir($config['build']['url'].'compra?error=cambio_stock');
                    }else{
                        if($http->query->get('error') !== null){
                            $message = $http->query->get('message');
                        }else{
                            $message = '';
                        }
                        $this->template->display('compra/procesar', [
                            "telefono" => $this->user["telefono"],
                            "cp" => $this->user["p_code"],
                            "rfc" => $this->user["RFC"],
                            "url_post" => $config["build"]["url"]."compra/guardar",
                            "message" => $message
                        ]);
                        
                    }
                    
                }else{
                    // Redireccionar a compra si la cantidad es 0
                    Helper\Functions::redir($config['build']['url'].'compra');
                }
                
            }
        
        }elseif($metodo == 'guardar'){
            
            // Si el usuario no esta conectado
            if(empty($this->user)){
                
                Helper\Functions::redir($config['build']['url'].'autenticacion');
                
            }else{    
                
                global $http;
                // Obtener stripeToken
                $stripeToken = $http->request->get('stripeToken');
                (new Model\Compra)->registrarCompra($stripeToken);
                
            }
        
        }else{
            Helper\Functions::redir($config['build']['url'].'compra');
        }
    }
}