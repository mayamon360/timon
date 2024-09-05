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
 * Modelo VentasDeMostrador
 */
class VentasDeMostrador extends Models implements IModels {
    use DBModel;

    public function ventas() {
        $ventas = $this->db->select('*', 'salidas', null, null, null, "ORDER BY id_salida DESC");
        return $ventas;
    }
    public function venta($id) {
        $venta = $this->db->select('*', 'salida', null, "id_salida='$id'", null, "ORDER BY id_salida DESC");
        return $venta[0];
    }
    public function ventasPor($item,$valor) {
        $ventas = $this->db->select('*', 'salida', null, "$item='$valor'", null, "ORDER BY id_salida DESC");
        return $ventas;
    }
    public function ventaDetalle($item, $valor){
        $ventas = $this->db->select('*','salida_detalle', null,"$item = '$valor'");
        if($ventas){
            return $ventas;
        }
        return false;
    }
    public function ventaDetallePor($item,$valor, $item2,$valor2){
        $ventas = $this->db->select('*','salida_detalle', null,"$item = '$valor' AND $item2 = '$valor2'");
        if($ventas){
            return $ventas;
        }
        return false;
    }

    public function salidas(){
        return $this->db->select('*', 'consignacion', null, "tipo = 'salida'", null, "ORDER BY id_consignacion DESC");
    }
    public function salida($id) {
        $salida = $this->db->select('*', 'consignacion', null, "id_consignacion='$id'", null, "ORDER BY id_consignacion DESC");
        return $salida[0];
    }
    public function salidaPor($item, $valor){
        return $this->db->select('*', 'consignacion', null, "$item = '$valor' AND tipo = 'salida'", null, "ORDER BY id_consignacion DESC");
    }
    public function salidaDetalle($item, $valor){
        $salida = $this->db->select('*','consignacion_detalle', null,"$item = '$valor'");
        if($salida){
            return $salida;
        }
        return false;
    }
    
    public function imprimirTicketRapido() {
        global $http;

        // ID RECIBIDO POR GET
        $idVenta = $this->db->scape( $http->query->get('id_venta') );
        // OBTENER DATOS DE LA VENTA POR EL ID
        $venta = $this->venta($idVenta);
        if(!$venta){
            return ["status" => "error"];
        }
        // OBTENER METODO DE PAGO
        switch ($venta["metodo_pago"]) {
            case 'efectivo':
                $mp = 'Efectivo';
                break;
            case 'tarjeta':
                $mp = 'Tarjeta';
                break;
            case 'mixto':
                $mp = 'Mixto';
                break;
            case 'puntos':
                $mp = 'Puntos';
                break;
        }
        
        // OBTENER DATOS DEL COMERCIO
        $datosPlantilla = (new Model\Comercio)->datosPlantilla();
        
        // OBTENER DATOS DEL ALMACEN
        $almacen = (new Model\Almacenes)->almacen($venta['id_almacen']);
    
        // OBTENER DATOS DEL CLIENTE
        $cliente = (new Model\Clientes)->cliente($venta['id_cliente']);
        if($venta['id_cliente'] != 1){
            $cliente_txt = '<b>Cliente:</b> '.$cliente['cliente'];
        }else{
            $cliente_txt = $cliente['cliente'];
        }
        
        $datos = [
            "empresa"       => $datosPlantilla['nombre'],
            "direccion"     => $datosPlantilla['direccion'],
            "telefono"      => $datosPlantilla['telefono'],
            "fecha"         => Helper\Functions::fecha($venta['fechaVenta'].' '.$venta['horaVenta']),
            "estado"        => $venta['estado'],
            "folio"         => $venta['folio'],
            "mp"            => $mp,
            "transaccion"   => $venta['transaccion'],
            "cliente"       => $cliente_txt
        ];
    
        // OBTENER PRODUCTOS EN LA VENTA
        $productos = $this->ventaDetalle('id_salida',$idVenta);
        
        // CANTIDADES EN 0
        $total = 0;
        $sumaCantidad = 0;
        $sumaPuntos = 0;
        
        $array_productos = [];
        foreach($productos as $key => $value) {
            
            $p = (new Model\Productos)->productoNi($value['id_producto']);
            $producto = $p['id'].' '.$p['producto'].' '.$p['leyenda'];
            if(strlen($producto) > 20){
                $producto = trim(substr($producto, 0, 20));
            }
            
            $cantidad = (int) $value['vendido'];
            $descuento = (int) $value['descuento'];
            $precio = (real) $value['precio'];
            
            if($descuento != 0){
                $aplicarDescuento = ($precio * $descuento) / 100;
                $precioDescuento = $precio - $aplicarDescuento;
                $totalD = '($' .number_format($precioDescuento ,2). ')';
                $subtotal = $cantidad * $precioDescuento;
                
                $desc_txt = '-'.$descuento.'% '.$totalD;
            }else{
                $totalD = '';
                $subtotal = $cantidad * $precio;
                
                $desc_txt = '';
            }

            $array_productos[] = [
                "cantidad"  => $cantidad,
                "producto"  => $producto,
                "precio"    => number_format($precio,2),
                "desc"      => $desc_txt,
                "subtotal"  => number_format($subtotal,2)
            ];
            
            $total += $subtotal;
            $sumaCantidad += $cantidad;
            $sumaPuntos += $value['puntos'];
        }
        
        # ACTUALIZAR VENTA
        $this->db->update('salida', array(
            'impresion' => 1
        ), "id_salida='".$venta['id_salida']."'");
        
        return [
            "status"    => "success", 
            "datos"     => $datos, 
            "productos" => $array_productos,
            "cantidad"  => $sumaCantidad,
            "total"     => number_format($total,2),
            "puntos"    => $sumaPuntos
        ];
    }


    /**
     * Retorna los datos de las ventas y consignación para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarVentasMostrador() : array {
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
        
        $ventas = $this->db->select('*', 'salida', null, "fechaVenta BETWEEN '$start_date' AND '$end_date' AND id_almacen='$id_almacen'", null, "ORDER BY id_salida DESC");
        $consignaciones = $this->db->select('*', 'consignacion', null, "tipo = 'salida' AND fecha BETWEEN '$start_date' AND '$end_date' AND id_almacen='$id_almacen'", null, "ORDER BY id_consignacion DESC");

        $data = [];
        if($ventas){
            foreach ($ventas as $key => $value) {

                if( ($value['id_cliente'] === null || $value['id_cliente'] == '') && ($value['id_accion'] == 1 || $value['id_accion'] == 2) ){
                    $tipoSalida = 'Ajuste';
                    $genero = 'o';
                }else{
                    $tipoSalida = 'Venta';
                    $genero = 'a';
                }
                
                $infoData = [];

                $fechaVenta = $value['fechaVenta'].' '.$value['horaVenta'];
                $infoData[] = $fechaVenta;

                if($tipoSalida == 'Ajuste'){
                    $infoData[] = $tipoSalida;  
                }elseif($tipoSalida == 'Venta'){
                    $infoData[] = $tipoSalida;
                }
                $infoData[] = $value["folio"];

                switch ($value["metodo_pago"]) {
                    case 'efectivo':
                        if($value["total"] > 0){
                            $infoData[] = 'EFECTIVO';
                        }else{
                            $infoData[] = 'ANTICIPO';
                        }
                        break;

                    case 'tarjeta':
                        if($value["total"] > 0){
                            $infoData[] = 'TARJETA';
                        }else{
                            $infoData[] = 'ANTICIPO';
                        }
                        break;

                    case 'mixto':
                        if($value["total"] > 0){
                            $infoData[] = 'MIXTO';
                        }
                        break;

                    case 'puntos':
                        $infoData[] = 'PUNTOS';
                        break;
                    
                    default:
                    $infoData[] = '--';
                    break;
                }
                
                if($tipoSalida == 'Ajuste'){
                    $subtotal = $this->db->select('SUM(cantidad * precio) AS subtotal', 'salida_detalle', null, "id_salida = '".$value['id_salida']."'");
                    $infoData[] = number_format($subtotal[0]['subtotal'],2);
                }elseif($tipoSalida == 'Venta'){
                    $subtotal = $this->db->select('SUM( vendido * ( precio - ( ( precio * descuento ) / 100 ) ) ) AS subtotal', 'salida_detalle', null, "id_salida = '".$value['id_salida']."'");
                    
                    $anticipo = (real) $value['anticipo'];
                    $subtotal = (real) $subtotal[0]['subtotal'];
                    
                    if($anticipo != 0){
                        $subtotal = $subtotal - $anticipo;
                    }else{
                        $subtotal = $subtotal;
                    }
                    $infoData[] = number_format($subtotal,2);
                }

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
                        if($tipoSalida == 'Venta'){
                            $infoData[] = '<span class="badge bg-red">Cancelad'.$genero.'</span>';
                        }else{
                            $infoData[] = '--';
                        }
                        break;

                    case 1:
                        if($tipoSalida == 'Venta'){
                            $infoData[] = '<span class="badge bg-green">Aplicad'.$genero.'</span>';
                        }else{
                            $infoData[] = '--';
                        }
                        break;
                }
			
                $date1=date_create($value["fechaVenta"]);
                $date2=date_create(date('Y-m-d'));
                $diff=date_diff($date1,$date2);
                $diff = $diff->d;

                $btn_ver = "<button type='button' class='btn btn-sm btn-default btn-flat mostrarVentaSalida' folio='".$value["folio"]."' tipo='".$tipoSalida."'>
                                <i data-toggle='tooltip' title='Ver detalles' class='fas fa-eye'></i>
                            </button>";
                $btn_ticket = "<a href='ventasDeMostrador/ticket/".$value['id_salida']."' target='_blank' class='btn btn-sm btn-flat btn-default' key='".$value["id_salida"]."'>
                                <i data-toggle='tooltip' title='Descargar ticket' class='fas fa-receipt'></i>
                            </a>"; 
                $btn_nota = "<a href='ventasDeMostrador/nota/".$value['id_salida']."' target='_blank' class='btn btn-sm btn-flat btn-default' key='".$value["id_salida"]."'>
                            <i data-toggle='tooltip' title='Descargar nota' class='fas fa-file-alt'></i>
                        </a>";
                if($diff <= 7){         // No se permite una devolución despues de 7 días
                	$btn_editar = "<a href='ventasDeMostrador/editar/".$value["id_salida"]."' class='btn btn-sm btn-flat btn-default'>
                                <i data-toggle='tooltip' title='Devolución' class='fas fa-reply text-red'></i>
                            </a>";
                }else{                  // No se permite cancelar despues de 2 días
                	$btn_editar = "";
                }
                if($diff <= 1){
                	$btn_cancelar = "<button class='btn btn-sm btn-default btn-flat cancelarVenta' folio='".$value["folio"]."'>
                                    <i data-toggle='tooltip' title='Cancelar venta' class='fas fa-window-close text-red'></i>
                                </button>";
                }else{
                	$btn_cancelar = "";
                }

                // si ya se aplico una devolución, se desactiva el boton para hacer otra devolución y para cancelar la venta
                $dev = $this->db->query("SELECT * FROM salida_detalle WHERE id_salida = '{$value['id_salida']}' AND devolucion != 0");
                if($dev->num_rows > 0){
                    $btn_editar = "";
                    $btn_cancelar = "";
                }
                
                $btn_group = "<div class='btn-group'>";
                if($tipoSalida == 'Ajuste'){
                    $btn_group .= $btn_ver;
                    $btn_group .= $btn_nota;
                }elseif($tipoSalida == 'Venta'){
                    if($value['metodo_pago'] == 'efectivo' || $value['metodo_pago'] == 'tarjeta' || $value['metodo_pago'] == 'mixto'){

                        $btn_group .= $btn_ver;
                        $btn_group .= $btn_ticket;
                        if($value['estado'] == 1 && $anticipo == 0){      // si el estado es 1, es decir aplicada, se muestran los botones para editar y para cancelar
                            $btn_group .= $btn_editar;
                            $btn_group .= $btn_cancelar;
                        }

                    }else{

                        $btn_group .= $btn_ver;
                        $btn_group .= $btn_ticket;

                    }
                }
                $btn_group .= "</div>";
                $infoData[] = $btn_group;
                
                $data[] = $infoData; 
            }
        }

        if($consignaciones){
            foreach ($consignaciones as $key => $value) {
                $infoData = [];

                $fechaSalida = $value['fecha'].' '.$value['hora'];
                $infoData[] = $fechaSalida;

                $infoData[] = 'S.Consignación';

                $infoData[] = $value["folio"];

                $infoData[] = '--';

                $subtotal = $this->db->select('SUM(cantidad*costo) AS subtotal', 'consignacion_detalle', null, "id_consignacion = '".$value['id_consignacion']."'");
                $infoData[] = number_format($subtotal[0]['subtotal'],2);

                $time = strtotime($fechaSalida);
                $timeUnaHora = $time + 3600;
                $hoy = date('Y-m-d H:i:s');
                $f_hoy = Helper\Functions::fecha($hoy);
                $f_ven = Helper\Functions::fecha($fechaSalida);
                $f1 = substr($f_hoy,0,-17);
                $f2 = substr($f_ven,0,-17);
                if(time() < $timeUnaHora){
                    $fecha = Helper\Strings::amigable_time(strtotime($fechaSalida));
                }elseif(substr($hoy, 0, -9) == substr($fechaSalida, 0, -9)){
                    $fecha = str_replace($f2, 'Hoy ', $f_ven);
                }else{
                    $fecha = Helper\Functions::fecha($fechaSalida);
                }
                $infoData[] = $fecha;

                $infoData[] = '--';

                $btn_ver = "<button type='button' class='btn btn-sm btn-flat btn-default mostrarVentaSalida' folio='".$value["folio"]."' tipo='Salida de consignación'>
                                <i data-toggle='tooltip' title='Ver detalles' class='fas fa-eye'></i>
                            </button>";
                $btn_nota = "<a href='ventasDeMostrador/nota/".$value['id_consignacion']."' target='_blank' class='btn btn-sm btn-flat btn-default' key='".$value["id_consignacion"]."'>
                                <i data-toggle='tooltip' title='Descargar nota' class='fas fa-file-alt'></i>
                            </a>";

                $btn_group = "<div class='btn-group'>";
                $btn_group .= $btn_ver;
                $btn_group .= $btn_nota;
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


    public function mostrarListaVenta() {
        try {
            global $config, $http;

            $folio = $this->db->scape($http->request->get('folio'));
            $venta = $this->ventasPor('folio',$folio);

            if($venta){

                $ventaDetalle = $this->ventaDetalle('id_salida',$venta[0]['id_salida']);

                $tbody = '';
                $sumaCantidad = 0;
                $sumaSubtotal = 0;

                foreach ($ventaDetalle as $key => $value) {
                    
                    $cantidad = (int) $value['cantidad'];
                    $sumaCantidad = $sumaCantidad + $cantidad;
                    $producto = (new Model\Productos)->productoNi($value['id_producto']);
                    $codigo = $producto['codigo'];
                    $precio = (real) $value['precio'];
                    $descuento = (int) $value['descuento'];

                    if($descuento != 0){                                            # - Si el descuento es diferente de 0 -
                        $precioDescuento = $precio - ( ($precio * $descuento) / 100 );# - Calcular el precio con descuento -
                        $subtotal = $cantidad*$precioDescuento;                     # - Calcular subtotal con el precio descuento -
                    }else{                                                          # - Si el descuento es 0 -
                        $precioDescuento = 0;                                       # - Definir precio descuento como 0
                        $subtotal = $cantidad*$precio;                              # - Calcular sobtotal con el precio normal -
                    }

                    if($precioDescuento != 0){                                      # - Si el precio descuento es diferente de 0 -
                        $precioFormato = '<del class="text-muted">'.number_format($precio, 2).'</del> <b>'.number_format($precioDescuento,2).'</b>'; # - Mostrar precio normal y precio con descuento -
                    }else{
                        $precioFormato = '<b>'.number_format($precio, 2).'</b>'; # - Mostrar solo precio normal -
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

                    if($producto["detalles"] != '' && $producto["detalles"] !== null){
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

                    $sumaSubtotal = $sumaSubtotal + $subtotal;

                    $tbody .= '
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">'.$cantidad.'</td>
                        <td style="vertical-align: middle;">
                            '.$codigo.' - <b>'.$producto['producto'].'</b> '.$producto['leyenda'].' - '.$editorial.' - '.$autores.' <i class="fas fa-info-circle infoDetalles text-aqua" data-toggle="popover" title="<b>FICHA TÉCNICA</b>" data-content="<small>'.$detallesTexto.'</small>"></i>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            '.$precioFormato.'
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            '.$descuento.' %
                        </td>
                        <td class="text-right" style="vertical-align: middle;">$ '.number_format($subtotal,2).'</td>
                    </tr>';
                }

                $tbody .= '
                <tr>
                    <th class="text-center">'.$sumaCantidad.'</th>
                    <th class="text-right" colspan="3">TOTAL:</th>
                    <th class="text-right text-red">'.number_format($sumaSubtotal,2).'</th>
                </tr>';

                return array('status' => 'success', 'tipo'=> 'venta', 'tbody' => $tbody);

            }else{

                $salida = $this->salidaPor('folio',$folio);

                if($salida){
                    $salidaDetalle = $this->salidaDetalle('id_consignacion',$salida[0]['id_consignacion']);

                    $tbody = '';
                    $sumaCantidad = 0;
                    $sumaSubtotal = 0;

                    foreach ($salidaDetalle as $key => $value) {
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

                        if($producto["detalles"] != '' && $producto["detalles"] !== null){
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
                        $sumaSubtotal = $sumaSubtotal + $subtotal;

                        $tbody .= '
                        <tr class="font-weight-bold">
                            <td class="text-center" style="vertical-align: middle;">'.$cantidad.'</td>
                            <td style="vertical-align: middle;">
                                '.$codigo.' - <b>'.$producto['producto'].'</b> '.$producto['leyenda'].' - '.$editorial.' - '.$autores.' <i class="fas fa-info-circle infoDetalles text-aqua" data-toggle="popover" title="<b>FICHA TÉCNICA</b>" data-content="<small>'.$detallesTexto.'</small>"></i>
                            </td>
                            <td class="text-right" style="vertical-align: middle;">
                                '.number_format($costo,2).'
                            </td>
                            <td class="text-right" style="vertical-align: middle;" class="font-weight-bold">
                                '.number_format($precio,2).'
                            </td>
                            <td class="text-right" style="vertical-align: middle;">'.number_format($subtotal,2).'</td>
                        </tr>';
                    }

                    $tbody .= '
                    <tr>
                        <th class="text-center">'.$sumaCantidad.'</th>
                        <th class="text-right" colspan="3">TOTAL:</th>
                        <th class="text-right text-red">'.number_format($sumaSubtotal,2).'</th>
                    </tr>';

                    return array('status' => 'success', 'tipo'=> 'salida', 'tbody' => $tbody);

                }else{
                    throw new ModelsException('La salida no existe.');
                }

            }

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    # CANCELAR VENTA COMPLETA POR FOLIO
    public function devolucionPorFolio() {
        try {
            global $http;

            # VALIDAR PASSWORD
            $password = $this->db->scape($http->request->get('pass'));
            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();
            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            # VALIDAR CAJA
            $almacen = (new Model\Almacenes)->almacenPrincipal($userProfile["id_user"]);
            $caja = (new Model\Caja)->cargarCaja('id_almacen', $almacen['id_almacen']);
            if(!$caja){
                throw new ModelsException('Es necesario registrar monto incial en caja para poder continuar.');
            }else{
                if($caja && $caja['estado'] == 1){
                    throw new ModelsException('No es posible realizar más movientos, la caja del día de hoy ya ha sido cerrada.');
                }
            }
            $idCaja = $caja['id_caja'];

            # VALIDAR FOLIO DE VENTA
            $folio = $this->db->scape($http->request->get('folio'));
            $ventas = $this->ventasPor("folio", $folio);
            if(!$ventas){
                throw new ModelsException('El folio '.$folio.' no existe.');
            }
            $venta = $ventas[0];

            # VALIDAR CANCELACIÓN
            if($venta['metodo_pago'] == 'puntos'){
                throw new ModelsException('Las ventas pagadas con puntos no pueden ser canceladas.');
            }
            if($venta['estado'] == 0){
                throw new ModelsException('La venta '.$folio.' ya ha sido cancelada.');
            }
            if($venta['id_almacen'] != $almacen['id_almacen']){
                throw new ModelsException('La venta '.$folio.' no se realizó en esté almacen.');
            }
            if($venta['id_accion'] !== null){
                throw new ModelsException('El '.$folio.' no corresponde a una venta.');
            }

            $fecha_modificacion = date('Y-m-d H:i:s');

            # OBTENER LISTA DE PRODUCTOS
            $lista_productos = $this->ventaDetalle('id_salida', $venta['id_salida']);     

            $total_puntos = 0;
            $sumaCantidad = 0;
            $total = 0;

            # RECORRER LISTA
            foreach ($lista_productos as $key => $value) {

                $idProducto = $value['id_producto'];
                $cantidad = (int) $value['vendido'];
                $costo = (real) $value['costo'];
                $precio = (real) $value['precio'];
                $descuento = (int) $value['descuento'];
                $puntos = (real) $value['puntos'];

                if($descuento != 0){
                    $precioDescuento = $precio - ( ($precio * $descuento) / 100 );
                    $subtotal = $cantidad * $precioDescuento;
                }else{
                    $precioDescuento = 0;
                    $subtotal = $cantidad * $precio;
                }

                $total_puntos += $puntos;
                $sumaCantidad += $cantidad;

                $total += $subtotal;
 
                # REGISTRAR MOVIMIENTO EN CARDEX
                $this->db->insert('productos_cardex', [
                    'id_producto' => $idProducto,
                    'cantidad' => $cantidad,
                    'id_almacen' => $almacen['id_almacen'],
                    'id_clienteProveedor' => $venta['id_cliente'],
                    'costo' => $costo,
                    'precio' => $precio,
                    'descuento' => $descuento,
                    'operacion' => 'devolucion',
                    'movimiento' => 'df',                                # df = devolucion por folio (venta cancelada)
                    'referencia' => $venta['folio'],
                    'fecha' => $fecha_modificacion 
                ]);

                # ACTUALIZAR STOCK DEL PRODUCTO
                $producto = (new Model\Productos)->producto($idProducto);
                
                $nuevoStock = $producto['stock'] + $cantidad;
                $nuevasEntradas = $producto['total_entradas'] + $cantidad;
                $nuevaCantidad = $producto['ventasMostrador'] - $cantidad;

                $this->db->update('productos', array(
                    'stock' => $nuevoStock,
                    'ventasMostrador' => $nuevaCantidad,
                    'fechaModificacion' => $fecha_modificacion,
                    'usuarioModificacion' => $this->id_user,
                    'total_entradas' => $nuevasEntradas
                ), "id='$idProducto'", 1);

                # ACTUALIZAR DEVOLCIÓN Y CANTIDAD VENDIDA EN EL DETALLE DE SALIDA
                $this->db->update('salida_detalle', array(
                    'devolucion' => $cantidad,
                    'vendido' => 0,
                    'estado' => 2
                ), "id_salida='{$venta['id_salida']}' AND id_producto='$idProducto'", 1);

            }

            # QUITAR LA CANTIDAD Y PUNTOS AL CLIENTE
            $cliente = (new Model\Clientes)->cliente($venta['id_cliente']);

            $nuevasCompras = $cliente['compras'] - $sumaCantidad;

            if($venta['id_cliente'] != 1){

                if($cliente['tipo'] == 1){
                    $nuevosPuntos = $cliente['puntos'] - $total_puntos;
                    $this->db->update('clientes', array(
                        'compras' => $nuevasCompras,
                        'puntos' => $nuevosPuntos,
                        'fechaModificacion' => $fecha_modificacion,
                        'usuarioModificacion' => $this->id_user
                    ), "id_cliente='{$cliente['id_cliente']}'", 1);
                }

            }else{

                $this->db->update('clientes', array(
                    'compras' => $nuevasCompras,
                    'fechaModificacion' => $fecha_modificacion,
                    'usuarioModificacion' => $this->id_user
                ), "id_cliente='{$cliente['id_cliente']}'", 1);

            }

            # REGISTRAS MOVIMIENTOS EN CAJA (EGRESO)
            /* 
            Si se cancela una venta con pago mixto, solo se registrara en caja el egreso por el monto en efectivo,
            por lo que, si la venta si se pago y se cancelo por que el cliente lo pidio, sera necesario registrar un egreso adicional
            por el monto de pago con tarjeta, ya que podra faltar en caja, sino no hay problema

            Si se cancela una venta que se pago por completo con tarjeta, no se registrara en caja el egreso por el total,
            por lo que, si la venta si se pago y se cancelo por que el cliente lo pidio, sera necesario registrar un egreso adicional
            por el monto total de la venta, ya que podra faltar en caja, sino no hay problema

            No se registran los egresos de pago con tarjeta, por si la cancelacion de la venta fue por un error de quien la registro,
            de otra manera el egreso se refleharia en caja siendo que nunca se regreso dinero al cliente, teniendo segun dinero de más
            cuando en realidad no es así
            */
            if($venta['metodo_pago'] == 'efectivo'){
                $tipo = 'Egreso';
                $descripcion = 'Egreso por concepto de cancelación de venta: '.$venta['folio'].'.'; 
                $this->db->insert('caja_movimientos', array(
                    'id_caja' => $idCaja,
                    'tipo' => $tipo,
                    'concepto' => 'Cancelación',
                    'referencia' => $venta['folio'],
                    'descripcion' => $descripcion,
                    'monto' => $total,
                    'usuario' => $this->id_user,
                    'hora' => date('H:i:s')
                ));
            }elseif($venta['metodo_pago'] == 'mixto'){
                $tipo = 'Egreso';
                $descripcion = 'Egreso (solo mixto efectivo) por concepto de cancelación de venta: '.$venta['folio'].'.'; 
                $total = $venta['montoEfectivo'] - $venta['cambio'];
                $this->db->insert('caja_movimientos', array(
                    'id_caja' => $idCaja,
                    'tipo' => $tipo,
                    'concepto' => 'Cancelación',
                    'referencia' => $venta['folio'],
                    'descripcion' => $descripcion,
                    'monto' => $total,
                    'usuario' => $this->id_user,
                    'hora' => date('H:i:s')
                ));
            }

            # ACTUALIZAR LA VENTA
            $this->db->update('salida', array(
                'fechaCancelacion' => $fecha_modificacion,
                'usuarioCancelacion' => $this->id_user,
                'estado' => 0
            ), "id_salida='{$venta['id_salida']}'");
            
            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => "La venta {$venta['folio']} se canceló correctamente.");

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage()); 
        }
    }


    public function cargarListaVentaEditar() {
        global $http;

        # DATOS DE LA VENTA A EDITAR
        $id_venta = intval($http->request->get('id_venta'));
        $venta = $this->venta($id_venta);
        
        if($venta){

            $productos = $this->ventaDetalle('id_salida',$id_venta);

            $tbody = '';
            $sumaSubtotal = 0;
            $sumaCantidad = 0;

            foreach ($productos as $key => $value) {
                
                $miProducto = (new Model\Productos)->producto($value['id_producto']);

                $codigo = $miProducto['codigo'];
                $producto = $miProducto['producto'];
                $leyenda = $miProducto['leyenda'];

                $cantidad = $value['vendido'];
                $precio = (real) $value['precio'];
                $descuento = (int) $value['descuento'];

                if($descuento != 0){
                    $precioDescuento = $precio - ( ($precio * $descuento) / 100 );
                    $subtotal = $cantidad * $precioDescuento;
                }else{
                    $precioDescuento = 0;
                    $subtotal = $cantidad * $precio;
                }

                $sumaCantidad += $cantidad;
                $sumaSubtotal += $subtotal;

                if($precioDescuento != 0){
                    $precioFormato = '<span class="badge bg-gray"><del>$'.number_format($precio, 2).'</del></span> <span class="badge bg-blue">'.number_format($precioDescuento,2).'</span>';   
                }else{
                    $precioFormato = '<span class="badge bg-blue">$ '.number_format($precio, 2).'</span>';
                }

                $plural = ($cantidad == 1) ? '' : 's';

                $precioFinal = $precio - $precioDescuento;

                $tbody .= '
                <tr class="hoverTrDefault">

                    <td style="vertical-align:middle;" class="text-center">';
                        if($value['vendido'] > 1){ # 1 = se puede generar devolución, 0 = ya se generó devolución

                            if($value['estado'] == 1){ # 1 = aun se puede generar devolucion por cantidad, # 2 ya se gerenero devolucion
                                $devolucion = ($value['devolucion'] == 0) ? '' : $value['devolucion'];
                                $tbody .= '
                                    <div class="form-group" style="margin-bottom:0;">
                                        <div class="input-group">
                                            <div class="input-group-addon bg-gray" style="font-weight:bold; padding-left:3px; padding-right:3px;">
                                                '.$value['cantidad'].' - 
                                            </div>
                                            <input type="text" class="form-control input-sm text-right inputDevolucion" id="inputDevolucion'.$value['id_producto'].'" value="'.$devolucion.'" vendido="'.$value['vendido'].'" key="'.$value['id_producto'].'" placeholder="0">
                                            <div class="input-group-btn">
                                                <button type="button" disabled class="btn bg-navy btn-flat btn-sm btnDevolucion" id="btnDevolucion'.$value['id_producto'].'" key="'.$value['id_producto'].'" producto="'.$producto.' '.$leyenda.'" precio="'.$precioFinal.'">
                                                    <i class="fas fa-check" title="Aplicar" data-toggle="tooltip"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }else{
                                $tbody .= $value['cantidad'].' - '.$value['devolucion'];
                            }

                        }else{
                            $tbody .= $value['cantidad'].' - '.$value['devolucion'];
                        }
                    $tbody .= '
                    </td>

                    <td style="vertical-align:middle;" class="text-center">
                        '.$cantidad.'
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

                    <td style="vertical-align:middle;">';
                        if($value['vendido'] > 0 && $value['estado'] == 1){ # Si lo vendido es mayor a 0 y el estado es 1 = aun no se genera devolucion
                            $tbody .= '
                            <button type="button" class="btn bg-navy btn-sm devolucionProducto" style="margin-right:5px;" key="'.$value['id_producto'].'" cantidad="'.$cantidad.'" producto="'.$producto.'" data-toggle="tooltip" title="Aplicar devolución de '.$cantidad.' libro'.$plural.'" subtotal="'.number_format($subtotal,2).'" data-placement="left">
                                <i class="fas fa-reply"></i>
                            </button>';
                        }
                    $tbody .= '
                    </td>

                </tr>
                ';

            }

            $totalFormat = "$ ".number_format($sumaSubtotal,2);

            return array("status"=>'success',
                        "tbody" => $tbody, 
                        "sumaCantidad"=> $sumaCantidad, 
                        "totalG" => $totalFormat);
                    
        }else{
            return array("status" => 'error');
        }
    }

    # DEVOLUCION POR ITEM
    public function devolucionPorItem() {
        try {
            global $http;

            $password = $this->db->scape($http->request->get('pass'));
            $id_producto = intval($http->request->get('id_producto'));
            $id_venta = intval($http->request->get('id_venta'));
            $fecha_modificacion = date('Y-m-d H:i:s');

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            # VALIDAR CAJA
            $almacen = (new Model\Almacenes)->almacenPrincipal($userProfile["id_user"]);

            $caja = (new Model\Caja)->cargarCaja('id_almacen', $almacen['id_almacen']);
            if(!$caja){
                throw new ModelsException('Es necesario registrar monto incial en caja para poder continuar.');
            }else{
                if($caja && $caja['estado'] == 1){
                    throw new ModelsException('No es posible realizar más movientos, la caja del día de hoy ya ha sido cerrada.');
                }
            }
            $idCaja = $caja['id_caja'];

            $venta = $this->venta($id_venta);
            if(!$venta){
                throw new ModelsException('La venta no existe.');
            }

            # VALIDAR CANCELACIÓN
            if($venta['metodo_pago'] == 'puntos'){
                throw new ModelsException('Para las ventas pagadas con puntos no hay devolución.');
            }
            if($venta['estado'] == 0){
                throw new ModelsException('La venta '.$venta['folio'].' ya ha sido cancelada.');
            }
            if($venta['id_almacen'] != $almacen['id_almacen']){
                throw new ModelsException('La venta '.$venta['folio'].' no se realizó en esté almacen.');
            }

            $item_producto = $this->ventaDetallePor('id_salida',$id_venta, 'id_producto', $id_producto);
            if(!$item_producto){
                throw new ModelsException('El libro y la venta no coinciden.');
            }
            $item_producto = $item_producto[0];
            
            $cantidad = (int) $item_producto['cantidad'];
            $vendido = (int) $item_producto['vendido'];
            if($cantidad != $vendido){
                $cantidad = $vendido;
            }
            $costo = (real) $item_producto['costo'];
            $precio = (real) $item_producto['precio'];
            $descuento = (int) $item_producto['descuento'];
            $puntos = (real) $item_producto['puntos'];

            if($descuento != 0){
                $precioDescuento = $precio - ( ($precio * $descuento) / 100 );
                $subtotal = $cantidad * $precioDescuento;
            }else{
                $precioDescuento = 0;
                $subtotal = $cantidad * $precio;
            }

            $producto = (new Model\Productos)->producto($id_producto);
            if($item_producto['estado'] == 2){
                throw new ModelsException('Al libro '.$producto['producto'].' '.$producto['leyenda'].' ya se le aplicó devolución.');
            }
            
            # REGISTRAR MOVIMIENTO EN CARDEX
            $this->db->insert('productos_cardex', [
                'id_producto' => $id_producto,
                'cantidad' => $cantidad,
                'id_almacen' => $almacen['id_almacen'],
                'id_clienteProveedor' => $venta['id_cliente'],
                'costo' => $costo,
                'precio' => $precio,
                'descuento' => $descuento,
                'operacion' => 'devolucion',
                'movimiento' => 'di',                           # di = devolucion por item
                'referencia' => $venta['folio'],
                'fecha' => $fecha_modificacion 
            ]);

            # ACTUALIZAR STOCK DEL PRODUCTO
            $nuevoStock = $producto['stock'] + $cantidad;
            $nuevasEntradas = $producto['total_entradas'] + $cantidad;
            $nuevasVentas = $producto['ventasMostrador'] - $cantidad;
            $this->db->update('productos', array(
                'stock' => $nuevoStock,
                'ventasMostrador' => $nuevasVentas,
                'fechaModificacion' => $fecha_modificacion,
                'usuarioModificacion' => $this->id_user,
                'total_entradas' => $nuevasEntradas
            ), "id='$id_producto'", 1);

            # ACTUALIZAR ESTADO EN EL DETALLE DE SALIDA
            $this->db->update('salida_detalle', array(
                'devolucion' => $cantidad,
                'vendido' => 0,
                'estado' => 2
            ), "id_salida='{$venta['id_salida']}' AND id_producto='$id_producto'", 1);

            # QUITAR LA CANTIDAD Y PUNTOS AL CLIENTE
            $cliente = (new Model\Clientes)->cliente($venta['id_cliente']);
            $nuevasCompras = $cliente['compras'] - $cantidad;
            if($venta['id_cliente'] != 1){
                if($cliente['tipo'] == 1){
                    $nuevosPuntos = $cliente['puntos'] - $puntos;
                    $this->db->update('clientes', array(
                        'compras' => $nuevasCompras,
                        'puntos' => $nuevosPuntos,
                        'fechaModificacion' => $fecha_modificacion,
                        'usuarioModificacion' => $this->id_user
                    ), "id_cliente='{$cliente['id_cliente']}'", 1);
                }
            }else{
                $this->db->update('clientes', array(
                    'compras' => $nuevasCompras,
                    'fechaModificacion' => $fecha_modificacion,
                    'usuarioModificacion' => $this->id_user
                ), "id_cliente='{$cliente['id_cliente']}'", 1);
            }

            # REGISTRAS MOVIMIENTOS EN CAJA (EGRESO)
            /*
            Solo para devoluciones y donde la venta tiene más de un libro, ademas de que es para el item completo, 
            es decir, aplica si por ejemplo se llevo dos y devuelve los dos, si el caso es deolver solo uno se ocupa la devolucion
            por cantidad.

            OJO
            La devolucion registra un egreso en caja por el monto equivalente, por lo que si se trata ajustar la venta por algun error
            sera mejor cancelar la venta y volver a hacerla, ya que si la venta fue registrada con pago en tarjeta o mixto, en caja sobraria dinero
            cuando no es así, si fue por deolucion del cliente no hay problema
            */
            $tipo = 'Egreso';
            $descripcion = 'Egreso por concepto de devolución en venta: '.$venta['folio'].' del libro '.$id_producto.' '.$producto['producto'].'.'; 
            $this->db->insert('caja_movimientos', array(
                'id_caja' => $idCaja,
                'tipo' => $tipo,
                'concepto' => 'Devolución',
                'referencia' => $venta['folio'],
                'descripcion' => $descripcion,
                'monto' => $subtotal,
                'usuario' => $this->id_user,
                'hora' => date('H:i:s')
            ));   
            
            $sql = $this->db->select('SUM(cantidad) as cant, SUM(devolucion) AS dev','salida_detalle', null, "id_salida = '{$venta['id_salida']}'");
            $cantidades = (int) $sql[0]['cant'];
            $devoluciones = (int) $sql[0]['dev'];

            if($cantidades == $devoluciones){
                # ACTUALIZAR LA VENTA
                $this->db->update('salida', array(
                    'fechaCancelacion' => $fecha_modificacion,
                    'usuarioCancelacion' => $this->id_user,
                    'estado' => 0
                ), "id_salida='{$venta['id_salida']}'", 1);
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La devolución del producto se realizó correctamente');
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage()); 
        }
        
    }

    # DEVOLUCION POR CANTIDAD
    public function devolucionPorCantidad() {
        try {
            global $http;
            $password = $this->db->scape($http->request->get('pass'));
            $id_producto = intval($http->request->get('id_producto'));
            $id_venta = intval($http->request->get('id_venta'));
            $devolucion = intval($http->request->get('devolucion'));
            $fecha_modificacion = date('Y-m-d H:i:s');

            # VALIDAR CONTRASEÑA
            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();
            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            # VALIDAR CAJA
            $almacen = (new Model\Almacenes)->almacenPrincipal($userProfile["id_user"]);
            $caja = (new Model\Caja)->cargarCaja('id_almacen', $almacen['id_almacen']);
            if(!$caja){
                throw new ModelsException('Es necesario registrar monto incial en caja para poder continuar.');
            }else{
                if($caja && $caja['estado'] == 1){
                    throw new ModelsException('No es posible realizar más movientos, la caja del día de hoy ya ha sido cerrada.');
                }
            }
            $idCaja = $caja['id_caja'];
            
            # VALIDAR VENTA
            $venta = $this->venta($id_venta);
            if(!$venta){
                throw new ModelsException('La venta no existe.');
            }

            # VALIDAR CANCELACIÓN
            if($venta['metodo_pago'] == 'puntos'){
                throw new ModelsException('Para las ventas pagadas con puntos no hay devolución.');
            }
            if($venta['estado'] == 0){
                throw new ModelsException('La venta '.$venta['folio'].' ya ha sido cancelada.');
            }
            if($venta['id_almacen'] != $almacen['id_almacen']){
                throw new ModelsException('La venta '.$venta['folio'].' no se realizó en esté almacen.');
            }

            // OBTENER DETALLES DE LA VENTA
            $item_producto = $this->ventaDetallePor('id_salida',$id_venta, 'id_producto', $id_producto);
            if(!$item_producto){
                throw new ModelsException('El libro y la venta no coinciden.');
            }
            $item_producto = $item_producto[0];
            $cantidad = (int) $item_producto['cantidad'];
            $costo = (real) $item_producto['costo'];
            $precio = (real) $item_producto['precio'];
            $descuento = (int) $item_producto['descuento'];
            $puntos = ($item_producto['puntos'] / $cantidad);
            $puntos = $puntos * $devolucion;
            if($descuento != 0){
                $precioDescuento = $precio - ( ($precio * $descuento) / 100 );
                $subtotal = $devolucion * $precioDescuento;
            }else{
                $precioDescuento = 0;
                $subtotal = $devolucion * $precio;
            }

            # VALIDAR SI YA SE APLICO DEVOLUCION
            $producto = (new Model\Productos)->producto($id_producto);
            if($item_producto['estado'] == 2){
                throw new ModelsException('Al libro '.$producto['producto'].' '.$producto['leyenda'].' ya se le aplicó devolución.');
            }

            # VALIDAR SI YA SE APLICO DEVOLUCION
            if($devolucion > $cantidad){
                throw new ModelsException('La devolución del libro no debe superar la cantidad vendida.');
            }elseif($devolucion == $cantidad){
                throw new ModelsException('Para devolucion completa, usar el boton al final de la fila.');
            }

            # REGISTRAR MOVIMIENTO EN CARDEX
            $this->db->insert('productos_cardex', [
                'id_producto' => $id_producto,
                'cantidad' => $devolucion,
                'id_almacen' => $almacen['id_almacen'],
                'id_clienteProveedor' => $venta['id_cliente'],
                'costo' => $costo,
                'precio' => $precio,
                'descuento' => $descuento,
                'operacion' => 'devolucion',
                'movimiento' => 'dc',                                   # dc = devolucion por cantidad
                'referencia' => $venta['folio'],
                'fecha' => $fecha_modificacion 
            ]);

            # ACTUALIZAR STOCK DEL PRODUCTO
            $nuevoStock = $producto['stock'] + $devolucion;
            $nuevasEntradas = $producto['total_entradas'] + $cantidad;
            $nuevasVentas = $producto['ventasMostrador'] - $devolucion;
            $this->db->update('productos', array(
                'stock' => $nuevoStock,
                'ventasMostrador' => $nuevasVentas,
                'fechaModificacion' => $fecha_modificacion,
                'usuarioModificacion' => $this->id_user,
                'total_entradas' => $nuevasEntradas
            ), "id='$id_producto'", 1);

            # ACTUALIZAR ESTADO EN EL DETALLE DE SALIDA
            $nuevoVendido = $cantidad - $devolucion;
            $this->db->update('salida_detalle', array(
                'devolucion' => $devolucion,
                'vendido' => $nuevoVendido,
                'estado' => 2
            ), "id_salida='{$venta['id_salida']}' AND id_producto='$id_producto'", 1);

            # QUITAR LA CANTIDAD Y PUNTOS AL CLIENTE
            $cliente = (new Model\Clientes)->cliente($venta['id_cliente']);
            $nuevasCompras = $cliente['compras'] - $devolucion;
            if($venta['id_cliente'] != 1){
                if($cliente['tipo'] == 1){
                    $nuevosPuntos = $cliente['puntos'] - $puntos;
                    $this->db->update('clientes', array(
                        'compras' => $nuevasCompras,
                        'puntos' => $nuevosPuntos,
                        'fechaModificacion' => $fecha_modificacion,
                        'usuarioModificacion' => $this->id_user
                    ), "id_cliente='{$cliente['id_cliente']}'", 1);
                }
            }else{
                $this->db->update('clientes', array(
                    'compras' => $nuevasCompras,
                    'fechaModificacion' => $fecha_modificacion,
                    'usuarioModificacion' => $this->id_user
                ), "id_cliente='{$cliente['id_cliente']}'", 1);
            }

            # REGISTRAS MOVIMIENTOS EN CAJA (EGRESO)
            /*
            Solo para devoluciones por cantidad.

            OJO
            La devolucion registra un egreso en caja por el monto equivalente, por lo que si se trata ajustar la venta por algun error
            sera mejor cancelar la venta y volver a hacerla, ya que si la venta fue registrada con pago en tarjeta o mixto, en caja sobraria dinero
            cuando no es así, si fue por deolucion del cliente no hay problema
            */
            $tipo = 'Egreso';
            $descripcion = 'Egreso por concepto de devolución en venta: '.$venta['folio'].' del libro '.$id_producto.' '.$producto['producto'].'.'; 
            $this->db->insert('caja_movimientos', array(
                'id_caja' => $idCaja,
                'tipo' => $tipo,
                'concepto' => 'Devolución',
                'referencia' => $venta['folio'],
                'descripcion' => $descripcion,
                'monto' => $subtotal,
                'usuario' => $this->id_user,
                'hora' => date('H:i:s')
            ));   
            
            $sql = $this->db->select('SUM(cantidad) as cant, SUM(devolucion) AS dev','salida_detalle', null, "id_salida = '{$venta['id_salida']}'");
            $cantidades = (int) $sql[0]['cant'];
            $devoluciones = (int) $sql[0]['dev'];

            if($cantidades == $devoluciones){
                # ACTUALIZAR LA VENTA
                $this->db->update('salida', array(
                    'fechaCancelacion' => $fecha_modificacion,
                    'usuarioCancelacion' => $this->id_user,
                    'estado' => 0
                ), "id_salida='{$venta['id_salida']}'");
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La devolución del producto se realizó correctamente');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage()); 
        }
    }

    public function imprimirTicket($idVenta) {

        $datosPlantilla = (new Model\Comercio)->datosPlantilla();
        $venta = $this->venta($idVenta);

        $almacen = (new Model\Almacenes)->almacen($venta['id_almacen']);

        $cliente = (new Model\Clientes)->cliente($venta['id_cliente']);
        if($venta['id_cliente'] != 1){
            $cliente_txt = '<b>Cliente:</b> '.$cliente['cliente'];
        }else{
            $cliente_txt = $cliente['cliente'];
        }
        $atendio = (new Model\Administradores)->administrador($venta['usuarioVenta']);

        $lista_productos = $this->ventaDetalle('id_salida',$idVenta);

        switch ($venta["metodo_pago"]) {
            case 'efectivo':
                $mp = 'Efectivo';
                break;
            case 'tarjeta':
                $mp = 'Tarjeta';
                break;
            case 'mixto':
                $mp = 'Mixto';
                break;
            case 'puntos':
                $mp = 'Puntos';
                break;
        }
            
        $fecha = date_create($venta['fechaVenta']);
        $fecha = date_format($fecha, 'd/m/Y');
        $hora = date_create($venta['horaVenta']);
        $hora = date_format($hora, 'h:i a');
        
        $page_size = 110;
        $item_size = 12;
        $items = count($lista_productos);

        $height = $page_size + ($item_size*$items);

        $html = '
        <page format="76x'.$height.'" backtop="5mm" backbottom="5mm" backleft="6mm" backright="6mm" style="font-family: Arial, Helvetica, sans-serif;">
            
            <div style="padding:0;">

                <h4 style="text-align:center; font-weight:bold; margin-top:0;">
                    '.$datosPlantilla['nombre'].'
                </h4>
                <p style="text-align:center; font-size:12px; margin-top:-15px;">
                    '.$datosPlantilla['direccion'].'<br>
                    '.$datosPlantilla['telefono'].'
                </p>

                <p style="text-align:center; font-size:12px; margin-top:-5px;">
                    '.$fecha.' '.$hora.'
                </p>

                <p style="text-align:center; margin:0; padding:0;">
                    '.$venta['folio'].'
                </p>';
                
                if($venta['estado'] == 0) {
                    $html .= '<br><p style="text-align:center; margin:0; padding:0;"><b>VENTA CANCELADA</b></p>';
                }
                
                $html .= '
                <!--
                <div style="text-align:center">
                    <barcode dimension="1D" type="C128" value="'.$venta['folio'].'" label="label" style="width:95%; height:8mm; font-size: 12px; margin:auto;"></barcode>
                </div>
                -->

                <p style="text-align:center; font-size:11px;">
                    '.$cliente_txt.'
                </p>

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
                    
                    $total = 0;
                    $sumaCantidad = 0;
                    $sumaPuntos = 0;

                    foreach ($lista_productos as $key => $value) {
                        $p = (new Model\Productos)->productoNi($value['id_producto']);

                        $producto = $p['producto'].' '.$p['leyenda'];
                        
                        if($venta['estado'] == 0) {
                            $cantidad = (int) $value['cantidad'];
                        } else {
                            $cantidad = (int) $value['vendido'];
                        }
                        
                        $descuento = (int) $value['descuento'];
                        $precio = (real) $value['precio'];
                        
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

                    if($total > 0){
                        //$total_letra = Helper\Functions::numero_letras(number_format($total,2,'.',''));
                        $total_letra = '';
                    }
                    
                    $anticipo = (int) $venta['anticipo'];
                    $resta = $total - $anticipo;
                    if($anticipo != 0){
                        $html .= '
                        <tr>
                            <td style="width:20%; text-align:left; padding-top:30px;"><strong>'.$sumaCantidad.'</strong></td>
                            <td style="width:50%; text-align:right; padding-top:30px;" colspan="2">Total</td>
                            <td style="width:30%; text-align:right; padding-top:30px;"><strong>'.number_format($total,2).'</strong></td>
                        </tr>
                        <tr>
                            <td style="width:20%; text-align:left; padding-top:1px;"></td>
                            <td style="width:50%; text-align:right; padding-top:1px;" colspan="2">'.$venta['folio_anticipo'].'</td>
                            <td style="width:30%; text-align:right; padding-top:1px;"><strong>'.number_format($anticipo,2).'</strong></td>
                        </tr>
                        <tr>
                            <td style="width:20%; text-align:left; padding-top:1px;"></td>
                            <td style="width:50%; text-align:right; padding-top:1px;" colspan="2">=</td>
                            <td style="width:30%; text-align:right; padding-top:1px;"><strong>'.number_format($resta,2).'</strong></td>
                        </tr>';
                    }else{
                        $html .= '
                        <tr>
                            <td style="width:20%; text-align:left; padding-top:30px;"><strong>'.$sumaCantidad.'</strong></td>
                            <td style="width:50%; text-align:right; padding-top:30px;" colspan="2">Total:</td>
                            <td style="width:30%; text-align:right; padding-top:30px;"><strong>'.number_format($total,2).'</strong></td>
                        </tr>';
                    }

                    if($sumaPuntos > 0){
                        $html .= '
                        <tr>
                            <td style="text-align:right; padding-top:5px; font-weight:bold;" colspan="3">PUNTOS: #</td>
                            <td style="text-align:right; padding-top:5px;">'.number_format($sumaPuntos,2).'</td>
                        </tr>';
                    }

                    if($venta['estado'] == 0){

                        /*$html .= '
                        <tr>
                            <td colspan="4" style="text-align:center; padding-top:60px; padding-bottom:50px; font-weight:bold; font-size:22px;">CANCELADA</td>
                        </tr>';*/

                    }else{

                        $html .= '
                        <tr>
                            <td colspan="4" style="text-align:center; vertical-align:middle; padding:3px 0px;">'.$total_letra.'</td>
                        </tr>';

                        if($mp == 'Efectivo'){
                            $cambio = $venta['pago'] - $total;
                            $html .= '
                            <!--<tr>
                                <td style="text-align:right; padding-top:5px; font-weight:bold;" colspan="3">Pago en '.$mp.':</td>
                                <td style="text-align:right; padding-top:5px;">'.number_format($venta['pago'],2).'</td>
                            </tr>
                            <tr>
                                <td style="text-align:right; padding-top:5px; font-weight:bold;" colspan="3">Cambio:</td>
                                <td style="text-align:right; padding-top:5px;">'.number_format($cambio,2).'</td>
                            </tr>-->';
                        }elseif($mp == 'Tarjeta'){

                            if($venta['transaccion'] == ''){
                                $trTransaccion = '';
                            }else{
                                $trTransaccion = '
                                <tr>
                                    <td style="text-align:right; padding-top:5px;" colspan="4"><b>Transacción:</b> '.$venta['transaccion'].'</td>
                                </tr>';
                            }

                            /*$html .= '
                            <tr>
                                <td style="text-align:right; padding-top:5px; font-weight:bold;" colspan="4">Pago con '.$mp.'</td>
                            </tr>
                            '.$trTransaccion;*/
                        }elseif($mp == 'Mixto'){
                            $cambio = $venta['pago'] - $total;
                            if($venta['transaccion'] == ''){
                                $trTransaccion = '';
                            }else{
                                $trTransaccion = '
                                <tr>
                                    <td style="text-align:right; padding-top:5px;" colspan="4"><b>Transacción:</b> '.$venta['transaccion'].'</td>
                                </tr>';
                            }
                            $html .= '
                            <!--<tr>
                                <td style="text-align:right; padding-top:5px; font-weight:bold;" colspan="3">Pago con tarjeta:</td>
                                <td style="text-align:right; padding-top:5px;">'.number_format($venta['montoTarjeta'],2).'</td>
                            </tr>
                            '.$trTransaccion.'
                            <tr>
                                <td style="text-align:right; padding-top:5px; font-weight:bold;" colspan="3">Pago en efectivo:</td>
                                <td style="text-align:right; padding-top:5px;">'.number_format($venta['montoEfectivo'],2).'</td>
                            </tr>
                            <tr>
                                <td style="text-align:right; padding-top:5px; font-weight:bold;" colspan="3">Cambio:</td>
                                <td style="text-align:right; padding-top:5px;">'.number_format($cambio,2).'</td>
                            </tr>-->';
                        }else{
                            $html .= '
                            <tr>
                                <td style="text-align:right; padding-top:5px; font-weight:bold;" colspan="4">¡Felicidades!</td>
                            </tr>';
                        }
                    }   
                    
                    /*
                    $_total = "10.20";
                    $array_total = explode(".",$_total);
                    $decimal = (int) $array_total[1];
                    $redondeo = 0;

                    if($decimal < 50 && $decimal != 0){
                        $redondeo = 50 - $decimal;
                    }elseif($decimal < 50){
                        $redondeo = 100 - $decimal;
                    }

                    $redondeo = "0.".$redondeo;
                    $redondeo = (real) $redondeo;


                    echo 'Total: '.$_total.' <br> Redondeo: '.number_format($redondeo,2).'<br> Total con redondeo: '.number_format( ($_total + $redondeo),2 );

                    */

                $html .= '    
                </table>';
                
                if($venta['estado'] != 0){
                    $html .= '
                    <table style="width: 100%; margin-top:5px;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="font-size:12px; width:100%; text-align:center; padding:5px 0; font-weight:bold;">
                                NO SE ACEPTAN <br> CAMBIOS NI DEVOLUCIONES 
                            </td>
                        </tr>
                    </table>
                    <p style="text-align:center; font-size:10px;">
                        ~ Gracias por su compra ~<br>
                    </p>';
                }
                
                $html .= '
            </div>
            
        </page>';
        
        # ACTUALIZAR VENTA
        $this->db->update('salida', array(
            'impresion' => 1
        ), "id_salida='".$venta['id_salida']."'");

        echo $html;
    }

    public function imprimirNota($idSalida, $tipo) {

        $datosPlantilla = (new Model\Comercio)->datosPlantilla();

        if($tipo == 'consignacion'){

            $salida = $this->salidaPor('id_consignacion',$idSalida); // consignacion

            $fechaHoraLetra = Helper\Functions::fecha($salida[0]['fecha'].' '.$salida[0]['hora']);
            $atendio = (new Model\Administradores)->administrador($salida[0]['usuario_consignacion']);
            $referencia = $salida[0]['referencia'];
            $proveedor = (new Model\Proveedores)->proveedor($salida[0]['id_proveedor']);

            $tipoSalida = ' SALIDA DE CONSIGNACIÓN';

            $salidaDetalle = $this->salidaDetalle('id_consignacion',$salida[0]['id_consignacion']);

        }elseif($tipo == 'ajuste'){

            $salida = $this->ventasPor('id_salida',$idSalida); // consignacion

            $fechaHoraLetra = Helper\Functions::fecha($salida[0]['fechaVenta'].' '.$salida[0]['horaVenta']);
            $atendio = (new Model\Administradores)->administrador($salida[0]['usuarioVenta']);
            $referencia = '--';
            $proveedor = (new Model\Proveedores)->proveedor($salida[0]['id_accion']);

            $tipoSalida = 'AJUSTE DE SALIDA';

            $salidaDetalle = $this->ventaDetalle('id_salida',$salida[0]['id_salida']);

        }
        $almacen = (new Model\Almacenes)->almacen($salida[0]['id_almacen']);

        $html = '
            <page backtop="12.7mm" backbottom="12.7mm" backleft="12.7mm" backright="12.7mm" style="font-family: Helvetica, sans-serif;">
                <table style="width:100%;" border="0" cellpadding="0">
                    <tr>
                        <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; padding-bottom:20px; color:#000; font-size:10px;">

                            <b>Almacén:</b> '.$almacen['almacen'].'<br><br>

                            <b>Fecha:</b> '.$fechaHoraLetra.'<br>
                            <b>Registró:</b> '.$atendio['name'].'<br><br>

                            <b>Referencia:</b> '.$referencia.'<br>
                            <b>Proveedor:</b> <span style="color:'.$datosPlantilla['colorPlantilla'].';">'.$proveedor['proveedor'].'</span>

                        </td>
                        <td style="width:50%; border-bottom:2px solid '.$datosPlantilla['colorPlantilla'].'; text-align:right; color:'.$datosPlantilla['colorPlantilla'].'; vertical-align:middle;">
                            FOLIO DE '.$tipoSalida.'<br><br>
                            <barcode dimension="1D" type="C128" value="'.$salida[0]['folio'].'" label="label" style="width:80%; height:6mm; font-size: 12px;"></barcode>
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

                foreach ($salidaDetalle as $key => $value) {
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
                            <br>(ID: '.$value['id_producto'].') <b>'.$producto['producto'].'</b>
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
                        <td style="border-bottom:1px solid #eee;text-align:right; padding-right:5px; font-weight:bold;">
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