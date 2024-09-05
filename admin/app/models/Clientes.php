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
 * Modelo Clientes
 */
class Clientes extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene todos los clientes
     * 
     * @return false|array
    */ 
    public function clientes() {
        $clientes = $this->db->select('*', 'clientes', null, null, null, "ORDER BY id_cliente DESC");
        return $clientes;
    }

    /**
     * Obtiene todos los clientes lealtad
     * 
     * @return false|array
    */ 
    public function clientesLealtad() {
        $clientes = $this->db->select('*', 'clientes', null, "tipo = 1 || tipo = 0", null, "ORDER BY id_cliente DESC"); # tipo 0 = Venta al publico en general, tipo 2 cliente lealtad
        return $clientes;
    }

    /**
     * Obtiene datos de una categoría según su id en la base de datos
     * 
     * @param int $id: Id del cliente a obtener
     *
     * @return false|array[0]
    */ 
    public function cliente($id) {
        $cliente = $this->db->select("*", 'clientes', null, "id_cliente='$id'", 1);
        if($cliente){
            return $cliente[0];
        }else{
            return false;
        }
    }

    /**
     * Retorna los datos de los clientes para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarClientes() : array {
        $clientes = $this->clientes();
        $administrador = (new Model\Users)->getOwnerUser();
        $data = [];
        if($clientes){
            foreach ($clientes as $key => $value) {
                if($value["id_cliente"] != 1 && $value["tipo"] == 2){
                    $infoData = [];

                    $infoData[] = '<span class="badge bg-black" style="font-weight:500;">'.$value["id_cliente"].'</span>';
                    
                    $infoData[] = '<b>'.$value["cliente"].'</b>';

                    if($value["RFC"] != ''){
                        $infoData[] = $value["RFC"];
                    }else{
                        $infoData[] = '--';
                    }

                    if($value["correoElectronico"] != ''){
                        $infoData[] = $value["correoElectronico"];
                    }else{
                        $infoData[] = '--';
                    }

                    if($value["telefono"] != ''){
                        $infoData[] = $value["telefono"];
                    }else{
                        $infoData[] = '--';
                    }

                    $infoData[] = $value["compras"];

                    if($value["fechaUltimaCompra"] !== NULL && $value["fechaUltimaCompra"] != ''){

                        $fechaUC = Helper\Functions::fecha($value["fechaUltimaCompra"], false);
                        $infoData[] = $fechaUC;

                    }else{
                        $infoData[] = '--';
                    }

                    if($value["estado"] == 1){
                        $infoData[] = $estado = '<span class="estado'.$value["id_cliente"].'"><i class="fas fa-toggle-on fa-lg text-aqua estado" value="0" key="'.$value["id_cliente"].'" style="cursor:pointer; vertical-align:middle; margin-right:10px;"></i></span> <span class="badge bg-aqua" style="font-weight:800;">Activo<span>';
                    }else{
                        $infoData[] = $estado = '<span class="estado'.$value["id_cliente"].'"><i class="fas fa-toggle-off fa-lg text-muted estado" value="1" key="'.$value["id_cliente"].'" style="cursor:pointer; vertical-align:middle; margin-right:10px;"></i></span> <span class="badge bg-grey" style="font-weight:800;">Inactivo<span>';
                    }
                    
                    if($value["compras"] > 0){
                        $eliminar = '';
                    }else{
                        $eliminar = "<button class='btn btn-sm btn-default eliminar' key='".$value["id_cliente"]."'><i class='fas fa-trash-alt' data-toggle='tooltip' title='Eliminar'></i></button>";
                    }
                    
                    $infoData[] = "<div class='btn-group'>
                                        <button class='btn btn-sm btn-default obtenerCliente' key='".$value["id_cliente"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt' data-toggle='tooltip' title='Editar'></i></button>
                                        $eliminar
                                    </div>";
                    $data[] = $infoData; 
                }
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    /**
     * Cambia el estado de un cliente
     *
     * 0:desactivado | 1:activado 
     *
     * @return array
    */ 
    public function estadoCliente() : array {
        try {
            global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $cliente = $this->cliente($id);

            if(!$cliente){
                throw new ModelsException('El cliente no existe.');
            }else{
                $this->db->update('clientes',array('estado' => $estado),"id_cliente='$id'");
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

            if($estado == 1){
                $estado_txt = 'activo';
            }else{
                $estado_txt = 'inactivo';
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado de cliente '.$id.' '.$cliente['cliente'].' a '.$estado_txt, $perfil, $administrador['id_user'], 14, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Valida si el nombre de un cliente ya existe
     *
     * Si viene id excluye ese valor de la consulta para evitar que muestre el mensaje esto dado a que se trata de una edición  
     * 
     * @return array
    */ 
    public function validarCliente() : array {
        try {
            global $http;
            
            $item = $this->db->scape($http->request->get('item'));

            $valor = $this->db->scape($http->request->get('valor'));

            $id = intval($http->request->get('id'));

            if($item != "" && $valor != ''){

                if($item == 'erfc'){
                    $item = 'rfc';
                } elseif ($item == 'ecliente') {
                    $item = 'cliente';
                } elseif ($item == 'ecorreo_electronico') {
                    $item = 'correo_electronico';
                } elseif ($item == 'etelefono') {
                    $item = 'telefono';
                }

                if($item == 'correo_electronico'){
                    if(!Helper\Strings::is_email($valor)){
                        throw new ModelsException('El correo electrónico no es válido.');
                    }
                }

                if($id != "" && $id != 0){
                    $clientes = $this->db->select('*', 'clientes', null, "$item = '$valor' AND id_cliente != '$id'");
                }else{
                    $clientes = $this->db->select('*', 'clientes', null, "$item = '$valor'");
                }

                if($clientes){

                    $texto = '';

                    switch ($item) {
                        case 'rfc':
                        case 'erfc':
                            $texto .= "con RFC ".$valor;
                            break;

                        case 'cliente':
                        case 'ecliente':
                            $texto .= $valor;
                            break;

                        case 'correo_electronico':
                        case 'ecorreo_electronico':
                            $texto .= "con correo electrónico ".$valor;
                            break;
                        
                        case 'telefono':
                        case 'etelefono':
                            $texto .= "con teléfono ".$valor;
                            break;
                    }

                    throw new ModelsException('El cliente '.$texto.' ya existe en la base de datos.');
                }

            }

            return array('status' => null);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Agrega un nuevo cliente 
     * 
     * @return array
    */ 
    public function agregarCliente() : array {
        try {
            global $http;
            # Obtener los datos $_POST

            $tipo = 2;
            $rfc = $this->db->scape(trim($http->request->get('rfc')));
            $cliente = $this->db->scape(trim($http->request->get('cliente')));
            $cliente = Helper\Strings::clean_string($cliente);
            $correo = $this->db->scape($http->request->get('correoElectronico'));
            $telefono = $this->db->scape($http->request->get('telefono'));
            
            $descuento_gral = (real) $http->request->get('descuento');
            $devolucion_gral = (int) $http->request->get('devolucion');
            
            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador){
                $perfiles = $administrador['perfiles'];
                foreach ($perfiles as $key => $value) {
                    if($value['principal']){
                        $perfil = $value['id_perfil'];
                    }
                }
            }

            if($administrador['control'] == 0){ # - Si el usuario no tiene control (0) y el tipo de cliente a registrar es crédito (2)
                throw new ModelsException('No tienes los permisos necesarios para registrar clientes de crédito.');
            }

            if($cliente == ""){
                throw new ModelsException('Se debe especificar el nombre del cliente.');
            }

            if($correo == ""){
                throw new ModelsException('Se debe especificar el correo electrónico.');
            }
            
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new ModelsException('El correo electrónico no es válido.');
            }

            # Registrar cliente
            $insertar = $this->db->insert('clientes', array(
                'tipo' => $tipo,
                'rfc' => mb_strtoupper($rfc, 'UTF-8'),
                'cliente' => ucwords(mb_strtolower($cliente, 'UTF-8')),
                'correoElectronico' => $correo,
                'telefono' => $telefono,
                'fechaRegistro' => date('Y-m-d H:i:s'),
                'usuarioRegistro' => $administrador['id_user'],
                'estado' => 1
            ));
            
            $this->db->insert('clientes', array(
                'id_cliente' => $insertar,
                'descuento' => $descuento_gral,
                'devolucion' => $devolucion_gral
            ));

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de cliente '.$insertar.' '.ucwords(mb_strtolower($cliente, 'UTF-8')), $perfil, $administrador['id_user'], 14, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El cliente '.ucwords(mb_strtolower($cliente, 'UTF-8')).' se agregó correctamente.', 'agregado' => $insertar);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }


    /**
     * Obtiene los datos de un cliente para cargarlo en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerCliente() : array {
        try {
            global $http;

            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get('id'));

            if($metodo != 'obtenerCliente' || $metodo == null){throw new ModelsException('El método no está definido.');}

            if($id != "" && $id != 0){
                $cliente = $this->cliente($id);
                if($cliente && $cliente['tipo'] == 2){
                    
                    $clientes_porcentajes = $this->db->select('*','clientes_porcentajes',null,"id_cliente='$id'");
                    if($clientes_porcentajes){
                        $descuento = $clientes_porcentajes[0]['descuento'];
                        $devolucion = $clientes_porcentajes[0]['devolucion'];
                    }else{
                        $descuento = 0;
                        $devolucion = 0;
                    }
                    
                    $formulario = "";
                    $formulario .= '<input type="hidden" name="idC" id="idC" value="'.$cliente["id_cliente"].'">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-3">
                                                <div class="form-group" data-toggle="tooltip" title="Tipo de cliente">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-user-tag"></i>
                                                    </span>
                                                    <select class="form-control" name="tipoCliente" id="tipoCliente" style="width: 100%;" disabled>
                                                        <option value="2" selected>Crédito</option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-md-3">
                                            <div class="form-group">
                                                  <div class="input-group">
                                                        <span class="input-group-addon">
                                                              <i class="fas fa-id-badge"></i>
                                                        </span>
                                                        <input type="text" class="form-control text-uppercase evalidar" tipo="erfc" id="erfc" name="rfc" placeholder="RFC" value="'.$cliente['RFC'].'" key="'.$cliente["id_cliente"].'">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <div class="form-group">
                                                  <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-user-alt"></i>
                                                        </span>
                                                        <input type="text" class="form-control evalidar" tipo="ecliente" id="ecliente" name="cliente" placeholder="Cliente" value="'.$cliente['cliente'].'" key="'.$cliente["id_cliente"].'">
                                                  </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                  <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-envelope"></i>
                                                        </span>
                                                        <input type="text" class="form-control evalidar" tipo="ecorreo_electronico" id="ecorreo_electronico" name="correoElectronico" placeholder="Correo electrónico" value="'.$cliente['correoElectronico'].'" key="'.$cliente["id_cliente"].'">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                  <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-phone"></i>
                                                        </span>
                                                        <input type="text" class="form-control validar" tipo="etelefono" id="etelefono" name="telefono" placeholder="Teléfono" value="'.$cliente['telefono'].'" key="'.$cliente["id_cliente"].'">
                                                  </div>
                                            </div>
                                        </div>  
                                    </div>

                                    <div class="row">
                                    
                                        <div class="col-xs-12 col-md-6"></div>  
                                        <div class="col-xs-6 col-md-3">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control text-right descuento" id="edescuento" name="edescuento" placeholder="Descuento general" value="'.$descuento.'">
                                                    <span class="input-group-addon bg-gray" style="padding-left:0;padding-right:0; border-left:0;">
                                                        <i class="fas fa-percentage"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-3">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control text-right devolucion" id="edevolucion" name="edevolucion" placeholder="Devolución general" value="'.$devolucion.'">
                                                    <span class="input-group-addon bg-gray" style="padding-left:0;padding-right:0; border-left:0;">
                                                        <i class="fas fa-percentage"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>  
                                        
                                    </div>';

                    return array('status' => 'success', 'formulario' => $formulario);
                }else{
                    throw new ModelsException('El cliente no existe.');
                }
            }else{
                throw new ModelsException('La petición no se puede procesar (id nulo).');
            }    
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Agrega un nuevo cliente 
     * 
     * @return array
    */ 
    public function editarCliente() : array {
        try {
            global $http;
            # Obtener los datos $_POST
            $id = intval($http->request->get("idC"));

            $rfc = $this->db->scape(trim($http->request->get('rfc')));

            $cliente = $this->db->scape(trim($http->request->get('cliente')));
            $cliente = Helper\Strings::clean_string($cliente);

            $correo = $this->db->scape($http->request->get('correoElectronico'));

            $telefono = $this->db->scape($http->request->get('telefono'));
            
            $descuento_gral = (real) $http->request->get('descuento');
            $devolucion_gral = (int) $http->request->get('devolucion');

            $clienteBD = $this->cliente($id);

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

            if(!$clienteBD){throw new ModelsException("El cliente no existe");}
            
            $cliente_credito_activo = $this->db->select('*','credito',null,"id_cliente='$id' AND estado=1");
            
            if($cliente_credito_activo){                                        // Para evitar que cambie los porcentajes de descuentos y devoluciones (cliente credito)
                throw new ModelsException("El cliente no puede ser editado mientras tenga un credito abierto.");
            }

            if($cliente == ""){
                throw new ModelsException('El campo Cliente está vació.');
            }

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new ModelsException('El correo electrónico '.$correo.' no es válido.');
            }

            # Editar cliente
            $this->db->update('clientes', array(
                'rfc' => mb_strtoupper($rfc, 'UTF-8'),
                'cliente' => ucwords(mb_strtolower($cliente, 'UTF-8')),
                'correoElectronico' => $correo,
                'telefono' => $telefono,
                'fechaModificacion' => date('Y-m-d H:i:s'),
                'usuarioModificacion' => $administrador['id_user']
            ), "id_cliente='$id'");
            
            $clientes_porcentajes = $this->db->select('*','clientes_porcentajes',null,"id_cliente='$id'");
            if($clientes_porcentajes){
                $this->db->update('clientes_porcentajes', array(
                    'descuento' => $descuento_gral,
                    'devolucion' => $devolucion_gral
                ), "id_cliente='$id'");      
            }else{
                $this->db->insert('clientes_porcentajes', array(
                    'id_cliente' => $id,
                    'descuento' => $descuento_gral,
                    'devolucion' => $devolucion_gral
                ));
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de cliente '.$id.' '.ucwords(mb_strtolower($cliente, 'UTF-8')), $perfil, $administrador['id_user'], 14, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El cliente '.$id.' '.ucwords(mb_strtolower($cliente, 'UTF-8')).' se editó correctamente.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }


    /**
     * Elimina un cliente 
     * 
     * @return array
    */ 
    public function eliminarCliente() : array {
        try {
            
            global $http;
            $id = intval($http->request->get('id'));
            // Traer dato del cliente por id
            $cliente = $this->cliente($id);
            
            if(!$cliente){
                throw new ModelsException('El cliente no existe.');
            }else{
                
                $administrador = (new Model\Users)->getOwnerUser();
                if($administrador['control'] == 0 && $cliente["tipo"] == 2){ # - Si el usuario no tiene control (0) y el tipo de cliente a eliminar es crédito (2)
                    throw new ModelsException('No tienes los permisos necesarios para eliminar clientes.');
                }
                
                if($cliente["compras"] > 0){
                    throw new ModelsException('El cliente no puede ser eliminado.');    
                }
                
                $eliminarCliente = $this->db->delete('clientes', "id_cliente='$id'", 1);

                if($administrador){
                    $administrador = $administrador;
                    $perfiles = $administrador['perfiles'];
                    foreach ($perfiles as $key => $value) {
                        if($value['principal']){
                            $perfil = $value['id_perfil'];
                        }
                    }
                }

                (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de cliente '.$id, $perfil, $administrador['id_user'], 14, date('Y-m-d H:i:s'), 0);

                return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'El cliente ha sido eliminado.');
            }
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage(), 'id' => $id);
        }
    }

    /**
     * Obtiene los datos de los clientes para cargarlos en un select
     * 
     * @return array
    */ 
    public function obtenerClientes() : array {
        try {
            global $http;
            # Obtener los datos $_POST
            $metodo = $http->request->get('metodo');
            $selected = intval($http->request->get('seleccionado'));

            if($metodo != 'cargar clientes' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }

            $clientes = $this->clientesLealtad();

            $options = '';

            foreach ($clientes as $key => $value) {

                if($value['id_cliente'] == 1){
                    if($selected == $value['id_cliente']){
                        $options .= '<option value="'.$value['id_cliente'].'" selected>'.$value['cliente'].'</option>';
                    }else{
                        $options .= '<option value="'.$value['id_cliente'].'">'.$value['cliente'].'</option>';
                    }
                }
                
            }

            $options .= '<option></option><optgroup label="Código lealtad">';
            foreach ($clientes as $key => $value) {

                if($value['id_cliente'] != 1 && $value['estado'] == 1){
                    if($selected == $value['id_cliente']){
                        $options .= '<option value="'.$value['id_cliente'].'" selected>'.$value['codigoFidelidad'].' '.$value['puntos'].'</option>';
                    }else{
                        $options .= '<option value="'.$value['id_cliente'].'">'.$value['codigoFidelidad'].' '.$value['puntos'].'</option>';
                    }
                }
                
            }
            $options .= "</optgroup>";

            return array('status' => 'success', 'clientes' => $options);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage(), 'id' => $id);
        }

    }
    
    public function cargarEditorialesCliente() {
        try{
            global $http;
            $cliente = intval($http->request->get('cliente'));
            $editorial_seleccionada = intval($http->request->get('editorial'));
            
            $select_editoriales = '';
            $cliente_editorial = $this->db->select(
                "e.id_editorial, e.editorial", 
                "editoriales AS e", 
                "INNER JOIN clientes_editoriales AS ce ON e.id_Editorial=ce.id_Editorial",
                "id_cliente = '$cliente'",
                null,
                "ORDER BY e.editorial ASC"
            );
            
            if(!$cliente_editorial){
                return array('status' => 'error');
            }else{
                
                $select_editoriales .= '
                <select class="form-control seleccionar input-sm" name="seleccionar_editorial" id="seleccionar_editorial">';
                    
                if($editorial_seleccionada == 0){
                    $select_editoriales .= '
                    <option value="0" disabled selected>Editoriales asociadas</option>';
                }else{
                    $select_editoriales .= '
                    <option value="0" disabled>Editoriales asociadas</option>';
                }    
                    
                foreach($cliente_editorial as $key => $value) {
                    if($editorial_seleccionada == $value['id_editorial']){
                        $select_editoriales .= '
                        <option value="'.$value['id_editorial'].'" selected>'.$value['editorial'].'</option>';
                    }else{
                        $select_editoriales .= '
                        <option value="'.$value['id_editorial'].'">'.$value['editorial'].'</option>';   
                    }
                }
                $select_editoriales .= '
                </select>';
                
                return array('status' => 'success', 'editoriales' => $select_editoriales);
            }
            
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage(), 'id' => $id);
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