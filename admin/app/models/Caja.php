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
 * Modelo Caja
 */
class Caja extends Models implements IModels {
    use DBModel;

    /**
     * Cargar la caja del día
     * 
     * @return array
    */ 
    public function cargarCaja($item = '', $valor = '') {

        if($item != '' && $valor != ''){
            $and = "AND $item = '$valor'";
        }else{
            $and = '';
        }

        $hoy = date('Y-m-d');
        $cajaHoy = $this->db->select('*', 'caja', null, "fecha_apertura = '$hoy' $and");
        if($cajaHoy){
            return $cajaHoy[0];
        }
        return false;
    }

    /**
     * Cargar la caja por el valor de un item
     * 
     * @return array
    */ 
    public function cargarCajaPor($item, $valor, $item2 = '', $valor2 = '') {

        if($item2 != '' && $valor2 != ''){
            $and = "AND $item2 = '$valor2'";
        }else{
            $and = '';
        }

        $caja = $this->db->select('*', 'caja', null, "$item = '$valor' $and");
        if($caja){
            return $caja[0];
        }
        return false;
    }
    
    public function obtenerSaldo($id_caja) {
        
        $ingresos = $this->db->select('SUM(monto) AS total_ingresos', 'caja_movimientos', null, "id_caja='".$id_caja."' AND tipo='Ingreso'");
        $total_ingresos = (real) $ingresos[0]['total_ingresos'];

        $cobros = $this->db->select('SUM(monto) AS total_cobros', 'caja_movimientos', null, "id_caja='".$id_caja."' AND tipo='Cobro'");
        $total_cobros = (real) $cobros[0]['total_cobros'];
        
        $abonos = $this->db->select('SUM(monto) AS total_abonos', 'caja_movimientos', null, "id_caja='".$id_caja."' AND tipo='Abono'");
        $total_abonos = (real) $abonos[0]['total_abonos'];

        $egresos = $this->db->select('SUM(monto) AS total_egresos', 'caja_movimientos', null, "id_caja='".$id_caja."' AND tipo='Egreso'");
        $total_egresos = (real) $egresos[0]['total_egresos'];

        $pagos = $this->db->select('SUM(monto) AS total_pagos', 'caja_movimientos', null, "id_caja='".$id_caja."' AND tipo='Pago'");
        $total_pagos = (real) $pagos[0]['total_pagos'];
    
        $saldo = ($total_ingresos + $total_cobros + $total_abonos) - ($total_egresos + $total_pagos);
        
        return $saldo;
        
    }

    /**
     * Cargar los movimientos en caja por fecha seleccionada
     * 
     * @return array
    */ 
    public function cargarCajaPorDia() {
        try {
            global $http;
            # Obtener los datos $_POST
            $fecha = $this->db->scape($http->request->get('fecha'));                                                    // obtener fecha seleccionada
            if($fecha == ''){                                                                                           // si la fecha viene vacía se asignará la fecha de hoy
                $fecha = date('Y-m-d');
            }
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);                                         // Obtener el almacén donde ha iniciado sesión el usuario
            $caja = $this->cargarCajaPor('fecha_apertura', $fecha, 'id_almacen', $almacen['id_almacen']);               // Obtener los datos de la caja por la fecha y por el almacén
            
            if($caja){                                                                                                  // Si hay datos registrados en caja con la fecha y alamcén
            
                $id_caja = $caja["id_caja"];                                                                            // id de la caja
                $monto = (real) $caja["monto"];                                                                         // monto con el que se incio la caja
                $estado = ($caja["estado"] == 0) ? 'Abierta' : 'Cerrada';                                               // verificar el estado de la caja (0 = Aboerta, 1 = Cerrada)
                
                /* INGRESOS --------------------------------------------------------------------------------------------*/
                $ingresos = $this->db->select('*', 'caja_movimientos', null, "id_caja='$id_caja' AND tipo='Ingreso'");  // obtener los ingresos en caja
        
                $total_ingresos = 0;
                $tr_ingresos = '';
                if($ingresos){                                                                                          // si hay registros de ingresos
                    foreach ($ingresos as $key => $value) {                                                             // recorrer los registros para llenar las filas con estos datos
                        $usuario = (new Model\Administradores)->administrador($value['usuario']);                       // obtener datos del usuario
                        $total_ingresos += $value['monto'];                                                             // hacer la suma del monto de ingreso por cada registro
                        $tr_ingresos .= '
                        <tr>
                            <td>'.$value['concepto'].'</td>
                            <td>'.$value['referencia'].'</td>
                            <td>'.$value['descripcion'].'</td>
                            <td class="text-right text-green font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                            <td>'.$usuario['name'].'</td>
                            <td>'.$value['hora'].'</td>
                        </tr>';                                                                                         // fila de ingresos con los datos respectivos por cada registro
                    }
                    $tr_ingresos .= '
                    <tr>
                        <th colspan="4" class="text-right"><span class="text-green">$ '.number_format($total_ingresos,2).'</span></th>
                        <th colspan="2"></th>
                    </tr>';                                                                                             // fila con el monto total de ingresos
                }else{
                    $tr_ingresos .= '
                    <tr>
                        <td colspan="6" class="text-center">No hay registro de ingresos.</td>
                    </tr>';
                }

                /* COBROS ----------------------------------------------------------------------------------------------*/
                $cobros = $this->db->select('*', 'caja_movimientos', null, "id_caja='$id_caja' AND tipo='Cobro'");
                $total_cobros = 0;
                $tr_cobros = '';
                if($cobros){
                    foreach ($cobros as $key => $value) {
                        $usuario = (new Model\Administradores)->administrador($value['usuario']);
                        $total_cobros += $value['monto'];
                        $tr_cobros .= '
                        <tr>
                            <td>'.$value['concepto'].'</td>
                            <td>'.$value['referencia'].'</td>
                            <td>'.$value['descripcion'].'</td>
                            <td class="text-right text-teal font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                            <td>'.$usuario['name'].'</td>
                            <td>'.$value['hora'].'</td>
                        </tr>';
                    }
                    $tr_cobros .= '
                    <tr>
                        <th colspan="4" class="text-right"><span class="text-teal">$ '.number_format($total_cobros,2).'</span></th>
                        <th colspan="2"></th>
                    </tr>';
                }else{
                    $tr_cobros .= '
                    <tr>
                        <td colspan="6" class="text-center">No hay registro de cobros.</td>
                    </tr>';
                }
                
                /* ABONOS ----------------------------------------------------------------------------------------------*/
                $abonos = $this->db->select('*', 'caja_movimientos', null, "id_caja='$id_caja' AND tipo='Abono'");
                $total_abonos = 0;
                $tr_abonos = '';
                if($abonos){
                    foreach ($abonos as $key => $value) {
                        $usuario = (new Model\Administradores)->administrador($value['usuario']);
                        $total_abonos += $value['monto'];
                        $tr_abonos .= '
                        <tr>
                            <td>'.$value['concepto'].'</td>
                            <td>'.$value['referencia'].'</td>
                            <td>'.$value['descripcion'].'</td>
                            <td class="text-right text-teal font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                            <td>'.$usuario['name'].'</td>
                            <td>'.$value['hora'].'</td>
                        </tr>';
                    }
                    $tr_abonos .= '
                    <tr>
                        <th colspan="4" class="text-right"><span class="text-teal">$ '.number_format($total_abonos,2).'</span></th>
                        <th colspan="2"></th>
                    </tr>';
                }else{
                    $tr_abonos .= '
                    <tr>
                        <td colspan="6" class="text-center">No hay registro de abonos.</td>
                    </tr>';
                }

                /* EGRESOS ---------------------------------------------------------------------------------------------*/
                $egresos = $this->db->select('*', 'caja_movimientos', null, "id_caja='$id_caja' AND tipo='Egreso'");
                $total_egresos = 0;
                $tr_egresos = '';
                if($egresos){
                    foreach ($egresos as $key => $value) {
                        $usuario = (new Model\Administradores)->administrador($value['usuario']);
                        $total_egresos += $value['monto'];
                        $tr_egresos .= '
                        <tr>
                            <td>'.$value['concepto'].'</td>
                            <td>'.$value['referencia'].'</td>
                            <td>'.$value['descripcion'].'</td>
                            <td class="text-right text-red font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                            <td>'.$usuario['name'].'</td>
                            <td>'.$value['hora'].'</td>
                        </tr>';
                    }
                    $tr_egresos .= '
                    <tr>
                        <th colspan="4" class="text-right"><span class="text-red">$ '.number_format($total_egresos,2).'</span></th>
                        <th colspan="2"></th>
                    </tr>';
                }else{
                    $tr_egresos .= '
                    <tr>
                        <td colspan="6" class="text-center">No hay registro de egresos.</td>
                    </tr>';
                }

                /* PAGOS -----------------------------------------------------------------------------------------------*/
                $pagos = $this->db->select('*', 'caja_movimientos', null, "id_caja='$id_caja' AND tipo='Pago'");
                $total_pagos = 0;
                $tr_pagos = '';
                if($pagos){
                    foreach ($pagos as $key => $value) {
                        $usuario = (new Model\Administradores)->administrador($value['usuario']);
                        $total_pagos += $value['monto'];
                        $tr_pagos .= '
                        <tr>
                            <td>'.$value['concepto'].'</td>
                            <td>'.$value['referencia'].'</td>
                            <td>'.$value['descripcion'].'</td>
                            <td class="text-right text-orange font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                            <td>'.$usuario['name'].'</td>
                            <td>'.$value['hora'].'</td>
                        </tr>';
                    }
                    $tr_pagos .= '
                    <tr>
                        <th colspan="4" class="text-right"><span class="text-orange">$ '.number_format($total_pagos,2).'</span></th>
                        <th colspan="2"></th>
                    </tr>';
                }else{
                    $tr_pagos .= '
                    <tr>
                        <td colspan="6" class="text-center">No hay registro de pagos.</td>
                    </tr>';
                }
                
                /* COBROS TARJETA -----------------------------------------------------------------------------------------------*/
                $tarjeta = $this->db->select(
                    's.folio, s.usuarioVenta, sd.vendido * ( sd.precio - ( ( sd.precio * sd.descuento ) / 100 ) ) AS monto, s.horaVenta', 
                    'salida AS s', 
                    'INNER JOIN salida_detalle AS sd ON s.id_salida=sd.id_salida', 
                    "s.fechaVenta='$fecha' AND s.metodo_pago='tarjeta' AND s.estado=1");
                $total_tarjeta = 0;
                $tr_cobros_tarjeta = '';
                if($tarjeta){
                    
                    foreach ($tarjeta as $key => $value) {
                        $usuario = (new Model\Administradores)->administrador($value['usuarioVenta']);
                        $total_tarjeta += $value['monto'];
                        $tr_cobros_tarjeta .= '
                        <tr>
                            <td>Cobro</td>
                            <td>'.$value['folio'].'</td>
                            <td>Cobro por concepto de venta: '.$value['folio'].'</td>
                            <td class="text-right text-purple font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                            <td>'.$usuario['name'].'</td>
                            <td>'.$value['horaVenta'].'</td>
                        </tr>';
                    }
                    
                    $pago_mixto_tarjeta = $this->db->select(
                    's.folio, s.usuarioVenta, s.montoTarjeta AS monto, s.horaVenta', 
                    'salida AS s', 
                    null, 
                    "s.fechaVenta='$fecha' AND s.metodo_pago='mixto' AND s.estado=1");
                    if($pago_mixto_tarjeta){
                        foreach ($pago_mixto_tarjeta as $key => $value) {
                            $usuario = (new Model\Administradores)->administrador($value['usuarioVenta']);
                            $total_tarjeta += $value['monto'];
                            $tr_cobros_tarjeta .= '
                            <tr>
                                <td>Cobro</td>
                                <td>'.$value['folio'].'</td>
                                <td>Cobro (mixto tarjeta) por concepto de venta: '.$value['folio'].'</td>
                                <td class="text-right text-purple font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                                <td>'.$usuario['name'].'</td>
                                <td>'.$value['horaVenta'].'</td>
                            </tr>';
                        }
                    }
                    
                    $tr_cobros_tarjeta .= '
                    <tr>
                        <th colspan="4" class="text-right"><span class="text-purple">$ '.number_format($total_tarjeta,2).'</span></th>
                        <th colspan="2"></th>
                    </tr>';
                    
                }else{
                    $tr_cobros_tarjeta .= '
                    <tr>
                        <td colspan="6" class="text-center">No hay registro de cobros con tarjeta.</td>
                    </tr>';
                }
                
                /* COBROS STRIPE -----------------------------------------------------------------------------------------------*/
                $stripe = $this->db->select('cs.*, cl.cliente', 'compras_stripe AS cs', 'INNER JOIN clientes AS cl ON cs.id_cliente=cl.id_cliente', 
                    "cs.fecha='$fecha' AND (cs.estatus!='refunded' AND cs.estatus!='requires_action' AND cs.estatus!='requires_payment_method')");
                $total_stripe = 0;
                $tr_cobros_stripe = '';
                if($stripe){
                    
                    foreach ($stripe as $key => $value) {
                        $total_stripe += $value['monto_compra'];
                        $tr_cobros_stripe .= '
                        <tr>
                            <td>Cobro</td>
                            <td>'.$value['id_stripe'].'</td>
                            <td>Venta en línea: '.$value['folio'].'</td>
                            <td class="text-right text-purple font-weight-bold">$ '.number_format($value['monto_compra'],2).'</td>
                            <td>'.$value['cliente'].'</td>
                            <td>'.$value['hora'].'</td>
                        </tr>';
                    }
                    
                }else{
                    $tr_cobros_stripe .= '
                    <tr>
                        <td colspan="6" class="text-center">No hay registro de ventas en línea.</td>
                    </tr>';
                }
                
                
                
                
                /*------------------------------------------------------------------------------------------------------*/
                $saldo = ($total_ingresos + $total_cobros + $total_abonos) - ($total_egresos + $total_pagos);
                /*$output[] = [
                    'movimiento' => 'Monto inicial',
                    'total' => $monto
                ];*/
                $output[] = [
                    'movimiento' => 'Ingresos',
                    'total' => $total_ingresos
                ];
                $output[] = [
                    'movimiento' => 'Cobros',
                    'total' => $total_cobros
                ];
                $output[] = [
                    'movimiento' => 'Abonos',
                    'total' => $total_abonos
                ];
                $output[] = [
                    'movimiento' => 'Egresos',
                    'total' => $total_egresos
                ];
                $output[] = [
                    'movimiento' => 'Pagos',
                    'total' => $total_pagos
                ];
                $output[] = [
                    'movimiento' => 'Cobros con tarjeta',
                    'total' => $total_tarjeta
                ];
                $output[] = [
                    'movimiento' => 'Cobros con stripe',
                    'total' => $total_stripe
                ];
                

                return array('status' => 'success', 
                            'monto' => '$ '.number_format($monto,2), 
                            'total_ingresos' => '$ '.number_format($total_ingresos,2),
                            'tr_ingresos' => $tr_ingresos,
                            'total_cobros' => '$ '.number_format($total_cobros,2),
                            'tr_cobros' => $tr_cobros,
                            'total_abonos' => '$ '.number_format($total_abonos,2),
                            'tr_abonos' => $tr_abonos,
                            'total_egresos' => '$ '.number_format($total_egresos,2),
                            'tr_egresos' => $tr_egresos,
                            'total_pagos' => '$ '.number_format($total_pagos,2),
                            'tr_pagos' => $tr_pagos,
                            'saldo' => '$ '.number_format($saldo,2),
                            'tr_cobros_tarjeta' => $tr_cobros_tarjeta,
                            'total_tarjeta' => '$ '.number_format($total_tarjeta,2),
                            'tr_cobros_stripe' => $tr_cobros_stripe,
                            'total_stripe' => '$ '.number_format($total_stripe,2),
                            'estado' => $estado,
                            'grafico' => $output,
                            'fechaActual' => date('Y-m-d')                                                              // fechaActual : para validar si se mostrará o no el botón de reapertura de caja (solo si la fecha seleccionada es igual a la fecha de hoy)
                        );
            }else{                                                                                                      // Si NO hay datos registrados en caja con la fecha y alamcén
                $msj_error = "No hubierón registros en caja para la fecha: $fecha.";                                                                                                    
                $error = 2;                                                                                             // Error se define como 2 por defecto sin considerar algo especifico
                if($fecha == date('Y-m-d')){
                    $error = 3;                                                                                         // En este caso error cambia el valor a 3 para validar si se mostrará o no el botón de reapertura de caja (solo si la fecha seleccionada es igual a la fecha de hoy)
                    $msj_error = 'Por favor registrar el total de monto inicial de hoy.';
                }
                throw new ModelsException($msj_error);
            }
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage(), 'error' => $error);
        }
    }

    public function obtenerFormularioCaja() {

        $u = new Model\Users;
        $userProfile = $u->getOwnerUser();

        $formulario = '
        <div class="row">
            <div class="col-xs-12">
                <p>
                    <small>Total de monto inicial:</small>
                </p>                             
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                              <i class="fas fa-dollar-sign"></i>
                        </span>
                        <input type="text" class="form-control text-right" name="monto_inicial" id="input_monto_inicial" placeholder="0.00" value="3000" readonly>
                    </div>
                </div>
            </div>
        </div>';

        return array('formulario' => $formulario);
    }
    
    public function registrarMonto(){
        
        if($this->id_user !== NULL){
            
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
    	
        	$cajaActual = $this->cargarCaja('id_almacen', $almacen['id_almacen']);
    
            if($cajaActual == false){
                
                $fecha = date('Y-m-d');
        	    $hora = date('H:i:s');
                
                $cajaAnterior = $this->db->select("id_caja, estado", "caja", null, "estado = 0 AND fecha_apertura != '$fecha'", 1);
                if($cajaAnterior){
                    $cajaAnterior = $cajaAnterior[0];
                    $id_caja = $cajaAnterior['id_caja'];
                	if($cajaAnterior['estado'] == 0){
                        $this->db->update('caja', array(
                            'hora_cierre' => $hora,
                            'usuario_cierre' => $this->id_user,
                            'estado' => 1
                        ), "id_caja = '$id_caja'", 1);
                	}   
                }
            
            	$this->db->insert('caja', array(
                    'monto' => 3000,
                    'id_almacen' => $almacen['id_almacen'],
                    'fecha_apertura' => $fecha,
                    'hora_apertura' => $hora,
                    'usuario_apertura' => $this->id_user,
                    'estado' => 0
               ));   
            }
        
        }
       
    }

    public function registrarMontoInicial() {
        try {
            global $http;

            $monto_inicial = (real) $http->request->get('monto_inicial');
            if($monto_inicial == 0){
                throw new ModelsException('Especificar el total de monto inicial.');
            }
            if($monto_inicial > 3000){
                $monto_inicial = 3000;
            }

            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
            if(!$almacen){
                throw new ModelsException('No fue posible registrar monto inicial.');
            }

            $caja = $this->cargarCaja('id_almacen', $almacen['id_almacen']);
            if($caja != false){
                throw new ModelsException('Ya ha sido registrado un monto incial.');
            }
            
            $cajaAnterior = $this->db->select("MAX(id_caja) AS id_caja, estado", "caja");
            if($cajaAnterior){
                $cajaAnterior = $cajaAnterior[0];
            	if($cajaAnterior['estado'] == 0){
                    $this->db->update('caja', array(
                        'hora_cierre' => $hora,
                        'usuario_cierre' => $this->id_user,
                        'estado' => 1
                    ), "id_caja = '{$cajaAnterior['id_caja']}'", 1);
            	}   
            }

            $this->db->insert('caja', array(
                'monto' => $monto_inicial,
                'id_almacen' => $almacen['id_almacen'],
                'fecha_apertura' => $fecha,
                'hora_apertura' => $hora,
                'usuario_apertura' => $this->id_user,
                'estado' => 0
            ));

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Se ha registrado correctamente el monto incial.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    public function cerrarCaja() {
        try {
            global $http;
            $password = $this->db->scape($http->request->get('pass'));

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $fecha = date('Y-m-d');
            $almacen = (new Model\Almacenes)->almacenPrincipal($userProfile['id_user']);
            $id_almacen = $almacen['id_almacen'];

            $this->db->update('caja', array(
                'hora_cierre' => date('H:i:s'),
                'usuario_cierre' => $this->id_user,
                'estado' => 1
            ), "fecha_apertura='$fecha' AND id_almacen='$id_almacen'", 1);

            /* AGREGAR ENVIO DE CORREO ELECTRONICO Y NOTIFICACION EN PANEL*/

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La caja ha sido cerrada correctamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    public function obtenerFormularioMovimientos() {
        try {
            global $http;
            $tipo_movimiento = $http->request->get('tipo');

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            $formulario = '
            <div class="row">
                <input type="hidden" name="tipo_movimiento" value="'.$tipo_movimiento.'">
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-address-card"></i>
                            </span>
                            <input type="text" class="form-control" value="'.$userProfile["name"].'" disabled>
                        </div>  
                    </div>                                          
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa-user-lock"></i>
                            </span>
                            <input type="password" class="form-control" name="passwordProfile" placeholder="Contraseña requerida" required>
                        </div>  
                    </div>                                          
                </div>
                <div class="col-xs-12">  
                    <hr>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-8">
                    <p>
                        <small>Por concepto de:</small>
                    </p> 
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa-pen-alt"></i>
                            </span>
                            <input type="text" class="form-control" name="concepto" id="concepto" placeholder="Concepto del '.$tipo_movimiento.'">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <p>
                        <small>Total del '.$tipo_movimiento.':</small>
                    </p>                             
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <input type="text" class="form-control text-right" name="monto_movimiento" id="input_monto_movimiento" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-5">
                    <p>
                        <small>Referencia:</small>
                    </p>                             
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            <input type="text" class="form-control" name="referencia" id="referencia" placeholder="Referencia">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-7">
                    <p>
                        <small>Descripción:</small>
                    </p>                             
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa-pen-alt"></i>
                            </span>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción del '.$tipo_movimiento.'">
                        </div>
                    </div>
                </div>
            </div>';

            return array('formulario' => $formulario);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    public function registrarMovimiento() {
        try {
            global $http, $config;
            $password = $this->db->scape($http->request->get('passwordProfile'));
            $tipo_movimiento = $this->db->scape($http->request->get('tipo_movimiento'));
            $concepto = $this->db->scape($http->request->get('concepto'));
            $monto_movimiento = (real) $http->request->get('monto_movimiento');
            $referencia = $this->db->scape($http->request->get('referencia'));
            $descripcion = $this->db->scape($http->request->get('descripcion'));

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $fecha = date('Y-m-d');
            $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);

            $caja = $this->cargarCaja('id_almacen', $almacen['id_almacen']);


            if(!$caja){
                throw new ModelsException('Para poder realizar movimientos por favor especificar primero el total de monto inicial.');
            }else{
                if($caja['estado'] == 1){
                    throw new ModelsException('La caja ya ha sido cerrada, no es posible registrar más movimientos.');
                }
            }
            $id_caja = $caja['id_caja'];

            if($concepto == ''){
                throw new ModelsException('Es necesario especificar el concepto del movimiento.');
            }

            if($monto_movimiento == 0){
                throw new ModelsException('Es necesario especificar el monto total del '.$tipo_movimiento.'.');
            }
            
            $arrayTiposMovimiento = ['egreso', 'ingreso', 'pago'];
            if(!in_array($tipo_movimiento, $arrayTiposMovimiento)){
                throw new ModelsException('Selecciona un movimiento de caja válido.');
            }

            if($descripcion == ''){
                throw new ModelsException('Es necesario especificar la descripción del movimiento.');
            }

            $hora = date('H:i:s');

            if($tipo_movimiento == 'egreso' || $tipo_movimiento == 'pago'){
                
                $saldo = $this->obtenerSaldo($caja['id_caja']);
                
                if($saldo < $monto_movimiento){
                    throw new ModelsException('No es posible realizar el '.$tipo_movimiento.', el monto supera el saldo en caja.');
                }
            }

            $this->db->insert('caja_movimientos', array(
                'id_caja' => $id_caja,
                'tipo' => ucfirst($tipo_movimiento),
                'concepto' => $concepto,
                'referencia' => $referencia,
                'descripcion' => $descripcion,
                'monto' => $monto_movimiento,
                'usuario' => $this->id_user,
                'hora' => $hora,
            ));

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Se ha registrado correctamente el '.$tipo_movimiento.' por '.number_format($monto_movimiento,2).'.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    public function descargarReporteCaja($fecha) {

        $fecha = $this->db->scape($fecha);

        $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);

        $caja = $this->cargarCajaPor('fecha_apertura', $fecha, 'id_almacen', $almacen['id_almacen']);

        if($caja){

            $nombre = "Reporte $fecha.xls"; 
            header('Expires: 0');
            header('Cache-control: private');
            header('Content-type: application/vnd.ms-excel'); //Archivo excelS
            header('Cache-Control: cache, must-revalidate');
            header('Content-Description: File Transfer');
            header('Last-Modified: '.date('D, d M Y H:i:s'));
            header('Pragma: public');
            header('Content-Disposition: attachment; filename="'.$nombre.'"');
            header('Content-Transfer-Encoding: binary');

            $movimientos_caja = $this->db->select('*', 'caja_movimientos', null, "id_caja = '".$caja['id_caja']."'", null, 'ORDER BY id_cm DESC'); 

            $usuario = (new Model\Administradores)->administrador($caja['usuario_apertura']);

            $html = "
                <table border='0'>
                    <tr>
                        <th colspan='7' style='text-align:center; background:#cfcfcf;'>REPORTE (".date_format(date_create($fecha), 'd-m-Y').")</th>
                    </tr>
                    <tr>
                        <th colspan='7' style='text-align:center; background:#cfcfcf;'>MOVIMIENTOS EN CAJA</th>
                    </tr>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>TIPO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>CONCEPTO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>REFERENCIA</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>DESCRIPCION</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>MONTO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>USUARIO</td>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>HORA</td>
                    </tr>";

            foreach ($movimientos_caja as $key => $value) {

                $usuario = (new Model\Administradores)->administrador($value['usuario']);

                if($value['referencia'] == ''){
                    $referencia = '--';
                }else{
                    $referencia = $value['referencia'];
                }

                $html .= "<tr>";

                $html .= "<td>{$value['tipo']}</td>";
                $html .= "<td>{$value['concepto']}</td>";
                $html .= "<td style='text-align:center;'>$referencia</td>";
                $html .= "<td>{$value['descripcion']}</td>";
                $html .= "<td style='text-align:right;'>$ ".number_format($value['monto'],2)."</td>";
                $html .= "<td>{$usuario['name']}</td>";
                $html .= "<td>{$value['hora']}</td>";

                $html .= "</tr>";
            

            }
            
            $tarjeta = $this->db->select(
                's.folio, s.usuarioVenta, sd.vendido * ( sd.precio - ( ( sd.precio * sd.descuento ) / 100 ) ) AS monto, s.horaVenta', 
                'salida AS s', 
                'INNER JOIN salida_detalle AS sd ON s.id_salida=sd.id_salida', 
                "s.fechaVenta='$fecha' AND s.metodo_pago='tarjeta' AND s.estado=1");
            $total_tarjeta = 0;

            if($tarjeta){
                
                $html .= "
                <tr>
                        <th colspan='7' style='text-align:center; background:#cfcfcf;'>PAGOS CON TARJETA</th>
                    </tr>
                <tr>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>TIPO</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>CONCEPTO</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>REFERENCIA</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>DESCRIPCION</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:right;'>MONTO</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>USUARIO</td>
                    <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777; text-align:center;'>HORA</td>
                </tr>";
                foreach ($tarjeta as $key => $value) {
                    $usuario = (new Model\Administradores)->administrador($value['usuarioVenta']);
                    $html .= '
                    <tr>
                        <td>Cobro</td>
                        <td>Venta</td>
                        <td>'.$value['folio'].'</td>
                        <td>Cobro por concepto de venta: '.$value['folio'].'</td>
                        <td class="text-right text-purple font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                        <td>'.$usuario['name'].'</td>
                        <td>'.$value['horaVenta'].'</td>
                    </tr>';
                }
                
                $pago_mixto_tarjeta = $this->db->select(
                's.folio, s.usuarioVenta, s.montoTarjeta AS monto, s.horaVenta', 
                'salida AS s', 
                null, 
                "s.fechaVenta='$fecha' AND s.metodo_pago='mixto' AND s.estado=1");
                if($pago_mixto_tarjeta){
                    foreach ($pago_mixto_tarjeta as $key => $value) {
                        $usuario = (new Model\Administradores)->administrador($value['usuarioVenta']);
                        $html .= '
                        <tr>
                            <td>Cobro</td>
                            <td>Venta</td>
                            <td>'.$value['folio'].'</td>
                            <td>Cobro por concepto de venta: '.$value['folio'].'</td>
                            <td class="text-right text-purple font-weight-bold">$ '.number_format($value['monto'],2).'</td>
                            <td>'.$usuario['name'].'</td>
                            <td>'.$value['horaVenta'].'</td>
                        </tr>';
                    }
                }
                
            }else{
                
            }

            $html .= "</table>";
            
            echo "\xEF\xBB\xBF"; //UTF-8 BOM
            echo $html;
        }else{
            global $config;
            # REDIRECCIONAR
            Helper\Functions::redir($config['build']['url'].'caja');
        }

    }

    public function reaperturarCaja(){

        try {

            global $http;
            $password = $this->db->scape($http->request->get('pass'));
            $fechaRecibida = $this->db->scape($http->request->get('fecha'));

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $fecha = date('Y-m-d');
            $almacen = (new Model\Almacenes)->almacenPrincipal($userProfile['id_user']);
            $id_almacen = $almacen['id_almacen'];

            if($fecha != $fechaRecibida){
                throw new ModelsException('Imposible abrir la caja de un día diferente al actual.');
            }

            $this->db->update('caja', array(
                'hora_reapertura' => date('H:i:s'),
                'usuario_reapertura' => $this->id_user,
                'estado' => 0
            ), "fecha_apertura='$fecha' AND id_almacen='$id_almacen'", 1);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La caja se abrio nuevamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }

    }

    public function montoTotalCaja($fecha){

        $almacen = (new Model\Almacenes)->almacenPrincipal($this->id_user);
        $id_almacen = $almacen['id_almacen'];

        $caja = $this->db->select('id_caja', 'caja', null, "fecha_apertura='$fecha' AND id_almacen='$id_almacen'");

        if($caja){

            $id_caja = $caja[0]['id_caja'];
            $saldo = $this->obtenerSaldo($id_caja);

            return number_format($saldo, 2);

        }else{
            return "0.00";
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