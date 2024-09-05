<?php

namespace app\models;

use app\models as Model;
use Ocrend\Kernel\Helpers as Helper;
use Ocrend\Kernel\Models\Models;
use Ocrend\Kernel\Models\IModels;
use Ocrend\Kernel\Models\ModelsException;
use Ocrend\Kernel\Models\Traits\DBModel;
use Ocrend\Kernel\Router\IRouter;

use MercadoPago;

/**
 * Modelo Compra
 */
class Compra extends Models implements IModels {
    use DBModel;
    
    /**
     * PROD_ACCESS_TOKEN
     *
     * @var string
     */
    const ACCESS_TOKEN_MP = 'APP_USR-4195699361815598-090321-38b5f89049f01c4fa17abfa2b2b23f8a-623937048'; // TEST-4640407682966085-092304-1f603f9b064ab13f11f98da71c574403-624491582
    
    public function checkCart() {
        
        global $session;
        
        if( $session->get('carrito_compras') !== null && count($session->get('carrito_compras')) > 0 && !empty($session->get('carrito_compras')) ) { 
            $cambioStock = $this->cambioStock();
            if($cambioStock){
                return "change_stock";
            }
            return "";
        }else{
            return "reload";
        }
        
    }
    
    /**
     * Modifica el metodo de envio en el carrito de compras
     *
     * @return void
    */ 
    public function metodoEnvio() {
        try{
            
            global $http,$session;
            $metodo_envio = intval($http->query->get('metodo_envio'));              // 1 FEDEX, 2 RECOGER EN LIBRERIA
            
            if($metodo_envio <= 0 || $metodo_envio > 2){
                $session->remove('metodo_envio');
                throw new ModelsException('Es necesario seleccionar un método de envío correcto.');   
            }
            
            $session->set("metodo_envio", $metodo_envio);
            
            return array('status' => 'success', 'envio' => $session->get("metodo_envio"));
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }
    
    /**
     * Valida si los productos en la lista aun se encuentran en stock
     *
     * @return void
    */ 
    public function cambioStock() {
        global $session;
        $cambioStock = false;
        
        # - Si la lista de productos no esta vacía -
        if( $session->get('carrito_compras') !== null && count($session->get('carrito_compras')) > 0 && !empty($session->get('carrito_compras')) ) { 

            $arrayCarrito = $session->get("carrito_compras");

            foreach ($arrayCarrito as $key => $value) {
            
                $id = (int) $key;
                //$cant = (int) $value;

                $mi_libro = (new Model\Libro)->libroPor('stock', null, "estado=1 AND id=$id", 1);
                $stock = (int) $mi_libro['stock'];

                if($stock == 0) {
                    unset($arrayCarrito[$key]);  
                    $session->set("carrito_compras", $arrayCarrito);
                    $cambioStock = true;
                }
                
                
            }

            # - Si la cantidad de elementos es menor a 0
            if( count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ){
                $this->limpiarCarrito();
            }

        }
        return $cambioStock;

    }

    public function agregarCarrito(){
        try{
            global $http,$session;
            $id = intval($http->query->get('id'));
            if($id == 0){
                throw new ModelsException('Es necesario seleccionar un producto.');
            }

            $mi_libro = (new Model\Libro)->libroPor('*', null, "estado=1 AND id=$id", 1);
            if(!$mi_libro){
                throw new ModelsException('El producto no existe.');
            }

            $stock = (int) $mi_libro['stock'];
            if($stock == 0){
                throw new ModelsException('El producto no se encuentra disponible en este momento.');
            }

            # Declarar $agregado como false
            $agregado = false;

            # Si la session carrito_compras es igual a null
            if ( $session->get('carrito_compras') === null || count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ) {
                # Crear primer item de la lista
                $arrayCarrito[$id] = 1;
                # Crear el array de la session carrito_compras con el primer producto
                $session->set("carrito_compras", $arrayCarrito);
            } else {
                # Obtener el array de la session carrito_compras
                $arrayCarrito = $session->get("carrito_compras");
                
                if (array_key_exists($id, $arrayCarrito)){
                    $agregado = true;
                }

                if($agregado === true){
                    throw new ModelsException('El producto ya se agregó a tu compra.');
                }else{
                    # Agregamos el nuevo producto al array a la lsita existente
                    $arrayCarrito[$id] = 1;
                    # Ordenamos el array array por clave en orden inverso
                    krsort($arrayCarrito);
                    # Crear nuevamente el array de la session carrito_compras con otro producto agregado
                    $session->set("carrito_compras", $arrayCarrito);
                }
            }

            $cantidad = $this->cantidadCarrito();

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El producto se agregó a tu compra.', 'cantidad' => '('.$cantidad.')');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }

    public function cantidadCarrito(){
        global $session;
        $cantidad = 0;
        if ( $session->get('carrito_compras') === null || count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ) {
            return $cantidad;
        }else{
            $arrayCarrito = $session->get("carrito_compras");
            foreach($arrayCarrito as $key => $value){
                $cantidad = $cantidad + (int) $value;
            }
            return $cantidad;
        }
    }

    public function cargarCompra(){
        global $session, $config;

        $html = '';
        $html_movil = '';
        $cantidad_total = 0;
        $subtotal = 0;
        $info_dir = '';

        if ( $session->get('carrito_compras') === null || count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ) {
            $status = 'empty';
            $html .= '
            <div class="alert alert-inverse fade show animated flash" role="alert">
                <span class="alert-inner--icon text-info"><i class="fas fa-info-circle"></i></span>
                <span class="alert-inner--text"><strong>!La cesta está vacía!</strong> No hay productos en tu lista de compra.</span>
            </div>';
            $html_movil .= '
            <div class="alert alert-inverse fade show animated flash" role="alert">
                <span class="alert-inner--icon text-info"><i class="fas fa-info-circle"></i></span>
                <span class="alert-inner--text"><strong>!La cesta está vacía!</strong> No hay productos en tu lista de compra.</span>
            </div>';
            $resultados_txt = '0 productos en';
            $subtotal_format = '$ 0.00';
            $envio_format = '$ 0.00';
            $opc_envio = '--';
            $total_format = '$ 0.00';
            
            $boton = ''; 

        }else{
            $status = 'full';
            $arrayCarrito = $session->get("carrito_compras");
            foreach($arrayCarrito as $key => $value){
                $id = (int) $key;
                $cantidad = (int) $value;
                $cantidad_total = $cantidad_total + $cantidad;
                $mi_libro = (new Model\Libro)->libroPor('*, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar', null, "estado=1 AND id=$id", 1);
                if($mi_libro){
                    $autores = (new Model\Autor)->autoresHtml($mi_libro['autores']);
                    $precio = (real) $mi_libro['precioMostrar'];
                    $importe = $precio * $cantidad;
                    $subtotal += $importe;
                    $stock = (int) $mi_libro['stock'];
                    
                    
                    $html .= '
                    <div class="row rounded border mx-0 py-2 px-0 mb-3">
                        <div class="col-2 col-md-1 align-self-center">
                            <button type="button" class="btn btn-sm btn-icon-only rounded-circle boton_negro eliminar_carrito" idLibro="'.$mi_libro['id'].'">
                                <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                            </button>
                        </div>
                        <div class="col-3 col-md-2 align-self-center">
                            <img src="'.$config['build']['url'].$mi_libro['imagen'].'?'.time().'" class="d-block mt-2 mr-3" width="80px">
                        </div>
                        <div class="col-7 col-md-9 align-self-center">
                            <p class="font-weight-bold mb-0 text-truncate text-dark">'.$mi_libro['producto'].' '.$mi_libro['leyenda'].'</p>
                            <p class="font-weight-normal text-truncate text-muted mb-0"><span>'.$autores.'</span></p>
                            <span class="font-weight-bold float-right">'.$cantidad.' * $ '.number_format($precio,2).' = $ '.number_format($precio*$cantidad,2).'</span>';
                            $html .= '
                            <div class="input-group input-group-sm mb-2">';
                                if($cantidad == 1){
                                    $html .= '
                                    <div class="input-group-append">
                                        <span class="input-group-text px-2 btn rounded-0 border-right-0" disabled style="cursor:not-allowed;">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </div>
                                    ';
                                }else{
                                    $html .= '
                                    <div class="input-group-append quitar_cantidad" idLibro="'.$mi_libro['id'].'">
                                        <span class="input-group-text px-2 btn rounded-0 border-right-0 boton_negro">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </div>
                                    ';
                                }
                                $html .= '<input type="text" class="form-control form-control-sm px-1 text-center input_cantidad" id="input_cantidad'.$mi_libro['id'].'" idLibro="'.$mi_libro['id'].'" value="'.$cantidad.'" placeholder="0">';
                                if($cantidad == $stock){
                                    $html .= '
                                    <div class="input-group-append">
                                        <span class="input-group-text px-2 btn rounded-0" disabled style="cursor:not-allowed;">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                    </div>';
                                }else{
                                    $html .= '
                                    <div class="input-group-append agregar_cantidad" idLibro="'.$mi_libro['id'].'">
                                        <span class="input-group-text px-2 btn rounded-0 boton_negro">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                    </div>';
                                }
                            $html .= '
                            </div>';
                        $html .= '  
                        </div>
                    </div>';
                    
                    
                    
                    $html_movil .= '
                    <div class="row rounded border mx-0 py-2 px-0 mb-3">
                        <div class="col-2 col-md-1">
                            <button type="button" class="btn btn-sm btn-icon-only rounded-circle boton_negro eliminar_carrito" idLibro="'.$mi_libro['id'].'">
                                <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                            </button>
                            <img src="'.$config['build']['url'].$mi_libro['imagen'].'?'.time().'" class="d-block mt-2" width="40px">
                        </div>
                        <div class="col-10 col-md-11 align-self-center">
                            <p class="font-weight-bold mb-0 text-truncate text-dark">'.$mi_libro['producto'].' '.$mi_libro['leyenda'].'</p>
                            <p class="font-weight-normal text-truncate text-muted mb-0"><span>'.$autores.'</span></p>
                            <span class="font-weight-bold float-right">'.$cantidad.' * $ '.number_format($precio,2).' = $ '.number_format($precio*$cantidad,2).'</span><br>';
                            $html_movil .= '
                            <div class="input-group input-group-sm mb-2">';
                                if($cantidad == 1){
                                    $html_movil .= '
                                    <div class="input-group-append">
                                        <span class="input-group-text px-2 btn rounded-0 border-right-0" disabled style="cursor:not-allowed;">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </div>
                                    ';
                                }else{
                                    $html_movil .= '
                                    <div class="input-group-append quitar_cantidad" idLibro="'.$mi_libro['id'].'">
                                        <span class="input-group-text px-2 btn rounded-0 border-right-0 boton_negro">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </div>
                                    ';
                                }
                                $html_movil .= '<input type="text" class="form-control form-control-sm px-1 text-center input_cantidad" id="input_cantidad'.$mi_libro['id'].'" idLibro="'.$mi_libro['id'].'" value="'.$cantidad.'" placeholder="0">';
                                if($cantidad == $stock){
                                    $html_movil .= '
                                    <div class="input-group-append">
                                        <span class="input-group-text px-2 btn rounded-0" disabled style="cursor:not-allowed;">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                    </div>';
                                }else{
                                    $html_movil .= '
                                    <div class="input-group-append agregar_cantidad" idLibro="'.$mi_libro['id'].'">
                                        <span class="input-group-text px-2 btn rounded-0 boton_negro">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                    </div>';
                                }
                            $html_movil .= '
                            </div>';
                        $html_movil .= '   
                        </div>
                    </div>';
                }
            }
            $resultados_txt = $cantidad_total.' producto'.(($cantidad_total == 1) ? '': 's').' en';
            
            
            if ( $session->get('metodo_envio') === null || empty($session->get('metodo_envio')) ) {
                $envio_seleccionado = 1;
            } else {
                $envio_seleccionado = $session->get('metodo_envio');
            }
            
            if($envio_seleccionado == 1){
                $checked_1 = 'checked';
                $checked_2 = '';
            }elseif($envio_seleccionado == 2){
                $checked_1 = '';
                $checked_2 = 'checked';
            }
            
            if($subtotal >= 800){
                
                $total = $subtotal;
                
                $subtotal_format = '$ '.number_format($total,2);
                
                $opc_envio = '
                <div class="custom-control custom-radio">
                    <input class="custom-control-input metodo_envio" type="radio" name="metodo_envio" id="metodo_envio1" value="1" '.$checked_1.'>
                    <label class="custom-control-label" for="metodo_envio1">
                        $ 0.00 (FEDEX)
                    </label>
                </div>

                <div class="custom-control custom-radio mb-3">
                    <input class="custom-control-input metodo_envio" type="radio" name="metodo_envio" id="metodo_envio2" value="2" '.$checked_2.'>
                    <label class="custom-control-label" for="metodo_envio2">
                        Entrega en librería
                    </label>
                </div>';
                
                $total_format = '$ '.number_format($total,2);
                
            }else{
                
                if($envio_seleccionado == 1){
                    $costo_envio = 220;
                    if($this->id_user !== null){
                        $usuario = (new Model\Usuarios)->getOwnerUser();
                        $estado = [
                            1 => 'Aguascalientes',
                            2 => 'Baja California',
                            3 => 'Baja California Sur',
                            4 => 'Campeche',
                            5 => 'Coahuila',
                            6 => 'Colima',
                            7 => 'Chiapas',
                            8 => 'Chihuahua',
                            9 => 'Ciudad de México',
                            10 => 'Durango',
                            11 => 'Guanajuato',
                            12 => 'Guerrero',
                            13 => 'Hidalgo',
                            14 => 'Jalisco',
                            15 => 'Estado de México',
                            16 => 'Michoacán',
                            17 => 'Morelos',
                            18 => 'Nayarit',
                            19 => 'Nuevo León',
                            20 => 'Oaxaca',
                            21 => 'Puebla',
                            22 => 'Querétaro',
                            23 => 'Quintana Roo',
                            24 => 'San Luis Potosí',
                            25 => 'Sinaloa',
                            26 => 'Sonora',
                            27 => 'Tabasco',
                            28 => 'Tamaulipas',
                            29 => 'Tlaxcala',
                            30 => 'Veracruz',
                            31 => 'Yucatán',
                            32 => 'Zacatecas'
                        ];
                        $estado_cliente = $estado[$usuario['state']];
                        $info_dir .= "
                        <h6 class='text-muted font-weight-regular'>Dirección destino (<a href='./cuenta/mis-datos'>Actualizar</a>)</h6>
                        <small>
                            <strong>Estado:</strong> $estado_cliente<br>
                            <strong>Delegación/Municipio:</strong> {$usuario['municipality']}<br>
                            <strong>Colonia:</strong> {$usuario['colony']}<br>
                            <strong>Calle:</strong> {$usuario['street']} {$usuario['o_number']}<br>
                            <strong>CP:</strong> {$usuario['p_code']}<br>
                            <strong>Entre las calles:</strong> {$usuario['b_streets']}<br>
                            <strong>Referencias:</strong> {$usuario['a_references']}
                        </small>
                        ";
                    }
                }else{
                    $costo_envio = 0;
                }
                
                $total = $subtotal + $costo_envio;
                
                $subtotal_format = '$ '.number_format($subtotal,2);
                $envio_format = '$ '.number_format($costo_envio,2);
                
                $opc_envio = '
                <div class="custom-control custom-radio">
                    <input class="custom-control-input metodo_envio" type="radio" name="metodo_envio" id="metodo_envio1" value="1" '.$checked_1.'>
                    <label class="custom-control-label" for="metodo_envio1">
                        $ 220.00 (FEDEX)
                    </label>
                </div>
                
                <div class="custom-control custom-radio mb-3">
                    <input class="custom-control-input metodo_envio" type="radio" name="metodo_envio" id="metodo_envio2" value="2" '.$checked_2.'>
                    <label class="custom-control-label" for="metodo_envio2">
                        Entrega en librería
                    </label>
                </div>';
                
                $total_format = '$ '.number_format($total,2);
                
            }
            
            if($this->id_user === NULL){
                $boton = '
                <a href="autenticacion" class="btn btn-sm btn-icon boton_negro">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-inner--text">Procesar compra</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
                '; 
            }else{
                $boton = '
                <a href="compra/procesar" class="btn btn-sm btn-icon boton_negro">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-inner--text">Procesar compra</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
                ';
            }
        }

        return array('status' => $status, 'content' => $html, 'content_movil' => $html_movil, 'resultados' => $resultados_txt, 'subtotal' => $subtotal_format, 'opc_envio' => $opc_envio, 'total' => $total_format, 'cantidad' => '('.$cantidad_total.')', 'boton_procesar' => $boton, 'info_dir' => $info_dir);
    }
    
    public function cargarCompraDos(){
        global $session, $config;

        $html = '';
        $cantidad_total = 0;
        $subtotal = 0;

        if ( $session->get('carrito_compras') === null || count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ) {
            $status = 'empty';
            $html .= '
            <div class="alert alert-info alert-dismissible fade show animated flash" role="alert">
                <span class="alert-inner--icon"><i class="fas fa-info-circle"></i></span>
                <span class="alert-inner--text">La lista de compra está vacía.</span>
            </div>';
            $resultados_txt = '0 productos en';
            $subtotal_format = '$ 0.00';
            $envio_format = '$ 0.00';
            $total_format = '$ 0.00';

        }else{
            $status = 'full';
            $arrayCarrito = $session->get("carrito_compras");
            foreach($arrayCarrito as $key => $value){
                $id = (int) $key;
                $cantidad = (int) $value;
                $cantidad_total = $cantidad_total + $cantidad;
                $mi_libro = (new Model\Libro)->libroPor('*, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar', null, "estado=1 AND id=$id", 1);
                if($mi_libro){
                    $autores = (new Model\Autor)->autoresHtml($mi_libro['autores']);
                    $precio = (real) $mi_libro['precioMostrar'];
                    $importe = $precio * $cantidad;
                    $subtotal += $importe;
                    $html .= '
                    <div class="row rounded border mx-0 py-2 px-0 mb-1">
                        <div class="col-12 d-none d-sm-inline-block col-sm-3 col-md-2">
                            <img src="'.$config['build']['url'].$mi_libro['imagen'].'?'.time().'" class="d-block mt-2" width="80px">
                        </div>
                        <div class="col-12 col-sm-9 col-md-10 align-self-center">
                            <p class="font-weight-bold mb-0 text-truncate text-dark">'.$mi_libro['producto'].' '.$mi_libro['leyenda'].'</p>
                            <p class="font-weight-normal mb-0 text-truncate text-muted">'.$autores.'</p>
                            <span class="font-weight-bold float-right">'.$cantidad.' * $ '.number_format($precio,2).' = $ '.number_format($precio,2).'</span>
                        </div>
                    </div>';
                }
            }
            $resultados_txt = $cantidad_total.' producto'.(($cantidad_total == 1) ? '': 's').' en';
            
            if ( $session->get('metodo_envio') === null || empty($session->get('metodo_envio')) ) {
                $envio_seleccionado = 1;
            } else {
                $envio_seleccionado = $session->get('metodo_envio');
            }
            
            if($subtotal >= 800){
                
                $total = $subtotal;
                
                $subtotal_format = '$ '.number_format($total,2);
                if($envio_seleccionado == 1){
                    $envio_format = '$ 0.00 (FEDEX)';   
                }else{
                    $envio_format = 'Entrega en librería';
                }
                $total_format = '$ '.number_format($total,2);
                
            }else{
                
                if($envio_seleccionado == 1){
                    $costo_envio = 220;
                    $metodo_envio = '$ '.number_format($costo_envio,2).' (FEDEX)';
                }else{
                    $costo_envio = 0;
                    $metodo_envio = 'Entrega en librería';
                }
            
                $total = $subtotal + $costo_envio;
                
                $subtotal_format = '$ '.number_format($subtotal,2);
                $envio_format = $metodo_envio;
                $total_format = '$ '.number_format($total,2);
                
            }

        }
        
        return array('status' => $status, 'content' => $html, 'resultados' => $resultados_txt, 'subtotal' => $subtotal_format, 'envio' => $envio_format, 'total' => $total_format, 'cantidad' => '('.$cantidad_total.')');
    }
    
    private function cargarTotalCompra(){
        
        global $session;
        $arrayCarrito = $session->get("carrito_compras");
        $subtotal = 0;
        
        foreach($arrayCarrito as $key => $value){
            $id = (int) $key;
            $cantidad = (int) $value;
            $mi_libro = (new Model\Libro)->libroPor('*, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar', null, "estado=1 AND id=$id", 1);
            $precio = (real) $mi_libro['precioMostrar'];
            $importe = $precio * $cantidad;
            $subtotal += $importe;
        }
        
        if ( $session->get('metodo_envio') === null || empty($session->get('metodo_envio')) ) {
            $envio_seleccionado = 1;
        } else {
            $envio_seleccionado = $session->get('metodo_envio');
        }
        
        if($subtotal >= 800){
                
            $costo_envio = 0;
            $total = $subtotal;
            
        }else{
            
            if($envio_seleccionado == 1){
                $costo_envio = 220;
            }else{
                $costo_envio = 0;
            }
            $total = $subtotal + $costo_envio;
            
        }
        
        return $total;
        
    }
    
    private function cargarTotalCompraDetalle(){
        
        global $session;
        $arrayCarrito = $session->get("carrito_compras");
        $subtotal = 0;
        
        foreach($arrayCarrito as $key => $value){
            $id = (int) $key;
            $cantidad = (int) $value;
            $mi_libro = (new Model\Libro)->libroPor('*, IF(precioOferta > 0 ,precioOferta, precio) AS precioMostrar', null, "estado=1 AND id=$id", 1);
            $precio = (real) $mi_libro['precioMostrar'];
            $importe = $precio * $cantidad;
            $subtotal += $importe;
        }
        
        if ( $session->get('metodo_envio') === null || empty($session->get('metodo_envio')) ) {
            $envio_seleccionado = 1;
        } else {
            $envio_seleccionado = $session->get('metodo_envio');
        }
        
        if($subtotal >= 800){
                
            $costo_envio = 0;
            $total = $subtotal;
            
        }else{
            
            if($envio_seleccionado == 1){
                $costo_envio = 220;
            }else{
                $costo_envio = 0;
            }
            $total = $subtotal + $costo_envio;
            
        }
        
        return [$subtotal, $envio_seleccionado, $costo_envio];
        
    }

    public function elimiarCarrito(){
        try {
            global $http,$session;
            $id = intval($http->query->get('id'));
            if ( $session->get('carrito_compras') === null || count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ) {
                throw new ModelsException('La lista está vacia.');
            }
            if($id == 0){
                throw new ModelsException('Es necesario seleccionar un producto.');
            }

            # Obtener el array de la session arrayCarrito
            $arrayCarrito = $session->get("carrito_compras");

            if (!array_key_exists($id, $arrayCarrito)){
                throw new ModelsException('El producto no se encuentra en tu compra.');
            }else{
                # Eliminar producto
                unset($arrayCarrito[$id]);
                # Crear nuevamente el array de la session arrayCarrito con la nueva cantidad
                $session->set("carrito_compras", $arrayCarrito);
            }

            # - Si la cantidad de elementos es menor a 0
            if (count($session->get('carrito_compras')) == 0){
                # - Ejecutar el método para vaciar el carrito
                $this->limpiarCarrito();
            }

            return array('status' => 'success');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }

    public function modificarCantidad(){
        try {
            
            global $http,$session;
            $id = intval($http->query->get('id'));
            $cantidad = intval($http->query->get('cantidad'));
            if($cantidad == 0){
                $cantidad = 1;
            }
            if ( $session->get('carrito_compras') === null || count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ) {
                throw new ModelsException('La lista está vacia.');
            }
            if($id == 0){
                throw new ModelsException('Es necesario seleccionar un producto.');
            }
            $mi_libro = (new Model\Libro)->libroPor('*', null, "estado=1 AND id=$id", 1);
            if(!$mi_libro){
                throw new ModelsException('El producto no existe.');
            }
            $stock = (int) $mi_libro['stock'];
            if($stock < $cantidad){
                throw new ModelsException('La cantidad solicitada no se encuentra disponible.');
            }

            # Obtener el array de la session arrayCarrito
            $arrayCarrito = $session->get("carrito_compras");
            if (!array_key_exists($id, $arrayCarrito)){
                throw new ModelsException('El producto no se encuentra en tu compra.');
            }else{
                # Actualizamos la cantidad por la nueva
                $arrayCarrito[$id] = $cantidad;
                # Crear nuevamente el array de la session arrayCarrito con la nueva cantidad
                $session->set("carrito_compras", $arrayCarrito);
            }

            return array('status' => 'success');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }

    public function limpiarCarrito() {
        global $session;
        $session->remove('carrito_compras');
        $session->remove('metodo_envio');
    }

    public function solicitarVale() {
        
        global $session;
        
        if ( $session->get('carrito_compras') === null || count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ) {
                return array('status' => 'error');
        }else{

            if( $this->cambioStock() ){
                return array('status' => 'error');
            }
                
            $u = new Model\Usuarios;
            $usuario = $u->getOwnerUser();
            
            $folio = $usuario["id_cliente"]."-".uniqid();
                        
            \Stripe\Stripe::setApiKey('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');
            
            $intent = \Stripe\PaymentIntent::create([
                "amount" => ($this->cargarTotalCompra() * 100),
                "currency" => "mxn",
                "description" => $folio,
                "payment_method_types" => ["oxxo"],
                "receipt_email" => $usuario["correoElectronico"]
            ]);
            
            $montos = $this->cargarTotalCompraDetalle();
            $compra = $this->db->insert('compras_stripe', array(
                'id_stripe' => $intent->id,
                'folio' => $folio,
                'id_cliente' => $usuario["id_cliente"],
                'monto_compra' => $montos[0],
                'envio' => $montos[1],
                'monto_envio' => $montos[2],
                'metodo_pago' => 2,
                'estatus' => $intent->status,
                'fecha' => date('Y-m-d'),
                'hora' => date('h:i:s')
            ));
            
            $arrayCarrito = $session->get("carrito_compras");
            foreach($arrayCarrito as $key => $value){
                $id = (int) $key;
                $cantidad = (int) $value;
                $mi_libro = (new Model\Libro)->libroPor('*', null, "estado=1 AND id=$id", 1);
                if($mi_libro){
                    if($mi_libro['oferta'] == 1){
                        $puntos = 0;
                    }else{
                        $puntos = ($cantidad * $mi_libro['precio']) * 0.05;
                    }
                    $this->db->insert('compras_stripe_detalle', array(
                        'id_compra' => $compra,
                        'id_producto' => $id,
                        'cantidad' => $cantidad,
                        'puntos' => $puntos,
                        'costo' => $mi_libro['precio_compra'],
                        'precio' => $mi_libro['precio'],
                        'descuento' => $mi_libro['descuentoOferta'],
                        'precioDescuento' => $mi_libro['precioOferta'],
                    ));
                }
            
            }
            
            return array('status' => 'success', 'name' => $usuario["cliente"], 'email' => $usuario["correoElectronico"], 'intent' => $intent->client_secret, 'id_stripe' => $intent->id);
        }
    }
    
    public function registrarCompra($stripeToken) {
        
        try {
            
            global $config, $session;
            
            if ( $session->get('carrito_compras') === null || count($session->get('carrito_compras')) == 0 || empty($session->get('carrito_compras')) ) {
                Helper\Functions::redir($config['build']['url'].'compra?error=compra_vacia');
            }else{
                
                if( $this->cambioStock() ){
                    Helper\Functions::redir($config['build']['url'].'compra?error=cambio_stock');
                }
            
                $u = new Model\Usuarios;
                $usuario = $u->getOwnerUser();
                $folio = $usuario["id_cliente"]."-".uniqid();
                
                // Cargar Stripe
                \Stripe\Stripe::setApiKey('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');
                // Cobrar al cliente
                $charge = \Stripe\Charge::create(array(
                    "amount" => ($this->cargarTotalCompra() * 100),
                    "currency" => "mxn",
                    "description" => $folio,
                    "receipt_email" => $usuario["correoElectronico"],
                    "source" => $stripeToken
                ));
                
                $montos = $this->cargarTotalCompraDetalle();
                $compra = $this->db->insert('compras_stripe', array(
                    'id_stripe' => $charge->id,
                    'folio' => $folio,
                    'id_cliente' => $usuario["id_cliente"],
                    'monto_compra' => $montos[0],
                    'envio' => $montos[1],
                    'monto_envio' => $montos[2],
                    'metodo_pago' => 1,
                    'comprobante' => $charge->receipt_url,
                    'estatus' => $charge->status,
                    'fecha' => date('Y-m-d'),
                    'hora' => date('h:i:s')
                ));
                
                $arrayCarrito = $session->get("carrito_compras");
                foreach($arrayCarrito as $key => $value){
                    $id = (int) $key;
                    $cantidad = (int) $value;
                    $mi_libro = (new Model\Libro)->libroPor('*', null, "estado=1 AND id=$id", 1);
                    if($mi_libro){
                        if($mi_libro['oferta'] == 1){
                            $puntos = 0;
                        }else{
                            $puntos = ($cantidad * $mi_libro['precio']) * 0.05;
                        }
                        $this->db->insert('compras_stripe_detalle', array(
                            'id_compra' => $compra,
                            'id_producto' => $id,
                            'cantidad' => $cantidad,
                            'puntos' => $puntos,
                            'costo' => $mi_libro['precio_compra'],
                            'precio' => $mi_libro['precio'],
                            'descuento' => $mi_libro['descuentoOferta'],
                            'precioDescuento' => $mi_libro['precioOferta'],
                        ));
                    }
                
                }
                
                
                (new Model\Compra)->limpiarCarrito();
                Helper\Functions::redir($config['build']['url'].'cuenta/mis-compras');
            
            }
        
        } catch(\Exception $e) {
            $stripeCode = $e->getStripeCode();
            $message = $e->getMessage();
            
            if($stripeCode == "incorrect_cvc"){
                $message = "El código de seguridad de tu tarjeta es incorrecto.";
            }elseif($stripeCode == "card_declined"){
                $message = "Tu tarjeta fue rechazada.";
            }elseif($stripeCode == "expired_card"){
                $message = "Tu tarjeta ha caducado.";
            }elseif($stripeCode == "processing_error"){
                $message = "Se produjo un error al procesar tu tarjeta. Inténtalo de nuevo en un momento.";
            }elseif($stripeCode == "incorrect_number"){
                $message = "El número de tu tarjeta no es válido.";
            }elseif($stripeCode == "authentication_required"){
                $message = "Tu tarjeta fue rechazada. Esta transacción requiere autenticación.";
            }elseif($stripeCode == "token_already_used"){
                $message = "No puedes usar un token de compra más de una vez.";
            }else{
                $message = "Se produjo un error al procesar tu tarjeta. Inténtalo de nuevo en un momento.";
            }
            
            Helper\Functions::redir($config['build']['url']."compra/procesar?error=$stripeCode&message=$message");
        }
        
    }
    
    
    public function mostrarCompras() {
        global $config;
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            Helper\Functions::redir($config['build']['url'].'autenticacion');
        }
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $compras = $this->db->select("*", "compras", null, "id_cliente = '$this->id_user'", null, "ORDER BY id DESC");
        $data = [];
        if($compras){
            $access_token = self::ACCESS_TOKEN_MP;
            foreach ($compras as $key => $value) {
                $pago = json_decode(file_get_contents("https://api.mercadopago.com/v1/payments/{$value['mp_payment_id']}?access_token=$access_token"),true);
                $infoData = [];
                $infoData[] = '<span class="btn btn-sm btn-outline-dark py-1 info_pago" data-toggle="modal" data-target="#info_pago" id="'.$value['mp_payment_id'].'" referencia="'.$value["folio"].'">'.$value["folio"].'</span>';
                $fechaDesc = strftime("%A, %d de %B de %Y a las %H:%M", strtotime($pago['date_created']));
                $infoData[] = $fechaDesc;
                if($value["mp_payment_status"] == 'pending'){
                    if(strtotime(date("c")) > strtotime($value['date_of_expiration'])){
                        $estatus = '
                        <span class="badge badge-dot mr-4 text-dark">
                            <i class="bg-danger"></i> Expiró
                        </span>';
                    }else{
                        $estatus = '
                        <span class="badge badge-dot mr-4 text-dark animated infinite flash">
                            <i class="bg-warning"></i> Pendiente
                        </span>';
                    }
                }elseif($value["mp_payment_status"] == 'approved'){
                    $estatus = '
                    <span class="badge badge-dot mr-4 text-dark">
                        <i class="bg-success"></i> Aprobado
                    </span>';
                }elseif($value["mp_payment_status"] == 'prepared'){
                    $estatus = '
                    <span class="badge badge-dot mr-4 text-dark">
                        <i class="bg-success"></i> Listo para envío
                    </span>';
                }elseif($value["mp_payment_status"] == 'sent'){
                    $estatus = '
                    <span class="badge badge-dot mr-4 text-dark">
                        <i class="bg-info"></i> En camino ('.$value['guia'].')
                    </span>';
                }elseif($value["mp_payment_status"] == 'delivered'){
                    $estatus = '
                    <span class="badge badge-dot mr-4 text-dark">
                        <i class="bg-primary"></i> Entregado
                    </span>';
                }else{
                    $estatus = '
                    <span class="badge badge-dot mr-4 text-dark">
                        <i class="bg-dark"></i> Indefinido
                    </span>';
                }
                $infoData[] = $estatus;
                $infoData[] = '$ '.number_format(($value["total"] + $value["monto_envio"]),2);
                if($value["payment_type_id"] == 'ticket' && $value["payment_method_id"] == 'oxxo'){
                    if(strtotime(date("c")) > strtotime($value['date_of_expiration'])){
                        $infoData[] = "OXXO";
                    }else{
                        $infoData[] = '<a href="'.$pago["transaction_details"]["external_resource_url"].'" target="_blank">OXXO</a>';
                    }
                }else{
                    $infoData[] = 'Tarjeta';
                }
                $data[] = $infoData;  
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }
    
    public function mostrarComprasStripe() {
        global $config;
        if($this->id_user === NULL) {                                           # - Si el usuario no esta logeado o es igual a NULL -
            Helper\Functions::redir($config['build']['url'].'autenticacion');
        }
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $compras = $this->db->select("*", "compras_stripe", null, "id != 1 AND estatus != 'requires_payment_method' AND id_cliente = '$this->id_user'", null, "ORDER BY id DESC");
        $data = [];
        if($compras){
            foreach ($compras as $key => $value) {
                $infoData = [];
                $infoData[] = '<span class="btn btn-sm btn-outline-dark py-1 info_pago" data-toggle="modal" data-target="#info_pago" folio="'.$value["folio"].'">'.$value['folio'].'</span>';
                $infoData[] = $value['id_stripe'];
                $fechaDesc = '<span class="d-none d-sm-inline">'.strftime("%A, %d de %B de %Y a las %H:%M", strtotime($value['fecha'].' '.$value['hora'])).'</span><span class="d-inline d-sm-none">'.$value['fecha'].' '.$value['hora'].'</span>';
                $infoData[] = $fechaDesc;
                switch ($value['estatus']) {
                    case 'requires_payment_method':
                        $infoData[] = 'Requiere método de pago';
                        break;
                    case 'requires_action':
                        $infoData[] = 'Por pagar';
                        break;
                    case 'succeeded':
                        $infoData[] = 'Confirmamos el pago';
                        break;
                    case 'refunded':
                        $infoData[] = 'Reembolsado';
                        break;
                    case 'prepared':
                        $infoData[] = 'Preparando tu pedido';
                        break;
                    case 'sent':
                        $infoData[] = 'Está en camino';
                        break;
                    case 'delivered':
                        $infoData[] = 'Entregamos tu pedido';
                        break;
                    default:
                        $infoData[] = 'No definido';
                        break;
                }
                $infoData[] = '$ '.number_format(($value['monto_compra'] + $value['monto_envio']),2);
                $infoData[] = ($value['metodo_pago'] == 1) ? 'Tarjeta' : 'Vale OXXO';
                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }
    
    public function mostrarCompra() {
        try{
            setlocale(LC_TIME, 'es_ES.UTF-8');
            
            global $http;
            $folio = trim($http->query->get('folio'));
            
            $compra = $this->db->select("*", "compras_stripe", null, "folio = '$folio'", null, "ORDER BY id DESC", 1);
            if(!$compra){
                throw new ModelsException('No hay información asociada a la referencia.');
            }
            $compra = $compra[0];
            if($compra['envio'] == 1){
                if($compra['guia'] != ''){
                    $enlace = '<br>ID de Rastreo: <a target="_blank" href="https://www.fedex.com/apps/fedextrack/index.html?tracknumbers='.$compra['guia'].'&cntry_code=mx">'.$compra['guia'].'</a>';
                }else{
                    if($compra['estatus'] == 'refunded'){
                        $enlace = '';  
                    }else{
                        $enlace = '<br> Pendiente';   
                    }
                }
                $envio = 'Envío $ '.number_format($compra['monto_envio'],2).$enlace;
            }else{
                $envio = 'Entrega en <br><a href="https://goo.gl/maps/hcBqLkiNygMRbaBQ9" target="_blank">Librería</a>';
            }
            
            $metodo_pago = $compra['metodo_pago'] == 1 ? 'Tarjeta' : 'Vale OXXO';
            switch ($compra['estatus']) {
                case 'requires_payment_method':
                    $estatus = 'Requiere método de pago';
                    break;
                case 'requires_action':
                    $estatus = 'Por pagar';
                    break;
                case 'succeeded':
                    $estatus = 'Confirmamos el pago';
                    break;
                case 'refunded':
                    $estatus = 'Reembolsado';
                    break;
                case 'prepared':
                    $estatus = 'Preparando tu pedido';
                    break;
                case 'sent':
                    $estatus = 'Está en camino';
                    break;
                case 'delivered':
                    $estatus = 'Entregamos tu pedido';
                    break;
                default:
                    $estatus = 'No definido';
                    break;
            }
            
            $html = '
            <table class="w-100 text-center border-bottom">
                <tbody>
                    <tr>
                        <td class="w-50 pb-2">Subtotal $'.number_format($compra['monto_compra'],2).'</td>
                        <td class="w-50 pb-2">'.$envio.'</td>
                    </tr>
                </tbody>
            </table>
            <h1 class="mt-2 p-0 text-center text-app"><span class="total">$ '.number_format(($compra['monto_compra']+$compra['monto_envio']),2).'</span></h1>
            <table class="w-100 text-center mb-4">
                <tbody>
                    <tr>
                        <td colspan="2">'.strftime("%A, %d de %B de %Y a las %H:%M", strtotime($compra['fecha'].' '.$compra['hora'])).'</td>
                    </tr>
                    <tr>
                        <td class="w-50 text-right pr-1 font-weight-bold">Método pago:</td>
                        <td class="w-50 text-left pl-1">'.$metodo_pago.'</td>
                    </tr>
                    <tr>
                        <td class="w-50 text-right pr-1 font-weight-bold">Estatus:</td>
                        <td class="w-50 text-left pl-1">'.$estatus.'</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="'.$compra['comprobante'].'" target="_blank" class="btn btn-sm btn-outline-dark btn-icon py-1 mt-2">
                                <span class="btn-inner--text">Comprobante de pago</span>
                                <span class="btn-inner--icon"><i class="fas fa-print"></i></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="./cuenta/ticket/'.$folio.'" target="_blank" class="btn btn-sm btn-outline-dark btn-icon py-1 mt-2">
                                <span class="btn-inner--text">Ticket de compra</span>
                                <span class="btn-inner--icon"><i class="fas fa-print"></i></span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-muted text-center"><h5>Resumen de la compra</h5></div>
            ';
            
            $compra_detalle = $this->db->select("cd.*", "compras_stripe AS c", "INNER JOIN compras_stripe_detalle AS cd ON c.id = cd.id_compra", "c.folio = '$folio'");
            foreach($compra_detalle as $key => $value){
                $id = $value['id_producto'];
                $cantidad = (real) $value['cantidad'];
                $precio = (real) $value['precio'];
                $descuento = (real) $value['descuento'];
                if($descuento != 0){
                    $precio = (real) $value['precioDescuento'];
                }
                $mi_libro = (new Model\Libro)->libroPor('*', null, "estado=1 AND id=$id", 1);
                
                if($mi_libro){
                    if($mi_libro['leyenda'] != ''){
                        $leyenda = ' '.$mi_libro['leyenda'];
                    }else{
                        $leyenda = '';
                    }
                    $autores = (new Model\Autor)->autoresHtml($mi_libro['autores']);
                    //$importe = $precio;
                    //$subtotal += $importe;
                    $html .= '
                    <div class="row rounded border mx-0 py-2 px-0 mb-1">
                        <div class="col-2">
                            <img src="'.$config['build']['url'].$mi_libro['imagen'].'?'.time().'" class="mt-2 d-none d-md-block" width="70px">
                            <img src="'.$config['build']['url'].$mi_libro['imagen'].'?'.time().'" class="mt-2 d-block d-md-none" width="40px">
                        </div>
                        <div class="col-10 align-self-center">
                            <p class="font-weight-bold mb-0 text-truncate text-dark">'.$mi_libro['producto'].' '.$mi_libro['leyenda'].'</p>
                            <p class="font-weight-normal mb-0 text-truncate text-muted">'.$autores.'</p>
                            <span class="font-weight-bold float-right">'.$cantidad.' * $ '.number_format($precio,2).' = $ '.number_format($precio,2).'</span>
                        </div>
                    </div>';
                }
                
            }
                
            return array(
                'status' => 'success', 
                'html' => $html,
            );
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }
    
    public function datosPlantilla() : array {

        $datosPlantilla = $this->db->select('*', 'plantilla');
        return $datosPlantilla[0];

    }
    
    public function compra($folio) {
        
        $compra = $this->db->select('*', 'compras_stripe', null, "folio='$folio'");
        return $compra[0];
        
    }
    
    public function imprimirTicket($folio) {

        $datosPlantilla = $this->datosPlantilla();
        $compra = $this->compra($folio);

        $compra_detalle = $this->db->select("cd.*", "compras_stripe AS c", "INNER JOIN compras_stripe_detalle AS cd ON c.id = cd.id_compra", "c.folio = '$folio'");
        
        $mp = $compra['metodo_pago'] == 1 ? 'TARJETA' : 'OXXO';
        switch ($compra['estatus']) {
            case 'requires_payment_method':
                $estatus = 'Requiere método de pago';
                break;
            case 'requires_action':
                $estatus = 'Por pagar';
                break;
            case 'succeeded':
                $estatus = 'Confirmamos el pago';
                break;
            case 'refunded':
                $estatus = 'Reembolsado';
                break;
            case 'prepared':
                $estatus = 'Preparando tu pedido';
                break;
            case 'sent':
                $estatus = 'Está en camino';
                break;
            case 'delivered':
                $estatus = 'Entregamos tu pedido';
                break;
            default:
                $estatus = 'No definido';
                break;
        }
            
        $fecha = date_create($compra['fecha']);
        $fecha = date_format($fecha, 'd/m/Y');
        $hora = date_create($compra['hora']);
        $hora = date_format($hora, 'h:i a');
        
        $page_size = 110;
        $item_size = 12;
        $items = count($compra_detalle);

        $height = $page_size + ($item_size*$items);

        $html = '
        <div style="padding:0; font-family: Arial, Helvetica, sans-serif; width:320px;">
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
                '.$folio.'
            </p>
            <p style="text-align:center; font-size:11px;">Venta en línea</p>
            <p style="text-align:center; margin:0; padding:0;"><b>'.$estatus.'</b></p>
            <br>
            <table style="font-size:11px; width: 100%;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width: 25%; max-width:25%; text-align:left; padding-bottom:3px;">Cant.</td>
                    <td style="width: 75%; max-width:75%; text-align:left; padding-bottom:3px;" colspan="2">DESCRIPCIÓN</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-bottom:10px; border-bottom:1px solid #000;">
                        Precio
                    </td>
                    <td style="text-align:left; padding-bottom:10px; border-bottom:1px solid #000;">
                        Dto.
                    </td>
                    <td style="width: 30%; max-width:30%; text-align:right; padding-bottom:10px; border-bottom:1px solid #000;">
                        Importe
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="height:12px;"></td>
                </tr>';
                
            $sumaCantidad = 0;
                
            foreach($compra_detalle as $key => $value){
                
                $id = $value['id_producto'];
                $cantidad = (real) $value['cantidad'];
                $precio = (real) $value['precio'];
                $descuento = (real) $value['descuento'];
                
                if($descuento != 0){
                    $precio = (real) $value['precioDescuento'];
                }
                $subtotal = $cantidad * $precio;
                
                $mi_libro = (new Model\Libro)->libroPor('*', null, "id=$id", 1);
                
                if($mi_libro){

                    $autores = (new Model\Autor)->autoresHtml($mi_libro['autores']);
                    $html .= '
                        <tr>
                            <td style="text-align:left; padding-bottom:3px;">
                                '.$cantidad.'
                            </td>
                            <td style="text-align:left; padding-bottom:3px;" colspan="2">
                                '.$mi_libro['producto'].'
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:left; padding-bottom:4px;">
                                '.number_format($precio,2).'
                            </td>
                            <td style="padding-bottom:4px;">
                            </td>
                            <td style="text-align:right; padding-bottom:4px;">
                                '.number_format($subtotal,2).'
                            </td>
                        </tr>';
                }
                
                $sumaCantidad += $cantidad;
                
            }

            $html .= '
            </table>';
            
            $monto_compra = (real) $compra['monto_compra']; 
            $monto_envio = (real) $compra['monto_envio'];
            $total = $monto_compra + $monto_envio;

            $html .= '
            <table style="font-size:11px; width: 100%; border-top:1px solid #000; margin-top:10px;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width:20%; text-align:left; padding-top:10px;"><strong>'.$sumaCantidad.'</strong></td>
                <td style="width:50%; text-align:right; padding-top:10px;" colspan="2">Subtotal:</td>
                <td style="width:30%; text-align:right; padding-top:10px;"><strong>'.number_format($monto_compra,2).'</strong></td>
            </tr>
            <tr>
                <td style="width:20%; text-align:left; padding-top:1px;"></td>
                <td style="width:50%; text-align:right; padding-top:1px;" colspan="2">Costo de envío:</td>
                <td style="width:30%; text-align:right; padding-top:1px;"><strong>'.number_format($monto_envio,2).'</strong></td>
            </tr>
            <tr>
                <td style="width:20%; text-align:left; padding-top:1px;"></td>
                <td style="width:50%; text-align:right; padding-top:1px;" colspan="2">TOTAL:</td>
                <td style="width:30%; text-align:right; padding-top:1px;"><strong>'.number_format($total,2).'</strong></td>
            </tr>
            <tr>
                <td style="width:20%; text-align:left; padding-top:1px;"></td>
                <td style="width:50%; text-align:right; padding-top:1px;" colspan="2">Método de pago:</td>
                <td style="width:30%; text-align:right; padding-top:1px;"><strong>'.$mp.'</strong></td>
            </tr>
            </table>
            <table style="width: 100%; margin-top:5px;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="font-size:12px; width:100%; text-align:center; padding:5px 0; font-weight:bold;">
                        NO SE ACEPTAN <br> CAMBIOS NI DEVOLUCIONES 
                    </td>
                </tr>
            </table>
            <p style="text-align:center; font-size:10px;">
                ~ Gracias por su compra ~<br>
            </p>
        </div>';

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