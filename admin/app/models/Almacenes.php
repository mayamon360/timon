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
 * Modelo Almacenes
 */
class Almacenes extends Models implements IModels {
    use DBModel;

    public function almacenes() {
    	$almacenes = $this->db->select('*', 'almacenes', null, null, null, 'ORDER BY id_almacen DESC');
    	return $almacenes;
    }

    public function almacen($id){
    	$almacen = $this->db->select('*', 'almacenes', null, "id_almacen='$id'");
    	if($almacen){
    		return $almacen[0];
    	}
    	return false;
    }

    /**
     * Determinar si un almacen está asignado a un administrador
     * 
     * @param int $id_user: Id del usuario
     * @param int $id_almacen: Id del almacen
     *
     * @return true|false
    */ 
    public function almacenAsignado($id_user, $id_almacen) {
        $almacenAsignado = $this->db->select("*", 'administradores_almacenes', null, "id_user='$id_user' AND id_almacen='$id_almacen'", 1);
        if($almacenAsignado){
            return true;
        }
        return false;
    }

    public function almacenesAsignados($id_user){
        $almacenesAsignados = $this->db->select('a.id_almacen, a.almacen, aa.principal', 'almacenes AS a', "INNER JOIN administradores_almacenes AS aa ON a.id_almacen=aa.id_almacen", "aa.id_user='".$id_user."'", null, "ORDER BY aa.principal DESC");
        return $almacenesAsignados;
    }

    public function almacenPrincipal($id_user){
        $almacenPrincipal = $this->db->select('a.id_almacen, a.almacen', 'almacenes AS a', "INNER JOIN administradores_almacenes AS aa ON a.id_almacen=aa.id_almacen", "aa.id_user='".$id_user."' AND aa.principal='1'", 1);
        if($almacenPrincipal){
            return $almacenPrincipal[0];
        }
        return false;
    }

    /**
     * perfiles adcionales
     * 
     * @return array
    */ 
    public function almacenesAdcionales() : array {

        global $http;
        $id = intval($http->request->get('almacen'));

        if($id != 0){
            $almacenes = $this->db->select("*", "almacenes", null, "id_almacen != '$id'", null, "ORDER BY almacen ASC");

            if($almacenes){
                $html = '<div class="col-xs-12">
                            <p class="text-muted text-center"><b>Almacenes adicionales</b></p>
                            <hr>
                        </div>';

                foreach ($almacenes as $key => $value) {
                    $html .= '
                        <div class="col-xs-12 col-sm-6" >
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="almacenesAdcionales[]" value="'.$value['id_almacen'].'"> '.$value['almacen'].'
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