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
 * Modelo Home
 */
class Home extends Models implements IModels {
    use DBModel;
    
    /**
     * Obtiene el monto total en inventario, tomando en cuenta el precio y stock 
     * 
     * (perfil 4 Administración de sucursal)
     *    
     * @return false|string con el total
     */
    public function totalInventario(){
        $productos = (new Model\Productos)->productos();
        $totalPrecio = 0;
        $totalCosto = 0;
        if($productos){
        	foreach($productos as $key => $value){
	            $stock = (int) $value['stock'];
	            if($stock > 0){
	                
	                $precio = (real) $value['precio'];
	                $subtotal = $stock * $precio;
	                $totalPrecio += $subtotal;
	                
	                $costo = (real) $value['precio_compra'];
	                $subtotal2 = $stock * $costo;
	                $totalCosto += $subtotal2;
	            }
	        }
        }
        return [
            'precio' => number_format($totalPrecio,2),
            'costo' => number_format($totalCosto,2)
        ];
    }
    
    /**
     * Obtiene el monto total de las compras del mes actual
     * 
     * (perfil 4 Administración de sucursal)
     *    
     * @return false|string con el total
     */
    public function totalCompras(){
        $anio = date('Y');    
        $mes = date('m');
        $compras = $this->db->select('SUM(ed.cantidad * ed.costo) AS total', 
            'entrada_detalle AS ed', 
            "INNER JOIN entrada AS e ON ed.id_entrada=e.id_entrada", 
            "YEAR(e.fecha) = '$anio' AND MONTH(e.fecha) = '$mes' AND e.id_proveedor IS NOT NULL"
        );
        
        if($compras){
            return number_format($compras[0]['total'],2);
        }
        return '0.00';
    }
    
    /**
     * Obtiene el monto total de las ventas del día
     *    
     * (perfil 4 Administración de sucursal)
     * 
     * @return false|string con el total
     */ 
    public function totalVentas() {
        $dia = date('Y-m-d');
        $ventas = $this->db->select('SUM(sd.vendido * (sd.precio - (sd.precio * sd.descuento) / 100)) AS total', 
            'salida_detalle AS sd', 
            "INNER JOIN salida AS s ON sd.id_salida=s.id_salida", 
            "s.fechaVenta = '$dia' AND s.estado = '1' AND s.id_cliente IS NOT NULL");
        if($ventas){
            return number_format($ventas[0]['total'],2);
        }
        return '0.00';
    }
    
    /**
     * Obtiene la cantidad de créditos sin pagar
     *    
     * (perfil 4 Administración de sucursal)
     * 
     * @return false|string con el total
     */ 
    public function cuentasPorCobrar(){
        $cpc = $this->db->select("COUNT(id_credito) as cuentas", "credito", null, "estado='2'");
        return $cpc[0]['cuentas'];
    }

    /**
     * Obtiene el total de categorías (perfil 4 Administración de sucursal)
     * 
     * (perfil 4 Administración de sucursal)
     *    
     * Excluye el id 1, porque es la categoria 'SIN CATEGORIA'
     * 
     * @return false|string con el total
     */ 
    public function totalCategorias() {
    	$totalCategorias = $this->db->select('COUNT(id) as total', 'categorias', null, 'id != 1');
    	return $totalCategorias[0]["total"];
    }

    /**
     * Obtiene el total de subcategorías (perfil 4 Administración de sucursal)
     *
     * (perfil 4 Administración de sucursal)
     * 
     * Excluye el id 1, porque es la categoria 'SIN SUBCATEGORIA'
     * 
     * @return false|string con el total
     */ 
    public function totalSubcategorias() {
    	$totalSubcategorias = $this->db->select('COUNT(id) as total', 'subcategorias', null, 'id != 1');
    	return $totalSubcategorias[0]["total"];
    }
    
    /**
     * Obtiene la suma total de las editoriales
     *
     * (perfil 4 Administración de sucursal) 
     *
     * @return false|string con el total
     */ 
    public function totalEditoriales() {
    	$totalEditoriales = $this->db->select('COUNT(id_editorial) as total', 'editoriales', null, 'id_editorial != 1');
    	return $totalEditoriales[0]["total"];
    }
    
    /**
     * Obtiene la suma total de los autores
     * 
     * (perfil 4 Administración de sucursal) 
     *
     * @return false|string con el total
     */ 
    public function totalAutores() {
    	$totalAutores = $this->db->select('COUNT(id_autor) as total', 'autores', null, 'id_autor != 1');
    	return $totalAutores[0]["total"];
    }

    /**
     * Obtiene la suma total de los productos
     *
     * (perfil 4 Administración de sucursal) 
     *
     * @return false|string con el total
     */ 
    public function totalProductos() {
    	$totalProductos = $this->db->select('COUNT(id) as total', 'productos', null, "descontinuado = 'NO'");
    	return $totalProductos[0]["total"];
    }

    /**
     * Obtiene la suma total de los clientes
     *
     * (perfil 4 Administración de sucursal) 
     *
     * @return false|string con el total
     */ 
    public function totalClientes() {
    	$totalClientes = $this->db->select('COUNT(id_cliente) as total', 'clientes', null, "id_cliente != 1");
    	return $totalClientes[0]["total"];
    }

    /**
     * Obtiene la suma total de los proveedores
     *
     * (perfil 4 Administración de sucursal) 
     *
     * @return false|string con el total
     */ 
    public function totalProveedores() {
    	$totalProveedores = $this->db->select('COUNT(id_proveedor) as total', 'proveedores', null, "id_proveedor != 1 AND id_proveedor != 2");
    	return $totalProveedores[0]["total"];
    }

    /**
     * Obtiene la suma total de los sliders
     *  
     * (perfil 2 Ajustes y estadísticas del sitio web)  
     *
     * @return false|string con el total
     */ 
    public function totalSliders() {
    	$totalSliders = $this->db->select('COUNT(id) as total', 'carrusel');
    	return $totalSliders[0]["total"];
    }

    /**
     * Obtiene la suma total de los banners
     * 
     * (perfil 2 Ajustes y estadísticas del sitio web)
     *
     * @return false|string con el total
     */ 
    public function totalBanners() {
    	$totalBanners = $this->db->select('COUNT(id) as total', 'anuncios');
    	return $totalBanners[0]["total"];
    }
    
    /**
     * Obtiene las vistas por refion (se puede eliminar)
     *
     * (perfil 2 Ajustes y estadísticas del sitio web)
     *
     * @return false|string con el total
     */ 
    public function totalVisitas() {
    	$totalVisitas = $this->db->select('SUM(cantidad) AS total', 'visitasregion');
    	return $totalVisitas[0]["total"];
    }
    
    /**
     * Obtiene los usuarios registrados en la página web
     *
     * (perfil 2 Ajustes y estadísticas del sitio web)
     *
     * @return false|array con información de los usuarios
     */ 
    public function mostrarUsuarios(string $orden = 'id', $limite = null) {

    	return $this->db->select('id, nombre, correoElectronico, modo, foto, fecha, UNIX_TIMESTAMP(fechaRegistro) AS fechaRegistro, 
            estatus', 
            'usuarios', null, null, $limite, "ORDER BY '$orden' DESC"
        );

    }
    
    /**
     * Obtiene los productos registrados
     *    
     *
     * @return false|array con información de los productos
     */ 
    public function mostrarProductos(string $orden, $limite = null) {
        return $this->db->select('id, idCategoria, idSubcategoria, tipo, ruta, producto, descripcion, estracto, multimedia, detalles, precio, imagen, vistas, ventas, ventasMostrador, vistasGratis,
            ventasGratis, ofertadoPorCategoria, ofertadoPorSubcategoria, oferta, precioOferta, descuentoOferta, imagenOferta, finOferta, peso, entrega, UNIX_TIMESTAMP(fechaRegistro) AS fechaRegistro,
            usuarioRegistro, fechaModificacion, usuarioModificacion, estado', 
            'productos', null, null, $limite, "ORDER BY $orden DESC"
        );
    }

    public function ventasPorMes(){
        global $http;

        $anio = intval($http->request->get('anio'));
        if($anio == 0 || $anio == null || $anio == ''){
            $anio = date('Y');
        }

        $ventas = $this->db->select('YEAR(s.fechaVenta) AS anio, MONTH(s.fechaVenta) AS mes, SUM(sd.vendido * (sd.precio - (sd.precio * sd.descuento) / 100)) AS total', 
            'salida_detalle AS sd', 
            "INNER JOIN salida AS s ON s.id_salida=sd.id_salida", 
            "YEAR(s.fechaVenta) = '$anio' AND s.estado = '1' AND s.id_cliente IS NOT NULL",
            null,
            "GROUP BY MONTH(s.fechaVenta) ORDER BY MONTH(s.fechaVenta) DESC"
        );

        $html = '';

        if(!$ventas){
            $html .= '
            <tr>
                <td colspan="5" class="text-center">Sin registros de ventas para mostrar</td>
            </tr>';
        }else{
            $totalGeneral = 0;
            foreach ($ventas as $key => $value){
                $tM = (real) $value['total'];
                $totalGeneral = $totalGeneral + $tM;
            }

            foreach ($ventas as $key => $value){

                switch ($value['mes']) {
                    case '1':
                        $mes = 'Enero';
                        break;
                    case '2':
                        $mes = 'Febrero';
                        break;
                    case '3':
                        $mes = 'Marzo';
                        break;
                    case '4':
                        $mes = 'Abril';
                        break;
                    case '5':
                        $mes = 'Mayo';
                        break;
                    case '6':
                        $mes = 'Junio';
                        break;
                    case '7':
                        $mes = 'Julio';
                        break;
                    case '8':
                        $mes = 'Agosto';
                        break;
                    case '9':
                        $mes = 'Septiembre';
                        break;
                    case '10':
                        $mes = 'Octubre';
                        break;
                    case '11':
                        $mes = 'Noviembre';
                        break;
                    case '12':
                        $mes = 'Diciembre';
                        break;
                }

                $totalMes = (real) $value['total'];

                $porcentaje = ( (100 * $totalMes) / $totalGeneral);

                if($porcentaje < 33.33){
                    $progressColor = 'progress-bar-danger';
                    $spanColor = 'bg-red';
                }elseif($porcentaje > 33.33 && $porcentaje < 66.66){
                    $progressColor = 'progress-bar-yellow';
                    $spanColor = 'bg-yellow';
                }elseif($porcentaje > 66.66){
                    $progressColor = 'progress-bar-success';
                    $spanColor = 'bg-green';
                }

                $porcentaje = number_format($porcentaje,2);

                $html .= '
                <tr>
                    <td>'.$anio.'</td>
                    <td>'.$mes.'</td>
                    <td class="text-right"><b>$ '.number_format($totalMes, 2).'</b></td>
                    <td class="text-center"><span class="badge '.$spanColor.'">'.$porcentaje.' %</span></td>
                    <td style="vertical-align:middle;">
                        <div class="progress progress-striped active" style="margin:0; border-radius:10px;">
                            <div class="progress-bar '.$progressColor.'" style="width: '.$porcentaje.'%"></div>
                        </div>
                    </td>
                </tr>';
            }

            $html .= '
            <tr>
                <td colspan="2" class="text-right"><b>TOTAL GENERAL:</b></td>
                <td class="text-right"><b>$ '.number_format($totalGeneral,2).'</b></td>
                <td class="text-center"><span class="badge bg-black">100 %</span></td>
                <td class="text-center"><b>VENTAS '.$anio.'</b></td>
            </tr>';
            
        }

        return array('tbody' => $html);
    } 

    public function comprasPorMes(){
        global $http;

        $anio = intval($http->request->get('anio'));
        if($anio == 0 || $anio == null || $anio == ''){
            $anio = date('Y');
        }
        
        $compras = $this->db->select('YEAR(e.fecha) AS anio, MONTH(e.fecha) AS mes, SUM(ed.cantidad * ed.costo) AS total', 
            'entrada_detalle AS ed', 
            "INNER JOIN entrada AS e ON ed.id_entrada=e.id_entrada", 
            "YEAR(e.fecha) = '$anio' AND e.id_proveedor IS NOT NULL",
            null,
            "GROUP BY MONTH(e.fecha) ORDER BY MONTH(e.fecha) DESC"
        );

        $html = '';

        if(!$compras){
            $html .= '
            <tr>
                <td colspan="5" class="text-center">Sin registros de compras para mostrar</td>
            </tr>';
        }else{
            $totalGeneral = 0;
            foreach ($compras as $key => $value){
                $tM = (real) $value['total'];
                $totalGeneral = $totalGeneral + $tM;
            }

            foreach ($compras as $key => $value){

                switch ($value['mes']) {
                    case '1':
                        $mes = 'Enero';
                        break;
                    case '2':
                        $mes = 'Febrero';
                        break;
                    case '3':
                        $mes = 'Marzo';
                        break;
                    case '4':
                        $mes = 'Abril';
                        break;
                    case '5':
                        $mes = 'Mayo';
                        break;
                    case '6':
                        $mes = 'Junio';
                        break;
                    case '7':
                        $mes = 'Julio';
                        break;
                    case '8':
                        $mes = 'Agosto';
                        break;
                    case '9':
                        $mes = 'Septiembre';
                        break;
                    case '10':
                        $mes = 'Octubre';
                        break;
                    case '11':
                        $mes = 'Noviembre';
                        break;
                    case '12':
                        $mes = 'Diciembre';
                        break;
                }

                $totalMes = (real) $value['total'];

                $porcentaje = ( (100 * $totalMes) / $totalGeneral);

                if($porcentaje < 33.33){
                    $progressColor = 'progress-bar-danger';
                    $spanColor = 'bg-red';
                }elseif($porcentaje > 33.33 && $porcentaje < 66.66){
                    $progressColor = 'progress-bar-yellow';
                    $spanColor = 'bg-yellow';
                }elseif($porcentaje > 66.66){
                    $progressColor = 'progress-bar-success';
                    $spanColor = 'bg-green';
                }

                $porcentaje = number_format($porcentaje,2);

                $html .= '
                <tr>
                    <td>'.$anio.'</td>
                    <td>'.$mes.'</td>
                    <td class="text-right"><b>$ '.number_format($totalMes, 2).'</b></td>
                    <td class="text-center"><span class="badge '.$spanColor.'">'.$porcentaje.' %</span></td>
                    <td style="vertical-align:middle;">
                        <div class="progress progress-striped active" style="margin:0; border-radius:10px;">
                            <div class="progress-bar '.$progressColor.'" style="width: '.$porcentaje.'%"></div>
                        </div>
                    </td>
                </tr>';
            }

            $html .= '
            <tr>
                <td colspan="2" class="text-right"><b>TOTAL GENERAL:</b></td>
                <td class="text-right"><b>$ '.number_format($totalGeneral,2).'</b></td>
                <td class="text-center"><span class="badge bg-black">100 %</span></td>
                <td class="text-center"><b>COMPRAS '.$anio.'</b></td>
            </tr>';
        }

        return array('tbody' => $html);
    }

    

    /**
     * Obtiene el monto de ventas clasificado por día
     *    
     *
     * @return false|array con información de las ventas
     */ 
    public function cargarVentas() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarVentas' || $metodo == null) {

                throw new ModelsException('El método recibido no está definido.');

            }

            $stmt = $this->db->select('MONTH(fechaCompra) AS mes, YEAR(fechaCompra) AS year, SUM(cantidad*precio) AS TOTAL', 'compras', null, null, null, "GROUP BY MONTH(fechaCompra) ORDER BY id ASC");

            if($stmt){

                foreach ($stmt as $key => $value) {
                    $output[] = [
                        'fecha' => $value["mes"].' '.$value["year"],
                        'ventas' => floatval($value["TOTAL"])
                    ];
                }

                return array('status' => 'success', 'message' => $output);

            }else{

                throw new ModelsException('No hay ventas.');

            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'message' => $e->getMessage());

        }

    }

    /**
     * Obtiene el total de visitas clasificados por localidades de México
     *    
     *
     * @return false|array con información de las visitas
     */ 
    public function cargarVisitas() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarVisitas' || $metodo == null) {

                throw new ModelsException('El método recibido no está definido.');

            }

            $stmt = $this->db->select('region, cantidad', 'visitasregion', null, "pais='México' AND region != 'Sin definir'");

            if($stmt){
                foreach ($stmt as $key => $value) {
                    $output[] = [
                        'region' => $value["region"],
                        'visitas' => intval($value["cantidad"])
                    ];
                }
                return array('estatus' => 'Success', 'message' => $output);
            }else{
                throw new ModelsException('No hay registro de visitas.');
            }

            
            
        } catch (ModelsException $e) {

            return array('estatus' => 'Error', 'message' => $e->getMessage());

        }

    }

    /**
     * Obtiene los 5 productos más vendidos
     *    
     *
     * @return false|array con información de los productos más vendidos
     */ 
    public function cargarMasVendidos() {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'cargarMasVendidos' || $metodo == null) {

                throw new ModelsException('El método recibido no está definido.');

            }

            $stmt = $this->db->select('id, codigo, ventas, producto', 'productos', null, "ventas != 0", 8, "ORDER BY ventas DESC");

            if($stmt){

                $arrayColores = ['#f44336', '#ff5722', '#ff9800', '#ffc107', '#ffeb3b', '#cddc39', '#8bc34a', '#4caf50'];

                /*$idTOP8 = '';*/

                foreach ($stmt as $key => $value) {

                    /*$idTOP8 .= $value['id'].','; */

                    $codigo = ($value['codigo'] == '') ? 'ID'.$value['id'] : $value['codigo'];

                    $output[] = [
                        'producto' => $codigo,
                        'porcentaje' => intval($value["ventas"]),
                        'color' => $arrayColores[$key]
                    ];
                }

                /*$idTOP8 = substr($idTOP8,0,-1);

                $stmt2 = $this->db->select('SUM(ventas) as total', 'productos', null, "id NOT IN(".$idTOP8.")");

                $output[] = [
                    'producto' => 'Todos los demás',
                    'porcentaje' => intval($stmt2[0]['total'])
                ];*/

                return array('status' => 'success', 'message' => $output);

            }else{

                throw new ModelsException('No hay más vendidos.');

            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'message' => $e->getMessage());

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