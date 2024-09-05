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
 * Modelo PuntoDeVenta
 */
class PuntoDeVenta extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene todos los comprobantes
     * 
     * @return false|array
    */ 
    public function comprobantes() {
        $comprobantes = $this->db->select('*', 'comprobantes', null, "estado=1");
        return $comprobantes;
    }

    /**
     * Obtiene datos de un comprobante según su id en la base de datos
     * 
     * @param int $id: Id del comprobante a obtener
     *
     * @return false|array[0]
    */ 
    public function comprobante($id) {
        $comprobante = $this->db->select("*", 'comprobantes', null, "id_comprobante='$id'", 1);
        if( $comprobante ) {
            return $comprobante[0];
        } else {
            return false;
        }
    }

    /**
     * Obtiene los datos del ticket
     *
     * @return false|array[0]
    */ 
    public function datosTicket() {
        $datosTicket = $this->comprobante(1);
        $salida = $this->db->select('MAX(numero) AS ultimo', 'salida', null, "comprobante='1'");
        if($salida === NULL){
            $ultimaVentaTicket = 1;
        }else{
            $ultimaVentaTicket = intval($salida[0]['ultimo'])+1;
        }
        $serie = $datosTicket['prefijo'].'-'.substr(date('Y'), -2);
        $serie .= '-'.date('m');
        $numero = str_pad($ultimaVentaTicket, 6, "0", STR_PAD_LEFT);
        return array("serie" => $serie, "numero" => $numero, "nVenta" => $ultimaVentaTicket);
    }

    /**
     * Valida si los productos en la lista aun se encuentran en stock
     *
     * @return void
    */ 
    public function validarProductosListaVenta() {
        global $session;
        $cambioStock = false;
        
        # - Si la lista de productos no esta vacía -
        if( $session->get('listaProductosVenta') !== null && count($session->get('listaProductosVenta')) > 0 && !empty($session->get('listaProductosVenta')) ) { 

            $arrayProductosVenta = $session->get("listaProductosVenta");                                        # - Obtener los productos en la lista -
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);                                 # - Realizar consulta para obtener el almacen del usuario activo - 

            foreach ($arrayProductosVenta as $key => $value) {                                                  # - Recorrer productos en la lista -

                $idProducto = $value['idProducto'];                                                             # - id del producto -
                $cant = (int) $value['cantidad'];                                                               # - cantidad solicitdada -
                $stock = (new Model\Productos)->stock_producto($idProducto, $almacen['id_almacen']);            # - Obtener el stock del producto
                                                                                       
                if($stock < $cant && $stock != 0) {                                                            # - Si el stock es menor a la cantidad solicitada y diferente de 0 -                         
                    $arrayProductosVenta[$key]["cantidad"] = $stock;                                            # - Asignar el stock a la cantidad solicitada -
                    $session->set("listaProductosVenta", $arrayProductosVenta);                               # - Actualizar la lista de productos con la nueva cantidad del producto -
                    $cambioStock = true;
                }

                if($stock == 0) {                                                                               # - Si el stock es igual a 0 -
                    unset($arrayProductosVenta[$key]);                                                          # - Eliminar producto de la lista -   
                    $session->set("listaProductosVenta", $arrayProductosVenta);                               # - Actualizar la lista de productos para quitar el producto eliminado -
                    $cambioStock = true;
                }
            }

            # - Si la cantidad de elementos es menor a 0
            if( count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ){
                $this->vaciarListaVenta();                                                                      # - Ejecutar el método para vaciar la lista de productos 
            }

        }
        return $cambioStock;

    }

    /**
     * Carga la lista de los productos
     *
     * @return array
    */
    public function cargarListaVenta() {
        global $session;
        $this->validarProductosListaVenta();                                    # - Validar que los productos en la lista esten en stock -
        
        # - Si lista de productos no esta vacía -
        if($session->get('listaProductosVenta') !== null && count($session->get('listaProductosVenta')) > 0 && !empty($session->get('listaProductosVenta')) ) {

            $arrayProductosVenta = $session->get("listaProductosVenta");        # - Obtener los productos en la lista - 
            $tbody = '';

            $sumaCantidad = 0;
            $sumaSubtotal = 0;

            $totalItems = count($arrayProductosVenta);

            foreach ($arrayProductosVenta as $key => $value) {                  # - Recorrer productos en la lista -

                $idProducto = $value['idProducto'];                             # - id del producto -
                $cantidad = (int) $value['cantidad'];                           # - cantidad solicitdada -
                $descuento = (int) $value['descuento'];                         # - descuento aplicado
                $miProducto = (new Model\Productos)->producto($idProducto);     # - Realizar consulta del producto por su id para obtener datos -
                $codigo = $miProducto['codigo'];                                # - Obtener el código del producto -
                $producto = $miProducto['producto'];                            # - Obtener nombre del producto -
                $leyenda = $miProducto['leyenda'];                              # - Obtener leyenda del producto -
                $precio = (real) $miProducto['precio'];                         # - Obtener el precio del producto - 

                if($descuento != 0){                                            # - Si el descuento es diferente de 0 -
                    $precioDescuento = $precio - ( ($precio * $descuento) / 100 );  # - Calcular el precio con descuento -
                    $subtotal = $cantidad * $precioDescuento;                   # - Calcular subtotal con el precio descuento -
                }else{                                                          # - Si el descuento es 0 -
                    $precioDescuento = 0;                                       # - Definir precio descuento como 0
                    $subtotal = $cantidad * $precio;                            # - Calcular sobtotal con el precio normal -
                }
                $sumaCantidad += $cantidad;                                     # - Sumar la cantidad de cada producto -
                $sumaSubtotal += $subtotal;                                     # - Sumar el subtotal de cada producto -

                if($precioDescuento != 0){                                      # - Si el precio descuento es diferente de 0 -
                    $precioFormato = '<del class="text-muted">'.number_format($precio, 2).'</del> <strong>'.number_format($precioDescuento,2).'</strong>'; # - Mostrar precio normal y precio con descuento -
                }else{
                    $precioFormato = '<strong>'.number_format($precio, 2).'</strong>'; # - Mostrar solo precio normal -
                }
                
                $attr_liquidacion = '';
                if($miProducto["liquidacion"] == "SI"){
                    $attr_liquidacion = 'text-yellow'; 
                }
                
                $tbody .= '
                        <tr class="hoverTrDefault">

                            <td style="vertical-align:middle;" class="font-weight-bold text-center">
                            # '.$totalItems.'
                            </td>

                            <td style="vertical-align:middle;">
                                <div class="input-group">

                                    <span class="input-group-addon btn btnQuitarCantidad" key="'.$idProducto.'" style="padding-left:2px; padding-right:2px;">
                                        <i class="fas fa-minus fa-xs"></i>
                                    </span>  

                                    <input class="form-control input-sm text-center inputCantidad" id="inputCantidad'.$idProducto.'" key="'.$idProducto.'" type="text" value="'.$cantidad.'">

                                    <span class="input-group-addon btn btnAgregarCantidad" key="'.$idProducto.'" style="padding-left:2px; padding-right:2px;">
                                        <i class="fas fa-plus fa-xs"></i>
                                    </span>

                                </div>
                            </td>

                            <td style="vertical-align:middle;">
                                '.$codigo.' | '.$idProducto.' <br> <span class="font-weight-bold '.$attr_liquidacion.'">'.$producto.'</span> '.$leyenda.' <br>
                                <span class="label pill bg-black">('.$miProducto['stock'].' - '.$cantidad.' = '.($miProducto['stock'] - $cantidad).')</span>
                            </td>
                            <td style="vertical-align:middle; text-align:center;">
                                '.$precioFormato.'
                            </td>

                            <td style="vertical-align:middle;" class="text-center">
                                <div class="input-group pull-right divInputDescuento" style="width:60px;">

                                    <input class="form-control input-sm inputDescuento" type="text" key="'.$idProducto.'" value="'.$descuento.'" placeholder="0">
                                    <span class="input-group-addon bg-gray" style="padding-left:1px; padding-right:1px;"><i class="fas fa-percent fa-xs"></i></span>

                                </div>
                            </td>

                            <td style="vertical-align:middle;" class="text-right font-weight-bold">'.number_format(($subtotal),2).'</td>

                            <td style="vertical-align:middle;" class="text-right">
                                <button type="button" class="btn btn-default btn-flat btn-sm eliminarProductoListaVenta" style="margin-right:5px;" key="'.$idProducto.'">
                                    <i class="fas fa-times text-red"></i>
                                </button>
                            </td>

                        </tr>';
                        $totalItems--;
            }

            $total = $sumaSubtotal;                                         # - Asignar al total general la suma de los subtotales - 
            $totalFormat = number_format($total,2);                         # - Agregar formato al total ( Ej. $ 100.00 ) -

            return array("status" => "llena",                               # - Retornar el estatus de la lista como "llena" -
                        "tbody" => $tbody,                                  # - Retornar la tabla con formato html para mostrar los productos en lista -
                        "sumaCantidad"=> $sumaCantidad,                     # - Retornar la suma de las cantidades de cada producto -
                        "totalG" => $totalFormat, # Total con formato       # - Retornar el total general con formato -
                        "total" => $total); # Total sin formato             # - Retornar el total general sin formato -
                        

        }else{                                                              # - Si lista de productos esta vacía - 
            $this->vaciarListaVenta();                                      # - Vaciar la lista para asegurarnos que no exista elementos vacíos en la lista -
            $tbody = '
                    <tr>
                        <td colspan="6" class="text-center">
                            No hay productos en la lista de salida.
                        </td>
                    </tr>';
            return array("status" => "vacia",                               # - Retornar el estatus de la lista como "vacia" -
                        "tbody" => $tbody,                                  # - Retornar la tabla con formato html para mostrar mensaje 'No hay productos en la lista.' -
                        "sumaCantidad"=> 0,                                 # - Retornar la suma de las cantidades de cada producto como 0 - 
                        "totalG" => "0.00",                                 # - Retornar el total general con formato como ( $ 0.00 ) -
                        "total" => 0);                                      # - Retornar el total general sin formato como ( 0 ) -
        }

    }

    /**
     * Agregar producto a la lista de productos en venta
     *
     * @param string $cant: cantidad
     * @param string $id: id de producto
     * 
    */ 
    public function agregarListaVenta($cant, $id){
        
        global $session;                                                                        // cargar sesiones
        $producto = (new Model\Productos)->producto($id);                                       // obtener datos del producto por el id
        if(!$producto) {                                                                        // si el producto no existe, error
            throw new ModelsException('El producto no existe.');
        }
        
        if($cant == 0) {
            $cant = 1;
        }

        $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);                     // obtener los datos del almacen por el usuario que este activo
        
        $stock_almacen = (new Model\Productos)->stock_producto($id, $almacen['id_almacen']);    // obtener el stock del producto
        if($stock_almacen < 1) {                                                                // si el stock es menor a 1, error
            throw new ModelsException('El stock del producto en este alamcén se encuentra en 0.');
        }

        if($cant > $stock_almacen) {                                                            // si la cantidad supera el stock, error 
            throw new ModelsException('La cantidad solicitada supera el stock del producto en este alamcén.');
        }

        # Declarar $agregado como false
        $agregado = false;

        # Creamos el array de los datos del producto a agregar
        $itemProducto = [
                "idProducto" => (int) $producto['id'],
                "cantidad" => (int) $cant,
                "descuento" => (real) 0
        ];

        # Si la session listaProductosVenta es igual a null o la lsita esta vacia
        if( $session->get('listaProductosVenta') === null || count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ) {

            # Crear primer item de la lista
            $arrayProductosVenta[] = $itemProducto;
            # Crear el array de la session listaProductosVenta con el primer producto
            $session->set("listaProductosVenta", $arrayProductosVenta);

        # Si no entonces la session listaProductosVenta existe
        } else {

            # Obtener el array de la session listaProductosVenta
            $arrayProductosVenta = $session->get("listaProductosVenta");

            # Recorrer el array $arrayProductosVenta
            foreach ($arrayProductosVenta as $key => $value) {

                # Si el id del producto recibido ya existe
                if($value["idProducto"] == $id) {

                    # Cambia el valor de $agregado a true
                    $agregado = true;
                    # Obtener el id actual del producto del arreglo
                    $idProducto = $value["idProducto"];
                    # Obtener el stock del producto
                    $stock_almacen = (new Model\Productos)->stock_producto($idProducto, $almacen['id_almacen']);    

                    # Sumamos la cantidad solicitada a la que ya se habia solicitado
                    $nueva_cant = $arrayProductosVenta[$key]["cantidad"] + $cant;

                    # Si la nueva cantidad es menor o igual al sctock 
                    if($nueva_cant <= $stock_almacen) { 

                        # Actualizamos la cantidad
                        $arrayProductosVenta[$key]["cantidad"] = $nueva_cant;
                        # Ordenamos el array array por clave en orden inverso
                        krsort($arrayProductosVenta);
                        # Crear nuevamente el array de la session listaProductosVenta con la nueva cantidad
                        $session->set("listaProductosVenta", $arrayProductosVenta);

                    # Si la cantidad supera el stock
                    } else {
                        throw new ModelsException('La cantidad solicitada supera el stock del producto en este alamcén.');
                    }

                }

            } # end-foreach

            # Si el producto no se encuentra agregado, lo agregamos
            if($agregado === false){

                # Agregamos el nuevo producto al array a la lsita existente
                $arrayProductosVenta[] = $itemProducto;
                # Ordenamos el array array por clave en orden inverso
                krsort($arrayProductosVenta);
                # Crear nuevamente el array de la session listaProductosVenta con un producto agregado
                $session->set("listaProductosVenta", $arrayProductosVenta);
            }


        } # end-else
            
    }

    public function agregarProductoListaVenta() {
        try {
            global $http;

            $id = intval($http->request->get('key'));

            $this->agregarListaVenta(1, $id);

            return array('status' => 'success', 'title' => '¡Agregado a la lista!', 'message' => 'El producto se agregó a la lista.');
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }

    public function modificarCantidad() {
        try {
            global $http, $session;

            $cant = intval($http->request->get('cantidad'));
            $id = intval($http->request->get('key'));

            if($cant == 0 || $cant == ''){
                $cant = 1;
            }

            $producto = (new Model\Productos)->producto($id);

            if(!$producto) {
                throw new ModelsException('El producto no existe.');
            }

            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
            $stock_almacen = (new Model\Productos)->stock_producto($id, $almacen['id_almacen']);        // obtener el stock del producto

            if($stock_almacen < 1) {
                throw new ModelsException('El stock del producto en este alamcén se encuentra en 0.');
            }

            if($cant > $stock_almacen) {
                throw new ModelsException('La cantidad solicitada supera el stock del producto en este alamcén.');
            }

            if( $session->get('listaProductosVenta') === null || count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosVenta
            $arrayProductosVenta = $session->get("listaProductosVenta");

            # Recorrer el array $arrayProductosVenta
            foreach ($arrayProductosVenta as $key => $value) {

                # Buscar el item al que se le va a actualizar la cantidad
                if($value["idProducto"] == $id) {
                    # Actualizamos la cantidad por la nueva
                    $arrayProductosVenta[$key]["cantidad"] = $cant;
                    # Crear nuevamente el array de la session listaProductosVenta con la nueva cantidad
                    $session->set("listaProductosVenta", $arrayProductosVenta);
                }

            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Se actualizó la cantidad del producto.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }

    public function agregarDescuento() {
        try {
            global $http, $session;
            $tipo = $http->request->get('tipo');
            $id = intval($http->request->get('key'));
            $descuento = (int) $http->request->get('descuento');

            if( $session->get('listaProductosVenta') === null || count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosVenta
            $arrayProductosVenta = $session->get("listaProductosVenta");

            if($tipo == 'general'){
                if($descuento > 50){
                    throw new ModelsException('El descuento no puede ser superior al 50%.');
                }
                # Recorrer el array $arrayProductosVenta
                foreach ($arrayProductosVenta as $key => $value) {
                    # Actualizamos la cantidad por la nueva
                    $arrayProductosVenta[$key]["descuento"] = $descuento;
                    # Crear nuevamente el array de la session listaProductosVenta con la nueva cantidad
                    $session->set("listaProductosVenta", $arrayProductosVenta);
                }
            }elseif($tipo == 'individual' && $id != 0){
                
                $producto = (new Model\Productos)->producto($id);
                if(!$producto) {
                    throw new ModelsException('El producto no existe.');
                }
                if($descuento > 50){
                    if($producto['liquidacion'] == 'NO'){
                        throw new ModelsException('El descuento no puede ser superior al 50%.');   
                    }
                    if($descuento > 95){
                        throw new ModelsException('El descuento no puede ser superior al 95%.');
                    }
                }
                
                # Recorrer el array $arrayProductosVenta
                foreach ($arrayProductosVenta as $key => $value) {
                    # Buscar el item al que se le va a actualizar la cantidad
                    if($value["idProducto"] == $id) {
                        # Actualizamos la cantidad por la nueva
                        $arrayProductosVenta[$key]["descuento"] = $descuento;
                        # Crear nuevamente el array de la session listaProductosVenta con la nueva cantidad
                        $session->set("listaProductosVenta", $arrayProductosVenta);
                    }
                }
            }

            if($tipo == 'general'){
                if($descuento > 0){
                    $message = "Se aplicó un descuento general del $descuento %.";
                }else{
                    $message = "Se eliminó el descuento general.";
                }
            }elseif($tipo == 'individual'){
                if($descuento > 0){
                    $message = "Se aplicó un descuento del $descuento %.";
                }else{
                    $message = "Se eliminó el descuento.";
                }
            }

            return array('status' => 'success', 'title' => '¡Descuento aplicado!', 'message' => $message);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
        
    }

    public function quitarProductoListaVenta() {
        try {
            global $http, $session;
            $id = intval($http->request->get('key'));

            if( $session->get('listaProductosVenta') === null || count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosVenta
            $arrayProductosVenta = $session->get("listaProductosVenta");

            foreach ($arrayProductosVenta as $key => $value) {
                # Buscar el item que se va a eliminar
                if($value["idProducto"] == $id) {
                    # Eliminar producto
                    unset($arrayProductosVenta[$key]);
                    # Crear nuevamente el array de la session listaProductosVenta con la nueva cantidad
                    $session->set("listaProductosVenta", $arrayProductosVenta);
                }
            }

            if( count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ){  # - Si la cantidad de elementos es menor a 0
                $this->vaciarListaVenta();                                                                          # - Ejecutar el método para vaciar la lista de productos 
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El producto se quitó de la lista.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }
    }

    public function vaciarListaVenta() {
        global $session;
        $session->remove('listaProductosVenta');
        //$session->remove('impuestoComision');
    }

    public function confirmarVenta() {
        try {
            global $http, $session;

            $cliente = intval($http->request->get('cliente'));                              # - Obtener cliente -
            $documento = intval($http->request->get('documento'));                          # - Obtener documento -
            $metodoPago = $this->db->scape($http->request->get('metodoPago'));              # - Obtener metodo de pago -

            # PAGO EFECTIVO
            $efectivo = (real) $http->request->get('efectivo');                             # - Obtener monto en efectivo -

            # PAGO TARJETA
            $transaccion = $this->db->scape($http->request->get('transaccion'));            # - Obtener número de transaccion -

            # PAGO MIXTO
            $montoTarjeta = (real) $http->request->get('montoTarjeta');                     # - Obtener monto en tarjeta -
            $montoEfectivo = (real) $http->request->get('montoEfectivo');                   # - Obtener monto en efectivo -
            $transaccionMixto = $this->db->scape($http->request->get('transaccionMixto'));  # - Obtener número de transaccion -

            $fechaVenta = date("Y-m-d");                                                    # - Definir la fecha -
            $horaVenta = date("H:i:s");                                                     # - Definir la fecha -
            
            $total = 0;                                                                     # - Definir la suma de los subtotales de los productos en 0 -
            $sumaCantidad = 0;                                                              # - Definir la suma de las cantidades de los productos en 0 -
            $puntosGanados = 0;                                                             # - Definir la suma de los puntos ganados en 0 -

            if($this->id_user === NULL) {                                                                                   # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }
            if( $session->get('listaProductosVenta') === null || count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ) {         # - Si lista de productos esta vacía -
                throw new ModelsException('La lista está vacia.');
            }
            $cambio_cantidad = $this->validarProductosListaVenta();                                                         # - Ejecutar método para validar el stock de los productos en la lista -
            if($cambio_cantidad){
                throw new ModelsException('La cantidad solicitada en algunos productos ha cambiado, revise la lista nuevamente para confirmar.');
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
            $idCaja = $caja['id_caja'];                                                 # - Obtener id de la caja del día
            $datosCliente = (new Model\Clientes)->cliente($cliente);                    # - Realizar consulta del cliente por su id para obtener datos -
            if(!$datosCliente) {                                                        # - Si el cliente seleccionado no existe
                throw new ModelsException('El cliente seleccionado no existe.');
            }
            if($datosCliente['tipo'] == 2){                                             # - Tipo de cliente 2 es para ventas a crédito
                throw new ModelsException('El cliente seleccionado sólo puede hacer ventas a crédito.');
            }
            if($documento == 1) {                                                       # - Si el documento es 1 (ticket)
                $datosTicket = $this->datosTicket();                                    # - Obtener datos del ticket
                $numero = $datosTicket['nVenta'];                                       # - Obtener numero de venta
                $folio = $datosTicket['serie'].'-'.$datosTicket['numero'];              # - Crear folio de la venta
            } else {
                throw new ModelsException('El documento seleccionado no es válido.');
            }
            
            $arrayProductosVenta = $session->get("listaProductosVenta");                # - Obtener los productos en la lista - 

            foreach ($arrayProductosVenta as $key => $value) {                          # - Recorrer productos en la lista para obtener el total -
                $cantidad = (int) $value['cantidad'];                                   # - cantidad solicitdada -
                $descuento = (int) $value['descuento'];                                 # - descuento aplicado -
                $miProducto = (new Model\Productos)->producto($value['idProducto']);    # - Realizar consulta del producto por su id para obtener datos -
                $precio = (real) $miProducto['precio'];                                 # - Obtener el precio de venta del producto -
                if($descuento != 0) {                                                   # - Si el descuento es diferente de 0 -
                    $precio = $precio - ( ($precio * $descuento) / 100 );               # - Calcular el precio aplicando el descuento -
                    $subtotal = $cantidad * $precio;                                    # - Obtener subtotal con el precio descuento -
                } else {                                                                # - Si el descuento es 0 -
                    $subtotal = $cantidad * $precio;                                    # - Calcular sobtotal con el precio normal -
                }
                $sumaCantidad += $cantidad;                                             # - Sumar la cantidad de cada producto -
                $total += $subtotal;                                                    # - Sumar el subtotal de cada producto -
            } 

            if($cliente != 1 && $datosCliente['tipo'] == 1){                            # - Si el cliente es diferente a "Venta al público en general" y de tipo lealtad (1) -
                $aplicarPuntos = true;                                                  # - Definir Aplicar puntos como true
            }else{
                $aplicarPuntos = false;                                                 # - Definir Aplicar puntos como false
            }

            # - PAGO EN EFECTIVO ------------------------------------------------------------------------------------
            if($metodoPago == 'efectivo'){   
                /*
                if($efectivo < $total) {                                                # - Si el efectivo es menor o igual al total -
                    throw new ModelsException('El pago en efectivo no cubre el total de la venta.');
                }
                */
                // $pago = $efectivo;
                $pago = $total;
                // $cambio = $efectivo - $total;
                $cambio = 0;
                
                $tipo = 'Cobro';                                                        # - Tipo de movimiento a registrar en caja como "Cobro" -
                $descripcion = 'Cobro por concepto de venta: '.$folio.'.';              # - Descripción de movimiento a registrar en caja -
                $this->db->insert('caja_movimientos', array(                            # - REGISTRAR COBRO EN CAJA - 
                    'id_caja' => $idCaja,
                    'tipo' => $tipo,
                    'concepto' => 'Venta',
                    'referencia' => $folio,
                    'descripcion' => $descripcion,
                    'monto' => $total,
                    'usuario' => $this->id_user,
                    'hora' => date('H:i:s')
                ));
            # - PAGO CON TARJETA -----------------------------------------------------------------------------------
            }elseif($metodoPago == 'tarjeta'){                                          
                if($transaccion == '') {                                                    # - Si no se envió código de transacción -
                    $transaccion = '';
                }
                $pago = $total;
                $cambio = 0;
            # - PAGO MIXTO -----------------------------------------------------------------------------------------
            }elseif($metodoPago == 'mixto'){                                            
                if($transaccionMixto == '') {                                               # - Si no se envió código de transacción -
                    $transaccion = '';
                }
                if($montoTarjeta == 0 || $montoEfectivo == 0){                              # - Si el monto en tarjeta o efectivo son = 0 -
                    throw new ModelsException('Para pago mixto, ingresar monto en tarjeta y en efectivo.');
                }
                if($montoTarjeta >= $total ){                                               # - Si el monto en tarjeta es mayor o igual al total -
                    throw new ModelsException('El monto total de la venta debe estar dividido, si desea pagar todo con tarjeta seleccione el método correspondiente.');
                }
                if($montoEfectivo >= $total ){                                              # - Si el monto en efectivo es mayor o igual al total -
                    throw new ModelsException('El monto total de la venta debe estar dividido, si desea pagar todo en efectivo seleccione el método correspondiente.');
                }

                /*
                
                EJEMPLO:
                El total de la venta es de 550.00, se paga con tarjeta 400.00 y en efectivo 200.00:

                ($total)         550.00                                             $total

                ($montoTarjeta)  400.00 + ($montoEfectivo) 200.00   =   600.00      => $montoMixto

                ($montoMixto)    600.00 - ($total)         550.00   =   50.00       => $cambio

                ($montoEfectivo) 200.00 - ($cambio)         50.00   =   150.00      => $montoCaja 

                */
                $montoMixto = $montoTarjeta + $montoEfectivo;                               # - Monto mixto total = (tarjeta + efectivo)
                if($montoMixto < $total) {                                                  # - Si el Monto mixto total es menor al total -
                    throw new ModelsException('El monto en tarjeta y efectivo no cubre el total de la venta.');
                }
                $cambio = $montoMixto - $total;                                             # - Cambio = Monto mixto total - total
                $tipo = 'Cobro';                                                            # - Tipo de movimiento a registrar en caja como "Cobro" -
                $descripcion = 'Cobro (mixto efectivo) por concepto de venta: '.$folio.'.'; # - Descripción de movimiento a registrar en caja -
                $montoCaja = $montoEfectivo - $cambio;
                $this->db->insert('caja_movimientos', array(                                # - REGISTRAR COBRO EN CAJA - 
                    'id_caja' => $idCaja,
                    'tipo' => $tipo,
                    'concepto' => 'Venta',
                    'referencia' => $folio,
                    'descripcion' => $descripcion,
                    'monto' => $montoCaja,
                    'usuario' => $this->id_user,
                    'hora' => date('H:i:s')
                ));

            # - PAGO CON PUNTOS ------------------------------------------------------------------------------------
            }elseif($metodoPago == 'puntos'){                                           
                if($cliente == 1){
                    throw new ModelsException('Seleccione un código de lealtad para validar los puntos.');
                }
                if($datosCliente['puntos'] < $total){                                   # - Si los puntos son menor al total -
                    throw new ModelsException('Los puntos no cubren el total de la venta. Puntos del cliente: '.$datosCliente['puntos']);
                }else{
                    if($total <= 100){
                        throw new ModelsException('El pago con puntos no aplica en ventas menores a $100.00.');
                    }
                }
                $pago = $total;
                $cambio = 0;
                $aplicarPuntos = false;                                                 # - No se sumaran puntos cuando la venta es pagada con puntos
            # - PAGO INVALIDO ------------------------------------------------------------------------------------
            }else{
                throw new ModelsException('Seleccionar un método de pago valido');
            } 

            if($metodoPago != 'mixto'){
                $id_venta = $this->db->insert('salida', array(                          # - REGISTRAR SALIDA (efectivo o tarjeta o puntos) -
                    'id_almacen' => $almacen['id_almacen'],
                    'comprobante' => $documento,
                    'numero' => $numero,
                    'folio' => $folio,
                    'id_cliente' => $cliente,
                    'metodo_pago' => $metodoPago,
                    'transaccion' => $transaccion,
                    'total' => $total,
                    'pago' => $pago,
                    'cambio' => $cambio,
                    'fechaVenta' => $fechaVenta,
                    'horaVenta' => $horaVenta,
                    'usuarioVenta' => $this->id_user,
                    'impresion' => 0,
                    'estado' => 1                                                       # - (1) Venta cerrada -
                ));
            }else{
                $id_venta = $this->db->insert('salida', array(                          # - REGISTRAR SALIDA (mixto) -
                    'id_almacen' => $almacen['id_almacen'],
                    'comprobante' => $documento,
                    'numero' => $numero,
                    'folio' => $folio,
                    'id_cliente' => $cliente,
                    'metodo_pago' => $metodoPago,
                    'transaccion' => $transaccion,
                    'total' => $total,
                    'pago' => $montoMixto,
                    'montoTarjeta' => $montoTarjeta,
                    'montoEfectivo' => $montoEfectivo,
                    'cambio' => $cambio,
                    'fechaVenta' => $fechaVenta,
                    'horaVenta' => $horaVenta,
                    'usuarioVenta' => $this->id_user,
                    'impresion' => 0,
                    'estado' => 1                                                       # - (1) Venta cerrada -
                ));
            }

            foreach ($arrayProductosVenta as $key => $value) {                          # - Recorrer productos en la lista para el detalle de la salida, el cardex y stock del producto - 

                $id_producto = $value['idProducto'];

                $cantidad = (int) $value['cantidad'];                                   # - cantidad solicitdada -
                $descuento = (int) $value['descuento'];                                 # - descuento aplicado -
                $miProducto = (new Model\Productos)->producto($id_producto);            # - Realizar consulta del producto por su id para obtener datos -
                $precio = (real) $miProducto['precio'];                                 # - Obtener el precio de venta del producto - 
                $costo = (real) $miProducto['precio_compra'];                           # - Obtener el precio de compra del producto - 
                $nuevoStock = $miProducto['stock'] - $cantidad;                         # - Restar al stock del producto la cantidad solicitada -
                $nuevasVentas = $miProducto['ventasMostrador'] + $cantidad;             # - Sumar a la ventas de mostrador del producto la cantidad solicitada -
                $nuevasSalidas = $miProducto['total_salidas'] + $cantidad;              # - Sumar al total de salidas del producto la cantidad solicitada -

                if($descuento != 0) {                                                   # - Si el descuento es diferente de 0 -
                    $precioDescuento = $precio - ( ($precio * $descuento) / 100 );      # - Calcular el precio con descuento -
                    $subtotal = $cantidad * $precioDescuento;                           # - Calcular subtotal con el precio descuento -
                    $aplicarPuntos = false;                                             # - Si el producto tiene descuento no se aplicaran puntos
                } else {                                                                # - Si el descuento es 0 -
                    $subtotal = $cantidad*$precio;                                      # - Calcular sobtotal con el precio normal -
                }
                if($aplicarPuntos){                                                     # - Si aplicar puntos es true
                    $puntos = ( $subtotal * 5 ) / 100;                                  # - Calcular los puntos ganados del producto
                    $puntosGanados += $puntos;                                          # - Sumar los puntos ganados de cada producto
                }else{
                    $puntos = 0;
                }

                $this->db->update('productos',array(                                    # - ACTUALIZAR VENTAS, STOCK Y SALIDAS DEL PRODUCTO -
                    'stock' => $nuevoStock, 
                    'ventasMostrador' => $nuevasVentas,
                    'total_salidas' => $nuevasSalidas
                ),"id='".$id_producto."'",1);

                $this->db->insert('salida_detalle', array(                              # - REGISTRAR DETALLE DE SALIDA - 
                    'id_salida' => $id_venta,
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,
                    'vendido' => $cantidad,
                    'puntos' => $puntos,
                    'costo' => $costo,
                    'precio' => $precio,
                    'descuento' => $descuento,
                    'estado' => 1
                ));

                $this->db->insert('productos_cardex', [                                 # - REGISTRAR CARDEX COMO "venta" -
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,
                    'id_almacen' => $almacen['id_almacen'],
                    'id_clienteProveedor' => $cliente,
                    'costo' => $costo,
                    'precio' => $precio,
                    'descuento' => $descuento,                                          # descuento al que sale
                    'operacion' => 'venta',
                    'movimiento' => 'vs',                                               # - (vs) = venta salida
                    'referencia' => $folio,
                    'fecha' => $fechaVenta.' '.$horaVenta 
                ]);

            }
            
            $compras = $datosCliente['compras'];
            $nuevasCompras = $compras + $sumaCantidad;
            
            if($metodoPago == 'efectivo' || $metodoPago == 'tarjeta' || $metodoPago == 'mixto'){
                
                if($cliente != 1){
                    $puntosCliente = $datosCliente['puntos'];
                    $nuevosPuntos = $puntosCliente + $puntosGanados;
                }else{
                    $nuevosPuntos = 0;
                }
                $this->db->update('clientes',array(                                         # - ACTUALIZAR COMPRAS Y SUMAR PUNTOS AL CLIENTE -
                    'compras' => $nuevasCompras,
                    'puntos' => $nuevosPuntos, 
                    'fechaUltimaCompra' => $fechaVenta.' '.$horaVenta),"id_cliente='$cliente'",1);
            }else{
                
                if($cliente != 1){
                    $puntosCliente = $datosCliente['puntos'];
                    $nuevosPuntos = $puntosCliente - $total;
                }else{
                    $nuevosPuntos = 0;
                }
                $this->db->update('clientes',array(                                         # - ACTUALIZAR COMPRAS Y RESTAR PUNTOS AL CLIENTE -
                    'compras' => $nuevasCompras,
                    'puntos' => $nuevosPuntos, 
                    'fechaUltimaCompra' => $fechaVenta.' '.$horaVenta),"id_cliente='$cliente'");
            }  

            $this->vaciarListaVenta(); 

            return array('status' => 'success', 'title' => '¡Venta registrada!', 'message' => 'La venta ha sido registrada correctamente con el folio '.$folio.'.', 'idsalida' => $id_venta);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage()); 
        }
    }

    public function consignacionSalida() {
        try {
            global $http, $session;

            $proveedor = intval($http->request->get('proveedor'));
            $referencia = $this->db->scape($http->request->get('referencia'));

            $fecha = date("Y-m-d");
            $hora = date("H:i:s");
            $folio = strtoupper(uniqid());

            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);

            # VALIDAR SI HAY USUARIO ACTIVO
            if($this->id_user === NULL){
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }

            # VALIDAR QUE LA LISTA DE PRODUCTOS EXISTA O ESTE LLENA
            if( $session->get('listaProductosVenta') === null || count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # VALIDAR QUE LOS PRODUCTOS AGREGADOS EN LA LISTA AÚN ESTEN EN STOCK
            $cambio_cantidad = $this->validarProductosListaVenta();                                                         # - Ejecutar método para validar el stock de los productos en la lista -
            if($cambio_cantidad){
                throw new ModelsException('La cantidad solicitada en algunos productos ha cambiado, revise la lista nuevamente para confirmar.');
            }

            # VALIDAR PROVEEDOR
            if($proveedor == 0){
                throw new ModelsException('El proveedor seleccionado no es válido.');
            }else{
                $datosProveedor = (new Model\Proveedores)->proveedor($proveedor);
                if(!$datosProveedor){
                    throw new ModelsException('El proveedor no existe.');
                }
                if( ($proveedor == 1 || $proveedor == 2)){
                    throw new ModelsException('Para la salida de consignación es necesario seleccionar un proveedor.');
                }else{
                    if($referencia == '' || $referencia === null){
                	    throw new ModelsException('Especificar como referencia el folio de consignación de entrada.');
                    }
                    /*$entradaConsignacion = $this->db->select('id_consignacion,id_almacen', 'consignacion', null, "tipo = 'entrada' AND folio = '$referencia' AND id_proveedor = '$proveedor'",1);
                    if(!$entradaConsignacion){
                       throw new ModelsException('El folio:'.$referencia.' de consignación de entrada con el proveedor '.$datosProveedor['proveedor'].', no existe.');
                    } 
                    if($entradaConsignacion[0]['id_almacen'] != $almacen['id_almacen']){
                       throw new ModelsException('La consignación de entrada (Folio:'.$referencia.') no se realizó en este almacén.');
                    }*/
                }
            }

            $sumaCantidad = 0;

            $id_salida = $this->db->insert('consignacion', array(
                'id_almacen' => $almacen['id_almacen'],
                'folio' => $folio,
                'referencia' => $referencia,
                'id_proveedor' => $proveedor,
                'tipo' => 'salida',
                'usuario_consignacion' => $this->id_user,
                'fecha' => $fecha,
                'hora' => $hora
            ));

            # OBTENER DETALLES DE LA LISTA DE PRODUCTOS
            $arrayProductosVenta = $session->get("listaProductosVenta");

            foreach ($arrayProductosVenta as $key => $value) {

                $id = intval($value['idProducto']);
                $cantidad = (int) $value['cantidad'];

                $producto = (new Model\Productos)->producto($id);

                $costo = (real) $producto['precio_compra'];
                $precio = (real) $producto['precio'];

                $nuevoStock = $producto['stock'] - $cantidad;
                $nuevasSalidas = $producto['total_salidas'] + $cantidad;
                $sumaCantidad += $cantidad;

                # ACTUALIZAR STOCK DE CADA PRODUCTO
                $this->db->update('productos',array(
                    'stock' => $nuevoStock,
                    'total_salidas' => $nuevasSalidas 
                ), "id='$id'",1);

                # REGISTRAR DETALLE DE CONSIGNACION
                $this->db->insert('consignacion_detalle', array(
                    'id_consignacion' => $id_salida,
                    'id_producto' => $id,
                    'cantidad' => $cantidad,
                    'costo' => $costo,
                    'precio' => $precio
                ));

                # REGISTRAR CARDEX DE PRODUCTOS (entrada como un alias para consignación de salida)
                $this->db->insert('productos_cardex', array(
                    'id_producto' => $id,
                    'cantidad' => $cantidad,
                    'id_almacen' => $almacen['id_almacen'],
                    'id_clienteProveedor' => $proveedor,
                    'costo' => $costo,
                    'precio' => $precio,
                    'operacion' => 'salida',
                    'movimiento' => 'cs',                                               # - (cs) = consignacion salida
                    'referencia' => $folio,
                    'fecha' => $fecha.' '.$hora
                ));

            }

            # RESTAR COMPRAS A PROVEEDOR
            $compras = $datosProveedor['compras'];
            $nuevasCompras = $compras - $sumaCantidad;

            $this->db->update('proveedores',array(
                'compras' => $nuevasCompras
            ),"id_proveedor='$proveedor'");
            
            $this->vaciarListaVenta(); 

            return array('status' => 'success', 'title' => '¡Salida aplicada!', 'message' => 'La devolución de consignación se registró correctamente.', 'idsalida' => $id_salida);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage()); 
        }
    }

    public function ajusteSalida() {
        try {
            global $http, $session;

            $accion = intval($http->request->get('accion'));

            $fecha = date("Y-m-d");
            $hora = date("H:i:s");
            $folio = strtoupper(uniqid());

            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);

            # VALIDAR SI HAY USUARIO ACTIVO
            if($this->id_user === NULL){
                throw new ModelsException('Es posible que su sesión haya caducado, recargue la página para verificarlo.');
            }

            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador['control'] == 0){ # - Si el usuario no tiene control (0)
                throw new ModelsException('No tienes los permisos necesarios para realizar ajuste de inventario.');
            }

            # VALIDAR QUE LA LISTA DE PRODUCTOS EXISTA O ESTE LLENA
            if( $session->get('listaProductosVenta') === null || count($session->get('listaProductosVenta')) == 0 || empty($session->get('listaProductosVenta')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # VALIDAR QUE LOS PRODUCTOS AGREGADOS EN LA LISTA AÚN ESTEN EN STOCK
            $cambio_cantidad = $this->validarProductosListaVenta();                                                         # - Ejecutar método para validar el stock de los productos en la lista -
            if($cambio_cantidad){
                throw new ModelsException('La cantidad solicitada en algunos productos ha cambiado, revise la lista nuevamente para confirmar.');
            }

            # VALIDAR ACCION
            if($accion == 0){
                throw new ModelsException('La acción seleccionada no es válida.');
            }else{
                $datosAccion = (new Model\Proveedores)->proveedor($accion);
                if(!$datosAccion){
                    throw new ModelsException('La acción no existe.');
                }
                if( ($accion == 1 || $accion == 2)){ 
                }else{
                    throw new ModelsException('Para procesar el ajuste, seleccionar la acción correspondiente.');
                }
            }

            # OBTENER DETALLES DE LA LISTA DE PRODUCTOS
            $arrayProductosVenta = $session->get("listaProductosVenta");
            $total = 0;

            # Para obtener el total
            foreach ($arrayProductosVenta as $key => $value) {                          # - Recorrer productos en la lista para obtener el total -
                $cantidad = (int) $value['cantidad'];                                   # - cantidad solicitdada -
                $descuento = (int) $value['descuento'];                                 # - descuento aplicado -
                $miProducto = (new Model\Productos)->producto($value['idProducto']);    # - Realizar consulta del producto por su id para obtener datos -
                $precio = (real) $miProducto['precio'];                                 # - Obtener el precio de venta del producto -
                if($descuento != 0) {                                                   # - Si el descuento es diferente de 0 -
                    $precio = $precio - ( ($precio * $descuento) / 100 );               # - Calcular el precio aplicando el descuento -
                    $subtotal = $cantidad * $precio;                                    # - Obtener subtotal con el precio descuento -
                } else {                                                                # - Si el descuento es 0 -
                    $subtotal = $cantidad * $precio;                                    # - Calcular sobtotal con el precio normal -
                }
                $total += $subtotal;                                                    # - Sumar el subtotal de cada producto -
            }

            $id_ajuste = $this->db->insert('salida', array(                             # - REGISTRAR SALIDA -
                'id_almacen' => $almacen['id_almacen'],
                'folio' => $folio,
                'id_accion' => $accion,
                'total' => $total,
                'fechaVenta' => $fecha,
                'horaVenta' => $hora,
                'usuarioVenta' => $this->id_user,
                'estado' => 1                                                           # - (1) Ajuste realizado -
            ));

            $sumaCantidad = 0;
            foreach ($arrayProductosVenta as $key => $value) {

                $id = intval($value['idProducto']);
                $cantidad = (int) $value['cantidad'];

                $producto = (new Model\Productos)->producto($id);
                $costo = (real) $producto['precio_compra'];
                $precio = (real) $producto['precio'];

                $nuevoStock = $producto['stock'] - $cantidad;
                $nuevasSalidas = $producto['total_salidas'] + $cantidad;
                $sumaCantidad += $cantidad;

                # ACTUALIZAR STOCK DE CADA PRODUCTO
                $this->db->update('productos',array(
                    'stock' => $nuevoStock,
                    'total_salidas' => $nuevasSalidas
                ),"id='$id'",1);

                $this->db->insert('salida_detalle', array(                              # - REGISTRAR DETALLE DE SALIDA - 
                    'id_salida' => $id_ajuste,
                    'id_producto' => $id,
                    'cantidad' => $cantidad,
                    'costo' => $costo,
                    'precio' => $precio,
                    'estado' => 1
                ));

                $this->db->insert('productos_cardex', [                                 # - REGISTRAR CARDEX COMO "venta" -
                    'id_producto' => $id,
                    'cantidad' => $cantidad,
                    'id_almacen' => $almacen['id_almacen'],
                    'id_clienteProveedor' => $accion,
                    'costo' => $costo,
                    'precio' => $precio,
                    'operacion' => 'salida',
                    'movimiento' => 'as',                                               # - (as) = ajuste salida
                    'referencia' => $folio,
                    'fecha' => $fecha.' '.$hora 
                ]);

            }
            
            # RESTAR COMPRAS A ACCION
            $compras = $datosAccion['compras'];
            $nuevasCompras = $compras - $sumaCantidad;

            $this->db->update('proveedores',array(
                'compras' => $nuevasCompras
            ),"id_proveedor='$accion'");

            $this->vaciarListaVenta(); 

            return array('status' => 'success', 'title' => '¡Ajuste aplicado!', 'message' => 'El ajuste de inventario se proceso correctamente, con el folio '.$folio.'.', 'idsalida' => $id_ajuste);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage()); 
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