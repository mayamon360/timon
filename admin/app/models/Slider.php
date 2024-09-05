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
 * Modelo Slider
 */
class Slider extends Models implements IModels {
    use DBModel;

    /**
     * URL de multimedia
     *
     * @var int
     */
    const URL_ASSETS = '../assets/plantilla/';

    const URL_ASSETS_WEBPAGE = '../../pagina/';

    /**
     * Obtiene los registros en la tabla carrusel
     * 
     * @return array
    */ 
    public function datosSliders() : array {
        $sliders = $this->db->select('*', 'carrusel', null, null, null, 'ORDER BY orden ASC');
        return $sliders;
    }

    /**
     * Obtiene el registro en la tabla carrusel por el id
     * 
     * @return array
    */ 
    public function datoSlider($idSlider) : array {
        $slider = $this->db->select('*', 'carrusel', null, "id='$idSlider'", 1);
        return $slider[0];
    }

    /**
     * Agrega un nuevo slide y retorna sus valores
     * 
     * @return array
    */ 
    public function crearSlider() : array {

        $tipoSlide = "sliderOpcion1";

        $vinculo = "#";
        $colorMascara = "hm-black-light";
        $fondo = "vistas/img/carrusel/default/background_default.jpg";

        $alineacionContenido = "SlideIzquierdaCentro";
        $alineacionTexto = "text-left";

        $claseTitulo = "fadeInLeft";
        $estiloTitulo = '{"background": "rgba(0,0,0,.3)", "color": "var(--white)"}';
        $titulo = "Lorem ipsum";

        $claseSubtitulo = "fadeInRight";
        $estiloSubtitulo = '{"background": "var(--yellow)", "color": "var(--dark)"}';
        $subtitulo = "Lorem ipsum dolor";

        $claseDescripcion = "fadeInUpBig";
        $estiloDescripcion = '{"background": "rgba(255,255,255,.3)", "color": "var(--white)"}';
        $descripcion = "Lorem ipsum dolor sit amet, consectetur.";

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            if($metodo != 'crearSlider' || $metodo == null) {

                throw new ModelsException('El método no está definido.');

            }

            # Obtener los sliders para saber cual es el último

            $sliders = $this->datosSliders();

            foreach ($sliders as $key => $value) {
                $orden = $value["orden"];
            }

            $orden = $orden + 1;

            # Registrar al usuario
            $insertar = $this->db->insert('carrusel', array(
                'nombre' => '',
                'tipoSlide' => $tipoSlide,
                'vinculo' => $vinculo,
                'colorMascara' => $colorMascara,
                'fondo' => $fondo,
                'alineacionContenido' => $alineacionContenido,
                'alineacionTexto' => $alineacionTexto,
                'titulo' => $titulo,
                'subtitulo' => $subtitulo,
                'descripcion' => $descripcion,
                'claseTitulo' => $claseTitulo,
                'claseSubtitulo' => $claseSubtitulo,
                'claseDescripcion' => $claseDescripcion,
                'estiloTitulo' => $estiloTitulo,
                'estiloSubtitulo' => $estiloSubtitulo,
                'estiloDescripcion' => $estiloDescripcion,
                'orden' => $orden
            ));

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

            (new Model\Actividad)->registrarActividad('Evento', 'Adición de slider '.$insertar, $perfil, $administrador['id_user'], 2, date('Y-m-d H:i:s'), 0);
            
            return array('status' => 'success', 'title' => '!ATENCIÓN¡', 'message' => 'El slider se agregó correctamente. Es necesario editar su contenido para que se pueda visualizar en la página web.');

        } catch (ModelsException $e) {
            
            return array('status' => 'Error', 'message' => $e->getMessage());

        }

    }

    /**
     * Ordenar slider
     * 
     * @return array
    */ 
    public function ordenarSlider() : array {

        try {

            global $http;

            $metodo = $http->request->get('metodo');

            $idSlider = $this->db->scape($http->request->get('idSlider'));
            $idSlider = intval(str_replace("itemSlide", "", $idSlider)); 
            $orden = $this->db->scape($http->request->get('orden'));

            if($metodo != 'ordenarSlider' || $metodo == null) {

                throw new ModelsException('El método no está definido.');

            }

            # Actualizar orden
            $this->db->update('carrusel',array(
                'orden' => $orden,
            ),"id='$idSlider'",1);

            # Para solo registrar una vez
            if($orden == 1){
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

                (new Model\Actividad)->registrarActividad('Evento', 'Cambio en el orden de sliders', $perfil, $administrador['id_user'], 2, date('Y-m-d H:i:s'), 0);
            }

            return array('status' => 'Success', 'message' => 'id: '.$idSlider.' orden: '.$orden);
            
        } catch (ModelsException $e) {

            return array('status' => 'Error', 'message' => $e->getMessage());
            
        }
    
    }

    /**
     * Actualizar slider
     * 
     * @return array
    */ 
    public function actualizarSlider() : array {

        try { 

            global $http;

            // Recibir valores enviados desde el formulario
            $metodo = $http->request->get('metodo');
            $id = intval($http->request->get('id'));
            $nombre = $this->db->scape($http->request->get('nombreSlider'));
            $vinculo = $this->db->scape($http->request->get('vinculoSlider'));
            $tipo = $this->db->scape($http->request->get('tipoSlider'));
            $fondo = $http->files->get('fondo');
            $imagen = $http->files->get('imagen');

            $titulo = $this->db->scape($http->request->get('titulo'));
            $estiloTitulo = '{"background": "'.$this->db->scape($http->request->get('fondoT')).'", "color": "'.$this->db->scape($http->request->get('colorT')).'"}';

            $subtitulo = $this->db->scape($http->request->get('subtitulo'));
            $estiloSubtitulo = '{"background": "'.$this->db->scape($http->request->get('fondoS')).'", "color": "'.$this->db->scape($http->request->get('colorS')).'"}';

            $descripcion = $this->db->scape($http->request->get('descripcion'));
            $estiloDescripcion = '{"background": "'.$this->db->scape($http->request->get('fondoD')).'", "color": "'.$this->db->scape($http->request->get('colorD')).'"}';

            $anchoSlider = intval($http->request->get('ancho'));

            // Traer dato del slider por id
            $slider = $this->datoSlider($id);

            /* Validar método */
            if($metodo != 'actualizarSlider' || $metodo == null) {

                throw new ModelsException('El método no está definido.');

            }

            /* Validar vinculo */
            if($vinculo != ""){

                if($vinculo == "nulo"){

                    $vinculo = "";

                }else{

                    if(!filter_var($vinculo, FILTER_VALIDATE_URL)){

                        throw new ModelsException('El enlace no es válido.');

                    }

                }

            }else{

                $vinculo = $slider["vinculo"];

            }

            /* Validar tipo */
            switch ($tipo) {
                case 'sliderOpcion1':
                    $alineacionContenido = "SlideIzquierdaCentro";
                    $alineacionTexto = "text-left";
                    $claseTitulo = "fadeInLeft";
                    $claseSubtitulo = "fadeInRight";
                    $claseDescripción = "fadeInUpBig";
                    $alineacionImagen = "SlideDerechaCentroImg";
                    $direccionImagen = "float-right";
                    $claseImagen = "zoomIn";
                    break;

                case 'sliderOpcion2':
                    $alineacionContenido = "SlideDerechaCentro";
                    $alineacionTexto = "text-right";
                    $claseTitulo = "fadeInRight";
                    $claseSubtitulo = "fadeInLeft";
                    $claseDescripción = "fadeInUpBig";
                    $alineacionImagen = "SlideIzquierdaCentroImg";
                    $direccionImagen = "float-left";
                    $claseImagen = "zoomIn";
                    break;

                case 'sliderOpcion3':
                    $alineacionContenido = "";
                    $alineacionTexto = "";
                    $claseTitulo = "";
                    $claseSubtitulo = "";
                    $claseDescripción = "";
                    $alineacionImagen = "";
                    $direccionImagen = "";
                    $claseImagen = "";
                    break;

                case 'sliderOpcion4':
                    $alineacionContenido = "SlideCentroTop";
                    $alineacionTexto = "text-center";
                    $claseTitulo = "zoomInDown";
                    $claseSubtitulo = "zoomInUp";
                    $claseDescripción = "fadeInUpBig";
                    $alineacionImagen = "SlideCentroBottomImg";
                    $direccionImagen = "float-none";
                    $claseImagen = "zoomIn";
                    break;

                case 'sliderOpcion5':
                    $alineacionContenido = "SlideDerechaTop";
                    $alineacionTexto = "text-right";
                    $claseTitulo = "fadeInDown";
                    $claseSubtitulo = "zoomInLeft";
                    $claseDescripción = "zoomInRight";
                    $alineacionImagen = "SlideDerechaBottomImg";
                    $direccionImagen = "float-right";
                    $claseImagen = "zoomIn";
                    break;

                case 'sliderOpcion6':
                    $alineacionContenido = "SlideIzquierdaTop";
                    $alineacionTexto = "text-left";
                    $claseTitulo = "fadeInDown";
                    $claseSubtitulo = "zoomInRight";
                    $claseDescripción = "zoomInLeft";
                    $alineacionImagen = "SlideIzquierdaBottomImg";
                    $direccionImagen = "float-left";
                    $claseImagen = "zoomIn";
                    break;

                default:
                    throw new ModelsException('Seleccione un tipo de slider.');
                    break;

            }

            /* Validar imagen de fondo */
            if($fondo == null) {

                $urlFondo = $slider["fondo"];

            }else{

                /* Validar la extensión de la imagen */
                if(!Helper\Files::is_image($fondo->getClientOriginalName())){

                    throw new ModelsException('El formato de la imagen no es válido.');

                /* Validar el tamaño de la imagen*/    
                } else if($fondo->getClientSize()>2000000){

                    throw new ModelsException('El tamaño de la imagen superara los 2 Mb.');

                } else {

                    list($ancho, $alto) = getimagesize($fondo->getRealPath());

                        //$rutaArchivoAnterior = self::URL_ASSETS.$slider["fondo"];
                        $rutaArchivoAnterior = self::URL_ASSETS_WEBPAGE.$slider["fondo"];

                    $nuevoAncho = 1600;
                    $nuevoAlto = 707;
                    $nombreArchivo = "vistas/img/carrusel/slide$id/slide$id";

                    /* Eliminar imagen anterior */
                    if($rutaArchivoAnterior != "vistas/img/carrusel/default/background_default.jpg"){

                        unlink($rutaArchivoAnterior);

                    }

                    /* Creamos directorio si no existe */
                    //$directorio = self::URL_ASSETS."vistas/img/carrusel/slide$id";
                    $directorio = self::URL_ASSETS_WEBPAGE."vistas/img/carrusel/slide$id";

                    if(!file_exists($directorio)){

                        mkdir($directorio, 0755);

                    }

                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

                    // Si la imagen es jpg 
                    if ($fondo->getMimeType() == "image/jpeg") {

                        //$ruta = self::URL_ASSETS.$nombreArchivo.'.jpg';
                        $ruta = self::URL_ASSETS_WEBPAGE.$nombreArchivo.'.jpg';

                        $urlFondo = $nombreArchivo.'.jpg';

                        $origen = imagecreatefromjpeg($fondo->getRealPath());

                        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

                        imagejpeg($destino, $ruta, 100);

                    // Si la imagen es png
                    } else if($fondo->getMimeType() == "image/png") {

                        //$ruta = self::URL_ASSETS.$nombreArchivo.'.png';
                        $ruta = self::URL_ASSETS_WEBPAGE.$nombreArchivo.'.png';

                        $urlFondo = $nombreArchivo.'.png';

                        $origen = imagecreatefrompng($fondo->getRealPath());

                        // Conservar transparencias

                        imagealphablending($destino, FALSE);

                        imagesavealpha($destino, TRUE);

                        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

                        imagepng($destino, $ruta, 9);    

                    }

                }

            }

            /* Validar imagen adicional */
            if($imagen == null) {

                $urlImagen = $slider["imagen"];

            }else{

                /* Validar la extensión de la imagen adicional */
                if(!Helper\Files::is_image($imagen->getClientOriginalName())){

                    throw new ModelsException('El formato de la imagen no es válido.');

                /* Validar el tamaño de la imagen adicional */    
                } else if($imagen->getClientSize()>2000000){

                    throw new ModelsException('El tamaño de la imagen superara los 2 Mb.');

                } else {

                    list($ancho, $alto) = getimagesize($imagen->getRealPath());

                    //$rutaArchivoAnterior = self::URL_ASSETS.$slider["imagen"];
                    $rutaArchivoAnterior = self::URL_ASSETS_WEBPAGE.$slider["imagen"];

                    $nuevoAncho = $anchoSlider;
                    $nuevoAlto = ($alto * $nuevoAncho) / $ancho;
                    $nombreArchivo = "vistas/img/carrusel/slide$id/imagen";

                    /* Eliminar imagen anterior */
                    if($rutaArchivoAnterior != ""){

                        unlink($rutaArchivoAnterior);

                    }

                    /* Creamos directorio si no existe */
                    //$directorio = self::URL_ASSETS."vistas/img/carrusel/slide$id";
                    $directorio = self::URL_ASSETS_WEBPAGE."vistas/img/carrusel/slide$id";

                    if(!file_exists($directorio)){

                        mkdir($directorio, 0755);

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

            if($tipo == 'sliderOpcion3'){

                $urlImagen = "";

                $titulo = "";
                $estiloTitulo = "";

                $subtitulo = "";
                $estiloSubtitulo = "";

                $descripcion = "";
                $estiloDescripcion = "";

                $anchoSlider = "";

            }

            $this->db->update('carrusel',array(
                'nombre' => $nombre,
                'vinculo' => $vinculo,
                'estatus' => 1,
                'tipoSlide' => $tipo,
                'fondo' => $urlFondo,
                'imagen' => $urlImagen,
                'alineacionContenido' => $alineacionContenido,
                'alineacionTexto' => $alineacionTexto,
                'alineacionImagen' => $alineacionImagen,
                'direccionImagen' => $direccionImagen,
                'titulo' => $titulo,
                'estiloTitulo' => $estiloTitulo,
                'subtitulo' => $subtitulo,
                'estiloSubtitulo' => $estiloSubtitulo,
                'descripcion' => $descripcion,
                'estiloDescripcion' => $estiloDescripcion,
                'anchoImagen' => $anchoSlider
            ),"id='$id'",1);

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

            (new Model\Actividad)->registrarActividad('Evento', 'Edición de slider '.$id, $perfil, $administrador['id_user'], 2, date('Y-m-d H:i:s'), 0);

            return array('status' => 'success', 'id' => $id, 'title' => '¡Bien hecho!', 'message' => 'El slider se actualizó correctamente.', 'url' => $this->db->scape($http->request->get('vinculoSlider')));

        } catch (ModelsException $e) {

            return array('status' => 'error', 'title' => '¡Oops!', 'message' => $e->getMessage(), 'id' => $id);
            
        }

    }

    /**
     * Elimina el registro de un slide por el id
     * 
     * @return array
    */ 
    public function eliminarSlider() : array {

        try {

            global $http;

            $id = intval($http->request->get('idSlider'));

            // Traer dato del slider por id
            $slider = $this->datoSlider($id);

            if(!$slider){

                throw new ModelsException('El slider no existe.');

            }else{

                //$rutaFondoAnterior = self::URL_ASSETS.$slider["fondo"];
                //$rutaImagenAnterior = self::URL_ASSETS.$slider["imagen"];
                $rutaFondoAnterior = self::URL_ASSETS_WEBPAGE.$slider["fondo"];
                $rutaImagenAnterior = self::URL_ASSETS_WEBPAGE.$slider["imagen"];

                /* Eliminar fondo */
                if($slider["fondo"] != "vistas/img/carrusel/default/background_default.jpg"){

                    unlink($rutaFondoAnterior);

                }

                /* Eliminar imagen */
                if($slider["imagen"] != ""){

                    unlink($rutaImagenAnterior);

                }

                /* Eliminamos directorio si existe */
                //$directorio = self::URL_ASSETS."vistas/img/carrusel/slide$id";
                $directorio = self::URL_ASSETS_WEBPAGE."vistas/img/carrusel/slide$id";

                if(file_exists($directorio)){

                    //rmdir(self::URL_ASSETS.'/vistas/img/carrusel/slide'.$slider["id"].'');
                    rmdir(self::URL_ASSETS_WEBPAGE.'/vistas/img/carrusel/slide'.$slider["id"].'');

                }

                $eliminarSlider = $this->db->delete('carrusel', "id='$id'", 1);

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

                (new Model\Actividad)->registrarActividad('Evento', 'Eliminación de slider '.$id, $perfil, $administrador['id_user'], 2, date('Y-m-d H:i:s'), 0);

                return array('status' => 'success', 'title' => '¡Bien hecho!','message' => 'El slider ha sido eliminado correctamente.');

            }
            
        } catch (Exception $e) {
            
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