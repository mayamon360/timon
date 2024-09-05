<?php

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;
use Ocrend\Kernel\Router\IRouter;

# Llamar la clase y métodos de Html2Pdf para generar las notas
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

/**
 * Controlador creditos/
*/
class creditosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        
        parent::__construct($router, $configControler = [
        	'users_logged' => true,
            'module_access' => true
        ]);

        # OBTENER MÉTODO Y ID DE LA URL
        $metodo = $router->getMethod();
        $id = $router->getId(true);

        # OBTENER DATOS DEL MODULO
        $datosModulo = (new Model\Menu)->datosModulo($router->getController());
        
        # SI EL MÉTODO Y ID SON DIFERENTES A NULL (CARGAR OPCIONES ADICIONALES) 
        if($metodo !== null && $id !== null){
            
            global $config;

            # CARGAR CLASE CREDITOS
            $c = new Model\Creditos;

            # CONSULTAR CRÉDITO POR ID QUE VIENE DE LA URL
            $credito = $c->creditosPor('id_credito',$id);

            # SI EL CRÉDITO EXISTE
            if($credito){

                # SI EL METODO ES "EDITAR" CARGAR MODULO EDITAR
                if($metodo == 'editar'){

                    # GENERAR VARIABLES QUE SE ENVIARÁN A LA VISTA EDITAR
                    $almacen = (new Model\Almacenes)->almacenPrincipal($this->user['id_user']);
                    $caja = (new Model\Caja)->cargarCaja('id_almacen', $almacen['id_almacen']);
                    $cajaAbiertaHoy = ($caja) ? 'si' : 'no';
                    $cliente = (new Model\Clientes)->cliente($credito[0]['id_cliente']);
                    $estado = $credito[0]['estado'];
                    $fechaCredito = $credito[0]["fecha"].' '.$credito[0]["hora"];
                    $time = strtotime($fechaCredito);
                    $timeUnaHora = $time + 3600;
                    $hoy = date('Y-m-d H:i:s');
                    $f_hoy = Helper\Functions::fecha($hoy);
                    $f_ven = Helper\Functions::fecha($fechaCredito);
                    $f1 = substr($f_hoy,0,-17);
                    $f2 = substr($f_ven,0,-17);
                    if(time() < $timeUnaHora){
                        $fecha = Helper\Strings::amigable_time(strtotime($fechaCredito));
                    }elseif(substr($hoy, 0, -9) == substr($fechaCredito, 0, -9)){
                        $fecha = str_replace($f2, 'Hoy ', $f_ven);
                    }else{
                        $fecha = Helper\Functions::fecha($fechaCredito);
                    }

                    # RENDERIZAR MODULO PARA EDITAR ENVIANDO VARIABLES
                    $this->template->display('creditos/editar', array(
                        'datosModulo' => $datosModulo,
                        'folio' => $credito[0]['folio'],
                        'id_credito' => $credito[0]['id_credito'],
                        'cliente' => $cliente['cliente'],
                        'fecha' => $fecha,
                        'estado' => $estado,
                        'cajaAbiertaHoy' => $cajaAbiertaHoy
                    ));

                # SI EL METODO ES "NOTA" OBTENER EL TIPO DE NOTA (SALIDAS, DEVOLUCIONES, PAGOS, COMPLETA)
                }elseif($metodo == 'nota'){
                    
                    # OBTENER EL TIPO DE NOTA (VIENE EN URL DESPUES DEL METODO)
                    $router->setRoute('/tipo');
                    $tipo = $router->getRoute('/tipo');

                    # OBTENER LA FECHA (VIENEN EN LA URL DESPUES DEL TIPO)
                    $router->setRoute('/fecha');
                    $fecha = $router->getRoute('/fecha');

                    # SI EL TIPO ES DIFERENTE DE NULL (IDENTIFICAR EL TIPO DE NOTA A GENERAR)
                    if($tipo != '' && $tipo !== null){

                        $tiposNota = ['salidas', 'devoluciones', 'pagos'];
                        if(!in_array($tipo, $tiposNota)){
                            Helper\Functions::redir($config['build']['url'].'creditos');
                        }
                        if($fecha === null){
                            $fecha_txt = '';
                        }else{
                            $fecha_txt = ' '.$fecha;
                        }
                        ob_start();
                        $c->imprimirNota($id, $tipo, $fecha);
                        $content = ob_get_clean();
                        if( $tipo == 'pagos' || ($fecha !== null) ){
                            $html2pdf = new Html2Pdf('P', 'C7', 'es', true, 'UTF-8', 0);
                        }else{
                            $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', 0);
                        }
                        $html2pdf->pdf->SetDisplayMode('fullpage');
                        $html2pdf->writeHTML($content);
                        $html2pdf->output($tipo.' '.$credito[0]['folio'].$fecha_txt.'.pdf');
                    
                    # REDIRECCIONAR SI EL TIPO NO ESTÁ EN LAS OPCIONES (SALIDAS, ENTRADAS, PAGOS, COMPLETA)
                    }else{
                        ob_start();
                        $c->imprimirNota($id, 'completa', null);
                        $content = ob_get_clean();
                        $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', 0);
                        $html2pdf->pdf->SetDisplayMode('fullpage');
                        $html2pdf->writeHTML($content);
                        $html2pdf->output($credito[0]['folio'].'.pdf');
                        //Helper\Functions::redir($config['build']['url'].'creditos');
                    }

                }elseif($metodo == 'ticket'){
                    
                }
            
            # REDIRECCIONAR SI EL CREDITO NO EXISTE
            }else{
                Helper\Functions::redir($config['build']['url'].'creditos');
            }

        # RENDERIZAR MODULO PARA ADMINISTRAR CRÉDITOS
        }else{

            $this->template->display('creditos/creditos', array(
                'datosModulo' => $datosModulo
            ));

        }

    }
    
}