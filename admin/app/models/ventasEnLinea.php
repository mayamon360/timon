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
 * Modelo ventasEnLinea
 */
class ventasEnLinea extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene todas las ventas, haciendo INNER JOIN a productos y usuarios
     * 
     * @return false|array
    */ 
    public function ventasCompletas() {
       $ventas = $this->db->select("*", "compras AS c", "INNER JOIN clientes AS cl ON c.id_cliente=cl.id_cliente", null, null, "ORDER BY c.id DESC");
        return $ventas;
    }

    /**
     * Obtiene datos de una compra según su id en la base de datos, haciendo INNER JOIN a productos y usuarios
     * 
     * @return false|array[0]
    */ 
    public function compra($id) {
        $compra = $this->db->select('*', 'compras_stripe AS c', null, "id='$id'");
        if($compra){
            return $compra[0];
        }else{
            return false;
        }
    }

    /**
     * Retorna los datos de las categorías para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarVentas() : array {
        global $http,$config;
        
        setlocale(LC_TIME, 'es_ES.UTF-8');

        $fecha = $this->db->scape($http->request->get('fecha'));
        if($fecha == ''){
            $ventas = $this->db->select("c.*, cl.cliente", "compras_stripe AS c", "INNER JOIN clientes AS cl ON c.id_cliente=cl.id_cliente", "c.id != 1 AND c.estatus != 'requires_payment_method'", null, "ORDER BY c.id DESC");
        }else{
            $ventas = $this->db->select("c.*, cl.cliente", "compras_stripe AS c", "INNER JOIN clientes AS cl ON c.id_cliente=cl.id_cliente", "c.id != 1 AND c.estatus != 'requires_payment_method' AND c.fecha = '$fecha'", null, "ORDER BY c.id DESC");
        }


        $data = [];

        if($ventas){
            
            foreach ($ventas as $key => $value) {
                
                $infoData = [];
                $infoData[] = $value["id"];
                $infoData[] = '<button class="btn btn-sm btn-link ver_compra" folio="'.$value["folio"].'" data-toggle="modal" data-target="#modal-compra">'.$value["folio"].'</button>';
                $infoData[] = $value["id_stripe"];
                $infoData[] = $value["cliente"];
                $infoData[] = strftime("%A, %d de %B de %Y a las %H:%M", strtotime($value['fecha'].' '.$value['hora']));
                
                $fecha_vale = date_create($value['fecha'].' '.$value['hora']);
                $intervalo = Helper\Strings::date_difference( date_format($fecha_vale, 'd-m-Y'), date('d-m-Y') );
                        
                switch ($value['estatus']) {
                    case 'requires_payment_method':
                        $infoData[] = 'Requiere método de pago';
                        break;
                    case 'requires_action':
                        if($intervalo <= 5){
                            $infoData[] = '<span class="badge bg-gray"><i class="fas fa-spinner"></i> Por pagar</span>';
                        }else{
                            $infoData[] = '<span class="badge bg-red"><i class="far fa-calendar-times"></i> Caduco</span>';
                        }
                        break;
                    case 'succeeded':
                        $infoData[] = '<span class="badge bg-light-blue"><i class="fas fa-check"></i> Pagado</span>';
                        break;
                    case 'refunded':
                        $infoData[] = '<span class="badge bg-red"><i class="fas fa-undo-alt"></i> Reembolsado</span>';
                        break;
                    case 'prepared':
                        $infoData[] = '<span class="badge bg-aqua"><i class="fas fa-box-open"></i> Preparado</span>';
                        break;
                    case 'sent':
                        $infoData[] = '<span class="badge bg-yellow"><i class="fas fa-shipping-fast"></i> Enviado</span>';
                        break;
                    case 'delivered':
                        $infoData[] = '<span class="badge bg-green"><i class="fas fa-check-double"></i> Entregado</span>';
                        break;
                    default:
                        $infoData[] = 'No definido';
                        break;
                }
                if($value['estatus'] == 'refunded'){
                    $monto = 0.00;
                }else{
                    $monto = ($value["monto_compra"] + $value["monto_envio"]);
                }
                
                $infoData[] = number_format($monto,2);
                $infoData[] = $value["envio"] == 1 ? '<img src="'.$config["build"]["urlAssetsPagina"].'assets/plantilla/img/general/FedEx.svg" title="Paquetería" height="15px">': 'LIBRERÍA';
                if($value['metodo_pago'] == 1){
                    $infoData[] = '<img src="'.$config["build"]["urlAssetsPagina"].'assets/plantilla/img/general/tarjeta.svg" title="Tarjeta" height="30px">';
                }else{
                    $infoData[] = '<img src="'.$config["build"]["urlAssetsPagina"].'assets/plantilla/img/general/oxxo.svg" title="Vale OXXO" height="20px">';
                }
                
                $btn_actualizar = '';
                $btn_preparar = '';
                $btn_reembolzar = '';
                $btn_enviar = '';
                $btn_entregar = '';
                
                switch ($value['estatus']) {
                    
                    case 'requires_action':                     // CASO 0 requiere pago OXXO
                        if($intervalo <= 5){
                            $btn_actualizar = '<button type="button" class="btn btn-sm btn-default cambiar_estatus" compra="'.$value["id"].'" id="actualizar"><i class="fas fa-redo"></i></button>
                            <button type="button" class="btn btn-sm btn-default cambiar_estatus" compra="'.$value["id"].'" id="confirmar_oxxo"><i class="fas fa-check"></i></button>';
                        } else {
                            $btn_actualizar = '';
                        }
                        break;
                    
                    case 'succeeded':                           // CASO 1 exitoso
                        if($value['metodo_pago'] == 1){         // Si pago con tarjeta
                            $btn_reembolzar = '<button type="button" class="btn btn-sm btn-danger cambiar_estatus" compra="'.$value["id"].'" id="reembolzar">Reembolzar</button>';
                        }
                        $btn_preparar = '<button type="button" class="btn btn-sm btn-default cambiar_estatus" compra="'.$value["id"].'" id="preparar">Preparar</button>';
                        break;
                        
                    case 'prepared':                            // CASO 2 preparado
                        if($value["envio"] == 1){               // Si se envia por FEDEX
                            $btn_enviar = '<button type="button" class="btn btn-sm btn-default cambiar_estatus" compra="'.$value["id"].'" id="enviar">Asignar guía</button>';
                        }else{
                            $btn_entregar = '<button type="button" class="btn btn-sm btn-default cambiar_estatus" compra="'.$value["id"].'" id="entregar">Entregar</button>';
                        }
                        break;
                        
                    case 'sent':                                // CASO 3 enviado
                        $btn_enviar = '<button type="button" class="btn btn-sm btn-default cambiar_estatus" compra="'.$value["id"].'" id="entregar">Entregado</button>';
                        break;
                }
                
                $infoData[] = '<div class="btn-group" role="group" aria-label="...">
                                    '.$btn_actualizar.$btn_preparar.$btn_reembolzar.$btn_enviar.$btn_entregar.'
                                </div>';
                                
                $data[] = $infoData;
                
            }
            
        }
        
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;

    }

    /**
     * Cambia el estatus de una compra
     *
     * 1:Enviado | 2:Entregado
     * 
     * @return array
    */ 
    public function cambiarEstatus() : array {
        try {
            global $http;
            $id = intval($http->request->get('compra'));
            $metodo = $http->request->get('metodo');
            $guia = $http->request->get('guia');
            
            $compra = $this->compra($id);
            $fecha = date("Y-m-d");
            $hora = date("H:i:s");
            
            if(!$compra){
                throw new ModelsException('La compra no existe.');
            }else{
                
                if($metodo == 'reembolzar'){
                    
                    if($compra['metodo_pago'] == 1){
                        if($compra['estatus'] == 'succeeded') {
                            \Stripe\Stripe::setApiKey('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');
                            $refund = \Stripe\Refund::create([
                                'charge' => $compra['id_stripe']
                            ]);
                            $this->db->update('compras_stripe',array('id_reembolzo' => $refund->id, 'estatus' => 'refunded'),"id='$id'");
                        }else{
                            throw new ModelsException('No se puede realizar el reembolzo.');
                        }
                    }else{
                        throw new ModelsException('El reembolzo solo aplica para pagos con tarjeta.');
                    }
                
                }elseif($metodo == 'confirmar_oxxo') {
                    if($compra['metodo_pago'] == 2){
                        $this->db->update('compras_stripe',array('estatus' => 'succeeded'),"id='$id'");
                    } else {
                        throw new ModelsException('El pago se realizó con tarjeta.');
                    }
                }elseif($metodo == 'preparar') {
                    
                    if($compra['estatus'] == 'succeeded') { 
                        
                        $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
                        $id_cliente = $compra['id_cliente'];
                        $venta_detalle = $this->db->select("*", "compras_stripe_detalle", null, "id_compra = '$id'");
                        
                        $totalPuntos = 0;
                        $sumaCantidad = 0;
                        $preparar = true;
                        
                        foreach($venta_detalle as $value){
                            
                            $id_producto = (int) $value['id_producto'];
                            $cantidad = (int) $value['cantidad'];
                            $mi_libro = (new Model\Productos)->producto($id_producto);
                            $stock = (new Model\Productos)->stock_producto($id_producto, $almacen['id_almacen']);
                            
                            if($stock < $cantidad){
                               $preparar = false; 
                            }
                            
                        }
                           
                        if($preparar) { 
                            foreach($venta_detalle as $value){
                                
                                $id_producto = (int) $value['id_producto'];
                                $cantidad = (int) $value['cantidad'];
                                $puntos = (real) $value['puntos'];
                                $costo = (real) $value['costo'];
                                $precio = (real) $value['precio'];
                                $descuento = (real) $value['descuento'];
                                
                                $mi_libro = (new Model\Productos)->producto($id_producto);
                                $stock = (new Model\Productos)->stock_producto($id_producto, $almacen['id_almacen']);
                                
                                if($stock >= $cantidad){
                                    
                                    $nuevoStock = $stock - $cantidad; 
                                    $nuevasVentas = $mi_libro['ventasMostrador'] + $cantidad;
                                    $nuevasSalidas = $mi_libro['total_salidas'] + $cantidad;
    
                                    // UPDATE PRODUTO
                                    $this->db->update('productos',array(
                                        'stock' => $nuevoStock, 
                                        'ventasMostrador' => $nuevasVentas,
                                        'total_salidas' => $nuevasSalidas
                                    ),"id='$id_producto'",1);
                                    
                                    
                                    // CARDEX PRODUCTO
                                    $this->db->insert('productos_cardex', [
                                        'id_producto' => $id_producto,
                                        'cantidad' => $cantidad,
                                        'id_almacen' => $almacen['id_almacen'],
                                        'id_clienteProveedor' => $id_cliente,
                                        'costo' => $costo,
                                        'precio' => $precio,
                                        'descuento' => $descuento,                                 
                                        'operacion' => 'venta',
                                        'movimiento' => 'vs',
                                        'referencia' => $compra['folio'],
                                        'fecha' => $fecha.' '.$hora
                                    ]);
                                    
                                    $totalPuntos += $puntos;
                                    $sumaCantidad += $cantidad;
                                }
                                
                            }
                        
                            $cliente = $this->db->select("*","clientes", null, "tipo=1 AND id_cliente = '$id_cliente'",1);
                            if($cliente){
                                $comprasBD = $cliente[0]['compras'];
                                $nuevasCompras = $comprasBD + $sumaCantidad;
                                
                                $puntosBD = $cliente[0]['puntos'];
                                $nuevosPuntos = $puntosBD + $totalPuntos;
                                
                                $this->db->update('clientes',array( 
                                    'compras' => $nuevasCompras,
                                    'puntos' => $nuevosPuntos, 
                                    'fechaUltimaCompra' => $fecha.' '.$hora),
                                "id_cliente='$id_cliente'",1);
                            }
                            
                            $this->db->update('compras_stripe',array('estatus' => 'prepared'),"id='$id'");
                        } else {
                            throw new ModelsException('Revisa el stock de los productos en la lista');
                        }
                    }else{
                        throw new ModelsException('El pedido aún no ha sido pagado.');
                    }
                }elseif($metodo == 'enviar'){
                    if($compra['estatus'] == 'prepared') { 
                        if($guia == '' || $guia === null){
                            throw new ModelsException('Proporcionar el número de guía.');
                        }
                        $this->db->update('compras_stripe',array('estatus' => 'sent', 'guia' => $guia),"id='$id'");
                    }else{
                        throw new ModelsException('El pedido aún no ha sido preparado.');
                    }
                }elseif($metodo == 'entregar' || $metodo == 'completar'){
                    if($compra['estatus'] == 'sent' || ($compra['estatus'] == 'prepared' && $compra['envio'] == 2)) { 
                        $this->db->update('compras_stripe',array('estatus' => 'delivered'),"id='$id'");
                    }else{
                        throw new ModelsException('El pedido no puede ser entregado.');
                    }
                }else{
                    throw new ModelsException('La acción no esta definida.');
                } 

            }

            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador){
                $administrador = $administrador;
                $perfiles = $administrador['perfiles'];
                foreach ($perfiles as $key => $value) {
                    if($value['principal']){
                        $perfil = $value['id_perfil'];
                    }
                }
            }

            (new Model\Actividad)->registrarActividad('Notificacion', 'Cambio de estado a '.$metodo.' del pedido en línea '.$id, $perfil, $administrador['id_user'], 8, date('Y-m-d H:i:s'), 0);
            
            $comprasAprovadas = $this->db->select("COUNT(id) AS succeeded", "compras_stripe", null, "estatus = 'succeeded'"); 
            $totalAprovadas = (int) $comprasAprovadas[0]['succeeded'];
        
            $comprasPreparadas = $this->db->select("COUNT(id) AS prepared", "compras_stripe", null, "estatus = 'prepared'");
            $totalPreparadas = (int) $comprasPreparadas[0]['prepared'];

            return array('status' => 'success', 'totalAprovadas' => $totalAprovadas, 'totalPreparadas' => $totalPreparadas);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }
    
    public function verCompra() {
        
        try {
            setlocale(LC_TIME, 'es_ES.UTF-8');
            global $http,$config;
            $folio = $http->request->get('folio');
            
            $compra = $this->db->select("c.*, cl.cliente, cl.telefono, cl.RFC", "compras_stripe AS c", "INNER JOIN clientes AS cl ON c.id_cliente = cl.id_cliente", "c.folio = '$folio'", null, "ORDER BY c.id DESC", 1);
            if(!$compra){
                throw new ModelsException('No hay información asociada a la referencia.');
            }
            $compra = $compra[0];
            if($compra['envio'] == 1){
                if($compra['guia'] != ''){
                    $enlace = ' <br>ID de Rastreo: <strong><a target="_blank" href="https://www.fedex.com/apps/fedextrack/index.html?tracknumbers='.$compra['guia'].'&cntry_code=mx">'.$compra['guia'].'</a></strong>';
                }else{
                    if($compra['estatus'] == 'refunded'){
                        $enlace = '';
                    }else{
                        $enlace = ' <br>Pendiente';
                    }
                    
                }
                $envio = 'Envío <strong>$ '.number_format($compra['monto_envio'],2).'</strong>'.$enlace;
            }else{
                $envio = 'Entrega en Librería</a>';
            }
            
            $metodo_pago = $compra['metodo_pago'] == 1 ? 'Tarjeta' : 'Vale OXXO';
            switch ($compra['estatus']) {
                case 'requires_payment_method':
                    $estatus = 'Requiere método de pago';
                    break;
                case 'requires_action':
                    $estatus = 'Por pagar';
                    break;
                case 'succeeded':
                    $estatus = 'Pagado';
                    break;
                case 'refunded':
                    $estatus = 'Reembolsado';
                    break;
                case 'prepared':
                    $estatus = 'Preparado';
                    break;
                case 'sent':
                    $estatus = 'Enviado';
                    break;
                case 'delivered':
                    $estatus = 'Entregado';
                    break;
                default:
                    $estatus = 'No definido';
                    break;
            }
            
            $cliente = $this->db->select("*", "clientes_envio", null, "id_cliente = {$compra['id_cliente']}");
            $cliente = $cliente[0];
            $estado = [
                1 => 'Aguascalientes',
                2 => 'Baja California',
                3 => 'Baja California Sur',
                4 => 'Campeche',
                5 => 'Coahuila',
                6 => 'Colima',
                7 => 'Chiapas',
                8 => 'Chihuahua',
                9 => 'Ciudad de México',
                10 => 'Durango',
                11 => 'Guanajuato',
                12 => 'Guerrero',
                13 => 'Hidalgo',
                14 => 'Jalisco',
                15 => 'Estado de México',
                16 => 'Michoacán',
                17 => 'Morelos',
                18 => 'Nayarit',
                19 => 'Nuevo León',
                20 => 'Oaxaca',
                21 => 'Puebla',
                22 => 'Querétaro',
                23 => 'Quintana Roo',
                24 => 'San Luis Potosí',
                25 => 'Sinaloa',
                26 => 'Sonora',
                27 => 'Tabasco',
                28 => 'Tamaulipas',
                29 => 'Tlaxcala',
                30 => 'Veracruz',
                31 => 'Yucatán',
                32 => 'Zacatecas'
            ];
            $estado_cliente = $estado[$cliente['state']];
            $html = '
            <table class="table text-center" style="margin-bottom:0px;">
                <tbody>
                    <tr>
                        <td style="width:50%; padding-bottom:0px; border-top:0px;">Subtotal <strong>$'.number_format($compra['monto_compra'],2).'</strong></td>
                        <td style="width:50%; padding-bottom:0px; border-top:0px;">'.$envio.'</td>
                    </tr>
                </tbody>
            </table>
            <h1 class="text-center" style="padding:0px; margin:0px; margin-bottom:10px;">
                <span class="total"><strong>$ '.number_format(($compra['monto_compra']+$compra['monto_envio']),2).'</strong></span>
            </h1>
            <table class="table table-striped" style="margin-bottom:20px;">
                <tbody>
                    <tr>
                        <td class="text-center" colspan="2">'.strftime("%A, %d de %B de %Y a las %H:%M", strtotime($compra['fecha'].' '.$compra['hora'])).'</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <a class="btn btn-sm btn-flat btn-default" role="button" data-toggle="collapse" href="#datosCliente" aria-expanded="false" aria-controls="datosCliente">
                                '.$compra['cliente'].' ('.$compra['id_cliente'].')
                            </a>
                            <div class="collapse" id="datosCliente" style="margin-top:10px;">
                                <div>
                                    <table class="table table-striped" style="margin-bottom:0px;">
                                        <tr>
                                            <th style="width:50%; padding-right:5px;" class="text-right">RFC</th>
                                            <td style="width:50%; padding-right:5px;" class="text-left">'.$compra['RFC'].'</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; padding-right:5px;" class="text-right">Teléfono</th>
                                            <td style="width:50%; padding-right:5px;" class="text-left">'.$compra['telefono'].'</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; padding-right:5px;" class="text-right">Código postal</th>
                                            <td style="width:50%; padding-right:5px;" class="text-left">'.$cliente['p_code'].'</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; padding-right:5px;" class="text-right">Estado</th>
                                            <td style="width:50%; padding-right:5px;" class="text-left">'.$estado_cliente.' ('.$cliente['state'].')</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; padding-right:5px;" class="text-right">Minicipio</th>
                                            <td style="width:50%; padding-right:5px;" class="text-left">'.$cliente['municipality'].'</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; padding-right:5px;" class="text-right">Colonia</th>
                                            <td style="width:50%; padding-right:5px;" class="text-left">'.$cliente['colony'].'</td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%; padding-right:5px;" class="text-right">Calle</th>
                                            <td style="width:50%; padding-right:5px;" class="text-left">'.$cliente['street'].' '.(($cliente['o_number'] == 0) ? 's/n' : '#'.$cliente['o_number']).'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                <strong>Entre las calles</strong><br>
                                                '.$cliente['b_streets'].'
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                <strong>Referencias</strong><br>
                                                '.$cliente['a_references'].'
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th style="width:50%; padding-right:5px;" class="text-right">Método pago:</th>
                        <td style="width:50%; padding-right:5px;" class="text-left ">'.$metodo_pago.'</td>
                    </tr>
                    <tr>
                        <th style="width:50%; padding-right:5px;" class="text-right">Estatus:</th>
                        <td style="width:50%; padding-right:5px;" class="text-left">'.$estatus.'</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <a href="'.$compra['comprobante'].'" target="_blank" class="btn btn-sm btn-flat btn-default">
                                Comprobante <i class="fas fa-print"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-muted text-center" style="margin-bottom:20px;">
                <h4>Resumen de la compra</h4>
            </div>
            ';
            
            $compra_detalle = $this->db->select("cd.*", "compras_stripe AS c", "INNER JOIN compras_stripe_detalle AS cd ON c.id = cd.id_compra", "c.folio = '$folio'");
            foreach($compra_detalle as $key => $value){
                $id = $value['id_producto'];
                $cantidad = (real) $value['cantidad'];
                $precio = (real) $value['precio'];
                $descuento = (real) $value['descuento'];
                if($descuento != 0){
                    $precio = (real) $value['precioDescuento'];
                }
                
                $mi_libro = $this->db->select("p.codigo, p.producto, IF(p.leyenda IS NULL, '', p.leyenda) AS leyenda, p.ruta, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial, p.imagen",
                                                "productos AS p", 
                                                "INNER JOIN editoriales AS e ON p.id_editorial = e.id_editorial 
                                                INNER JOIN productos_autores pa ON p.id = pa.id_producto
                                                INNER JOIN autores a ON pa.id_autor = a.id_autor",
                                                "p.id='$id'",
                                                null,
                                                "GROUP BY p.id 
                                                ORDER BY p.id DESC");
                
                if($mi_libro){
                    $mi_libro = $mi_libro[0];
                    if($mi_libro['leyenda'] != ''){
                        $leyenda = ' '.$mi_libro['leyenda'];
                    }else{
                        $leyenda = '';
                    }
                    $importe = $precio * $cantidad;
                    $html .= '
                    <div class="row" style="margin-top:10px; padding-top: 10px; border-top:1px solid #f4f4f4;">
                        <div class="col-xs-2">
                            <img src="'.$config['build']['urlAssetsPagina'].$mi_libro['imagen'].'" width="70px">
                        </div>
                        <div class="col-xs-10 align-self-center">
                            <p style="margin:0; padding:0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <a href="'.$config['build']['urlAssetsPagina'].'libro/'.$mi_libro['ruta'].'" target="_blank"><strong>'.$mi_libro['producto'].' '.$mi_libro['leyenda'].'</strong></a>
                            </p>
                            <p style="margin:0; padding:0;" class="text-muted">'.$mi_libro['editorial'].'</p>
                            <p style="margin:0; padding:0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="text-muted">'.(($mi_libro['autores'] == 'SIN AUTOR') ? '' : $mi_libro['autores']).'</p>
                            <div class="text-right">
                                <span class="float-right"><strong>'.$cantidad.'</strong> &#215; '.number_format($precio,2).' &#9552; $ '.number_format($importe,2).'</span>
                            </div>
                        </div>
                    </div>';
                }
                
            }
                
            return array(
                'status' => 'success', 
                'html' => $html
            );
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
        
    }
    
    public function checkShopping(){
        $comprasAprovadas = $this->db->select("COUNT(id) AS succeeded", "compras_stripe", null, "estatus = 'succeeded'"); 
        $totalAprovadas = (int) $comprasAprovadas[0]['succeeded'];
        
        $comprasPreparadas = $this->db->select("COUNT(id) AS prepared", "compras_stripe", null, "estatus = 'prepared'");
        $totalPreparadas = (int) $comprasPreparadas[0]['prepared'];

        return array('totalAprovadas' => $totalAprovadas, 'totalPreparadas' => $totalPreparadas);
    }
    
    
    
    

    public function reporteVentas() {
        $nombre = "Reporte de ventas.xls"; 
        $ventas = $this->ventasCompletas();

        if($ventas){
            header('Expires: 0');
            header('Cache-control: private');
            header('Content-type: application/vnd.ms-excel'); //Archivo excelS
            header('Cache-Control: cache, must-revalidate');
            header('Content-Description: File Transfer');
            header('Last-Modified: '.date('D, d M Y H:i:s'));
            header('Pragma: public');
            header('Content-Disposition: attachment; filename="'.$nombre.'"');
            header('Content-Transfer-Encoding: binary');
            $html = "";
            $html .= "
                <table border='0'>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>ID VENTA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>PRODUCTO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>CANT.</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>PRECIO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>VENTA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>MÉTODO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>CLIENTE</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>EMAIL</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>ESTATUS</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>DIRECCIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FECHA</td>
                    </tr>";
            $cont = 1;
            foreach ($ventas as $key => $value) {

                $backColor = ($cont%2==0) ? 'background-color:#f7f7f7;' : 'background-color:#fff;';

                if($value["metodo"] == 'gratis'){
                    $tipo = "<td style='border:1px solid #eee; color: rgb(119, 119, 119); ".$backColor."'>Gratis</td>";
                }elseif($value["metodo"] == 'paypal'){
                    $tipo = "<td style='border:1px solid #eee; color: #003087; ".$backColor."'>PayPal</td>";
                }elseif($value["metodo"] == 'payu'){
                    $tipo = "<td style='border:1px solid #eee; color: #b2d234; ".$backColor."'>Payu</td>";
                }

                if($value["tipo"] == 'virtual'){
                    $estatus = "<td style='border:1px solid #eee; color: rgb(119, 119, 119); ".$backColor."'>VE-3</td>";
                }else{
                    if($value["envio"] == 0){
                        $estatus = "<td style='border:1px solid #eee; color: rgb(221, 75, 57); ".$backColor."'>FE-1</td>";
                    }elseif($value["envio"] == 1){
                        $estatus = "<td style='border:1px solid #eee; color: rgb(243, 156, 18); ".$backColor."'>FE-2</td>";
                    }elseif($value["envio"] == 2){
                        $estatus = "<td style='border:1px solid #eee; color: rgb(0, 166, 90); ".$backColor."'>FE-3</td>";
                    }
                }  

                if($value["correoElectronico"] == $value["correoComprador"]){
                    $email = "<td style='border:1px solid #eee; ".$backColor."'>".$value["correoElectronico"]."</td>";
                }else{
                    $email = "<td style='border:1px solid #eee; ".$backColor."'>".$value['correoElectronico'].' - '.$value['correoComprador']."</td>";
                }

                $html .= "
                    <tr>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['id']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['producto']." ".$value['detalles']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['cantidad']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>$".number_format($value["precio"],2)."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>$".number_format(($value["cantidad"]*$value["precio"]),2)."</td>
                        ".$tipo."
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['nombre']."</td>
                        ".$email."
                        ".$estatus."
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['direccion'].' '.$value['pais']."</td>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['fechaCompra']."</td>
                    </tr>
                ";

                $cont++;
            }

            $html .= '</table>';
            echo utf8_decode($html);
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