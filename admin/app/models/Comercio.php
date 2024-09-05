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
 * Modelo Comercio
 */
class Comercio extends Models implements IModels {

    use DBModel;

    /**
     * Obtiene la lista de paises
     *    
     *
     * @return string
     */ 
    public function cargarPaises() {

        $paises = $this->db->select('codigo,pais', 'paises', null, "estado=1");
        $paises = json_encode($paises);
        return $paises;

    }

    /**
     * Obtiene los datos del comercio
     *    
     *
     * @return false|array con datos del comercio
     */ 
    public function datosComercio() : array {

        $datosComercio = $this->db->select('*', 'comercio');
        return $datosComercio[0];

    }

    /**
     * Obtiene los datos de la plantilla
     *    
     *
     * @return false|array con datos de la plantilla
     */ 
    public function datosPlantilla() : array {

        $datosPlantilla = $this->db->select('*', 'plantilla');
        return $datosPlantilla[0];

    }

    /**
     * Respuesta generada al cambiar datos del comercio
     *    
     *
     * @return array
     */ 
    public function cambiarDatos() {

        try {

            global $http, $router;

            $metodo = $http->request->get('metodo');        /* Método */

            $nombre = $this->db->scape($http->request->get('nombre'));
            $direccion = $this->db->scape($http->request->get('direccion'));
            $telefono = $this->db->scape($http->request->get('telefono'));
            $correo = $this->db->scape($http->request->get('correo'));

            /* Validar si se recibió método */
            if($metodo != null) {

                /* Validar si el método corresponde a los permitidos*/
                if($metodo != 'cambiarDatos') {

                    throw new ModelsException('El método no está definido.');

                } else {

                    # Verificar que no están vacíos
                    if (Helper\Functions::e($nombre, $direccion, $telefono, $correo)) {
                        throw new ModelsException('Verifique los datos del formulario.');
                    }

                    # Formato de email
                    if (!Helper\Strings::is_email($correo)) {
                        throw new ModelsException('El correo electrónico no es válido.');
                    }

                    # Actualizar colores
                    $this->db->update('plantilla',array(
                        'nombre' => $nombre,
                        'direccion' => $direccion,
                        'telefono' => $telefono,
                        'correoElectronico' => $correo
                    ),"id='1'",1);

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

                    (new Model\Actividad)->registrarActividad('Evento', 'Edición de datos de contacto', $perfil, $administrador['id_user'], 1, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'DATOS GENERALES Y DE CONTACTO se actualizarón correctamente.');   

                }

            } else {

                throw new ModelsException('La petición no se puede procesar (método nulo).');

            }


        } catch (ModelsException $e) {

            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
            
        }

    }

    /**
     * Respuesta generada al cambiar logotipo o icono
     * 
     * @return array
    */ 
    public function cambiarLogo() : array {

        try {

            global $http;

            $metodo = $http->request->get('metodo');        /* Método */
            $logotipo = $http->files->get('imagen');        /* Imagen */

            /* Validar si se recibió método */
            if($metodo != null){

                /* Validar si el método corresponde a los permitidos*/
                if($metodo != 'cambiarLogo' && $metodo != 'cambiarIcono') {

                    throw new ModelsException('El método no está definido.');

                } else {

                    /* Validar que la imagen se haya recibido */
                    if($logotipo == null) {

                        throw new ModelsException('La petición no se puede procesar (imagen nula).');

                    } else {

                        /* Validar la extensión de la imagen */
                        if(!Helper\Files::is_image($logotipo->getClientOriginalName())){

                            throw new ModelsException('El formato de la imagen no es válido.');

                        /* Validar el tamaño de la imagen*/    
                        } else if($logotipo->getClientSize()>2000000){

                            throw new ModelsException('El tamaño de la imagen superara los 2 Mb.');

                        } else {

                            $datosPlantilla = $this->datosPlantilla();

                            /* Modificar tamaño de imagen */
                            list($ancho, $alto) = getimagesize($logotipo->getRealPath());

                            /* Crear la ruta de la imagen anterior */
                            if($metodo == 'cambiarLogo') {

                                $rutaArchivoAnterior = '../assets/plantilla/'.$datosPlantilla["logotipo"];
                                $nuevoAncho = 170;
                                $nuevoAlto = 70;
                                $nombreArchivo = "vistas/img/plantilla/logotipo";

                                $item = 'logotipo';

                            } else if($metodo == 'cambiarIcono') {

                                $rutaArchivoAnterior = '../assets/plantilla/'.$datosPlantilla["icono"];
                                $nuevoAncho = 50;
                                $nuevoAlto = 50;
                                $nombreArchivo = "vistas/img/plantilla/icono";

                                $item = 'icono';

                            }

                            /* Eliminar imagen anterior */
                            unlink($rutaArchivoAnterior);


                            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

                            // Si la imagen es jpg 
                            if ($logotipo->getMimeType() == "image/jpeg") {

                                $ruta = '../assets/plantilla/'.$nombreArchivo.'.jpg';

                                $rutaBD = $nombreArchivo.'.jpg';

                                $origen = imagecreatefromjpeg($logotipo->getRealPath());

                                imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

                                imagejpeg($destino, $ruta, 100);

                            // Si la imagen es png
                            } else if($logotipo->getMimeType() == "image/png") {

                                $ruta = '../assets/plantilla/'.$nombreArchivo.'.png';

                                $rutaBD = $nombreArchivo.'.png';

                                $origen = imagecreatefrompng($logotipo->getRealPath());

                                // Conservar transparencias

                                imagealphablending($destino, FALSE);

                                imagesavealpha($destino, TRUE);

                                imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

                                imagepng($destino, $ruta, 9);    

                            }

                            # Actualizar datos 
                            $this->db->update('plantilla',array("$item" => "$rutaBD"), "id='1'",1);

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

                            (new Model\Actividad)->registrarActividad('Evento', 'Edición de '.$item, $perfil, $administrador['id_user'], 1, date('Y-m-d H:i:s'), 0);

                            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'El '.$item.' se guardó correctamente.');

                        }

                    }

                }

            } else {

                throw new ModelsException('La petición no se puede procesar (método nulo).');

            }   


        } catch(ModelsException $e) {

            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());

        }

    }

    /**
     * Respuesta generada al cambiar colores
     *    
     *
     * @return array
     */ 
    public function cambiarColores() : array {

        try {

            global $http;

            $metodo = $http->request->get('metodo');        /* Método */

            /* Colores */
            $barraSuperior = $this->db->scape($http->request->get('barraSuperior'));
            $textoSuperior = $this->db->scape($http->request->get('textoSuperior'));
            $textoHover = $this->db->scape($http->request->get('textoHover'));
            $borderDivisor = $this->db->scape($http->request->get('borderDivisor'));
            $colorP1 = $this->db->scape($http->request->get('colorP1'));
            $colorP2 = $this->db->scape($http->request->get('colorP2'));

            /* Validar si se recibió método */
            if($metodo != null) {

                /* Validar si el método corresponde a los permitidos*/
                if($metodo != 'cambiarColores') {

                    throw new ModelsException('El método no está definido.');

                } else {

                    # Verificar que no están vacíos
                    if (Helper\Functions::e($barraSuperior, $textoSuperior, $textoHover, $borderDivisor, $colorP1, $colorP2)) {
                        throw new ModelsException('Verifique los datos del formulario.');
                    }

                    # Actualizar colores
                    $this->db->update('plantilla',array(
                        'fondoBarraSuperior' => $barraSuperior,
                        'colorTextoBarraSuperior ' => $textoSuperior,
                        'colorTextoHoverBarraSuperior' => $textoHover,
                        'borderDivisor' => $borderDivisor,
                        'colorPlantilla' => $colorP1,
                        'colorPlantillaDos' => $colorP2
                    ),"id='1'",1);

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

                    (new Model\Actividad)->registrarActividad('Evento', 'Edición de colores', $perfil, $administrador['id_user'], 1, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Los colores de la plantilla se guardaron correctamente.');

                }

            } else {

                throw new ModelsException('La petición no se puede procesar (método nulo).');

            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
            
        }

    }

    /**
     * Respuesta generada al cambiar redes sociales
     *    
     *
     * @return array
     */ 
    public function cambiarRS() : array {
        
        try {

            global $http;

            $metodo = $http->request->get('metodo');                /* Método */
            $redesSociales = $http->request->get('redesSociales');

            /* Validar si se recibió método */
            if($metodo != null) {

                /* Validar si el método corresponde a los permitidos*/
                if($metodo != 'cambiarRS') {

                    throw new ModelsException('El método no está definido.');

                } else {

                    # Verificar que no venga vació
                    if (Helper\Functions::e($redesSociales) || strlen($redesSociales) <= 2) {
                        throw new ModelsException('Active por lo menos una red social.');
                    }

                    # Actualizar redes sociales
                    $this->db->update('plantilla',array(
                        'redesSociales' => $redesSociales
                    ),"id='1'",1);

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

                    (new Model\Actividad)->registrarActividad('Evento', 'Edición de redes sociales', $perfil, $administrador['id_user'], 1, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Las redes sociales se guardaron correctamente.');

                }

            } else {

                throw new ModelsException('La petición no se puede procesar (método nulo).');

            }
            
        } catch (ModelsException $e) {

            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());
            
        }
    }

    /**
     * Respuesta generada al cambiar códigos
     *    
     *
     * @return array
     */ 
    public function cambiarCodigos() : array {

        try {
            
            global $http;

            $metodo = $http->request->get('metodo');                /* Método */
            $googleMaps = $http->request->get('googleMaps');
            $googleAnalytics = $http->request->get('googleAnalytics');
            $apiFacebook = $http->request->get('apiFacebook');
            $pixelFacebook = $http->request->get('pixelFacebook');
            $password = $http->request->get('password');

            /* Validar si se recibió método */
            if($metodo != null) {

                /* Validar si el método corresponde a los permitidos*/
                if($metodo != 'cambiarCodigos') {

                    throw new ModelsException('El método no está definido.');

                } else {

                    # Contraseña vacia
                    if(Helper\Functions::emp($password)) {

                        throw new ModelsException('Es necesario proporcionar su contraseña para continuar con esté proceso.');

                    }

                    # Verificar contraseña de usuario
                    if(!(new Model\Users)->validatePassword($password,$this->id_user)){

                      throw new ModelsException('Verifique su contraseña.');

                    }

                    # Verificar que no están vacíos
                    if(Helper\Functions::e($googleMaps, $googleAnalytics, $apiFacebook)) {

                        throw new ModelsException('Los campos con (*) son obligatorios.');

                    }

                    # Actualizar códigos
                    $this->db->update('plantilla',array(
                        'googleMaps' => $googleMaps,
                        'googleAnalytics' => $googleAnalytics,
                        'apiFacebook' => $apiFacebook,
                        'pixelFacebook' => $pixelFacebook
                    ),"id='1'",1);

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

                    (new Model\Actividad)->registrarActividad('Evento', 'Edición de códigos javascript', $perfil, $administrador['id_user'], 1, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Los códigos javascript se guardaron correctamente.');

                }

            } else {

                throw new ModelsException('La petición no se puede procesar (método nulo).');

            }

        } catch (ModelsException $e) {
            
            return array('status' => 'error', 'title' => '¡Opss!', 'message' => $e->getMessage());

        }

    }

    /**
     * Respuesta generada al cambiar información
     *    
     *
     * @return array
     */ 
    public function cambiarInformacion() : array {

        try {

            global $http;

            $metodo = $http->request->get('metodo');                /* Método */

            $impuesto = $this->db->scape($http->request->get('impuesto'));
            $envioNac = $this->db->scape($http->request->get('envioNac'));
            $envioInt = $this->db->scape($http->request->get('envioInt'));
            $tasaMinNac = $this->db->scape($http->request->get('tasaMinNac'));
            $tasaMinInt = $this->db->scape($http->request->get('tasaMinInt'));
            $pais = $this->db->scape($http->request->get('pais'));
            $modoPaypal = $this->db->scape($http->request->get('modoPaypal'));
            $clientID = $this->db->scape($http->request->get('clientID'));
            $secretKey = $this->db->scape($http->request->get('secretKey'));
            $modoPayu = $this->db->scape($http->request->get('modoPayu'));
            $merchantID = $this->db->scape($http->request->get('merchantID'));
            $accountID = $this->db->scape($http->request->get('accountID'));
            $apiKey = $this->db->scape($http->request->get('apiKey'));

            $password = $http->request->get('password');

            /* Validar si se recibió método */
            if($metodo != null) {

                /* Validar si el método corresponde a los permitidos*/
                if($metodo != 'cambiarInformacion') {

                    throw new ModelsException('El método no está definido.');

                } else {

                     # Contraseña vacia
                    if(Helper\Functions::emp($password)) {

                        throw new ModelsException('Es necesario proporcionar su contraseña para continuar con esté proceso.');

                    }

                    # Verificar contraseña de usuario
                    if(!(new Model\Users)->validatePassword($password,$this->id_user)){

                      throw new ModelsException('Verifique su contraseña.');

                    }

                    # Verificar que no están vacíos
                    if(Helper\Functions::e($impuesto, $envioNac, $envioInt, $tasaMinNac, $tasaMinInt, $pais, $modoPaypal, $clientID, $secretKey, $modoPayu, $merchantID, $accountID, $apiKey)) {

                        throw new ModelsException('Todos los campos de información comercial son obligatorios.');

                    }

                    # Actualizar códigos
                    $this->db->update('comercio',array(
                        'impuesto' => floatval($impuesto),
                        'envioNacional' => floatval($envioNac),
                        'envioInternacional ' => floatval($envioInt),
                        'tasaMinimaNac' => floatval($tasaMinNac),
                        'tasaMinimaInt' => floatval($tasaMinInt),
                        'pais' => $pais,
                        'modoPaypal ' => $modoPaypal,
                        'clientIdPaypal' => $clientID,
                        'secretPaypal' => $secretKey,
                        'modoPayulatam ' => $modoPayu,
                        'merchantIdPayulatam' => $merchantID,
                        'accountIdPayulatam' => $accountID,
                        'apiKeyPayulatam' => $apiKey
                    ),"id='1'",1);

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

                    (new Model\Actividad)->registrarActividad('Evento', 'Edición de información comercial', $perfil, $administrador['id_user'], 1, date('Y-m-d H:i:s'), 0);

                    return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'La información comercial se guardó correctamente.');

                }

            } else {

                throw new ModelsException('La petición no se puede procesar (método nulo).');

            }

            
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