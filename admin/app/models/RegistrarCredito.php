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
 * Modelo RegistrarCredito
 */
class RegistrarCredito extends Models implements IModels {
    use DBModel;

    public function creditoPor($where) {
        $credito = $this->db->select("*", 'credito', null, $where);
        if ( $credito ) {
            return $credito[0];
        } else {
            return false;
        }
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
        if ( $comprobante ) {
            return $comprobante[0];
        } else {
            return false;
        }
    }

    /**
     * Obtiene los datos de la nota
     *
     * @return false|array[0]
    */ 
    public function datosNota() {
        $nota = $this->comprobante(2);
        $credito = $this->db->select('MAX(id_credito) AS ultimo', 'credito');
        if($credito === NULL){
            $ultimo = 1;
        }else{
            $ultimo = intval($credito[0]['ultimo'])+1;
        }
        $serie = $nota['prefijo'].'-'.substr(date('Y'), -2);
        $serie .= '-'.date('m');
        $numero = str_pad($ultimo, 6, "0", STR_PAD_LEFT);
        return array("serie" => $serie, "numero" => $numero);
    }

    /**
     * Valida si los productos en la lista aun se encuentran en stock
     *
     * @return void
    */ 
    public function validarProductosListaCredito() {
        global $session;
        $cambioStock = false;

        if ( $session->get('listaProductosCredito') !== null && count($session->get('listaProductosCredito')) > 0 && !empty($session->get('listaProductosCredito')) ) { # - Si lista de productos no esta vacía -
            
            $arrayProductosCredito = $session->get("listaProductosCredito");                                        # - Obtener los productos en la lista - 
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);                                     # - Realizar consulta para obtener el almacen del usuario activo -
            
            foreach ($arrayProductosCredito as $key => $value) {                                                    # - Recorrer productos en la lista -
            
                $idProducto = $value['idProducto'];                                                                 # - id del producto -
                $cant = (int) $value['cantidad'];                                                                   # - cantidad solicitdada -
                $stock = (new Model\Productos)->stock_producto($idProducto, $almacen['id_almacen']);                # - Obtener el stock del producto
                
                if ($stock < $cant && $stock != 0) {                                                                # - Si el stock es menor a la cantidad solicitada y diferente de 0 -                         
                    $arrayProductosCredito[$key]["cantidad"] = $stock;                                              # - Asignr el stock a la cantidad solicitada -
                    $session->set("listaProductosCredito", $arrayProductosCredito);                                 # - Actualizar la lista de productos con la nueva cantidad del producto -
                    $cambioStock = true;
                }
                
                if ($stock == 0) {                                                                                  # - Si el stock es igual 1 0 -
                    unset($arrayProductosCredito[$key]);                                                            # - Eliminar producto de la lista -   
                    $session->set("listaProductosCredito", $arrayProductosCredito);                                 # - Actualizar la lista de productos para quitar el producto eliminado -
                    $cambioStock = true;
                }
            }
            
            if( count($session->get('listaProductosCredito')) == 0 || empty($session->get('listaProductosCredito')) ){                                                # - Si la cantidad de elementos es menor a 0
                $this->vaciarListaCredito();                                                                        # - Ejecutar el método para vaciar la lista de productos 
            }
            
        }

        return $cambioStock;
    }

    /**
     * Carga la lista de los productos
     *
     * @return array
    */
    public function cargarListaCredito() {
        global $session;
        $this->validarProductosListaCredito();                                  # - Validar que los productos en la lista esten en stock -
        
        if ( $session->get('listaProductosCredito') !== null && count($session->get('listaProductosCredito')) > 0 && !empty($session->get('listaProductosCredito')) ) {   # - Si lista de productos no esta vacía -
            
            $arrayProductosCredito = $session->get("listaProductosCredito");        # - Obtener los productos en la lista - 
            $tbody = '';
            $sumaSubtotal = 0;
            $sumaCantidad = 0;
            
            $totalItems = count($session->get('listaProductosCredito'));

            foreach ($arrayProductosCredito as $key => $value) {                # - Recorrer productos en la lista -
                $idProducto = $value['idProducto'];                             # - id del producto -
                $cantidad = (int) $value['cantidad'];                           # - cantidad solicitdada -
                $descuento = (real) $value['descuento'];                         # - descuento aplicado
                $miProducto = (new Model\Productos)->producto($idProducto);     # - Realizar consulta del producto por su id para obtener datos -
                $codigo = $miProducto['codigo'];                                # - Obtener el código del producto -
                $producto = $miProducto['producto'];                            # - Obtener nombre del producto -
                $precio = (real) $miProducto['precio'];                         # - Obtener el precio del producto - 

                if($descuento != 0){                                            # - Si el descuento es diferente de 0 -
                    $precioDescuento = $precio - ( ($precio * $descuento) / 100 );# - Calcular el precio con descuento -
                    $subtotal = $cantidad * $precioDescuento;                     # - Calcular subtotal con el precio descuento -
                }else{                                                          # - Si el descuento es 0 -
                    $precioDescuento = 0;                                       # - Definir precio descuento como 0
                    $subtotal = $cantidad * $precio;                            # - Calcular sobtotal con el precio normal -
                }
                $sumaCantidad += $cantidad;                                     # - Sumar la cantidad de cada producto -
                $sumaSubtotal += $subtotal;                                     # - Sumar el subtotal de cada producto -
                
                if($precioDescuento != 0){                                      # - Si el precio descuento es diferente de 0 -
                    $precioFormato = '<del class="text-gray">'.number_format($precio, 2).'</del> <b>'.number_format($precioDescuento,2).'</b>'; # - Mostrar precio normal y precio con descuento -
                }else{
                    $precioFormato = '<b>'.number_format($precio, 2).'</b>'; # - Mostrar solo precio normal -
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

                                    <input class="form-control input-sm text-center inputCantidad" id="inputCantidad'.$idProducto.'" key="'.$idProducto.'" type="text" value="'.$cantidad.'" placeholder="0">

                                    <span class="input-group-addon btn btnAgregarCantidad" key="'.$idProducto.'" style="padding-left:2px; padding-right:2px;">
                                        <i class="fas fa-plus fa-xs"></i>
                                    </span>

                                </div>
                            </td>
                            
                            <td style="vertical-align:middle;">
                                '.$codigo.' | '.$idProducto.' <br> <span class="font-weight-bold">'.$producto.'</span> '.$leyenda.' <br>
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

                            <td style="vertical-align:middle;" class="text-right">'.number_format(($subtotal),2).'</td>

                            <td style="vertical-align:middle;" class="text-right">
                                <button type="button" class="btn btn-default btn-sm btn-flat eliminarProductoListaCredito" style="margin-right:5px;" key="'.$idProducto.'">
                                    <i class="fas fa-times text-red"></i>
                                </button>
                            </td>

                        </tr>';
                        $totalItems--;
            }

            $total = $sumaSubtotal;                                         # - Asignar al total general la suma de los subtotales - 
            $totalFormat = number_format($total,2);                    # - Agregar formato al total ( Ej. $ 100.00 ) -

            return array("status" => "llena",                               # - Retornar el estatus de la lista como "llena" -
                        "tbody" => $tbody,                                  # - Retornar la tabla con formato html para mostrar los productos en lista -
                        "sumaCantidad"=> $sumaCantidad,                     # - Retornar la suma de las cantidades de cada producto -
                        "totalG" => $totalFormat, # Total con formato       # - Retornar el total general con formato -
                        "total" => $total); # Total sin formato             # - Retornar el total general sin formato -
                        

        }else{                                                              # - Si lista de productos esta vacía - 
            $this->vaciarListaCredito();                                      # - Vaciar la lista para asegurarnos que no exista elementos vacíos en la lista -
            $tbody = '
                    <tr>
                        <td colspan="6" class="text-center">
                            No hay productos en la lista.
                        </td>
                    </tr>';
            return array("status" => "vacia",                               # - Retornar el estatus de la lista como "vacia" -
                        "tbody" => $tbody,                                  # - Retornar la tabla con formato html para mostrar mensaje 'No hay productos en la lista.' -
                        "sumaCantidad"=> 0,                                 # - Retornar la suma de las cantidades de cada producto como 0 - 
                        "totalG" => "0.00",                               # - Retornar el total general con formato como ( $ 0.00 ) -
                        "total" => 0);                                      # - Retornar el total general sin formato como ( 0 ) -
        }

    }

    /**
     * Agregar producto a la lista de productos en credito
     *
     * @param string $cant: cantidad
     * @param string $id: id de producto
     * 
    */ 
    public function agregarListaCredito($cant, $id, $id_cliente){
        
        global $session;
        $producto = (new Model\Productos)->producto($id);
        if (!$producto) {
            throw new ModelsException('El producto no existe.');
        }

        if($cant == 0) {
            $cant = 1;
        }

        $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
        $stock_almacen = (new Model\Productos)->stock_producto($id, $almacen['id_almacen']);    // obtener el stock del producto
        if ($stock_almacen < 1) {
            throw new ModelsException('El stock del producto se encuentra en 0.');
        }

        if ($cant > $stock_almacen) {
            throw new ModelsException('La cantidad solicitada supera el stock del producto.');
        }

        # Declarar $agregado como false
        $agregado = false;

        # Creamos el array de los datos del producto a agregar
        $itemProducto = [
            "idProducto" => (int) $producto['id'],
            "cantidad" => (int) $cant,
            "descuento" => (real) 0
        ];

        # Si la session listaProductosCredito es igual a null
        if ( $session->get('listaProductosCredito') === null || count($session->get('listaProductosCredito')) == 0 || empty($session->get('listaProductosCredito')) ) {

            # Crear primer item de la lista
            $arrayProductosCredito[] = $itemProducto;
            # Crear el array de la session listaProductosCredito con el primer producto
            $session->set("listaProductosCredito", $arrayProductosCredito);

        # Si no entonces la session listaProductosCredito existe
        } else {

            # Obtener el array de la session listaProductosCredito
            $arrayProductosCredito = $session->get("listaProductosCredito");

            # Recorrer el array $arrayProductosCredito
            foreach ($arrayProductosCredito as $key => $value) {

                # Si el id del producto recibido ya existe
                if ($value["idProducto"] == $id) {

                    # Cambia el valor de $agregado a true
                    $agregado = true;
                    # Obtener el stock del producto
                    $idProducto = $value["idProducto"];
                    $stock_almacen = (new Model\Productos)->stock_producto($idProducto, $almacen['id_almacen']);    // obtener el stock del producto
                    # Nueva cantidad
                    $nueva_cant = $arrayProductosCredito[$key]["cantidad"] + $cant;

                    # Si la nueva cantidad es menor al sctock 
                    if ($nueva_cant <= $stock_almacen) { 

                        # Actualizamos la cantidad por la nueva
                        $arrayProductosCredito[$key]["cantidad"] = $nueva_cant;
                        # Ordenamos el array array por clave en orden inverso
                        krsort($arrayProductosCredito);
                        # Crear nuevamente el array de la session listaProductosCredito con la nueva cantidad
                        $session->set("listaProductosCredito", $arrayProductosCredito);

                    # Si no entonces la nueva cantidad supera el stock
                    } else {
                        throw new ModelsException('La cantidad solicitada supera el stock del producto.');

                    }

                }

            } # end-foreach

            # Si el producto no se encuentra agregado, lo agregamos
            if($agregado === false){

                # Agregamos el nuevo producto al array a la lsita existente
                $arrayProductosCredito[] = $itemProducto;
                # Ordenamos el array array por clave en orden inverso
                krsort($arrayProductosCredito);
                # Crear nuevamente el array de la session listaProductosCredito con otro producto agregado
                $session->set("listaProductosCredito", $arrayProductosCredito);
            }

        } # end-else
            
    }

    public function agregarProductoListaCredito() {
        try {
            global $http;

            $id = intval($http->request->get('key'));
            $id_cliente = intval($http->request->get('cliente'));

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
            $this->agregarListaCredito(1, $id, $id_cliente);
            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El producto se agregó a la lista.');
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    public function modificarCantidadCredito() {
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

            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
            $stock_almacen = (new Model\Productos)->stock_producto($id, $almacen['id_almacen']);

            if ($stock_almacen < 1) {
                throw new ModelsException('El stock del producto se encuentra en 0.');
            }

            if ($cant > $stock_almacen) {
                throw new ModelsException('La cantidad solicitada supera el stock del producto.');
            }

            if( $session->get('listaProductosCredito') === null || count($session->get('listaProductosCredito')) == 0 || empty($session->get('listaProductosCredito')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosCredito
            $arrayProductosCredito = $session->get("listaProductosCredito");

            # Recorrer el array $arrayProductosCredito
            foreach ($arrayProductosCredito as $key => $value) {

                # Buscar el item al que se le va a actualizar la cantidad
                if ($value["idProducto"] == $id) {
                    # Actualizamos la cantidad por la nueva
                    $arrayProductosCredito[$key]["cantidad"] = $cant;
                    # Crear nuevamente el array de la session listaProductosCredito con la nueva cantidad
                    $session->set("listaProductosCredito", $arrayProductosCredito);
                }

            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Se actualizó la cantidad del producto.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }
    
    public function agregarDescuentoCredito() {
        try {
            global $http, $session;
            $tipo = $http->request->get('tipo');
            $id = intval($http->request->get('key'));
            $descuento = (int) $http->request->get('descuento');

            if( $session->get('listaProductosCredito') === null || count($session->get('listaProductosCredito')) == 0 || empty($session->get('listaProductosCredito')) ) {
                throw new ModelsException('La lista está vacia.');
            }
            
            # Obtener el array de la session listaProductosCredito
            $arrayProductosCredito = $session->get("listaProductosCredito");
            
            if($tipo == 'general'){
                if($descuento > 50){
                    throw new ModelsException('El descuento no puede ser superior al 50%.');
                }
                # Recorrer el array $arrayProductosCredito
                foreach ($arrayProductosCredito as $key => $value) {
                    # Actualizamos la cantidad por la nueva
                    $arrayProductosCredito[$key]["descuento"] = $descuento;
                    # Crear nuevamente el array de la session listaProductosCredito con la nueva cantidad
                    $session->set("listaProductosCredito", $arrayProductosCredito);
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
                
                # Recorrer el array $arrayProductosCredito
                foreach ($arrayProductosCredito as $key => $value) {
                    # Buscar el item al que se le va a actualizar la cantidad
                    if($value["idProducto"] == $id) {
                        # Actualizamos la cantidad por la nueva
                        $arrayProductosCredito[$key]["descuento"] = $descuento;
                        # Crear nuevamente el array de la session listaProductosCredito con la nueva cantidad
                        $session->set("listaProductosCredito", $arrayProductosCredito);
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

    public function quitarProductoListaCredito() {
        try {
            global $http, $session;
            $id = intval($http->request->get('key'));

            if( $session->get('listaProductosCredito') === null || count($session->get('listaProductosCredito')) == 0 || empty($session->get('listaProductosCredito')) ) {
                throw new ModelsException('La lista está vacia.');
            }

            # Obtener el array de la session listaProductosCredito
            $arrayProductosCredito = $session->get("listaProductosCredito");

            foreach ($arrayProductosCredito as $key => $value) {
                # Buscar el item al que se le va a actualizar la cantidad
                if ($value["idProducto"] == $id) {
                    # Eliminar producto
                    unset($arrayProductosCredito[$key]);
                    # Crear nuevamente el array de la session listaProductosCredito con la nueva cantidad
                    $session->set("listaProductosCredito", $arrayProductosCredito);
                }
            }

            if( count($session->get('listaProductosCredito')) == 0 || empty($session->get('listaProductosCredito')) ){                                              # - Si la cantidad de elementos es menor a 0
                $this->vaciarListaCredito();                                                                      # - Ejecutar el método para vaciar la lista de productos 
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El producto se quitó de la lista.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    public function vaciarListaCredito() {
        global $session;
        $session->remove('listaProductosCredito');
    }

    public function confirmarCredito() {
        try {
            global $http, $session;
            $id_cliente = intval($http->request->get('cliente'));                           # - Obtener cliente -
            $fecha = date("Y-m-d");                                                         # - Definir la fecha -
            $hora = date("H:i:s");                                                          # - Definir la fecha -

            if ($this->id_user === NULL) {                                                  # - Si el usuario no esta logeado o es igual a NULL -
                throw new ModelsException('Es posible que su sesión se haya cerrado, recargue la página para verificarlo.');
            }

            if($id_cliente == 0){
                $this->vaciarListaCredito();
                throw new ModelsException('Seleccione un cliente de la lista.');
            }else{
                $cliente = (new Model\Clientes)->cliente($id_cliente);
                if(!$cliente) {                                                       # - Si el cliente seleccionado no existe
                    $this->vaciarListaCredito();
                    throw new ModelsException('El cliente seleccionado no existe.');
                }
                if($cliente['estado'] == 0 || $cliente['tipo'] == 1){
                    $this->vaciarListaCredito();
                    throw new ModelsException('Seleccione un cliente de la lista.');
                }
            }

            if ( $session->get('listaProductosCredito') === null || count($session->get('listaProductosCredito')) == 0 || empty($session->get('listaProductosCredito')) ) {    # - Si lista de productos esta vacía -
                throw new ModelsException('La lista está vacia.');
            }

            $cambioStock = $this->validarProductosListaCredito();                       # - Ejecutar método para validar el stock de los productos en la lista -
            if($cambioStock){
                throw new ModelsException('El stock de algunos productos ha cambiado, revisa la lista para ver los cambios realizados antes de procesar el crédito.');
            }

            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);         # - Realizar consulta para obtener el almacen del usuario activo -
            
            $datosNota = $this->datosNota();                                            # - Obtener datos de la nota
            $folio = $datosNota['serie'].'-'.$datosNota['numero'];                      # - Crear folio de la nota
            
            $arrayProductosCredito = $session->get("listaProductosCredito");            # - Obtener los productos en la lista -  
            
            // Revisar si el cliente tiene un credito activo
            $where = "id_cliente='$id_cliente' AND estado=1";
            $credito_abierto = $this->creditoPor($where);

            if($credito_abierto){
                $id_credito = $credito_abierto['id_credito'];
                $msj = 'El crédito con folio '.$credito_abierto['folio'].' ha sido actualizado.';
            }else{
                $id_credito = $this->db->insert('credito', array(                       # - REGISTRAR CREDITO -
                    'id_almacen' => $almacen['id_almacen'],
                    'folio' => $folio,
                    'id_cliente' => $id_cliente,
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'usuario' => $this->id_user,
                    'estado' => 2                                                       # - (1) Crédito abierto -
                ));
                $msj = 'El crédito ha sido registrado correctamente con el folio '.$folio.'.';
            }

            $sumaCantidad = 0;                                                          # - Definir la suma de las cantidades de los productos en 0 -

            foreach ($arrayProductosCredito as $key => $value) {                        # - Recorrer productos en la lista para el detalle del crédito, el cardex y stock del producto - 
            
                $id_producto = $value['idProducto'];

                $cantidad = (int) $value['cantidad'];                                   # - cantidad solicitdada -
                $descuento = (real) $value['descuento'];                                # - descuento aplicado -
                $sumaCantidad += $cantidad; 

                $miProducto = (new Model\Productos)->producto($id_producto);            # - Realizar consulta del producto por su id para obtener datos -
                $precio = (real) $miProducto['precio'];                                 # - Obtener el precio de venta del producto - 
                $costo = (real) $miProducto['precio_compra'];                           # - Obtener el precio de compra del producto - 

                $nuevoStock = $miProducto['stock'] - $cantidad;                         # - Restar al stock del producto la cantidad solicitada -
                $nuevasSalidas = $miProducto['total_salidas'] + $cantidad;
                $nuevasVentas = $miProducto['ventasMostrador'] + $cantidad;             # - Sumar a las ventas de mostrador del producto la cantidad solicitada -

                if($credito_abierto){

                    $productoCredito = $this->db->select("*", "credito_detalle", null, "id_credito = '$id_credito' AND id_producto = '$id_producto'",1);
                    
                    if($productoCredito){                                               # - Si el producto ya se encuentra registrado en el crédito, solo se actualizan cantidades
                        $this->db->query("UPDATE credito_detalle 
                            SET cantidad = (cantidad + $cantidad), 
                            vendido = (vendido + $cantidad) 
                            WHERE id_credito = '$id_credito' 
                            AND id_producto = '$id_producto'",1);
                    }else{                                                              # - Si el producto no se encuentra registrado en el crédito
                        $this->db->insert('credito_detalle', array(                     # - REGISTRAR DETALLE DEL CRÉDITO - 
                            'id_credito' => $id_credito,
                            'id_producto' => $id_producto,
                            'cantidad' => $cantidad,
                            'devolucion' => 0,
                            'vendido' => $cantidad,
                            'costo' => $costo,
                            'precio' => $precio,
                            'descuento' => $descuento
                        ));
                    }
                    
                }else{
                    $this->db->insert('credito_detalle', array(                         # - REGISTRAR DETALLE DEL CRÉDITO - 
                        'id_credito' => $id_credito,
                        'id_producto' => $id_producto,
                        'cantidad' => $cantidad,
                        'devolucion' => 0,
                        'vendido' => $cantidad,
                        'costo' => $costo,
                        'precio' => $precio,
                        'descuento' => $descuento
                    ));
                }

                $this->db->insert('credito_historial', array(                           # - REGISTRAR HISTORIAL DE CRÉDITO (SALIDA) - 
                    'id_credito' => $id_credito,
                    'tipo' => 'salida',
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,
                    'fecha' => $fecha,
                    'hora' => $hora
                ));

                $this->db->insert('productos_cardex', [                                 # - REGISTRAR CARDEX COMO "venta" -
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,
                    'id_almacen' => $almacen['id_almacen'],
                    'id_clienteProveedor' => $id_cliente,
                    'costo' => $costo,
                    'precio' => $precio,
                    'descuento' => $descuento,                                          # descuento al que sale
                    'operacion' => 'venta',
                    'referencia' => $folio,
                    'movimiento' => 'crs',                                              # crs = credito salida
                    'fecha' => $fecha.' '.$hora 
                ]);

                $this->db->update('productos',array(                                    # - ACTUALIZAR VENTAS, STOCK Y SALIDAS DEL PRODUCTO -
                    'stock' => $nuevoStock, 
                    'ventasMostrador' => $nuevasVentas,
                    'total_salidas' => $nuevasSalidas
                ),"id='$id_producto'",1);

            }
            
            $compras = $cliente['compras'];
            $nuevasCompras = $compras + $sumaCantidad; 
            $this->db->update('clientes',array(                                         # - ACTUALIZAR COMPRAS AL CLIENTE -
                'compras' => $nuevasCompras, 
                'fechaUltimaCompra' => $fecha.' '.$hora
            ),"id_cliente='$id_cliente'",1);

            $this->vaciarListaCredito(); 

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => $msj);

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