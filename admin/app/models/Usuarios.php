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
 * Modelo Usuarios
 */
class Usuarios extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene todos los usuarios 
     * 
     * @return false|array
    */ 
    public function usuarios() {
        $usuarios = $this->db->select("id, nombre, correoElectronico, modo, foto, verificacion, fechaRegistro, estatus", "usuarios", null, null, null, "ORDER BY id DESC");
        return $usuarios;
    }

    /**
     * Obtiene datos de un usuario según su id en la base de datos
     *
     * @param int $id: Id del usuario a obtener
     * 
     * @return false|array[0]
    */ 
    public function usuario($id) {
        $usuario = $this->db->select("*", 'usuarios', null, "id='$id'", 1);
        if($usuario){
            return $usuario[0];
        }else{
            return false;
        }
    }

    /**
     * Retorna los datos de los usuarios para ser mostrados en datatables
     * 
     * @return array
    */  
    public function mostrarUsuarios() : array {
        global $config;

        # Obtener la url de multimedia
        $urlPagina = $config['build']['urlAssetsPagina'];

        $usuarios = $this->usuarios();
        $data = [];
        if($usuarios){
            foreach ($usuarios as $key => $value) {
                $infoData = [];
                $infoData[] = '<span class="badge bg-black" style="font-weight:500;">'.$value["id"].'</span>';

                if($value["modo"] == 'directo'){
                    $infoData[] = '<img class="img-circle" width="30px" src="'.$urlPagina.$value["foto"].'?'.time().'">';
                }else{
                    $infoData[] = '<img class="img-circle" width="30px" src="'.$value["foto"].'">';
                }

                $infoData[] = '<span class="badge bg-purple" style="font-weight:500;">'.$value["nombre"].'</span>';
                $infoData[] = $value["correoElectronico"];
                $infoData[] = $value["modo"];
                if($value["modo"] != 'directo'){
                    $infoData[] = '<span class="badge bg-grey">N/A</span>';
                }else{
                    if($value["verificacion"] == 0){
                        $infoData[] = '<span class="badge bg-green">SÍ</span>';
                    }else{
                        $infoData[] = '<span class="badge bg-red">NO</span>';
                    }
                }

                if($value["estatus"] == 1){
                    $infoData[] = $estado = '<span class="estado'.$value["id"].'"><i class="fas fa-toggle-on fa-lg text-aqua estado" value="0" key="'.$value["id"].'" style="cursor:pointer;"></i></span>';
                }else{
                    $infoData[] = $estado = '<span class="estado'.$value["id"].'"><i class="fas fa-toggle-off fa-lg text-muted estado" value="1" key="'.$value["id"].'" style="cursor:pointer;"></i></span>';
                }

                $infoData[] = Helper\Functions::fecha($value["fechaRegistro"]);
                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;

    }

    /**
     * Cambia el estado de una categoría, afectando a subcategorías, productos y banner asociados a está
     *
     * 0:desactivado | 1:activado
     * 
     * @return array
    */ 
    public function estadoUsuario() : array {
        try {
            global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $usuario = $this->usuario($id);
            if(!$usuario){
                throw new ModelsException('El usuario no existe.');
            }else{

                if($usuario["modo"] == 'directo'){
                    //$verificacion = ($estado == 1) ? 0 : 1;
                $this->db->update('usuarios',array('estatus' => $estado/*,'verificacion' => $verificacion*/),"id='$id'");
                }else{
                    $this->db->update('usuarios',array('estatus' => $estado),"id='$id'");
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

            if($estado == 1){
                $estado_txt = 'activo';
            }else{
                $estado_txt = 'inactivo';
            }

            (new Model\Actividad)->registrarActividad('Evento', 'Cambio de estado del usuario web '.$id.' - '.$usuario['nombre'].' a '.$estado_txt, $perfil, $administrador['id_user'], 12, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }


    public function correos() {
        $nombre = "Correo de usuarios.xls"; 
        $usuarios = $this->usuarios();

        if($usuarios){
            header('Expires: 0');
            header('Cache-control: private');
            header('Content-type: application/vnd.ms-excel'); //Archivo excel
            header('Cache-Control: cache, must-revalidate');
            header('Content-Description: File Transfer');
            header('Last-Modified: '.date('D, d M Y H:i:s'));
            header('Pragma: public');
            header('Content-Disposition: attachment; filename="'.$nombre.'"');
            header('Content-Transfer-Encoding: binary');
            $html = "";
            $html .= "
                <table border='0'>
                    <tr>
                        <td style='font-weight:bold; border:1px solid #eee; border-bottom:2px solid #777;'>Correo</td>
                    </tr>";
            $cont = 1;
            foreach ($usuarios as $key => $value) {

                $backColor = ($cont%2==0) ? 'background-color:#f7f7f7;' : 'background-color:#fff;';

                if($value['modo'] == 'directo'){
                    if($value['verificacion'] == 0){
                        $html .= "
                        <tr>
                            <td style='border:1px solid #eee; ".$backColor."'>".$value['correoElectronico']."</td>
                        </tr>
                        "; 
                    }
                }else{
                   $html .= "
                    <tr>
                        <td style='border:1px solid #eee; ".$backColor."'>".$value['correoElectronico']."</td>
                    </tr>
                    "; 
                }

                $cont++;
            }

            $html .= '</table>';
            echo utf8_decode($html);
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