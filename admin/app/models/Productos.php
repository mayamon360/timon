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
 * Modelo Productos
 */
class Productos extends Models implements IModels {
    use DBModel;

    /**
     * DIRECTORIO DE IMAGENES
     *
     * @var int
     */

    const URL_ASSETS_WEBSITE = '../../';
    
    /**
     * Crea y retorna un codigo aleatorio
     * 
     * @return array
    */
    public function generarCodigoProducto() : array {
        $clave = uniqid();
        return array('codigo' => $clave);
    }

    /**
     * Obtiene los productos según los valores a buscar
     *
     * @param string $valores: array con los datos a buscar
     * 
     * @return false|array
    */ 
    public function busquedaAvanzada($valores,$order = "ORDER BY id DESC") {     
        $productos = $this->db->select("*", "productos", null, $valores, null, $order);
        return $productos;
    }

    /**
     * Obtiene los productos según el valor de un item, NO APLICA PARA LOS DESCONTINUADOS
     *
     * @param string $item: campo de la tabla
     * @param string $valor: valor a comparar
     * 
     * @return false|array
    */ 
    public function productosPor($item, $valor) {     
        $productos = $this->db->select("*", "productos", null, "$item='$valor'");
        return $productos;
    }

    /**
     * Obtiene datos de un producto según su id en la base de datos, NO APLICA SI ESTA DESCONTINUADO
     *
     * @param int $id: Id del producto a obtener
     * 
     * @return false|array[0]
    */ 
    public function producto($id) {
        $producto = $this->db->select("*", "productos", null, "id='$id' AND descontinuado = 'NO'", 1);
        if($producto){
            return $producto[0];
        }else{
            return false;
        }   
    }
    
    /**
     * Obtiene datos de un producto según su id en la base de datos, APLICA SI ESTA O NO DESCONTINUADO
     *
     * @param int $id: Id del producto a obtener
     * 
     * @return false|array[0]
    */ 
    public function productoNi($id) {
        $producto = $this->db->select("*", "productos", null, "id='$id'", 1);
        if($producto){
            return $producto[0];
        }else{
            return false;
        }   
    }
    
    /**
     * Obtiene todos los productos, NO APLICA PARA LOS DESCONTINUADOS
     *
     * @return false|array
    */ 
    public function productos() {
        $productos = $this->db->select("*", "productos", null, "descontinuado = 'NO'", null, "ORDER BY id DESC");
        return $productos;
    }
    
    /**
     * Obtiene el stock de algun producto
     *
     * @param int $id_producto: id del producto
     * @param int $id_almacen: almacen de la sesion actual de usuario
     * 
     * @return false|array
    */ 
    public function stock_producto($id_producto, $id_almacen) {
        
        $compra = $this->db->select('SUM(cantidad) as compras', 'productos_cardex', null, "id_producto = '$id_producto' AND operacion='compra' AND id_almacen='$id_almacen'");
        $entrada = $this->db->select('SUM(cantidad) as entradas', 'productos_cardex', null, "id_producto = '$id_producto' AND operacion='entrada' AND id_almacen='$id_almacen'");
        $devolucion = $this->db->select('SUM(cantidad) as devoluciones', 'productos_cardex', null, "id_producto = '$id_producto' AND operacion='devolucion' AND id_almacen='$id_almacen'");
        $venta = $this->db->select('SUM(cantidad) as ventas', 'productos_cardex', null, "id_producto = '$id_producto' AND operacion='venta' AND id_almacen='$id_almacen'");
        $salida = $this->db->select('SUM(cantidad) as salidas', 'productos_cardex', null, "id_producto = '$id_producto' AND operacion='salida' AND id_almacen='$id_almacen'");
        
        $stock_almacen = ( (int) $compra[0]['compras'] + (int) $entrada[0]['entradas'] + (int) $devolucion[0]['devoluciones'] ) - ( (int) $venta[0]['ventas'] + (int) $salida[0]['salidas'] );
        
        return $stock_almacen;
    }
    
    /**
     * Obtiene los costos de un producto por proveedor
     *
     * @return array
    */
    public function costosProducto() {
        
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            return false;
        }
        
        global $http;
        $id = intval($http->request->get('id'));            // id del producto
        $producto = $this->productoNi($id);                   // obtener datos del producto por su id
        
        $costos = $this->db->select(
            "pp.costo, pp.precio, pp.descuento, DATE_FORMAT(pp.fecha, '%d-%m-%Y') fecha, p.proveedor", 
            "producto_descuento_provedor pp", 
            'INNER JOIN proveedores p ON pp.id_proveedor=p.id_proveedor', 
            "id_producto = '$id'", 
            null,
            "ORDER BY pp.id_pp DESC");
        
        $precioOferta = '';
        if($producto['precioOferta'] != 0 || $producto['descuentoOferta'] != 0){
            $precioOferta = '
                <tr>
                    <th colspan = "2" class="text-right">
                        Precio ofertado en línea:
                    </th>
                    <th colspan = "3">
                        <span class="badge bg-yellow">'.$producto['precioOferta'].'</span> ( -'.$producto['descuentoOferta'].'% )
                    </th>
                </tr>';
        }
        
        $tbody = '
            <tbody>
                <tr>
                    <td colspan = "5" class="text-center">
                        '.$producto['codigo'].' - '.$id.' <h4>'.$producto['producto'].' <small>'.$producto['leyenda'].'</small></h4>
                    </td>
                </tr>
                <tr>
                    <th colspan = "2" class="text-right">
                        Precio de compra:
                    </th>
                    <th colspan = "3">
                        <span class="badge badge-default">'.$producto['precio_compra'].'</span>
                    </th>
                </tr>
                <tr>
                    <th colspan = "2" class="text-right">
                        Precio de venta:
                    </th>
                    <th colspan = "3">
                        <span class="badge bg-blue">'.$producto['precio'].'</span>
                    </th>
                </tr>
                '.$precioOferta.'';
                $tbody .= "
                <tr class='text-center bg-navy text-white font-weight-bold'>
                    <td>Proveedor</td>
                    <td>Costo</td>
                    <td>Precio</td>
                    <td>Descuento</td>
                    <td>Fecha</td>
                </tr>";
        if($costos){
            foreach ($costos as $key => $value) {
                $tbody .= "
                <tr class='text-center'>
                    <td>".$value['proveedor']."</td>
                    <th class='text-center'>".$value['costo']."</th>
                    <td>".$value['precio']."</td>
                    <th class='text-center'>".$value['descuento']."</th>
                    <td>".$value['fecha']."</td>
                </tr>"; 
            }
        }else{
            $tbody .= "
                <tr>
                    <td colspan = '5' class='text-center'>No hay registro de costos.</td>
                </tr>"; 
        }
        
        return array('infoCostos' => $tbody);
    }

    /**
     * Obtiene la información del stock de un producto en cada almacén
     *
     * @return array
    */
    public function stockProducto() {
        
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            return false;
        }
        
        global $http, $config;
        $paginaWeb = $config['build']['urlAssetsPagina'];
        
        $id = intval($http->request->get('id'));            // id del producto
        $producto = $this->productoNi($id);                 // obtener datos del producto por su id
        $tbody = '';                                        // creamos tbody stock por almacen
        $tbodyumov = '';                                    // creamos tbody ultimos 10 movimientos
        
        if($producto){                                      // si el producto existe
            
            // TBODY STOCK POR ALMACEN                  ------------------------ TBODY STOCK POR ALMACEN
            $imagen = $producto["imagen"];
            if($imagen == 'assets/plantilla/img/productos/default/default.jpg'){
                $imagen = '<img width="50px" src="'.$paginaWeb.$imagen.'?'.time().'"><br><br>';
            }else{
                $imagen = '<img width="50px" src="'.$paginaWeb.$imagen.'?'.time().'"><br><br>';
            }
            $tbody .= '
            <tbody>
                <tr class="font-weight-bold">
                    <th colspan = "2" class="text-center">
                        '.$imagen.'
                        '.$producto['codigo'].' - '.$id.' <h4>'.$producto['producto'].' <small>'.$producto['leyenda'].'</small></h4>
                    </th>
                </tr>';
            $almacenes = (new Model\Almacenes)->almacenes();
            $stock_almacen_total = 0;
            foreach ($almacenes as $key => $value) {
                $almacen = $value['almacen'];
                $stock_almacen = $this->stock_producto($id, $value['id_almacen']);  
                $stock = $this->colorear_stock($producto['stock_minimo'], $stock_almacen);
                $tbody .= "
                <tr>
                    <td><span class='font-weight-bold text-blue'>AL-".$value['id_almacen']."</span> $almacen</td>
                    <td class='text-center font-weight-bold'>$stock</td>
                </tr>"; 
                $stock_almacen_total += $stock_almacen;
            }
            $tbody .= '
                <tr>
                    <td class="text-right">Stock</td>
                    <td class="text-center font-weight-bold">'.$this->colorear_stock($producto['stock_minimo'], $stock_almacen_total).'</td>
                </tr>
            </tbody>';
            
            // TBODY ULTIMOS 10 MOVIMIENTOS                  ------------------- TBODY MOVIMIENTOS
            $ultimos_movimientos = $this->db->select('*', 'productos_cardex', null, "id_producto = '$id'", null, "ORDER BY id_cardex ASC");
            $tbodyumov .= '
            <tbody>
                <tr class="bg-navy text-white">
                    <th class="text-center">Movimiento</th>
                    <th></th>
                    <th class="text-center">Cantidad</th>
                    <th></th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Referencia</th>
                    <th class="text-center">Fecha</th>
                </tr>';
            $cantidad_mov = 0;
            $stock = 0;
            if($ultimos_movimientos){
                foreach ($ultimos_movimientos as $key => $value) {
                    switch($value['movimiento']){
                        case 'ce':
                            $tipoM = 'Entrada (compra)';
                            $colorM = 'bg-purple';
                            $stock += $value['cantidad'];
                            $operacion = "<i class='fas fa-plus'></i>";
                            break;
                        case 'ae':
                            $tipoM = 'Entrada (ajuste)';
                            $colorM = 'bg-purple';
                            $stock += $value['cantidad'];
                            $operacion = "<i class='fas fa-plus'></i>";
                            break;
                        case 'ce':
                            $tipoM = 'Entrada (consignación)';
                            $colorM = 'bg-purple';
                            $stock += $value['cantidad'];
                            $operacion = "<i class='fas fa-plus'></i>";
                            break;
                        case 'vs':
                            $tipoM = 'Salida (venta)';
                            $colorM = 'bg-aqua';
                            $stock -= $value['cantidad'];
                            $operacion = "<i class='fas fa-minus'></i>";
                            break;
                        case 'cs':
                            $tipoM = 'Salida (consignación)';
                            $colorM = 'bg-aqua';
                            $stock -= $value['cantidad'];
                            $operacion = "<i class='fas fa-minus'></i>";
                            break;
                        case 'as':
                            $tipoM = 'Salida (ajuste)';
                            $colorM = 'bg-aqua';
                            $stock -= $value['cantidad'];
                            $operacion = "<i class='fas fa-minus'></i>";
                            break;
                        case 'df':
                            $tipoM = 'Entrada (venta cancelada)';
                            $colorM = 'bg-aqua';
                            $stock += $value['cantidad'];
                            $operacion = "<i class='fas fa-plus'></i>";
                            break;
                        case 'di':
                        case 'dc':
                            $tipoM = 'Entrada (venta devolución)';
                            $colorM = 'bg-aqua';
                            $stock += $value['cantidad'];
                            $operacion = "<i class='fas fa-plus'></i>";
                            break;
                        case 'crs':
                            $tipoM = 'Salida (crédito)';
                            $colorM = 'bg-orange';
                            $stock -= $value['cantidad'];
                            $operacion = "<i class='fas fa-minus'></i>";
                            break;
                        case 'crdf':
                            $tipoM = 'Entrada (crédito cancelado)';
                            $colorM = 'bg-orange';
                            $stock += $value['cantidad'];
                            $operacion = "<i class='fas fa-plus'></i>";
                            break;
                        case 'crdc':
                            $tipoM = 'Entrada (crédito devolución)';
                            $colorM = 'bg-orange';
                            $stock += $value['cantidad'];
                            $operacion = "<i class='fas fa-plus'></i>";
                            break;
                        default:
                            $tipoM = 'No definido';
                            $colorM = 'bg-grey';
                            $operacion = "";
                            break;
                    }
                    
                    $time = strtotime($value['fecha']);
                    $timeUnaHora = $time + 3600;
                    $hoy = date('Y-m-d H:i:s');
                    $f_hoy = Helper\Functions::fecha($hoy);
                    $f_ven = Helper\Functions::fecha($value['fecha']);
                    $f1 = substr($f_hoy,0,-17);
                    $f2 = substr($f_ven,0,-17);
                    if(time() < $timeUnaHora){
                        $fecha = Helper\Strings::amigable_time(strtotime($value['fecha']));
                    }elseif(substr($hoy, 0, -9) == substr($value['fecha'], 0, -9)){
                        $fecha = str_replace($f2, 'Hoy ', $f_ven);
                    }else{
                        $fecha = Helper\Functions::fecha($value['fecha']);
                    }
                    
                    if($value['descuento'] == '' || $value['descuento'] === NULL){
                        $descuento = '0.00';
                    }else{
                        $descuento = $value['descuento'];
                    }
                
                    $tbodyumov .= '
                    <tr>
                        <td class="text-center '.$colorM.' font-weight-bold">'.$tipoM.'</td>
                        <td class="text-center">'.$operacion.'</td>
                        <td class="text-center"><span class="label bg-black">'.$value['cantidad'].'</span></td>
                        <td class="text-center"><i class="fas fa-equals"></i></span>
                        <td class="text-center"><span class="label bg-black">'.$stock.'</span></td>
                        <td class="text-center">'.$value['referencia'].'</td>
                        <td class="text-center">'.$fecha.'</td>
                    </tr>';
                }
            $cantidad_mov = count($ultimos_movimientos);
            }else{
                $tbodyumov .= '
                <tr>
                    <td colspan="5" class="text-center">No hay movimientos registrados</td>
                </tr>';
            }
            $tbodyumov .= '
            </tbody>';
        }
        return array('infoStock' => $tbody, 'ultimos_movimientos' => $tbodyumov, 'cant_mov' => $cantidad_mov, 'id_producto' => $id);
    }
    
    public function movimientosProducto(){
        
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            return false;
        }
        
        global $http;
        
        $id = intval($http->request->get('key'));
        $movimientos = $this->db->select('*', 'productos_cardex', null, "id_producto = '$id'", null, "ORDER BY id_cardex ASC");
        $tbody = ''; 
        
        if($movimientos){
            $tbody .= '
            <tbody>
                <tr class="bg-navy text-white">
                    <th class="text-center">Movimiento</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Almacén</th>
                    <th class="text-center">Referencia</th>
                    <th class="text-center">Fecha</th>
                </tr>';
                
            foreach($movimientos as $key => $value) {
                switch($value['movimiento']){
                    case 'ce':
                        $tipoM = 'Entrada (compra)';
                        $colorM = 'bg-purple';
                        break;
                    case 'ae':
                        $tipoM = 'Entrada (ajuste)';
                        $colorM = 'bg-purple';
                        break;
                    case 'ce':
                        $tipoM = 'Entrada (consignación)';
                        $colorM = 'bg-purple';
                        break;
                    case 'vs':
                        $tipoM = 'Salida (venta)';
                        $colorM = 'bg-aqua';
                        break;
                    case 'cs':
                        $tipoM = 'Salida (consignación)';
                        $colorM = 'bg-aqua';
                        break;
                    case 'as':
                        $tipoM = 'Salida (ajuste)';
                        $colorM = 'bg-aqua';
                        break;
                    case 'df':
                        $tipoM = 'Entrada (venta cancelada)';
                        $colorM = 'bg-aqua';
                        break;
                    case 'di':
                    case 'dc':
                        $tipoM = 'Entrada (venta devolución)';
                        $colorM = 'bg-aqua';
                        break;
                    case 'crs':
                        $tipoM = 'Salida (crédito)';
                        $colorM = 'bg-orange';
                        break;
                    case 'crdf':
                        $tipoM = 'Entrada (crédito cancelado)';
                        $colorM = 'bg-orange';
                        break;
                    case 'crdc':
                        $tipoM = 'Entrada (crédito devolución)';
                        $colorM = 'bg-orange';
                        break;
                    default:
                        $tipoM = 'No definido';
                        $colorM = 'bg-grey';
                        break;
                }
                
                $time = strtotime($value['fecha']);
                $timeUnaHora = $time + 3600;
                $hoy = date('Y-m-d H:i:s');
                $f_hoy = Helper\Functions::fecha($hoy);
                $f_ven = Helper\Functions::fecha($value['fecha']);
                $f1 = substr($f_hoy,0,-17);
                $f2 = substr($f_ven,0,-17);
                if(time() < $timeUnaHora){
                    $fecha = Helper\Strings::amigable_time(strtotime($value['fecha']));
                }elseif(substr($hoy, 0, -9) == substr($value['fecha'], 0, -9)){
                    $fecha = str_replace($f2, 'Hoy ', $f_ven);
                }else{
                    $fecha = Helper\Functions::fecha($value['fecha']);
                }
                
                if($value['descuento'] == '' || $value['descuento'] === NULL){
                    $descuento = '0.00';
                }else{
                    $descuento = $value['descuento'];
                }
            
                $tbody .= '
                <tr>
                    <td class="text-center '.$colorM.' font-weight-bold">'.$tipoM.'</td>
                    <td class="text-center"><span class="label bg-black">'.$value['cantidad'].'</span></td>
                    <td class="text-center"><span class="font-weight-bold text-blue">AL-'.$value['id_almacen'].'</span></td>
                    <td class="text-center">'.$value['referencia'].'</td>
                    <td class="text-center">'.$fecha.'</td>
                </tr>';
            }
            
            return array('status' => 'success', 'todos_movimientos' => $tbody, 'id_producto' => $id);
        }else{
            return array('status' => 'error');
        }
    }

    public function colorear_stock($stock_minimo, $stock) {

        $stock_minimo_medio = $stock_minimo/2;
        $stock_medio = $stock_minimo + $stock_minimo_medio;

        if($stock >= 0 && $stock <= $stock_minimo_medio){
            $resultado = '<span class="label bg-red text-white font-weight-bold">'.$stock.'</span>';
        }elseif($stock > $stock_minimo_medio && $stock <= $stock_minimo){
            $resultado = '<span class="label bg-orange text-white font-weight-bold">'.$stock.'</span>';
        }elseif($stock > $stock_minimo && $stock <= $stock_medio){
            $resultado = '<span class="label bg-yellow text-white font-weight-bold">'.$stock.'</span>';
        }elseif($stock > $stock_medio){
            $resultado = '<span class="label bg-green text-white font-weight-bold">'.$stock.'</span>';
        }
        return $resultado;
    }

    /**
     * Mostrar los productos en datatbles en el template productos
     * 
     * Origen productos.js 
     * 
     * @return json
    */ 
    public function mostrarProductos() {
        global $config;
        
        // Si el usuario no esta logeado
        if($this->id_user === NULL) { 
            return false;
        }
        
        # Obtener la url de multimedia
        $paginaWeb = $config['build']['urlAssetsPagina'];
        
        # Consultar productos
        $productos = $this->db->query("
                        SELECT p.id, p.codigo, p.producto, IF(p.leyenda IS NULL, '', p.leyenda) AS leyenda, p.ruta, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial, p.stock, 
                        p.stock_minimo, p.precio, p.precio_compra, (p.stock * p.precio) AS monto, (p.ventas + p.ventasMostrador) AS ventas, c.categoria, s.subcategoria, p.estado, 
                        IF(p.total_entradas > 0 OR p.total_salidas > 0, 'NO', 'SI') AS eliminar,
                        IF(p.imagen != 'assets/plantilla/img/productos/default/default.jpg', ' <sup>(img)</sup>', '') AS img, 
                        IF(p.liquidacion != 'SI', '', 'text-red') AS attr_liquidacion,  
                        IF(p.descontinuado != 'SI', '', '<sup class=\'text-red\'>descontinuado</sup>') AS descontinuado, 
                        IF(p.precioOferta != 0 OR p.descuentoOferta != 0, 'text-yellow', '') AS attr_oferta
                        FROM productos AS p 
                        INNER JOIN categorias AS c ON p.idCategoria = c.id
                        INNER JOIN subcategorias AS s ON p.idSubcategoria = s.id
                        INNER JOIN editoriales AS e ON p.id_editorial = e.id_editorial 
                        INNER JOIN productos_autores pa ON p.id = pa.id_producto
                        INNER JOIN autores a ON pa.id_autor = a.id_autor
                        GROUP BY p.id 
                        ORDER BY p.id DESC
                        ");
        
        $data = [];
        if($productos){
            
            // comentar
            // REGISTRAR ENTRADA
            /*
            $folio_entrada = strtoupper(uniqid());
            $fecha = date("Y-m-d");
            $hora = date("H:i:s");
            $this->db->insert('entrada', [
                'id_almacen' => 1,
                'folio' => $folio_entrada,
                'id_accion' => 1,
                'fecha' => $fecha,
                'hora' => $hora,
                'usuario_entrada' => 1
            ]);
            $totalCantidad = 0;
            */
            // comentar

                
            foreach ($productos as $key => $value) {
                
                // comentar
                /*
                $id = $value["id"];

                if($value['stock'] > 0){
                    
                    # REGISTRAR DETALLE DE ENTRADA
                    $this->db->insert('entrada_detalle', [
                        'id_entrada' => 1,
                        'id_producto' => $id,
                        'cantidad' => $value['stock'],
                        'costo' => $value["precio"],
                        'precio' => $value["precio"],
                        'estado' => 1
                    ]); 
                    # REGISTRAR CARDEX DE PRODUCTOS
                    $this->db->insert('productos_cardex', [
                        'id_producto' => $id,
                        'cantidad' => $value['stock'],
                        'id_almacen' => 1,
                        'id_clienteProveedor' => 1,
                        'costo' => $value["precio"],
                        'precio' => $value["precio"],
                        'operacion' => 'entrada',
                        'movimiento' => 'ae',                       # (ae) = ajuste entrada
                        'referencia' => $folio_entrada,
                        'fecha' => $fecha.' '.$hora
                    ]);
                    // ACTUALIZAR ENTRADAS
                    $this->db->update('productos',array(
                        'ventas' => 0,
                        'ventasMostrador' => 0,
                        'total_entradas' => $value['stock'],
                        'total_salidas' => 0
                    ),"id='$id'",1);
                    
                    $totalCantidad += $value['stock'];
                
                }
                */
                // comentar

                    
                $infoData = [];
                $infoData[] = $value["codigo"];                                                 // 0 codigo
                $infoData[] = $value["id"];                                                     // 1 id
                $infoData[] = '<span class="font-weight-bold"><a href="'.$paginaWeb.'libro/'.$value["ruta"].'" target="_blank" data-toggle="tooltip" data-placement="left" title="Abrir enlace" class="'.$value["attr_liquidacion"].'">'.$value["producto"].'</a></span> '.$value["leyenda"].$value["img"].$value["descontinuado"];          // 1 producto
                $infoData[] = $value["autores"];                                                // 2 autor
                $infoData[] = $value['editorial'];                                              // 3 editorial
                $infoData[] = '<span class="'.$value['attr_oferta'].'">'.$value["precio"].'</span>'; // 4 precio
                $infoData[] = $this->colorear_stock($value['stock_minimo'], $value['stock']);   // 5 stock
                $infoData[] = number_format($value["monto"],2);                                 // 6 monto
                $infoData[] = $value['ventas'];                                                 // 7 ventas 
                $infoData[] = '';                                                               // 8 opciones
                $infoData[] = $value['stock'];                                                  // 10 stock (existencia)            * oculto desde js
                $infoData[] = $value['estado'];                                                 // 11 estado                        * oculto desde js
                $infoData[] = $value['categoria'];                                              // 12 categoria                     * oculto desde js
                $infoData[] = $value['subcategoria'];                                           // 13 subcategoria                  * oculto desde js
                $infoData[] = $value['eliminar'];                                               // 14 control para boton eliminar   * oculto desde js
                
                $data[] = $infoData;
            }
            
            // comentar
            /*
            $this->db->update('proveedores',array(
                'compras' => $totalCantidad, 
                'fecha_ultima_compra' => $fecha.' '.$hora
            ),"id_proveedor='1'");
            */
            // comentar

        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    /**
     * Descontinua un producto
     *
     * @return array
    */ 
    public function descontinuarProducto() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            
            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador['control'] == 0){
               throw new ModelsException("No tienes permisos para realizar esta acción.");   
            }
            
            $id = intval($http->request->get('id'));

            $producto = $this->producto($id);
            if(!$producto){
                throw new ModelsException("El producto no existe.");  
            }
            
            if($producto["stock"] > 0){
                throw new ModelsException("El producto aun tiene {$producto["stock"]} en stock.");
            }

            $this->db->update('productos',array(
                'descontinuado' => 'SI', 
                'estado' => 0
            ),"id='$id'",1);

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

            (new Model\Actividad)->registrarActividad('Evento', 'Se descontinuo el producto '.$id.' '.$producto['producto'], $perfil, $administrador['id_user'], 6, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡DESCONTINUADO!', 'message' => 'El producto '.$id.' '.$producto['producto'].' se descontinuo.');
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Activa un producto
     *
     * 
     * @return array
    */ 
    public function activarProducto() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;     
            
            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador['control'] == 0){
               throw new ModelsException("No tienes permisos para realizar esta acción.");   
            }
            
            $id = intval($http->request->get('id'));

            $producto = $this->productoNi($id);
            if(!$producto){
                throw new ModelsException("El producto no existe.");  
            }
            
            $this->db->update('productos',array(
                'descontinuado' => 'NO', 
                'estado' => 1
            ),"id='$id'",1);

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

            (new Model\Actividad)->registrarActividad('Evento', 'Se activo el producto '.$id.' '.$producto['producto'], $perfil, $administrador['id_user'], 6, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡ACTIVADO!', 'message' => 'El producto '.$id.' '.$producto['producto'].' se activo nuevamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Poner producto en liquidacion
     *
     * SI | NO
     * 
     * @return array
    */ 
    public function liquidacion() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            
            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador['control'] == 0){
               throw new ModelsException("No tienes permisos para realizar esta acción.");   
            }

            $id = intval($http->request->get('id'));
            $liquidacion = $this->db->scape( trim($http->request->get('liquidacion')) );

            $producto = $this->producto($id);
            if(!$producto){
                throw new ModelsException("El producto no existe.");  
            }
            
            if($producto["estado"] != 1){
                throw new ModelsException("El producto no esta disponible");  
            }
            
            $this->db->update('productos',array('liquidacion' => $liquidacion),"id='$id'");

            if($liquidacion == 1){
                $liquidacion_txt = 'El producto se puso en liquidación';
            }else{
                $liquidacion_txt = 'El producto no está en liquidación';
            }

            return array('status' => 'success', 'title' => '¡Editado!', 'message' => $anticipo_txt);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Marcar el producto como novedad
     *
     * SI | NO
     * 
     * @return array
    */ 
    public function novedad() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;

            $id = intval($http->request->get('id'));
            $novedad = $this->db->scape( trim($http->request->get('novedad')) );

            $producto = $this->producto($id);
            if(!$producto){
                throw new ModelsException("El producto no existe.");  
            }
            
            if($producto["estado"] != 1){
                throw new ModelsException("El producto no esta disponible");  
            }
            
            $this->db->update('productos',array('novedad' => $novedad),"id='$id'");

            if($novedad == 1){
                $novedad_txt = 'El producto se agregó a las Novedades';
            }else{
                $novedad_txt = 'El producto se quitó de las Novedades';
            }

            return array('status' => 'success', 'title' => '¡Editado!', 'message' => $novedad_txt);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }

    /**
     * Cambia el valor del anticipo de un producto
     *
     * 0:sin anticipo | 1:con anticipo
     * 
     * @return array
    */ 
    public function anticipoProducto() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            
            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador['control'] == 0){
               throw new ModelsException("No tienes permisos para realizar esta acción.");   
            }

            $id = intval($http->request->get('id'));
            $anticipo = intval($http->request->get('anticipo'));

            $producto = $this->producto($id);
            if(!$producto){
                throw new ModelsException("El producto no existe.");  
            }
            
            if($producto["estado"] != 1){
                throw new ModelsException("El producto no esta disponible");  
            }
            
            $this->db->update('productos',array('pedido' => $anticipo),"id='$id'");

            if($anticipo == 1){
                $anticipo_txt = 'SI solicitar anticipo';
            }else{
                $anticipo_txt = 'NO solicitar anticipo';
            }

            return array('status' => 'success', 'title' => '¡Editado!', 'message' => $anticipo_txt);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }

    /**
     * Agrega un nuevo producto 
     * 
     * @return array
    */ 
    public function agregarProducto() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            
            # CODIGO            --------------------------------------------------------------------------------------------------- CODIGO
            $codigoP = $this->db->scape(trim($http->request->get('codigoP')));          # codigo del producto
            if($codigoP == "" || $codigoP === null){                                    # si el codigo viene vacio
                $codigoAleatorio = $this->generarCodigoProducto();                      # generar un codigo aleatorio
                $codigoP = $codigoAleatorio["codigo"];                                  # obtener el codigo generado
            }
            
            # LEYENDA           --------------------------------------------------------------------------------------------------- LEYENDA
            $leyenda = $this->db->scape(trim($http->request->get('leyenda')));  # leyenda del producto, texto adiconal que vaya con el título
            $leyenda = Helper\Strings::clean_string($leyenda);                  # limpiar leyenda
            
             # NOMBRE           --------------------------------------------------------------------------------------------------- NOMBRE
            $nombreP = $this->db->scape(trim($http->request->get('nombreP')));          # nombre del producto, si no viene sera (string "")
            if($nombreP == ''){                                                         # si es "", enviar error
                throw new ModelsException("El nombre del producto está vacío.");
            }
            $nombreP = Helper\Strings::clean_string(mb_strtoupper($nombreP, 'UTF-8'));  # limpiar nombre del producto y pasarlo a mayuscula
            # Consultar si el producto ya existe (codigo y nombre coinciden)
            $producto_existe = $this->db->select('*', 'productos', null, "codigo = '$codigoP' AND producto = '$nombreP' AND leyenda = '$leyenda'");
            # Si el producto ya existe, enviar error
            if($producto_existe){
                throw new ModelsException('El producto '.$nombreP.' '.$leyenda.' con el código '.$codigoP.' ya existe. Intente con otro nombre o cambie el código.');
            }
            $nombreCompleto = $nombreP.(($leyenda == '') ? '' : ' '.$leyenda); 
            $rutaP = Helper\Strings::url_amigable(mb_strtolower($nombreCompleto, 'UTF-8'));
            $rutaP = $rutaP.'-'.$codigoP;

            # EDITORIAL         --------------------------------------------------------------------------------------------------- EDITORIAL
            $editorial = (int) $http->request->get('editorial');                # editorial, si no vienen será (int 0) 
            if($editorial == 0){                                                # si es 0 asignar 1
                $editorial=1;
            }
            
            # AUTORES           --------------------------------------------------------------------------------------------------- AUTORES
            $autores = $this->db->scape($http->request->get('autores'));        # autores, si no vienen sera (string "")
            if($autores == "" || $autores === null || $autores == 0){           # si es "" asignar 1
                $autores=1;
            }
            
            # PRECIO COMPRA     --------------------------------------------------------------------------------------------------- PRECIO COMPRA
            $precioC = (real) $http->request->get('precioCompra');              # Precio de compra 
            if($precioC <= 0){
                throw new ModelsException("El precio de compra es necesario.");
            }
             
            # PRECIO VENTA      --------------------------------------------------------------------------------------------------- PRECIO VENTA
            $precioR = (real) $http->request->get('precio');                    # Precio de venta
            if($precioC > $precioR){
                throw new ModelsException("El precio de venta debe ser mayor o igual al precio de compra.");
            }
            
            # CATEGORIA         --------------------------------------------------------------------------------------------------- CATEGORIA
            $categoria = (int) $http->request->get('categoria');                # categoria, si no viene será (int 0)
            if($categoria == 0){                                                # si es 0, asignar 1
                $categoria = 1;
            }
            # Llamar el Modelo Categorias
            $c = new Model\Categorias;
            # Obtener los datos de la categoría por su id($categoria) 
            $categoriaAsignada = $c->categoria($categoria);
            # Si la categoria asignada no se encuentra en la BD
            if(!$categoriaAsignada){
                throw new ModelsException("La categoría seleccionada no es valida");
            }
            
            # SUBCATEGORIA      --------------------------------------------------------------------------------------------------- SUBCATEGORIA
            $subcategoria = (int) $http->request->get('subcategoria');          # subcategoria, si no viene será (int 0)
            if($subcategoria == 0){                                             # si es 0, asignar 1
                $subcategoria = 1;
            }
            # Llamar el Modelo Subcategorias
            $s = new Model\Subcategorias;
            # Obtener los datos de la subcategoría por su id($subcategoria) 
            $subcategoriaAsignada = $s->subcategoria($subcategoria);
            # Si la subcategoria asignada no se encuentra en la BD
            if(!$subcategoriaAsignada){
                throw new ModelsException("La subcategoría seleccionada no es valida");
            }
            
            # FICHA TECNICA     --------------------------------------------------------------------------------------------------- FICHA TECNICA
            # Obtener y convertir a array los valores de los detalles que vienen en tipo string
            $dn = json_decode($http->request->get('dn'),true);                  # dn = nombres de los detalles
            $dd = json_decode($http->request->get('dd'),true);                  # dd = datos a asignar a los nombres de los detalles
            $detalles = [];                                                     # Se crea el Array detalles
            $cont = 0;                                                          # Variable $cont servira para saber que clave del array $dd se va a comprar con la de array $dn
            # Se compara si el array $dn y $dd traen el mismo número de elementos, esto se hace como medida para evitar error si se altera 
            # el código javascript ya que de forma normal siempre vendran con la misma cantidad de elementos.
            if(count($dn) == count($dd)){
                # Recorrer Array $dn
                foreach ($dn as $key => $value) {
                    # Si el nombre del detalle o bien el valor de este vienen vacios
                    if($value == null || $value == ''){
                        unset($dn[$key]);
                        unset($dd[$key]);
                    # Si el valor del detalle viene vacio se asigna NO DEFINIDO
                    }elseif($dd[$cont] == null || $dd[$cont] == ''){
                        $dd[$key] = "NO DEFINIDO";
                    }else{
                        # Retira las etiquetas HTML y PHP de $value
                        $v = strip_tags($value);
                        # Reemplazamos todo lo que no sea alfanumerico o espacios por un espacio vacio ''
                        $v = trim(preg_replace('/[^[:alnum:][:space:]]/u', '', $v));
                        $v = mb_strtoupper($v, 'UTF-8');
                        # Sobreescribimos el valor escapado en la clave correspondiente
                        $dn[$key] = $v;
                    }
                    # Aumentamos en 1 $cont que es la clave siguiente
                    $cont++;
                }
                # Recorrer Array $dd
                foreach ($dd as $key2 => $value2) {
                    # Si $value2 es un array
                    if(is_array($value2)){
                        # Recorremos el array
                        foreach ($value2 as $key3 => $value3) {
                            # Retira las etiquetas HTML y PHP de $value3
                            $v3 = strip_tags($value3);
                            # Reemplazamos todo lo que no coinsida con el patrón por un espacio vació ''
                            $v3 = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s\.\-])', '', $v3);
                            $v3 = mb_strtoupper($v3, 'UTF-8');
                            # Sobreescribimos el valor escapado en la clave correspondiente al array dentro del array
                            $dd[$key2][$key3] = $v3;
                        }
                    # Si no entonces $value2 es un string
                    }else{
                        # Retira las etiquetas HTML y PHP de $value2
                        $v2 = strip_tags($value2);
                        # Reemplazamos todo lo que no coinsida con el patrón por un espacio vació ''
                        $v2 = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s\.\-])', '', $v2);
                        $v2 = mb_strtoupper($v2, 'UTF-8');
                        # Sobreescribimos el valor escapado en la clave correspondiente
                        $dd[$key2] = $v2;
                    }
                }
                # Se llena el Array detalles uniendo cada elemento con el valor de $dn y $dd
                for($i = 0; $i < count($dn); $i++) {
                    $detalles[$dn[$i]] = $dd[$i];
                }
            }else{
                throw new ModelsException('Se alteró la estructura en detalles.');
            }
            # Si el array detalles permanece vacio, detalles sera ''
            if(count($detalles) < 1){
                $detalles = '';
            }else{
                # Se convierte el Array $detalles en string para poder guardar en la base de datos
                $detalles = json_encode($detalles);
            }
            
            # STOCK MINIMO      --------------------------------------------------------------------------------------------------- STOCK MINIMO
            $stock_minimo = (int) $http->request->get('stock_minimo');          # Stock minimo, si no viene sera (int 0)
            if($stock_minimo == 0){                                             # Si es 0, asignar 3
                $stock_minimo = 3;
            }
            
            # DESCRIPCION       --------------------------------------------------------------------------------------------------- DESCRIPCION
            $desc = htmlspecialchars($http->request->get('desc'));              # Descripcion del producto
            if($desc == '' || $desc == null){ 
                $desc = "Sin descripción"; 
            }
           
            # PALABRAS CLAVE    --------------------------------------------------------------------------------------------------- PALABRAS CLAVE
            $pClave = $this->db->scape($http->request->get('pClave'));          # Palabras clave
            if($pClave == '' || $pClave == null){
                $pClave = $nombreP.' '.$leyenda.' ,'.$codigoP;                   
            }

            # Registrar cabeceras
            $this->db->insert('cabeceras', array(
                'ruta' => $rutaP,
                'titulo' => $nombreP,
                'descripcion' => $desc,
                'palabrasClave' => $pClave
            ));

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

            # Registrar productos
            $insertar = $this->db->insert('productos', array(
                "codigo" => $codigoP,
                "stock" => 0,
                "stock_minimo" => $stock_minimo,
                "autores" => $autores,
                "id_editorial" => $editorial,
                "idCategoria" => $categoria,
                "idSubcategoria" => $subcategoria,
                "ruta" => $rutaP,
                "producto" => $nombreP,
                "leyenda" => $leyenda,
                "descripcion" => $desc,
                "detalles" => $detalles,
                "precio_compra" => $precioC,
                "precio" => $precioR,
                
                "ofertadoPorCategoria" => 0,
                "ofertadoPorSubcategoria" => 0,
                "oferta" => 0,
                "precioOferta" => 0,
                "descuentoOferta" => 0,
                "finOferta" => '',
                
                "fechaRegistro" => date('Y-m-d H:i:s'),
                "usuarioRegistro" => $administrador['id_user'],
                "estado" => 1
            ));
            
            $arrayAutores = explode(",", $autores );
            foreach($arrayAutores as $key => $value){
                $this->db->insert('productos_autores', array(
                    "id_producto" => $insertar,
                    "id_autor" => $value
                ));
            }
            
            $redireccionar = 0;
            $entradas = (int) $http->request->get('entradas');                  # Entradas
            if($entradas > 0){
                (new Model\RegistrarCompras)->agregarListaCompra($entradas, $insertar);
                $redireccionar = 1;
            }

            # IMAGEN PRINCIPAL  --------------------------------------------------------------------------------------------------- IMAGEN PRINCIPAL
            $imagen = $http->files->get('imagen');                              # Imagen principal
            if($imagen === NULL || $imagen === null || $imagen == 'NULL' || $imagen == 'null' || $imagen == '') {
                $urlImagen = "assets/plantilla/img/productos/default/default.jpg";
                $traeImagen = false;
            }else{
                if(!Helper\Files::is_image($imagen->getClientOriginalName())){
                    throw new ModelsException('El formato del archivo no corresponde a una imagen.');   
                } else if($imagen->getClientSize()>200000){
                    throw new ModelsException('El tamaño de la imagen no debe superar los 200 KB.');
                } else {
                    $traeImagen = true;
                }
            }
            if($traeImagen){                                                    # Si trae imagen principal del producto
                list($ancho, $alto) = getimagesize($imagen->getRealPath());
                $nuevoAncho = 350;
                $nuevoAlto = ($alto * $nuevoAncho) / $ancho;
                
                $directorio = self::URL_ASSETS_WEBSITE."assets/plantilla/img/productos";
                if(!file_exists($directorio)){
                    mkdir($directorio, 0755);
                }
                $nombreArchivo = "assets/plantilla/img/productos/".$insertar;
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                # Si la imagen es jpg 
                if ($imagen->getMimeType() == "image/jpeg") {
                    $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.jpg';
                    $urlImagen = $nombreArchivo.'.jpg';
                    $origen = imagecreatefromjpeg($imagen->getRealPath());
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagejpeg($destino, $ruta, 100);
                # Si la imagen es png
                } else if($imagen->getMimeType() == "image/png") {
                    $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.png';
                    $urlImagen = $nombreArchivo.'.png';
                    $origen = imagecreatefrompng($imagen->getRealPath());
                    # Conservar transparencias
                    imagealphablending($destino, FALSE);
                    imagesavealpha($destino, TRUE);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagepng($destino, $ruta, 9);    
                }  
            }

            $this->db->update('productos', array(
                "imagen" => $urlImagen,
            ), "id='$insertar'",1);

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de producto '.$insertar.' '.mb_strtoupper($nombreP, 'UTF-8'), $perfil, $administrador['id_user'], 6, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Registrado!', 'message' => 'El producto '.mb_strtoupper($nombreP, 'UTF-8').' se registró correctamente.', 'redireccionar' => $redireccionar);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de un producto para cargarla en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerProducto() : array {
        try {
            
            // Si el usuario no esta logeado
            if($this->id_user === NULL) {                                      
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http, $config;
    
            $paginaWeb = $config['build']['urlAssetsPagina'];
            
            // id recibido
            $id = intval($http->request->get('id'));
            // Consultar producto por id
            $producto = $this->productoNi($id);
            // Datos de usuario actual
            $administrador = (new Model\Users)->getOwnerUser();
            
            // Si el producto existe
            if($producto) {
                
                // id
                $id = $producto["id"];
                // Codigo
                $codigoP = $producto["codigo"];
                // Producto
                $nombreP = $producto["producto"];
                // Leyenda
                $leyenda = $producto["leyenda"];
                // Stock minimo
                $stock_minimo = $producto["stock_minimo"];    
                // Autores
                $arrayAutores = explode(",", $producto["autores"] );
                // Costo
                $precioCompra = (real) $producto["precio_compra"];
                // Precio
                $precio = (real) $producto["precio"];
                // Categoria
                $idC = $producto["idCategoria"];
                $c = new Model\Categorias;
                if($idC != 0){
                    $categoria = $c->categoria($idC);
                }
                $categorias = $c->categorias();
                // Subcategoria
                $idS = $producto["idSubcategoria"];
                $s = new Model\Subcategorias;
                if($idS != 0){
                    $subcategoria = $s->subcategoria($idS);
                }
                $subcategorias = $s->subcategorias($idC);
                // Detalles
                $detalles = $producto["detalles"];
                // Descripcion
                $desc = $producto["descripcion"];
                // Palabras clave
                $cabecera = $this->db->select("*","cabeceras",null,"ruta='".$producto["ruta"]."'",1);
                if($cabecera){
                    $idCb = $cabecera[0]["id"];
                    $pClave = $cabecera[0]["palabrasClave"];
                }else{
                    $idCb = 0;
                    $pClave = "";
                }
                // Imagen
                $imagen = $producto["imagen"];
                
                // Datos de oferta
                $oferta = $producto["oferta"];
                $ofertaC = $producto["ofertadoPorCategoria"];
                $ofertaS = $producto["ofertadoPorSubcategoria"];
                $ofertaEd = $producto["ofertadoPorEditorial"];
                $ofertaAu = $producto["ofertadoPorAutor"];
                $oPrecio = $producto["precioOferta"];
                $oPorcentaje = $producto["descuentoOferta"];
                $oFecha = substr($producto["finOferta"], 0, -9);
    
                $formulario = "";
                $formulario .= ' 
                <input type="hidden" name="idP" id="idP" value="'.$id.'">
                <input type="hidden" name="idCb" id="idCb" value="'.$idCb.'">
    
                <div class="row">
    
                    <div class="col-xs-12 col-md-6">
    
                        <div class="form-group" data-toggle="tooltip" title="Código de barras">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-barcode"></i>
                                </span>
                                <input type="text" class="form-control" id="eCodigoP" name="eCodigoP" placeholder="Código de barras" value="'.$codigoP.'" autocomplete="off">
                            </div>
                        </div>
    
                        <div class="form-group" data-toggle="tooltip" title="Nombre del producto">
                            <div class="input-group">
                                <span class="input-group-addon text-red">
                                    <i class="fas fa-book"></i>
                                </span>
                                <input type="text" class="text-uppercase form-control eValidarP" name="eNombreP" id="eNombreP" value="'.$nombreP.'" placeholder="Nombre del producto" autocomplete="off">
                            </div>
                        </div>
                                        
                        <div class="form-group" data-toggle="tooltip" title="Leyenda adicional">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <input type="text" class="form-control" id="eLeyenda" name="eLeyenda" placeholder="Leyenda adicional" value="'.$leyenda.'" autocomplete="off">
                            </div>
                        </div>
    
                        <div class="form-group" data-toggle="tooltip" title="Selecciona editorial">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-building"></i>
                                </span>
                                <select class="form-control js-example-placeholder-single js-states eSeleccionarEditorial" name="eEditorial" id="eEditorial" style="width: 100%;" lang="es" data-placeholder="Editorial" data-allow-clear="true">';
                                    $formulario .= '
                                    <optgroup label="Editoriales disponibles">';
                                    $editoriales =  $this->db->select('*','editoriales', null, "id_editorial != 1");
                                    foreach ($editoriales as $key => $value) {
                                        if($value["id_editorial"] == $producto["id_editorial"]){
                                            $formulario .= '<option value="'.$value["id_editorial"].'" selected="selected">'.$value["editorial"].'</option>';
                                        }else{
                                            $formulario .= '<option value="'.$value["id_editorial"].'">'.$value["editorial"].'</option>';
                                        }
                                    }
                                    $formulario .= '
                                    </optgroup>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-default eagregarEditorial" type="button"><i class="far fa-plus-square"></i></button>
                                </span>	
                            </div>
                        </div>
    
                        <div class="diveAgregarEditorial hidden" style="margin-top:-13px; margin-bottom:50px;">
                            <div class="box box-default box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Agregar editorial</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group" style="margin:0;" data-toggle="tooltip" title="Nombre de la nueva editorial">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fas fa-building"></i>
                                            </span>
                                            <input type="text" class="form-control text-uppercase evalidarEditorial" id="enuevaEditorial" placeholder="Nombre de la editorial" autocomplete="off">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-block" id="eregistrarEditorial"><i class="fas fa-chevron-right"></i></button>
                                            </span>
                                        </div>
                                    </div>																													
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group" data-toggle="tooltip" title="Selecciona Autor o autores">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-user-tie"></i>
                                </span>
                                <select class="form-control js-example-placeholder-single select2-selection--multiple js-states eSeleccionarAutores" name="eAutores[]" id="eAutores" lang="es" multiple="multiple" data-placeholder="Autor(es)" style="width: 100%; max-width: 300px;" >';
                                    $formulario .= '
                                    <optgroup label="Autores disponibles">';
                                    $autores =  $this->db->select('*','autores', null, "id_autor != 1");
                                    foreach ($autores as $key => $value) {
                                        if(in_array($value['id_autor'], $arrayAutores)) {
                                            $formulario .= '<option value="'.$value["id_autor"].'" selected="selected">'.$value["autor"].'</option>';
                                        }else{
                                            $formulario .= '<option value="'.$value["id_autor"].'">'.$value["autor"].'</option>';
                                        }
                                    }
    
                                    $formulario .= '
                                    </optgroup>
                                </select>	
                                <span class="input-group-btn">
                                    <button class="btn btn-default eagregarAutor" type="button"><i class="far fa-plus-square"></i></button>
                                </span>	
                            </div>
                        </div>
    
                        <div class="diveAgregarAutor hidden" style="margin-top:-13px; margin-bottom:50px;">
                            <div class="box box-default box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Agregar autor</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group" style="margin:0;" data-toggle="tooltip" title="Nombre del nuevo autor">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fas fa-user-tie"></i>
                                            </span>
                                            <input type="text" class="form-control text-uppercase evalidarAutor" id="enuevoAutor" placeholder="Nombre del autor" autocomplete="off">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-block" id="eregistrarAutor"><i class="fas fa-chevron-right"></i></button>
                                            </span>
                                        </div>
                                    </div>																													
                                </div>
                            </div>
                        </div>
                                        
                        <div class="row">
    
                            <div class="col-xs-12 col-sm-6 form-group" data-toggle="tooltip" title="Precio de compra">
                                <div class="input-group">
                                    <span class="input-group-addon text-red"><i class="fa fa-dollar"></i></span>
                                    <input type="text" class="form-control" id="ePrecioCompra" name="ePrecioCompra" placeholder="Precio de compra" value="'.$precioCompra.'" autocomplete="off">
                                </div>
                            </div>
    
                            <div class="col-xs-12 col-sm-6 form-group" data-toggle="tooltip" title="Precio de venta">
                                <div class="input-group">
                                    <span class="input-group-addon text-red"><i class="fa fa-dollar"></i></span>
                                    <input type="text" class="form-control" id="ePrecio" name="ePrecio" placeholder="Precio de venta" value="'.$precio.'" autocomplete="off">
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-6 form-group" data-toggle="tooltip" title="Stock mínimo">
                                <div class="input-group stock">
                                    <span class="input-group-addon">
                                        <i class="fas fa-sort-numeric-down"></i>
                                    </span>
                                    <input type="text" class="form-control text-right" id="eStock_minimo" name="eStock_minimo" placeholder="Stock mínimo" value="'.$stock_minimo.'" autocomplete="off">
                                </div>
                            </div>
    
                        </div>
                                        
                        <div class="form-group" data-toggle="tooltip" title="Selecciona categoría">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <select class="form-control js-example-placeholder-single js-states eSeleccionarCategoria" name="eCategoria" id="eCategoria" style="width: 100%;" lang="es" data-placeholder="Categoría" data-allow-clear="true">';
                                    if($idC == 0){
                                        $formulario .= '
                                        <option value="0" selected="selected">Sin categoría</option>';
                                    }else{
                                        $formulario .= '
                                        <option value="0">Sin categoría</option>';
                                    }
                                    $formulario .= '
                                    <optgroup label="Categorías disponibles">';
                                    foreach ($categorias as $key => $value) {
                                        if($value["id"] == $idC){
                                            $formulario .= '<option value="'.$value["id"].'" selected="selected">'.$value["categoria"].'</option>';
                                        }else{
                                            $formulario .= '<option value="'.$value["id"].'">'.$value["categoria"].'</option>';
                                        }
                                    }
                                    $formulario .= '
                                    </optgroup>
                                </select>
                            </div>
                        </div>            
    
                        <div class="form-group" data-toggle="tooltip" title="Selecciona subcategoría">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <select class="form-control js-example-placeholder-single js-states eSeleccionarSubcategoria" name="eSubcategoria" id="eSubcategoria" style="width: 100%;" lang="es" data-placeholder="Subcategoría" data-allow-clear="true">';
                                    if($idS == 0){
                                        $formulario .= '
                                        <option value="0" selected="selected">Sin subcategoría</option>';
                                    }else{
                                        $formulario .= '
                                        <option value="0">Sin subcategoría</option>';
                                    }
                                    if($subcategorias){
                                        $formulario .= '
                                        <optgroup label="Subcategorías disponibles">';
                                        foreach ($subcategorias as $key => $value) {
                                            if($value["id"] == $idS){
                                                $formulario .= '<option value="'.$value["id"].'" selected="selected">'.$value["subcategoria"].'</option>';
                                            }else{
                                                $formulario .= '<option value="'.$value["id"].'">'.$value["subcategoria"].'</option>';
                                            }
                                        }
                                        $formulario .= '
                                        </optgroup>';
                                    }
                                    $formulario .= '
                                </select>
                            </div>
                        </div>';
                                        
                        $formulario .= '
                        <div class="panel panel-default">
    
                            <div class="panel-body addContent">
    
                                <div id="eGrupoDetalles">
                                    <div class="row">
                                        <div class="col-xs-7">
                                            <h2 style="margin:0;"><small>Ficha técnica</small></h2>
                                        </div>
                                        <div class="col-xs-5">
                                            <input type="button" class="btn btn-sm btn-default btn-block" value="Agregar" id="eAgregar">
                                        </div>
                                        <div class="col-xs-7"></div>
                                        <div class="col-xs-5">
                                            <input type="button" class="btn btn-sm btn-danger btn-block" value="Quitar" id="eQuitar">
                                        </div>
                                    </div>';
                                    if($detalles != '' && $detalles !== null){
                                        $detalles = json_decode($detalles,true);
                                        $cantDetalles = count($detalles);
                                        $cont = 1;
                                        foreach ($detalles as $key => $value) {
                                            if($cont <= 3){
                                                $readonly = "readonly";
                                                $placeholder1 = $key;
                                                $placeholder2 = "Especificar ".$key;
                                            }else{
                                                $readonly = "";
                                                $placeholder1 = "Característica";
                                                $placeholder2 = "Información";
                                            }
                                            if(is_array($value)){
                                                $value = json_encode($value);
                                                $reemplazar = ["[","]",'"', "'"];
                                                $value = str_replace($reemplazar, "", $value);
                                            }
                                            $formulario .= '
                                            <div class="eDetalle" id="eDetalle'.$cont.'">
                                                <div class="row">
                                                    <hr>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" name="edn[]" class="dn form-control" placeholder="'.$placeholder1.'" value="'.$key.'" '.$readonly.' autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" name="edd[]" class="dd form-control" placeholder="'.$placeholder2.'" value="'.$value.'" autocomplete="off">
                                                        </div>
                                                    </div>                                                          
                                                </div>
                                            </div>';
                                            $cont++;
                                        }
                                    }else{
                                        $formulario .= '
                                        <div class="eDetalle" id="eDetalle1">
                                                <div class="row">
                                                    <hr>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" name="edn[]" class="text-uppercase dn form-control" placeholder="Nombre de la característica" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" name="edd[]" class="text-uppercase dd form-control" placeholder="Información de la característica" autocomplete="off">
                                                        </div>
                                                    </div>															
                                                </div>
                                        </div>';
                                    }
                                        $formulario .= '
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group" data-toggle="tooltip" title="Descripción del producto">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <textarea class="form-control eDesc" rows="11" style="resize: none;" name="eDesc" id="eDesc" placeholder="Descripción" autocomplete="off">'.$desc.'</textarea>
                            </div>
                        </div>
    
                        <div class="form-group" data-toggle="tooltip" title="Palabras clave">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                            <i class="fas fa-tags"></i>
                                    </span>
                                    <input type="text" class="form-control epClave tagsinput" name="epClave" id="epClave" data-role="tagsinput" placeholder="Palabras clave (separadas por coma)" value="'.$pClave.'" autocomplete="off">
                                </div>
                        </div>
                            
                    </div>
    
                    <div class="col-xs-12 col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-body addContent">	
                                <div class="text-center">
                                    <label>Imagen principal</label>
                                    <div>
                                        <label for="eImagen" class="btn btn-sm btn-default btn-block"><i class="fa fa-upload"></i> Selecciona una imagen</label>
                                        <input type="file" name="eImagen" id="eImagen" style="visibility:hidden;" accept="image/*">
                                    </div>
                                    <p class="help-block" style="margin-top:-20px;">Tamaño recomendado <br>350px * 500px, peso máximo 5MB</p>
                                    <p><img src="'.$paginaWeb.$imagen.'?'.time().'" class="img-thumbnail ePrevisualizarImagen" style="width:200px; max-width:100%; height:auto"></p>
                                </div>
                            </div>
                        </div>';
                        
                    if($producto["descontinuado"] == 'SI'){
                        
                        $formulario .= '  
                            <div class="row">
                                <div class="col-xs-12 text-red font-weight-bold text-center">
                                    <span class="animated flash infinite">Producto descontinuado</span>
                                </div>
                                <br>
                                <br>
                                <div class="col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-4">
                                    <button type="button" class="btn bg-aqua btn-sm btn-block btn-flat font-weight-bold" id="activar" key="'.$id.'">ACTIVAR</button>
                                </div>
                            </div>';
                         
                    }else{
                        
                        //if($administrador['control'] == 1){ # - Si el usuario tiene control 1
                        
                            if($producto["pedido"] == 0){
                                $pedido = 1;
                                $attr = "";
                            }elseif($producto["pedido"] == 1){
                                $pedido = 0;
                                $attr = "checked";
                            }
                            
                            if($producto["liquidacion"] == 'NO'){
                                $attr2 = "";
                                $liquidacion = 'SI';
                            }elseif($producto["liquidacion"] == 'SI'){
                                $attr2 = "checked";
                                $liquidacion = 'NO';
                            }
                            
                            $fecha_registro = date_create($producto['fechaRegistro']);
                            $intervalo = Helper\Strings::date_difference( date_format($fecha_registro, 'd-m-Y'), date('d-m-Y') );
                            /*if($intervalo <= 30){*/
                                if($producto["novedad"] == 1){
                                    $attr3 = "checked";
                                    $novevad = 0;
                                }else{
                                    $attr3 = "";
                                    $novevad = 1;
                                }
                            /*}*/
                            
                            $formulario .= '  
                            <div class="row">
                                <div class="col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-4">
                                    <button type="button" class="btn btn-danger btn-sm btn-block btn-flat font-weight-bold" id="descontinuar" key="'.$id.'">Descontinuar</button>
                                </div>
                                <br><br><br>
                                <!--<div class="col-xs-12 text-center">
                                    <div class="checkbox" data-toggle="tooltip" title="Registra un ingreso en caja por el monto del anticipo">
                                        <label>
                                            <input type="checkbox" id="anticipo" '.$attr.' key="'.$id.'" value="'.$pedido.'"> Solicitar un anticipo al hacer pedidos
                                        </label>
                                    </div>
                                </div>-->
                                <div class="col-xs-12 text-center">
                                    <div class="checkbox" data-toggle="tooltip" title="Permite aplicar un descuento superior al 50%">
                                        <label>
                                            <input type="checkbox" id="liquidacion" '.$attr2.' key="'.$id.'" value="'.$liquidacion.'"> Liquidación en punto de venta
                                        </label>
                                    </div>
                                </div>';
                                
                                /*if($intervalo <= 30){*/
                                    $formulario .= '
                                    <div class="col-xs-12 text-center">
                                        <div class="checkbox" data-toggle="tooltip" title="Mostrar el libro como novedad en la página web">
                                            <label>
                                                <input type="checkbox" id="novedad" '.$attr3.' key="'.$id.'" value="'.$novevad.'"> Novedad
                                            </label>
                                        </div>
                                    </div>';
                                /*}*/
                                
                            $formulario .= '
                            </div>';
                        //}
                        
                    }
                    
                    $formulario .= '
                    </div>';
                    
                    if($oferta == 1){
                        $options = '
                            <option value="0">Sin oferta</option>
                            <option value="1" selected>Con oferta</option>
                        ';
                        $clase = '';
                        $clase2 = '';
                    }else if($oferta == 0) {
                        $options = '
                            <option value="0">Sin oferta</option>
                            <option value="1">Con oferta</option>
                        ';
                        if($precio == 0){
                            $clase = 'disabled';
                        }else{
                            $clase = '';
                        }
                        $clase2 = 'hidden';
                        $oPrecio = "";
                        $oPorcentaje = "";
                        $oFecha = "";
                    }
                    $finOfertaTime = strtotime($producto["finOferta"]);
                    $fecha = time();
                    if(($ofertaC != 0 || $ofertaS != 0 || $ofertaEd != 0 || $ofertaAu != 0) && $fecha < $finOfertaTime){
                        if($ofertaC != 0){
                            $ofertadoPor = 'Categoría';
                        }elseif($ofertaS != 0){
                            $ofertadoPor = 'Subcategoría';
                        }elseif($ofertaEd != 0){
                            $ofertadoPor = 'Editorial';
                        }elseif($ofertaAu != 0){
                            $ofertadoPor = 'Autor';
                        }
                        $formulario .= '
                        <div class="col-xs-12">
                            <hr>
                            <h4 class="text-center">OFERTA EXCLUSIVA EN LÍNEA</h4>
                            <p class="text-center">
                                El producto ya está ofertado por su '.$ofertadoPor.'
                            </p>
                            <input type="hidden" name="ofertaBD" id="ofertaBD" value="1">
                        </div>';
                    }else{
                        if($producto["descontinuado"] == 'NO'){
                            $formulario .= '
                            <div class="col-xs-12">
                                <hr>
                                <h4 class="text-center">OFERTA EXCLUSIVA EN LÍNEA</h4>
                            </div>
                            <div class="col-xs-12 col-md-6 col-md-offset-3">
                                <div class="form-group text-center">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fas fa-certificate text-yellow"></i>
                                        </span>
                                        <select class="form-control" name="eOferta" id="eOferta" '.$clase.'>
                                            '.$options.'
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="eOfertaDetalles '.$clase2.'">
                                <div class="col-xs-12 col-md-6 col-md-offset-3">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group" data-toggle="tooltip" title="Ofertar con un precio especifico">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-dollar"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="eoPrecio" name="eoPrecio" placeholder="Precio" value="'.$oPrecio.'" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group" data-toggle="tooltip" title="Ofertar con procentaje de descuento">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="eoPorcentaje" name="eoPorcentaje" placeholder="Porcentaje" value="'.$oPorcentaje.'"" autocomplete="off">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-percent"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="tooltip" title="Fecha de vigencia de la oferta">
                                            <input type="text" class="form-control datepicker" id="eoFecha" name="eoFecha" placeholder="Fin de la oferta" value="'.$oFecha.'" autocomplete="off">
                                            <span class="input-group-addon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        }
                    }
                    $formulario .= '
                </div>';
                return array('status' => 'success', 'formulario' => $formulario, 'cD' => $cantDetalles, 'id_libro_editar' => 'ID - '.$id);
            }else{
                throw new ModelsException("El producto no existe");  
            }
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }

    /**
     * Edita un producto
     * 
     * @return array
    */ 
    public function editarProducto() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;

            $id = intval($http->request->get('idP'));
            $idCb = intval($http->request->get('idCb'));
            
            # VALIDAR QUE EL PRODUCTO CON EL ID EXISTA
            $producto = $this->productoNi($id);
            if(!$producto){
                throw new ModelsException("El producto no existe.");
            }else{
                $cabecera = $this->db->select("*","cabeceras",null,"id='$idCb' AND ruta='".$producto["ruta"]."'",1);
                if(!$cabecera){
                    $insertarCabecera = true;
                }else{
                    $insertarCabecera = false;
                }
            }
            
            if($producto["descontinuado"] == 'SI'){
                throw new ModelsException("El producto está descontinuado.");
            }

            # CODIGO            --------------------------------------------------------------------------------------------------- CODIGO
            $codigoP = $this->db->scape(trim($http->request->get('codigoP')));
            if($codigoP == "" || $codigoP === null){
                $codigoAleatorio = $this->generarCodigoProducto();
                $codigoP = $codigoAleatorio["codigo"];
            }
            
            # LEYENDA           --------------------------------------------------------------------------------------------------- LEYENDA
            $leyenda = $this->db->scape(trim($http->request->get('leyenda')));
            $leyenda = Helper\Strings::clean_string($leyenda);
            
            # NOMBRE           --------------------------------------------------------------------------------------------------- NOMBRE
            $nombreP = $this->db->scape(trim($http->request->get('nombreP')));
            if($nombreP == ''){
                throw new ModelsException("El nombre del producto está vacío.");
            }
            $nombreP = Helper\Strings::clean_string(mb_strtoupper($nombreP, 'UTF-8'));  # limpiar nombre del producto y pasarlo a mayuscula
            $productoExistente = $this->db->select('*', 'productos', null, "codigo = '$codigoP' AND producto = '$nombreP' AND leyenda = '$leyenda' AND id != $id");
            if($productoExistente){
                throw new ModelsException('El producto '.$nombreP.' '.$leyenda.' con el código '.$codigoP.' ya existe. Intente con nombre o cambie el código.');
            }
            $nombreCompleto = $nombreP.(($leyenda == '') ? '' : ' '.$leyenda); 
            $rutaP = Helper\Strings::url_amigable(mb_strtolower($nombreCompleto, 'UTF-8'));
            $rutaP = $rutaP.'-'.$codigoP;

            # EDITORIAL         --------------------------------------------------------------------------------------------------- EDITORIAL
            $editorial = intval($http->request->get('editorial')); // int 0 
            if($editorial == 0){
                $editorial=1;
            }
            # Llamar el Modelo Editorial
            $e = new Model\Editoriales;
            # Obtener los datos de la editorial por su id($editorial) 
            $editorialAsignada = $e->editorial($editorial);
            # Si la editorial asignada no se encuentra en la BD
            if(!$editorialAsignada){
                throw new ModelsException("La editorial asignada no es valida");
            }

            # AUTORES           --------------------------------------------------------------------------------------------------- AUTORES
            $autores = $this->db->scape($http->request->get('autores')); // string ""
            if($autores == "" || $autores === null || $autores == 0){
                $autores=1;
            }
            
            # PRECIO COMPRA     --------------------------------------------------------------------------------------------------- PRECIO COMPRA 
            $precioC = (real) $http->request->get('precioCompra');
            if($precioC == 0){
                throw new ModelsException("El precio de compra es necesario.");
            }

            # PRECIO VENTA      --------------------------------------------------------------------------------------------------- PRECIO VENTA
            $precioR = (real) $http->request->get('precio');
            if($precioC > $precioR){
                throw new ModelsException("El precio de venta debe ser mayor o igual al precio de compra.");
            }

            # CATEGORIA         --------------------------------------------------------------------------------------------------- CATEGORIA
            $categoria = intval($http->request->get('categoria'));
            if($categoria == 0){
                $categoria = 1;
            }
            # Llamar el Modelo Categorias
            $c = new Model\Categorias;
            # Obtener los datos de la categoría por su id($categoria) 
            $categoriaAsignada = $c->categoria($categoria);
            # Si la categoria asignada no se encuentra en la BD
            if(!$categoriaAsignada){
                throw new ModelsException("La categoría asignada no es valida");
            }

            # SUBCATEGORIA      --------------------------------------------------------------------------------------------------- SUBCATEGORIA
            $subcategoria = intval($http->request->get('subcategoria'));
            if($subcategoria == 0){
                $subcategoria = 1;
            }
            # Llamar el Modelo Subcategorias
            $s = new Model\Subcategorias;
            # Obtener los datos de la subcategoría por su id($subcategoria) 
            $subcategoriaAsignada = $s->subcategoria($subcategoria);
            # Si la subcategoria asignada no se encuentra en la BD
            if(!$subcategoriaAsignada){
                throw new ModelsException("La subcategoría asignada no es valida");
            }
            
            # FICHA TECNICA     --------------------------------------------------------------------------------------------------- FICHA TECNICA
            # Obtener y convertir a array los valores de los detalles que vienen en tipo string
            $dn = json_decode($http->request->get('dn'),true);
            $dd = json_decode($http->request->get('dd'),true);
            # Se crea el Array detalles
            $detalles = [];
            # Variable $cont servira para saber que clave del array $dd se va a comprar con la de array $dn
            $cont = 0;
            # Se compara si el array $dn y $dd traen el mismo número de elementos, esto se hace como medida para evitar error si se altera 
            # el código javascript ya que de forma normal siempre vendran con la misma cantidad de elementos.
            if(count($dn) == count($dd)){
                # Recorrer Array $dn
                foreach ($dn as $key => $value) {
                    # Si el nombre del detalle o bien el valor de este vienen vacios
                    if($value == null || $value == ''){
                        unset($dn[$key]);
                        unset($dd[$key]);
                    }elseif($dd[$cont] == null || $dd[$cont] == ''){
                        $dd[$key] = "NO DEFINIDO";
                    }else{
                        # Retira las etiquetas HTML y PHP de $value
                        $v = strip_tags($value);
                        # Reemplazamos todo lo que no sea alfanumerico o espacios por un espacio vacio ''
                        $v = trim(preg_replace('/[^[:alnum:][:space:]]/u', '', $v));
                        $v = mb_strtoupper($v, 'UTF-8');
                        # Sobreescribimos el valor escapado en la clave correspondiente
                        $dn[$key] = $v;
                    }
                    # Aumentamos en 1 $cont que es la clave siguiente
                    $cont++;
                }
                # Recorrer Array $dd
                foreach ($dd as $key2 => $value2) {
                    # Si $value2 es un array
                    if(is_array($value2)){
                        # Recorremos el array
                        foreach ($value2 as $key3 => $value3) {
                            # Retira las etiquetas HTML y PHP de $value3
                            $v3 = strip_tags($value3);
                            # Reemplazamos todo lo que no coinsida con el patrón por un espacio vació ''
                            $v3 = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s\.\-])', '', $v3);
                            $v3 = mb_strtoupper($v3, 'UTF-8');
                            # Sobreescribimos el valor escapado en la clave correspondiente al array dentro del array
                            $dd[$key2][$key3] = $v3;
                        }
                    # Si no entonces $value2 es un string
                    }else{
                        # Retira las etiquetas HTML y PHP de $value2
                        $v2 = strip_tags($value2);
                        # Reemplazamos todo lo que no coinsida con el patrón por un espacio vació ''
                        $v2 = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s\.\-])', '', $v2);
                        $v2 = mb_strtoupper($v2, 'UTF-8');
                        # Sobreescribimos el valor escapado en la clave correspondiente
                        $dd[$key2] = $v2;
                    }
                }
                # Se llena el Array detalles uniendo cada elemento con el valor de $dn y $dd
                for($i = 0; $i < count($dn); $i++) {
                    $detalles[$dn[$i]] = $dd[$i];
                }
            }else{
                throw new ModelsException('Se alteró la estructura en detalles.');
            }
            if(count($detalles) < 1){
                $detalles = '';
            }else{
                # Se convierte el Array $detalles en string para poder guardar en la base de datos
                $detalles = json_encode($detalles);
            }

            # STOCK MINIMO      --------------------------------------------------------------------------------------------------- STOCK MINIMO
            $stock_minimo = (int) $http->request->get('stock_minimo');
            if($stock_minimo == 0){
                $stock_minimo = 3;
            }

            # DESCRIPCION       --------------------------------------------------------------------------------------------------- DESCRIPCION
            $desc = htmlspecialchars($http->request->get('desc'));
            if($desc == '' || $desc == null){
                $desc = "Sin descripción"; 
            }

            # PALABRAS CLAVE    --------------------------------------------------------------------------------------------------- PALABRAS CLAVE
            $pClave = $this->db->scape($http->request->get('pClave'));
            if($pClave == '' || $pClave == null){
                $pClave = $nombreP.' ,'.$codigoP;                     
            }

            # IMAGEN PRINCIPAL  --------------------------------------------------------------------------------------------------- IMAGEN PRINCIPAL
            $imagen = $http->files->get('imagen');
            if($imagen === NULL || $imagen === null || $imagen == 'NULL' || $imagen == 'null' || $imagen == '') {
                $urlImagen = $producto["imagen"];
                $traeImagen = false;
            }else{
                if(!Helper\Files::is_image($imagen->getClientOriginalName())){
                    throw new ModelsException('El formato del archivo no corresponde a una imagen.'); 
                } else if($imagen->getClientSize()>200000){
                    throw new ModelsException('El tamaño de la imagen no debe superar los 200 KB.');
                } else {
                    $traeImagen = true;
                }
            }
            # Si trae imagen principal del producto
            if($traeImagen){
                list($ancho, $alto) = getimagesize($imagen->getRealPath());
                $nuevoAncho = 350;
                $nuevoAlto = ($alto * $nuevoAncho) / $ancho;
                
                $directorio = self::URL_ASSETS_WEBSITE."assets/plantilla/img/productos";
                if(!file_exists($directorio)){
                    mkdir($directorio, 0755);
                }
                $nombreArchivo = "assets/plantilla/img/productos/".$id;

                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                # Si la imagen es jpg 
                if ($imagen->getMimeType() == "image/jpeg") {
                    $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.jpg';

                    $urlImagen = $nombreArchivo.'.jpg';
                    $origen = imagecreatefromjpeg($imagen->getRealPath());
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagejpeg($destino, $ruta, 100);
                # Si la imagen es png
                } else if($imagen->getMimeType() == "image/png") {
                    $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.png';

                    $urlImagen = $nombreArchivo.'.png';
                    $origen = imagecreatefrompng($imagen->getRealPath());
                    # Conservar transparencias
                    imagealphablending($destino, FALSE);
                    imagesavealpha($destino, TRUE);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagepng($destino, $ruta, 9);    
                }
            }

            # OFERTA            --------------------------------------------------------------------------------------------------- OFERTA
            $oferta = intval($http->request->get('oferta'));
            
            # OFERTA BD     --------------------------------------------------------------------------------------------------- OFERTA BD
            $ofertaBD = intval($http->request->get('ofertaBD'));

            # PRECIO OFERTA     --------------------------------------------------------------------------------------------------- PRECIO OFERTA
            $oPrecio = (real) $http->request->get('oPrecio');

            # DESCUENTO OFERTA  --------------------------------------------------------------------------------------------------- DESCUENTO OFERTA
            $oPorcentaje = (real) $http->request->get('oPorcentaje');

            # VIGENCIA OFERTA   --------------------------------------------------------------------------------------------------- VIGENCIA OFERTA
            $oFecha = $this->db->scape(trim($http->request->get('oFecha')));




            # OPCIONES DE LA OFERTA ----------------------------------------------------------------------------------------------- OPCIONES DE LA OFERTA

            # Variables de control de oferta
            $ofertaExistente = false;
            
            $ofertadoPorCategoria = 0;
            $ofertadoPorSubcategoria = 0;
            $ofertadoPorEditorial = 0;
            $ofertadoPorAutor = 0;

            # Si hay oferta por categoria
            if($categoriaAsignada["oferta"] == 1 || $producto["ofertadoPorCategoria"] == 1){
                $textoOfertaExistente = 'la categoría '.$categoriaAsignada["categoria"];
                $ofertaExistente = true;
            }
            
            # Si hay oferta por subcategoria
            if($subcategoriaAsignada["oferta"] == 1 || $producto["ofertadoPorSubcategoria"] == 1){
                $textoOfertaExistente = 'la subcategoría '.$subcategoriaAsignada["subcategoria"];
                $ofertaExistente = true;
            }
             
            # Si hay oferta por editorial  
            if($editorialAsignada["oferta"] == 1 || $producto["ofertadoPorEditorial"] == 1){
                $textoOfertaExistente = 'la editorial '.$editorialAsignada["editorial"];
                $ofertaExistente = true;
            }
            
            # Si hay oferta por autor 
            if($producto["ofertadoPorAutor"] == 1){
                $textoOfertaExistente = 'algun autor';
                $ofertaExistente = true;
            }
            
            # Si hay una oferta existente y el precio se intenta modificar
            if($ofertaExistente){
                if($precioR != $producto['precio']){
                    throw new ModelsException("El precio del producto no puede ser editado mientras este ofertado en línea.");
                }
            }
            
            # Si se desea poner en oferta individual
            if($oferta == 1){

                # Si hay una oferta existente
                if($ofertaExistente){
                    throw new ModelsException("El producto ya se encuentra ofertado por $textoOfertaExistente.");
                }

                # Si el precio real es menor o igual a 0 no se puede aplicar oferta
                if($precioR <= 0){
                    throw new ModelsException("Para aplicar oferta el producto debe tener precio.");
                }

                # Si no se recibe el valor a aplicar en la oferta, es decir, no trae ni precio ni descuento a aplicar
                if($oPrecio == 0 && $oPorcentaje == 0){
                    throw new ModelsException('Es necesario especificar un valor para la oferta.');
                }

                # Variables de precio y porcentaje de la oferta 
                $precio = $oPrecio;
                $porcentaje = $oPorcentaje; 

                # La oferta sera por porcentaje, es decir el producto se mostrara con -tanto porciento de descuento
                if($precio == 0){
                    # Si el porcentaje es igual o mayor a 100
                    if($porcentaje >= 100){
                        throw new ModelsException('El porcentaje de descuento debe ser menor a 100.');
                    }
                    # Se hace la operación para determinar el precio oferta según el porcentaje de descuento
                    $precioProducto = $precioR-($precioR*($porcentaje/100));
                    # Se asigna el porcentaje de descuento recibido
                    $porcentajeProducto = $porcentaje;
                # La oferta sera por precio, es decir el producto se mostrara con el precio ofertado
                }elseif($porcentaje == 0){
                    # Si el precio ofertado es mayor o igual al precio real
                    if($precio >= $precioR){
                        throw new ModelsException('El precio en la oferta debe ser menor al precio real del producto.');
                    }
                    # Se asigna el precio ofertado recibido
                    $precioProducto = $precio;
                    # Se hace la operación para determinar el porcentaje de descuento según el precio ofertado
                    $porcentajeProducto = 100-(($precio/$precioR)*100);
                # Si ambos valores vienen llenos es que ya tiene una oferta de tipo producto
                }elseif ($precio != 0 && $porcentaje != 0) {
                    $precioProducto = $precio;
                    $porcentajeProducto = $porcentaje;
                }

                # Si no se especifica la fecha en que terminara la oferta
                if($oFecha == ""){
                    throw new ModelsException('Es necesario especificar la fecha para el fin de la oferta.');
                } else {
                    $oFecha = $oFecha.' 23:59:59';
                }

                # Si $precioProducto(es decir el Precio ofertado) es mayor al precio real del producto
                # la oferta no aplica ya que el precio oferta debe ser menor al real  
                if($precioProducto > $precioR){
                    $oferta = 0;
                    $precioProducto = 0;
                    $porcentajeProducto = 0;
                    $oFecha = "";
                }
            
            # Si NO se desea poner en oferta individual
            }else{
                
                # Si hay una oferta existente, se toman los datos de la base
                if($ofertaExistente || $ofertaBD == 1){
                    
                    $ofertadoPorCategoria = $producto['ofertadoPorCategoria'];
                    $ofertadoPorSubcategoria = $producto['ofertadoPorSubcategoria'];
                    $ofertadoPorEditorial = $producto['ofertadoPorEditorial'];
                    $ofertadoPorAutor = $producto['ofertadoPorAutor'];
                    $oferta = $producto['oferta'];
                    $precioProducto = $producto['precioOferta'];
                    $porcentajeProducto = $producto['descuentoOferta'];
                    $oFecha = $producto['finOferta'];
                    
                }else{
                    
                    $precioProducto = 0;
                    $porcentajeProducto = 0;
                    $oFecha = "";
                    
                }

            }

            # Registrar cabeceras
            $this->db->update('cabeceras', array(
                'ruta' => $rutaP,
                'titulo' => $nombreP,
                'descripcion' => $desc,
                'palabrasClave' => $pClave
            ), "id='$idCb'",1);

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

            # Registrar productos
            $this->db->update('productos', array(
                "codigo" => $codigoP,
                "stock_minimo" => $stock_minimo,
                "autores" => $autores,
                "id_editorial" => $editorial,
                "idCategoria" => $categoria,
                "idSubcategoria" => $subcategoria,
                "ruta" => $rutaP,
                "producto" => $nombreP,
                "leyenda" => $leyenda,
                "descripcion" => $desc,
                "detalles" => $detalles,
                "precio_compra" => $precioC,
                "precio" => $precioR,
                "imagen" => $urlImagen,
                "oferta" => $oferta,
                "precioOferta" => $precioProducto,
                "descuentoOferta" => $porcentajeProducto,
                "finOferta" => $oFecha,
                "usuarioModificacion" => $administrador['id_user']
            ), "id='$id'",1);
            
            $this->db->delete('productos_autores', "id_producto='$id'");
            $arrayAutores = explode(",", $autores );
            foreach($arrayAutores as $key => $value){
                $this->db->insert('productos_autores', array(
                    "id_producto" => $id,
                    "id_autor" => $value
                ));
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de producto '.$id.' '.$nombreP, $perfil, $administrador['id_user'], 6, date('Y-m-d H:i:s'), 0);

            if($oferta==1){
                $ofertaTexto = ($oPrecio==0) ? "a -".$oPorcentaje."%" : 'todo a $'.number_format($oPrecio,2);
                (new Model\Actividad)->registrarActividad('Notificacion', "Adición de oferta ($ofertaTexto) en producto $id ".$nombreP." con vigencia hasta $oFecha", $perfil, $administrador['id_user'], 6, date('Y-m-d H:i:s'), 0);
            }

            return array('status' => 'success', 'title' => '¡Editado!', 'message' => 'El producto '.$id.' '.$nombreP.' se editó correctamente');
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());

        }

    }

    /**
     * Elimina un producto
     * 
     * @return array
    */ 
    public function eliminarProducto() : array {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            $id = intval($http->request->get('id'));
            $producto = $this->productoNi($id);
            if(!$producto){
                throw new ModelsException('El producto no existe.');
            }else{
                if($producto["stock"] > 0){
                    throw new ModelsException("El producto aun tiene {$producto["stock"]} en stock.");
                }
                $stmt2 = $this->db->select("*", 'entrada_detalle', null, "id_producto='".$id."'");
                if($stmt2){
                    return array('status' => 'info', 'title' => '¡Atención!','message' => 'El producto no puede ser eliminado, existen registros asociadas a este.');
                }else{
                    /* Eliminar imagen principal */
                    if($producto["imagen"] != 'assets/plantilla/img/productos/default/default.jpg'){
                        unlink(self::URL_ASSETS_WEBSITE.$producto["imagen"]);
                    }
                    
                    $cabecera = $this->db->select("*", "cabeceras", null, "ruta = '".$producto['stock']."'");
                    
                    $this->db->delete('deseos', "idProducto='$id'");
                    $this->db->delete('productos', "id='$id'", 1);
                    $this->db->delete('productos_autores', "id_producto='$id'");
                    if($cabecera){
                        $this->db->delete('cabeceras', "id='".$cabecera[0]["id"]."'", 1);
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

                    (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de producto '.$id, $perfil, $administrador['id_user'], 6, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Eliminado!','message' => 'El producto ha sido eliminado.');
                }
            }
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage(), 'id' => $id);
        }
        
    }


    /**
     * Obtiene el producto(s) según su código o nombre                          (PUNTO DE VENTA)
     * 
     * @return array
    */ 
    public function buscarProductoVenta() {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            # Obtener dato de búsqueda
            $busqueda = $this->db->scape(trim($http->request->get('busqueda')));
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
            # Si no viene ni código ni datos de búsqueda
            if($busqueda == ""){
                throw new ModelsException('Ingrese un dato para realizar la búsqueda del producto.');
            }
            # Si el dato de busqueda es diferente de vació
            if($busqueda != ""){
                if(strlen($busqueda) < 3){
                    throw new ModelsException('Ingresar al menos 3 caracteres para realizar la búsqueda.');
                }
                # Realizar búsqueda por código o producto o detalles
                $productos = $this->db->query("SELECT p.id, p.codigo, p.producto, p.leyenda, p.stock_minimo, p.stock, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial,  p.precio, p.liquidacion 
                                                FROM productos AS p 
                                                INNER JOIN editoriales AS e ON p.id_editorial=e.id_editorial 
                                                INNER JOIN productos_autores pa ON pa.id_producto = p.id
                                                INNER JOIN autores a ON a.id_autor = pa.id_autor 
                                                WHERE (p.codigo LIKE '%$busqueda%' OR p.producto LIKE '%$busqueda%' OR p.leyenda LIKE '%$busqueda%') 
                                                AND p.estado='1' AND p.descontinuado='NO'
                                                GROUP BY p.id 
                                                ORDER BY p.stock DESC, p.producto");
            }
            $status = '';
            $title = '';
            $message = '';
            $resultados = $productos->num_rows;
            $tr = '';
            if($resultados == 0){
                throw new ModelsException('No se encontrarón resultados.');
            # Si los resultados son igual a 1 
            }elseif($resultados == 1){
                $producto = $productos->fetch_assoc();
                (new Model\PuntoDeVenta)->agregarListaVenta(1, $producto["id"]);
                $status = 'success';
                $title = '¡Agregado!';
                $message = 'El producto se agregó a la lista.';
                # Agregar el producto a la lista de productos en venta
                $productos->free();
            # Si no los resultados son superiores a 1
            }else{
                # Recorrer array $productos
                foreach ($productos as $key => $value) {
                    $idProducto = $value['id'];
                    $stock_almacen = $value['stock'];
                    $stock = $this->colorear_stock($value['stock_minimo'], $stock_almacen);
                    $autor = $value['autores'];
                    $editorial = $value['editorial'];
                    $codigo = $value['codigo'];
                    
                    $leyenda = "";
                    if($value["leyenda"] != ""){
                        $leyenda = $value["leyenda"];
                    }
                    
                    $attr_liquidacion = '';
                    if($value["liquidacion"] == "SI"){
                        $attr_liquidacion = 'text-red'; 
                    }

                    if($stock_almacen == 0){
                        $tr .= '<tr>
                                    <td style="vertical-align:middle; width:50px;">
                                        '.$value['codigo'].'
                                    </td>
                                    <td style="vertical-align:middle;">
                                        '.$idProducto.' | <span class="font-weight-bold '.$attr_liquidacion.'">'.$value['producto'].'</span> '.$leyenda.'
                                    </td>
                                    <td style="width:250px;">
                                        '.$autor.'
                                    </td>
                                    <td style="width:150px;">
                                        '.$editorial.'
                                    </td>
                                    <td style="width:60px;" class="text-center font-weight-bold">
                                        '.number_format($value['precio'],2).'
                                    </td>
                                    <td style="width:60px;" class="text-center">'.$stock.'</td>
                                    <td style="width:60px;" class="text-center"></td>
                                </tr>';
                    }else{
                        $tr .= '<tr>
                                    <td style="vertical-align:middle; width:50px; ">
                                        '.$value['codigo'].'
                                    </td>
                                    <td style="vertical-align:middle;">
                                        '.$idProducto.' | <span class="font-weight-bold '.$attr_liquidacion.'">'.$value['producto'].'</span> '.$leyenda.'
                                    </td>
                                    <td style="width:250px;">
                                        '.$autor.'
                                    </td>
                                    <td style="width:150px;">
                                        '.$editorial.'
                                    </td>
                                    <td style="width:60px;" class="text-center font-weight-bold">
                                        '.number_format($value['precio'],2).'
                                    </td>
                                    <td style="width:60px;" class="text-center">'.$stock.'</td>
                                    <td style="width:60px;" class="text-center">
                                        <i class="fas fa-share text-aqua" id="btnAgregarVenta" key="'.$value['id'].'" style="cursor:pointer;" data-toggle="tooltip" title="Salida" data-placement="left"></i>
                                    </td>
                                </tr>';
                    }
                }
            }
            return array('status' => $status, 'title' => $title, 'message' => $message, 'resultados' => $resultados, 'tb' => 'datos', 'tr' => $tr);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage());
        }
    }

    /**
     * Retorna los datos de los productos para ser mostrados en datatables      (PUNTO DE VENTA)
     * 
     * @return json
    */ 
    public function mostrarProductosPV() : array {
        $productos = $this->db->query("SELECT p.id, p.codigo, p.producto, p.leyenda, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial, p.stock, p.stock_minimo, p.precio, p.liquidacion 
                                        FROM productos AS p 
                                        INNER JOIN editoriales AS e ON p.id_editorial=e.id_editorial 
                                        INNER JOIN productos_autores pa ON pa.id_producto = p.id
                                        INNER JOIN autores a ON a.id_autor = pa.id_autor 
                                        WHERE p.estado='1' AND p.descontinuado='NO'
                                        GROUP BY p.id 
                                        ORDER BY p.id DESC");

        $data = [];
        if($productos){
            foreach ($productos as $key => $value) {
                $infoData = [];
                $infoData[] = $value["codigo"];
                
                $infoData[] = $value["id"];
                
                $leyenda = "";
                if($value["leyenda"] != ""){
                    $leyenda = " ".$value["leyenda"];
                }
                $attr_liquidacion = '';
                if($value["liquidacion"] == "SI"){
                    $attr_liquidacion = 'text-red'; 
                }
                $infoData[] = '<span class="font-weight-bold '.$attr_liquidacion.'">'.$value["producto"].'</span>'.$leyenda;
                $infoData[] = $value["autores"];
                $infoData[] = $value['editorial'];
                $infoData[] = number_format($value["precio"],2);
                $infoData[] = $this->colorear_stock($value['stock_minimo'], $value['stock']);
                if($value['stock'] > 0){
                    $infoData[] = '<i class="fas fa-share text-aqua" id="btnAgregarVenta" key="'.$value['id'].'" style="cursor:pointer;" data-toggle="tooltip" title="Salida" data-placement="left"></i>';
                }else{
                    $infoData[] = '';
                }
                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }


     /**
     * Obtiene el producto(s) según su código o nombre                          (REGISTRAR COMPRAS)
     * 
     * @return array
    */ 
    public function buscarProductoCompra() {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            # Obtener dato de búsqueda
            $busqueda = $this->db->scape(trim($http->request->get('busqueda')));
            # Si no viene ni código ni datos de búsqueda
            if($busqueda == ""){
                throw new ModelsException('Ingrese un dato para realizar la búsqueda del producto.');
            }
            # Si el dato de busqueda es diferente de vació
            if($busqueda != ""){
                if(strlen($busqueda) < 3){
                    throw new ModelsException('Ingresar al menos 3 caracteres para realizar la búsqueda.');
                }
                # Realizar búsqueda por código o producto o detalles
                $productos = $this->db->query("SELECT p.id, p.codigo, p.producto, p.leyenda, p.stock_minimo, p.stock, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial,  p.precio, p.precio_compra
                                                FROM productos AS p 
                                                INNER JOIN editoriales AS e ON p.id_editorial=e.id_editorial 
                                                INNER JOIN productos_autores pa ON pa.id_producto = p.id
                                                INNER JOIN autores a ON a.id_autor = pa.id_autor 
                                                WHERE (p.codigo LIKE '%$busqueda%' OR p.producto LIKE '%$busqueda%' OR p.leyenda LIKE '%$busqueda%') 
                                                AND p.estado='1' AND p.descontinuado='NO'
                                                GROUP BY p.id 
                                                ORDER BY p.stock DESC, p.producto");
            }
            $status = '';
            $title = '';
            $message = '';
            $resultados = $productos->num_rows;
            $tr = '';
            if($resultados == 0){
                throw new ModelsException('No se encontrarón resultados.');
            # Si los resultados son igual a 1 
            }elseif($resultados == 1){  
                $producto = $productos->fetch_assoc();
                (new Model\RegistrarCompras)->agregarListaCompra(1, $producto["id"]);
                $status = 'success';
                $title = '¡Agregado a la lista!';
                $message = 'El producto se agregó a la lista.';
                # Agregar el producto a la lista de productos en compra
                $productos->free();
            # Si no los resultados son superiores a 1
            }else{
                # Recorrer array $productos
                foreach ($productos as $key => $value) {
                    $idProducto = $value['id'];
                    $stock_almacen = $value['stock'];
                    $stock = $this->colorear_stock($value['stock_minimo'], $stock_almacen);
                    $autor = $value['autores'];
                    $editorial = $value['editorial'];
                    $codigo = $value['codigo'];
                    
                    $leyenda = "";
                    if($value["leyenda"] != ""){
                        $leyenda = $value["leyenda"];
                    }
                
                    $tr .= '<tr>
                                <td style="vertical-align:middle; width:50px; ">'.$value['codigo'].'</td>

                                <td style="vertical-align:middle;">
                                    '.$idProducto.' | <span class="font-weight-bold">'.$value['producto'].'</span> '.$leyenda.'
                                </td>
                                    
                                <td style="width:250px;">'.$autor.'</td>
                                <td style="width:150px;">'.$editorial.'</td>
                                <td style="width:60px;" class="text-center">'.number_format($value['precio_compra'],2).'</td>
                                <td style="width:60px;" class="text-center font-weight-bold">'.number_format($value['precio'],2).'</td>
                                <td style="width:60px;" class="text-center">'.$stock.'</td>
                                <td style="width:60px;" class="text-center">
                                    <i class="fas fa-reply text-purple" id="btnAgregar" key="'.$value['id'].'" style="cursor:pointer;" data-toggle="tooltip" title="Entrada" data-placement="left"></i>
                                </td>
                            </tr>';

                }
            }
            return array('status' => $status, 'title' => $title, 'message' => $message, 'resultados' => $resultados, 'tb' => 'datos', 'tr' => $tr);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage());
        }
    }

    /**
     * Retorna los datos de los productos para ser mostrados en datatables      (REGISTRAR COMPRAS)
     * 
     * @return json
    */ 
    public function mostrarProductosC() : array {
        $productos = $this->db->query("SELECT p.id, p.codigo, p.producto, p.leyenda, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial, p.stock, p.stock_minimo, p.precio, p.precio_compra
                                        FROM productos AS p 
                                        INNER JOIN editoriales AS e ON p.id_editorial=e.id_editorial 
                                        INNER JOIN productos_autores pa ON pa.id_producto = p.id
                                        INNER JOIN autores a ON a.id_autor = pa.id_autor 
                                        WHERE p.estado='1' AND p.descontinuado='NO'
                                        GROUP BY p.id 
                                        ORDER BY p.id DESC");
        $data = [];
        if($productos){
            foreach ($productos as $key => $value) {
                $infoData = [];
                $infoData[] = $value["codigo"];
                
                $infoData[] = $value["id"];
                
                $leyenda = "";
                if($value["leyenda"] != ""){
                    $leyenda = " ".$value["leyenda"];
                }
                
                $infoData[] = '<strong>'.$value["producto"].'</strong>'.$leyenda;
                $infoData[] = $value["autores"];
                $infoData[] = $value['editorial'];
                $infoData[] = number_format($value["precio_compra"],2);
                $infoData[] = number_format($value["precio"],2);
                $infoData[] = $this->colorear_stock($value['stock_minimo'], $value['stock']);
                $infoData[] = '<i class="fas fa-reply text-purple" id="btnAgregar" key="'.$value['id'].'" style="cursor:pointer; margin-left:15px;" data-toggle="tooltip" title="Entrada" data-placement="left"></i>';
                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }
    
    
    /**
     * Obtiene el producto(s) según su código o nombre                          (REGISTRAR CREDITO)
     * 
     * @return array
    */ 
    public function buscarProductoCredito() {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;

            # Obtener cliente
            $id_cliente = intval($http->request->get('cliente'));
            # Obtener dato de búsqueda
            $busqueda = $this->db->scape(trim($http->request->get('busqueda')));

            if($id_cliente == 0){
                $this->vaciarListaCredito();
                throw new ModelsException('Seleccione un cliente de la lista.');
            }else{
                $cliente = (new Model\Clientes)->cliente($id_cliente);
                if($cliente['estado'] == 0 || $cliente['tipo'] == 1){
                    $this->vaciarListaCredito();
                    throw new ModelsException('Seleccione un cliente de la lista.');
                }
            }

            
            # Si no viene ni código ni datos de búsqueda
            if($busqueda == ''){
                throw new ModelsException('Ingrese un dato para realizar la búsqueda del producto.');
            }
            # Si el dato de busqueda es diferente de vació
            if($busqueda != ''){
            	if(strlen($busqueda) < 3){
            		throw new ModelsException('Ingresar al menos 3 caracteres para realizar la búsqueda.');
            	}
                # Realizar búsqueda por código o producto o detalles
                $productos = $this->db->query("SELECT p.id, p.codigo, p.producto, p.leyenda, p.stock_minimo, p.stock, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial,  p.precio 
                                                FROM productos AS p 
                                                INNER JOIN editoriales AS e ON p.id_editorial=e.id_editorial 
                                                INNER JOIN productos_autores pa ON pa.id_producto = p.id
                                                INNER JOIN autores a ON a.id_autor = pa.id_autor 
                                                WHERE (p.codigo LIKE '%$busqueda%' OR p.producto LIKE '%$busqueda%' OR p.leyenda LIKE '%$busqueda%') 
                                                AND p.estado='1' AND p.descontinuado='NO'
                                                GROUP BY p.id 
                                                ORDER BY p.stock DESC, p.producto");
            }
            $status = '';
            $title = '';
            $message = '';
            $resultados = $productos->num_rows;
            $tr = '';

            if($resultados == 0){
                throw new ModelsException('No se encontrarón resultados.');
            # Si los resultados son igual a 1 
            }elseif($resultados == 1){
                $producto = $productos->fetch_assoc();
                (new Model\RegistrarCredito)->agregarListaCredito(1, $producto["id"], $id_cliente);
                $status = 'success';
                $title = '¡Agregado!';
                $message = 'El producto se agregó a la lista.';
                $productos->free();
            # Si no los resultados son superiores a 1
            }else{
                # Recorrer array $productos
                foreach ($productos as $key => $value) {
                    $idProducto = $value['id'];
                    $stock_almacen = $value['stock'];
                    $stock = $this->colorear_stock($value['stock_minimo'], $stock_almacen);
                    $autor = $value['autores'];
                    $editorial = $value['editorial'];
                    $codigo = $value['codigo'];
                    
                    $leyenda = "";
                    if($value["leyenda"] != ""){
                        $leyenda = $value["leyenda"];
                    }

                    if($stock_almacen == 0){
                        $tr .= '<tr>
                                    <td style="vertical-align:middle; width:50px;">
                                        '.$value['codigo'].'
                                    </td>
                                    
                                    <td style="vertical-align:middle;">
                                        '.$idProducto.' | <span class="font-weight-bold">'.$value['producto'].'</span> '.$leyenda.'
                                    </td>
                                    
                                    <td style="width:250px;">
                                        '.$autor.'
                                    </td>
                                    <td style="width:150px;">
                                        <strong>'.$editorial.'</strong>
                                    </td>
                                    <td style="width:60px;" class="text-center font-weight-bold">
                                        '.number_format($value['precio'],2).'
                                    </td>
                                    <td style="width:60px;" class="text-center">'.$stock.'</td>
                                    <td style="width:60px;" class="text-center"></td>
                                </tr>';
                    }else{
                        $tr .= '<tr>
                                    <td style="vertical-align:middle; width:50px;">
                                        '.$value['codigo'].'
                                    </td>
                                    <td style="vertical-align:middle;">
                                        '.$idProducto.' | <span class="font-weight-bold">'.$value['producto'].'</span> '.$leyenda.'
                                    </td>
                                    <td style="width:250px;">
                                        '.$autor.'
                                    </td>
                                    <td style="width:150px;">
                                        <strong>'.$editorial.'</strong>
                                    </td>
                                    <td style="width:60px;" class="text-center font-weight-bold">
                                        '.number_format($value['precio'],2).'
                                    </td>
                                    <td style="width:60px;" class="text-center">'.$stock.'</td>
                                    <td style="width:60px;" class="text-center">
                                        <i class="fas fa-share text-orange" id="btnAgregarCredito" key="'.$value['id'].'" style="cursor:pointer;" data-toggle="tooltip" title="Crédito" data-placement="left"></i>
                                    </td>
                                </tr>';
                    }

                }

            }

            return array('status' => $status, 'title' => $title, 'message' => $message, 'resultados' => $resultados, 'tb' => 'datos', 'tr' => $tr);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage());
        }
    }
    
    public function mostrarProductosPedidos() {
        $productos = $this->db->query("SELECT p.id, p.codigo, p.producto, p.leyenda, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial, p.stock, p.stock_minimo, p.precio, p.precio_compra
                                        FROM productos AS p 
                                        INNER JOIN editoriales AS e ON p.id_editorial=e.id_editorial 
                                        INNER JOIN productos_autores pa ON pa.id_producto = p.id
                                        INNER JOIN autores a ON a.id_autor = pa.id_autor 
                                        WHERE p.estado='1' AND p.descontinuado='NO' AND stock = 0
                                        GROUP BY p.id 
                                        ORDER BY p.id DESC");
        
        $data = [];
        if($productos){
            foreach ($productos as $key => $value) {
                $infoData = [];
                $infoData[] = $value["codigo"];
                
                $infoData[] = $value["id"];
                
                $leyenda = "";
                if($value["leyenda"] != ""){
                    $leyenda = " ".$value["leyenda"];
                }
                
                $infoData[] = '<strong>'.$value["producto"].'</strong>'.$leyenda;
                $infoData[] = $value["autores"];
                $infoData[] = $value['editorial'];
                $infoData[] = number_format($value["precio_compra"],2);
                $infoData[] = number_format($value["precio"],2);
                $infoData[] = '<i class="fa fa-fw fa-list-alt text-purple" id="btnAgregarPedido" key="'.$value['id'].'" style="cursor:pointer; margin-left:15px; margin-right:15px;" data-toggle="tooltip" title="Cargar en formulario" data-placement="left"></i>';
                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    /**
     * Valida las ofertas existentes
     * 
    */
    public function validarOfertas() {

        /* VALIDAR OFERTA EN CATEGORÍAS */
        $categorias = (new Model\Categorias)->categoriasPor('oferta', 1);
        if($categorias){
            foreach ($categorias as $key => $value) {
                $idC = $value["id"];
                $finOferta = strtotime($value["finOferta"]);
                $diaActual = time();
                if($diaActual > $finOferta){
                    
                    $this->db->update('categorias',array(
                        'oferta' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'imgOferta' => '',
                        'finOferta' => ''
                    ),"id='$idC'");

                    $this->db->update('subcategorias',array(
                        'ofertaCategoria' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ),"id_categoria='$idC'");

                    $this->db->update('productos',array(
                        'ofertadoPorCategoria' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ),"idCategoria='$idC'");

                }
            }
        }

        /* VALIDAR OFERTA SUBCATEGORIAS */
        $subcategorias = (new Model\Subcategorias)->subcategoriasPor('oferta', 1);
        if($subcategorias){
            foreach ($subcategorias as $key => $value) {
                $idS = $value["id"];
                $finOferta = strtotime($value["finOferta"]);
                $diaActual = time();

                if($diaActual > $finOferta){
                    $this->db->update('subcategorias',array(
                        'oferta' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ),"id='$idS'");

                    $this->db->update('productos',array(
                        'ofertadoPorSubcategoria' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ),"idSubcategoria='$idS'");   
                }

            }
        }
        
        /* VALIDAR OFERTA EDITORIALES */
        $editoriales = (new Model\Editoriales)->editorialesPor('oferta', 1);
        if($editoriales){
            foreach ($editoriales as $key => $value) {
                $idE = $value["id_editorial"];
                $finOferta = strtotime($value["finOferta"]);
                $diaActual = time();

                if($diaActual > $finOferta){
                    $this->db->update('editoriales',array(
                        'oferta' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ),"id_editorial='$idE'");

                    $this->db->update('productos',array(
                        'ofertadoPorEditorial' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ),"id_editorial='$idE'");   
                }

            }
        }
        
        /* VALIDAR OFERTA AUTORES */
        $autores = (new Model\Autores)->autoresPor('oferta', 1);
        if($autores){
            foreach ($editoriales as $key => $value) {
                $idA = $value["id_autor"];
                $finOferta = strtotime($value["finOferta"]);
                $diaActual = time();

                if($diaActual > $finOferta){
                    $this->db->update('autores',array(
                        'oferta' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ),"id_autor='$idA'");
                    
                    $this->db->update('productos',array(
                        'ofertadoPorAutor' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ), "FIND_IN_SET({$idA},autores)>0");  
                      
                }

            }
        }

        /* VALIDAR OFERTA PRODUCTOS */
        $productos = $this->productosPor('oferta', 1);
        if($productos){
            foreach ($productos as $key => $value) {
                $idP = $value["id"];
                $finOferta = strtotime($value["finOferta"]);
                $diaActual = time();
                if($diaActual > $finOferta){
                    $this->db->update('productos',array(
                        'oferta' => 0,
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => ''
                    ),"id='$idP'"); 
                }
            }
        }

        return "Se validarón las ofertas correctamente";

    }

    public function obtenerEditoriales(){
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            # Obtener los datos $_POST
            $metodo = $http->request->get('metodo');
            $selected = intval($http->request->get('seleccionado'));

            if($metodo != 'cargar' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }

            $editoriales =  $this->db->select('*','editoriales', null, "id_editorial != 1 AND estado = 1", null, 'ORDER BY editorial ASC');

            $options = '<option></option><optgroup label="Editoriales disponibles">';
            foreach ($editoriales as $key => $value) {

                if($selected == $value['id_editorial']){
                    $options .= '<option value="'.$value['id_editorial'].'" selected>'.$value['editorial'].'</option>';
                }else{
                    $options .= '<option value="'.$value['id_editorial'].'">'.$value['editorial'].'</option>';
                }
                
            }
            $options .= "</optgroup>";

            return array('status' => 'success', 'editoriales' => $options);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage());
        }
    }

    public function obtenerAutores(){
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            # Obtener los datos $_POST
            $metodo = $http->request->get('metodo');
            $selected = intval($http->request->get('seleccionado'));
            $autoresSeleccionados = $http->request->get('autoresSeleccionados');
            if($autoresSeleccionados !== null || $autoresSeleccionados != ''){
                $autoresSeleccionados = $autoresSeleccionados;
            }else{
                $autoresSeleccionados = [];
            }

            if($metodo != 'cargar' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }

            $autores =  $this->db->select('*','autores', null, 'id_autor != 1 AND estado = 1', null, 'ORDER BY autor ASC');

            $options = '<optgroup label="Autores disponibles">';

            foreach ($autores as $key => $value) {

                if( $selected == $value['id_autor'] || in_array($value['id_autor'], $autoresSeleccionados) ){
                    $options .= '<option value="'.$value['id_autor'].'" selected>'.$value['autor'].'</option>';
                }else{
                    $options .= '<option value="'.$value['id_autor'].'">'.$value['autor'].'</option>';
                }
                
            }
            $options .= "</optgroup>";

            return array('status' => 'success', 'autores' => $options, 'AS' => $autoresSeleccionados);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Descargar movimientos del producto segun el id enviado
     * 
     * origen (controlador productos)
    */
    public function descargarMovimientos($id_producto) {
        global $config;
        
        // Si el usuario no esta logeado o es igual a NULL
        if($this->id_user === NULL) { 
            // Redireccionar a la ruta principal
            Helper\Functions::redir($config['build']['url']);
        }
        
        // Datos del producto
        $producto = $this->productoNi($id_producto);
        
        // Movimientos del producto
        $movimientos = $this->db->select('*', 'productos_cardex', null, "id_producto = '$id_producto'", null, "ORDER BY id_cardex DESC");
        
        // Si el producto existe y tiene movimientos        
        if($producto && $movimientos){
            
            // Archivo excel
            $nombre = "Movimientos {$producto['codigo']} - {$producto['id']} {$producto['producto']} {$producto['leyenda']}.xls"; 
            header('Expires: 0');
            header('Cache-control: private');
            header('Content-type: application/vnd.ms-excel; charset=UTF-8');
            header('Cache-Control: cache, must-revalidate');
            header('Content-Description: File Transfer');
            header('Last-Modified: '.date('D, d M Y H:i:s'));
            header('Pragma: public');
            header('Content-Disposition: attachment; filename="'.$nombre.'"');
            header('Content-Transfer-Encoding: binary');
            
            $html = "
                <table border='0'>
                    <tr>
                        <td colspan='8' style='text-align:center;'>{$producto['codigo']} - {$producto['id']} {$producto['producto']} {$producto['leyenda']}</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>MOVIMIENTO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>CANTIDAD</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>ALMACÉN</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>REFERENCIA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>COSTO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>PRECIO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>DESCUENTO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>FECHA</td>
                    </tr>";

            foreach ($movimientos as $key => $value) {
            
                switch($value['movimiento']){
                    case 'ce':
                        $tipoM = 'Entrada (compra)';
                        break;
                    case 'ae':
                        $tipoM = 'Entrada (ajuste)';
                        break;
                    case 'ce':
                        $tipoM = 'Entrada (consignación)';
                        break;
                    case 'vs':
                        $tipoM = 'Salida (venta)';
                        break;
                    case 'cs':
                        $tipoM = 'Salida (consignación)';
                        break;
                    case 'as':
                        $tipoM = 'Salida (ajuste)';
                        break;
                    case 'df':
                        $tipoM = 'Entrada (venta cancelada)';
                        break;
                    case 'di':
                    case 'dc':
                        $tipoM = 'Entrada (venta devolución)';
                        break;
                    case 'crs':
                        $tipoM = 'Salida (crédito)';
                        break;
                    case 'crdf':
                        $tipoM = 'Entrada (crédito cancelado)';
                        break;
                    case 'crdc':
                        $tipoM = 'Entrada (crédito devolución)';
                        break;
                    default:
                        $tipoM = 'No definido';
                        break;
                }
                
                if($value['descuento'] == '' || $value['descuento'] === NULL){
                    $descuento = '0.00';
                }else{
                    $descuento = $value['descuento'];
                }

                $html .= "<tr>";
                $html .= "<td style='text-align:center;'>$tipoM</td>";
                $html .= "<td style='text-align:center;'>".$value['cantidad']."</td>";
                $html .= "<td style='text-align:center;'>AL-".$value['id_almacen']."</td>";
                $html .= "<td style='text-align:center;'>".$value['referencia']."</td>";
                $html .= "<td style='text-align:center;'>".$value['costo']."</td>";
                $html .= "<td style='text-align:center;'>".$value['precio']."</td>";
                $html .= "<td style='text-align:center;'>".$descuento."</td>";
                $html .= "<td style='text-align:center;'>".$value['fecha']."</td>";
                $html .= "</tr>";
                
            }         
            $html .= "</table>";
            
            echo $html;
            
        }else{
            # Redireccionar a la ruta de productos
            Helper\Functions::redir($config['build']['url'].'productos');
        }
        
        /*
        $movimientos = $this->db->query('SELECT p.codigo, CONCAT(p.producto," ",IF(p.leyenda IS NULL, "", p.leyenda)) as libro, GROUP_CONCAT(DISTINCT a.autor) AS autores, e.editorial, p.precio_compra, p.precio AS precio_venta, p.stock
                            FROM productos AS p 
                            INNER JOIN editoriales AS e ON p.id_editorial=e.id_editorial 
                            INNER JOIN productos_autores pa ON pa.id_producto = p.id
                            INNER JOIN autores a ON a.id_autor = pa.id_autor
                            WHERE p.descontinuado = "NO"
                            GROUP BY p.id 
                            ORDER BY e.editorial ASC, p.stock DESC');
        $nombre = "NUEVO INVENTARIO 2020.xls"; 
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
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>CODIGO</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>LIBRO</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>AUTOR</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>EDITORIAL</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>PRECIO COMPRA</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>PRECIO VENTA</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>STOCK</td>
                </tr>";
        foreach ($movimientos as $key => $value) {
            $html .= "<tr>";
            $html .= "<td style='text-align:center;'>".$value['codigo']."</td>";
            $html .= "<td style='text-align:center;'>".$value['libro']."</td>";
            $html .= "<td style='text-align:center;'>".$value['autores']."</td>";
            $html .= "<td style='text-align:center;'>".$value['editorial']."</td>";
            $html .= "<td style='text-align:center;'>".$value['precio_compra']."</td>";
            $html .= "<td style='text-align:center;'>".$value['precio_venta']."</td>";
            $html .= "<td style='text-align:center;'>".$value['stock']."</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";
        echo $html;
        */

    }

    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}
