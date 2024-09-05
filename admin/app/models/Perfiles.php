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
 * Modelo Perfiles
 */
class Perfiles extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene todos los perfiles 
     * 
     * @return false|array
    */ 
    public function perfiles() {
        $perfiles = $this->db->select("*", "perfiles", null, null, null, "ORDER BY id_perfil DESC");
        return $perfiles;
    }

    /**
     * Obtiene datos de un perfil según su id en la base de datos, haciendo INNER JOIN a cabeceras
     * 
     * @param int $id: Id de la categoría a obtener
     *
     * @return false|array[0]
    */ 
    public function perfil($id) {
        $perfil = $this->db->select("*", 'perfiles', null, "id_perfil='$id'", 1);
        if($perfil){
            return $perfil[0];
        }
        return false;
    }

    /**
     * Obtiene los perfiles de un administrador, haciendo INNER JOIN a administradores_perfiles
     * 
     * @param int $id: Id del usuario
     *
     * @return false|array[0]
    */ 
    public function perfilesAdministrador($id) {
        $perfilesAdministrador = $this->db->select("p.id_perfil, p.perfil, ap.principal", 'perfiles AS p', 'INNER JOIN administradores_perfiles AS ap ON ap.id_perfil = p.id_perfil', "ap.id_user='$id' AND p.estado=1");
        if($perfilesAdministrador){
            return $perfilesAdministrador;
        }
        return false;
    }

    /**
     * Obtiene el prefil principal de un administrador, haciendo INNER JOIN a administradores_perfiles
     * 
     * @param int $id: Id del usuario
     *
     * @return false|array[0]
    */ 
    public function perfilPrincipal($id) {
        $perfilPrincipal = $this->db->select("p.id_perfil, p.perfil", 'perfiles AS p', 'INNER JOIN administradores_perfiles AS ap ON ap.id_perfil = p.id_perfil', "ap.id_user='$id' AND ap.principal = 1", 1);
        if($perfilPrincipal){
            return $perfilPrincipal[0];
        }
        return false;
    }

    /**
     * Determinar si un perfil está asignado a un administrador
     * 
     * @param int $id_user: Id del usuario
     * @param int $id_perfil: Id del perfil
     *
     * @return true|false
    */ 
    public function perfilAsignado($id_user, $id_perfil) {
        $perfilAsignado = $this->db->select("*", 'administradores_perfiles', null, "id_user='$id_user' AND id_perfil='$id_perfil'", 1);
        if($perfilAsignado){
            return true;
        }
        return false;
    }

    /**
     * Retorna los datos de los perfiles para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarPerfiles() : array {

        $perfiles = $this->perfiles();
        $data = [];
        if($perfiles){
            foreach ($perfiles as $key => $value) {
                $infoData = [];

                $infoData[] = '<span class="badge bg-black" style="font-weight:500;">'.$value["id_perfil"].'</span>';
                $infoData[] = '<span class="badge bg-purple" style="font-weight:500;">'.$value["perfil"].'</span>';

                if($value["estado"] == 1){
                    $infoData[] = $estado = '<span class="estado'.$value["id_perfil"].'" data-toggle="tooltip" title="Activo"><i class="fas fa-toggle-on fa-lg text-aqua estado" value="0" key="'.$value["id_perfil"].'" style="cursor:pointer;"></i></span>';
                }else{
                    $infoData[] = $estado = '<span class="estado'.$value["id_perfil"].'" data-toggle="tooltip" title="Inactivo"><i class="fas fa-toggle-off fa-lg text-muted estado" value="1" key="'.$value["id_perfil"].'" style="cursor:pointer;"></i></span>';
                }

                $modulos = (new Model\Menu)->modulos($value["id_perfil"]);
                if($modulos){
                    $modulosAsignados = '';
                    foreach ($modulos as $key2 => $value2) {

                        $modulosAsignados .= "<small><label class='badge bg-black' style='margin-top:-1px; padding-bottom:4px; margin-left:3px;'>{$value2['modulo']}</label></small> ";

                        $submodulos = (new Model\Menu)->submodulos((int) $value2['id_modulo'], (int) $value["id_perfil"]);

                        if($submodulos && count($submodulos) > 0){
                            foreach ($submodulos as $key3 => $value3) {
                                $modulosAsignados .= "<small><label class='badge bg-gray' style='margin-top:-1px; padding-bottom:4px; margin-left:3px;'>{$value3['modulo']}</label></small> ";
                            }
                        }
                    }
                    $infoData[] = $modulosAsignados;
                }else{
                    $infoData[] = '--';
                }

                $infoData[] = "<div class='btn-group'>
                                    <button class='btn btn-sm btn-primary obtenerPerfil' key='".$value["id_perfil"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt'></i></button>
                                    <button class='btn btn-sm btn-danger eliminar' key='".$value["id_perfil"]."'><i class='fas fa-trash-alt'></i></button>
                                </div>";

                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    /**
     * Cambia el estado de un perfil
     *
     * 0:desactivado | 1:activado 
     *
     * @return array
    */ 
    public function estadoPerfil() : array {
        try {
            global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));

            $perfil = $this->perfil($id);

            if(!$perfil){
                throw new ModelsException('El perfil no existe.');
            }else{
                $this->db->update('perfiles',array('estado' => $estado, 'fecha' => date('Y-m-d H:i:s')),"id_perfil='$id'",1);
            }

            $administrador = (new Model\Users)->getOwnerUser();
            if($administrador){
                $administrador = $administrador;
                $perfiles = $administrador['perfiles'];
                foreach ($perfiles as $key => $value) {
                    if($value['principal']){
                        $id_perfil = $value['id_perfil'];
                    }
                }
            }

            if($estado == 1){
                $estado_txt = 'activo';
            }else{
                $estado_txt = 'inactivo';
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Cambio el estado del perfil '.$perfil['perfil'].' a '.$estado_txt, $id_perfil, $administrador['id_user'], 11, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Valida si el nombre de un perfil ya existe
     *
     * Si viene id excluye ese valor de la consulta para evitar que muestre el mensaje esto dado a que se trata de una edición  
     * 
     * @return array
    */ 
    public function validarPerfil() : array {
        try {
            global $http;

            $perfil = $this->db->scape($http->request->get('perfil'));
            $perfil = mb_strtolower($perfil, 'UTF-8');
            $id = intval($http->request->get('id'));

            if($perfil != ""){
                if($id != "" && $id != 0){
                    $perfiles = $this->db->select('*', 'perfiles', null, "perfil = '$perfil' AND id_perfil != '$id'");
                }else{
                    $perfiles = $this->db->select('*', 'perfiles', null, "perfil = '$perfil'");
                }
                if($perfiles){
                    throw new ModelsException('El perfil '.$perfil.' ya existe, por favor intenta con otro nombre.');
                }else{
                    return array('status' => 'success');
                }
            } else {
                return array('status' => null);
            }
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Agrega una nuevo perfil
     * 
     * @return array
    */ 
    public function agregarPerfil() : array {
        try {
            global $http;
            # Obtener los datos $_POST
            $metodo = $http->request->get('metodo');
            $nombre = $this->db->scape(trim($http->request->get('perfil')));
            $nombre = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s])', '', $nombre);
            $modulos = $http->request->get('modulos');
            $passwordProfile = $this->db->scape($http->request->get('passwordProfile'));
            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$passwordProfile)){
                throw new ModelsException('La contraseña es incorrecta, por favor verificala e intenta nuevamente.');
            }

            if($metodo != 'agregar' || $metodo == null){throw new ModelsException('El método no está definido.');}
            if($nombre == ""){throw new ModelsException('El Nombre del perfil está vació, por favor introduce el nombre del perfil.');}
            
            $perfil = $this->db->select('*', 'perfiles', null, "perfil = '$nombre'");
            if($perfil){throw new ModelsException('El perfil '.$nombre.' ya existe, por favor intenta con otro nombre.');}

            # Registrar perfil
            $insertar = $this->db->insert('perfiles', array(
                'perfil' => ucfirst(mb_strtolower($nombre, 'UTF-8')),
                'fecha' => date('Y-m-d H:i:s'),
                'estado' => 0
            ));

            if($modulos != null && is_array($modulos)){

                foreach ($modulos as $key => $value) {

                    $id_modulo = intval($value);

                    if($this->db->select('*','modulos',null,"id_modulo='$id_modulo'",1)){

                        $this->db->insert('perfiles_modulos', array(
                            'id_perfil' => $insertar,
                            'id_modulo' => $id_modulo 
                        ));

                    }
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

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de perfil '.ucfirst(mb_strtolower($nombre, 'UTF-8')), $perfil, $administrador['id_user'], 11, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El perfil '.ucfirst(mb_strtolower($nombre, 'UTF-8')).' se agregó correctamente.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de un perfil para cargarla en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerPerfil() : array {
        try {
            global $http, $config;

            # Obtener la url de multimedia
            $servidor = $config['build']['urlAssets'];

            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get('id'));

            if($metodo != 'obtenerPerfil' || $metodo == null){throw new ModelsException('El método no está definido.');}
            if($id != "" && $id != 0){

                $perfil = $this->perfil($id);

                $u = new Model\Users;
                $userProfile = $u->getOwnerUser();

                if($perfil){

                    $formulario = "";
                    $formulario .= '<input type="hidden" name="metodo" value="editar">
                                    <input type="hidden" name="idP" value="'.$perfil["id_perfil"].'">
                                    <div class="row">
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
                                        <div class="col-xs-12">                             
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                          <i class="fas fa-user-check"></i>
                                                    </span>
                                                    <input type="text" class="form-control eValidar" tipo="edicion" id="perfil" name="perfil" key="'.$perfil["id_perfil"].'" placeholder="Nombre del perfil" value="'.$perfil['perfil'].'" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <p class="text-muted text-center">
                                                <b>Modulos asignados al perfil</b>
                                            </p>
                                            <hr>
                                            <div class="col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-4" id="modulosAsignados">';
                                                $cargarModulos = (new Model\Menu)->menuPerfil($perfil["id_perfil"]);
                                                $formulario .= $cargarModulos;
                                            $formulario .= '</div>
                                        </div>
                                    </div>';

                    return array('status' => 'success', 'formulario' => $formulario);
                }else{
                    throw new ModelsException('El perfil recibido no existe, por favor intenta con un perfil de la tabla.');
                }
            }else{
                throw new ModelsException('La petición no puede ser procesada (id nulo).');
            }    
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

     /**
     * Edita un administrador
     * 
     * @return array
    */ 
    public function editarPerfil() : array {
        try {
            global $http;
            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get('idP'));

            $perfil = $this->db->scape(trim($http->request->get('perfil')));
            $perfil = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s])', '', $perfil);
            $modulos = $http->request->get('modulos');
            
            $passwordProfile = $this->db->scape($http->request->get('passwordProfile'));

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$passwordProfile)){
                throw new ModelsException('La contraseña es incorrecta, por favor verificala e intenta nuevamente.');
            }

            $perfilBD = $this->perfil($id);

            if(!$perfilBD){throw new ModelsException("El perfil que se intenta editar no existe.");}         

            if($metodo != 'editar' || $metodo == null){throw new ModelsException("El método recibido no está definido.");}  
            
            if($perfil == ""){
                $perfil = $perfilBD['perfil'];
            }
            
            $validarPerfil = $this->db->select('*', 'perfiles', null, "perfil = '$perfil' AND id_perfil != '$id'");
            if($validarPerfil){throw new ModelsException('El nombre del perfil ya existe, por favor intente usar otro nombre.');}

            # Actualizar perfil
            $this->db->update('perfiles', array(
                'perfil' => ucfirst(mb_strtolower($perfil, 'UTF-8'))
            ), "id_perfil='$id'",1);

            if($modulos != null){

                if(is_array($modulos)){

                    $this->db->delete('perfiles_modulos', "id_perfil='$id'");

                    foreach ($modulos as $key => $value) {

                        $id_modulo = intval($value);

                        if($this->db->select('*','modulos',null,"id_modulo='$id_modulo'",1)){

                            $this->db->insert('perfiles_modulos', array(
                                'id_perfil' => $id,
                                'id_modulo' => $id_modulo 
                            ));

                        }
                    }
                }
            }else{
                $this->db->delete('perfiles_modulos', "id_perfil='$id'");
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

            (new Model\Actividad)->registrarActividad('Evento', 'Edición del perfil '.$perfilBD['perfil'], $perfil, $administrador['id_user'], 11, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El perfil se editó correctamente.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Elimina un perfil
     * 
     * @return array
    */ 
    public function eliminarPerfil() : array {
        try {
            global $http;

            $password = $this->db->scape($http->request->get('pass'));

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('La contraseña es incorrecta, por favor verificala e intenta nuevamente.');
            }

            $id = intval($http->request->get('id'));

            $perfil = $this->perfil($id);
            if(!$perfil){
                throw new ModelsException('El perfil que se intenta eliminar no existe.');
            }else{

                if($perfil['id_perfil'] == 1 || $perfil['id_perfil'] == 2 || $perfil['id_perfil'] == 3 || $perfil['id_perfil'] == 4){
                    throw new ModelsException('El perfil no puede ser eliminado.');
                }

                $adminPerfil = $this->db->select('*', 'administradores_perfiles', null, "id_perfil='{$perfil['id_perfil']}'");

                if($adminPerfil){
                    return array('status' => 'info', 'title' => '¡Atención!', 'message' => 'El perfil no puede ser eliminado, existen registros asociados a este.');
                }else{
                    $this->db->delete('perfiles', "id_perfil='$id'");
                    $this->db->delete('perfiles_modulos', "id_perfil='$id'");

                    $administrador = (new Model\Users)->getOwnerUser();
                    if($administrador){
                        $administrador = $administrador;
                        $perfiles = $administrador['perfiles'];
                        foreach ($perfiles as $key => $value) {
                            if($value['principal']){
                                $id_perfil = $value['id_perfil'];
                            }
                        }
                    }

                    (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de perfil '.$id.' - '.$perfil['perfil'], $id_perfil, $administrador['id_user'], 11, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'El perfil ha sido eliminado correctamente.');
                }
            }
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage());
        }
    }

    /**
     * perfiles adcionales
     * 
     * @return array
    */ 
    public function perfilesAdcionales() : array {

        global $http;
        $id = intval($http->request->get('perfil'));

        if($id != 0){
            $perfiles = $this->db->select("*", "perfiles", null, "id_perfil != '$id'", null, "ORDER BY perfil ASC");

            if($perfiles){
                $html = '<div class="col-xs-12">
                            <p class="text-muted text-center"><b>Perfiles adicionales</b></p>
                            <hr>
                        </div>';

                foreach ($perfiles as $key => $value) {
                    $html .= '
                        <div class="col-xs-12 col-sm-6" >
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="perfilesAdcionales[]" value="'.$value['id_perfil'].'" class="perfilesAdicionales" idPerfil="'.$value['id_perfil'].'"> '.$value['perfil'].'
                                </label>
                            </div>
                        </div>';
                }     

                $html .= '<div class="col-xs-12">
                    <hr>
                </div>';
            }else{
                $html = '';
            }
        }else{
            $html = '';
        }


        return array('html' => $html);
    }

    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}