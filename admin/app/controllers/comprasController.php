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
 * Controlador compras/
*/
class comprasController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
       	
       	parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        # METODO Y ID
        $metodo = $router->getMethod();
        $folio = $router->getId();

        # DATOS DEL MODULO
        $datosModulo = (new Model\Menu)->datosModulo($router->getController());
        

        if($metodo !== null && $folio !== null){

            global $config;
            $c = new Model\Compras;

            if($metodo == 'nota'){
                $compra = $c->compraPor('folio',$folio);
                $entrada = $c->entradaPor('folio',$folio);
                # Si la compra existe
                if($compra){

                    ob_start();
                    $c->imprimirNota($folio,'compra');
                    $content = ob_get_clean();

                    $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', 0);
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('nota_compra '.$compra[0]['folio'].'.pdf');

                }else if($entrada) {

                    ob_start();
                    $c->imprimirNota($folio,'entrada');
                    $content = ob_get_clean();

                    $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', 0);
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('nota_ajuste '.$entrada[0]['folio'].'.pdf');

                } else{
                    # REDIRECCIONAR
                    Helper\Functions::redir($config['build']['url'].'compras');
                }
            }

            # RENDERIZAR MODULO PARA ADMINISTRAR PEDIDOS ------------------------------------------
        }else{
            $this->template->display('compras/compras', array(
                'datosModulo' => $datosModulo
            ));
        }
		
    }
}