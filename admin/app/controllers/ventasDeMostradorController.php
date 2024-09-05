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
 * Controlador ventasdemostrador/
*/
class ventasdemostradorController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {

        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        # METODO Y ID
        $metodo = $router->getMethod();
        $id = $router->getId(true);

        # DATOS DEL MODULO
        $datosModulo = (new Model\Menu)->datosModulo($router->getController());

        if($metodo !== null && $id !== null){

            global $config;
            $v = new Model\VentasDeMostrador;

            # CARGAR MODULO EDITAR ---------------------------------------------------------------
            if($metodo == 'editar'){
                
                # CONSULTAR VENTA
                $venta = $v->ventasPor('id_salida',$id);
                
                # Si la venta existe
                if($venta && $venta[0]['estado'] == 1 && $venta[0]['metodo_pago'] != 'puntos' && $venta[0]['metodo_pago'] !== null && $venta[0]['metodo_pago'] != ''){

                    # VALIDAR CAJA
                    $caja = (new Model\Caja)->cargarCaja();
                    $cajaAbiertaHoy = ($caja) ? 'si' : 'no';

                    $venta = $venta[0];
                    $estado = $venta['estado'];
                    $cliente = (new Model\Clientes)->cliente($venta['id_cliente']);

                    $time = strtotime($venta["fechaVenta"]);
                    $timeUnaHora = $time + 3600;
                    $hoy = date('Y-m-d H:i:s');
                    $f_hoy = Helper\Functions::fecha($hoy);
                    $f_ven = Helper\Functions::fecha($venta["fechaVenta"]);
                    $f1 = substr($f_hoy,0,-17);
                    $f2 = substr($f_ven,0,-17);
                    if(time() < $timeUnaHora){
                        $fecha = Helper\Strings::amigable_time(strtotime($venta["fechaVenta"]));
                    }elseif(substr($hoy, 0, -9) == substr($venta['fechaVenta'], 0, -9)){
                        $fecha = str_replace($f2, 'Hoy ', $f_ven);
                    }else{
                        $fecha = Helper\Functions::fecha($venta["fechaVenta"]);
                    }

                    # RENDERIZAR MODULO PARA EDITAR
                    $this->template->display('ventasdemostrador/editarventademostrador', array(
                        'datosModulo' => $datosModulo,
                        'cajaAbiertaHoy' => $cajaAbiertaHoy,
                        'folio' => $venta['folio'],
                        'id_venta' => $venta['id_salida'],
                        'cliente' => $cliente['cliente'],
                        'mp' => $venta['metodo_pago'],
                        'pago' => $venta["pago"],
                        'cambio' => $venta['cambio'], 
                        'transaccion' => $venta['transaccion'],
                        'fecha' => $fecha,
                        'estado' => $estado
                    ));
                # Si la venta no existe
                }else{
                    # REDIRECCIONAR
                    Helper\Functions::redir($config['build']['url'].'ventasDeMostrador');
                }
            }elseif($metodo == 'ticket'){

                $venta = $v->venta($id);  // venta

                if($venta){

                    ob_start();
                    $v->imprimirTicket($venta['id_salida']);
                    $content = ob_get_clean();

                    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 0);
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('ticket '.$venta['folio'].'.pdf');

                }else{
                    # REDIRECCIONAR
                    Helper\Functions::redir($config['build']['url'].'ventasDeMostrador');
                }

            }elseif($metodo == 'nota'){
                
                $salida = $v->salida($id); // consignacion
                
                $ajuste = $v->venta($id); // ajuste

                if($salida){

                    ob_start();
                    $v->imprimirNota($salida['id_consignacion'], 'consignacion');
                    $content = ob_get_clean();

                    $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', 0);
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('nota '.$salida['folio'].'.pdf');

                }elseif($ajuste){
                
                    ob_start();
                    $v->imprimirNota($ajuste['id_salida'], 'ajuste');
                    $content = ob_get_clean();

                    $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', 0);
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('nota '.$salida['folio'].'.pdf');
                    
                }else{
                    # REDIRECCIONAR
                    Helper\Functions::redir($config['build']['url'].'ventasDeMostrador');
                }

            }

        # RENDERIZAR MODULO PARA ADMINISTRAR VENTAS ------------------------------------------
        }else{
            $this->template->display('ventasdemostrador/ventasdemostrador', array(
                'datosModulo' => $datosModulo
            ));
        }	

	}

}