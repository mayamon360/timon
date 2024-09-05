<?php

namespace app\models;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Models\Models;
use Ocrend\Kernel\Models\IModels;
use Ocrend\Kernel\Models\ModelsException;
use Ocrend\Kernel\Models\Traits\DBModel;
use Ocrend\Kernel\Router\IRouter;

/**
 * Modelo Compras
 */
class Compras extends Models implements IModels {
    use DBModel;

    public function compras(){
        $compras = $this->db->select('*', 'entrada', null, null, null, "ORDER BY id_entrada DESC");
        return $compras;
    }

    public function compra($id){
        $compra = $this->db->select('*', 'entrada', null, "id_entrada = '$id'", null, "ORDER BY id_entrada DESC");
        return $compra;
    }

    public function compraPor($item, $valor){
        $compra = $this->db->select('*', 'entrada', null, "$item = '$valor'", null, "ORDER BY id_entrada DESC");
        return $compra;
    }

    public function compraDetalle($item, $valor){
        $compras = $this->db->select('*','entrada_detalle', null,"$item = '$valor'");
        if($compras){
            return $compras;
        }
        return false;
    }

    public function entradas(){
        $entradas = $this->db->select('*', 'consignacion', null, "tipo = 'entrada'", null, "ORDER BY id_consignacion DESC");
        return $entradas;
    }
    public function entradaPor($item, $valor){
        $entrada = $this->db->select('*', 'consignacion', null, "$item = '$valor' AND tipo = 'entrada'", null, "ORDER BY id_consignacion DESC");
        return $entrada;
    }
    public function entradaDetalle($item, $valor){
        $entrada = $this->db->select('*','consignacion_detalle', null,"$item = '$valor'");
        if($entrada){
            return $entrada;
        }
        return false;
    }


    /**
     * Respuesta generada por defecto para el endpoint
     * 
     * @return array
    */ 
    public function mostrarCompras() : array {
        global $http;
        
        $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);                                         // Obtener el almacén donde ha iniciado sesión el usuario
        $id_almacen = $almacen['id_almacen'];

        $start_date = $this->db->scape($http->request->get('start_date'));
        $end_date = $this->db->scape($http->request->get('end_date'));
        
        if($start_date == ''){
            $start_date = date('Y-m-d');
        }
        if($end_date == ''){
            $end_date = date('Y-m-d');
        }

        if($start_date != '' && $end_date != ''){
            $compras = $this->db->select('*', 'entrada', null, "fecha BETWEEN '$start_date' AND '$end_date' AND id_entrada != 1 AND id_almacen='$id_almacen'", null, "ORDER BY id_entrada DESC");
            $consignaciones = $this->db->select('*', 'consignacion', null, "tipo = 'entrada' AND fecha BETWEEN '$start_date' AND '$end_date' AND id_almacen='$id_almacen'", null, "ORDER BY id_consignacion DESC");
        }

        $data = [];
        if($compras){
            foreach ($compras as $key => $value) {
                
                $infoData = [];

                $fecha_hora = $value['fecha'].' '.$value['hora'];

                $infoData[] = $fecha_hora;

                if( ($value["id_proveedor"] === null || $value["id_proveedor"] == '') && ($value['id_accion'] == 1 || $value['id_accion'] == 2) ){
                    $tipoEntrada = 'Ajuste';
                    $genero = 'o';
                }else{
                    $tipoEntrada = 'Compra';
                    $genero = 'a';
                }
                if($tipoEntrada == 'Ajuste'){
                    $infoData[] = $tipoEntrada;
                    $infoData[] = $value["folio"];
                }elseif($tipoEntrada == 'Compra'){
                    $infoData[] = $tipoEntrada.'</span>';
                    $infoData[] = $value["folio"];
                }

                if($tipoEntrada == 'Ajuste'){
                    $infoData[] = '--';
                }elseif($tipoEntrada == 'Compra'){
                    $infoData[] = $value["factura"];
                }

                if($tipoEntrada == 'Ajuste'){
                    $subtotal = $this->db->select('SUM(cantidad*costo) AS subtotal', 'entrada_detalle', null, "id_entrada = '".$value['id_entrada']."'");
                    $infoData[] = number_format($subtotal[0]['subtotal'],2);
                }elseif($tipoEntrada == 'Compra'){
                    $subtotal = $this->db->select('SUM(cantidad*costo) AS subtotal', 'entrada_detalle', null, "id_entrada = '".$value['id_entrada']."'");
                    $infoData[] = number_format($subtotal[0]['subtotal'],2);
                }

                $time = strtotime($fecha_hora);
                $timeUnaHora = $time + 3600;
                $hoy = date('Y-m-d H:i:s');
                $f_hoy = Helper\Functions::fecha($hoy);
                $f_ven = Helper\Functions::fecha($fecha_hora);
                $f1 = substr($f_hoy,0,-17);
                $f2 = substr($f_ven,0,-17);
                if(time() < $timeUnaHora){
                    $fecha = Helper\Strings::amigable_time(strtotime($fecha_hora));
                }elseif(substr($hoy, 0, -9) == substr($fecha_hora, 0, -9)){
                    $fecha = str_replace($f2, 'Hoy ', $f_ven);
                }else{
                    $fecha = Helper\Functions::fecha($fecha_hora);
                }
                $infoData[] = $fecha;

                $infoData[] = "<div class='btn-group'>
                                        <button type='button' class='btn btn-sm btn-default mostrarCompraEntrada' folio='".$value["folio"]."' tipo='".$tipoEntrada."'><i data-toggle='tooltip' title='Ver detalles' class='fas fa-eye'></i></button>
                                        <a href='compras/nota/".$value['folio']."' target='_blank' class='btn btn-sm btn-default' key='".$value["id_entrada"]."'><i data-toggle='tooltip' title='Descargar nota' class='fas fa-file-alt'></i></a>
                                    </div>";

                $data[] = $infoData;

            }
        }

        if($consignaciones){
            foreach ($consignaciones as $key => $value) {
                $infoData = [];

                $fecha_hora = $value['fecha'].' '.$value['hora'];

                $infoData[] = $fecha_hora;

                $infoData[] = 'Entrada de consignación';

                $infoData[] = $value["folio"];

                $infoData[] = $value["referencia"];

                $subtotal = $this->db->select('SUM(cantidad*costo) AS subtotal', 'consignacion_detalle', null, "id_consignacion = '".$value['id_consignacion']."'");
                $infoData[] = number_format($subtotal[0]['subtotal'],2);

                $time = strtotime($fecha_hora);
                $timeUnaHora = $time + 3600;
                $hoy = date('Y-m-d H:i:s');
                $f_hoy = Helper\Functions::fecha($hoy);
                $f_ven = Helper\Functions::fecha($fecha_hora);
                $f1 = substr($f_hoy,0,-17);
                $f2 = substr($f_ven,0,-17);
                if(time() < $timeUnaHora){
                    $fecha = Helper\Strings::amigable_time(strtotime($fecha_hora));
                }elseif(substr($hoy, 0, -9) == substr($fecha_hora, 0, -9)){
                    $fecha = str_replace($f2, 'Hoy ', $f_ven);
                }else{
                    $fecha = Helper\Functions::fecha($fecha_hora);
                }
                $infoData[] = $fecha;

                $infoData[] = "<div class='btn-group'>
                                        <button type='button' class='btn btn-sm btn-default mostrarCompraEntrada' folio='".$value["folio"]."' tipo='Entrada de consignación'><i data-toggle='tooltip' title='Ver detalles' class='fas fa-eye'></i></button>
                                        <a href='compras/nota/".$value['folio']."' target='_blank' class='btn btn-sm btn-default' key='".$value["id_consignacion"]."'><i data-toggle='tooltip' title='Descargar nota' class='fas fa-file-alt'></i></a>
                                    </div>";

                $data[] = $infoData;
            }
        }

        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }


    public function mostrarListaCompra() {
        try {
            global $config, $http;

            $folio = $this->db->scape($http->request->get('folio'));
            $compra = $this->compraPor('folio',$folio);

            if($compra){

                $compraDetalle = $this->compraDetalle('id_entrada',$compra[0]['id_entrada']);

                $tbody = '';
                $sumaCantidad = 0;
                $sumaSubtotal = 0;
                $sumaSubtotal2 = 0;

                foreach ($compraDetalle as $key => $value) {
                    
                    $cantidad = (int) $value['cantidad'];
                    $sumaCantidad = $sumaCantidad + $cantidad;
                    $producto = (new Model\Productos)->productoNi($value['id_producto']);
                    $codigo = $producto['codigo'];
                    $costo = (real) $value['costo'];
                    $precio = (real) $value['precio'];

                    $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
                    $editorial = $editorial['editorial'];

                    $arrayAutores = explode(",", $producto["autores"]);
                    $autores = "";
                    foreach($arrayAutores as $key1 => $value2){
                        $autor = (new Model\Autores)->autor($value2);
                        $autores .= $autor['autor'].', ';
                    }
                    $autores = substr($autores, 0, -2); 

                    if($producto["detalles"] != ''){
                        $detalles = json_decode($producto["detalles"], true);
                        $esMulti = false;
                        foreach ($detalles as $k => $v) {
                            if(is_array($v)){
                                $esMulti = true;
                            }
                        }
                        $detallesTexto = "";
                        foreach ($detalles as $key2 => $value2) {
                            if(is_array($value2)){
                                $detallesTexto .= "<em>".$key2.":</em> "; 
                                foreach ($value2 as $key3 => $value3) {
                                    $detallesTexto .= $value3."-";
                                }
                                $detallesTexto = substr($detallesTexto, 0, -1);
                                $detallesTexto .= "<br>";
                            }else if($value2 != null){
                                $detallesTexto .= "<em>".$key2.":</em> ".$value2."<br>";
                            }
                        } 
                    }else{
                        $detallesTexto = 'Sin información';
                    }

                    $subtotal = $cantidad * $costo;
                    $subtotal2 = $cantidad * $precio;

                    $sumaSubtotal = $sumaSubtotal + $subtotal;
                    $sumaSubtotal2 = $sumaSubtotal2 + $subtotal2;

                    $tbody .= '
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">'.$cantidad.'</td>
                        <td style="vertical-align: middle;">
                            '.$codigo.' - <b>'.$producto['producto'].'</b> '.$producto['leyenda'].' - '.$editorial.' - '.$autores.' <i class="fas fa-info-circle infoDetalles text-aqua" data-toggle="popover" title="<b>FICHA TÉCNICA</b>" data-content="<small>'.$detallesTexto.'</small>"></i>
                        </td>
                        <td class="text-right" style="vertical-align: middle;" class="text-center">
                            '.number_format($costo,2).'
                        </td>
                        <td class="text-right" style="vertical-align: middle;" class="text-center">
                            <b>'.number_format($precio,2).'</b>
                        </td>
                        <td class="text-right" style="vertical-align: middle;">'.number_format($subtotal,2).'</td>
                        <td class="text-right" style="vertical-align: middle; font-weight:800;">'.number_format($subtotal2,2).'</td>
                    </tr>';
                }

                $tbody .= '
                <tr>
                    <th class="text-center">'.$sumaCantidad.'</th>
                    <th class="text-right" colspan="3">TOTAL:</th>
                    <th class="text-right text-red">'.number_format($sumaSubtotal,2).'</th>
                    <th class="text-right text-red">'.number_format($sumaSubtotal2,2).'</th>
                </tr>';

                return array('status' => 'success', 'tbody' => $tbody);

            }else{

                $entrada = $this->entradaPor('folio',$folio);

                if($entrada){
                    $entradaDetalle = $this->entradaDetalle('id_consignacion',$entrada[0]['id_consignacion']);

                    $tbody = '';
                    $sumaCantidad = 0;
                    $sumaSubtotal = 0;
                    $sumaSubtotal2 = 0;

                    foreach ($entradaDetalle as $key => $value) {
                        $cantidad = (int) $value['cantidad'];
                        $sumaCantidad = $sumaCantidad + $cantidad;
                        $producto = (new Model\Productos)->productoNi($value['id_producto']);
                        $codigo = $producto['codigo'];
                        $costo = (real) $value['costo'];
                        $precio = (real) $value['precio'];

                        $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
                        $editorial = $editorial['editorial'];

                        $arrayAutores = explode(",", $producto["autores"]);
                        $autores = "";
                        foreach($arrayAutores as $key1 => $value2){
                            $autor = (new Model\Autores)->autor($value2);
                            $autores .= $autor['autor'].', ';
                        }
                        $autores = substr($autores, 0, -2); 

                        if($producto["detalles"] != ''){
                            $detalles = json_decode($producto["detalles"], true);
                            $esMulti = false;
                            foreach ($detalles as $k => $v) {
                                if(is_array($v)){
                                    $esMulti = true;
                                }
                            }
                            $detallesTexto = "";
                            foreach ($detalles as $key2 => $value2) {
                                if(is_array($value2)){
                                    $detallesTexto .= "<em>".$key2.":</em> "; 
                                    foreach ($value2 as $key3 => $value3) {
                                        $detallesTexto .= $value3."-";
                                    }
                                    $detallesTexto = substr($detallesTexto, 0, -1);
                                    $detallesTexto .= "<br>";
                                }else if($value2 != null){
                                    $detallesTexto .= "<em>".$key2.":</em> ".$value2."<br>";
                                }
                            } 
                        }else{
                            $detallesTexto = 'Sin información';
                        }

                        $subtotal = $cantidad * $costo;
                        $subtotal2 = $cantidad * $precio;

                        $sumaSubtotal = $sumaSubtotal + $subtotal;
                        $sumaSubtotal2 = $sumaSubtotal2 + $subtotal2;

                        $tbody .= '
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">'.$cantidad.'</td>
                            <td style="vertical-align: middle;">
                                '.$codigo.' - <b>'.$producto['producto'].'</b> '.$producto['leyenda'].' - '.$editorial.' - '.$autores.' <i class="fas fa-info-circle infoDetalles text-aqua" data-toggle="popover" title="<b>FICHA TÉCNICA</b>" data-content="<small>'.$detallesTexto.'</small>"></i>
                            </td>
                            <td class="text-right" style="vertical-align: middle;" class="text-center">
                                '.number_format($costo,2).'
                            </td>
                            <td class="text-right" style="vertical-align: middle;" class="text-center">
                                <b>'.number_format($precio,2).'</b>
                            </td>
                            <td class="text-right" style="vertical-align: middle;">'.number_format($subtotal,2).'</td>
                            <td class="text-right" style="vertical-align: middle; font-weight:800;">'.number_format($subtotal2,2).'</td>
                        </tr>';
                    }

                    $tbody .= '
                    <tr>
                        <th class="text-center">'.$sumaCantidad.'</th>
                        <th class="text-right" colspan="3">TOTAL:</th>
                        <th class="text-right text-red">'.number_format($sumaSubtotal,2).'</th>
                        <th class="text-right text-red">'.number_format($sumaSubtotal2,2).'</th>
                    </tr>';

                    return array('status' => 'success', 'tbody' => $tbody);

                }else{
                    throw new ModelsException('La compra no existe.');
                }

            }

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    public function imprimirNota($folio,$tipo) {
        global $config;

        $datosPlantilla = (new Model\Comercio)->datosPlantilla();

        if($tipo == 'compra'){

            $compra = $this->compraPor('folio',$folio);
            $almacen = (new Model\Almacenes)->almacen($compra[0]['id_almacen']);
            $registro = (new Model\Administradores)->administrador($compra[0]['usuario_entrada']);
            
            if( ($compra[0]['id_proveedor'] == null || $compra[0]['id_proveedor'] == '') && ($compra[0]['id_accion'] == 1 || $compra[0]['id_accion'] == 2) ){
                $tipoEntrada = 'AJUSTE DE ENTRADA';
                $referencia = '--';
                $proveedor = (new Model\Proveedores)->proveedor($compra[0]['id_accion']);
            }else{
                $tipoEntrada = 'COMPRA';
                $referencia = $compra[0]['factura'];
                $proveedor = (new Model\Proveedores)->proveedor($compra[0]['id_proveedor']);
            }

            $compraDetalle = $this->compraDetalle('id_entrada',$compra[0]['id_entrada']);

            $fecha_hora = $compra[0]['fecha'].' '.$compra[0]['hora'];
            
            $html = '
            <page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif;">
                <table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; padding-bottom:20px; color:#000; font-size:10px;">

                            <b>Almacén:</b> '.$almacen['almacen'].'<br><br>

                            <b>Fecha:</b> '.Helper\Functions::fecha($fecha_hora).'<br>
                            <b>Registró:</b> '.$registro['name'].'<br><br>

                            <b>Referencia:</b> '.$referencia.'<br>
                            <b>Proveedor:</b> <span style="color: '.$datosPlantilla['colorPlantilla'].'">'.$proveedor['proveedor'].'</span>

                        </td>
                        <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; text-align:right; color:'.$datosPlantilla['colorPlantilla'].'; vertical-align:middle;">
                            FOLIO DE '.$tipoEntrada.'<br><br>
                            <barcode dimension="1D" type="C128" value="'.$compra[0]['folio'].'" label="label" style="width:80%; height:6mm; font-size: 12px;"></barcode>
                        </td>
                    </tr>
                </table><br>';

                $html .= '
                <table style="width:100%; border:none; font-size:10px;" border="0" cellspacing="0" cellpadding="0">
                    <tr style="background:#CCC; text-transform:uppercase; font-size:8px; color:#000; text-align:center;">
                        <th colspan="6" style="padding:7px 0;">DETALLE</th>
                    </tr>
                    <tr style="background:#eee; text-transform:uppercase; font-size:8px;">
                        <th style="width:5%; padding:7px 0; text-align:center; padding-right:5px;">
                            Cant.
                        </th>
                        <th style="width:59%;">
                            Descripción
                        </th>
                        <th style="width:9%; text-align:right; padding-right:5px;">
                            P. compra
                        </th>
                        <th style="width:9%; text-align:right; padding-right:5px;">
                            P. venta
                        </th>
                        <th style="width:9%; text-align:right; padding-right:5px;">
                            Importe C
                        </th>
                        <th style="width:9%; text-align:right; padding-right:5px;">
                            Importe V
                        </th>
                    </tr>';

                $sumaCantidad = 0; 
                $total = 0;
                $total2 = 0;

                foreach ($compraDetalle as $key => $value) {
                    $id_producto = $value['id_producto'];
                    $producto = (new Model\Productos)->productoNi($id_producto);
                    $nombreP = $producto['producto'].' '.$producto['leyenda'];
                    if(strlen($nombreP) > 50){
                        $nombreP = trim(substr($nombreP, 0, 47));
                        $nombreP .= '...';
                    }
                    $codigo = $producto['codigo']; 
                    $cantidad = (int) $value['cantidad'] ;
                    $costo = (real) $value['costo'];
                    $precio = (real) $value['precio'];

                    $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
                    $editorial = $editorial['editorial'];

                    $arrayAutores = explode(",", $producto["autores"]);
                    $autores = "";
                    foreach($arrayAutores as $key1 => $value2){
                        $autor = (new Model\Autores)->autor($value2);
                        $autores .= $autor['autor'].', ';
                    }
                    $autores = substr($autores, 0, -2); 

                    if(strlen($autores) > 40){
                        $autores = trim(substr($autores, 0, 37));
                        $autores .= '...';
                    }

                    $subtotal = $cantidad * $costo;
                    $subtotal2 = $cantidad * $costo;

                    $sumaCantidad = $sumaCantidad + $cantidad;
                    $total = $total + $subtotal;
                    $total2 = $total2 + $subtotal2;

                    $html .= '
                    <tr>
                        <td style="border-bottom:1px solid #eee;text-align:center; padding:15px 0; padding-right:5px;">
                            '.$cantidad.'
                        </td>
                        <td style="border-bottom:1px solid #eee;">
                            <b>'.$codigo.'</b> 
                            <br>(ID: '.$value['id_producto'].') <b>'.$nombreP.'</b>
                            <br>'.$editorial.' - '.$autores.'
                            
                        </td>
                        <td style="border-bottom:1px solid #eee; text-align:right; padding-right:5px; color:red; font-weight:bold;">
                            $ '.number_format($costo,2).'
                        </td>
                        <td style="border-bottom:1px solid #eee; text-align:right; padding-right:5px;">
                            $ '.number_format($precio,2).'
                        </td>
                        <td style="border-bottom:1px solid #eee;text-align:right; padding-right:5px; color:red; font-weight:bold;">
                            $ '.number_format($subtotal,2).'
                        </td>
                        <td style="border-bottom:1px solid #eee;text-align:right; padding-right:5px;">
                            $ '.number_format($subtotal2,2).'
                        </td>
                    </tr>';
                }

                $plural = ($sumaCantidad == 1) ? '' : 's';

                $html .= '
                <tr style="text-transform:uppercase;">
                    <th style="text-align:center; padding:10px 0; padding-right:5px;">
                        '.$sumaCantidad.'
                    </th>
                    <td>
                        ARTÍCULO'.$plural.'
                    </td>
                    <td>
                    </td>
                    <th style="text-align:right; padding:2px 0;">
                        TOTAL:
                    </th>
                    <th style="text-align:right; padding:2px 0; padding-right:5px; color:red;">
                        $ '.number_format($total,2).'
                    </th>
                    <th style="text-align:right; padding:2px 0; padding-right:5px;">
                        $ '.number_format($total2,2).'
                    </th>
                </tr>';

                $html .= '</table>';

            $html .= '</page>';
        
        }else{

            $entrada = $this->entradaPor('folio',$folio);

            $almacen = (new Model\Almacenes)->almacen($entrada[0]['id_almacen']);

            $registro = (new Model\Administradores)->administrador($entrada[0]['usuario_consignacion']);
            $proveedor = (new Model\Proveedores)->proveedor($entrada[0]['id_proveedor']);

            $entradaDetalle = $this->entradaDetalle('id_consignacion',$entrada[0]['id_consignacion']);

            $fecha_hora = $compra[0]['fecha'].' '.$compra[0]['hora'];

            $html = '
            <page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif;">
                <table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; padding-bottom:20px; color:#000; font-size:10px;">

                            <b>Almacén:</b> '.$almacen['almacen'].'<br><br>

                            <b>Fecha:</b> '.Helper\Functions::fecha($fecha_hora).'<br>
                            <b>Registró:</b> '.$registro['name'].'<br><br>

                            <b>Referencia:</b> '.$entrada[0]['referencia'].'<br>
                            <b>Proveedor:</b> <span style="color: '.$datosPlantilla['colorPlantilla'].'">'.$proveedor['proveedor'].'</span>

                        </td>
                        <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; text-align:right; color:'.$datosPlantilla['colorPlantilla'].'; vertical-align:middle;">
                            FOLIO DE CONSIGNACIÓN DE ENTRADA<br><br>
                            <barcode dimension="1D" type="C128" value="'.$entrada[0]['folio'].'" label="label" style="width:80%; height:6mm; font-size: 12px;"></barcode>
                        </td>
                    </tr>
                </table><br>';

                $html .= '
                <table style="width:100%; border:none; font-size:10px;" border="0" cellspacing="0" cellpadding="0">
                    <tr style="background:#CCC; text-transform:uppercase; font-size:8px; color:#000; text-align:center;">
                        <th colspan="6" style="padding:7px 0;">DETALLE</th>
                    </tr>
                    <tr style="background:#eee; text-transform:uppercase; font-size:8px;">
                        <th style="width:5%; padding:7px 0; text-align:center; padding-right:5px;">
                            Cant.
                        </th>
                        <th style="width:59%;">
                            Descripción
                        </th>
                        <th style="width:9%; text-align:right; padding-right:5px;">
                            P. compra
                        </th>
                        <th style="width:9%; text-align:right; padding-right:5px;">
                            P. venta
                        </th>
                        <th style="width:9%; text-align:right; padding-right:5px;">
                            Importe c
                        </th>
                        <th style="width:9%; text-align:right; padding-right:5px;">
                            Importe v
                        </th>
                    </tr>';

                $sumaCantidad = 0; 
                $total = 0;
                $total2 = 0;

                foreach ($entradaDetalle as $key => $value) {
                    $id_producto = $value['id_producto'];
                    $producto = (new Model\Productos)->productoNi($id_producto);
                    $nombreP = $producto['producto'].' '.$producto['leyenda'];
                    if(strlen($nombreP) > 50){
                        $nombreP = trim(substr($nombreP, 0, 47));
                        $nombreP .= '...';
                    }

                    $codigo = $producto['codigo']; 
                    $cantidad = (int) $value['cantidad'] ;
                    $costo = (real) $value['costo'];
                    $precio = (real) $value['precio'];

                    $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
                    $editorial = $editorial['editorial'];

                    $arrayAutores = explode(",", $producto["autores"]);
                    $autores = "";
                    foreach($arrayAutores as $key1 => $value2){
                        $autor = (new Model\Autores)->autor($value2);
                        $autores .= $autor['autor'].', ';
                    }
                    $autores = substr($autores, 0, -2); 

                    if(strlen($autores) > 40){
                        $autores = trim(substr($autores, 0, 37));
                        $autores .= '...';
                    }

                    $subtotal = $cantidad * $costo;
                    $subtotal2 = $cantidad * $precio;

                    $sumaCantidad = $sumaCantidad + $cantidad;
                    $total = $total + $subtotal;
                    $total2 = $total2 + $subtotal2;

                    $html .= '
                    <tr>
                        <td style="border-bottom:1px solid #eee;text-align:center; padding:15px 0; padding-right:5px;">
                            '.$cantidad.'
                        </td>
                        <td style="border-bottom:1px solid #eee;">
                            <b>'.$codigo.'</b> 
                            <br>(ID: '.$value['id_producto'].') <b>'.$nombreP.'</b>
                            <br>'.$editorial.' - '.$autores.'
                        </td>
                        <td style="border-bottom:1px solid #eee; text-align:right; padding-right:5px; color:red; font-weight:bold;">
                            $ '.number_format($costo,2).'
                        </td>
                        <td style="border-bottom:1px solid #eee; text-align:right; padding-right:5px;">
                            $ '.number_format($precio,2).'
                        </td>
                        <td style="border-bottom:1px solid #eee;text-align:right; padding-right:5px; color:red; font-weight:bold;">
                            $ '.number_format($subtotal,2).'
                        </td>
                        <td style="border-bottom:1px solid #eee;text-align:right; padding-right:5px;">
                            $ '.number_format($subtotal2,2).'
                        </td>
                    </tr>';
                }

                $plural = ($sumaCantidad == 1) ? '' : 's';

                $html .= '
                <tr style="text-transform:uppercase;">
                    <th style="text-align:center; padding:10px 0; padding-right:5px;">
                        '.$sumaCantidad.'
                    </th>
                    <td>
                        ARTÍCULO'.$plural.'
                    </td>
                    <td>
                    </td>
                    <th style="text-align:right; padding:2px 0;">
                        TOTAL:
                    </th>
                    <th style="text-align:right; padding:2px 0; padding-right:5px; color:red;">
                        $ '.number_format($total,2).'
                    </th>
                    <th style="text-align:right; padding:2px 0; padding-right:5px;">
                        $ '.number_format($total2,2).'
                    </th>
                </tr>';

                $html .= '</table>';

            $html .= '</page>';

        }

        echo $html;

    }

    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}