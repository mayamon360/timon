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
 * Modelo ReporteComprasEntradas
 */
class ReporteComprasEntradas extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene el monto de compras clasificado por día
     *    
     *
     * @return false|array con información de las compras
     */ 
    public function cargarCompras() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarCompras' || $metodo == null) {

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
                    #$compras = $this->db->select("DATE_FORMAT(e.fecha, '%h %p') as fecha, SUM(ed.cantidad*ed.costo) AS total", 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)", null, "GROUP BY DATE_FORMAT(e.fecha, '%H') ORDER BY e.id_entrada ASC");
                    $compras = $this->db->select("DATE_FORMAT(CONCAT(e.fecha,' ',e.hora), '%h %p') as fecha, SUM(ed.cantidad*ed.costo) AS total, e.hora", 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$start_date' AND '$end_date' AND e.id_proveedor IS NOT NULL", null, "GROUP BY DATE_FORMAT(e.hora, '%H') ORDER BY e.id_entrada ASC");
                # Si el rango es menor o igual a un mes
                }elseif($y == 0 && $m == 0 && $d <= 31){
                    #$compras = $this->db->select("DATE_FORMAT(e.fecha, '%Y-%m-%d') as fecha, SUM(ed.cantidad*ed.costo) AS total", 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)", null, "GROUP BY DATE_FORMAT(e.fecha, '%Y-%m-%d') ORDER BY e.id_entrada ASC");
                    $compras = $this->db->select("DATE_FORMAT(e.fecha, '%Y-%m-%d') as fecha, SUM(ed.cantidad*ed.costo) AS total, e.hora", 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$start_date' AND '$end_date' AND e.id_proveedor IS NOT NULL", null, "GROUP BY DATE_FORMAT(e.fecha, '%Y-%m-%d') ORDER BY e.id_entrada ASC");
                }elseif($y == 0 && $m >= 1){
                    #$compras = $this->db->select("DATE_FORMAT(e.fecha, '%Y-%m') as fecha, SUM(ed.cantidad*ed.costo) AS total", 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)", null, "GROUP BY DATE_FORMAT(e.fecha, '%m') ORDER BY e.id_entrada ASC");
                    $compras = $this->db->select("DATE_FORMAT(e.fecha, '%Y-%m') as fecha, SUM(ed.cantidad*ed.costo) AS total, e.hora", 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$start_date' AND '$end_date' AND e.id_proveedor IS NOT NULL", null, "GROUP BY DATE_FORMAT(e.fecha, '%m') ORDER BY e.id_entrada ASC");
                }elseif($y >= 1){
                    #$compras = $this->db->select("DATE_FORMAT(e.fecha, '%Y') as fecha, SUM(ed.cantidad*ed.costo) AS total", 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY)", null, "GROUP BY DATE_FORMAT(e.fecha, '%Y') ORDER BY e.id_entrada ASC");
                    $compras = $this->db->select("DATE_FORMAT(e.fecha, '%Y') as fecha, SUM(ed.cantidad*ed.costo) AS total, e.hora", 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$start_date' AND '$end_date' AND e.id_proveedor IS NOT NULL", null, "GROUP BY DATE_FORMAT(e.fecha, '%Y') ORDER BY e.id_entrada ASC");
                }else{
                    throw new ModelsException('No hay entradas en el rango seleccionado.');
                }
                
            }else{                
                $compras = $this->db->select('e.fecha, SUM(ed.cantidad*ed.costo) AS total', 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "AND e.id_proveedor IS NOT NULL", null, "ORDER BY e.id_entrada ASC");
            }

            $total = 0;

            if($compras){


                foreach ($compras as $key => $value) {
                    $output[] = [
                        'fecha' => $value["fecha"],
                        'compras' => floatval($value["total"])
                    ];

                    $total = $total + $value["total"];
                }

                return array('status' => 'success', 'message' => $output, 'total' => '$ '.number_format($total,2));

            }else{
                throw new ModelsException('No hay entradas en el rango seleccionado.');
            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'message' => $e->getMessage());

        }

    }

    /**
     * Obtiene los 10 productos más comprados
     *    
     *
     * @return false|array con información de los productos más comprados
     */ 
    public function cargarMasComprados() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarMasComprados' || $metodo == null) {

                throw new ModelsException('El método recibido no está definido.');

            }

            $start_date = $this->db->scape($http->request->get('start_date'));
            $end_date = $this->db->scape($http->request->get('end_date'));

            if($start_date != '' && $end_date != ''){
                $stmt = $this->db->select("SUM(pc.cantidad) AS cantidadCompras, p.id, p.codigo, p.producto, p.leyenda", 'productos_cardex AS pc', 'INNER JOIN productos AS p ON pc.id_producto=p.id', "pc.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY) AND pc.operacion='compra'", 30, "GROUP BY pc.id_producto ORDER BY cantidadCompras DESC");
            }else{
                $stmt = $this->db->select("SUM(pc.cantidad) AS cantidadCompras, p.id, p.codigo, p.producto, p.leyenda", 'productos_cardex AS pc', 'INNER JOIN productos AS p ON pc.id_producto=p.id', "pc.operacion='compra'", 30, "GROUP BY pc.id_producto ORDER BY cantidadCompras DESC");
            }

            if($stmt){

                $arrayColores = ['#f44336', '#ff5722', '#ff9800', '#ffc107', '#ffeb3b', '#cddc39', '#8bc34a', '#4caf50', '#388e3c', '#2e7d32'];
                $li_masComprados = '';

                foreach ($stmt as $key => $value) {

                    $comprasReales = intval($value["cantidadCompras"]);
                    if($comprasReales > 0){

                        $li_masComprados .= '
                            <li>
                                <a>
                                    '.$value['codigo'].' - '.$value['producto'].' '.$value['leyenda'].' <span class="pull-right"><strong> '.$comprasReales.' </strong></span>
                                </a>
                            </li>';

                        $output[] = [
                            'producto' => $value['codigo'],
                            'porcentaje' => $comprasReales,
                            'color' => $arrayColores[$key]
                        ];
                    }
                    
                } 

                return array('status' => 'success', 'message' => $output, 'li_masComprados' => $li_masComprados);

            }else{
                throw new ModelsException('No hay entradas en el rango seleccionado.');
            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'message' => $e->getMessage());

        }

    }

    /**
     * Obtiene los 10 mejores proveedores
     *    
     *
     * @return false|array con información de los mejores proveedores
     */ 
    public function cargarTopProveedores() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarTopProveedores' || $metodo == null) {

                throw new ModelsException('El método recibido no está definido.');

            }

            $start_date = $this->db->scape($http->request->get('start_date'));
            $end_date = $this->db->scape($http->request->get('end_date'));

            if($start_date != '' && $end_date != ''){
                $stmt = $this->db->select("SUM(pc.cantidad) AS cantidadCompras, p.id_proveedor, p.proveedor", 'productos_cardex AS pc', 'INNER JOIN proveedores AS p ON pc.id_clienteProveedor=p.id_proveedor', "pc.fecha BETWEEN '$start_date' AND DATE_ADD('$end_date', INTERVAL 1 DAY) AND pc.operacion='compra'", 30, "GROUP BY pc.id_clienteProveedor ORDER BY cantidadCompras DESC");
            }else{
                $stmt = $this->db->select("SUM(pc.cantidad) AS cantidadCompras, p.id_proveedor, p.proveedor", 'productos_cardex AS pc', 'INNER JOIN proveedores AS p ON pc.id_clienteProveedor=p.id_proveedor', "pc.operacion='compra'", 30, "GROUP BY pc.id_clienteProveedor ORDER BY cantidadCompras DESC");
            }

            if($stmt){

                $arrayColores = ['#f44336', '#ff5722', '#ff9800', '#ffc107', '#ffeb3b', '#cddc39', '#8bc34a', '#4caf50', '#388e3c', '#2e7d32'];
                $li_topProveedores = '';

                foreach ($stmt as $key => $value) {

                    $comprasReales = intval($value["cantidadCompras"]);
                    if($comprasReales > 0){

                        $li_topProveedores .= '
                            <li>
                                <a>
                                    #'.$value['id_proveedor'].' - '.$value['proveedor'].' <span class="pull-right"><strong> '.$comprasReales.' </strong></span>
                                </a>
                            </li>';

                        $output[] = [
                            'proveedor' => '#'.$value['id_proveedor'],
                            'porcentaje' => $comprasReales,
                            'color' => $arrayColores[$key]
                        ];
                    }
                    
                } 

                return array('status' => 'success', 'message' => $output, 'li_topProveedores' => $li_topProveedores);

            }else{
                throw new ModelsException('No hay entradas en el rango seleccionado.');
            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'message' => $e->getMessage());

        }

    }

    public function descargarReporteCompras($fi, $ff) {

        $fi = $this->db->scape($fi);
        $ff = $this->db->scape($ff);

        #$compras = $this->db->select('e.folio, e.id_almacen, e.factura, e.id_proveedor, e.fecha, e.usuario_entrada, ed.id_producto, ed.cantidad, ed.costo', 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$fi' AND DATE_ADD('$ff', INTERVAL 1 DAY)", null, 'ORDER BY e.id_entrada DESC');
        $compras = $this->db->select('e.folio, e.id_almacen, e.factura, e.id_proveedor, e.id_accion, e.fecha, e.hora, e.usuario_entrada, ed.id_producto, ed.cantidad, ed.costo', 'entrada AS e', "INNER JOIN entrada_detalle AS ed ON e.id_entrada=ed.id_entrada", "e.fecha BETWEEN '$fi' AND '$ff'", null, 'ORDER BY e.id_entrada DESC');

        if($compras){

            $nombre = "Reporte de entradas $fi - $ff.xls"; 
            header('Expires: 0');
            header('Cache-control: private');
            header('Content-type: application/vnd.ms-excel'); //Archivo excel
            header('Cache-Control: cache, must-revalidate');
            header('Content-Description: File Transfer');
            header('Last-Modified: '.date('D, d M Y H:i:s'));
            header('Pragma: public');
            header('Content-Disposition: attachment; filename="'.$nombre.'"');
            header('Content-Transfer-Encoding: binary');
            $html = "
                <table border='0'>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FOLIO COMPRA O AJUSTE</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>ALMACÉN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>REFERENCIA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>PROVEEDOR O ACCIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>REGISTRÓ</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>CANTIDAD</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>DESCRIPCIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>PRECIO DE COMPRA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>SUBTOTAL</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FECHA</td>
                    </tr>";

            foreach ($compras as $key => $value) {
                
                $almacen = (new Model\Almacenes)->almacen($value['id_almacen']);

                $folio = $value['folio'];
                $factura = $value['factura'];
                if($factura == ''){
                	$factura = '--';
                }
                $proveedor = (new Model\Proveedores)->proveedor($value['id_proveedor']);
                if(!$proveedor){
                	$proveedor = (new Model\Proveedores)->proveedor($value['id_accion']);
                }
                $usuario = (new Model\Administradores)->administrador($value['usuario_entrada']);

                $cantidad = (int) $value['cantidad'];

                $producto = (new Model\Productos)->productoNi($value['id_producto']);
                $codigo = $producto['codigo'];
                $mi_producto = $codigo.' - '.$producto['id'].' '.$producto['producto'].' '.$producto['leyenda'];

                $costo = (real) $value['costo'];

                $subtotal = $costo * $cantidad;

                $fecha_compra = $value['fecha'].' '.$value['hora'];

                $html .= "<tr>";

                $html .= "<td>$folio</td>";
                $html .= "<td>".$almacen['almacen']."</td>";
                $html .= "<td>$factura</td>";
                $html .= "<td>{$proveedor['proveedor']}</td>";
                $html .= "<td>{$usuario['name']}</td>";
                $html .= "<td style='text-align:right;'>$cantidad</td>";
                $html .= "<td>$mi_producto</td>";
                $html .= "<td style='text-align:right;'>$ ".number_format($costo,2)."</td>";
                $html .= "<td style='text-align:right;'>$ ".number_format($subtotal,2)."</td>";
                $html .= "<td>$fecha_compra</td>";

                $html .= "</tr>";              

            }

            $html .= "</table><br><br>";

            #$entradas = $this->db->select('a.folio, a.id_almacen, a.descripcion, a.fecha, a.usuario_ajuste, ad.id_producto, ad.cantidad, ad.costo', 'ajuste AS a', "INNER JOIN ajuste_detalle AS ad ON a.id_ajuste=ad.id_ajuste", "a.fecha BETWEEN '$fi' AND DATE_ADD('$ff', INTERVAL 1 DAY)", null, 'ORDER BY a.id_ajuste DESC');
            $entradas = $this->db->select('c.id_almacen, c.folio, c.referencia, c.fecha, c.hora, c.id_proveedor, c.usuario_consignacion, cd.id_producto, cd.cantidad, cd.costo', 'consignacion AS c', "INNER JOIN consignacion_detalle AS cd ON c.id_consignacion=cd.id_consignacion", "c.fecha BETWEEN '$fi' AND '$ff' AND tipo='entrada'", null, 'ORDER BY c.id_consignacion DESC');

            if($entradas){
                $html .= "
                <table border='0'>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FOLIO DE CONSIGNACIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>ALMACÉN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>REFERENCIA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>REGISTRÓ</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>CANTIDAD</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>DESCRIPCIÓN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>PROVEEDOR</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>PRECIO DE COMPRA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>SUBTOTAL</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>FECHA</td>
                    </tr>";
                
                    foreach ($entradas as $key => $value) {
                
                        $almacen = (new Model\Almacenes)->almacen($value['id_almacen']);
        
                        $folio = $value['folio'];

                        $proveedor = (new Model\Proveedores)->proveedor($value['id_proveedor']);
                        $usuario = (new Model\Administradores)->administrador($value['usuario_consignacion']);
        
                        $cantidad = (int) $value['cantidad'];
        
                        $producto = (new Model\Productos)->productoNi($value['id_producto']);
                        $codigo = $producto['codigo'];
                        $mi_producto = $codigo.' - '.$producto['id'].' '.$producto['producto'].' '.$producto['leyenda'];
        
                        $costo = (real) $value['costo'];
        
                        $subtotal = $costo * $cantidad;
        
                        $fecha_compra = $value['fecha'].' '.$value['hora'];
        
                        $html .= "<tr>";
        
                        $html .= "<td>$folio</td>";
                        $html .= "<td>{$almacen['almacen']}</td>";
                        $html .= "<td>{$value['referencia']}</td>";
                        $html .= "<td>{$proveedor['proveedor']}</td>";
                        $html .= "<td>{$usuario['name']}</td>";
                        $html .= "<td style='text-align:right;'>$cantidad</td>";
                        $html .= "<td>$mi_producto</td>";
                        $html .= "<td style='text-align:right;'>$ ".number_format($costo,2)."</td>";
                        $html .= "<td style='text-align:right;'>$ ".number_format($subtotal,2)."</td>";
                        $html .= "<td>$fecha_compra</td>";
        
                        $html .= "</tr>";              
        
                    }

                $html .= "</table>";
                
            }

            echo $html;

        }else{

                global $config;
                # REDIRECCIONAR
                Helper\Functions::redir($config['build']['url'].'reporteComprasEntradas');
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