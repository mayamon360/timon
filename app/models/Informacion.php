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
 * Modelo Informacion
 */
class Informacion extends Models implements IModels {
    use DBModel;
    
    /**
     * Envía un mensaje de contacto al administrador
     *
     * @return array : Con información de éxito/falla del envio de mensaje.
     */
    public function contactMessage() : array {
        try{
            global $http,$config;
            
            $subject = trim($http->request->get('subject'));
            $name = trim($http->request->get('name'));
            $email = trim($http->request->get('email'));
            $message = trim($http->request->get('message'));
            
            # Verificar que no están vacíos
            if (Helper\Functions::e($subject, $name, $email, $message)) {
                throw new ModelsException('Para continuar, es necesario llenar el formulario.');
            }
            
            # Verificar email 
            if (!Helper\Strings::is_email($email)) {
                throw new ModelsException('El correo electrónico no tiene un formato válido, verifícalo e intenta nuevamente.');
            }
            
            $secret = '6Ld3NcwUAAAAAPXOJhU2h2M4RyEqeE3JTBAMrJKl';
            $response = $http->request->get('g-recaptcha-response');
            $result = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
            $array = json_decode($result,true);
            # Verificar que no sea un robot
            if($array["success"] === false){
                throw new ModelsException('Confirme que no es un robot haciendo clic en el reCaptcha.');  
            }
            
            # Construir mensaje y enviar mensaje
            $HTML = '<p class="contenido">
                '.$message.'
            </p>';
            $EXTRA = '';
            
            # Enviar el correo electrónico
            $dest = array();
            $repl = array();
			$repl[$email] = $name;
			$dest['ti0911291m@gmail.com'] = 'Administrador';
			$dest['eltimonlibreria@gmail.com'] = 'El timón librería';
            $email_send = Helper\Emails::send($dest,array(
                # Url de logo
                '{{url}}' => $config['build']['url'],
                # Logo
                '{{logo}}' => $config['build']['url'].$config['mailer']['logo'],
                # Tipo de correo
                '{{module}}' => $name,
                # Contenido del mensaje
                '{{content}}' => $HTML,
                # Título del mensaje
                '{{title}}' => $subject,
                '{{title_html}}' => '<b>'.$email.'</b>',
                # Subtítulo del mensaje
                '{{subtitle}}' => '',
                # Contenido extra del mensaje
                '{{extra}}' => $EXTRA,
                # Copyright
                '{{copyright}}' => ''
              ),1,[],$repl);

            # Verificar si hubo algún problema con el envío del correo
            if(false === $email_send) {
                throw new ModelsException('No se ha podido enviar el correo electrónico.');
            }
            
            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Hemos recibido tu mensaje, en breve nos comunicaremos contigo.');
            
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
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