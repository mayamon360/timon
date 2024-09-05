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
 * Modelo Datos
 */
class Datos extends Models implements IModels {
    use DBModel;

    /**
     * Obtiene datos de una categoria según condiciones en la base de datos
     *
     * @param int $id: Id de la categoria a obtener
     * 
     * @return false|array[0]
    */  
    public function categoriaPor($where) {
        $stmt = $this->db->select("*", "categorias", null, $where, 1);
        if($stmt){
            return $stmt[0];
        }else{
            return false;
        } 
    }

    /**
     * Obtiene datos de una categoria según condiciones en la base de datos
     *
     * @param int $id: Id de la categoria a obtener
     * 
     * @return false|array[0]
    */  
    public function subcategoriaPor($where) {
        $stmt = $this->db->select("*", "subcategorias", null, $where, 1);
        if($stmt){
            return $stmt[0];
        }else{
            return false;
        } 
    }
    
    /**
     * Obtiene las cabeceras
     *
     * @return false|array con las cabeceras
     */   
    public function cabeceras($ruta) {
    	if($ruta == null || $ruta == ''){
    	    $ruta = 'inicio';
    	}
        $stmt = $this->db->select('*','cabeceras',null,"ruta = '$ruta'");
        if($stmt){
            return $stmt[0];
        }
        return false;
    }

    /**
     * Obtiene el logotipo para header y footer
     *
     * @return false|array con las rutas de logotipo
     */   
    public function logotipos() {
        $stmt = $this->db->select('logotipo_header,logotipo_footer','plantilla');
        if($stmt){
            return $stmt[0];
        }
        return false;
    }

    /**
     * Obtiene nombre y datos de contacto
     *
     * @return false|array con la información
     */   
    public function datosGenerales() {
        $stmt = $this->db->select('nombre, direccion, telefono_contacto, correo_contacto','comercio');
        if($stmt){
            return $stmt[0];
        }
        return false;
    }

    /**
     * Obtiene colores de la Plantilla
     *
     * @return false|array con los colores
     */   
    public function coloresPlantilla() {
        $stmt = $this->db->select('appColor1, appColor2, appColor3, appColor4','plantilla');
        if($stmt){
            return $stmt[0];
        }
        return false;
    }

    /**
     * Obtiene las redes sociales
     *
     * @return false|array con información de las redes sociales en formato json
     */   
    public function redesSociales() {
        $stmt = $this->db->select('appRedesSociales','plantilla');
        if($stmt){
            return json_decode($stmt[0]['appRedesSociales'],true);
        }
        return false;
    }

    /**
     * Obtiene las categorías
     *
     * @return false|array con información de las categorias
     */   
    public function categorias() {
        $stmt = $this->db->select('*','categorias', null, "estado=1");
        if($stmt){
            return $stmt;
        }
        return false;
    }

    /**
     * Obtiene las subcategorías
     *
     * @return false|array con información de las categorias
     */   
    public function subcategorias() {
        $stmt = $this->db->select('*','subcategorias', null, "estado=1");
        if($stmt){
            return $stmt;
        }
        return false;
    }

    public function categoriasHtml(int $id_categoria, $como_enlace = false){
        global $config;
        $stmt = $this->categoriaPor("id = $id_categoria");
        if($como_enlace){
            return '<a class="d-inline pr-1 pixelCategoria enlace_negro" href="'.$config['build']['url'].'libros/'.$stmt['ruta'].'/'.$stmt['id'].'">
                <i class="fas fa-angle-right mr-1 infinite"></i> '.$stmt['categoria'].'
            </a>';
        }
        return $stmt['categoria'];
    }

    public function subcategoriasHtml(int $id_categoria, int $id_subcategoria, $como_enlace = false){
        global $config;
        $stmt = $this->subcategoriaPor("id = $id_subcategoria AND id_categoria = $id_categoria");
        if($como_enlace){
            return '<a class="d-inline text-truncate ml-1 pixelSubcategoria" href="'.$config['build']['url'].'libros/'.$stmt['ruta'].'/'.$stmt['id'].'">
                '.$stmt['subcategoria'].'
            </a>';
        }
        return $stmt['subcategoria'];
    }

    public function usuarios(){
        return $this->db->select("*", "administradores");
    }

    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}