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
 * Modelo ReporteVentasMostrador
 */
class ReporteVentasMostrador extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene el monto de ventas clasificado por día
     *    
     *
     * @return false|array con información de las ventas
     */ 
    public function cargarVentasMostrador() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarVentas' || $metodo == null) {

                throw new ModelsException('El método recibido no está definido.');

            }

            $start_date = $this->db->scape($http->request->get('start_date'));
            $end_date = $this->db->scape($http->request->get('end_date'));

            if($start_date != '' && $end_date != ''){

                $timeSD = new \DateTime($start_date);
                $timeED = new \DateTime($end_date);
                $diff = $timeSD->diff($timeED);

                $y = $diff->y;
                $m = $diff->m;
                $d = $diff->d;

                # Si el rango es de 1 día (ayer u hoy)
                if($y == 0 && $m == 0 && $d == 0){
                    $ventas = $this->db->query("SELECT DATE_FORMAT(CONCAT(s.fechaVenta,' ',s.horaVenta), '%h %p') as fecha, SUM( sd.vendido * ( sd.precio - ( ( sd.precio * sd.descuento ) / 100 ) ) ) AS total, 
                                                s.horaVenta 
                                                FROM salida AS s 
                                                INNER JOIN salida_detalle AS sd ON s.id_salida=sd.id_salida
                                                WHERE s.fechaVenta BETWEEN '$start_date' AND '$end_date'
                                                GROUP BY DATE_FORMAT(s.horaVenta, '%H') ORDER BY s.id_salida ASC");
                    if($ventas->num_rows == 0){
                        $ventas = false;
                    }                                   
                # Si el rango es menor o igual a un mes
                }elseif($y == 0 && $m == 0 && $d <= 31){
                    $ventas = $this->db->query("SELECT DATE_FORMAT(s.fechaVenta, '%Y-%m-%d') as fecha, 
                                                SUM( sd.vendido * ( sd.precio - ( ( sd.precio * sd.descuento ) / 100 ) ) ) AS total, 
                                                s.horaVenta 
                                                FROM salida AS s 
                                                INNER JOIN salida_detalle AS sd ON s.id_salida=sd.id_salida
                                                WHERE s.fechaVenta BETWEEN '$start_date' AND '$end_date'
                                                GROUP BY DATE_FORMAT(s.fechaVenta, '%d') ORDER BY s.id_salida ASC");  
                    if($ventas->num_rows == 0){
                        $ventas = false;
                    }            
                }elseif($y == 0 && $m >= 1 && $d >= 0){
                    $ventas = $this->db->query("SELECT DATE_FORMAT(s.fechaVenta, '%Y-%m') as fecha, 
                                                SUM( sd.vendido * ( sd.precio - ( ( sd.precio * sd.descuento ) / 100 ) ) ) AS total, 
                                                s.horaVenta 
                                                FROM salida AS s 
                                                INNER JOIN salida_detalle AS sd ON s.id_salida=sd.id_salida
                                                WHERE s.fechaVenta BETWEEN '$start_date' AND '$end_date'
                                                GROUP BY DATE_FORMAT(s.fechaVenta, '%m') ORDER BY s.id_salida ASC");
                    if($ventas->num_rows == 0){
                        $ventas = false;
                    }  
                }elseif($y >= 1){
                    $ventas = $this->db->query("SELECT DATE_FORMAT(s.fechaVenta, '%Y') as fecha, 
                                                SUM( sd.vendido * ( sd.precio - ( ( sd.precio * sd.descuento ) / 100 ) ) ) AS total, 
                                                s.horaVenta 
                                                FROM salida AS s 
                                                INNER JOIN salida_detalle AS sd ON s.id_salida=sd.id_salida
                                                WHERE s.fechaVenta BETWEEN '$start_date' AND '$end_date'
                                                GROUP BY DATE_FORMAT(s.fechaVenta, '%Y') ORDER BY s.id_salida ASC");
                    if($ventas->num_rows == 0){
                        $ventas = false;
                    }  
                }else{
                    throw new ModelsException('No hay ventas en el rango seleccionado.');
                }
                
            }else{
                $ventas = $this->db->query("SELECT s.fechaVenta as fecha, 
                                            SUM( sd.vendido * ( sd.precio - ( ( sd.precio * sd.descuento ) / 100 ) ) ) AS total, 
                                            s.horaVenta 
                                            FROM salida AS s 
                                            INNER JOIN salida_detalle AS sd ON s.id_salida=sd.id_salida
                                            ORDER BY s.id_salida ASC");
                if($ventas->num_rows == 0){
                    $ventas = false;
                }  
            }

            $total = 0;

            if(!$ventas){

                throw new ModelsException('No hay ventas en el rango seleccionado.');

            }else{

                foreach ($ventas as $key => $value) {
                    $output[] = [
                        'fecha' => $value["fecha"],
                        'salidas' => floatval($value["total"])
                    ];

                    $total = $total + $value["total"];
                }

                return array('status' => 'success', 'message' => $output, 'total' => '$ '.number_format($total,2));

            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'message' => $e->getMessage());

        }

    }

    /**
     * Obtiene los 10 productos más vendidos
     *    
     *
     * @return false|array con información de los productos más vendidos
     */ 
    public function cargarMasVendidosMostrador() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarMasVendidos' || $metodo == null) {

                throw new ModelsException('El método recibido no está definido.');

            }

            $start_date = $this->db->scape($http->request->get('start_date'));
            $end_date = $this->db->scape($http->request->get('end_date'));

            if($start_date != '' && $end_date != ''){

                $stmt = $this->db->select("SUM(pc.cantidad) AS cantidadVentas, p.id, p.codigo, p.producto", 'productos_cardex AS pc', 'INNER JOIN productos AS p ON pc.id_producto=p.id', "pc.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY) AND pc.operacion='venta'", 30, "GROUP BY pc.id_producto ORDER BY cantidadVentas DESC");
                
            }else{

                $stmt = $this->db->select("SUM(pc.cantidad) AS cantidadVentas, p.id, p.codigo, p.producto", 'productos_cardex AS pc', 'INNER JOIN productos AS p ON pc.id_producto=p.id', "pc.operacion='venta'", 30, "GROUP BY pc.id_producto ORDER BY cantidadVentas DESC");
            }

            if($stmt){

                $arrayColores = ['#f44336', '#ff5722', '#ff9800', '#ffc107', '#ffeb3b', '#cddc39', '#8bc34a', '#4caf50', '#388e3c', '#2e7d32'];
                $li_masVendidos = '';

                foreach ($stmt as $key => $value) {

                    $devoluciones = $this->db->select("SUM(pc.cantidad) AS cantidadDevoluciones", 'productos_cardex AS pc', null, "pc.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY) AND pc.operacion='devolucion' AND pc.id_producto='{$value['id']}'");
                    if($devoluciones){
                        $cantidadDevoluciones = (int) $devoluciones[0]['cantidadDevoluciones'];
                    }else{
                        $cantidadDevoluciones = 0;
                    }

                    $ventasReales = intval($value["cantidadVentas"]) - $cantidadDevoluciones;
                    if($ventasReales > 0){

                        $codigo = ($value['codigo'] == '') ? 'ID'.$value['id'] : $value['codigo'];
                        $li_masVendidos .= '
                            <li>
                                <a>
                                    '.$codigo.' - '.$value['producto'].' <span class="pull-right"><strong> '.$ventasReales.'</strong></span>
                                </a>
                            </li>';

                        $output[] = [
                            'producto' => $codigo,
                            'porcentaje' => $ventasReales,
                            'color' => $arrayColores[$key]
                        ];
                    }
                    
                } 

                return array('status' => 'success', 'message' => $output, 'li_masVendidos' => $li_masVendidos);

            }else{
                throw new ModelsException('No hay ventas en el rango seleccionado.');
            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'message' => $e->getMessage());

        }

    }

    /**
     * Obtiene los 10 mejores clientes
     *    
     *
     * @return false|array con información de los mejores clientes
     */ 
    public function cargarTopClientes() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarTopClientes' || $metodo == null) {

                throw new ModelsException('El método recibido no está definido.');

            }

            $start_date = $this->db->scape($http->request->get('start_date'));
            $end_date = $this->db->scape($http->request->get('end_date'));

            if($start_date != '' && $end_date != ''){

                $stmt = $this->db->select("SUM(pc.cantidad) AS cantidadVentas, c.id_cliente, c.cliente", 'productos_cardex AS pc', 'INNER JOIN clientes AS c ON pc.id_clienteProveedor=c.id_cliente', "pc.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY) AND pc.operacion='venta'", 30, "GROUP BY pc.id_clienteProveedor ORDER BY cantidadVentas DESC");
                
            }else{

                $stmt = $this->db->select("SUM(pc.cantidad) AS cantidadVentas, c.id_cliente, c.cliente", 'productos_cardex AS pc', 'INNER JOIN clientes AS c ON pc.id_clienteProveedor=c.id_cliente', "pc.operacion='venta'", 30, "GROUP BY pc.id_clienteProveedor ORDER BY cantidadVentas DESC");
            }

            if($stmt){

                $arrayColores = ['#f44336', '#ff5722', '#ff9800', '#ffc107', '#ffeb3b', '#cddc39', '#8bc34a', '#4caf50', '#388e3c', '#2e7d32'];
                $li_topClientes = '';

                foreach ($stmt as $key => $value) {

                    $devoluciones = $this->db->select("SUM(pc.cantidad) AS cantidadDevoluciones", 'productos_cardex AS pc', null, "pc.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY) AND pc.operacion='devolucion' AND pc.id_clienteProveedor='{$value['id_cliente']}'");

                    if($devoluciones){
                        $cantidadDevoluciones = (int) $devoluciones[0]['cantidadDevoluciones'];
                    }else{
                        $cantidadDevoluciones = 0;
                    }

                    $ventasReales = intval($value["cantidadVentas"]) - $cantidadDevoluciones;
                    if($ventasReales > 0){

                        $li_topClientes .= '
                            <li>
                                <a>
                                    #'.$value['id_cliente'].' - '.$value['cliente'].' <span class="pull-right"><strong> '.$ventasReales.'</strong></span>
                                </a>
                            </li>';

                        $output[] = [
                            'cliente' => '#'.$value['id_cliente'],
                            'porcentaje' => $ventasReales,
                            'color' => $arrayColores[$key]
                        ];
                    }
                    
                } 

                return array('status' => 'success', 'message' => $output, 'li_topClientes' => $li_topClientes);

            }else{
                throw new ModelsException('No hay ventas en el rango seleccionado.');
            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'message' => $e->getMessage());

        }

    }

    public function descargarReporteVentas($fi, $ff) {

        $fi = $this->db->scape($fi);
        $ff = $this->db->scape($ff);

        $ventas = $this->db->select('s.id_salida, s.folio, s.id_almacen, s.metodo_pago, s.id_cliente, s.id_accion, s.fechaVenta, s.horaVenta, s.usuarioVenta, sd.id_producto, sd.cantidad, sd.vendido, sd.precio, sd.descuento', 'salida AS s', "INNER JOIN salida_detalle AS sd ON s.id_salida=sd.id_salida", "s.fechaVenta BETWEEN '$fi' AND '$ff' AND s.estado != 0", null, 'ORDER BY s.id_salida DESC');
                
        if($ventas){

            $nombre = "Reporte de salidas $fi - $ff.xls"; 
            header('Expires: 0');
            header('Cache-control: private');
            header('Content-type: application/vnd.ms-excel'); //Archivo excelS
            header('Cache-Control: cache, must-revalidate');
            header('Content-Description: File Transfer');
            header('Last-Modified: '.date('D, d M Y H:i:s'));
            header('Pragma: public');
            header('Content-Disposition: attachment; filename="'.$nombre.'"');
            header('Content-Transfer-Encoding: binary');
            $html = "
                <table border='0'>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FOLIO VENTA O AJUSTE</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>ALMACÉN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>CLIENTE O ACCIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>ATENDIÓ</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>CANTIDAD</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>DESCRPCIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>PRECIO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>DESCUENTO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>TOTAL</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>MÉTODO DE PAGO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FECHA</td>
                    </tr>";

            foreach ($ventas as $key => $value) {
                
                $folio = $value['folio'];
                
                $almacen = (new Model\Almacenes)->almacen($value['id_almacen']);

                $cliente = (new Model\Clientes)->cliente($value['id_cliente']);
                $cliente = $cliente['cliente'];
                if(!$cliente){
                    $cliente = (new Model\Proveedores)->proveedor($value['id_accion']);
                    $cliente = $cliente['proveedor'];
                }

                $mp = $value['metodo_pago'];
                switch ($mp) {
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
                
                $fecha_venta = $value['fechaVenta'].' '.$value['horaVenta'];
  
                $usuario = (new Model\Administradores)->administrador($value['usuarioVenta']);                
	
                $id_producto = $value['id_producto'];
                $cantidad = (int) $value['vendido'];
                if($cantidad == 0){
                    $cantidad = (int) $value['cantidad'];
                }
                $descuento = (int) $value['descuento'];
                $precio = (real) $value['precio'];

                $producto = (new Model\Productos)->productoNi($id_producto);
                $codigo = $producto['codigo'];
                $mi_producto = $codigo.' - '.$producto['id'].' '.$producto['producto'].' '.$producto['leyenda'];

                if($cantidad > 0){
                    if($descuento != 0){

                        $precioDescuento = $precio - (($precio * $descuento) / 100);
                        $subtotal = $cantidad * $precioDescuento;

                    }else{

                        $precioDescuento = 0;
                        $subtotal = $cantidad * $precio;

                    }

                    if($value['metodo_pago']  == 'tarjeta' || $value['metodo_pago']  == 'efectivo' || $value['metodo_pago']  == 'mixto'){
                        $color = 'color: blue;';
                    }else{
                        $color = 'color: black;';
                    }

                    $html .= "<tr>";
                    $html .= "<td style='$color'>$folio</td>";
                    $html .= "<td style='$color'>".$almacen['almacen']."</td>";
                    $html .= "<td style='$color'>$cliente</td>";
                    $html .= "<td style='$color'>{$usuario['name']}</td>";
                    $html .= "<td style='text-align:right; $color'>$cantidad</td>";
                    $html .= "<td style='$color'>$mi_producto</td>";
                    $html .= "<td style='text-align:right; $color'>$ ".number_format($precio,2)."</td>";
                    $html .= "<td style='text-align:right; $color'>$descuento %</td>";
                    $html .= "<td style='text-align:right; $color'>$ ".number_format($subtotal,2)."</td>";
                    $html .= "<td style='$color'>$mp</td>";
                    $html .= "<td style='$color'>$fecha_venta</td>";
                    $html .= "</tr>";
                }         

            }

            $html .= "</table><br><br>";

            $salidas = $this->db->select('c.id_almacen, c.folio, c.referencia, c.fecha, c.hora, c.id_proveedor, c.usuario_consignacion, cd.id_producto, cd.cantidad, cd.costo, cd.precio', 'consignacion AS c', "INNER JOIN consignacion_detalle AS cd ON c.id_consignacion=cd.id_consignacion", "c.fecha BETWEEN '$fi' AND '$ff' AND tipo='salida'", null, 'ORDER BY c.id_consignacion DESC');

            if($salidas){
                $html .= "
                <table border='0'>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FOLIO DE CONSIGNACIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>ALMACÉN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>PROVEEDOR</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>REGISTRÓ</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>CANTIDAD</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>DESCRIPCIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>COSTO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>PRECIO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>TOTAL</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>REFERENCIA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FECHA</td>
                    </tr>";
                
                    foreach ($salidas as $key => $value) {
                
                        $almacen = (new Model\Almacenes)->almacen($value['id_almacen']);
        
                        $folio = $value['folio'];

                        $proveedor = (new Model\Proveedores)->proveedor($value['id_proveedor']);
                        $usuario = (new Model\Administradores)->administrador($value['usuario_consignacion']);
        
                        $cantidad = (int) $value['cantidad'];
        
                        $producto = (new Model\Productos)->productoNi($value['id_producto']);
                        $codigo = $producto['codigo'];
                        $mi_producto = $codigo.' - '.$producto['id'].' '.$producto['producto'].' '.$producto['leyenda'];
        
                        $costo = (real) $value['costo'];
                        $precio = (real) $value['precio'];
        
                        $subtotal = $costo * $cantidad;
        
                        $fecha_compra = $value['fecha'].' '.$value['hora'];
        
                        $html .= "<tr>";
        
                        $html .= "<td>$folio</td>";
                        $html .= "<td>{$almacen['almacen']}</td>";
                        $html .= "<td>{$proveedor['proveedor']}</td>";                        
                        $html .= "<td>{$usuario['name']}</td>";
                        $html .= "<td style='text-align:right;'>$cantidad</td>";
                        $html .= "<td>$mi_producto</td>";                        
                        $html .= "<td style='text-align:right; font-weight:bold;'>$ ".number_format($costo,2)."</td>";
                        $html .= "<td style='text-align:right;'>$ ".number_format($precio,2)."</td>";
                        $html .= "<td style='text-align:right; font-weight:bold;'>$ ".number_format($subtotal,2)."</td>";
                        $html .= "<td>{$value['referencia']}</td>";
                        $html .= "<td>$fecha_compra</td>";
        
                        $html .= "</tr>";              
        
                    }

                $html .= "</table>";
                
            }

            echo $html;
        }else{
            global $config;
            # REDIRECCIONAR
            Helper\Functions::redir($config['build']['url'].'reporteVentasMostrador');
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