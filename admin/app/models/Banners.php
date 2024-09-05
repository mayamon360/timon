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
 * Modelo Banners
 */
class Banners extends Models implements IModels {
    use DBModel;

    /**
     * URL de multimedia
     *
     * @var int
     */
    const URL_ASSETS = '../assets/plantilla/';

    const URL_ASSETS_WEBPAGE = '../../pagina/';

    /**
     * Obtiene los anuncios
     *
     * 
     * @return false|array
    */ 
    public function banners(){
        $banners = $this->db->select('*', 'anuncios', null, null, null, "ORDER BY id DESC");
        return $banners;
    }

    /**
     * Obtiene datos de un anuncio según su id en la base de datos
     * 
     * @param int $id: Id del anuncio a obtener
     *
     * @return false|array[0]
    */  
    public function banner($id) {
        $banner = $this->db->select("*", 'anuncios', null, "id='$id'", 1);
        if($banner){
            return $banner[0];
        }else{
            return false;
        }
    }

    /**
     * Obtiene los anuncios según el valor de un item
     *
     * @param string $item: campo de la tabla
     * @param string $valor: valor a comparar
     * 
     * @return false|array[0]
    */  
    public function bannerPor($item, $valor) {
        $banner = $this->db->select("*", 'anuncios', null, "$item='$valor'", 1);
        if($banner){
            return $banner[0];
        }else{
            return false;
        }
    }



    /**
     * Retorna los datos de los anuncios para ser mostrados en datatables
     * 
     * @return array
    */ 
    public function mostrarBanners() : array {
        global $config;

        # Obtener la url de multimedia
        $servidor = $config['build']['urlAssets'];

        $banners = $this->banners();

        $data = [];
        if($banners){
            foreach ($banners as $key => $value) {
                $infoData = [];
                $infoData[] = '<span class="badge bg-black" style="font-weight:500;">'.$value["id"].'</span>';
                $infoData[] = '<span class="badge bg-purple" style="font-weight:500;">'.$value["ruta"].'</span>';
                $infoData[] = $value["tipo"];
                if($value["estado"] == 1){
                    $infoData[] = $estado = '<span class="estado'.$value["id"].'"><i class="fas fa-toggle-on fa-lg text-aqua estado" value="0" key="'.$value["id"].'" style="cursor:pointer;"></i></span>';
                }else{
                    $infoData[] = $estado = '<span class="estado'.$value["id"].'"><i class="fas fa-toggle-off fa-lg text-muted estado" value="1" key="'.$value["id"].'" style="cursor:pointer;"></i></span>';
                }
                if($value["imagen"] != ""){
                    $infoData[] = '<img class="img-thumbnail" width="100px" src="'.$servidor.$value["imagen"].'?'.time().'">';
                } else {
                    $infoData[] = "";
                }
                if($value["tipo"] == 'inicio'){
                    $infoData[] = $value["titulo"];
                    $infoData[] = $value["subtitulo"];
                    $infoData[] = $value["descripcion"];
                }else{
                    $infoData[] = "--";
                    $infoData[] = "--";
                    $infoData[] = "--";
                }
                if($value["tipo"] == 'inicio'){
                     $infoData[] = "<div class='btn-group'>
                                    <button class='btn btn-sm btn-primary obtenerBanner' key='".$value["id"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt'></i></button>
                                </div>";
                }else{
                    $infoData[] = "<div class='btn-group'>
                                        <button class='btn btn-sm btn-primary obtenerBanner' key='".$value["id"]."' data-toggle='modal' data-target='#modalEditar'><i class='fas fa-pencil-alt'></i></button>
                                    </div>";
                }
                $data[] = $infoData;
            }
        }
        $json_data = [
            "data"   => $data   
        ];
        return $json_data;
    }

    /**
     * Cambia el estado de un anuncio
     *
     * 0:desactivado | 1:activado
     * 
     * @return array
    */ 
    public function estadoBanner() : array {
        try {
            global $http;
            $id = intval($http->request->get('id'));
            $estado = intval($http->request->get('estado'));
            $banner = $this->banner($id);
            if(!$banner){
                throw new ModelsException('El banner no existe.');
            }else{

                if($banner["tipo"] == 'categoria'){
                    $c = new Model\Categorias;
                    $categoria = $c->categoriasPor('ruta', $banner["ruta"]);
                    if($categoria[0]["estado"] == 0){
                        throw new ModelsException("Es necesario activar la categoría ".$categoria[0]["categoria"].'.');
                    }else{
                       $this->db->update('anuncios',array('estado' => $estado),"id='$id'",1); 
                    }
                }elseif($banner["tipo"] == 'subcategoria') {
                    $s = new Model\Subcategorias;
                    $subcategoria = $s->subcategoriasPor('ruta', $banner["ruta"]);
                    if($subcategoria[0]["estado"] == 0){
                        throw new ModelsException("Es necesario activar la subcategoría ".$subcategoria[0]["subcategoria"].'.');
                    }else{
                       $this->db->update('anuncios',array('estado' => $estado),"id='$id'",1); 
                    }
                }else{
                    $this->db->update('anuncios',array('estado' => $estado),"id='$id'",1);
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

            (new Model\Actividad)->registrarActividad('Evento', 'Cambio el estado del banner '.$id.' a '.$estado_txt, $perfil, $administrador['id_user'], 7, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de una categoría para cargarla en el formulario de edición
     * 
     * @return array
    */ 
    public function obtenerBanner() : array {
        try {
            global $http, $config;

            # Obtener la url de multimedia
            $servidor = $config['build']['urlAssets'];

            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get('id'));
            if($metodo != 'obtenerBanner' || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }
            if($id != "" && $id != 0){
                $banner = $this->banner($id);
                if($banner){
                    $formulario = "";
                    $formulario .= '<input type="hidden" name="metodo" value="editar">
                                    <input type="hidden" name="idB" id="idB" value="'.$banner["id"].'">
                                    <input type="hidden" name="tipo" id="tipo" value="'.$banner["tipo"].'">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Tipo de banner</label>
                                                <input type="text" class="form-control" disabled value="'.$banner["tipo"].'">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Ruta de banner</label>
                                                <input type="text" class="form-control" disabled value="'.$banner["ruta"].'">
                                            </div>
                                        </div>
                                    </div>';
                        if($banner["tipo"] == 'inicio'){
                            $formulario .= '<div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="titulo">Título</label>
                                                    <input type="text" id="titulo" name="titulo" class="form-control" value="'.$banner["titulo"].'">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="subtitulo">Subtítulo</label>
                                                    <input type="text" id="subtitulo" name="subtitulo" class="form-control" value="'.$banner["subtitulo"].'">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="descripcion">Descripción</label>
                                                    <input type="text" id="descripcion" name="descripcion" class="form-control" value="'.$banner["descripcion"].'">
                                                </div>
                                            </div>
                                        </div>';
                        }
                        $formulario .= '
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="text-center">
                                                    <label>Imagen</label>
                                                    <div>
                                                        <label for="eImagen" class="btn btn-sm btn-default btn-block"><i class="fa fa-upload"></i> Selecciona una imagen</label>
                                                        <input type="file" name="eImagen" id="eImagen" style="visibility:hidden;" accept="image/*">
                                                    </div>
                                                    <p class="help-block" style="margin-top:-20px;">Tamaño recomendado <br>1600px * 550px, peso máximo 2MB</p>
                                                    <p><img src="'.$servidor.$banner["imagen"].'" class="img-thumbnail ePrevisualizarImagen" style="width:100%; max-width:100%; height:auto"></p>
                                                </div>
                                            </div>
                                        </div>';
                    return array('status' => 'success', 'formulario' => $formulario);
                }else{
                    throw new ModelsException('El banner no existe.');
                }
            }else{
                throw new ModelsException('La petición no se puede procesar (id nulo).');
            }
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());  
        }
    }

    /**
     * Respuesta generada por defecto para el endpoint
     * 
     * @return int
    */ 
    public function editarBanner() : array {
        try {
            global $http;
            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get("idB"));
            $tipo = $http->request->get("tipo");
            $imagen = $http->files->get('eImagen');
            if($metodo != "editar" || $metodo == null){
                throw new ModelsException('El método no está definido.');
            }
            $banner = $this->banner($id);
            if($tipo == 'inicio' || $id == 1){
                $titulo = $this->db->scape($http->request->get("titulo"));
                if($titulo == ""){
                    throw new ModelsException('El título del banner está vacío.');
                }else{
                    $titulo = ucfirst(mb_strtolower($titulo, 'UTF-8'));
                }
                $subtitulo = $this->db->scape($http->request->get("subtitulo"));
                if($subtitulo == ""){
                    throw new ModelsException('El subtítulo del banner está vacío.');
                }else{
                    $subtitulo = ucfirst(mb_strtolower($subtitulo, 'UTF-8'));
                }
                $descripcion = $this->db->scape($http->request->get("descripcion"));
                if($descripcion == ""){
                    throw new ModelsException('El descripción del banner está vacía.');
                }
            }else{
                $titulo = "";
                $subtitulo = "";
                $descripcion = "";
            }
            if($imagen == null) {
                $urlImagen = $banner["imagen"];
            }else{
                /* Validar la extensión de la imagen */
                if(!Helper\Files::is_image($imagen->getClientOriginalName())){
                    throw new ModelsException('El formato de la imagen no es válido.');
                /* Validar el tamaño de la imagen*/    
                } else if($imagen->getClientSize()>2000000){
                    throw new ModelsException('El tamaño de la imagen superara los 2 Mb.');
                } else {
                    list($ancho, $alto) = getimagesize($imagen->getRealPath());
                    $rutaArchivoAnterior = '../assets/plantilla/'.$banner["imagen"];
                    $nuevoAncho = 1600;
                    $nuevoAlto = 550;
                    $nombreArchivo = "vistas/img/banner/".$banner["ruta"]."";
                    /* Eliminar imagen anterior */
                    if($banner["imagen"] != "vistas/img/banner/default/default.jpg"){
                        unlink($rutaArchivoAnterior);
                    }
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    // Si la imagen es jpg 
                    if ($imagen->getMimeType() == "image/jpeg") {
                        
                        //$ruta = self::URL_ASSETS.$nombreArchivo.'.jpg';
                        $ruta = self::URL_ASSETS_WEBPAGE.$nombreArchivo.'.jpg';

                        $urlImagen = $nombreArchivo.'.jpg';
                        $origen = imagecreatefromjpeg($imagen->getRealPath());
                        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta, 100);
                    // Si la imagen es png
                    } else if($imagen->getMimeType() == "image/png") {
                        
                        //$ruta = self::URL_ASSETS.$nombreArchivo.'.png';
                        $ruta = self::URL_ASSETS_WEBPAGE.$nombreArchivo.'.png';

                        $urlImagen = $nombreArchivo.'.png';
                        $origen = imagecreatefrompng($imagen->getRealPath());
                        // Conservar transparencias
                        imagealphablending($destino, FALSE);
                        imagesavealpha($destino, TRUE);
                        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta, 9);    
                    }
                }
            }
            $this->db->update('anuncios', array(
                'imagen' => $urlImagen,
                'titulo' => $titulo,
                'subtitulo' => $subtitulo,
                'descripcion' => $descripcion
            ), "id='$id'");

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

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de banner '.$id, $perfil, $administrador['id_user'], 7, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El banner '.$id.' se editó correctamente.');
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