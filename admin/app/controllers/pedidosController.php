<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

/**
 * Controlador pedidos/
*/
class pedidosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
    	
        parent::__construct($router, $configController = [
        	'users_logged' => true,
        	'module_access' => true
        ]);

        # METODO Y ID
        $metodo = $router->getMethod();
        $id = $router->getId(true);

        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        if($metodo !== null && $id !== null){

            global $config;
            $p = new Model\Pedidos;

            if($metodo == 'nota'){
                $pedido = $p->pedido($id);
                # Si el pedido existe
                if($pedido){
                    
                    /*if($pedido['estado'] == 1){*/
                        ob_start();
                        $p->imprimirNota($id,$pedido['folio']);
                        $content = ob_get_clean();
    
                        $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', 0);
                        $html2pdf->pdf->SetDisplayMode('fullpage');
                        $html2pdf->writeHTML($content);
                        $html2pdf->output('Lista de pedidos '.$pedido['folio'].'.pdf');
                    /*}else{
                        # REDIRECCIONAR
                        Helper\Functions::redir($config['build']['url'].'pedidos');
                    }*/

                }else{
                    # REDIRECCIONAR
                    Helper\Functions::redir($config['build']['url'].'pedidos');
                }
            }elseif($metodo == 'ver'){
                
                $pedido = $p->pedido($id);
                if($pedido){
                    $this->template->display('pedidos/verpedido', array(
                        'datosModulo' => $datosModulo,
                        'folio' => $pedido['folio'],
                        'id' => $id
            		));
                }else{
                    # REDIRECCIONAR
                    Helper\Functions::redir($config['build']['url'].'pedidos');
                }

            }elseif($metodo == 'ticket'){
                
                $folio_pedido_detalle = $p->folioPedidoDetalle($id);
                
                if($folio_pedido_detalle){
                    ob_start();
                    $p->imprimirTicket($id);
                    $content = ob_get_clean();
                    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 0);
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('ticket pedido '.$folio_pedido_detalle['folio_pedido'].'.pdf');
                    
                }else{
                    # REDIRECCIONAR
                    Helper\Functions::redir($config['build']['url'].'pedidos');
                }
            }

        # RENDERIZAR MODULO PARA ADMINISTRAR PEDIDOS ------------------------------------------
        }else{
    		$this->template->display('pedidos/pedidos', array(
                'datosModulo' => $datosModulo
    		));
        }

    }
}