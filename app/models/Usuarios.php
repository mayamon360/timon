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
     * Máximos intentos de inincio de sesión de un usuario
     *
     * @var int
     */
    const MAX_ATTEMPTS = 5;

    /**
     * Tiempo entre máximos intentos en segundos
     *
     * @var int
     */
    const MAX_ATTEMPTS_TIME = 300; # (cinco minutos)

    /**
     * Log de intentos recientes con la forma 'email' => (int) intentos
     *
     * @var array
     */
    private $recentAttempts = array();

    /**
     * Hace un set() a la sesión login_user_recentAttempts con el valor actualizado.
     *
     * @return void
    */
    private function updateSessionAttempts() {
        global $session;

        $session->set('login_user_recentAttempts', $this->recentAttempts);
    }

    /**
     * Revisa si las contraseñas son iguales
     *
     * @param string $pass : Contraseña sin encriptar
     * @param string $pass_repeat : Contraseña repetida sin encriptar
     *
     * @throws ModelsException cuando las contraseñas no coinciden
     */
    private function checkPassMatch(string $pass, string $pass_repeat) {
        if ($pass != $pass_repeat) {
            throw new ModelsException('Las contraseñas no coinciden, virifícala e intenta nuevamente.');
        }
    }

    /**
     * Verifica el email introducido, tanto el formato como su existencia en el sistema
     *
     * @param string $email: Email del usuario
     *
     * @throws ModelsException en caso de que no tenga formato válido o ya exista
     */
    private function checkEmail(string $email) {
        # Formato de email
        if (!Helper\Strings::is_email($email)) {
            throw new ModelsException('El correo electrónico no tiene un formato válido, verifícalo e intenta nuevamente.');
        }
        # Existencia de email
        $email = $this->db->scape($email);
        $query = $this->db->select('id_cliente', 'clientes', null, "correoElectronico='$email'", 1);
        if (false !== $query) {
            throw new ModelsException('El correo electrónico proporcionado ya se encuentra asociado a una cuenta, prueba iniciar sesión o solicita una nueva contraseña si la has olvidado.');
        }
    }

    /**
     * Restaura los intentos de un usuario al iniciar sesión
     *
     * @param string $email: Email del usuario a restaurar
     *
     * @throws ModelsException cuando hay un error de lógica utilizando este método
     * @return void
     */
    private function restoreAttempts(string $email) {       
        if (array_key_exists($email, $this->recentAttempts)) {
            $this->recentAttempts[$email]['attempts'] = 0;
            $this->recentAttempts[$email]['time'] = null;
            $this->updateSessionAttempts();
        } else {
            throw new ModelsException('Error lógico');
        }
    }

    /**
     * Genera la sesión con el id del usuario que ha iniciado
     *
     * @param array $user_data: Arreglo con información de la base de datos, del usuario
     *
     * @return void
     */
    private function generateSession(array $user_data) {
        global $session, $cookie, $config;
        
        # Generar un session hash
        $cookie->set('session_hash_website', md5(time()), $config['sessions']['user_cookie']['lifetime']);
        
        # Generar la sesión del usuario
        $session->set($cookie->get('session_hash_website') . '__user_id_',(int) $user_data['id_user']);

        # Generar data encriptada para prolongar la sesión
        if($config['sessions']['user_cookie']['enable']) {
            # Generar id encriptado
            $encrypt = Helper\Strings::ocrend_encode($user_data['id_user'], $config['sessions']['user_cookie']['key_encrypt']);

            # Generar cookies para prolongar la vida de la sesión
            $cookie->set('appsalt_website', Helper\Strings::hash($encrypt), $config['sessions']['user_cookie']['lifetime']);
            $cookie->set('appencrypt_website', $encrypt, $config['sessions']['user_cookie']['lifetime']);
        }
    }

    /**
     * Verifica en la base de datos, el email y contraseña ingresados por el usuario
     *
     * @param string $email: Email del usuario que intenta el login
     * @param string $pass: Contraseña sin encriptar del usuario que intenta el login
     *
     * @return bool true: Cuando el inicio de sesión es correcto 
     *              false: Cuando el inicio de sesión no es correcto
     */
    private function authentication(string $email,string $pass) : bool {
        $email = $this->db->scape($email);
        $query = $this->db->select('id_cliente as id_user, clave as pass, estado','clientes',null, "correoElectronico='$email'",1);
        
        # Incio de sesión con éxito
        if(false !== $query && Helper\Strings::chash($query[0]['pass'],$pass)) {

            # Restaurar intentos
            $this->restoreAttempts($email);

            # Generar la sesión
            if($query[0]['estado'] != 1){
                throw new ModelsException('Es probable que la cuenta aún no haya sido activada o que se encuentre temporalmente deshabilitada.');
            }else{
                $this->generateSession($query[0]);
                return true;
            }
            
        }

        return false;
    }

    /**
     * Establece los intentos recientes desde la variable de sesión acumulativa
     *
     * @return void
     */
    private function setDefaultAttempts() {
        global $session;

        if (null != $session->get('login_user_recentAttempts')) {
            $this->recentAttempts = $session->get('login_user_recentAttempts');
        }
    }
    
    /**
     * Establece el intento del usuario actual o incrementa su cantidad si ya existe
     *
     * @param string $email: Email del usuario
     *
     * @return void
     */
    private function setNewAttempt(string $email) {
        if (!array_key_exists($email, $this->recentAttempts)) {
            $this->recentAttempts[$email] = array(
                'attempts' => 0, # Intentos
                'time' => null # Tiempo 
            );
        } 

        $this->recentAttempts[$email]['attempts']++;
        $this->updateSessionAttempts();
    }

    /**
     * Controla la cantidad de intentos permitidos máximos por usuario, si llega al límite,
     * el usuario podrá seguir intentando en self::MAX_ATTEMPTS_TIME segundos.
     *
     * @param string $email: Email del usuario
     *
     * @throws ModelsException cuando ya ha excedido self::MAX_ATTEMPTS
     * @return void
     */
    private function maximumAttempts(string $email) {
        if ($this->recentAttempts[$email]['attempts'] >= self::MAX_ATTEMPTS) {
            
            # Colocar timestamp para recuperar más adelante la posibilidad de acceso
            if (null == $this->recentAttempts[$email]['time']) {
                $this->recentAttempts[$email]['time'] = time() + self::MAX_ATTEMPTS_TIME;
            }
            
            if (time() < $this->recentAttempts[$email]['time']) {
                # Setear sesión
                $this->updateSessionAttempts();
                # Lanzar excepción
                $time = Helper\Strings::amigable_time($this->recentAttempts[$email]['time']);
                throw new ModelsException('Has superado el límite de intentos para iniciar sesión. Intentalo nuevamente '.lcfirst($time).'.');
            } else {
                $this->restoreAttempts($email);
            }
        }
    }   

    /**
     * Obtiene datos de un usuario según su id en la base de datos
     *    
     * @param int $id: Id del usuario a obtener
     * @param string $select : Por defecto es *, se usa para obtener sólo los parámetros necesarios 
     *
     * @return false|array con información del usuario
     */   
    public function getUserById(int $id, string $select = '*') {
        return $this->db->select($select,'clientes',null,"id_cliente='$id'",1);
    }
    
    /**
     * Obtiene a todos los usuarios
     *    
     * @param string $select : Por defecto es *, se usa para obtener sólo los parámetros necesarios 
     *
     * @return false|array con información de los usuarios
     */  
    public function getUsers(string $select = '*') {
        return $this->db->select($select, 'clientes');
    }

    /**
     * Obtiene datos del usuario conectado actualmente
     *
     * @param string $select : Por defecto es *, se usa para obtener sólo los parámetros necesarios
     *
     * @throws ModelsException si el usuario no está logeado
     * @return array con datos del usuario conectado
     */
    public function getOwnerUser(string $select = '*') : array {
        if(null !== $this->id_user) {    
               
            $user = $this->db->select($select,'clientes',null, "id_cliente='$this->id_user'",1);

            # Si se borra al usuario desde la base de datos y sigue con la sesión activa
            if(false === $user) {
                $this->logout();
            }
            
            $send = $this->db->select('p_code, state, municipality, colony, street, o_number, b_streets, a_references','clientes_envio',null, "id_cliente='$this->id_user'",1);
            if($send){
                $send = $send[0];
            }else{
                $send = [
                    'p_code' => '',
                    'state' => '',
                    'municipality' => '',
                    'colony' => '',
                    'street' => '',
                    'o_number' => '',
                    'b_streets' => '',
                    'a_references' => ''
                ];
            }
            
            $dataUser = array_merge($user[0], $send);
            
            return $dataUser;
        } 
           
        throw new \RuntimeException('El usuario no está logeado.');
    }

     /**
     * Realiza la acción de login dentro del sistema
     *
     * @return array : Con información de éxito/falla al inicio de sesión.
     */
    public function login() : array {

        try {

            if($this->id_user !== null){
                return array('status' => 'info', 'title' => '¡Sesión activa!', 'message' => 'Espera un momento, por favor...');
            }
            
            global $http;

            # Definir de nuevo el control de intentos
            $this->setDefaultAttempts();

            # Obtener los datos $_POST
            $email = strtolower($http->request->get('l_mail'));
            $pass = $http->request->get('l_password');

            # Verificar que no están vacíos
            if (Helper\Functions::e($email, $pass)) {
                throw new ModelsException('Para continuar, es necesario llenar el formulario.');
            }
            
            $secret = '6Ld3NcwUAAAAAPXOJhU2h2M4RyEqeE3JTBAMrJKl';
            $response = $http->request->get('g-recaptcha-response');
            $result = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
            $array = json_decode($result,true);
            # Verificar que no sea un robot
            if($array["success"] === false){
                throw new ModelsException('Confirme que no es un robot haciendo clic en el reCaptcha.');  
            }
            
            # Añadir intentos
            $this->setNewAttempt($email);
        
            # Verificar intentos 
            $this->maximumAttempts($email);

            # Autentificar
            if (!$this->authentication($email, $pass)) {
                throw new ModelsException('Las credenciales son incorrectas, verifica tus datos e intenta nuevamente.');
            }else{
                return array('status' => 'success', 'title' => '¡Iniciando sesión!', 'message' => 'Espera un momento, por favor...');
            }

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }        
    }

    /**
     * Realiza la acción de registro dentro del sistema
     *
     * @return array : Con información de éxito/falla al registrar el usuario nuevo.
     */
    public function register() : array {
        try {
        
            global $config,$http;

            # Obtener los datos $_POST
            $name = trim($http->request->get('r_name'));
            $name = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s])', '', $name);
            $email = $http->request->get('r_mail');
            $pass = $http->request->get('r_password');
            $pass_repeat = $http->request->get('r_repassword');
            $politicas = $http->request->get('politicas');
            $codigoLealtad = mb_strtoupper(uniqid('',true));
            $codigoLealtad = str_replace('.', '', $codigoLealtad);
            //$token = md5(time());

            # Verificar que no están vacíos
            if (Helper\Functions::e($name, $email, $pass, $pass_repeat)) {
                throw new ModelsException('Para continuar, es necesario llenar el formulario.');
            }

            # Verificar email 
            $this->checkEmail($email);

            # Veriricar contraseñas
            $this->checkPassMatch($pass, $pass_repeat);
            
            /*if ($politicas != 'acepto'){
                throw new ModelsException('Es necesario aceptar las Condiciones.');
            }*/
            
            /*$secret = '6Ld3NcwUAAAAAPXOJhU2h2M4RyEqeE3JTBAMrJKl';
            $response = $http->request->get('g-recaptcha-response');
            $result = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
            $array = json_decode($result,true);
            # Verificar que no sea un robot
            if($array["success"] === false){
                throw new ModelsException('Confirme que no es un robot haciendo clic en el reCaptcha.');  
            }*/

            # Registrar al usuario
            $id_user = $this->db->insert('clientes', array(
                'tipo' => 1,
                'clave' => Helper\Strings::hash($pass),
                //'token' => $token,
                'cliente' => ucwords(mb_strtolower($name, 'UTF-8')),
                'correoElectronico' => $email,
                'codigoFidelidad' => $codigoLealtad,
                'puntos' => 0,
                'estado' => 1
            ));

            //$link = $config['build']['url'] . 'autenticacion?token='.$token.'&user='.$id_user.'&action=activateAccount';

            # Construir mensaje y enviar mensaje
            //$HTML = '<p class="contenido">
            //    <a href="'.$link.'" class="enlace" target="_blank">Activa tu cuenta</a> para que puedas iniciar sesión.
            //</p>';
            /*$HTML = '<p class="contenido">
                <a href="'.$link.'" class="enlace" target="_blank">Activa tu cuenta</a> para que puedas iniciar sesión.
                <br />
                <br />
                <br />
                <br />
                <b>¡PREMIAMOS TU FIDELIDAD!</b><br />
                Acumula puntos en tus compras y canjéalos por libros.
                <br>
                <span style="font-size:10px;">(Aplica en libros sin descuento)</span>
            </p>';*/

            /*$EXTRA = '<p class="extra_imagen">
                <img src="'.$config['build']['url'].'assets/plantilla/img/general/regalo.png" width="40px">
                <br>
                + 5 puntos
            </p>';*/
            // $EXTRA = '';
            
            # Enviar el correo electrónico
            /*
            $dest = array();
			$dest[$email] = $name;
            $email_send = Helper\Emails::send($dest,array(
                # Url de logo
                '{{url}}' => $config['build']['url'],
                # Logo
                '{{logo}}' => $config['build']['url'].$config['mailer']['logo'],
                # Tipo de correo
                '{{module}}' => 'ACTIVAR CUENTA',
                # Contenido del mensaje
                '{{content}}' => $HTML,
                # Título del mensaje
                '{{title}}' => '¡Bienvenid@, '. $name .'!',
                '{{title_html}}' => '¡Bienvenid@, <br /><b>'. $name .'</b>!',
                # Subtítulo del mensaje
                '{{subtitle}}' => '<b>Gracias por registrarte</b>',
                # Contenido extra del mensaje
                '{{extra}}' => $EXTRA,
                # Copyright
                '{{copyright}}' => '&copy; '.date('Y') .' <a href="'.$config['build']['url'].'">'.$config['build']['name'].'</a> - Todos los derechos reservados.'
              ),1);

            # Verificar si hubo algún problema con el envío del correo
            if(false === $email_send) {
                throw new ModelsException('No se ha podido enviar el correo electrónico.');
            }*/

            //return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Tu cuenta se creó correctamente, en breve recibirás un correo con instrucciones para activarla y poder realizar tus primeras compras.');
            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Tu cuenta se creó correctamente, ahora puedes iniciar sesión para poder realizar tus primeras compras.');
        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }        
    }
    
    /**
      * Envía un correo electrónico al usuario que quiere recuperar la contraseña, con un token y una nueva contraseña.
      * Si el usuario no visita el enlace, el sistema no cambiará la contraseña.
      *
      * @return array<string,integer|string>
    */  
    public function lostpass() : array {
        try {
            global $http, $config;

            # Obtener datos $_POST
            $email = $http->request->get('f_mail');
            
            # Campo lleno
            if (Helper\Functions::emp($email)) {
                throw new ModelsException('Es necesario especificar una dirección de correo electrónico.');
            }

            # Filtro
            $email = $this->db->scape($email);

            # Obtener información del usuario 
            $user_data = $this->db->select('id_cliente,cliente', 'clientes', null, "correoElectronico='$email'", 1);

            # Verificar correo en base de datos 
            if (false === $user_data) {
                throw new ModelsException('El correo electrónico no está registrado, verifícalo e intenta nuevamente.');
            }

            # Generar token y contraseña 
            $token = md5(time());
            $pass = uniqid();
            $link = $config['build']['url'] . 'autenticacion/recuperar/?token='.$token.'&user='.$user_data[0]['id_cliente'].'&action=changeTemporalPassword';

            # Construir mensaje y enviar mensaje            
            $HTML = '<p class="contenido">
                Si desconoces la actividad de este correo no necesitas hacer nada.<br>
                Para cambiar tu contraseña por <b>'.$pass.'</b>, pulsa el <a href="'.$link.'" class="enlace" target="_blank">enlace</a>.
                <br />
                <br />
                <br />
                <br />
                <b>IMPORTANTE</b><br />
                Con la nueva contraseña podrás iniciar sesión, sin embargo te recomendamos actualizarla una vez dentro de tu cuenta en la opción "Cambiar contraseña".
            </p>';

            # Enviar el correo electrónico
            $dest = array();
			$dest[$email] = $user_data[0]['cliente'];
            $email_send = Helper\Emails::send($dest,array(

                # Url de logo
                '{{url}}' => $config['build']['url'],
                # Logo
                '{{logo}}' => $config['mailer']['logo'],
                # Tipo de correo
                '{{module}}' => 'CAMBIAR CONTRASEÑA',
                # Contenido del mensaje
                '{{content}}' => $HTML,
                # Título del mensaje
                '{{title}}' => 'Cambiar contraseña',
                '{{title_html}}' => 'Apreciable <br><b>'.$user_data[0]['cliente'].'</b>',
                # Subtítulo del mensaje
                '{{subtitle}}' => '<b>Sigue las instrucciones:</b>',
                # Contenido extra del mensaje
                '{{extra}}' => '',
                # Copyright
                '{{copyright}}' => '&copy; '.date('Y') .' <a href="'.$config['build']['url'].'">'.$config['build']['name'].'</a> - Todos los derechos reservados.'
              ),1);

            # Verificar si hubo algún problema con el envío del correo
            if(false === $email_send) {
                throw new ModelsException('No se ha podido enviar el correo electrónico.');
            }

            # Actualizar datos 
            $id_user = $user_data[0]['id_cliente'];
            $this->db->update('clientes',array(
                'clave_tmp' => Helper\Strings::hash($pass),
                'token' => $token
            ),"id_cliente='$id_user'",1);

            return array('status' => 'success', 'title' => '¡Solicitud aceptada!', 'message' => 'Hemos procesado tu solicitud, en breve recibirás un correo con instrucciones para obtener una nueva contraseña.');
        } catch(ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }

    /**
     * Desconecta a un usuario si éste está conectado, y lo devuelve al inicio
     *
     * @return void
     */    
    public function logout() {
        global $config, $session, $cookie;
	    
        $session->remove($cookie->get('session_hash_website') . '__user_id_');

        ($cookie->get('PHPSESSID') !== null) ? $cookie->remove('PHPSESSID') : '';
        ($cookie->get('session_hash_website') !== null) ? $cookie->remove('session_hash') : '';
        ($cookie->get('appsalt_website') !== null) ? $cookie->remove('appsalt_website') : '';
        ($cookie->get('appencrypt_website') !== null) ? $cookie->remove('appencrypt_website') : '';

        /*foreach($cookie->all() as $name => $value) {
            $cookie->remove($name);
        }*/

        Helper\Functions::redir($config['build']['url'].'autenticacion');
    }

    /**
     * Cambia la contraseña de un usuario en el sistema, luego de que éste haya solicitado cambiarla.
     * Luego retorna al sitio de inicio con la variable GET success=(bool)
     *
     * La URL debe tener la forma URL/lostpass?token=TOKEN&user=ID
     *
     * @return void
     */  
    public function changeTemporalPassword() {
        global $http;
        
        # Obtener los datos $_GET 
        $id_user = $http->query->get('user');
        $token = $http->query->get('token');
        $action = $http->query->get('action');

        $success = false;
        if (!Helper\Functions::emp($token) && is_numeric($id_user) && $id_user >= 1 && $action = 'changeTemporalPassword') {
            # Filtros a los datos
            $id_user = $this->db->scape($id_user);
            $token = $this->db->scape($token);
            # Ejecutar el cambio
            $update = $this->db->query("UPDATE clientes SET clave=clave_tmp, clave_tmp=NULL, token=NULL
            WHERE id_cliente='$id_user' AND token='$token' LIMIT 1;");
            if($this->db->affected_rows > 0){
                $success = true;
            }
        }
        
        if($success){
            return '<div class="container animated fadeInUp mt-3"><div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-inner--icon"><i class="fas fa-check"></i></span>
                <span class="alert-inner--text"><strong>¡Bien hecho!</strong> Tu contraseña se cambio correctamente, ahora puedes usarla para iniciar sesión.</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div></div>';
        }else{
            return '<div class="container animated flash mt-3"><div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="alert-inner--icon"><i class="fas fa-times"></i></span>
                <span class="alert-inner--text"><strong>¡Enlace roto!</strong> Es probable que la contraseña ya haya sido cambiada o que la estructura original del enlace haya sido alterada.</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div></div>';
        }
    }

    public function activateAccount(){
        global $http;

        # Obtener los datos $_GET 
        $id_user = $http->query->get('user');
        $token = $http->query->get('token');
        $action = $http->query->get('action');

        $success = false;
        if (!Helper\Functions::emp($token) && is_numeric($id_user) && $id_user >= 1 && $action = 'activateAccount') {
            # Filtros a los datos
            $id_user = $this->db->scape($id_user);
            $token = $this->db->scape($token);
            # Ejecutar el cambio
            $update = $this->db->query("UPDATE clientes SET estado=1, token=NULL
            WHERE id_cliente='$id_user' AND token='$token' LIMIT 1;");
            if($this->db->affected_rows > 0){
                $success = true;
            }
        }

        if($success){
            return '<div class="container animated fadeInUp mt-3"><div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-inner--icon"><i class="fas fa-check"></i></span>
                <span class="alert-inner--text"><strong>¡Bien hecho!</strong> Tu cuenta ha sido activada, ya puedes iniciar sesión.</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div></div>';
        }else{
            return '<div class="container animated flash mt-3"><div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="alert-inner--icon"><i class="fas fa-times"></i></span>
                <span class="alert-inner--text"><strong>¡Enlace roto!</strong> Es probable que la cuenta ya haya sido activada o que la estructura original del enlace haya sido alterada.</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div></div>';
        }

    }

    public function saveData(){
        try {
            global $http;
            if($this->id_user === null){
                return array('status' => 'warning', 'title' => '¡Sesión caducada!', 'message' => 'Espera un momento, por favor...');
            }

            $name = trim($http->request->get('d_name'));
            $name = preg_replace('([^A-Za-z0-9ÁÉÍÓÚáéíóúñÑüÜ\s])','',$name);
            $rfc = $http->request->get('d_rfc');
            $phone = $http->request->get('d_phone');

            if($name === null || $name == ''){
                throw new ModelsException('Es necesario ingresar tu nombre completo.');
            }
            
            if($rfc === null || $rfc == ''){
                throw new ModelsException('Por disposición oficial para el proceso de envió por paquetería es necesario proporcionar su RFC. ');
            }
            
            $this->db->update('clientes', array(
                'rfc' => mb_strtoupper($rfc, 'UTF-8'),
                'cliente' => ucwords(mb_strtolower($name, 'UTF-8')),
                'telefono' => $phone,
                'fechaModificacion' => date('Y-m-d H:i:s'),
                'usuarioModificacion' => $this->id_user
            ), "id_cliente='$this->id_user'");

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Tus datos se actualizarón correctamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }

    public function changePassword(){
        try {
            global $http;
            if($this->id_user === null){
                return array('status' => 'warning', 'title' => '¡Sesión caducada!', 'message' => 'Espera un momento, por favor...');
            }

            $a_password = $http->request->get('a_password');
            $n_password = $http->request->get('n_password');
            $r_password = $http->request->get('r_password');

            $user = $this->getOwnerUser();
            if(!Helper\Strings::chash($user["clave"],$a_password)){
                throw new ModelsException('Verifica tu contraseña.');
            }
            if(strlen($n_password) < 6){
                throw new ModelsException('La nueva contraseña debe tener al menos 6 caracteres.');
            }
            if($n_password != $r_password){
                throw new ModelsException('Las contraseñas no coinciden.');
            }

            $this->db->update('clientes', array(
                'clave' => Helper\Strings::hash($n_password),
            ), "id_cliente='$this->id_user'");

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Tu contraseña se cambio correctamente.');

        } catch (ModelsException $e) {
            return array('status' => 'error', 'title' => '¡Error!', 'message' => $e->getMessage());
        }
    }
    
    public function saveAddress(){
        try {
            global $http;
            if($this->id_user === null){
                return array('status' => 'warning', 'title' => '¡Sesión caducada!', 'message' => 'Espera un momento, por favor...');
            }

            $p_code = trim($http->request->get('p_code'));
            $state = trim($http->request->get('state'));
            $municipality = trim($http->request->get('municipality'));
            $colony = trim($http->request->get('colony'));
            $street = trim($http->request->get('street'));
            $o_number = trim($http->request->get('o_number'));
            $b_streets = trim($http->request->get('b_streets'));
            $a_references = trim($http->request->get('a_references'));
                
            if($p_code == '' || $p_code === null 
                || $state == '' || $state === null 
                || $municipality == '' || $municipality === null 
                || $colony == '' || $colony === null 
                || $street == '' || $street === null 
                || $o_number == '' || $o_number === null 
                || $b_streets == '' || $b_streets === null 
                || $a_references == '' || $a_references === null){
                throw new ModelsException('Es necesario ingresar todos los datos de la dirección de envío.');
            }
            
            $send = $this->db->select('*', "clientes_envio", null, "id_cliente='$this->id_user'",1);
            if($send){
                $this->db->update('clientes_envio', array(
                    'p_code' => $p_code,
                    'state' => $state,
                    'municipality' => $municipality,
                    'colony' => $colony,
                    'street' => $street,
                    'o_number' => $o_number,
                    'b_streets' => $b_streets,
                    'a_references' => $a_references
                ), "id_cliente='$this->id_user'");
            }else{
                $this->db->insert('clientes_envio', array(
                    'id_cliente' => $this->id_user,
                    'p_code' => $p_code,
                    'state' => $state,
                    'municipality' => $municipality,
                    'colony' => $colony,
                    'street' => $street,
                    'o_number' => $o_number,
                    'b_streets' => $b_streets,
                    'a_references' => $a_references
                ));
            }

            return array('status' => 'success', 'title' => '¡Bien hecho!', 'message' => 'Tus datos de envío se actualizarón correctamente.');

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