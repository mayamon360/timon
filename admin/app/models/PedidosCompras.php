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
 * Modelo PedidosCompras
 */
class PedidosCompras extends Models implements IModels {
    use DBModel;
    
    public function nuevoPedido() {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            $id_pedido = $this->db->select("id_e", "encargo", null, "estado = 1");
            
            if($id_pedido){
                
                $id_pedido = $id_pedido[0]['id_e'];
                
                $pedido_detalle = $this->db->select(
                    "*", 
                    "encargo_detalle", 
                    null,
                    "id_e = '$id_pedido'"
                );
                $productos = count($pedido_detalle);
                if($productos > 0){
                    
                    $this->db->update('encargo', array(
                        'estado' => 2
                    ),"id_e='$id_pedido'", 1);
                    
                }else{
                    throw new ModelsException('El pedido actual está vacío.'); 
                }
                
            
            }
            
            $this->db->insert('encargo', array(
                'folio' => strtoupper(uniqid()),
                'estado' => 1
            ));
            
            return array('status' => 'success', 'title' => '¡Registrado!', 'message' => 'Se creó un nuevo pedido.');
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    public function agregarPedido() {
        
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
            
            $id_pedido = $this->db->select("id_e", "encargo", null, "estado = 1");
            if($id_pedido){
                $id_pedido = $id_pedido[0]['id_e'];
            }else{
                $id_pedido = $this->db->insert('encargo', array(
                    'folio' => strtoupper(uniqid()),
                    'estado' => 1
                ));
            }
            
            global $http;
            
            # ID PRODUCTO       --------------------------------------------------------------------------------------------------- ID PRODUCTO
            $id = (int) $http->request->get('id_producto');                     # si viene un id es que se selecciono uno que ya existe en la base de datos
            
            # CANTIDAD          --------------------------------------------------------------------------------------------------- CANTIDAD
            $cantidad = (int) $http->request->get('cantidadNuevo');             # cantidad pedido
            if($cantidad == 0){
                $cantidad = 1;
            }
            
            if($id != 0){
                
                $producto = (new Model\Productos)->producto($id);
                if(!$producto){
                    throw new ModelsException("El producto no existe.");
                }
                $codigoP = $producto['codigo'];
                $nombreP = $producto['producto'];
                $leyenda = $producto['leyenda'];
                $precioC = $producto['precio_compra'];
                $precioR = $producto['precio'];
                
            }else{
                
                # CODIGO            --------------------------------------------------------------------------------------------------- CODIGO
                $codigoP = $this->db->scape(trim($http->request->get('codigoP')));          # codigo del producto
                if($codigoP == "" || $codigoP === null){                                    # si el codigo viene vacio
                    $codigoAleatorio = (new Model\Productos)->generarCodigoProducto();                      # generar un codigo aleatorio
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
                $rutaP = Helper\Strings::url_amigable(mb_strtolower($nombreP, 'UTF-8'));
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
                
                # Registrar cabeceras
                $this->db->insert('cabeceras', array(
                    'ruta' => $rutaP,
                    'titulo' => $nombreP,
                    'descripcion' => 'Sin descripción',
                    'palabrasClave' => "$nombreP $leyenda, $codigoP"
                ));
                
                # Registrar producto
                $id = $this->db->insert('productos', array(
                    "codigo" => $codigoP,
                    "stock" => 0,
                    "stock_minimo" => 3,
                    "autores" => $autores,
                    "id_editorial" => $editorial,
                    "idCategoria" => 1,
                    "idSubcategoria" => 1,
                    "ruta" => $rutaP,
                    "producto" => $nombreP,
                    "leyenda" => $leyenda,
                    "descripcion" => 'Sin descripción',
                    "detalles" => '',
                    "precio_compra" => $precioC,
                    "precio" => $precioR,
                    "ofertadoPorCategoria" => 0,
                    "ofertadoPorSubcategoria" => 0,
                    "oferta" => 0,
                    "precioOferta" => 0,
                    "descuentoOferta" => 0,
                    "finOferta" => '',
                    "fechaRegistro" => date('Y-m-d H:i:s'),
                    "usuarioRegistro" => $this->id_user,
                    "estado" => 1
                ));
                
                # Reistrar autores asociados
                $arrayAutores = explode(",", $autores );
                foreach($arrayAutores as $key => $value){
                    $this->db->insert('productos_autores', array(
                        "id_producto" => $id,
                        "id_autor" => $value
                    ));
                }
                
            }
            
            # ANTICIPO          --------------------------------------------------------------------------------------------------- ANTICIPO
            $anticipo = (real) $http->request->get('anticipo');                 # Precio de venta
            $total = $cantidad * $precioR;
            $anticipo_minimo = $total / 2;
            if($anticipo < $anticipo_minimo){
                throw new ModelsException("El anticipo minimo es de ".number_format($anticipo_minimo,2));
            }
            if($anticipo > $total){
                throw new ModelsException("El anticipo supera el monto de pedido");
            }
            
            $metodoPago = $this->db->scape($http->request->get('metodoPago'));  # - Obtener metodo de pago -
            $arrayMetodosPago = ['efectivo', 'tarjeta'];
            if(!in_array($metodoPago, $arrayMetodosPago)){
                throw new ModelsException('Selecciona un metódo de pago válido.');
            }
            
            $a_nombre = $this->db->scape($http->request->get('a_nombre'));      # - Obtener a nombre de quien -
            if($a_nombre == ''){                                                # si es "", enviar error
                throw new ModelsException("Ingrese a nombre de quien se registrara el anticipo.");
            }
            
            $fecha = date("Y-m-d");
            $hora = date("H:i:s");
            $folio_pedido = strtoupper(uniqid());                               # folio pedido
            
            $this->db->insert('encargo_detalle', array(
                'id_e' => $id_pedido,
                'folio_pedido' => $folio_pedido,
                'fecha_encargo' => $fecha,
                'hora_encargo' => $hora,
                'id_producto' => $id,
                'cantidad' => $cantidad,
                'anticipo' => $anticipo,
                'codigo_anticipo' => rand(10000, 99999),
                'metodo_pago' => $metodoPago,
                'a_nombre' => $a_nombre
            ));

            if($metodoPago == 'efectivo'){  
            
                $tipo = 'Ingreso';
                $descripcion = "Encargo de $cantidad libro(s) de $id - $codigoP | $nombreP $leyenda";    
                
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
                
            }

            return array('status' => 'success', 'title' => '¡Registrado!', 'message' => 'El producto '.mb_strtoupper($nombreP, 'UTF-8').' se agregó al pedido.', 'redireccionar' => $redireccionar);
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
        
    }
    
    public function cargarListaPedido() {
        
        $pedido = $this->db->select("id_e", "encargo", null, "estado = 1");
        
        if($pedido){
            
            $folio_general = $pedido[0]['folio'];
            $id_pedido = $pedido[0]['id_e'];
            $pedido_detalle = $this->db->select(
                "ed.a_nombre, ed.cantidad, p.id, p.codigo, p.producto, p.leyenda, p.precio_compra, p.precio, ed.anticipo, ed.metodo_pago", 
                "encargo_detalle AS ed", 
                "INNER JOIN productos AS p ON ed.id_producto=p.id", 
                "ed.id_e = '$id_pedido'",
                null,
                "ORDER BY ed.id_ed DESC"
            );
            
            if($pedido_detalle){
                $status = 'llena';
                foreach($pedido_detalle as $key => $value){
                    
                    $nombre = $value['a_nombre'];
                    $cantidad = (int) $value['cantidad'];
                    $id = $value['id'];
                    $codigo = $value['codigo'];
                    $producto = '<strong>'.$value['producto'].'</strong> '.$value['leyenda'];
                    $costo = (real) $value['precio_compra'];
                    $precio = (real) $value['precio'];
                    $anticipo = (real) $value['anticipo'];
                    
                    if($value['metodo_pago'] == 'efectivo'){
                        $metodo = '<span class="text-green">Efectivo</span>';
                    }else{
                        $metodo = '<span class="text-black">Tarjeta</span>';
                    }
    
                    $tbody .= '
                    <tr>
                        <td style="vertical-align:middle;">
                            '.$nombre.'
                        </td>
                        <td class="text-center" style="vertical-align:middle;">
                            '.$cantidad.'
                        </td>
                        <td style="vertical-align:middle;">
                            '.$id.' | '.$codigo.' <br> '.$producto.' 
                        </td>
                        <td class="text-center" style="vertical-align:middle;">
                            '.number_format($costo,2).'
                        </td>
                        <td class="text-center font-weight-bold" style="vertical-align:middle;">
                            '.number_format($precio,2).'
                        </td>
                        <td class="text-right" style="vertical-align:middle;">
                            '.number_format(($cantidad * $costo),2).'
                        </td>
                        <td class="text-right font-weight-bold" style="vertical-align:middle;">
                            '.number_format(($cantidad * $precio),2).'
                        </td>
                        <td class="text-center font-weight-bold text-purple" style="vertical-align:middle;">
                            '.number_format($anticipo,2).'
                        </td>
                        <td class="text-center font-weight-bold" style="vertical-align:middle;">
                            '.$metodo.'
                        </td>
                    </tr>'; 
                    
                }
                
            }else{
                $status = 'vacia';
                $tbody = '
                <tr>
                    <td colspan="9" class="text-center">
                        No hay productos en la lista.
                    </td>
                </tr>';
                
            }
            
        }else{
            $folio_general = '';
            $status = 'vacia';
            $tbody = '
            <tr>
                <td colspan="9" class="text-center">
                    No hay productos en la lista.
                </td>
            </tr>';
            
        }
        
        return array(
            "status" => $status,
            "tbody" => $tbody,
            "folio_general" => $folio_general
        );

    }
    
    public function productoPedidoFormulario() {
        try {
            
            if($this->id_user === NULL) {                                       # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            global $http;
            
            # ID PRODUCTO
            $id = (int) $http->request->get('key'); 
            $producto = (new Model\Productos)->producto($id);
            if(!$producto){
                throw new ModelsException("El producto no existe.");
            }
            $codigoP = $producto['codigo'];
            $nombreP = $producto['producto'];
            $leyenda = $producto['leyenda'];
            $precioC = $producto['precio_compra'];
            $precioR = $producto['precio'];
            
            $editorial = (new Model\Editoriales)->editorial($producto["id_editorial"]);
            $editorial = $editorial["editorial"];
            
            $arrayAutores = explode(",", $producto["autores"]);
            $autores = "";
            foreach($arrayAutores as $key1 => $value2){
                $autor = (new Model\Autores)->autor($value2);
                $autores .= $autor['autor'].', ';
            }
            $autores = substr($autores, 0, -2); 
            
            return array(
                'status' => 'success',
                'id' => $id,
                'codigo' => $codigoP,
                'producto' => $nombreP,
                'leyenda' => $leyenda,
                'costo' => $precioC,
                'precio' => $precioR,
                'editorial' => $editorial,
                'autores' => $autores,
                'anticipo' => ($precioR / 2)
            );
            
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