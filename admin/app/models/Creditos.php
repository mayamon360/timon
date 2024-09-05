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
 * Modelo Creditos
 */
class Creditos extends Models implements IModels {
    use DBModel;

    public function creditos() {
        $creditos = $this->db->select('*', 'credito', null, null, null, "ORDER BY id_credito DESC");
        return $creditos;
    }
    
    public function credito($id) {
        $credito = $this->db->select('*', 'credito', null, "id_credito='$id'", 1);
        if($credito){
            return $credito[0];   
        }
        return false;
    }
    
    public function creditosPor($item,$valor) {
        $creditos = $this->db->select('*', 'credito', null, "$item='$valor'", null, "ORDER BY id_credito DESC");
        return $creditos;
    }
    
    public function creditoDetalle($item, $valor){
        $credito = $this->db->select('*','credito_detalle', null,"$item = '$valor'");
        return $credito;
    }
    
    public function creditoDetallePor($item,$valor, $item2,$valor2){
        $credito = $this->db->select('*','credito_detalle', null,"$item = '$valor' AND $item2 = '$valor2'");
        return $credito;
    }

    public function salidas($id_credito) {
        $salidas = $this->db->select('*', 'credito_historial', null, "tipo='salida' AND id_credito='$id_credito'", null, "ORDER BY fecha,hora DESC");
        return $salidas;
    }

    public function devoluciones($id_credito) {
        $salidas = $this->db->select('*', 'credito_historial', null, "tipo='devolucion' AND id_credito='$id_credito'", null, "ORDER BY fecha,hora DESC");
        return $salidas;
    }

    public function pagos($id_credito){
        $salidas = $this->db->select('*', 'credito_pagos', null, "id_credito='$id_credito'", null, "ORDER BY fecha,hora DESC");
        return $salidas;
    }

    /**
     * Retorna los datos de los créditos para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarCreditos() : array {
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
            $creditos = $this->db->select('*', 'credito', null, "fecha BETWEEN '$start_date' AND '$end_date'", null, "ORDER BY id_credito DESC");
        }

        $data = [];
        if($creditos){
            foreach ($creditos as $key => $value) {
                
                $infoData = [];

                $fechaVenta = $value['fecha'].' '.$value['hora'];
                $infoData[] = $fechaVenta;

                $infoData[] = '<span class="badge bg-black" style="font-weight:800;">'.$value["folio"].'</span>';

                $almacen = (new Model\Almacenes)->almacen($value['id_almacen']);
                $infoData[] = $almacen['almacen'];

                $usuario = (new Model\Administradores)->administrador($value['usuario']);
                $infoData[] = $usuario["name"];
                
                $cliente = (new Model\Clientes)->cliente($value["id_cliente"]);
                $infoData[] = $cliente["cliente"];

                $subtotal = $this->db->select('SUM( vendido * ( precio - ( ( precio * descuento ) / 100 ) ) ) AS subtotal', 'credito_detalle', null, "id_credito = '".$value['id_credito']."'");
                $infoData[] = '$ '.number_format($subtotal[0]['subtotal'],2);

                $time = strtotime($fechaVenta);
                $timeUnaHora = $time + 3600;
                $hoy = date('Y-m-d H:i:s');
                $f_hoy = Helper\Functions::fecha($hoy);
                $f_ven = Helper\Functions::fecha($fechaVenta);
                $f1 = substr($f_hoy,0,-17);
                $f2 = substr($f_ven,0,-17);
                if(time() < $timeUnaHora){
                    $fecha = Helper\Strings::amigable_time(strtotime($fechaVenta));
                }elseif(substr($hoy, 0, -9) == substr($fechaVenta, 0, -9)){
                    $fecha = str_replace($f2, 'Hoy ', $f_ven);
                }else{
                    $fecha = Helper\Functions::fecha($fechaVenta);
                }
                $infoData[] = $fecha;

                switch ($value["estado"]) {
                    case 0:
                        $infoData[] = '<span class="label bg-red">Cancelado</span>';
                        break;
                    case 1:
                        $infoData[] = '<span class="label bg-gray">Abierto</span>';
                        break;

                    case 2:
                        $infoData[] = '<span class="label bg-aqua">Por pagar</span>';
                        break;

                    case 3:
                        $infoData[] = '<span class="label bg-green">Pagado</span>';
                        break;
                }

                $btn_ver = "<button type='button' class='btn btn-sm btn-default btn-flat mostrarCredito' folio='".$value["folio"]."' tipo='Crédito'>
                                <i data-toggle='tooltip' title='Vista general' class='fas fa-eye'></i>
                            </button>";
                $btn_nota = "<a href='creditos/nota/".$value['id_credito']."' target='_blank' class='btn btn-sm btn-flat btn-default' key='".$value["id_credito"]."'>
                                <i data-toggle='tooltip' title='Descargar nota' class='fas fa-file-alt'></i>
                            </a>"; 
                $btn_editar = "<a href='creditos/editar/".$value["id_credito"]."' class='btn btn-sm btn-flat btn-default'>
                                <i data-toggle='tooltip' title='Mostrar' class='fas fa-list'></i>
                            </a>";
                $btn_cancelar = "<button type='button' class='btn btn-sm btn-default btn-flat calcelarCredito' folio='".$value["folio"]."' key='".$value["id_credito"]."' tipo='Cancelar'>
                                <i data-toggle='tooltip' title='Cancelar crédito' class='fas fa-window-close text-red'></i>
                            </button>";
                            
                $btn_group = "<div class='btn-group'>";
                if($value['estado'] == 0){                                      # cancelado                                  

                    $btn_group .= $btn_ver;
                    $btn_group .= $btn_nota;

                }elseif($value['estado'] == 1){                                 # abierto

                    $btn_group .= $btn_ver;
                    $btn_group .= $btn_nota;
                    $btn_group .= $btn_editar;
                    
                    $creditoDevoluciones = $this->db->select("*", "credito_detalle", null, "id_credito = '{$value["id_credito"]}' AND devolucion > 0"); 
                    $creditoHistorial = $this->db->select("*", "credito_historial", null, "id_credito = '{$value["id_credito"]}' AND tipo = 'devolucion'"); 
                    $creditoPagos = $this->db->select("*", "credito_pagos", null, "id_credito = '{$value["id_credito"]}'"); 
                    // Si el crédito aun no tienen devoluciones y no tiene abonos, si se puede cancelar
                    if(!$creditoDevoluciones && !$creditoHistorial && !$creditoPagos){
                        $btn_group .= $btn_cancelar;
                    }

                }elseif($value['estado'] == 2){                                 # cerrado

                    $btn_group .= $btn_ver;
                    $btn_group .= $btn_nota;
                    $btn_group .= $btn_editar;
                    
                    $creditoDevoluciones = $this->db->select("*", "credito_detalle", null, "id_credito = '{$value["id_credito"]}' AND devolucion > 0"); 
                    $creditoHistorial = $this->db->select("*", "credito_historial", null, "id_credito = '{$value["id_credito"]}' AND tipo = 'devolucion'"); 
                    $creditoPagos = $this->db->select("*", "credito_pagos", null, "id_credito = '{$value["id_credito"]}'"); 
                    // Si el crédito aun no tienen devoluciones y no tiene abonos, si se puede cancelar
                    if(!$creditoDevoluciones && !$creditoHistorial && !$creditoPagos){
                        $btn_group .= $btn_cancelar;
                    }

                }elseif($value['estado'] == 3){                                 # pagado

                    $btn_group .= $btn_ver;
                    $btn_group .= $btn_nota;
                    $btn_group .= $btn_editar;

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

    public function mostrarListaCredito() {
        try {
            global $config, $http;
            # Obtener la url de multimedia
            $folio = $this->db->scape($http->request->get('folio'));
            $credito = $this->creditosPor('folio',$folio);

            if($credito){

                $creditoDetalle = $this->creditoDetalle('id_credito',$credito[0]['id_credito']);

                $tbody = '';
                $tfoot = '';

                $sumaCantidad = 0;
                $sumaDevolucion = 0;
                $sumaVendido = 0;

                $sumaSubtotal = 0;

                foreach ($creditoDetalle as $key => $value) {
                    
                    $cantidad = (int) $value['cantidad'];                           # Cantidad solicitada
                    $devolucion = (int) $value['devolucion'];                       # Cantidad en devolucion
                    $vendido = (int) $value['vendido'];                             # Cantidad vendida

                    $sumaCantidad += $cantidad;                                     # Suma de la cantidad solicitada de cada libro 
                    $sumaDevolucion += $devolucion;                                 # Suma de la cantidad en devolucion de cada libro 
                    $sumaVendido += $vendido;                                       # Suma de la cantidad vendida de cada libro 

                    $producto = (new Model\Productos)->productoNi($value['id_producto']);
                    $codigo = $producto['codigo'];

                    $precio = (real) $value['precio'];                              # Precio
                    $descuento = (int) $value['descuento'];                         # Descuento

                    if($descuento != 0){                                              # - Si el descuento es diferente de 0 -
                        $precioDescuento = $precio - ( ($precio * $descuento) / 100 );# - Calcular el precio con descuento -
                        $subtotal = $vendido * $precioDescuento;                      # - Calcular subtotal con el precio descuento -
                    }else{                                                            # - Si el descuento es 0 -
                        $precioDescuento = 0;                                         # - Definir precio descuento como 0
                        $subtotal = $vendido * $precio;                               # - Calcular sobtotal con el precio normal -
                    }

                    if($precioDescuento != 0){                                        # - Si el precio descuento es diferente de 0 -
                        $precioFormato = '<span class="badge bg-gray"><del><b>$ '.number_format($precio, 2).'</b></del></span> <span class="badge bg-blue">$ '.number_format($precioDescuento,2).'</span>'; # - Mostrar precio normal y precio con descuento -
                    }else{
                        $precioFormato = '<span class="badge bg-blue">$ '.number_format($precio, 2).'</span>'; # - Mostrar solo precio normal -
                    }

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

                    $sumaSubtotal += $subtotal;

                    $tbody .= '
                    <tr>
                        <td class="text-center font-weight-bold text-blue" style="vertical-align: middle;">'.$cantidad.'</td>
                        <td class="text-center font-weight-bold text-red" style="vertical-align: middle;">'.$devolucion.'</td>
                        <td class="text-center font-weight-bold text-green" style="vertical-align: middle;">'.$vendido.'</td>
                        <td style="vertical-align: middle;">
                            <span class="badge bg-purple" style="font-weight:800;">'.$codigo.'</span> <strong>'.$producto['producto'].'</strong> '.$producto['leyenda'].' - '.$editorial.' - '.$autores.' <i class="fas fa-info-circle infoDetalles text-aqua" data-toggle="popover" title="<b>FICHA TÉCNICA</b>" data-content="<small>'.$detallesTexto.'</small>"></i>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            '.$precioFormato.'
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            '.$descuento.' %
                        </td>
                        <td class="text-right font-weight-bold" style="vertical-align: middle;">$ '.number_format($subtotal,2).'</td>
                    </tr>';
                }

                $pagos = $this->db->select('SUM(monto) as pagos', "credito_pagos", null, "id_credito='".$credito[0]['id_credito']."'");
                
                if($pagos){
                    $pagos = $pagos[0]['pagos'];
                }else{
                    $pagos = 0;
                }

                $resta = $sumaSubtotal - $pagos;

                $tfoot = '
                <tr>
                    <th class="text-center font-weight-bold text-blue">'.$sumaCantidad.'</th>
                    <th class="text-center font-weight-bold text-red">'.$sumaDevolucion.'</th>
                    <th class="text-center font-weight-bold text-green">'.$sumaVendido.'</th>
                    <th class="text-right" colspan="3">TOTAL:</th>
                    <th class="text-right">$ '.number_format($sumaSubtotal,2).'</th>
                </tr>
                <tr>
                    <th class="text-right" colspan="6">PAGOS</th>
                    <th class="text-right">$ '.number_format($pagos,2).'</th>
                </tr>
                <tr>
                    <th class="text-right" colspan="6">RESTA:</th>
                    <th class="text-right text-red">$ '.number_format($resta,2).'</th>
                </tr>';

                return array('status' => 'success', 'tbody' => $tbody, 'tfoot' => $tfoot);

            }else{

                throw new ModelsException('El crédito no existe.');

            }

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    // CARGAR DATOS EN LA PESTAÑA DE EDITAR CRÉDITO
    public function cargarListaCreditoEditar() {
        global $http;

        $id_credito = intval($http->request->get('id_credito'));                    # Id del crédito recibido
        $credito = $this->credito($id_credito);                                     # Buscar el crédito por id               
        
        if($credito){                                                               # Si el crédito existe

            if($credito['estado'] == 0){                                            # Si el crédito está 0 = cancelado
                return array("status" => 'error');
            }
            
            $creditoDetalle = $this->creditoDetalle('id_credito',$id_credito);      # Obtener lista de productos en crédito

            $tbody = '';

            $sumaCantidad = 0;                                                      # Suma cantidades
            $sumaDevolucion = 0;                                                    # Suma devoluciones
            $sumaVendido = 0;                                                       # Suma ventas reales

            $sumaSubtotal = 0;                                                      # Suma subtotal o total general

            foreach ($creditoDetalle as $key => $value) {                           # Recorrer lista de productos
                
                $miProducto = (new Model\Productos)->producto($value['id_producto']);   # Obtener datos del producto
                $codigo = $miProducto['codigo'];
                $producto = $miProducto['producto'];
                $leyenda = $miProducto['leyenda'];

                $cantidad = (int) $value['cantidad'];
                $devolucion = (int) $value['devolucion'];
                $vendido = (int) $value['vendido'];
                $precio = (real) $value['precio'];
                $descuento = (int) $value['descuento'];

                if($descuento != 0){                                                    # Siempre tendrá descuento
                    $precioDescuento = $precio - ( ($precio * $descuento) / 100 );
                    $subtotal = $vendido * $precioDescuento;
                }else{
                    $precioDescuento = 0;
                    $subtotal = $vendido * $precio;
                }

                $sumaCantidad += $cantidad;
                $sumaDevolucion += $devolucion;
                $sumaVendido += $vendido;

                $sumaSubtotal += $subtotal;

                if($precioDescuento != 0){
                    $precioFormato = '<span class="badge bg-gray"><del>$'.number_format($precio, 2).'</del></span> <span class="badge bg-blue">'.number_format($precioDescuento,2).'</span>';   
                }else{
                    $precioFormato = '<span class="badge bg-blue">$ '.number_format($precio, 2).'</span>';
                }

                $tbody .= '
                <tr class="hoverTrDefault">

                    <td style="vertical-align:middle;" class="text-center">';

                        if($credito['estado'] == 1){    # Estado en 1, puede generar devoluciones
                            $tbody .= '
                            <div class="form-group" style="margin-bottom:0;">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm text-right inputDevolucion" id="inputDevolucion'.$value['id_producto'].'" vendido="'.$value['vendido'].'" key="'.$value['id_producto'].'" placeholder="0">
                                    <div class="input-group-btn">
                                        <button type="button" disabled class="btn btn-default btn-flat btn-sm btnDevolucion" id="btnDevolucion'.$value['id_producto'].'" key="'.$value['id_producto'].'" producto="'.$producto.'">
                                            <i class="fas fa-check" title="Aplicar devolución" data-toggle="tooltip"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>';
                        }                    

                    $tbody .= '
                    </td>

                    <td style="vertical-align:middle;" class="text-center">
                        '.$cantidad.'
                    </td>

                    <td style="vertical-align:middle;" class="text-center">
                        '.$devolucion.'
                    </td>

                    <td style="vertical-align:middle;" class="text-center">
                        '.$vendido.'
                    </td>

                    <td style="vertical-align:middle;">
                        <span class="badge bg-purple">'.$codigo.'</span> <strong>'.$producto.'</strong> '.$leyenda.'
                    </td>

                    <td style="vertical-align:middle;" class="text-center">
                        '.$precioFormato.'
                    </td>

                    <td style="vertical-align:middle;" class="text-center">
                        '.$descuento.' %   
                    </td>

                    <td style="vertical-align:middle;" class="text-right">
                        $ '.number_format(($subtotal),2).'
                    </td>

                </tr>';

            }
            
            $id_cliente = $credito['id_cliente'];                               # Obtener id del cliente 
            
            $clientes_porcentajes = $this->db->select('*','clientes_porcentajes',null,"id_cliente='$id_cliente'");
            if($clientes_porcentajes){
                $limite_devolucion = (int) $clientes_porcentajes[0]['devolucion'];
            }else{
                $limite_devolucion = 0;
            }
            
            $limiteGeneral = intval( ($sumaCantidad * $limite_devolucion) / 100 );      
            $limiteGeneral = $limiteGeneral - $sumaDevolucion;

            $totalFormat = "$ ".number_format($sumaSubtotal,2);

            $porcentaje_devolucion = round( ($sumaDevolucion * 100) / $sumaCantidad );
            $porcentaje_venta = round( ($sumaVendido * 100) / $sumaCantidad );

            $existen_pagos = $this->db->select('*', 'credito_pagos', null, "id_credito = '$id_credito'");
            if($existen_pagos){
                $pagos = $this->db->select("SUM(monto) AS pagos", "credito_pagos", null, "id_credito='$id_credito'");
                $pagos = $pagos[0]['pagos'];
            }else{
                $pagos = 0;
            }

            $resta = ($sumaSubtotal - $pagos);

            return array("status"=>'success',
                        "tbody" => $tbody, 
                        "sumaCantidad"=> $sumaCantidad,
                        "sumaDevolucion"=> $sumaDevolucion,
                        "sumaVendido" => $sumaVendido, 
                        "totalG" => $totalFormat,
                        'totalNumero' => number_format($sumaSubtotal,2),
                        "limite" => $limiteGeneral,
                        'porcentaje_devolucion' => $porcentaje_devolucion,
                        'porcentaje_venta' => $porcentaje_venta,
                        'pagos' => "$ ".number_format($pagos,2),
                        'resta' => "$ ".number_format($resta,2)
            );
                    
        }else{
            return array("status" => 'error');
        }
    }

    // CANCELAR CRÉDITO (solo si el estado del crédito es igual a 1 y no tiene registro de devoluciones ni de pagos)
    public function cancelarCredito(){
        try {
            global $http;

            $id_credito = intval($http->request->get('id'));
            $credito = $this->credito($id_credito);

            $password = $this->db->scape($http->request->get('pass'));
            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();
            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $almacen = (new Model\Almacenes)->almacenPrincipal($userProfile["id_user"]);

            $fecha_modificacion = date('Y-m-d H:i:s');
            
            # SI EXISTE EL CRÉDITO
            if($credito){
            
            	if($credito['id_almacen'] != $almacen['id_almacen']){
                     throw new ModelsException('El crédito '.$credito['folio'].' no se realizó en esté almacen.');
               }

                # SI EL ESTADO DEL CRÉDITO ES 1 = ABIERTO
                if($credito['estado'] == 1){

                    # VALIDAR SI EL CRÉDITO TIENE DEVOLCIONES
                    $creditoDevoluciones = $this->db->select("*", "credito_detalle", null, "id_credito = '$id_credito' AND devolucion > 0"); 
                    $creditoHistorial = $this->db->select("*", "credito_historial", null, "id_credito = '$id_credito' AND tipo = 'devolucion'"); 
                    if($creditoDevoluciones || $creditoHistorial){
                        throw new ModelsException('El crédito no puede ser cancelado por que ya tiene devoluciones registradas.');
                    }

                    # VALIDAR SI EL CRÉDITO TIENE PAGOS
                    $creditoPagos = $this->db->select("*", "credito_pagos", null, "id_credito = '$id_credito'"); 
                    if($creditoPagos){
                        throw new ModelsException('El crédito no puede ser cancelado por que ya tiene pagos registrados.');
                    }

                    # OBTENER LISTA DE PRODUCTOS
                    $lista_productos = $this->creditoDetalle('id_credito', $id_credito);

                    $sumaCantidad = 0;

                    # RECORRER LISTA
                    foreach ($lista_productos as $key => $value) {

                        $idProducto = $value['id_producto'];
                        $cantidad = (int) $value['cantidad'];
                        $costo = (real) $value['costo'];
                        $precio = (real) $value['precio'];
                        $descuento = (int) $value['descuento'];

                        $sumaCantidad += $cantidad;

                        # REGISTRAR MOVIMIENTO EN CARDEX
                        $this->db->insert('productos_cardex', [
                            'id_producto' => $idProducto,
                            'cantidad' => $cantidad,
                            'id_almacen' => $almacen['id_almacen'],
                            'id_clienteProveedor' => $credito['id_cliente'],
                            'costo' => $costo,
                            'precio' => $precio,
                            'descuento' => $descuento,
                            'operacion' => 'devolucion',
                            'movimiento' => 'crdf',                                 # crdf = credito devolucion por folio (credito cancelado)                              
                            'referencia' => $credito['folio'],
                            'fecha' => $fecha_modificacion 
                        ]);

                        # ACTUALIZAR STOCK DEL PRODUCTO
                        $producto = (new Model\Productos)->producto($idProducto);
                        $nuevoStock = $producto['stock'] + $cantidad;
                        $nuevasEntradas = $producto['total_entradas'] + $cantidad;
                        $nuevasVentas = $producto['ventasMostrador'] - $cantidad;
                        $this->db->update('productos', array(
                            'stock' => $nuevoStock,
                            'ventasMostrador' => $nuevasVentas,
                            'fechaModificacion' => $fecha_modificacion,
                            'usuarioModificacion' => $this->id_user,
                            'total_entradas' => $nuevasEntradas
                        ), "id='$idProducto'", 1);

                        # ACTUALIZAR DEVOLCIÓN Y CANTIDAD VENDIDA EN EL DETALLE DE CREDITO
                        $id_cd = $value['id_cd'];
                        $this->db->update('credito_detalle', array(
                            'devolucion' => $cantidad,
                            'vendido' => 0 
                        ), "id_cd='$id_cd' AND id_credito='$id_credito' AND id_producto='$idProducto'", 1);

                    }

                    # QUITAR LA CANTIDAD DE COMPRAS AL CLIENTE
                    if($credito['id_cliente'] != 1){
                        $cliente = (new Model\Clientes)->cliente($credito['id_cliente']);
                        if($cliente['tipo'] == 2){
                            $nuevasCompras = $cliente['compras'] - $sumaCantidad;
                            $this->db->update('clientes', array(
                                'compras' => $nuevasCompras,
                                'fechaModificacion' => $fecha_modificacion,
                                'usuarioModificacion' => $this->id_user
                            ), "id_cliente='{$credito['id_cliente']}'", 1);
                        }
                    }

                    # ACTUALIZAR EL CRÉDITO
                    $this->db->update('credito', array(
                        'estado' => 0
                    ), "id_credito='$id_credito'");
                    
                    return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => "El crédito {$credito['folio']} se canceló correctamente.");

                }else{
                    throw new ModelsException('El crédito no puede ser cancelado.');
                }

            }else{
                throw new ModelsException('El crédito no existe.');
            }

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage()); 
        }
    }

    public function devolucionCredito() {
        try {
            global $http;
            $id_producto = intval($http->request->get('id_producto'));
            $id_credito = intval($http->request->get('id_credito'));
            $devolucion = intval($http->request->get('devolucion'));
            $password = $this->db->scape($http->request->get('pass'));

            $fecha = date('Y-m-d');
            $hora = date("H:i:s");

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $almacen = (new Model\Almacenes)->almacenPrincipal($userProfile["id_user"]);

            $credito = $this->credito($id_credito);
            if(!$credito){
                throw new ModelsException('El crédito no existe.');
            }

            if($credito['estado'] == 0){
                throw new ModelsException('El crédito '.$credito['folio'].' ya ha sido cancelado.');
            }
            if($credito['estado'] == 2){
                throw new ModelsException('El crédito '.$credito['folio'].' ya ha sido cerrado.');
            }
            if($credito['id_almacen'] != $almacen['id_almacen']){
                throw new ModelsException('El crédito '.$credito['folio'].' no se realizó en esté almacen.');
            }

            $cliente = (new Model\Clientes)->cliente($credito['id_cliente']);

            $cantidades = $this->db->select("SUM(cantidad) as cantidad, SUM(devolucion) as devolucion, SUM(vendido) as vendido", "credito_detalle", null, "id_credito = '$id_credito'");
            
            $can = (int) $cantidades[0]['cantidad'];
            $dev = (int) $cantidades[0]['devolucion'];
            $ven = (int) $cantidades[0]['vendido'];
            
            $id_cliente = $credito['id_cliente'];                               # Obtener id del cliente 
            $id_editorial = $credito['id_editorial'];                           # Obtener id de la editorial 
            // Obtener 
            $limite_devolucion = $this->db->select('*', 'clientes_editoriales', null, "id_cliente='$id_cliente' AND id_editorial='$id_editorial'",1);
            $limite_devolucion = (int) $limite_devolucion[0]['devolucion'];

            $limite = intval( ($can * $limite_devolucion) / 100 );              # Obtener el limite de devolucion permitida (20%) en cantidad
            $limite = $limite - $dev;                                           # Obtener el limite restando las devoluciones (limite = limite cantidad - devoluciones hechas)

            if($devolucion > $limite){                                          # Si la devolucion que se pretende hacer ya supera el limite permitido
                throw new ModelsException('La devolución supera el '.$limite_devolucion.'% permitido. (Devolución permitida:'.$limite.').');
            }

            $item_producto = $this->creditoDetallePor('id_credito',$id_credito, 'id_producto', $id_producto);
            if(!$item_producto){
                throw new ModelsException('El producto y el crédito no coinciden.');
            }
            $item_producto = $item_producto[0];
            $cantidad_producto = (int) $item_producto['vendido'];
            $plural = ($cantidad_producto == 1) ? '' : 's';
            if($devolucion > $cantidad_producto){
                throw new ModelsException('La devolución del libro no debe ser mayor a '.$cantidad_producto.' pieza'.$plural.'.');
            }
            $costo = (real) $item_producto['costo'];
            $precio = (real) $item_producto['precio'];
            $descuento = (int) $item_producto['descuento'];

            $producto = (new Model\Productos)->producto($id_producto);

            # REGISTRAR MOVIMIENTO EN CARDEX
            $this->db->insert('productos_cardex', [
                'id_producto' => $id_producto,
                'cantidad' => $devolucion,
                'id_almacen' => $almacen['id_almacen'],
                'id_clienteProveedor' => $credito['id_cliente'],
                'costo' => $costo,
                'precio' => $precio,
                'descuento' => $descuento,
                'operacion' => 'devolucion',
                'movimiento' => 'crdc',                                 # crdc = credito devolucion por cantidad
                'referencia' => $credito['folio'],
                'fecha' => $fecha.' '.$hora 
            ]);

            # REGISTRAR HISTORIAL DE CREDITO (DEVOLUCION) 
            $this->db->insert('credito_historial', array(                             
                'id_credito' => $id_credito,
                'tipo' => 'devolucion',
                'id_producto' => $id_producto,
                'cantidad' => $devolucion,
                'fecha' => $fecha,
                'hora' => $hora
            ));

            # ACTUALIZAR STOCK DEL PRODUCTO
            $nuevoStock = $producto['stock'] + $devolucion;
            $nuevasEntradas = $producto['total_entradas'] + $devolucion;
            $nuevasVentas = $producto['ventasMostrador'] - $devolucion;
            $this->db->update('productos', array(
                'stock' => $nuevoStock,
                'ventasMostrador' => $nuevasVentas,
                'fechaModificacion' => $fecha.' '.$hora,
                'usuarioModificacion' => $this->id_user,
                'total_entradas' => $nuevasEntradas
            ), "id='$id_producto'", 1);

            # ACTUALIZAR CREDITO
            $this->db->query("UPDATE credito_detalle SET devolucion = (devolucion + $devolucion), vendido = (vendido - $devolucion) WHERE id_credito = '$id_credito' AND id_producto = '$id_producto'",1);
            
            # QUITAR LA CANTIDAD AL CLIENTE
            $nuevasCompras = $cliente['compras'] - $devolucion;
            $this->db->update('clientes', array(
                'compras' => $nuevasCompras,
                'fechaModificacion' => $fecha.' '.$hora,
                'usuarioModificacion' => $this->id_user
            ), "id_cliente='{$credito['id_cliente']}'", 1);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La devolución del producto se realizó correctamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage()); 
        }
    }

    public function cerrarCredito(){
        try {
            global $http;
            $password = $this->db->scape($http->request->get('pass'));
            $id_credito = intval($http->request->get('id_credito'));

            $fecha = date('Y-m-d');
            $hora = date("H:i:s");

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $credito = $this->credito($id_credito);
            if(!$credito){
                throw new ModelsException('El crédito no existe.');
            }

            if($credito['estado'] != 1){
                throw new ModelsException('El crédito '.$credito['folio'].' ya ha sido cerrado, cancelado o pagado.');
            }

            $this->db->update('credito', array(
                'estado' => 2,
                'fechaCierre' => $fecha.' '.$hora,
                'usuarioCierre' => $this->id_user
            ), "id_credito='$id_credito'", 1);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El crédito se cerró correctamente, puede seguir realizando abonos.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage()); 
        }
    }

    public function abonarCredito(){
        try {
            global $http;
            $password = $this->db->scape($http->request->get('pass'));
            $id_credito = intval($http->request->get('id_credito'));
            $metodo = $this->db->scape($http->request->get('metodo_pago'));
            $monto = (real) $http->request->get('monto_abono');
            $referencia = $this->db->scape($http->request->get('referencia'));
            $descripcion = $this->db->scape($http->request->get('descripcion'));

            $arrayMetodosPago = ['efectivo', 'deposito', 'condonacion'];

            $fecha = date('Y-m-d');
            $hora = date("H:i:s");

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();                                            

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $credito = $this->credito($id_credito);
            if(!$credito){
                throw new ModelsException('El crédito no existe.');
            }

            if($credito['estado'] == 0){
                throw new ModelsException('El crédito '.$credito['folio'].' está cancelado.');
            }

            if($credito['estado'] == 3){
                throw new ModelsException('El crédito '.$credito['folio'].' ya ha sido pagado.');
            }

            if(!in_array($metodo, $arrayMetodosPago)){
                throw new ModelsException('Selecciona un metódo de pago válido.');
            }
            if($monto == 0){
                throw new ModelsException('Es necesario especificar el monto a abonar.');
            }
            if($metodo == 'efectivo'){
                $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
                $caja = (new Model\Caja)->cargarCaja('id_almacen', $almacen['id_almacen']);
                if(!$caja){
                    throw new ModelsException('Para poder realizar movimientos por favor especificar primero el total de monto inicial.');
                }else{
                    if($caja['estado'] == 1){
                        throw new ModelsException('La caja ya ha sido cerrada, no es posible registrar más movimientos.');
                    }
                }
                $id_caja = $caja['id_caja'];
            }
            if($metodo == 'deposito'){
                if($referencia == '' || $referencia === null){
                    throw new ModelsException('Es necesario ingresar la referencia del pago en depósito.');
                }
            }elseif($metodo == 'condonacion'){
                if($descripcion == '' || $descripcion === null){
                    throw new ModelsException('Es necesario ingresar la descripción del pago.');
                }
            }

            $subtotal = $this->db->select('SUM( vendido * ( precio - ( ( precio * descuento ) / 100 ) ) ) AS subtotal', 'credito_detalle', null, "id_credito = '".$id_credito."'");
            $subtotal = (real) $subtotal[0]['subtotal'];

            $existen_pagos = $this->db->select('*', 'credito_pagos', null, "id_credito = '$id_credito'");
            if($existen_pagos){
                $pagos = $this->db->select("SUM(monto) AS pagos", "credito_pagos", null, "id_credito='$id_credito'");
                $pagos = (real) $pagos[0]['pagos'];
            }else{
                $pagos = 0;
            }

            $resta = $subtotal - $pagos;

            if($monto > $resta){
                throw new ModelsException('El saldo del crédito es de $ '.number_format($resta,2).'.');
            }

            $this->db->insert('credito_pagos', array(
                'id_credito' => $id_credito,
                'tipo' => $metodo,
                'monto' => $monto,
                'referencia' => $referencia,
                'descripcion' => $descripcion,
                'fecha' => $fecha,
                'hora' => $hora,
                'usuario' => $this->id_user
            ));

            if($monto == $resta){
                $this->db->update('credito', array(
                    'estado' => 3
                ), "id_credito='$id_credito'", 1);
            }
            
            if($metodo == 'efectivo'){
                $this->db->insert('caja_movimientos', array(
                    'id_caja' => $id_caja,
                    'tipo' => 'Abono',
                    'concepto' => 'Crédito',
                    'referencia' => $credito['folio'],
                    'descripcion' => 'Abono por concepto de crédito: '.$credito['folio'],
                    'monto' => $monto,
                    'usuario' => $this->id_user,
                    'hora' => $hora,
                ));
            }
            
            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El abono al crédito se realizó correctamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage()); 
        }
    }

    public function cargarSalidas(){
        global $http;
        $id_credito = intval($http->request->get('id_credito'));

        $salidas = $this->salidas($id_credito);

        $data = [];
        if($salidas){

            foreach ($salidas as $key => $value) {

                $infoData = [];

                $id_producto = $value['id_producto'];
                $producto = (new Model\Productos)->productoNi($id_producto);

                $infoData[] = $value['fecha'];

                $fecha = Helper\Functions::fecha($value['fecha'], false);
                $infoData[] = $fecha;           

                $infoData[] = "<span class='badge bg-purple'>".$producto['codigo']."</span> <strong>".$producto['producto']."</strong> ".$producto['leyenda'];

                $infoData[] = $value['cantidad'];

                $hora = strtotime($value['hora']); 

                $infoData[] = date('h:i A', $hora);

                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    public function cargarDevoluciones(){
        global $http;
        $id_credito = intval($http->request->get('id_credito'));

        $devoluciones = $this->devoluciones($id_credito);

        $data = [];
        if($devoluciones){

            foreach ($devoluciones as $key => $value) {

                $infoData = [];

                $id_producto = $value['id_producto'];
                $producto = (new Model\Productos)->productoNi($id_producto);

                $infoData[] = $value['fecha'];

                $fecha = Helper\Functions::fecha($value['fecha'], false);
                $infoData[] = $fecha;           

                $infoData[] = "<span class='badge bg-purple'>".$producto['codigo']."</span> <strong>".$producto['producto']."</strong> ".$producto['leyenda'];

                $infoData[] = $value['cantidad'];

                $hora = strtotime($value['hora']); 

                $infoData[] = date('h:i A', $hora);

                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    public function cargarPagos(){
        global $http;
        $id_credito = intval($http->request->get('id_credito'));

        $pagos = $this->pagos($id_credito);

        $data = [];
        if($pagos){

            foreach ($pagos as $key => $value) {

                $infoData = [];

                $infoData[] = $value['fecha'];

                $fecha = Helper\Functions::fecha($value['fecha'], false);
                $infoData[] = $fecha;      
                
                $hora = strtotime($value['hora']); 
                $infoData[] = date('h:i A', $hora);

                $infoData[] = '$ '.number_format($value['monto'],2);

                if($value['tipo'] == 'efectivo'){
                    $mp = 'Efectivo';
                }elseif($value['tipo'] == 'deposito'){
                    $mp = 'Depósito <i class="fas fa-question-circle" data-toggle="tooltip" title="" data-original-title="Referencia: '.$value['referencia'].'"></i>';
                }else{
                    $mp = 'Condonación <i class="fas fa-question-circle" data-toggle="tooltip" title="" data-original-title="Descripción: '.$value['descripcion'].'"></i>';
                }
                $infoData[] = $mp;

                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    public function imprimirNota($id_credito, $tipo, $fecha){

        global $config;
        $urlMultimedia = $config['build']['urlAssetsPagina'];

        # OBTENER DATOS DE PLANTILLA
        $datosPlantilla = (new Model\Comercio)->datosPlantilla();

        # ESCAPAR VARIABLES
        $id_credito = intval($id_credito);
        $fecha = $this->db->scape($fecha);

        # OBTENER DATOS DEL CRÉDITO
        $credito = $this->credito($id_credito);
        $almacen = (new Model\Almacenes)->almacen($credito['id_almacen']);
        $aperturo = (new Model\Administradores)->administrador($credito['usuario']);
        $cliente = (new Model\Clientes)->cliente($credito['id_cliente']);

        # SI EL TIPO DE NOTA ES -------------------- "SALIDAS"
        if($tipo == 'salidas'){

            # CONSULTAR QUE EXISTAN SALIDAS REGISTRADAS AL CRÉDITO POR FECHA
            $salidas = $this->db->select('*', 'credito_historial', null, "id_credito='$id_credito' AND fecha='$fecha' AND tipo='salida'", null, "ORDER BY id_hc DESC");

            # SI HAY SALIDAS CON LA FECHA SELECCIONADA
            if($salidas){
                
                $page_size = 110;
                $item_size = 12;
                $items = count($salidas);
                $height = $page_size + ($item_size*$items);
        
                $fecha_formato = Helper\Functions::fecha($fecha, false);

                $html = '';

                $html .= '
                <page backtop="76x'.$height.'" backtop="5mm" backbottom="5mm" backleft="6mm" backright="6mm" style="font-family: Helvetica, sans-serif;">
                
                    <div style="padding:0; text-align:center;">
                        <b style="font-size:14px;">COMPROBANTE DE SALIDAS</b><br><br>
                        <p style="font-size:12px;">
                            '.$fecha_formato.'<br>
                            Crédito: '.$credito['folio'].'<br>
                            Cliente: '.$cliente['cliente'].'
                            
                        </p>

                    </div>
                    <br>';
                    
                    $html .= '
                    <table style="font-size:11px; width: 100%;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width: 25%; max-width:25%; text-align:left; padding-bottom:3px;">Cant.</td>
                            <td style="width: 75%; max-width:75%; text-align:left; padding-bottom:3px;" colspan="2">DESCRIPCIÓN</td>
                        </tr>
    
                        <tr>
                            <td style="text-align:left; padding-bottom:15px;">
                                Precio
                            </td>
                            <td style="text-align:left; padding-bottom:15px;">
                                Dto.
                            </td>
                            <td style="width: 30%; max-width:30%; text-align:right; padding-bottom:15px;">
                                Importe
                            </td>
                        </tr>';
                    $sumaCantidad = 0; 
                    $total = 0;
                    
                    foreach ($salidas as $key => $value) {
                        $id_producto = $value['id_producto'];
                        $p = (new Model\Productos)->productoNi($value['id_producto']);

                        $producto = $p['id'].' - '.$p['producto'].' '.$p['leyenda'];
                        
                        $sql = $this->db->select('descuento, precio', 'credito_detalle', null, "id_credito='$id_credito' AND id_producto='$id_producto'");
                        
                        $cantidad = (int) $value['cantidad'];
                        $descuento = (real) $sql[0]['descuento'];
                        $precio = (real) $sql[0]['precio'];
                        
                        if($descuento != 0){

                            $aplicarDescuento = ($precio * $descuento) / 100;
                            $precioDescuento = $precio - $aplicarDescuento;
                            $totalD = '($' .number_format($precioDescuento ,2). ')';
                            $subtotal = $cantidad * $precioDescuento;

                        }else{

                            $totalD = '';
                            $subtotal = $cantidad * $precio;

                        }
                        $total += $subtotal;
                        $sumaCantidad += $cantidad;
                        $sumaPuntos += $value['puntos'];

                        if(strlen($producto) > 20){
                            $producto = trim(substr($producto, 0, 20));
                        }
                        if($descuento > 0){
                            $desc_txt = '-'.$descuento.'% '.$totalD;
                        }else{
                            $desc_txt = '';
                        }

                        $html .= '
                        <tr>
                            <td style="text-align:left; padding-bottom:3px;">
                                '.$cantidad.'
                            </td>
                            <td style="text-align:left; padding-bottom:3px;" colspan="2">
                                '.$producto.'
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:left; padding-bottom:4px;">
                                '.number_format($precio,2).'
                            </td>
                            <td style="padding-bottom:4px;">
                                '.$desc_txt.'
                            </td>
                            <td style="text-align:right; padding-bottom:4px;">
                                '.number_format($subtotal,2).'
                            </td>
                        </tr>';
                    }
                    $html .= '
                    </table>
                    <table style="font-size:11px; width: 100%; margin-top:-15px;" border="0" cellspacing="0" cellpadding="0">';
                    
                    $html .= '
                    <tr>
                        <td style="width:20%; text-align:left; padding-top:30px;"><strong>'.$sumaCantidad.'</strong></td>
                        <td style="width:50%; text-align:right; padding-top:30px;" colspan="2">Total:</td>
                        <td style="width:30%; text-align:right; padding-top:30px;"><strong>'.number_format($total,2).'</strong></td>
                    </tr>';
                    
                    $html .= '    
                    </table>';

                $html .= '
                </page>';

                echo $html;

            # CONSULTAR TODAS LAS SALIDAS REGISTRADAS AL CRÉDITO
            }else{

                $salidasTodas = $this->db->select('*', 'credito_historial', null, "id_credito='$id_credito' AND tipo='salida'", null, "ORDER BY id_hc DESC");

                if($salidasTodas){

                    $html = '';

                    $html .= '
                    <page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif;">
                        <table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; padding-bottom:20px; color:#000; font-size:10px;">

                                    <b style="font-size:14px; color:red;">REGISTRO DE SALIDAS</b> <br><br><br><br>
                                    <b>Cliente:</b> '.$cliente['cliente'].'<br>

                                </td>
                                <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; text-align:right; color:'.$datosPlantilla['colorPlantilla'].'; vertical-align:middle;">
                                    FOLIO DE CRÉDITO<br><br>
                                    <barcode dimension="1D" type="C128" value="'.$credito['folio'].'" label="label" style="width:80%; height:6mm; font-size: 12px;"></barcode>
                                </td>
                            </tr>
                        </table>
                        <br>';

                        $html .= '
                        <table style="width:100%; border:none; font-size:10px;" border="0" cellspacing="0" cellpadding="0">
                            <tr style="background:#CCC; text-transform:uppercase; color:#000; text-align:center;">
                                <th colspan="7" style="padding:7px 0;">SALIDAS REGISTRADAS</th>
                            </tr>
                            <tr style="background:#eee; text-transform:uppercase;">
                                <th style="width:27%; padding-left:5px;">
                                    Fecha
                                </th>
                                <th style="width:6%; padding:7px 0; text-align:center; padding-right:5px;">
                                    Cant.
                                </th>
                                <th style="width:34%;">
                                    Descripción
                                </th>
                                <th style="width:9%; text-align:center; padding-right:5px;">
                                    Precio
                                </th>
                                <th style="width:5%; text-align:center;">
                                    Dto.
                                </th>
                                <th style="width:9%; text-align:center; padding-right:5px;">
                                    P.Dto.
                                </th>
                                <th style="width:10%; text-align:right; padding-right:5px;">
                                    Importe
                                </th>
                            </tr>';

                        $sumaCantidad = 0; 
                        $total = 0;

                        $last = $salidasTodas[0]['fecha'];

                        foreach ($salidasTodas as $key => $value) {
                        
                            $id_producto = $value['id_producto'];
                            $producto = (new Model\Productos)->productoNi($id_producto);
                            $nombreP = $producto['producto'].' '.$producto['leyenda'];
                            if(strlen($nombreP) > 40){
                                $nombreP = trim(substr($nombreP, 0, 40));
                                $nombreP .= '...';
                            }

                            $codigo = $producto['codigo']; 
    
                            $cantidad = (int) $value['cantidad'];
    
                            $sql = $this->db->select('descuento, precio', 'credito_detalle', null, "id_credito='$id_credito' AND id_producto='$id_producto'");
                            $descuento = (real) $sql[0]['descuento'];
                            $precio = (real) $sql[0]['precio'];
    
                            $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
                            $editorial = $editorial['editorial'];
    
                            $arrayAutores = explode(",", $producto["autores"]);
                            $autores = "";
                            foreach($arrayAutores as $key1 => $value2){
                                $autor = (new Model\Autores)->autor($value2);
                                $autores .= $autor['autor'].', ';
                            }
                            $autores = substr($autores, 0, -2); 

                            $editorialAutor = $editorial.' - '.$autores;
                            if(strlen($editorialAutor) > 40){
                                $editorialAutor = substr($editorialAutor, 0, 40).'...';
                            }
    
                            if($descuento != 0){
                                $precioDescuento = $precio - (($precio * $descuento) / 100);
                                $subtotal = $cantidad * $precioDescuento;
                            }else{
                                $precioDescuento = $precio;
                                $subtotal = $cantidad * $precio;
                            }
                            
                            $sumaCantidad = $sumaCantidad + $cantidad;
                            $total = $total + $subtotal;
                            
                            if($last != $value['fecha']){
                                $background = "#f5f5f5";
                            }else{
                                $background = "#fff";
                            }

                            $last = $value['fecha'];
    
                            $html .= '
                            <tr style="background:'.$background .'">
                                <td style="border-bottom:1px solid #eee; padding:15px 0; padding-left:5px;">
                                    '.Helper\Functions::fecha($value['fecha'], false).'
                                </td>
                                <td style="border-bottom:1px solid #eee;text-align:center; padding:15px 0; padding-right:5px;">
                                    <b>'.$cantidad.'</b>
                                </td>
                                <td style="border-bottom:1px solid #eee;">
                                    '.$codigo.' (ID: '.$id_producto.')
                                    <br><b>'.$nombreP.'</b>
                                    <br>'.$editorialAutor.'
                                </td>
                                <td style="border-bottom:1px solid #eee; text-align:center;">
                                    $ '.number_format($precio,2).'
                                </td>
                                <td style="border-bottom:1px solid #eee; text-align:center; font-weight:bold;">
                                    '.$descuento.' %
                                </td>
                                <td style="border-bottom:1px solid #eee; text-align:center; font-weight:bold;">
                                    $ '.number_format($precioDescuento,2).'
                                </td>
                                <td style="border-bottom:1px solid #eee;text-align:right; padding-right:5px; font-weight:bold;">
                                    $ '.number_format($subtotal,2).'
                                </td>
                            </tr>';
                        }

                        $plural = ($sumaCantidad == 1) ? '' : 's';

                        $html .= '
                        <tr style="text-transform:uppercase;">
                            <th></th>
                            <th style="text-align:center; padding:10px 0; padding-right:5px;">
                                '.$sumaCantidad.'
                            </th>
                            <td>
                                ARTÍCULO'.$plural.'
                            </td>
                            <td></td>
                            <td></td>
                            <th style="text-align:right; padding:2px 0;">
                                TOTAL:
                            </th>
                            <th style="text-align:right; padding:2px 0; padding-right:5px; color:red;">
                                $ '.number_format($total,2).'
                            </th>
                        </tr>';
                        
                        $html .= '
                        </table>';

                    $html .= '
                    </page>';
    
                    echo $html;
                
                }

            }

        # SI EL TIPO DE NOTA ES -------------------- "DEVOLUCIONES"
        }elseif($tipo == 'devoluciones'){

            # CONSULTAR QUE EXISTAN DEVOLUCIONES REGISTRADAS AL CRÉDITO POR FECHA
            $devoluciones = $this->db->select('*', 'credito_historial', null, "id_credito='$id_credito' AND fecha='$fecha' AND tipo='devolucion'", null, "ORDER BY id_hc DESC");
            # SI HAY DEVOLUCIONES
            if($devoluciones){

                $fecha_formato = Helper\Functions::fecha($fecha, false);

                $html = '';

                $html .= '
                <page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif;">
                    <table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; padding-bottom:20px; color:#000; font-size:10px;">

                                <b style="font-size:14px; color:red;">COMPROBANTE DE DEVOLUCIONES</b> <br><br>
                                <b style="font-size:14px;">'.$fecha_formato.'</b> <br><br>
                                <b>Cliente:</b> '.$cliente['cliente'].'<br>

                            </td>
                            <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; text-align:right; color:'.$datosPlantilla['colorPlantilla'].'; vertical-align:middle;">
                                FOLIO DE CRÉDITO<br><br>
                                <barcode dimension="1D" type="C128" value="'.$credito['folio'].'" label="label" style="width:80%; height:6mm; font-size: 12px;"></barcode>
                            </td>
                        </tr>
                    </table>
                    <br>';

                    $html .= '
                    <table style="width:100%; border:none; font-size:10px;" border="0" cellspacing="0" cellpadding="0">
                        <tr style="background:#CCC; text-transform:uppercase; color:#000; text-align:center;">
                            <th colspan="6" style="padding:7px 0;">DEVOLUCIONES REGISTRADAS</th>
                        </tr>
                        <tr style="background:#eee; text-transform:uppercase;">
                            <th style="width:7%; padding:7px 0; text-align:center; padding-right:5px;">
                                Cant.
                            </th>
                            <th style="width:60%;">
                                Descripción
                            </th>
                            <th style="width:9%; text-align:center;">
                                Precio
                            </th>
                            <th style="width:5%; text-align:center;">
                                Dto.
                            </th>
                            <th style="width:9%; text-align:center;">
                                P.Dto.
                            </th>
                            <th style="width:10%; text-align:right; padding-right:5px;">
                                Importe
                            </th>
                        </tr>';

                    $sumaCantidad = 0; 
                    $total = 0;

                    foreach ($devoluciones as $key => $value) {
                        
                        $id_producto = $value['id_producto'];
                        $producto = (new Model\Productos)->productoNi($id_producto);
                        $nombreP = $producto['producto'].' '.$producto['leyenda'];
                        if(strlen($nombreP) > 40){
                            $nombreP = trim(substr($nombreP, 0, 40));
                            $nombreP .= '...';
                        }

                        $codigo = $producto['codigo']; 

                        $cantidad = (int) $value['cantidad'];

                        $sql = $this->db->select('descuento, precio', 'credito_detalle', null, "id_credito='$id_credito' AND id_producto='$id_producto'");
                        $descuento = (real) $sql[0]['descuento'];
                        $precio = (real) $sql[0]['precio'];

                        $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
                        $editorial = $editorial['editorial'];

                        $arrayAutores = explode(",", $producto["autores"]);
                        $autores = "";
                        foreach($arrayAutores as $key1 => $value2){
                            $autor = (new Model\Autores)->autor($value2);
                            $autores .= $autor['autor'].', ';
                        }
                        $autores = substr($autores, 0, -2); 

                        $editorialAutor = $editorial.' - '.$autores;
                        if(strlen($editorialAutor) > 40){
                            $editorialAutor = substr($editorialAutor, 0, 40).'...';
                        }                        

                        if($descuento != 0){
                            $precioDescuento = $precio - (($precio * $descuento) / 100);
                            $subtotal = $cantidad * $precioDescuento;
                        }else{
                            $precioDescuento = $precio;
                            $subtotal = $cantidad * $precio;
                        }
                        
                        $sumaCantidad = $sumaCantidad + $cantidad;
                        $total = $total + $subtotal;
                        

                        $html .= '
                        <tr>
                            <td style="border-bottom:1px solid #eee;text-align:center; padding:15px 0; padding-right:5px;">
                                <b>'.$cantidad.'</b>
                            </td>
                            <td style="border-bottom:1px solid #eee;">
                                '.$codigo.' (ID: '.$id_producto.') <b>'.$nombreP.'</b>
                                <br>'.$editorialAutor.'
                            </td>
                            <td style="border-bottom:1px solid #eee; text-align:center;">
                                $ '.number_format($precio,2).'
                            </td>
                            <td style="border-bottom:1px solid #eee; text-align:center;">
                                '.$descuento.' %
                            </td>
                            <td style="border-bottom:1px solid #eee; text-align:center;">
                                <b>$ '.number_format($precioDescuento,2).'</b>
                            </td>
                            <td style="border-bottom:1px solid #eee;text-align:right; padding-right:5px;">
                                <b>$ '.number_format($subtotal,2).'</b>
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
                        <td></td>
                        <td></td>
                        <th style="text-align:right; padding:2px 0;">
                            TOTAL:
                        </th>
                        <th style="text-align:right; padding:2px 0; padding-right:5px; color:red;">
                            $ '.number_format($total,2).'
                        </th>
                    </tr>';

                    $html .= '
                    </table>';
                
                $html .= '
                </page>';

                echo $html;

            }else{

                $devolucionesTodas = $this->db->select('*', 'credito_historial', null, "id_credito='$id_credito' AND tipo='devolucion'", null, "ORDER BY id_hc DESC");

                if($devolucionesTodas){

                    $html = '';

                    $html .= '
                    <page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif;">
                        <table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; padding-bottom:20px; color:#000; font-size:10px;">

                                    <b style="font-size:14px; color:red;">REGISTRO DE DEVOLUCIONES</b> <br><br><br><br>
                                    <b>Cliente:</b> '.$cliente['cliente'].'<br>

                                </td>
                                <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; text-align:right; color:'.$datosPlantilla['colorPlantilla'].'; vertical-align:middle;">
                                    FOLIO DE CRÉDITO<br><br>
                                    <barcode dimension="1D" type="C128" value="'.$credito['folio'].'" label="label" style="width:80%; height:6mm; font-size: 12px;"></barcode>
                                </td>
                            </tr>
                        </table>
                        <br>';
                        
                        $html .= '
                        <table style="width:100%; border:none; font-size:10px;" border="0" cellspacing="0" cellpadding="0">
                            <tr style="background:#CCC; text-transform:uppercase; color:#000; text-align:center;">
                                <th colspan="7" style="padding:7px 0;">DEVOLUCIONES REGISTRADAS</th>
                            </tr>
                            <tr style="background:#eee; text-transform:uppercase;">
                                <th style="width:27%; padding-left:5px;">
                                    Fecha
                                </th>
                                <th style="width:6%; padding:7px 0; text-align:center; padding-right:5px;">
                                    Cant.
                                </th>
                                <th style="width:34%;">
                                    Descripción
                                </th>
                                <th style="width:9%; text-align:center; padding-right:5px;">
                                    Precio
                                </th>
                                <th style="width:5%; text-align:center;">
                                    Dto.
                                </th>
                                <th style="width:9%; text-align:center; padding-right:5px;">
                                    P.Dto.
                                </th>
                                <th style="width:10%; text-align:right; padding-right:5px;">
                                    Importe
                                </th>
                            </tr>';


                        $sumaCantidad = 0; 
                        $total = 0;

                        $last = $devolucionesTodas[0]['fecha'];

                        foreach ($devolucionesTodas as $key => $value) {
                        
                            $id_producto = $value['id_producto'];
                            $producto = (new Model\Productos)->productoNi($id_producto);
                            $nombreP = $producto['producto'].' '.$producto['leyenda'];
                            if(strlen($nombreP) > 40){
                                $nombreP = trim(substr($nombreP, 0, 40));
                                $nombreP .= '...';
                            }

                            $codigo = $producto['codigo']; 
    
                            $cantidad = (int) $value['cantidad'];
    
                            $sql = $this->db->select('descuento, precio', 'credito_detalle', null, "id_credito='$id_credito' AND id_producto='$id_producto'");
                            $descuento = (real) $sql[0]['descuento'];
                            $precio = (real) $sql[0]['precio'];
    
                            $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
                            $editorial = $editorial['editorial'];
    
                            $arrayAutores = explode(",", $producto["autores"]);
                            $autores = "";
                            foreach($arrayAutores as $key1 => $value2){
                                $autor = (new Model\Autores)->autor($value2);
                                $autores .= $autor['autor'].', ';
                            }
                            $autores = substr($autores, 0, -2); 

                            $editorialAutor = $editorial.' - '.$autores;
                            if(strlen($editorialAutor) > 40){
                                $editorialAutor = substr($editorialAutor, 0, 40).'...';
                            }
    
                            if($descuento != 0){
                                $precioDescuento = $precio - (($precio * $descuento) / 100);
                                $subtotal = $cantidad * $precioDescuento;
                            }else{
                                $precioDescuento = $precio;
                                $subtotal = $cantidad * $precio;
                            }
                            
                            $sumaCantidad = $sumaCantidad + $cantidad;
                            $total = $total + $subtotal;
                            
                            
                            if($last != $value['fecha']){
                                $background = "#f5f5f5";
                            }else{
                                $background = "#fff";
                            }

                            $last = $value['fecha'];
    
                            $html .= '
                            <tr style="background:'.$background .'">
                                <td style="border-bottom:1px solid #eee; padding:15px 0; padding-left:5px;">
                                    '.Helper\Functions::fecha($value['fecha'], false).'
                                </td>
                                <td style="border-bottom:1px solid #eee;text-align:center; padding:15px 0; padding-right:5px;">
                                    <b>'.$cantidad.'</b>
                                </td>
                                <td style="border-bottom:1px solid #eee;">
                                    '.$codigo.' (ID: '.$id_producto.') 
                                    <br><b>'.$nombreP.'</b>
                                    <br>'.$editorialAutor.'
                                </td>
                                <td style="border-bottom:1px solid #eee; text-align:center;">
                                    $ '.number_format($precio,2).'
                                </td>
                                <td style="border-bottom:1px solid #eee; text-align:center;">
                                    '.$descuento.' %
                                </td>
                                <td style="border-bottom:1px solid #eee; text-align:center; font-weight:bold;">
                                    $ '.number_format($precioDescuento,2).'
                                </td>
                                <td style="border-bottom:1px solid #eee;text-align:right; padding-right:5px; font-weight:bold;">
                                    $ '.number_format($subtotal,2).'
                                </td>
                            </tr>';
                        }

                        $plural = ($sumaCantidad == 1) ? '' : 's';

                        $html .= '
                        <tr style="text-transform:uppercase;">
                            <th></th>
                            <th style="text-align:center; padding:10px 0; padding-right:5px;">
                                '.$sumaCantidad.'
                            </th>
                            <td>
                                ARTÍCULO'.$plural.'
                            </td>
                            <td></td>
                            <td></td>
                            <th style="text-align:right; padding:2px 0;">
                                TOTAL:
                            </th>
                            <th style="text-align:right; padding:2px 0; padding-right:5px; color:red;">
                                $ '.number_format($total,2).'
                            </th>
                        </tr>';
                        
                        $html .= '
                        </table>';

                    $html .= '
                    </page>';
    
                    echo $html;
                    
                }else{
                    echo '<page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif; text-align:center;">
                            No hay registró de devoluciones para el crédito <b>'.$credito['folio'].'</b>
                        </page>';
                }
            }
        # SI EL TIPO DE NOTA ES -------------------- "PAGOS"
        }elseif($tipo == 'pagos'){
            # CONSULTAR QUE EXISTAN PAGOS REGISTRADOS AL CRÉDITO POR FECHA
            $pagos = $this->db->select('*', 'credito_pagos', null, "id_credito='$id_credito' AND fecha='$fecha'", null, "ORDER BY id_cp DESC");
            # SI HAY PAGOS
            if($pagos){
                $fecha_formato = Helper\Functions::fecha($fecha, false);

                $page_size = 100;
                $item_size = 9;
                $items = count($pagos);

                $height = $page_size + ($item_size*$items);

                $html = '';

                $html .= '
                <page format="76x'.$height.'" backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm" style="font-family: Helvetica, sans-serif;">
                    <div style="padding:0;">

                        <h4 style="text-align:center; font-weight:bold; margin-top:0;">
                            COMPROBANTE DE PAGO
                        </h4>
                        
                        <table style="font-size:10px; width: 100%;" border="0" cellspacing="0" cellpadding="0">

                            <tr>
                                <td style="text-align:center; font-weight:bold; padding-bottom:10px;">'.$fecha_formato.'</td>
                            </tr>
                            <tr>
                                <td style="width:100%; text-align:center;">Cliente: '.$cliente['cliente'].'</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">Crédito: '.$credito['folio'].'</td>
                            </tr>
                            
                        </table><br>';

                        $html .= '
                        <table style="font-size:9px; width: 100%;" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th style="width:17%; padding-bottom:5px; border-bottom:1px solid #000;">
                                    HORA
                                </th>
                                <th style="width:58%; border-bottom:1px solid #000;">
                                    MÉTODO DE PAGO
                                </th>
                                <th style="width:25%; text-align:right; border-bottom:1px solid #000;">
                                    MONTO
                                </th>
                            </tr>';
                        
                        $total = 0;

                        foreach ($pagos as $key => $value) {

                            $hora = strtotime($value['hora']); 
                            $hora = date('h:i A', $hora);

                            $monto = (real) $value['monto'];
                            $total = $total + $monto;
                            
                            if($value['tipo'] == 'efectivo'){
                                $mp = 'Efectivo';
                            }elseif($value['tipo'] == 'deposito'){
                                $mp = 'Depósito <small>('.$value['referencia'].')</small>';
                            }else{
                                $mp = 'Condonación';
                            }

                            $html .= '
                            <tr>
                                <td style="padding: 5px 0;">'.$hora.'</td>
                                <td style="padding: 5px 0;">'.$mp.'</td>
                                <td style="text-align: right; padding: 5px 0;">$ '.number_format($monto,2).'</td>
                            </tr>';

                        }

                        $html .= '
                            <tr>
                                <th style="padding: 5px 0; border-top:1px dotted #000;"></th>
                                <th style="padding: 5px 0; border-top:1px dotted #000; text-align:right;">TOTAL:</th>
                                <th style="padding: 5px 0; border-top:1px dotted #000; text-align:right;">$ '.number_format($total,2).'</th>
                            </tr>';

                        $html .= '
                        </table>';

                $html .= '
                    </div>
                </page>';

                echo $html;
                
            }else{

                $pagosTodos = $this->db->select('*', 'credito_pagos', null, "id_credito='$id_credito'", null, "ORDER BY id_cp DESC");

                if($pagosTodos){
                    $page_size = 100;
                    $item_size = 9;
                    $items = count($pagosTodos);

                    $height = $page_size + ($item_size*$items);

                    $html = '';

                    $html .= '
                    <page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm" style="font-family: Helvetica, sans-serif;">
                        <div style="padding:0;">

                            <h4 style="text-align:center; font-weight:bold; margin-top:0;">
                                HISTORIAL DE PAGOS
                            </h4>
                            <h5 style="text-align:center; font-weight:bold; margin-top:0;">
                                '.$credito['folio'].'
                            </h5>
                            
                            <table style="font-size:10px; width: 100%;" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:100%; text-align:center;">Cliente: '.$cliente['cliente'].'</td>
                                </tr>
                                
                            </table><br>';

                        $html .= '
                        <table style="font-size:9px; width: 100%;" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th style="width:17%; padding-bottom:5px; border-bottom:1px solid #000;">
                                    HORA
                                </th>
                                <th style="width:58%; border-bottom:1px solid #000;">
                                    MÉTODO DE PAGO
                                </th>
                                <th style="width:25%; text-align:right; border-bottom:1px solid #000;">
                                    MONTO
                                </th>
                            </tr>';

                        $fechas_pagos = $this->db->select("DISTINCT fecha", "credito_pagos", null, "id_credito='$id_credito'", null, "ORDER BY id_cp DESC");

                        $total = 0;

                        foreach($fechas_pagos as $key => $value){
                            $html .= '
                            <tr>
                                <td style="text-align: center; padding: 5px 0; padding-top:10px;" colspan="3">'
                                    .Helper\Functions::fecha($value['fecha'], false).'
                                </td>
                            </tr>';
                            foreach($pagosTodos as $key2 => $value2){
                                if($value['fecha'] == $value2['fecha']){

                                    $monto = (real) $value2['monto'];
                                    $total = $total + $monto;

                                    $hora = strtotime($value2['hora']); 
                                    $hora = date('h:i A', $hora);

                                    if($value2['tipo'] == 'efectivo'){
                                        $mp = 'Efectivo';
                                    }elseif($value2['tipo'] == 'deposito'){
                                        $mp = 'Depósito <small>('.$value2['referencia'].')</small>';
                                    }else{
                                        $mp = 'Condonación';
                                    }

                                    $html .= '
                                    <tr>
                                        <td style="padding: 5px 0; border-bottom:1px dotted #000;">'.$hora.'</td>
                                        <td style="padding: 5px 0; border-bottom:1px dotted #000;">'.$mp.'</td>
                                        <td style="text-align: right; border-bottom:1px dotted #000; padding: 5px 0;">$ '.number_format($monto,2).'</td>
                                    </tr>';
                                }
                            }
                        }

                        $html .= '
                            <tr>
                                <th style="padding: 5px 0;"></th>
                                <th style="padding: 5px 0; text-align:right;">TOTAL:</th>
                                <th style="padding: 5px 0; text-align:right;">$ '.number_format($total,2).'</th>
                            </tr>';
                        
                        $html .= '
                        </table>';

                        if($credito['estado'] == 3){
                            $html .= '
                            <h4 style="text-align:center; font-weight:bold;">
                                * * * PAGADO * * *
                            </h4>';
                        }

                    $html .= '
                        </div>
                    </page>';

                    echo $html;
                }else{
                    echo '<page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif; text-align:center;">
                            No hay registró de pagos para el crédito <b>'.$credito['folio'].'</b>
                        </page>';
                }

            }
        # MOSTRAR EL CRÉDITO EN GENERAL
        }else{
            
            $datosPlantilla = (new Model\Comercio)->datosPlantilla();                       # Obtener datos de la plantilla
            $almacen = (new Model\Almacenes)->almacen($credito['id_almacen']);              # Obtener el ALMACÉN donde se relizó el crédito
            $cliente = (new Model\Clientes)->cliente($credito['id_cliente']);               # Obtener el CLIENTE del crédito 

            $aperturo = (new Model\Administradores)->administrador($credito['usuario']);    # Obtener el USUARIO que APERTURO el crédito 

            $creditoDetalle = $this->creditoDetalle('id_credito',$id_credito);              # Obtener LISTA DE PRODCUTOS en el crédito

            // CREAR PLANTILLA DEL PDF
            $html = '
            <page backtop="0mm" backbottom="0mm" backleft="0mm" backright="0mm" style="font-family: Helvetica, sans-serif;">
                <div style="padding:0; margin:0;">
                    <table cellspacing="0" border="0" style="margin-top:10mm;">
                        <tr>
                            <td style="width:40%; background: #0059c4; padding: 0;">
                                <div style="background: none; padding-left: 12.7mm; font-family: Helvetica, Arial, sans-serif; margin: auto 0; color:#fff; font-size:20px; font-weight:bold;">
                                    El timón Librería
                                </div>
                            </td>
                            <td style="width:60%; background: #FFF; padding: 0;">
                                <div style="background: none; padding-right: 12.7mm; font-family: Helvetica, Arial, sans-serif; margin:0; margin-top:15px; color:#0059c4; font-size:30px; font-weight:100; text-align:right;">
                                    F O L I O  &nbsp;&nbsp;D E&nbsp;&nbsp; C R É D I T O
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:40%; background: #ebebeb; padding: 0;">
                                <div style="background: none; padding-left: 12.7mm; font-family: Helvetica, Arial, sans-serif; margin: auto 0;">
                                    <b style="color: #0059c4; font-size:12px;">Cliente:</b><br>
                                    <b style="color: #212121; font-size:12px;">'.$cliente['cliente'].'</b>
                                </div>
                            </td>
                            <td style="width:60%; background: #FFF; padding: 0;">
                                <div style="background: none; padding-right: 12.7mm; font-family: Helvetica, Arial, sans-serif; margin:0; margin-top:0px; font-size:30px; font-weight:100; text-align:right;">
                                    <barcode dimension="1D" type="C128" value="'.$credito['folio'].'" label="label" style="width:91%; height:8mm; font-size: 14px; color:#212121;"></barcode><br>
                                </div>
                            </td>
                        </tr>
                    
                    </table>
                    <br>
                    <table cellspacing="0" border="0" style="width:100%; color:#212121; margin:0; padding:0;">
                        <tr style="font-size:10px;">
                            <th style="background: #ebebeb; border-bottom: 2px solid #212121; width:11%; padding:10px 0; padding-left: 12.7mm; text-align:center;">S</th>
                            <th style="background: #ebebeb; border-bottom: 2px solid #212121; width:5%; padding:10px 0; text-align:center;">D</th>
                            <th style="background: #ebebeb; border-bottom: 2px solid #212121; width:5%; padding:10px 0; text-align:center;">V</th>
                            <th style="background: #ebebeb; border-bottom: 2px solid #212121; width:43%; padding:10px 0;">DESCRIPCIÓN</th>
                            <th style="background: #ebebeb; border-bottom: 2px solid #212121; width:8%; padding:10px 0; text-align:center;">PRECIO</th>
                            <th style="background: #ebebeb; border-bottom: 2px solid #212121; width:5%; padding:10px 0; text-align:center;">DTO.</th>
                            <th style="background: #ebebeb; border-bottom: 2px solid #212121; width:8%; padding:10px 0; text-align:center;">P.DTO.</th>
                            <th style="background: #ebebeb; border-bottom: 2px solid #212121; width:15%; padding:10px 0; text-align:right; padding-right: 12.7mm;">IMPORTE</th>
                        </tr>';

                        // INICIALIZAR VARIABLES DE CONTEO DE CANTIDADES PARA SALIDAS, DEVOLUCIONES, VENTA REAL Y TOTAL
                        $sumaS = 0;                                 # Suma salidas
                        $sumaD = 0;                                 # Suma devoluciones
                        $sumaV = 0;                                 # Suma ventas reales
                        $total = 0;                                 # Total

                        // COMODÍN PARA ALTERNAR EL COLOR DE LAS FILAS
                        $cont = 0;

                        // VARIABLES PARA EL CONTROL DEL TAMAÑO Y NÚMERO DE FILAS DEL DOCUMENTO PDF
                        $pageRows = 27;                             # 27 filas para la primer página                          
                        $totalRows = count($creditoDetalle);        # Obtener el número de productos en el crédito
                        $rows = $pageRows - $totalRows;             # Indicar el número de filas que no tendrán información (en blanco)

                        foreach ($creditoDetalle as $key => $value) {   # Recorrer LISTA DE PRODUCTOS en el crédito 

                            $s = (int) $value['cantidad'];          # Salidas
                            $d = (int) $value['devolucion'];        # Devoluciones
                            $v = (int) $value['vendido'];           # Ventas reales

                            $sumaS = $sumaS + $s;                   # Sumar salidas
                            $sumaD = $sumaD + $d;                   # Sumar devoluciones
                            $sumaV = $sumaV + $v;                   # Sumar Ventas reales

                            $precio = (real) $value['precio'];          # Precio
                            $descuento = (real) $value['descuento'];    # Descuento

                            if($descuento != 0){                        # Si hay descuento (siempre tendrá descuento)
                                $precioDescuento = $precio - (($precio * $descuento) / 100);    # Precio con descuento
                                $subtotal = $precioDescuento * $v;                              # Subtotal con descuento
                            }else{                                      # ( * siempre tendrá descuento * )
                                $precioDescuento = $precio;
                                $subtotal = $precioDescuento * $v;
                            }

                            $total = $total + $subtotal;                # Sumar subtotales al total

                            $producto = (new Model\Productos)->productoNi($value['id_producto']);     # Obtener información del producto
                            $nombreP = $producto['producto'].' '.$producto['leyenda'];                                       # Nombre del producto
                            if(strlen($nombreP) > 35){
                                $nombreP = trim(substr($nombreP, 0, 35));
                                $nombreP .= '...';
                            }

                            $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
                            $editorial = $editorial['editorial'];

                            $arrayAutores = explode(",", $producto["autores"]);
                            $autores = "";
                            foreach($arrayAutores as $key1 => $value2){
                                $autor = (new Model\Autores)->autor($value2);
                                $autores .= $autor['autor'].', ';
                            }
                            $autores = substr($autores, 0, -2); 

                            $editorialAutor = $editorial.' - '.$autores;
                            if(strlen($editorialAutor) > 40){
                                $editorialAutor = substr($editorialAutor, 0, 40).'...';
                            }

                            $colorFila = ($cont%2==0) ? '#FFF' : '#ebebeb';                         # Determinar color de fila

                            // MOSTRAR INFORMACIÓN DEL PRODUCTO
                            $html .= '
                            <tr style="font-size:10px;">
                                <td style="padding: 3px 0; padding-left: 12.7mm; text-align:center; background: '.$colorFila.';">'.$s.'</td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.';">'.$d.'</td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.';"><b>'.$v.'</b></td>
                                <td style="padding: 3px 0; background: '.$colorFila.';">
                                    '.$producto['codigo'].' (ID: '.$producto['id'].') <b> '.$nombreP.'</b><br> 
                                    '.$editorialAutor.'
                                </td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.';">$ '.number_format($precio,2).'</td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.';">'.$descuento.' %</td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.';"><b>$ '.number_format($precioDescuento,2).'</b></td>
                                <td style="padding: 3px 0; padding-right: 12.7mm; text-align:right; background: '.$colorFila.';">$ '.number_format($subtotal,2).'</td>
                            </tr>';

                            $cont++;
                        }
                        
                        // CONTROL PARA LAS FILAS EN BLANCO
                        for ($i=0; $i < $rows; $i++) {                          # Crear un loop con el total de filas en blanco
                            $colorFila = ($cont%2!=0) ? '#ebebeb' : '#FFF';     # Determinar el color de la fila
                            $html .= '
                            <tr style="font-size:10px;">
                                <td style="padding: 3px 0; padding-left: 12.7mm; text-align:center; background: '.$colorFila.'; color: '.$colorFila.';">0</td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.'; color: '.$colorFila.';">0</td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.'; color: '.$colorFila.';">0</td>
                                <td style="padding: 3px 0; background: '.$colorFila.'; color: '.$colorFila.';">
                                    0<br> 
                                    0
                                </td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.'; color: '.$colorFila.';">0</td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.'; color: '.$colorFila.';">0</td>
                                <td style="padding: 3px 0; text-align:center; background: '.$colorFila.'; color: '.$colorFila.';">0</td>
                                <td style="padding: 3px 0; padding-right: 12.7mm; text-align:right; background: '.$colorFila.'; color: '.$colorFila.';">0</td>
                            </tr>';
                            $cont++;
                        }
                        
                        // OBTENER EL TOTAL DE PAGOS ASOCIADOS AL CRÉDITO
                        $pagos = $this->db->select('SUM(monto) as pagos', "credito_pagos", null, "id_credito='".$id_credito."'");
                
                        if($pagos){                         # Si hay pagos asociados
                            $pagos = $pagos[0]['pagos'];    # Obtener l monto total de los pagos
                        }else{
                            $pagos = 0;                     # Pagos en 0
                        }

                        $resta = $total - $pagos;           # Determinar lo que se resta del crédito

                        // MOSTRAR LOS TOTALES DE CANTIDADES Y DINERO
                        $html .= '
                        <tr style="font-size:10px;">
                            <th style="padding: 6px 0; padding-left: 12.7mm; text-align:center; border-top:2px solid #212121;">'.$sumaS.'</th>
                            <th style="padding: 6px 0; text-align:center; border-top:2px solid #212121;">'.$sumaD.'</th>
                            <th style="padding: 6px 0; text-align:center; border-top:2px solid #212121;">'.$sumaV.'</th>
                            <th style="border-top:2px solid #212121;"></th>
                            <th style="border-top:2px solid #212121;"></th>
                            <th style="border-top:2px solid #212121;"></th>
                            <th style="padding: 6px 0; border-top:2px solid #212121; text-align:right; background: #ebebeb;">TOTAL:</th>
                            <th style="padding: 6px 0; border-top:2px solid #212121; padding-right: 12.7mm; background: #ebebeb; text-align:right; color:#212121;">$ '.number_format($total,2).'</th>
                        </tr>';

                        // MOSTRAR EL MONTO TOTAL DE PAGOS
                        $html .= '
                        <tr style="font-size:10px;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="padding: 6px 0; background: #ebebeb; text-align:right;">PAGOS:</th>
                            <th style="padding: 6px 0; background: #ebebeb; padding-right: 12.7mm; text-align:right; color:#212121;">$ '.number_format($pagos,2).'</th>
                        </tr>';

                        // MOSTRAR LO QUE SE RESTA DEL CRÉDITO
                        $html .= '
                        <tr style="font-size:10px;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="padding: 6px 0; background: #ebebeb; text-align:right; border-bottom:2px solid #212121;">RESTA:</th>
                            <th style="padding: 6px 0; background: #ebebeb; padding-right: 12.7mm; border-bottom:2px solid #212121; text-align:right; color:red;">$ '.number_format($resta,2).'</th>
                        </tr>';
                        
                    $html .= '
                    </table>
                </div>
            </page>';

            echo $html;

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