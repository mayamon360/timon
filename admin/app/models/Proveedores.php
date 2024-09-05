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
 * Modelo Proveedores
 */
class Proveedores extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene todos los proveedores
     * 
     * @return false|array
    */ 
    public function proveedores() {
        $proveedores = $this->db->select('*', 'proveedores', null, null, null, "ORDER BY id_proveedor DESC");
        return $proveedores;
    }

    /**
     * Obtiene datos de una categoría según su id en la base de datos, haciendo INNER JOIN a cabeceras
     * 
     * @param int $id: Id de la categoría a obtener
     *
     * @return false|array[0]
    */ 
    public function proveedor($id) {
        $proveedor = $this->db->select("*", 'proveedores', null, "id_proveedor='$id'", 1);
        if($proveedor){
            return $proveedor[0];
        }else{
            return false;
        }
    }

    /**
     * Retorna los datos de los proveedores para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarProveedores() : array {
        $proveedores = $this->proveedores();
        $data = [];
        if($proveedores){
            foreach ($proveedores as $key => $value) {

                if($value["id_proveedor"] == 1 || $value["id_proveedor"] == 2){

                }else{

                    $infoData = [];

                    $infoData[] = '<span class="badge bg-black" style="font-weight:500;">'.$value["id_proveedor"].'</span>';
                    
                    if($value["RFC"] != ''){
                        $infoData[] = $value["RFC"];
                    }else{
                        $infoData[] = '--';
                    }

                    $infoData[] = '<b>'.$value["proveedor"].'</b>';
                    
                    if($value["estado"] == 1){
                        $infoData[] = $estado = '<span class="estado'.$value["id_proveedor"].'"><i class="fas fa-toggle-on fa-lg text-aqua estado" value="0" key="'.$value["id_proveedor"].'" style="cursor:pointer;"></i></span>';
                    }else{
                        $infoData[] = $estado = '<span class="estado'.$value["id_proveedor"].'"><i class="fas fa-toggle-off fa-lg text-muted estado" value="1" key="'.$value["id_proveedor"].'" style="cursor:pointer;"></i></span>';
                    }

                    if($value["correo_electronico"] != ''){
                        $infoData[] = $value["correo_electronico"];
                    }else{
                        $infoData[] = '--';
                    }

                    if($value["telefono"] != ''){
                        $infoData[] = $value["telefono"];
                    }else{
                        $infoData[] = '--';
                    }

                    if($value["direccion"] != ''){
                        $infoData[] = $value["direccion"];
                    }else{
                        $infoData[] = '--';
                    }
                    
                    if($value['compras'] > 0){
                        $btn_eliminar = '';
                    }else{
                        $btn_eliminar = "<button class='btn btn-sm btn-default eliminar' key='".$value["id_proveedor"]."'><i class='fas fa-trash-alt'></i></button>";
                    }

                    $infoData[] = "<div class='btn-group'>
                                        <button class='btn btn-sm btn-default obtenerProveedor' key='".$value["id_proveedor"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt'></i></button>
                                        $btn_eliminar
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
     * Cambia el estado de un proveedor
     *
     * 0:desactivado | 1:activado 
     *
     * @return array
    */ 
    public function estadoProveedor() : array {
        try {
            global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $proveedor = $this->proveedor($id);

            if(!$proveedor){
                throw new ModelsException('El proveedor no existe.');
            }else{
                if($id == 1 || $id == 2){
                    throw new ModelsException('No se puede editar el estado de esté registro');
                }
                $this->db->update('proveedores',array('estado' => $estado),"id_proveedor='$id'");
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


            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado de proveedor '.$id.' '.$proveedor['proveedor'].' a '.$estado_txt, $perfil, $administrador['id_user'], 15, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Valida si el nombre de un proveedor ya existe
     *
     * Si viene id excluye ese valor de la consulta para evitar que muestre el mensaje esto dado a que se trata de una edición  
     * 
     * @return array
    */ 
    public function validarProveedor() : array {
        try {
            global $http;
            
            $item = $this->db->scape($http->request->get('item'));

            $valor = $this->db->scape($http->request->get('valor'));

            $id = intval($http->request->get('id'));

            if($item != "" && $valor != ''){

                if($item == 'erfc'){
                    $item = 'rfc';
                } elseif ($item == 'eproveedor') {
                    $item = 'proveedor';
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
                    $proveedores = $this->db->select('*', 'proveedores', null, "$item = '$valor' AND id_proveedor != '$id'");
                }else{
                    $proveedores = $this->db->select('*', 'proveedores', null, "$item = '$valor'");
                }

                if($proveedores){

                    $texto = '';

                    switch ($item) {
                        case 'rfc':
                        case 'erfc':
                            $texto .= "con RFC ".$valor;
                            break;

                        case 'proveedor':
                        case 'eproveedor':
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

                    throw new ModelsException('El proveedor '.$texto.' ya existe en la base de datos.');
                }

            }

            return array('status' => null);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }


    /**
     * Agrega un nuevo proveedor 
     * 
     * @return array
    */ 
    public function agregarProveedor() : array {
        try {
            global $http;
            # Obtener los datos $_POST
            $metodo = $http->request->get('metodo');

            $rfc = $this->db->scape(trim($http->request->get('rfc')));

            $proveedor = $this->db->scape(trim($http->request->get('proveedor')));
            $proveedor = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s])', '', $proveedor);

            $correo = $this->db->scape($http->request->get('correoElectronico'));

            $telefono = $this->db->scape($http->request->get('telefono'));

            $direccion = $this->db->scape($http->request->get('direccion'));

            if($metodo != 'agregar' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }

            if($proveedor == ""){
                throw new ModelsException('El campo Proveedor está vació.');
            }

            if($correo != ""){
                if(!Helper\Strings::is_email($correo)){
                    throw new ModelsException('El correo electrónico no es válido.');
                }
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

            # Registrar proveedor
            $insertar = $this->db->insert('proveedores', array(
                'rfc' => mb_strtoupper($rfc, 'UTF-8'),
                'proveedor' => ucwords(mb_strtolower($proveedor, 'UTF-8')),
                'correo_electronico' => $correo,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'fechaRegistro' => date('Y-m-d H:i:s'),
                'usuarioRegistro' => $administrador['id_user'],
                'estado' => 1
            ));

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de proveedor '.$insertar.' '.$proveedor, $perfil, $administrador['id_user'], 15, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El proveedor '.ucwords(mb_strtolower($proveedor, 'UTF-8')).' se agregó correctamente.', 'agregado' => $insertar);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de un proveedor para cargarlo en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerProveedor() : array {
        try {
            global $http;

            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get('id'));

            if($metodo != 'obtenerProveedor' || $metodo == null){throw new ModelsException('El método no está definido.');}

            if($id != "" && $id != 0){
                $proveedor = $this->proveedor($id);
                if($proveedor){

                    if($id == 1 || $id == 2){
                        throw new ModelsException('No se puede editar esté registro');
                    }

                    $formulario = "";
                    $formulario .= '<input type="hidden" name="metodo" value="editar">
                                    <input type="hidden" name="idP" id="idP" value="'.$proveedor["id_proveedor"].'">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                      <div class="input-group">
                                                            <span class="input-group-addon">
                                                                  <i class="fas fa-id-badge"></i>
                                                            </span>
                                                            <input type="text" class="form-control text-uppercase evalidar" tipo="erfc" id="erfc" name="rfc" placeholder="RFC" value="'.$proveedor['RFC'].'" key="'.$proveedor["id_proveedor"].'">
                                                      </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                      <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fas fa-user-alt"></i>
                                                            </span>
                                                            <input type="text" class="form-control evalidar" tipo="eproveedor" id="eproveedor" name="proveedor" placeholder="Proveedor" value="'.$proveedor['proveedor'].'" key="'.$proveedor["id_proveedor"].'">
                                                      </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                      <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fas fa-envelope"></i>
                                                            </span>
                                                            <input type="text" class="form-control evalidar" tipo="ecorreo_electronico" id="ecorreo_electronico" name="correoElectronico" placeholder="Correo electrónico" value="'.$proveedor['correo_electronico'].'" key="'.$proveedor["id_proveedor"].'">
                                                      </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                      <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fas fa-phone"></i>
                                                            </span>
                                                            <input type="text" class="form-control validar" tipo="etelefono" id="etelefono" name="telefono" placeholder="Teléfono" value="'.$proveedor['telefono'].'" key="'.$proveedor["id_proveedor"].'">
                                                      </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                <textarea class="form-control" maxlength="120" rows="5" name="direccion" style="resize: none;" placeholder="Dirección" required>'.$proveedor['direccion'].'</textarea>
                                            </div>
                                        </div>
                                    </div>';

                    return array('status' => 'success', 'formulario' => $formulario);
                }else{
                    throw new ModelsException('El proveedor no existe.');
                }
            }else{
                throw new ModelsException('La petición no se puede procesar (id nulo).');
            }    
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Agrega un nuevo proveedor 
     * 
     * @return array
    */ 
    public function editarProveedor() : array {
        try {
            global $http;
            # Obtener los datos $_POST
            $metodo = $http->request->get('metodo');

            $id = intval($http->request->get("idP"));

            $rfc = $this->db->scape(trim($http->request->get('rfc')));

            $proveedor = $this->db->scape(trim($http->request->get('proveedor')));

            $proveedor = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s])', '', $proveedor);

            $correo = $this->db->scape($http->request->get('correoElectronico'));

            $telefono = $this->db->scape($http->request->get('telefono'));

            $direccion = $this->db->scape($http->request->get('direccion'));

            $fechaNacimiento = $this->db->scape($http->request->get('fechaNacimiento'));

            $proveedorBD = $this->proveedor($id);

            if($metodo != 'editar' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }

            if($id == 1 || $id == 2){
                throw new ModelsException('No se puede editar esté registro');
            }

            if(!$proveedorBD){throw new ModelsException("El proveedor no existe");}

            if($proveedor == ""){
                throw new ModelsException('El campo proveedor está vació.');
            }

            if($correo != ""){
                if(!Helper\Strings::is_email($correo)){
                    throw new ModelsException('El correo electrónico no es válido.');
                }
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

            # Editar cliente
            $this->db->update('proveedores', array(
                'rfc' => mb_strtoupper($rfc, 'UTF-8'),
                'proveedor' => ucwords(mb_strtolower($proveedor, 'UTF-8')),
                'correo_electronico' => $correo,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'usuarioModificacion' => $administrador['id_user']
            ), "id_proveedor='$id'");
            

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de proveedor '.$id.' '.$proveedor, $perfil, $administrador['id_user'], 15, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El proveedor '.ucwords(mb_strtolower($proveedor, 'UTF-8')).' se editó correctamente.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Elimina un proveedor 
     * 
     * @return array
    */ 
    public function eliminarProveedor() : array {
        try {
            global $http;
            $id = intval($http->request->get('id'));
            // Traer dato del proveedor por id
            $proveedor = $this->proveedor($id);

            if(!$proveedor){
                throw new ModelsException('El proveedor no existe.');
            }else{

                if($id == 1 || $id == 2){
                    throw new ModelsException('No se puede eliminar esté registro');
                }
                
                if($proveedor['compras'] > 0){
                    throw new ModelsException('El proveedor no puede ser elimado, hay registros asociados a el.');
                }

                $eliminarProveedor = $this->db->delete('proveedores', "id_proveedor='$id'", 1);

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

                (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de proveedor '.$id, $perfil, $administrador['id_user'], 15, date('Y-m-d H:i:s'), 0);

                return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'El proveedor ha sido eliminado.');
            }
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage(), 'id' => $id);
        }
    }

    /**
     * Obtiene los datos de los proveedores para cargarlos en un select
     * 
     * @return array
    */ 
    public function obtenerProveedores() : array {
        try {
            global $http;
            # Obtener los datos $_POST
            $metodo = $http->request->get('metodo');
            $selected = intval($http->request->get('seleccionado'));

            if($metodo != 'cargar proveedores' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }

            $proveedores = $this->proveedores();

            $options = '';
            $options .= '<option></option><optgroup label="Acciones disponibles"><option value="1">AJUSTE DE INVENTARIO</option><option value="2">MOVIMIENTO DE BODEGA</option></optgroup><optgroup label="Proveedores disponibles">';

            if($proveedores){

                foreach ($proveedores as $key => $value) {
                    if($value['id_proveedor'] != 1 && $value['id_proveedor'] != 2){
                        if($value['estado'] == 1){
                            if($selected == $value['id_proveedor']){
                                $options .= '<option value="'.$value['id_proveedor'].'" selected>'.$value['proveedor'].'</option>';
                            }else{
                                $options .= '<option value="'.$value['id_proveedor'].'">'.$value['proveedor'].'</option>';
                            }
                        }
                    }
                }
                $options .= "</optgroup>";   
            }

            return array('status' => 'success', 'proveedores' => $options);


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