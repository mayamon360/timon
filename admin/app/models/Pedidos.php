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
 * Modelo Pedidos
 */
class Pedidos extends Models implements IModels {
    use DBModel;

    public function pedidos(){
        $pedidos = $this->db->select('*', 'encargo', null, null, null, "ORDER BY id_e DESC");
        return $pedidos;
    }

    public function pedido($id_e){
        $pedido = $this->db->select('*','encargo',null,"id_e = '$id_e'");
        if($pedido){
            return $pedido[0];
        }
        return false;
    }

    public function folioPedidoDetalle($id_ed){
        $pedido_detalle = $this->db->select('*','encargo_detalle',null,"id_ed = '$id_ed'");
        if($pedido_detalle){
            return $pedido_detalle;
        }
        return false;
    }

    /**
     * Respuesta generada por defecto para el endpoint
     * 
     * @return array
    */ 
    public function mostrarPedidos() : array {
        global $http;
        
        
        $start_date = $this->db->scape($http->request->get('start_date'));
        $end_date = $this->db->scape($http->request->get('end_date'));
        
        if($start_date == ''){
            $start_date = '2020-06-08';
        }
        if($end_date == ''){
            $end_date = date('Y-m-d');
        }

        if($start_date != '' && $end_date != ''){
            $pedidos = $this->db->select(
                'e.id_e, e.folio, e.estado, MIN(DATE_FORMAT(ed.fecha_encargo, "%d/%m/%Y")) as fecha_inicio, MAX(DATE_FORMAT(ed.fecha_encargo, "%d/%m/%Y")) as fecha_fin, e.estado', 
                'encargo AS e', 
                "INNER JOIN encargo_detalle AS ed ON e.id_e=ed.id_e", 
                "ed.fecha_encargo BETWEEN '$start_date' AND '$end_date'", 
                null, 
                "GROUP BY e.id_e ORDER BY e.id_e DESC");
        }

        $data = [];
        if($pedidos){
            foreach ($pedidos as $key => $value) {
                
                $infoData = [];
                
                $id_e = $value["id_e"];
                $infoData[] = '<span class="badge bg-black" style="font-weight:800;">'.$id_e.'</span>';
                $infoData[] = '<span class="badge bg-purple" style="font-weight:800;">'.$value["folio"].'<span>';
                $infoData[] = '<span class="badge bg-gray" style="font-weight:800;">'.$value["fecha_inicio"].'<span>';
                $infoData[] = '<span class="badge bg-gray" style="font-weight:800;">'.$value["fecha_fin"].'<span>';
                
                $solicitados = $this->db->select("SUM(cantidad) as cantidad", "encargo_detalle", null, "id_e = '$id_e' AND estado_anticipo != 2");
                $solicitados = (int) $solicitados[0]['cantidad'];
                
                $entregados = $this->db->select("SUM(cantidad) as cantidad", "encargo_detalle", null, "id_e = '$id_e' AND estado_anticipo = 1");
                $entregados = (int) $entregados[0]['cantidad'];
                
                $btn_nota = "<a href='pedidos/nota/".$id_e."' target='_blank' class='btn btn-sm btn-default' key='".$id_e."'><i data-toggle='tooltip' title='Descargar nota' class='fas fa-file-alt'></i></a>";
                $btn_ver = "<a href='pedidos/ver/".$id_e."' class='btn btn-sm btn-default' key='".$id_e."'><i data-toggle='tooltip' title='Ver detalles' class='fa fa-fw fa-list-ul'></i></a>";
                
                if($solicitados == $entregados){
                    $class_ = 'bg-green';
                }else{
                    $class_ = 'bg-red';
                }
                
                $infoData[] = '<span class="badge bg-green" style="font-weight:800;">' . $solicitados . '<span>';
                $infoData[] = '<span class="badge '.$class_.'" style="font-weight:800;">' . $entregados . '<span>';
                
                $infoData[] = "
                <div class='btn-group'>
                    ".$btn_ver."
                    ".$btn_nota."
                </div>";


                $data[] = $infoData;

            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    public function mostrarListaPedido() {
        global $http;
        
        $id_pedido = (int) $http->request->get('id_pedido');
        
        $pedido_general = $this->pedido($id_pedido);
        
        $pedido_detalle = $this->db->select(
            "ed.id_ed, ed.folio_pedido, DATE_FORMAT(ed.fecha_encargo, '%d/%m/%Y') as fecha, ed.a_nombre, ed.cantidad, p.id, p.codigo, p.producto, p.leyenda, p.precio, p.stock, ed.anticipo, ed.folio_venta, ed.metodo_pago, ed.estado_anticipo", 
            "encargo_detalle AS ed", 
            "INNER JOIN productos AS p ON ed.id_producto=p.id", 
            "ed.id_e = '$id_pedido'",
            null,
            "ORDER BY ed.id_ed DESC"
        );

        $data = [];
        if($pedido_detalle){
            foreach ($pedido_detalle as $key => $value) {
                
                $infoData = [];
                $infoData[] = '<span class="badge bg-purple" style="font-weight:800;">'.$value["folio_pedido"].'<span>';
                $infoData[] = $value["fecha"];
                $infoData[] = $value["a_nombre"];
                
                $cantidad = (int) $value["cantidad"];
                $infoData[] = $cantidad;
                
                $infoData[] = '<sup><small class="text-muted">'.$value['id_ed'].'</small></sup> '.$value["id"].' | '.$value["codigo"].' <br><strong>'.$value["producto"].'</strong> '.$value["leyenda"];
                
                $precio = (real) $value["precio"];
                $infoData[] = '<span class="font-weight-bold">'.number_format($precio,2).'</span>';
                
                $total = $cantidad * $precio;
                $infoData[] = '<span class="font-weight-bold text-blue">'.number_format($total,2).'</span>';
                
                if($value['metodo_pago'] == 'efectivo'){
                    $metodo = 'Efectivo';
                }else{
                    $metodo = 'Tarjeta';
                }
                $anticipo = (real) $value["anticipo"];
                $infoData[] = '<span class="font-weight-bold text-maroon" data-toggle="tooltip" title="'.$metodo.'">'.number_format($anticipo,2).'</span>';
                
                $resta = $total - $anticipo;
                $infoData[] = '<span class="badge font-weight-bold bg-red">'.number_format($resta,2).'</span>';
                
                if($value["estado_anticipo"] == 0){
                    $infoData[] = '<span class="animated infinite flash font-weight-bold">Pendiente</span>';
                }elseif($value["estado_anticipo"] == 1){
                    $infoData[] = '<span class="font-weight-bold text-green">Entregado</span>';
                }elseif($value["estado_anticipo"] == 2){
                    $infoData[] = '<span class="font-weight-bold text-red">Cancelado</span>';
                }
                
                if($value["folio_venta"] != ''){
                    $infoData[] = $value["folio_venta"];
                }else{
                    $infoData[] = '---';
                }
                
                $btn_cancelar = "<button type='button' class='btn btn-sm btn-default' key='".$value["id_ed"]."' folio='".$value["folio_pedido"]."' anticipo='".number_format($anticipo,2)."' id='cancelar_pedido'>
                                <i data-toggle='tooltip' title='Cancelar' class='fa fa-fw fa-times text-red'></i>
                            </button>";
                            
                $btn_ticket_pedido = "<a href='pedidos/ticket/".$value["id_ed"]."' target='_blank' class='btn btn-sm btn-flat btn-default'>
                                <i data-toggle='tooltip' title='Ticket pedido' class='fas fa-receipt text-purple'></i>
                            </a>";
                            
                $btn_entregar = "<button type='button' class='btn btn-sm btn-default' key='".$value["id_ed"]."' id='entregar_pedido' data-toggle='modal' data-target='#modalConfirmarEntrega'>
                                <i data-toggle='tooltip' title='Entregar' class='fas fa-share text-aqua'></i>
                            </button>";
                            
                            
                $btn_group = "<div class='btn-group'>";
                
                    if($value["estado_anticipo"] == 0){                             // Si el pedido esta como Pendiente
                        
                        $btn_group .= $btn_cancelar;
                        $btn_group .= $btn_ticket_pedido;
                        
                        if($pedido_general['estado'] == 2 && $value["stock"] > 0){  // Si el pedido general ya esta cerrado y el producto tiene stock
                            $btn_group .= $btn_entregar;
                        }
                        
                    }elseif($value["estado_anticipo"] == 1){                        // Si el pedido ya ha sido entregado
                        
                        $venta = $this->db->select(
                            "id_salida",
                            "salida",
                            null,
                            "folio = '".$value["folio_venta"]."'"
                        );
                        $id_salida = $venta[0]['id_salida'];
                        $btn_group .= $btn_ticket_venta = "<a href='ventasDeMostrador/ticket/".$id_salida."' target='_blank' class='btn btn-sm btn-flat btn-default'>
                            <i data-toggle='tooltip' title='Ticket venta' class='fas fa-receipt text-aqua'></i>
                        </a>"; 
                        $btn_group .= $btn_ticket_pedido;
                                
                    }elseif($value["estado_anticipo"] == 2){                        // Si el pedido ya se cancelo
                        $btn_group .= $btn_ticket_pedido;   
                    }
                
                $btn_group .= "</div>";
                
                $infoData[] = $btn_group;
                
                $data[] = $infoData;

            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    public function cancelarPedido(){
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);                                             # - Realizar consulta para obtener el almacen del usuario activo -
            $caja = (new Model\Caja)->cargarCaja('id_almacen', $almacen['id_almacen']);                                     # - Realizar consulta para obtener datos de la caja del día -
            if(!$caja) {                                                                                                    # - Si la caja del día no esta aperturada -
                throw new ModelsException('Es necesario registrar monto incial en caja para poder continuar.');
            } else {
                if($caja && $caja['estado'] == 1) {                                                                         # - Si la caja de día ya ha sido cerrada -
                    throw new ModelsException('No es posible realizar más movientos, la caja ha sido cerrada.');
                }
            }
            $idCaja = $caja['id_caja'];
            
            global $http;

            $id_ed = intval($http->request->get('key'));
            $codigo = $this->db->scape($http->request->get('codigo'));
            if($codigo == ''){
                throw new ModelsException('El código de anticipo es necesario para poder cancelar el pedido.');
            }
            
            $pedido = $this->db->select('*','encargo_detalle',null,"id_ed = '$id_ed' AND codigo_anticipo = '$codigo'");
            $folio_pedido = $pedido[0]['folio_pedido'];
            $estado = $pedido[0]['estado_anticipo'];
            $anticipo = (real) $pedido[0]['anticipo']; 
            
            if(!$pedido){
                throw new ModelsException('El pedido no existe o el código de anticipo es incorrecto.');
            }elseif($estado == 1){
                throw new ModelsException('El pedido ya ha sido entregado.');
            }elseif($estado == 2){
                throw new ModelsException('El pedido ya ha sido cancelado.');
            }
            
            $saldo = (new Model\Caja)->obtenerSaldo($idCaja);
            if($saldo < $anticipo){
                throw new ModelsException('El anticipo a devolver supera el saldo en caja.');
            }

            $this->db->update('encargo_detalle',array(
                'estado_anticipo' => 2,
                'fecha_cancelado' => date('Y-m-d'),
                'hora_cancelado' => date('H:i:s')
            ),"id_ed='$id_ed'",1);
            
            $tipo = 'Egreso';
            $descripcion = "Egreso de anticipo";    
            
            $this->db->insert('caja_movimientos', array(
                'id_caja' => $idCaja,
                'tipo' => $tipo,
                'concepto' => 'Anticipo',
                'referencia' => $folio_pedido,
                'descripcion' => $descripcion,
                'monto' => $anticipo,
                'usuario' => $this->id_user,
                'hora' => date('H:i:s')
            ));

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El pedido ha sido cancelado.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    // imprimir nota general
    public function imprimirNota($id_pedido,$folio) {
        
        global $config;

        $datosPlantilla = (new Model\Comercio)->datosPlantilla();
        $color_plantilla = $datosPlantilla['colorPlantilla'];
        
        $pedido_detalle = $this->db->select(
            "ed.folio_pedido, ed.cantidad, p.id AS id_producto, p.codigo, p.producto, p.leyenda, p.id_editorial, p.autores, p.precio_compra, p.precio, ed.anticipo, ed.estado_anticipo", 
            "encargo_detalle AS ed", 
            "INNER JOIN productos AS p ON ed.id_producto=p.id", 
            "ed.id_e = '$id_pedido'",
            null,
            "ORDER BY ed.id_ed DESC, p.id ASC"
        );

        $html = '
        <page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif;">
            <table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:50%; border-bottom:2px solid #000; color:#000; color:#000; vertical-align:middle;">
                    FOLIO GENERAL
                    </td>
                    <td style="width:50%; border-bottom:2px solid #000; text-align:right; color:#000; vertical-align:middle; padding:10px 0px;">
                        
                        <barcode dimension="1D" type="C128" value="'.$folio.'" label="label" style="width:80%; height:6mm; font-size: 12px;"></barcode>
                    </td>
                </tr>
            </table><br>';
        
        if($pedido_detalle){
            $html .= '
            <table style="width:100%; border:none; font-size:9px;" border="0" cellspacing="0" cellpadding="0">
                <tr style="background:#eee; text-transform:uppercase; font-size:8px;">
                    
                    <th style="width:12%; padding:7px 0; text-align:center; padding-right:5px;">
                        Pedido
                    </th>
                    
                    <th style="width:5%; padding:7px 0; text-align:center; padding-right:5px;">
                        Cant.
                    </th>
                    
                    <th style="width:38%;">
                        Descripción
                    </th>
                    
                    <th style="width:9%; text-align:right; padding-right:5px;">
                        P.C.
                    </th>
                    
                    <th style="width:9%; text-align:right; padding-right:5px;">
                        P.V.
                    </th>
                    
                    <th style="width:9%; text-align:right; padding-right:5px;">
                        S.P.C.
                    </th>
                    
                    <th style="width:9%; text-align:right; padding-right:5px;">
                        S.P.V.
                    </th>
                    
                    <th style="width:9%; text-align:right; padding-right:5px;">
                        Anticipo
                    </th>

                </tr>';

            $sumaCantidad = 0; 
            $totalCosto = 0;
            $totalVenta = 0;
            $totalAnticipo = 0;

            foreach ($pedido_detalle as $key => $value) {
                
                $folio_pedido = $value['folio_pedido'];
                $cantidad = (int) $value["cantidad"];
                $id_producto = $value["id_producto"];
                $codigo = $value["codigo"];
                $producto = '<strong>'.$value["producto"].'</strong>';
                $leyenda = $value["leyenda"];
                if($leyenda != ''){
                    $producto = $producto.'<br>'.$leyenda;
                }
                $editorial = (new Model\Editoriales)->editorial($value["id_editorial"]);
                if(!$editorial){
                    $editorial = 'Sin editorial';
                }else{
                    $editorial = $editorial['editorial'];
                }
                if($value["autores"] != ''){
                    $arrayAutores = explode(",", $value["autores"]);
                    $autores = "";
                    foreach($arrayAutores as $key1 => $value2){
                        $autor = (new Model\Autores)->autor($value2);
                        $autores .= $autor['autor'].', ';
                    }
                    $autores = substr($autores, 0, -2); 
                }else{
                    $autores = "Sin autor";
                }
                
                $costo = (real) $value["precio_compra"];
                $precio = (real) $value["precio"];
                $anticipo = (real) $value["anticipo"];
                
                if($value['estado_anticipo'] == 0){
                    $txt = 'PENDIENTE<br><br>';
                }elseif($value['estado_anticipo'] == 1){    
                    $txt = 'ENTREGADO<br><br>';
                }elseif($value['estado_anticipo'] == 2){
                    $txt = 'CANCELADO<br><br>';
                    $cantidad = 0;
                    $anticipo = 0;
                }
                
                $sumaCantidad += $cantidad;

                $subtotalCosto = $cantidad * $costo;
                $subtotalVenta = $cantidad * $precio;
                
                $totalCosto += $subtotalCosto;
                $totalVenta += $subtotalVenta;
                $totalAnticipo += $anticipo;
                
                $html .= '
                <tr>
                    <td style="border-bottom:1px solid #eee; text-align:center;">
                        '.$folio_pedido.'
                    </td>
                    <td style="border-bottom:1px solid #eee; text-align:center;">
                        '.$cantidad.'
                    </td>
                    <td style="border-bottom:1px solid #eee; padding:5px 0;">
                        '.$txt.'
                        '.$id_producto.' | '.$codigo.' 
                        <br>'.$producto.'
                        <br>'.$editorial.'
                        <br>'.$autores.'
                    </td>
                    <td style="border-bottom:1px solid #eee; text-align:right; font-weight:bold;">
                        '.number_format($costo,2).'
                    </td>
                    <td style="border-bottom:1px solid #eee; text-align:right">
                        '.number_format($precio,2).'
                    </td>
                    <td style="border-bottom:1px solid #eee; text-align:right; font-weight:bold;">
                        '.number_format($subtotalCosto,2).'
                    </td>
                    <td style="border-bottom:1px solid #eee; text-align:right">
                        '.number_format($subtotalVenta,2).'
                    </td>
                    <td style="border-bottom:1px solid #eee; text-align:right; font-weight:bold;">
                        '.number_format($anticipo,2).'
                    </td>
                </tr>
                ';

            }

            $plural = ($sumaCantidad == 1) ? '' : 'S';
            
            $html .= '
            <tr>
                <th></th>
                <th style="text-align:center; padding:10px 0;">
                    '.$sumaCantidad.'
                </th>
                <td>
                    ARTÍCULO'.$plural.'
                </td>
                <th></th>
                <th style="text-align:right; padding:2px 0;">
                    TOTAL:
                </th>
                <th style="text-align:right; padding:2px 0; font-weight:bold;">
                    '.number_format($totalCosto,2).'
                </th>
                <th style="text-align:right; padding:2px 0; font-weight:bold;">
                    '.number_format($totalVenta,2).'
                </th>
                <th style="text-align:right; padding:2px 0; font-weight:bold;">
                    '.number_format($totalAnticipo,2).'
                </th>
            </tr>';

            $html .= '</table>';
        }

        $html .= '</page>';

        echo $html;
    }
    
    public function imprimirTicket($id_ed) {
        
        $datosPlantilla = (new Model\Comercio)->datosPlantilla();
        
        $pedido_detalle = $this->db->select(
            "id_e, folio_pedido, DATE_FORMAT(fecha_encargo, '%d/%m/%Y') AS fecha, DATE_FORMAT(hora_encargo, '%h:%i %p') AS hora, codigo_anticipo, anticipo, metodo_pago, a_nombre, estado_anticipo, 
            DATE_FORMAT(fecha_cancelado, '%d/%m/%Y') AS fecha_cancelado, 
            DATE_FORMAT(hora_cancelado, '%h:%i %p') AS hora_cancelado,
            DATE_FORMAT(fecha_aplicado, '%d/%m/%Y') AS fecha_aplicado, 
            DATE_FORMAT(hora_aplicado, '%h:%i %p') AS hora_aplicado", 
            "encargo_detalle", 
            null, 
            "id_ed = '$id_ed'");
        
        $pedido = $this->pedido($pedido_detalle[0]['id_e']);
        
        $anticipo = number_format($pedido_detalle[0]['anticipo'],2);
        
        if($pedido_detalle[0]['estado_anticipo'] == 0){
            
            $estado = 'Pendiente';
            $fecha = $pedido_detalle[0]['fecha'].' | '.$pedido_detalle[0]['hora'];
            
        }elseif($pedido_detalle[0]['estado_anticipo'] == 1){
            
            $estado = 'Aplicado';
            $fecha = $pedido_detalle[0]['fecha_aplicado'].' | '.$pedido_detalle[0]['hora_aplicado'];
            
        }elseif($pedido_detalle[0]['estado_anticipo'] == 2){
            
            $estado = 'Cancelado';
            $fecha = $pedido_detalle[0]['fecha_cancelado'].' | '.$pedido_detalle[0]['hora_cancelado'];
            $anticipo = '<del>'.number_format($pedido_detalle[0]['anticipo'],2).'</del>';
            
        }

        $html = '
        <page format="76x100" backtop="5mm" backbottom="5mm" backleft="8mm" backright="6mm" style="font-family: Arial, Helvetica, sans-serif;">
            
            <div style="padding:0;">

                <h4 style="text-align:center; font-weight:bold; margin-top:0;">
                    '.$estado.'
                </h4>

                <p style="text-align:center; font-size:12px; margin-top:-5px;">
                    '.$fecha.'
                </p>
                
                <h4 style="text-align:center; margin:5px 0px;">
                    '.$anticipo.'
                    <br><small>('.$pedido_detalle[0]['metodo_pago'].')</small>
                </h4>
                
                <p style="text-align:center;">
                    '.$pedido_detalle[0]['a_nombre'].'
                </p>
                
                <div style="text-align:center">
                        <barcode dimension="1D" type="C128" value="'.$pedido['folio'].'" label="label" style="width:95%; height:8mm; font-size: 12px; margin:auto;"></barcode>
                </div>
                <br>
                <div style="text-align:center">
                        <barcode dimension="1D" type="C128" value="'.$pedido_detalle[0]['folio_pedido'].'" label="label" style="width:95%; height:8mm; font-size: 12px; margin:auto;"></barcode>
                </div>
                <br>
                <div style="text-align:center">
                        <barcode dimension="1D" type="C128" value="'.$pedido_detalle[0]['codigo_anticipo'].'" label="label" style="width:95%; height:8mm; font-size: 12px; margin:auto;"></barcode>
                </div>
                
            </div>
            
        </page>';

        echo $html;
        
    }
    
    public function cargarLibroPedido() {
        try{
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            $id_ed = (int) $http->request->get('key');
            
            $pedido_detalle = $this->db->select(
                "ed.cantidad, p.id, p.codigo, p.producto, p.leyenda, p.precio, p.stock, ed.anticipo, ed.estado_anticipo, ed.folio_pedido", 
                "encargo_detalle AS ed", 
                "INNER JOIN productos AS p ON ed.id_producto=p.id", 
                "ed.id_ed = '$id_ed'",
                null,
                "ORDER BY ed.id_ed DESC"
            );
            
            if(!$pedido_detalle){
                throw new ModelsException('El pedido no existe.');
            }
            
            $pedido_detalle = $pedido_detalle[0];
            if($pedido_detalle['estado_anticipo'] != 0){
                throw new ModelsException('No es posible cargar el producto.');
            }
            $stock = (int) $pedido_detalle['stock'];
            if($stock < 1){
                throw new ModelsException('El stock del producto se encuentra en 0.');
            }
            
            $cantidad = (int) $pedido_detalle['cantidad'];
            $precio = (real) $pedido_detalle['precio'];
            $total = $cantidad * $precio;
            $anticipo = (real) $pedido_detalle['anticipo'];
            $resta = $total - $anticipo;
            
            return array(
                'status' => 'success',
                "folio_pedido" => $pedido_detalle['folio_pedido'],
                "cantidad" => $cantidad,
                "id_producto" => $pedido_detalle['id'],
                "codigo" => $pedido_detalle['codigo'],
                "producto" => $pedido_detalle['producto'],
                "leyenda" => $pedido_detalle['leyenda'],
                "precio" => number_format($precio,2),
                "total" => number_format($total,2),
                "anticipo" => number_format($anticipo,2),
                "resta" => number_format($resta,2)
            );
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    public function confirmarEntrega() {
        try{
            if($this->id_user === NULL) {
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            $cliente = 1;                       # Venta al publico en general
            $documento = 1;                     # Documento Ticket
            $fechaVenta = date("Y-m-d");        # Fecha de venta
            $horaVenta = date("H:i:s");         # Hora de venta
            
            /**
             * Obtener datos enviados por POST                                              ---------------------------------
            */
            global $http;
            // Folio del pedido detalle
            $folio_pedido = $this->db->scape($http->request->get('folio_pedido'));
            // Id del producto
            $id_producto = (int) $http->request->get('id_producto');
            // Codigo de anticipo 
            $codigo_anticipo = $this->db->scape($http->request->get('codigo_anticipo'));
            if($codigo_anticipo == ''){
                throw new ModelsException('El código de anticipo es necesario para poder cancelar el pedido.');
            }
            // Metodo de pago
            $metodoPago = $this->db->scape($http->request->get('metodo_pago'));
            
            /**
             * Consultar pedido detalle por folio pedido, id producto y codigo de anticipo  --------------------------------
            */
            $pedido_detalle = $this->db->select(
                "ed.cantidad, p.precio_compra, p.precio, p.stock, p.ventasMostrador, p.total_salidas, ed.anticipo, ed.estado_anticipo", 
                "encargo_detalle AS ed", 
                "INNER JOIN productos AS p ON ed.id_producto=p.id", 
                "ed.folio_pedido = '$folio_pedido' AND ed.id_producto = '$id_producto' AND ed.codigo_anticipo = '$codigo_anticipo'",1);
            // Si no se ecnuentra el pedido
            if(!$pedido_detalle){ 
                throw new ModelsException('El pedido no existe o el código de anticipo es incorrecto.');
            }
            // Simplificar consulta
            $pedido_detalle = $pedido_detalle[0];
            // Estado del anticipo
            $estado_anticipo = $pedido_detalle['estado_anticipo'];
            // 1 si ya se aplico o entrego
            if($estado_anticipo == 1){
                throw new ModelsException('El pedido ya ha sido entregado.');
            // 2 si se se cancelo 
            }elseif($estado_anticipo == 2){
                throw new ModelsException('El pedido ya ha sido cancelado.');
            }
            
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);                                             # - Realizar consulta para obtener el almacen del usuario activo -
            $id_almacen = $almacen['id_almacen'];
            $caja = (new Model\Caja)->cargarCaja('id_almacen', $id_almacen);                                                # - Realizar consulta para obtener datos de la caja del día -
            if(!$caja) {                                                                                                    # - Si la caja del día no esta aperturada -
                throw new ModelsException('Es necesario registrar monto incial en caja para poder continuar.');
            } else {
                if($caja && $caja['estado'] == 1) {                                                                         # - Si la caja de día ya ha sido cerrada -
                    throw new ModelsException('No es posible realizar más movientos, la caja ha sido cerrada.');
                }
            }
            $idCaja = $caja['id_caja'];                                                                                     # - Obtener id de la caja del día
            
            // Obtener stock del producto en el almacen activo
            $stock = (new Model\Productos)->stock_producto($id_producto, $id_almacen);
            // Si no hay stock 
            if($stock < 1){
                throw new ModelsException('El stock del producto se encuentra en 0.');
            }
            
            if($documento == 1) {                                                       # - Si el documento es 1 (ticket)
                $datosTicket = (new Model\PuntoDeVenta)->datosTicket();                 # - Obtener datos del ticket
                $numero = $datosTicket['nVenta'];                                       # - Obtener numero de venta
                $folio_venta = $datosTicket['serie'].'-'.$datosTicket['numero'];        # - Crear folio de la venta
            } else {
                throw new ModelsException('El documento seleccionado no es válido.');
            }
            
            $cantidad = (int) $pedido_detalle["cantidad"];
            $costo = (real) $pedido_detalle["precio_compra"];
            $precio = (real) $pedido_detalle["precio"];
            $total = $cantidad * $precio;
            $anticipo = (real) $pedido_detalle["anticipo"];
            $resta = $total - $anticipo;
            
            $nuevoStock = $pedido_detalle['stock'] - $cantidad;                         # - Restar al stock del producto la cantidad solicitada -
            $nuevasVentas = $pedido_detalle['ventasMostrador'] + $cantidad;             # - Sumar a la ventas de mostrador del producto la cantidad solicitada -
            $nuevasSalidas = $pedido_detalle['total_salidas'] + $cantidad;              # - Sumar al total de salidas del producto la cantidad solicitada -
            
            $datosCliente = (new Model\Clientes)->cliente($cliente);                    # - Realizar consulta del cliente por su id para obtener datos -
            $compras = (int) $datosCliente['compras'];
            $nuevasCompras = $compras + $cantidad;
                
            if($metodoPago == 'efectivo'){
                if($resta > 0){
                    $tipo = 'Cobro';                                                    # - Tipo de movimiento a registrar en caja como "Cobro" -
                    $descripcion = 'Cobro por concepto de venta: '.$folio_venta.'.';    # - Descripción de movimiento a registrar en caja -
                    $this->db->insert('caja_movimientos', array(                        # - REGISTRAR COBRO EN CAJA - 
                        'id_caja' => $idCaja,
                        'tipo' => $tipo,
                        'concepto' => 'Venta',
                        'referencia' => $folio_venta,
                        'descripcion' => $descripcion,
                        'monto' => $resta,
                        'usuario' => $this->id_user,
                        'hora' => date('H:i:s')
                    ));
                }
            }elseif($metodoPago == 'tarjeta'){                                          
                
            }else{
                throw new ModelsException('Seleccionar un método de pago valido');
            }
            
            $id_venta = $this->db->insert('salida', array(                      # - REGISTRAR SALIDA (efectivo o tarjeta o puntos) -
                'id_almacen' => $id_almacen,
                'comprobante' => $documento,
                'numero' => $numero,
                'folio' => $folio_venta,
                'id_cliente' => $cliente,
                'metodo_pago' => $metodoPago,
                'total' => $resta,
                'anticipo' => $anticipo,
                'folio_anticipo' => $folio_pedido,
                'pago' => $resta,
                'cambio' => 0,
                'fechaVenta' => $fechaVenta,
                'horaVenta' => $horaVenta,
                'usuarioVenta' => $this->id_user,
                'impresion' => 0,
                'estado' => 1                                                   # - (1) Venta cerrada -
            ));
            
            $this->db->update('productos',array(                                # - ACTUALIZAR VENTAS, STOCK Y SALIDAS DEL PRODUCTO -
                'stock' => $nuevoStock, 
                'ventasMostrador' => $nuevasVentas,
                'total_salidas' => $nuevasSalidas
            ),"id='".$id_producto."'",1);

            $this->db->insert('salida_detalle', array(                          # - REGISTRAR DETALLE DE SALIDA - 
                'id_salida' => $id_venta,
                'id_producto' => $id_producto,
                'cantidad' => $cantidad,
                'vendido' => $cantidad,
                'puntos' => 0,
                'costo' => $costo,
                'precio' => $precio,
                'descuento' => 0,
                'estado' => 1
            ));

            $this->db->insert('productos_cardex', [                             # - REGISTRAR CARDEX COMO "venta" -
                'id_producto' => $id_producto,
                'cantidad' => $cantidad,
                'id_almacen' => $id_almacen,
                'id_clienteProveedor' => $cliente,
                'costo' => $costo,
                'precio' => $precio,
                'descuento' => 0,                                               # descuento al que sale
                'operacion' => 'venta',
                'movimiento' => 'vs',                                           # - (vs) = venta salida
                'referencia' => $folio_venta,
                'fecha' => $fechaVenta.' '.$horaVenta 
            ]);
            
            $this->db->update('clientes',array(                                 # - ACTUALIZAR COMPRAS A VENTA AL PUBLICO EN GENERAL -
                'compras' => $nuevasCompras,
                'fechaUltimaCompra' => $fechaVenta.' '.$horaVenta
            ),"id_cliente='$cliente'",1);
                    
            $this->db->update('encargo_detalle',array(                          # - ACTUALIZAR ESTADO DEL ANTICIPO AL PEDIDO Y ENLAZAR FOLIO DE VENTA -
                'folio_venta' => $folio_venta,
                'estado_anticipo' => 1,                                     # Entregado o aplicado
                'fecha_aplicado' => $fechaVenta,
                'hora_aplicado' => $horaVenta
            ),"folio_pedido = '$folio_pedido' AND id_producto = '$id_producto' AND codigo_anticipo = '$codigo_anticipo'",1);
            
            return array('status' => 'success', 'title' => '¡Venta registrada!', 'message' => 'La venta ha sido registrada correctamente con el folio '.$folio_venta.'.', 'idsalida' => $id_venta);
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}