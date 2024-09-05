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
class Administradores extends Models implements IModels {
    use DBModel;

    /**
     * URL de multimedia
     *
     * @var int
     */

    const URL_ASSETS_WEBSITE = '../';

    /**
     * Obtiene todos los administradores 
     * 
     * @return false|array
    */ 
    public function administradores() {
        $administradores = $this->db->select("*", "administradores", null, "id_user != '".$this->id_user."' AND id_user != '1'", null, "ORDER BY id_user DESC");
        return $administradores;
    }

    /**
     * Obtiene datos de un administrador según su id en la base de datos, haciendo INNER JOIN a cabeceras
     * 
     * @param int $id: Id de la categoría a obtener
     *
     * @return false|array[0]
    */ 
    public function administrador($id) {
        $administrador = $this->db->select("*", 'administradores', null, "id_user='$id'", 1);
        if($administrador){
            return $administrador[0];
        }else{
            return false;
        }
    }

    /**
     * Obtiene los perfiles según el valor de un item
     *
     * @param string $item: campo de la tabla
     * @param string $valor: valor a comparar
     * 
     * @return false|array[0]
    */ 
    public function administradorPor($item, $valor){
        $administradores = $this->db->select('*', 'administradores', null, "$item='$valor'");
        if($administradores){
            return $administradores;
        }else{
            return false;
        }
    }

    /**
     * Retorna los datos de los administradores para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarAdministradores() : array {
        global $config;

        # Obtener la url de multimedia
        $servidor = $config['build']['urlAssets'];

        $administradores = $this->administradores();
        $data = [];
        if($administradores){
            $p = new Model\Perfiles;
            foreach ($administradores as $key => $value) {
                $infoData = [];

                $infoData[] = '<span class="badge bg-black" style="font-weight:500;">'.$value["id_user"].'</span>';
                $infoData[] = '<img src="'.$servidor.$value["photo"].'?'.time().'" class="img-circle" width="30px">';
                $infoData[] = '<span class="badge bg-purple" style="font-weight:500;">'.$value["name"].'</span>';
                $infoData[] = $value["email"];
                $infoData[] = $value["secret_key"];

                $perfilP = $p->perfilesAdministrador($value["id_user"]);
                $perfiles = '';
                $perfilAlmacen = false;
                foreach ($perfilP as $k => $v) {
                    if($v['principal'] == 1){
                        $perfiles .= ' <span class="badge bg-black">'.$v['perfil'].'</span> ';
                    }else{
                        $perfiles .= ' <span class="badge bg-gray">'.$v['perfil'].'</span> ';
                    }

                    if($v['id_perfil'] == 4){
                        $perfilAlmacen = true;
                    }
                }

                $infoData[] = $perfiles;

                if($perfilAlmacen){
                    $almacenesAdministrador = $this->db->select('aa.principal, a.almacen', 'administradores_almacenes AS aa', "INNER JOIN almacenes AS a ON aa.id_almacen=a.id_almacen", "aa.id_user = '".$value["id_user"]."'", null, "ORDER BY aa.principal DESC");
                    $almacenes = '';
                    foreach ($almacenesAdministrador as $k1 => $v2) {
                        if($v2['principal'] == 1){
                            $almacenes .= ' <span class="badge bg-black">'.$v2['almacen'].'</span> ';
                        }else{
                            $almacenes .= ' <span class="badge bg-gray">'.$v2['almacen'].'</span> ';
                        }
                    }

                    $infoData[] = $almacenes;

                }else{
                    $infoData[] = 'N/A';
                }

                if($value["status"] == 1){
                    $infoData[] = $estado = '<span class="estado'.$value["id_user"].'"><i class="fas fa-toggle-on fa-lg text-aqua estado" value="0" key="'.$value["id_user"].'" style="cursor:pointer;"></i></span>';
                }else{
                    $infoData[] = $estado = '<span class="estado'.$value["id_user"].'"><i class="fas fa-toggle-off fa-lg text-muted estado" value="1" key="'.$value["id_user"].'" style="cursor:pointer;"></i></span>';
                }

                $infoData[] = "<div class='btn-group'>
                                    <button class='btn btn-sm btn-primary obtenerAdministrador' key='".$value["id_user"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt'></i></button>
                                    <button class='btn btn-sm btn-danger eliminar' key='".$value["id_user"]."'><i class='fas fa-trash-alt'></i></button>
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
     * Valida si el nombre o correo de un administrador ya existe, retornando la ruta como url amigable
     *
     * Si viene id excluye ese valor de la consulta para evitar que muestre el mensaje esto dado a que se trata de una edición  
     * 
     * @return array
    */ 
    public function validarAdministrador() : array {
        try {
            global $http;
            $id = intval($http->request->get('id'));
            $item = $this->db->scape($http->request->get('item'));
            $valor = $this->db->scape(trim($http->request->get('valor')));

            if($item == 'name' || $item == 'ename'){
                if($id && $id != 0){
                    $administrador = $this->db->select('*', 'administradores', null, "name = '$valor' AND id_user != '$id'");
                }else{
                    $administrador = $this->db->select('*', 'administradores', null, "name = '$valor'");
                }
                $txt = 'nombre';
            }elseif($item == 'email' || $item == 'eemail'){
                if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    throw new ModelsException('El correo electrónico no es válido.');
                }
                if($id && $id != 0){
                    $administrador = $this->db->select('*', 'administradores', null, "email = '$valor' AND id_user != '$id'");
                }else{    
                    $administrador = $this->db->select('*', 'administradores', null, "email = '$valor'");
                }
                $txt = 'correo electrónico';
            }else{
                throw new ModelsException('La petición no se puede procesar (valor nulo).');
            }

            if($administrador){
                throw new ModelsException('Ya existe una cuenta con el '.$txt.' '.$valor.'.');
            }

            return array('status' => null);
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Cambia el estado de un administrador
     *
     * 0:desactivado | 1:activado 
     *
     * @return array
    */ 
    public function estadoAdministrador() : array {
        try {
            global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $administrador = $this->administrador($id);
            if(!$administrador){
                throw new ModelsException('El administrador no existe.');
            }else{
                $this->db->update('administradores',array('status' => $estado, 'date' => date('Y-m-d H:i:s')),"id_user='$id'",1);
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

            $adminEdit = $this->administrador($id);

            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado del administrador '.$id.' - '.$adminEdit['name'].' a '.$estado_txt, $perfil, $administrador['id_user'], 13, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Agrega una nuevo administrador
     * 
     * @return array
    */ 
    public function agregarAdministrador() : array {
        try {
            global $http;
            # Obtener los datos $_POST
            $metodo = $http->request->get('metodo');
            $nombre = $this->db->scape(trim($http->request->get('nombre')));
            //$nombre = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s])', '', $nombre);
            $correo = $this->db->scape(trim($http->request->get('correo')));
            $password = $this->db->scape($http->request->get('password'));
            $confirmarPassword = $this->db->scape($http->request->get('confirmarPassword'));
            $perfil = $this->db->scape($http->request->get('perfil'));
            $almacen = intval($http->request->get('almacen'));

            $perfilesBD = $this->db->select('id_perfil', 'perfiles');
            $perfiles = [];
            foreach ($perfilesBD as $key => $value) {
                $perfiles[] = (int) $value['id_perfil'];
            };

            $perfilesAdcionales = $http->request->get('perfilesAdcionales');
            $foto = $http->files->get('foto');
            $passwordProfile = $this->db->scape($http->request->get('passwordProfile'));

            $almacenesBD = $this->db->select('id_almacen', 'almacenes');
            $almacenes = [];
            foreach ($almacenesBD as $key => $value) {
                $almacenes[] = (int) $value['id_almacen'];
            };

            $almacenesAdcionales = $http->request->get('almacenesAdcionales');

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$passwordProfile)){
                throw new ModelsException('Verifique su contraseña.');
            }

            if($metodo != 'agregar' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }
            if($nombre == ""){
                throw new ModelsException('El Nombre está vacío.');
            }
            if($correo == ""){
                throw new ModelsException('El Correo electrónico está vacío.');
            }else{
                if(!Helper\Strings::is_email($correo)){
                    throw new ModelsException('El Correo electrónico no es válido.');
                }
            }

            if($password != '' && $confirmarPassword != ''){
                if($password != $confirmarPassword){
                    throw new ModelsException('Las contraseñas no coinciden.');
                }
            }else{
                throw new ModelsException('Es necesario asignar una contraseña.');
            }
            
            if(!in_array($perfil, $perfiles)){
                throw new ModelsException('El perfil seleccionado no es correcto.');
            }

            # Si se selecciono el perfil de sucursal
            if($perfil == 4 || (is_array($perfilesAdcionales) && in_array(4, $perfilesAdcionales))){
                # Validar que venga el almacen principal
                if(!in_array($almacen, $almacenes)){
                    throw new ModelsException('El almacén seleccionado no es correcto.');
                }
            }

            if($foto == null) {
                $urlFoto = "assets/plantilla/vistas/img/perfiles/default/default.jpg";
            }else{
                if(!Helper\Files::is_image($foto->getClientOriginalName())){
                    throw new ModelsException('El formato de la imagen no es válido.');   
                } else if($foto->getClientSize()>2000000){
                    throw new ModelsException('El tamaño de la imagen superara los 2 Mb.');
                }
                # Guardar la foto en la carpeta
                list($ancho, $alto) = getimagesize($foto->getRealPath());
                $nuevoAncho = 500;
                $nuevoAlto = 500;
                $nombreArchivo = "assets/plantilla/vistas/img/perfiles/$correo";
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                # Si la imagen es jpg 
                if ($foto->getMimeType() == "image/jpeg") {
                    
                    $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.jpg';

                    $urlFoto = $nombreArchivo.'.jpg';
                    $origen = imagecreatefromjpeg($foto->getRealPath());
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagejpeg($destino, $ruta, 100);
                # Si la imagen es png
                } else if($foto->getMimeType() == "image/png") {
                    
                    $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.png';

                    $urlFoto = $nombreArchivo.'.png';
                    $origen = imagecreatefrompng($foto->getRealPath());
                    # Conservar transparencias
                    imagealphablending($destino, FALSE);
                    imagesavealpha($destino, TRUE);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagepng($destino, $ruta, 9);    
                }
            }

            $google_authenticator = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            $secret_key = $google_authenticator->generateSecret();

            # Registrar administrador
            $insertar = $this->db->insert('administradores', array(
                'name' => ucwords(mb_strtolower($nombre, 'UTF-8')),
                'photo' => $urlFoto,
                'email' => $correo,
                'pass' => Helper\Strings::hash($password),
                'secret_key' => $secret_key,
                'date' => date('Y-m-d H:i:s'),
                'register_date' => date('Y-m-d H:i:s'),
                'status' => 0
            ));

            # Registrar perfil principal
            $this->db->insert('administradores_perfiles', array(
                'id_user' => $insertar,
                'id_perfil' => $perfil,
                'principal' => 1
            ));

            # Si el almacen es diferente de 0
            if($almacen != 0){
                # Registrar almacen principal
                $this->db->insert('administradores_almacenes', array(
                    'id_user' => $insertar,
                    'id_almacen' => $almacen,
                    'principal' => 1
                ));
            }

            # Si vienen almacenes adicionales
            if($almacenesAdcionales != null){
                if(count($almacenesAdcionales) > 0){
                    # Recorrer $almacenesAdcionales
                    foreach ($almacenesAdcionales as $key => $value) {
                        $this->db->insert('administradores_almacenes', array(
                            'id_user' => $insertar,
                            'id_almacen' => $value
                        ));
                    }
                }
            }

            # Registrar roles asociados al perfil principal
            $rolesPerfil = $this->db->select('r.id_rol', 'roles AS r', 'INNER JOIN modulos AS m ON r.id_modulo = m.id_modulo INNER JOIN perfiles_modulos AS pm ON m.id_modulo=pm.id_modulo', "pm.id_perfil='$perfil'");

            if($rolesPerfil){
                foreach ($rolesPerfil as $key => $value) {
                    $this->db->insert('roles_administradores', array(
                        'id_rol' => $value['id_rol'],
                        'id_user' => $insertar
                    ));
                }
            }

            # Si vienen perfiles adicionales
            if($perfilesAdcionales != null){
                if(count($perfilesAdcionales) > 0){

                    # Llamar el modelo Perfiles
                    $p = new Model\Perfiles;

                    # Recorrer $perfilesAdicionales
                    foreach ($perfilesAdcionales as $key => $value) {
                        
                        # Obtener perfil
                        $perfilAdiconal = intval($value);
                        
                        # Revisar si el perfil existe
                        if($p->perfil($perfilAdiconal)){

                            # Registrar perfil adicional
                            $this->db->insert('administradores_perfiles', array(
                                'id_user' => $insertar,
                                'id_perfil' => $value
                            ));

                            # Registrar roles asociados al perfil principal
                            $rolesPerfil = $this->db->select('r.id_rol', 'roles AS r', 'INNER JOIN modulos AS m ON r.id_modulo = m.id_modulo INNER JOIN perfiles_modulos AS pm ON m.id_modulo=pm.id_modulo', "pm.id_perfil='$value'");

                            if($rolesPerfil){

                                foreach ($rolesPerfil as $key2 => $value2) {

                                    $existe = $this->db->select('*', 'roles_administradores', null, "id_rol='{$value2['id_rol']}' AND id_user='insertar'");

                                    if(!$existe){

                                        $this->db->insert('roles_administradores', array(
                                            'id_rol' => $value2['id_rol'],
                                            'id_user' => $insertar
                                        ));
                                    }
                                }
                            }
                        }
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

            $newAdminInsert = $this->administrador($insertar);

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de administrador '.$insertar.' - '.$newAdminInsert['name'], $perfil, $administrador['id_user'], 13, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El administrador '.ucwords(mb_strtolower($nombre, 'UTF-8')).' se agregó correctamente.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de un administrador para cargarla en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerAdministrador() : array {
        try {
            global $http, $config;

            # Obtener la url de multimedia
            $servidor = $config['build']['urlAssets'];

            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get('id'));

            if($metodo != 'obtenerAdministrador' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }
            if($id != "" && $id != 0){

                $administrador = $this->administrador($id);

                $u = new Model\Users;
                $userProfile = $u->getOwnerUser();

                if($administrador){

                    $formulario = "";
                    $formulario .= '<input type="hidden" name="metodo" value="editar">
                                    <input type="hidden" name="idP" value="'.$administrador["id_user"].'">
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
                                                          <i class="fa fa-address-card"></i>
                                                    </span>
                                                    <input type="text" class="form-control evalidar" tipo="ename" id="ename" name="nombre" key="'.$administrador["id_user"].'" value="'.$administrador["name"].'" placeholder="Nombre" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                                    <input type="text" class="form-control evalidar" tipo="eemail" id="eemail" name="correo" key="'.$administrador["id_user"].'" value="'.$administrador["email"].'" placeholder="Corre electrónico" required>
                                                </div>  
                                            </div>                                          
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-key"></i>
                                                    </span>
                                                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                                                </div>  
                                            </div>                                          
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-key"></i>
                                                    </span>
                                                    <input type="password" class="form-control" name="confirmarPassword" placeholder="Confirmar contraseña" required>
                                                </div>  
                                            </div>                                          
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-user-check"></i>
                                                    </span>
                                                    <select class="form-control js-example-placeholder-single js-states eseleccionarPerfil" name="perfil" style="width: 100%;" lang="es" data-placeholder="Perfil" data-allow-clear="true" required>
                                                            <option></option>';
                                                        $formulario .= '
                                                        <optgroup label="Perfiles disponibles">';
                                                        
                                                        $p = new Model\Perfiles;
                                                        $perfilPincipal = $p->perfilPrincipal($administrador["id_user"]);
                                                        foreach ($p->perfiles() as $key => $value) {
                                                            if($value['id_perfil'] == $perfilPincipal['id_perfil']){
                                                                $formulario .= '
                                                                <option value="'.$value['id_perfil'].'" selected="selected">'.$value['perfil'].'</option>';
                                                            }else{
                                                                $formulario .= '
                                                                <option value="'.$value['id_perfil'].'">'.$value['perfil'].'</option>';
                                                            }
                                                        }

                                                        $formulario .= '
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="perfilesAdcionales">';

                                        $perfiles = $this->db->select("*", "perfiles", null, "id_perfil != '".$perfilPincipal['id_perfil']."'", null, "ORDER BY perfil ASC");

                                        if($perfiles){

                                                $formulario .= '
                                                    <div class="col-xs-12">
                                                        <p class="text-muted text-center"><b>Perfiles adicionales</b></p>
                                                        <hr>
                                                    </div>';

                                                foreach ($perfiles as $key => $value) {

                                                    $adminPerfil = $this->db->select("*", "administradores_perfiles", null, "id_perfil = '".$value['id_perfil']."' AND id_user = '".$administrador['id_user']."'");

                                                    $checkbox = ($adminPerfil) ? "checked" : '';
                                                    
                                                    $formulario .= '
                                                        <div class="col-xs-12 col-sm-6" >
                                                            <input class="perfilesAdicionales" idperfil="'.$value['id_perfil'].'" type="checkbox" name="perfilesAdcionales[]" value="'.$value['id_perfil'].'" '.$checkbox.'> '.$value['perfil'].'
                                                        </div>';
                                                }     

                                                $formulario .= '<div class="col-xs-12">
                                                    <hr>
                                                </div>';

                                        }
                                            
                                        $formulario .= '
                                        </div>';

                                        $perfilesAdministrador = $this->db->select('*', 'administradores_perfiles', null, "id_user='".$administrador['id_user']."' AND id_perfil='4'");
                                        $clasehidden = (!$perfilesAdministrador) ? 'hidden' : '';

                                        $almacenPrincipal = $this->db->select('a.id_almacen, a.almacen', 'almacenes AS a', "INNER JOIN administradores_almacenes AS aa ON a.id_almacen=aa.id_almacen", "aa.id_user='".$administrador['id_user']."' AND aa.principal=1",1);
                                        $almacenPrincipal = $almacenPrincipal[0]['id_almacen'];

                                        $formulario .= '
                                            <div class="col-xs-12 ealmacen '.$clasehidden.'">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-warehouse"></i>
                                                        </span>
                                                        <select class="form-control js-example-placeholder-single js-states eseleccionarAlmacen" name="almacen" style="width: 100%;" lang="es" data-placeholder="Almacén principal" data-allow-clear="true" required>
                                                            <option></option>
                                                            <optgroup label="Almacenes disponibles">';
                                                                $a = new Model\Almacenes;
                                                                foreach ($a->almacenes() as $key => $value) {
                                                                    if($value['id_almacen'] == $almacenPrincipal){
                                                                        $formulario .= '
                                                                        <option value="'.$value['id_almacen'].'" selected="selected">'.$value['almacen'].'</option>';
                                                                    }else{
                                                                        $formulario .= '
                                                                        <option value="'.$value['id_almacen'].'">'.$value['almacen'].'</option>';
                                                                    }
                                                                }

                                                            $formulario .= '
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>';

                                        $formulario .= '
                                        <div class="almacenAdicional">';

                                            if($perfilesAdministrador){
                                                $almacenes = $this->db->select("*", "almacenes", null, "id_almacen != '".$almacenPrincipal."'", null, "ORDER BY almacen ASC");

                                                if($almacenes){
                                                    $formulario .= '
                                                    <div class="col-xs-12">
                                                        <p class="text-muted text-center"><b>Perfiles adicionales</b></p>
                                                        <hr>
                                                    </div>';

                                                    foreach ($almacenes as $key => $value) {

                                                        $adminAlmacen = $this->db->select("*", "administradores_almacenes", null, "id_almacen = '".$value['id_almacen']."' AND id_user = '".$administrador['id_user']."'");

                                                        $checkbox = ($adminAlmacen) ? "checked" : '';
                                                        
                                                        $formulario .= '
                                                        <div class="col-xs-12 col-sm-6" >
                                                            <input type="checkbox" name="almacenesAdcionales[]" value="'.$value['id_almacen'].'" '.$checkbox.'> '.$value['almacen'].'
                                                        </div>';
                                                    } 

                                                    $formulario .= '<div class="col-xs-12">
                                                        <hr>
                                                    </div>';
                                                }
                                            }    


                                        $formulario .= '
                                        </div>';

                                        $formulario .= '
                                        <div class="col-xs-12 col-sm-8">
                                            <div class="text-center">
                                                <label>Foto</label>
                                                <div>
                                                    <label for="efoto" class="btn btn-sm btn-default btn-block"><i class="fa fa-upload"></i> Selecciona una imagen</label>
                                                    <input type="file" name="foto" id="efoto" style="visibility:hidden;" accept="image/*">
                                                    <p class="help-block" style="margin-top:-20px;">Tamaño recomendado <br>500px * 500px, peso máximo 2MB</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 text-center">
                                            <p><img src="'.$servidor.$administrador["photo"].'" class="img-circle eprevisualizarFoto" style="width:100px; height:100px;"></p>
                                        </div>
                                    </div>';

                    return array('status' => 'success', 'formulario' => $formulario);
                }else{
                    throw new ModelsException('El administrador no existe.');
                }
            }else{
                throw new ModelsException('La petición no se puede procesar (id nulo).');
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
    public function editarAdministrador() : array {

        try {
            global $http;
            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get('idP'));

            $nombre = $this->db->scape(trim($http->request->get('nombre')));
            //$nombre = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s])', '', $nombre);
            $correo = $this->db->scape(trim($http->request->get('correo')));
            $password = $this->db->scape($http->request->get('password'));
            $confirmarPassword = $this->db->scape($http->request->get('confirmarPassword'));

            $perfil = $this->db->scape($http->request->get('perfil'));
            $almacen = intval($http->request->get('almacen'));

            $perfilesBD = $this->db->select('id_perfil', 'perfiles');
            $perfiles = [];
            foreach ($perfilesBD as $key => $value) {
                $perfiles[] = (int) $value['id_perfil'];
            };

            $perfilesAdcionales = $http->request->get('perfilesAdcionales');
            $foto = $http->files->get('foto');
            $passwordProfile = $this->db->scape($http->request->get('passwordProfile'));

            $almacenesBD = $this->db->select('id_almacen', 'almacenes');
            $almacenes = [];
            foreach ($almacenesBD as $key => $value) {
                $almacenes[] = (int) $value['id_almacen'];
            };

            $almacenesAdcionales = $http->request->get('almacenesAdcionales');

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$passwordProfile)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $administrador = $this->administrador($id);

            if(!$administrador){
                throw new ModelsException("El administrador no existe.");
            }

            if($administrador["id_user"] == 1){
                throw new ModelsException("El administrador no puede ser editado.");
            }              

            if($metodo != 'editar' || $metodo == null){
                throw new ModelsException("El método no está definido.");}  
            if($nombre == ""){
                throw new ModelsException('El Nombre está vacío.');
            }
            if($correo == ""){
                throw new ModelsException('El Correo electrónico está vacío.');
            }else{
                if(!Helper\Strings::is_email($correo)){
                    throw new ModelsException('El Correo electrónico no es válido.');
                }
            }

            if($password == ''){
                $pass = $administrador["pass"];
            }else{
                if($password != $confirmarPassword){
                    throw new ModelsException('Las contraseñas no coinciden.');
                }else{
                    $pass = Helper\Strings::hash($password);
                }
            }

            if(!in_array($perfil, $perfiles)){throw new ModelsException('El perfil seleccionado no es correcto.');}

            # Si se selecciono el perfil de sucursal
            if($perfil == 4 || (is_array($perfilesAdcionales) && in_array(4, $perfilesAdcionales))){
                # Validar que venga el almacen principal
                if(!in_array($almacen, $almacenes)){
                    throw new ModelsException('El almacén seleccionado no es correcto.');
                }
            }

            if($foto == null) {
                $urlFoto = $administrador["photo"];
            }else{
                if(!Helper\Files::is_image($foto->getClientOriginalName())){
                    throw new ModelsException('El formato de la imagen no es válido.');   
                } else if($foto->getClientSize()>2000000){
                    throw new ModelsException('El tamaño de la imagen superara los 2 Mb.');
                }
                # Guardar la foto en la carpeta
                
                $rutaArchivoAnterior = self::URL_ASSETS_WEBSITE.$administrador["photo"];

                if($administrador["photo"] != "assets/plantilla/vistas/img/perfiles/default/default.jpg"){
                    unlink($rutaArchivoAnterior);
                }
                
                list($ancho, $alto) = getimagesize($foto->getRealPath());
                $nuevoAncho = 500;
                $nuevoAlto = 500;
                $nombreArchivo = "assets/plantilla/vistas/img/perfiles/$correo";
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                # Si la imagen es jpg 
                if ($foto->getMimeType() == "image/jpeg") {
                    
                    $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.jpg';

                    $urlFoto = $nombreArchivo.'.jpg';
                    $origen = imagecreatefromjpeg($foto->getRealPath());
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagejpeg($destino, $ruta, 100);
                # Si la imagen es png
                } else if($foto->getMimeType() == "image/png") {
                    
                    $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.png';

                    $urlFoto = $nombreArchivo.'.png';
                    $origen = imagecreatefrompng($foto->getRealPath());
                    # Conservar transparencias
                    imagealphablending($destino, FALSE);
                    imagesavealpha($destino, TRUE);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagepng($destino, $ruta, 9);    
                }
            }

            $p = new Model\Perfiles;
            $perfilPincipal = $p->perfilPrincipal($administrador["id_user"]);
            # Si el perfil seleccionado es diferente al que ya se tenia
            if($perfil != $perfilPincipal['id_perfil']){

                $this->db->delete('administradores_perfiles', "id_user = '".$administrador['id_user']."'");

                # Registrar nuevamente perfil principal
                $this->db->insert('administradores_perfiles', array(
                    'id_user' => $administrador['id_user'],
                    'id_perfil' => $perfil,
                    'principal' => 1
                ));

            }

            $almacenPrincipal = $this->db->select('a.id_almacen, a.almacen', 'almacenes AS a', "INNER JOIN administradores_almacenes AS aa ON a.id_almacen=aa.id_almacen", "aa.id_user='".$administrador['id_user']."' AND aa.principal=1",1);
            $almacenPrincipal = $almacenPrincipal[0]['id_almacen'];
            # Si el almacen es diferente de 0
            if($almacen != 0){
                # Si el almacen seleccionado es diferente al que ya se tenia
                if($almacen != $almacenPrincipal){

                    $this->db->delete('administradores_almacenes', "id_user = '".$administrador['id_user']."'");

                    # Registrar almacen principal
                    $this->db->insert('administradores_almacenes', array(
                        'id_user' => $administrador['id_user'],
                        'id_almacen' => $almacen,
                        'principal' => 1
                    ));
                }
            }else{
                $this->db->delete('administradores_almacenes', "id_user = '".$administrador['id_user']."'");
            }

            # Si vienen almacenes adicionales
            if($almacenesAdcionales != null){

                $this->db->delete('administradores_almacenes', "id_user = '".$administrador['id_user']."' AND id_almacen != '$almacen'");

                if(count($almacenesAdcionales) > 0){
                    # Recorrer $almacenesAdcionales
                    foreach ($almacenesAdcionales as $key => $value) {
                        $this->db->insert('administradores_almacenes', array(
                            'id_user' => $administrador['id_user'],
                            'id_almacen' => $value
                        ));
                    }
                }
            }else{
                $this->db->delete('administradores_almacenes', "id_user = '".$administrador['id_user']."' AND id_almacen != '$almacen'");
            }

            if($perfil == 4 || (is_array($perfilesAdcionales) && in_array(4, $perfilesAdcionales))){
                
            }else{
                $this->db->delete('administradores_almacenes', "id_user = '".$administrador['id_user']."'");
            }

            # Eliminar roles del administrador
            $this->db->delete('roles_administradores', "id_user='". $administrador['id_user']."'");

            # Registrar nuevamente roles asociados al perfil principal
            $rolesPerfil = $this->db->select('r.id_rol', 'roles AS r', 'INNER JOIN modulos AS m ON r.id_modulo = m.id_modulo INNER JOIN perfiles_modulos AS pm ON m.id_modulo=pm.id_modulo', "pm.id_perfil='$perfil'");

            if($rolesPerfil){
                foreach ($rolesPerfil as $key => $value) {
                    $this->db->insert('roles_administradores', array(
                        'id_rol' => $value['id_rol'],
                        'id_user' => $administrador['id_user']
                    ));
                }
            }

            # Actualizar perfil
            $this->db->update('administradores', array(
                'name' => ucwords(mb_strtolower($nombre, 'UTF-8')),
                'photo' => $urlFoto,
                'email' => $correo,
                'date' => date('Y-m-d H:i:s'),
                'pass' => $pass
            ), "id_user='$id'",1);

            # Si vienen perfiles adicionales
            if($perfilesAdcionales != null){

                $this->db->delete('administradores_perfiles', "id_user = '".$administrador['id_user']."' AND id_perfil != '$perfil'");

                # Llamar el modelo Perfiles
                $p = new Model\Perfiles;

                # Recorrer $perfilesAdicionales
                foreach ($perfilesAdcionales as $key => $value) {
                    
                    # Obtener perfil
                    $perfilAdiconal = intval($value);
                    
                    # Revisar si el perfil existe
                    if($p->perfil($perfilAdiconal)){

                        # Registrar perfil adicional
                        $this->db->insert('administradores_perfiles', array(
                            'id_user' => $administrador['id_user'],
                            'id_perfil' => $value
                        ));

                        # Registrar roles asociados al perfil principal
                        $rolesPerfil = $this->db->select('r.id_rol', 'roles AS r', 'INNER JOIN modulos AS m ON r.id_modulo = m.id_modulo INNER JOIN perfiles_modulos AS pm ON m.id_modulo=pm.id_modulo', "pm.id_perfil='$value'");

                        if($rolesPerfil){

                            foreach ($rolesPerfil as $key2 => $value2) {

                                $existe = $this->db->select('*', 'roles_administradores', null, "id_rol='{$value2['id_rol']}' AND id_user='".$administrador['id_user']."'");

                                if(!$existe){

                                    $this->db->insert('roles_administradores', array(
                                        'id_rol' => $value2['id_rol'],
                                        'id_user' => $administrador['id_user']
                                    ));
                                }
                            }
                        }
                    }
                }

            }else{
                $this->db->delete('administradores_perfiles', "id_user = '".$administrador['id_user']."' AND id_perfil != '$perfil'");
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

            $editAdminInsert = $this->administrador($id);

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de administrador '.$id.' - '.$editAdminInsert['name'], $perfil, $administrador['id_user'], 13, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El administrador '.$id.' - '.$editAdminInsert['name'].' se editó correctamente.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /*
     * Elimina un administrador
     * 
     * @return array
    */ 
    public function eliminarAdministrador() : array {
        try {
            global $http;

            $password = $this->db->scape($http->request->get('pass'));

            $u = new Model\Users;
            $userProfile = $u->getOwnerUser();

            if(!Helper\Strings::chash($userProfile["pass"],$password)){
                throw new ModelsException('Verifique su contraseña.');
            }

            $id = intval($http->request->get('id'));

            if($id == 1){
                throw new ModelsException("El administrador no puede ser eliminado.");
            }

            $administrador = $this->administrador($id);
            if(!$administrador){
                throw new ModelsException('El administrador no existe.');
            }else{

                /* Eliminar imagen de administrador */
                
                $rutaFoto = self::URL_ASSETS_WEBSITE.$administrador["photo"];

                if($administrador["photo"] != "assets/plantilla/vistas/img/perfiles/default/default.jpg"){
                    unlink($rutaFoto);
                }

                $this->db->delete('administradores', "id_user='$id'");
                $this->db->delete('administradores_almacenes', "id_user = '$id'");
                $this->db->delete('administradores_perfiles', "id_user = '$id'");
                $this->db->delete('roles_administradores', "id_user = '$id'");

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

                (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de administrador '.$id, $perfil, $administrador['id_user'], 13, date('Y-m-d H:i:s'), 0);

                return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'El administrador ha sido eliminado.');
            }
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage());
        }
    }

    /*
     * Obtiene los datos del usuario conectado y los devuelve para mostrarlos en su perfil
     * 
     * @return array
    */ 
    public function cargarCuenta() {

        global $config;

        # Obtener la url de multimedia
        $servidor = $config['build']['urlAssets'];

        $u = new Model\Users;
        $user = $u->getOwnerUser();
        
        if(!$user){
            Helper\Functions::redir();
        }else{

            # Llamar al modelo Perfiles
            $p = new Model\Perfiles;
            $perfilesAdmin = $p->perfilesAdministrador($user['id_user']);

            $html = "";

            $html .= '
                    <div class="box box-primary datos show">

                        <div class="box-body box-profile">

                            <img class="profile-user-img img-responsive img-circle" src="'.$servidor.$user['photo'].'" alt="'.$user['name'].'" style="width:100px; height:100px;">

                            <h3 class="profile-username text-center">'.$user['name'].'</h3>

                            <p class="text-center">';
                                
                                foreach ($user["perfiles"] as $key => $value) {
                                    if($value['principal'] == '1'){
                                        $html .= '<span class="text-muted" data-toggle="tooltip" title="Perfil activo"><b>'.$value['perfil'].'</b></span><br>';
                                    }else{
                                        $html .= '<small class="text-muted">'.$value['perfil'].'</small><br>';
                                    }
                                }
                                
                            $html .= '
                            </p>

                            <ul class="list-group list-group-unbordered">

                                <li class="list-group-item">
                                    
                                    <small>
                                        <div class="row">
                                            <div class="col-xs-2 text-right">
                                                <i class="fas fa-envelope text-muted" data-toggle="tooltip" title="Correo electrónico"></i>
                                            </div>
                                            <div class="col-xs-10">
                                                '.$user['email'].'
                                            </div>
                                        </div>
                                    </small>
                                    
                                </li>

                                <li class="list-group-item">
                                    
                                    <small>
                                        <div class="row">
                                            <div class="col-xs-2 text-right">';

                                                if($user['status'] == 1){
                                                    $html .= '<i class="fas fa-toggle-on text-muted" data-toggle="tooltip" title="Estado"></i>';
                                                }else{
                                                    $html .= '<i class="fas fa-toggle-off text-muted" data-toggle="tooltip" title="Estado"></i>';
                                                }

                                            $html .= '
                                            </div>
                                            <div class="col-xs-10">';

                                                if($user['status'] == 1){
                                                    $html .= '<span class="label label-info">Activado</span>';
                                                }else{
                                                    $html .= '<span class="label label-default">Desactivado</span>';
                                                }

                                            $html .= '
                                            </div>
                                        </div>
                                    </small>

                                </li>

                                <li class="list-group-item">
                                    
                                    <small>
                                        <div class="row">
                                            <div class="col-xs-2 text-right">
                                                <i class="fa fa-fw fa-user-plus text-muted" data-toggle="tooltip" title="Creado"></i>
                                            </div>
                                            <div class="col-xs-10">
                                                '.Helper\Strings::amigable_time(strtotime($user['register_date'])).'
                                            </div>
                                        </div>
                                    </small>

                                </li>

                                <li class="list-group-item">
                                    
                                    <small>
                                        <div class="row">
                                            <div class="col-xs-2 text-right">
                                                <i class="fas fa-user-edit text-muted" data-toggle="tooltip" title="Editado"></i>
                                            </div>
                                            <div class="col-xs-10">
                                                '.Helper\Strings::amigable_time(strtotime($user['date'])).'
                                            </div>
                                        </div>
                                    </small>

                                </li>

                                <li class="list-group-item">
                                    
                                    <small>
                                        <div class="row">
                                            <div class="col-xs-2 text-right">
                                                <i class="fas fa-sign-in-alt text-muted" data-toggle="tooltip" title="Ultimo ingreso"></i>
                                            </div>
                                            <div class="col-xs-10">
                                                '.Helper\Strings::amigable_time(strtotime($user['session_date'])).'
                                            </div>
                                        </div>
                                    </small>
    
                                </li>';

                            $perfilPrincipal = (new Model\Perfiles)->perfilPrincipal($user['id_user']);
                            if($perfilPrincipal['id_perfil'] == 4){
                                $almacenes = (new Model\Almacenes)->almacenesAsignados($user['id_user']);

                                $html .= '<li class="list-group-item">
                                    
                                    <small>
                                        <div class="row">
                                            <div class="col-xs-2 text-right">
                                                <i class="fas fa-warehouse text-muted"></i>
                                            </div>
                                            <div class="col-xs-10">';

                                            foreach ($almacenes as $key => $value) {
                                                if($value['principal'] == 1){
                                                    $html .= '<span class="badge bg-black">'.$value['almacen'].'</span>';
                                                }else{
                                                    $html .= '<span class="badge bg-gray">'.$value['almacen'].'</span>';
                                                }
                                            }
                                                
                                            $html .= '    
                                            </div>
                                        </div>
                                    </small>
    
                                </li>';
                            }

                            $html .= '
                            </ul>

                        </div>

                    </div>
                    
                    <div class="box box-default cargando hidden">
                        <div class="box-header">
                            <h3 class="box-title">Cargando datos</h3>
                        </div>
                        <div class="box-body animated infinite flash">
                            Por favor espere...
                        </div>
                        <div class="overlay">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>';

            return array($html);

        }

    }

    public function cargarHistorial(){
        $historial = (new Model\Actividad)->historial($this->id_user);
        $data = [];
        if($historial){
            foreach ($historial as $key => $value) {

                $stmt = $this->db->select('modulo', 'modulos', null, "id_modulo={$value['id_modulo']}");
                if($stmt){
                    $modulo = $stmt[0]['modulo'];
                }else{
                    $modulo = '---';
                }

                $infoData = [];

                $infoData[] = Helper\Strings::amigable_time(strtotime($value["fecha"]));
                $infoData[] = $modulo;
                $infoData[] = $value["texto"];

                $infoData[] = Helper\Functions::fecha($value["fecha"]);

                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
          
    }

    /*
     * Edita los datos del perfil del administardor conectado
     * 
     * @return array
    */ 
    public function cambiarDatosCuenta() {
        
        try {

            global $http, $config;

            # Obtener la url de multimedia
            $servidor = $config['build']['urlAssets'];

            $nombre = $this->db->scape($http->request->get('nombre'));
            $correo = $this->db->scape($http->request->get('correo'));
            $nueva_pass = $http->request->get('nueva_pass');
            $re_nueva_pass = $http->request->get('re_nueva_pass');
            $pass = $http->request->get('pass');
            $foto = $http->files->get('foto');

            $u = new Model\Users;
            $user = $u->getOwnerUser();

            if($user){

                if($correo != '' || $nueva_pass != ''){

                    if(!Helper\Strings::chash($user["pass"],$pass)){
                        throw new ModelsException('Verifique su contraseña.');
                    }

                }

                if($nombre == ''){
                    $nombre = $user["name"];
                }

                if($correo == ''){
                    $correo = $user["email"];
                }else{
                    if(!Helper\Strings::is_email($correo)){
                        throw new ModelsException('El correo electrónico no es válido.');
                    }
                }

                if($nueva_pass == ''){
                    $nueva_pass = $user["pass"];
                }else{
                    if($nueva_pass != $re_nueva_pass){
                        throw new ModelsException('Las contraseñas no coinciden.');
                    }else{
                        $nueva_pass = Helper\Strings::hash($nueva_pass);
                    }
                }

                if($foto == null) {
                    $urlFoto = $user["photo"];
                }else{
                    if(!Helper\Files::is_image($foto->getClientOriginalName())){
                        throw new ModelsException('El formato de la imagen no es válido.');   
                    } else if($foto->getClientSize()>2000000){
                        throw new ModelsException('El tamaño de la imagen superara los 2 Mb.');
                    }

                    $rutaArchivoAnterior = self::URL_ASSETS_WEBSITE.$user["photo"];

                    if($user["photo"] != "assets/plantilla/vistas/img/perfiles/default/default.jpg"){
                        unlink($rutaArchivoAnterior);
                    }
                    
                    list($ancho, $alto) = getimagesize($foto->getRealPath());
                    $nuevoAncho = 500;
                    $nuevoAlto = 500;
                    $nombreArchivo = "assets/plantilla/vistas/img/perfiles/$correo";
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    # Si la imagen es jpg 
                    if ($foto->getMimeType() == "image/jpeg") {
                        
                        $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.jpg';

                        $urlFoto = $nombreArchivo.'.jpg';
                        $origen = imagecreatefromjpeg($foto->getRealPath());
                        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta, 100);
                    # Si la imagen es png
                    } else if($foto->getMimeType() == "image/png") {
                        
                        $ruta = self::URL_ASSETS_WEBSITE.$nombreArchivo.'.png';

                        $urlFoto = $nombreArchivo.'.png';
                        $origen = imagecreatefrompng($foto->getRealPath());
                        # Conservar transparencias
                        imagealphablending($destino, FALSE);
                        imagesavealpha($destino, TRUE);
                        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta, 9);    
                    }
                }

                # Actualizar datos
                $this->db->update('administradores', array(
                    'name' => ucwords(mb_strtolower($nombre, 'UTF-8')),
                    'photo' => $urlFoto,
                    'email' => $correo,
                    'pass' => $nueva_pass,
                    'date' => date('Y-m-d H:i:s')
                ), "id_user='".$user["id_user"]."'",1);

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

                (new Model\Actividad)->registrarActividad('Historial', 'Edición de datos de cuenta', $perfil, $administrador['id_user'], null, date('Y-m-d H:i:s'), 0);

                return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Tus datos se editaron correctamente.', 'urlFoto' => $servidor.$urlFoto, 'nombre' => ucwords(mb_strtolower($nombre, 'UTF-8')));

            }else{
                $u->logout();
            }

        } catch (ModelsException $e) {
            
            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage());

        }

    }


    public function validarCorreoAdmin() {

        try {
            global $http;
            
            $correo = $this->db->scape($http->request->get('valor'));

            if(!Helper\Strings::is_email($correo)){
                throw new ModelsException('El correo electrónico no es válido.');
            }

            $administrador = $this->administradorPor('email', $correo);

            if($administrador){

                $datosAdministrador = $administrador[0];
                $perfilesAsignados = (new Model\Perfiles)->perfilesAdministrador($datosAdministrador['id_user']);

                $perfiles = '';
                $almacenes = '';
                $perfilAlmacenPrincipal = false; // Se define como false, que significa que el perfil de almacen o sucursal no es el principal
                
                if(count($perfilesAsignados) == 1){

                    $perfiles = '<input type="hidden" name="perfil" value="'.$perfilesAsignados[0]["id_perfil"].'">';

                    if($perfilesAsignados[0]["id_perfil"] == 4){

                        $almacenesAsignados = $this->db->select('a.id_almacen, a.almacen, aa.principal', 'almacenes AS a', "INNER JOIN administradores_almacenes AS aa ON a.id_almacen=aa.id_almacen", "aa.id_user='".$datosAdministrador['id_user']."'");
                        if(count($almacenesAsignados) > 1){
                            $almacenes .= '
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fas fa-warehouse"></i>
                                </span>
                                <select class="form-control js-example-placeholder-single js-states seleccionarAlmacen" name="almacen" style="width: 100%;" lang="es">
                                    <optgroup label="Almacenes asignados">';

                            foreach ($almacenesAsignados as $key => $value) {
                                $selected = ($value['principal'] == 1) ? 'selected="selected"' : '';
                                $almacenes .= '<option value="'.$value['id_almacen'].'" '.$selected.'>'.$value['almacen'].'</option>';
                            }

                            $almacenes .= '
                                    </optgroup>
                                </select>
                            </div>';
                        }else{
                            $almacenes .= '<input type="hidden" name="almacen" value="'.$almacenesAsignados[0]['id_almacen'].'">';
                        }

                        $perfilAlmacenPrincipal = true; // El perfil principal de almacen o sucursal si es el principal, cambia el valor de la variable
                    }

                    return array('status' => 'success', 'perfiles' => $perfiles, 'pap' => $perfilAlmacenPrincipal, 'almacenes' => $almacenes);

                }else{

                    $perfiles .= '
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-user-check"></i>
                        </span>
                        <select class="form-control js-example-placeholder-single js-states seleccionarPerfil" name="perfil" style="width: 100%;" lang="es">
                            <optgroup label="Perfiles asignados">';
                    $perfilAlmacen = 0;
                    foreach ($perfilesAsignados as $key => $value) {
                        $selected = ($value['principal'] == 1) ? 'selected="selected"' : '';
                        $perfiles .= '<option value="'.$value['id_perfil'].'" '.$selected.'>'.$value['perfil'].'</option>';
                        if($value['id_perfil'] == 4){
                            $perfilAlmacen = 1;
                        }

                        if($value['id_perfil'] == 4 && $value['principal'] == 1){
                            $perfilAlmacenPrincipal = true; // El perfil principal de almacen o sucursal si es el principal, cambia el valor de la variable
                        }
                    }

                    $perfiles .= '
                            </optgroup>
                        </select>
                    </div>';

                    if($perfilAlmacen == 1){
                        $almacenesAsignados = $this->db->select('a.id_almacen, a.almacen, aa.principal', 'almacenes AS a', "INNER JOIN administradores_almacenes AS aa ON a.id_almacen=aa.id_almacen", "aa.id_user='".$datosAdministrador['id_user']."'");
                        $almacenes .= '
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa-warehouse"></i>
                            </span>
                            <select class="form-control js-example-placeholder-single js-states seleccionarAlmacen" name="almacen" style="width: 100%;" lang="es">
                                <optgroup label="Almacenes asignados">';

                        foreach ($almacenesAsignados as $key => $value) {
                            $selected = ($value['principal'] == 1) ? 'selected="selected"' : '';
                            $almacenes .= '<option value="'.$value['id_almacen'].'" '.$selected.'>'.$value['almacen'].'</option>';
                        }

                        $almacenes .= '
                                </optgroup>
                            </select>
                        </div>';
                    }

                    return array('status' => 'success', 'perfiles' => $perfiles, 'pap' => $perfilAlmacenPrincipal, 'almacenes' => $almacenes);

                }

            }else{
                throw new ModelsException('El correo electrónico no está registrado.');
            }
            
            throw new ModelsException($administrador[0]['name']);

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }

    }

    public function checkSession() {
        
        $administrador = (new Model\Users)->getOwnerUser();
        if(!$administrador){
            return "desconectado";
        }else{
            return "conectado";
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