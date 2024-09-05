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
 * Modelo RegistrarCompras
 */
class RegistrarCompras extends Models implements IModels {
    use DBModel;

    /**
     * Agregar producto a la lista de productos en compra
     *
     * @param string $cant: cantidad
     * @param string $id: id de producto
     * 
    */ 
    public function agregarListaCompra($cant, $id){

        global $session;
        
        # Obtener los datos del producto por su id 
        $producto = (new Model\Productos)->producto($id);
        if (!$producto) {
            throw new ModelsException('El producto no existe.');
        }

        # Declarar $agregado como false (adelante puede cambiar a true si el producto ya esta agregado a la lista)
        $agregado = false;

        # Creamos el array de los datos del producto a agregar
        $itemProducto = [
            "idProducto" => (int) $producto['id'],                              # id del producto agregado
            "cantidad" => (int) $cant,                                          # cantidad a entrar
            "costo" => (real) $producto['precio_compra'],                       # precio de compra
            "precio" => (real) $producto['precio']                              # precio de venta
        ];

        # Si la session listaProductosCompra es igual a null
        if ( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {

            # Crear primer item de la lista
            $arrayProductosCompra[] = $itemProducto;
            # Crear el array de la session listaProductosCompra con el primer producto
            $session->set("listaProductosCompra", $arrayProductosCompra);

        # Si no entonces la session listaProductosCompra existe
        } else {

            # Obtener el array de la session listaProductosCompra
            $arrayProductosCompra = $session->get("listaProductosCompra");

            # Recorrer el array $arrayProductosCompra
            foreach ($arrayProductosCompra as $key => $value) {

                # Si el id del producto recibido ya existe
                if ($value["idProducto"] == $id) {

                    # Cambia el valor de $agregado a true
                    $agregado = true;
                    # Nueva cantidad
                    $nueva_cant = $arrayProductosCompra[$key]["cantidad"] + $cant;

                    # Actualizamos la cantidad por la nueva
                    $arrayProductosCompra[$key]["cantidad"] = $nueva_cant;
                    # Ordenamos el array array por clave en orden inverso
                    krsort($arrayProductosCompra);
                    # Crear nuevamente el array de la session listaProductosCompra con la nueva cantidad
                    $session->set("listaProductosCompra", $arrayProductosCompra);

                }

            } # end-foreach

            # Si el producto no se encuentra agregado, lo agregamos
            if($agregado === false){

                # Obtener el array de la session listaProductosCompra
                $arrayProductosCompra = $session->get("listaProductosCompra");
                # Agregamos el nuevo producto al array a la lsita existente
                $arrayProductosCompra[] = $itemProducto;
                # Ordenamos el array array por clave en orden inverso
                krsort($arrayProductosCompra);
                # Crear nuevamente el array de la session listaProductosCompra con otro producto agregado
                $session->set("listaProductosCompra", $arrayProductosCompra);
            }

        } # end-else
            
    }

    /**
     * Agregar producto a la lista de productos en compra
    */ 
    public function agregarProductoListaCompra() {
        try {
            global $http;
            $id = intval($http->request->get('key'));
            $this->agregarListaCompra(1, $id);
            return array('status' => 'success', 'title' => '¡Agregado a la lista!', 'message' => 'El producto se agregó a la lista.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Modificar la cantidad de algún producto en la lista
    */
    public function modificarCantidadCompra() {
        try {
            global $http, $session;

            $cant = intval($http->request->get('cantidad'));
            $id = intval($http->request->get('key'));

            if($cant == 0 || $cant == ''){
                $cant = 1;
            }

            $producto = (new Model\Productos)->producto($id);

            if (!$producto) {
                throw new ModelsException('El producto no existe.');
            }

            if ( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosCompra
            $arrayProductosCompra = $session->get("listaProductosCompra");

            # Recorrer el array $arrayProductosCompra
            foreach ($arrayProductosCompra as $key => $value) {

                # Buscar el item al que se le va a actualizar la cantidad
                if ($value["idProducto"] == $id) {
                    # Actualizamos la cantidad por la nueva
                    $arrayProductosCompra[$key]["cantidad"] = $cant;
                    # Crear nuevamente el array de la session listaProductosCompra con la nueva cantidad
                    $session->set("listaProductosCompra", $arrayProductosCompra);
                }

            }

            return array('status' => 'success', 'title' => '¡Cantidad actualizada!', 'message' => 'Se actualizó la cantidad del producto.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Modificar costo de compra (en la lista unicamente)
    */
    public function modificarCostoCompra() {
        try {
            global $http, $session;

            $id = intval($http->request->get('key'));
            $costo = (real) $http->request->get('costo');
            $precio = (real) $http->request->get('precio');

            if($costo == 0 || $costo === null || $costo == ''){
                throw new ModelsException('Es necesario proporcionar un valor en el precio de compra.');
            }
            
            if($costo > $precio){
                throw new ModelsException('El precio de compra debe ser menor o igual al precio de venta.');
            }

            if( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosCompra
            $arrayProductosCompra = $session->get("listaProductosCompra");

            # Recorrer el array $arrayProductosCompra
            foreach ($arrayProductosCompra as $key => $value) {
                # Buscar el item al que se le va a actualizar la cantidad
                if ($value["idProducto"] == $id) {

                    if($costo > $value["precio"]){
                        throw new ModelsException('El precio de compra debe ser menor o igual al precio de venta.');
                    }
                    # Actualizamos la cantidad por la nueva
                    $arrayProductosCompra[$key]["costo"] = $costo;
                    # Crear nuevamente el array de la session listaProductosCompra con la nueva cantidad
                    $session->set("listaProductosCompra", $arrayProductosCompra);
                }
            }
            
            return array('status' => 'success', 'title' => '¡P. Compra actualizado!', 'message' => 'El precio de compra del producto se aztualizó correctamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Modificar precio de venta (en la lista unicamente)
    */
    public function modificarPrecioVenta() {
        try {
            global $http, $session;

            $id = intval($http->request->get('key'));
            $precio = (real) $http->request->get('precio');
            $costo = (real) $http->request->get('costo');
            
            if($precio < $costo){
                throw new ModelsException('El precio de venta debe ser mayor o igual precio de compra.');
            }

            if ( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosCompra
            $arrayProductosCompra = $session->get("listaProductosCompra");

            # Recorrer el array $arrayProductosCompra
            foreach ($arrayProductosCompra as $key => $value) {
                # Buscar el item al que se le va a actualizar la cantidad
                if ($value["idProducto"] == $id) {

                    if($precio < $value["costo"]){
                        throw new ModelsException('El precio de venta debe ser mayor o igual al precio de compra.');
                    }
                    # Actualizamos la cantidad por la nueva
                    $arrayProductosCompra[$key]["precio"] = $precio;
                    # Crear nuevamente el array de la session listaProductosCompra con la nueva cantidad
                    $session->set("listaProductosCompra", $arrayProductosCompra);
                }
            }
            
            return array('status' => 'success', 'title' => '¡P. Venta actualizado!', 'message' => 'El precio de venta del producto se aztualizó correctamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }        
    }

    /**
     * Obtener los productos agregados a la lista
    */
    public function cargarListaCompras() {
        global $session, $config;

        if( $session->get('listaProductosCompra') !== null && count($session->get('listaProductosCompra')) > 0 || !empty($session->get('listaProductosCompra')) ){

            $arrayProductosCompra = $session->get("listaProductosCompra");
            $tbody = '';
            $sumaSubtotal = 0;
            $sumaCantidad = 0;

            $totalItems = count($arrayProductosCompra);

            foreach ($arrayProductosCompra as $key => $value) {
                
                $idProducto = $value['idProducto'];
                
                $miProducto = (new Model\Productos)->producto($idProducto);
                $codigo = $miProducto['codigo'];
                $producto = $miProducto['producto'];
                $leyenda = $miProducto['leyenda'];

                $precio = (real) $value['precio'];
                $costo = (real) $value['costo'];
                $cantidad = $value['cantidad'];

                $subtotal = $cantidad * $costo;

                $sumaCantidad += $cantidad;
                $sumaSubtotal += $subtotal;    
                
                $attr_liquidacion = '';
                if($miProducto["liquidacion"] == "SI"){
                    $attr_liquidacion = 'text-yellow'; 
                }

                $tbody .= '
                        <tr class="hoverTrDefault">

                            <td style="vertical-align:middle;" class="font-weight-bold text-center">
                                #'.$totalItems.'
                            </td>

                            <td style="vertical-align:middle;">
                                <div class="input-group">

                                    <span class="input-group-addon btn btnQuitarCantidad" key="'.$idProducto.'" style="padding-left:2px; padding-right:2px;">
                                        <i class="fas fa-minus fa-xs"></i>
                                    </span>  

                                    <input class="form-control input-sm text-center inputCantidad" id="inputCantidad'.$idProducto.'" key="'.$idProducto.'" type="text" value="'.$cantidad.'" placeholder="0">

                                    <span class="input-group-addon btn btnAgregarCantidad" key="'.$idProducto.'" style="padding-left:2px; padding-right:2px;">
                                        <i class="fas fa-plus fa-xs"></i>
                                    </span>

                                </div>
                            </td>
                            
                            <td style="vertical-align:middle;">
                                '.$codigo.' | '.$idProducto.' <br> <span class="font-weight-bold '.$attr_liquidacion.'">'.$producto.'</span> '.$leyenda.' <br>
                                <span class="label pill bg-black">('.$miProducto['stock'].' + '.$cantidad.' = '.($miProducto['stock'] + $cantidad).')</span>
                            </td>


			                <td style="vertical-align:middle;">
                                <div class="input-group">

                                    <span class="input-group-addon bg-gray" style="padding-left:0px; padding-right:0px;">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>

                                    <input class="form-control input-sm text-right inputCosto" id="inputCosto'.$idProducto.'" type="text" key="'.$idProducto.'" value="'.number_format($costo,2).'" placeholder="0.00">

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-default btnCosto" id="btnCosto'.$idProducto.'" key="'.$idProducto.'" disabled="">
                                            <i class="fas fa-redo-alt"></i>
                                        </button>
                                    </span>

                                </div>
                            </td>
                            
                            <td style="vertical-align:middle;">
                                <div class="input-group">

                                    <span class="input-group-addon bg-gray" style="padding-left:0px; padding-right:0px;">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>

                                    <input class="form-control input-sm text-right inputPrecio" id="inputPrecio'.$idProducto.'" type="text" key="'.$idProducto.'" value="'.number_format($precio,2).'" placeholder="0.00">

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-default btnPrecio" id="btnPrecio'.$idProducto.'" key="'.$idProducto.'" disabled="">
                                            <i class="fas fa-redo-alt"></i>
                                        </button>
                                    </span>

                                </div>
                            </td>

                            

                            <td style="vertical-align:middle;" class="text-right font-weight-bold">
                                '.number_format(($subtotal),2).'
                            </td>

                            <td style="vertical-align:middle;" class="text-right">
                                <button type="button" class="btn btn-default btn-flat btn-sm eliminarProductoListaCompra" style="margin-right:5px;" key="'.$idProducto.'">
                                    <i class="fas fa-times text-red"></i>
                                </button>
                            </td>

                        </tr>';
                        $totalItems--;
            }

            return array("status" => "llena", 
                        "tbody" => $tbody, 
                        "sumaCantidad"=> $sumaCantidad, 
                        "total" => number_format($sumaSubtotal,2));

        }else{
            $this->vaciarListaCompra();
            $tbody = '
                    <tr>
                        <td colspan="7" class="text-center">
                            No hay productos en la lista de entrada.
                        </td>
                    </tr>';
            return array("status" => "vacia", 
                        "tbody" => $tbody, 
                        "sumaCantidad"=> 0, 
                        "total" => "0.00");
        }

    }

    /**
     * Eliminar un producto de la lista
    */
    public function quitarProductoListaCompra() {
        try {
            global $http, $session;
            $id = intval($http->request->get('key'));
            
            if ( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosCompra
            $arrayProductosCompra = $session->get("listaProductosCompra");

            foreach ($arrayProductosCompra as $key => $value) {
                # Buscar el item al que se le va a actualizar la cantidad
                if ($value["idProducto"] == $id) {
                    # Eliminar producto
                    unset($arrayProductosCompra[$key]);
                    # Crear nuevamente el array de la session listaProductosCompra con la nueva cantidad
                    $session->set("listaProductosCompra", $arrayProductosCompra);
                }
            }
            
            if (count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra'))){ # - Si la cantidad de elementos es menor a 0 o esta vacia
                $this-> vaciarListaCompra();                                                                        # - Ejecutar el método para vaciar la lista de productos 
            }

            return array('status' => 'success', 'title' => '¡Eliminado!', 'message' => 'El producto se quitó de la lista.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Limpiar la lista
    */
    public function vaciarListaCompra() {
        try {
            global $session;
            if( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {
                throw new ModelsException('La lista está vacia.');
            }
            $session->remove('listaProductosCompra');
            return array('status' => 'success', 'title' => '¡Lista vacía!', 'message' => 'La lista se vacío correctamente.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
        
    }

    /**
     * Confirmar la entrada de productos como una compra
    */
    public function confirmarCompra() {
        try {

            global $http, $session;

            $proveedor = intval($http->request->get('proveedor'));
            $factura = $this->db->scape($http->request->get('factura'));

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $folio = uniqid();

            # VALIDAR SI HAY USUARIO ACTIVO
            if($this->id_user === NULL){
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }

            # VALIDAR QUE LA LISTA DE PRODUCTOS EXISTA O ESTE LLENA
            if ( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # VALIDAR PROVEEDOR
            $datosProveedor = (new Model\Proveedores)->proveedor($proveedor);
            if(!$datosProveedor){
                throw new ModelsException('SELECCIONAR UN PROVEEDOR de la lista.');
            }
            if($proveedor == 1 || $proveedor == 2){
                throw new ModelsException('Para realizar una compra se debe SELECCIONAR UN PROVEEDOR.');
            }

            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);

            $idEntrada = $this->db->insert('entrada', [
                'id_almacen' => $almacen['id_almacen'],
                'folio' => strtoupper($folio),
                'factura' => $factura,
                'id_proveedor' => $proveedor,
                'fecha' => $fecha,
                'hora' => $hora,
                'usuario_entrada' => $this->id_user
            ]);

            # OBTENER DETALLES DE LA LISTA DE PRODUCTOS EN COMPRA
            $arrayProductosCompra = $session->get("listaProductosCompra");

            $sumaCantidad = 0;

            foreach ($arrayProductosCompra as $key => $value) { // En la compra se toman todos los valores de la sesión de compras por que puede que se hayan actualizado los precios 

                $idProducto = intval($value['idProducto']);
                $cantidad = (int) $value['cantidad'];
                $costo = (real) $value['costo'];
                $precio = (real) $value['precio'];

                $producto = (new Model\Productos)->producto($idProducto);
                $nuevoStock = $producto['stock'] + $cantidad;
                $nuevasEntradas = $producto['total_entradas'] + $cantidad;

                $sumaCantidad += $cantidad;
                
                $descuento_proveedor = 100 - ( ($costo * 100) / $precio );
                // REVISAR SI HAY COSTOS DESCUENTOS DE PROVEEDOR REGISTRADOS
                $hay_descuentos = $this->db->select("id_pp", "producto_descuento_provedor", null, "id_producto = '$idProducto' AND id_proveedor = '$proveedor'", 1);
                // SI HAY DESCUENTOS
                if($hay_descuentos){
                    $id_pp = $hay_descuentos[0]['id_pp'];
                    # ACTUALIZAR CPSTOS DESCUENTOS PROVEEDOR
                    $this->db->update('producto_descuento_provedor', array(
                        'costo' => $costo, 
                        'precio' => $precio, 
                        'descuento' => $descuento_proveedor, 
                        'fecha' => $fecha, 
                    ), "id_pp = '$id_pp'", 1); 
                }else{
                    # REGISTRAR COSTO DESCUENTO DE PROVEEDOR
                    $this->db->insert('producto_descuento_provedor', [
                        'id_producto' => $idProducto,
                        'id_proveedor' => $proveedor,
                        'costo' => $costo, 
                        'precio' => $precio, 
                        'descuento' => $descuento_proveedor, 
                        'fecha' => $fecha
                    ]); 
                }

                if($precio != (real) $producto['precio']){
                    # ACTUALIZAR STOCK, COSTO, PRECIO, ENTRADAS Y QUITAR OFERTA
                    $this->db->update('productos', array(
                        'stock' => $nuevoStock, 
                        'precio_compra' => $costo, 
                        'precio' => $precio, 
                        'ofertadoPorCategoria' => 0, 
                        'ofertadoPorSubcategoria' => 0, 
                        'ofertadoPorEditorial' => 0,
                        'ofertadoPorAutor' => 0,
                        'oferta' => 0, 
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => '',
                        'total_entradas' => $nuevasEntradas
                    ), "id = '$idProducto'", 1); 
                }else{
                    # ACTUALIZAR STOCK, COSTO, PRECIO Y ENTRADAS
                    $this->db->update('productos', array(
                        'stock' => $nuevoStock, 
                        'precio_compra' => $costo,
                        'precio' => $precio,
                        'total_entradas' => $nuevasEntradas
                    ), "id = '$idProducto'", 1); 
                }                

                # REGISTRAR DETALLE DE ENTRADA
                $this->db->insert('entrada_detalle', [
                    'id_entrada' => $idEntrada,
                    'id_producto' => $idProducto,
                    'cantidad' => $cantidad,
                    'costo' => $costo,
                    'precio' => $precio,
                    'estado' => 1
                ]); 

                # REGISTRAR CARDEX DE PRODUCTOS
                $this->db->insert('productos_cardex', [
                    'id_producto' => $idProducto,
                    'cantidad' => $cantidad,
                    'id_almacen' => $almacen['id_almacen'],
                    'id_clienteProveedor' => $proveedor,
                    'costo' => $costo,
                    'precio' => $precio,
                    'descuento' => $descuento_proveedor,            # descuento al que entra
                    'operacion' => 'compra',
                    'movimiento' => 'ce',                           # (ce) = compra entrada
                    'referencia' => $folio,
                    'fecha' => $fecha.' '.$hora
                ]); 

            }

            # ACTUALIZAR COMPRAS A PROVEEDOR
            $compras = $datosProveedor['compras'];
            $nuevasCompras = $compras + $sumaCantidad;

            $this->db->update('proveedores',array(
                'compras' => $nuevasCompras, 
                'fecha_ultima_compra' => $fecha.' '.$hora
            ),"id_proveedor='$proveedor'");

            return array('status' => 'success', 'title' => '¡Compra registrada!', 'message' => 'La compra se registró correctamente');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Confirmar la entrada de productos como un ajuste (sumar cantidades)
    */
    public function ajusteEntrada() {
        try {
            global $http, $session;
            $accion = intval($http->request->get('accion'));

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $folio = strtoupper(uniqid());

            # VALIDAR SI HAY USUARIO ACTIVO
            if($this->id_user === NULL){
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            
            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador['control'] == 0){ # - Si el usuario no tiene control (0)
                throw new ModelsException('No tienes los permisos necesarios para realizar ajuste de inventario.');
            }

            # VALIDAR QUE LA LISTA DE PRODUCTOS EXISTA O ESTE LLENA
            if ( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # VALIDAR ACCION
            if($accion == 0){
                throw new ModelsException('SELECCIONAR UNA ACCIÓN de la lista.');
            }else{
                $datosAccion = (new Model\Proveedores)->proveedor($accion);
                if(!$datosAccion){
                    throw new ModelsException('La acción no existe.');
                }
                if($accion > 2){
                    throw new ModelsException('Para procesar el ajuste se debe SELECCIONAR LA ACCIÓN correspondiente.');
                }
            }

            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);

            $idEntrada = $this->db->insert('entrada', [
                'id_almacen' => $almacen['id_almacen'],
                'folio' => $folio,
                'id_accion' => $accion,
                'fecha' => $fecha,
                'hora' => $hora,
                'usuario_entrada' => $this->id_user
            ]);
            
            # OBTENER DETALLES DE LA LISTA DE PRODUCTOS EN COMPRA
            $arrayProductosCompra = $session->get("listaProductosCompra");

            $sumaCantidad = 0;
            foreach ($arrayProductosCompra as $key => $value) { // En la compra se toman todos los valores de la sesión de compras por que puede que se hayan actualizado los precios 
                
                $idProducto = intval($value['idProducto']);
                $cantidad = (int) $value['cantidad'];
                $costo = (real) $value['costo'];
                $precio = (real) $value['precio'];

                $producto = (new Model\Productos)->producto($idProducto);
                $nuevoStock = $producto['stock'] + $cantidad;
                $nuevasEntradas = $producto['total_entradas'] + $cantidad;

                $sumaCantidad += $cantidad;

                if($precio != (real) $producto['precio']){
                    # ACTUALIZAR STOCK, COSTO, PRECIO, ENTRADAS Y QUITAR OFERTA
                    $this->db->update('productos', array(
                        'stock' => $nuevoStock, 
                        'precio_compra' => $costo, 
                        'precio' => $precio, 
                        'ofertadoPorCategoria' => 0, 
                        'ofertadoPorSubcategoria' => 0, 
                        'ofertadoPorEditorial' => 0,
                        'ofertadoPorAutor' => 0,
                        'oferta' => 0, 
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => '',
                        'total_entradas' => $nuevasEntradas
                    ), "id = '$idProducto'", 1); 
                }else{
                    # ACTUALIZAR STOCK, COSTO, PRECIO Y ENTRADAS
                    $this->db->update('productos', array(
                        'stock' => $nuevoStock, 
                        'precio_compra' => $costo,
                        'precio' => $precio,
                        'total_entradas' => $nuevasEntradas
                    ), "id = '$idProducto'", 1); 
                }                

                # REGISTRAR DETALLE DE ENTRADA
                $this->db->insert('entrada_detalle', [
                    'id_entrada' => $idEntrada,
                    'id_producto' => $idProducto,
                    'cantidad' => $cantidad,
                    'costo' => $costo,
                    'precio' => $precio,
                    'estado' => 1
                ]); 
                
                # REGISTRAR CARDEX DE PRODUCTOS
                $this->db->insert('productos_cardex', [
                    'id_producto' => $idProducto,
                    'cantidad' => $cantidad,
                    'id_almacen' => $almacen['id_almacen'],
                    'id_clienteProveedor' => $accion,
                    'costo' => $costo,
                    'precio' => $precio,
                    'operacion' => 'entrada',
                    'movimiento' => 'ae',                       # (ae) = ajuste entrada
                    'referencia' => $folio,
                    'fecha' => $fecha.' '.$hora
                ]);
            }

            # ACTUALIZAR COMPRAS A LA ACCIÓN CORRESPONDIENTE
            $compras = $datosAccion['compras'];
            $nuevasCompras = $compras + $sumaCantidad;
            
            $this->db->update('proveedores',array(
                'compras' => $nuevasCompras, 
                'fecha_ultima_compra' => $fecha.' '.$hora
            ),"id_proveedor='$accion'");

            return array('status' => 'success', 'title' => '¡Ajuste registrado!', 'message' => 'El ajuste de inventario se proceso correctamente, con el folio '.$folio.'.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage()); 
        }
    }

    /**
     * Confirmar la entrada de productos como una entrada de consignación
    */
    public function consignacionEntrada() {
        try {
            global $http, $session;
            $proveedor = intval($http->request->get('proveedor'));
            $factura = $this->db->scape($http->request->get('factura'));

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $folio = strtoupper(uniqid());

            # VALIDAR SI HAY USUARIO ACTIVO
            if($this->id_user === NULL){
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }

            # VALIDAR QUE LA LISTA DE PRODUCTOS EXISTA O ESTE LLENA
            if ( $session->get('listaProductosCompra') === null || count($session->get('listaProductosCompra')) == 0 || empty($session->get('listaProductosCompra')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # VALIDAR PROVEEDOR
            $datosProveedor = (new Model\Proveedores)->proveedor($proveedor);
            if(!$datosProveedor){
                throw new ModelsException('SELECCIONAR UN PROVEEDOR de la lista.');
            }
            if($proveedor == 1 || $proveedor == 2){
                throw new ModelsException('Para realizar una consignación se debe SELECCIONAR UN PROVEEDOR.');
            }

            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);

            $id_ajuste = $this->db->insert('consignacion', array(
                'id_almacen' => $almacen['id_almacen'],
                'folio' => $folio,
                'referencia' => $factura,
                'id_proveedor' => $proveedor,
                'tipo' => 'entrada',
                'usuario_consignacion' => $this->id_user,
                'fecha' => $fecha,
                'hora' => $hora
            ));

            # OBTENER DETALLES DE LA LISTA DE PRODUCTOS
            $arrayProductosCompra = $session->get("listaProductosCompra");

            $sumaCantidad = 0;

            foreach ($arrayProductosCompra as $key => $value) { // En la compra se toman todos los valores de la sesión de compras por que puede que se hayan actualizado los precios 

                $id = intval($value['idProducto']);
                $costo = (real) $value['costo'];
                $precio = (real) $value['precio'];
                $cantidad = (int) $value['cantidad'];
                
                $producto = (new Model\Productos)->producto($id);
                $nuevoStock = $producto['stock'] + $cantidad;
                $nuevasEntradas = $producto['total_entradas'] + $cantidad;

                $sumaCantidad += $cantidad;
                
                $descuento_proveedor = 100 - ( ($costo * 100) / $precio );
                
                // REVISAR SI HAY DESCUENTOS DE PROVEEDOR REGISTRADOS
                $hay_descuentos = $this->db->select("id_pp", "producto_descuento_provedor", null, "id_producto = '$id' AND id_proveedor = '$proveedor'", 1);
                // SI HAY DESCUENTOS
                if($hay_descuentos){
                    $id_pp = $hay_descuentos[0]['id_pp'];
                    # ACTUALIZAR DESCUENTOS PROVEEDOR
                    $this->db->update('producto_descuento_provedor', array(
                        'costo' => $costo, 
                        'precio' => $precio, 
                        'descuento' => $descuento_proveedor, 
                        'fecha' => $fecha, 
                    ), "id_pp = '$id_pp'", 1); 
                }else{
                    # REGISTRAR DESCUENTO DE PROVEEDOR
                    $this->db->insert('producto_descuento_provedor', [
                        'id_producto' => $id,
                        'id_proveedor' => $proveedor,
                        'costo' => $costo, 
                        'precio' => $precio, 
                        'descuento' => $descuento_proveedor, 
                        'fecha' => $fecha
                    ]); 
                }

                if($precio != (real) $producto['precio']){
                    # ACTUALIZAR STOCK, COSTO, PRECIO, ENTRADAS Y QUITAR OFERTA
                      $this->db->update('productos',array(
                        'stock' => $nuevoStock,
                        'precio_compra' => $costo, 
                        'precio' => $precio, 
                        'ofertadoPorCategoria' => 0, 
                        'ofertadoPorSubcategoria' => 0, 
                        'ofertadoPorEditorial' => 0,
                        'ofertadoPorAutor' => 0,
                        'oferta' => 0, 
                        'precioOferta' => 0,
                        'descuentoOferta' => 0,
                        'finOferta' => '',
                        'total_entradas' => $nuevasEntradas
                    ),"id='$id'", 1);

                }else{
                    # ACTUALIZAR STOCK, COSTO, PRECIO Y ENTRADAS
                    $this->db->update('productos', array(
                        'stock' => $nuevoStock, 
                        'precio_compra' => $costo,
                        'precio' => $precio,
                        'total_entradas' => $nuevasEntradas
                    ), "id = '$id'", 1); 
                }  

                # REGISTRAR DETALLE DE CONSIGNACION
                $this->db->insert('consignacion_detalle', array(
                    'id_consignacion' => $id_ajuste,
                    'id_producto' => $id,
                    'cantidad' => $cantidad,
                    'costo' => $costo,
                    'precio' => $precio
                ));
                
                
                # REGISTRAR CARDEX DE PRODUCTOS (entrada como un alias para consignación de entrada)
                $this->db->insert('productos_cardex', array(
                    'id_producto' => $id,
                    'cantidad' => $cantidad,
                    'id_almacen' => $almacen['id_almacen'],
                    'id_clienteProveedor' => $proveedor,
                    'costo' => $costo,
                    'precio' => $precio,
                    'descuento' => $descuento_proveedor,                    # descuento al que entra
                    'operacion' => 'entrada',
                    'movimiento' => 'ce',                                   # (ce) = consignacion entrada
                    'referencia' => $folio,
                    'fecha' => $fecha.' '.$hora,
                ));

            }
            
            # ACTUALIZAR COMPRAS A PROVEEDOR
            $compras = $datosProveedor['compras'];
            $nuevasCompras = $compras + $sumaCantidad;

            $this->db->update('proveedores',array(
                'compras' => $nuevasCompras, 
                'fecha_ultima_compra' => $fecha.' '.$hora
            ),"id_proveedor='$proveedor'");

            return array('status' => 'success', 'title' => '¡Entrada de consignación registrada!', 'message' => 'La entrada de consignación se registró correctamente.');

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