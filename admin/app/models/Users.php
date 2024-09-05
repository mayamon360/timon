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
 * Modelo Users
 */
class Users extends Models implements IModels {
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
    const MAX_ATTEMPTS_TIME = 900; # (15 minutos)

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
            throw new ModelsException('Las contraseñas no coinciden.');
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
            throw new ModelsException('El correo electrónico no tiene un formato válido.');
        }
        # Existencia de email
        $email = $this->db->scape($email);
        $query = $this->db->select('id_user', 'administradores', null, "email='$email'", 1);
        if (false !== $query) {
            throw new ModelsException('El correo electrónico proporcionado ya existe.');
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
        $cookie->set('session_hash', md5(time()), $config['sessions']['user_cookie']['lifetime']);
        
        # Generar la sesión del usuario
        $session->set($cookie->get('session_hash') . '__user_id',(int) $user_data['id_user']);

        # Generar data encriptada para prolongar la sesión
        if($config['sessions']['user_cookie']['enable']) {
            # Generar id encriptado
            $encrypt = Helper\Strings::ocrend_encode($user_data['id_user'], $config['sessions']['user_cookie']['key_encrypt']);

            # Generar cookies para prolongar la vida de la sesión
            $cookie->set('appsalt', Helper\Strings::hash($encrypt), $config['sessions']['user_cookie']['lifetime']);
            $cookie->set('appencrypt', $encrypt, $config['sessions']['user_cookie']['lifetime']);
        }
    }

    /**
     * Guarda la fecha en que inicio sesión
     *
     *
     * @return void
     */
    private function dateSession(array $user_data) {
        $id =  $user_data['id_user'];      
        $this->db->update('administradores',array('session_date' => date('Y-m-d H:i:s'), 'online' => 1),"id_user='$id'",1);
    }

    /**
     * Verifica en la base de datos, el email y contraseña ingresados por el usuario
     *
     * @param string $email: Email del usuario que intenta el login
     * @param string $pass: Contraseña sin encriptar del usuario que intenta el login
     * @param string $perfil: Perfil
     * @param string $almacen: almacen
     *
     * @return $query[0]: Cuando el inicio de sesión es correcto 
     *         false: Cuando el inicio de sesión no es correcto
     */
    private function authentication(string $email,string $pass, int $perfil, int $almacen, string $key_code) {
        $email = $this->db->scape($email);
        $query = $this->db->select('id_user,pass,name,status,secret_key','administradores',null, "email='$email'",1);
        
        # Inicio de sesión con éxito
        if(false !== $query && Helper\Strings::chash($query[0]['pass'],$pass)) {

            if($query[0]['status'] == 0){
                throw new ModelsException('Tu cuenta está desactivada.');
            }

            /*$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            $get_code = $g->getCode($query[0]['secret_key']);

            if($key_code != $get_code){
                throw new ModelsException('El código de verificación es incorrecto, verificalo e intenta nuevamente.');
            }*/

            $perfilAsignado = (new Model\Perfiles)->perfilAsignado($query[0]['id_user'], $perfil);
            if(!$perfilAsignado){
                throw new ModelsException('El perfil no está asignado a tu cuenta.');
            }

            if($perfil == 4){
                $almacenAsignado = (new Model\Almacenes)->almacenAsignado($query[0]['id_user'], $almacen);
                if(!$almacenAsignado){
                    throw new ModelsException('El almacén no está asignado a tu cuenta.');
                }
            }

            # Restaurar intentos
            $this->restoreAttempts($email);

            # Generar la sesión
            $this->generateSession($query[0]);

            # Registrar fecha de sesión
            $this->dateSession($query[0]);

            return $query[0];
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

        global $session;

        if ($this->recentAttempts[$email]['attempts'] >= self::MAX_ATTEMPTS) {
            
            # Colocar timestamp para recuperar más adelante la posibilidad de acceso
            if (null == $this->recentAttempts[$email]['time']) {
                $this->recentAttempts[$email]['time'] = time() + self::MAX_ATTEMPTS_TIME;
            }
            
            if (time() < $this->recentAttempts[$email]['time']) {
                # Construir mensaje y enviar mensaje
                $HTML = '<strong>'.$email.'</strong> superó el límite de intentos para iniciar sesión, es posible que alguien más haya intentado acceder a la cuenta.';

                # Enviar el correo electrónico
                $dest = array();
                $dest['ti0911291m@gmail.com'] = 'José Alejandro Maya Montiel';
                $email_send = Helper\Emails::send($dest,array(
                    # Título del mensaje
                    '{{title}}' => 'Alerta de seguridad',
                    # Asunto
                    '{{subject}}' => 'Bloqueo de tiempo al iniciar sesión',
                    # Url de logo
                    '{{url_logo}}' => $config['build']['url'],
                    # Logo
                    '{{logo}}' => $config['mailer']['logo'],
                    # Contenido del mensaje
                    '{{content}} ' => $HTML,
                    # Copyright
                    '{{copyright}}' => '&copy; '.date('Y') .' <a href="'.$config['build']['url'].'">'.$config['build']['name'].'</a> - Todos los derechos reservados.'
                ),1);

                # Verificar si hubo algún problema con el envío del correo
                if(false === $email_send) {
                    throw new ModelsException('No se ha podido enviar el correo electrónico.');
                }
                # Setear sesión
                $this->updateSessionAttempts();
                # Lanzar excepción
                $time = Helper\Strings::amigable_time($this->recentAttempts[$email]['time']);
                throw new ModelsException('Se superó el límite de intentos para iniciar sesión. Intentalo nuevamente '.lcfirst($time).'.');
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
        return $this->db->select($select,'administradores',null,"id_user='$id'",1);
    }
    
    /**
     * Obtiene a todos los usuarios
     *    
     * @param string $select : Por defecto es *, se usa para obtener sólo los parámetros necesarios 
     *
     * @return false|array con información de los usuarios
     */  
    public function getUsers(string $select = '*') {
        return $this->db->select($select, 'administradores');
    }

    /**
     * Obtiene datos del usuario conectado actualmente
     *
     * @param string $select : Por defecto es *, se usa para obtener sólo los parámetros necesarios
     *
     * @throws ModelsException si el usuario no está logeado
     * @return array con datos del usuario conectado
     */
    public function getOwnerUser() {

        if(null !== $this->id_user) {    
               
            $user = $this->db->select('*','administradores',null, "id_user='$this->id_user'",1);

            # Si se borra al usuario desde la base de datos y sigue con la sesión activa
            if(false === $user) {
                $this->logout();
            }

            $perfiles = (new Model\Perfiles)->perfilesAdministrador($user[0]['id_user']);
            foreach ($perfiles as $key => $value) {

                $arrayP["perfiles"][] = array('id_perfil' => $value['id_perfil'], 'perfil' => $value['perfil'], 'principal' => $value['principal']);

            }

            $roles = $this->db->select('r.id_rol, r.rol, m.ruta', 'roles AS r', 'INNER JOIN roles_administradores AS ra ON r.id_rol=ra.id_rol INNER JOIN modulos AS m ON r.id_modulo=m.id_modulo ', "ra.id_user='".$user[0]['id_user']."'");
            /*$arrayR = [];*/
            if($roles){
                foreach ($roles as $key => $value) {
                    $arrayR["roles"][] = array('id_rol' => $value['id_rol'], 'rol' => $value['rol'], 'ruta' => $value['ruta']);
                }
            }

            return array_merge_recursive($user[0], $arrayP, $arrayR);

        }else{
            return false;
        } 
        
        /*throw new \RuntimeException('El usuario no está logeado.');*/
    }

     /**
     * Realiza la acción de login dentro del sistema
     *
     * @return array : Con información de éxito/falla al inicio de sesión.
     */
    public function login() : array {
        try {
            global $http,$config;

            # Definir de nuevo el control de intentos
            $this->setDefaultAttempts();   

            # Obtener los datos $_POST
            $email = strtolower($http->request->get('email'));
            $pass = $http->request->get('pass');
            $key_code = $http->request->get('key_code');

            $perfil = intval($http->request->get('perfil'));
            $perfilesBD = $this->db->select('id_perfil', 'perfiles');
            $perfiles = [];
            foreach ($perfilesBD as $key => $value) {
                $perfiles[] = (int) $value['id_perfil'];
            };

            $almacen = intval($http->request->get('almacen'));
            $almacenesBD = $this->db->select('id_almacen', 'almacenes');
            $almacenes = [];
            foreach ($almacenesBD as $key => $value) {
                $almacenes[] = (int) $value['id_almacen'];
            };

            $secret = '6Ld3NcwUAAAAAPXOJhU2h2M4RyEqeE3JTBAMrJKl';
            $response = $http->request->get('g-recaptcha-response');
            $result = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
            $array = json_decode($result,true);
            # Verificar que no sea un robot
            if($array["success"] === false){
                throw new ModelsException('Confirme el reCaptcha.');  
            }

            # Verificar que no están vacíos
            if (Helper\Functions::e($email, $pass)) {
                throw new ModelsException('Introduce tus datos para iniciar sesión e intenta nuevamente.');
            }
            
            # Añadir intentos
            $this->setNewAttempt($email);
        
            # Verificar intentos 
            $this->maximumAttempts($email);

            if(!in_array($perfil, $perfiles)){
                throw new ModelsException('El perfil no está definido, por favor selecciona un perfil de la lista de perfiles disponibles.');
            }

            if($perfil == 4){

                if(!in_array($almacen, $almacenes)){
                    throw new ModelsException('El almacén no está definido, por favor selecciona un almacén de la lista de almacenes disponibles.');
                }

            }

            # Autentificar
            $autentificar = $this->authentication($email, $pass, $perfil, $almacen, $key_code);

            # Si se realiza la autenticación
            if ($autentificar) {

                $this->db->update('administradores_perfiles',array('principal' => 0), "id_user='".$autentificar['id_user']."'");
                $this->db->update('administradores_perfiles',array('principal' => 1), "id_user='".$autentificar['id_user']."' AND id_perfil='".$perfil."'");

                if($perfil == 4){
                    $this->db->update('administradores_almacenes',array('principal' => 0), "id_user='".$autentificar['id_user']."'");
                    $this->db->update('administradores_almacenes',array('principal' => 1), "id_user='".$autentificar['id_user']."' AND id_almacen='".$almacen."'");
                }

                $perfilActual = (new Model\Perfiles)->perfil($perfil);

                (new Model\Actividad)->registrarActividad('Historial', 'Inicio de sesión con el perfil '.$perfilActual['perfil'], $perfil, $autentificar['id_user'], null, date('Y-m-d H:i:s'), 0);
                
                if(!in_array($autentificar['id_user'], [1,2])){

                    $hora_entrada = date('H:i:s');
                    $dia_entrada = date('Y-m-d');
                    $entrada_registrada = $this->db->select('*', 'reporte_entradas', null, "id_user='".$autentificar['id_user']."' AND dia = '".$dia_entrada."'", 1);

                    if(!$entrada_registrada){
                        # Construir mensaje y enviar mensaje
                        $HTML = '<center>'.$autentificar['name'].'<br>'.'<b>'.$dia_entrada.'</b> | <b>'.$hora_entrada.'</b>.</center>';
        
                        # Enviar el correo electrónico
                        $dest = array();
                        $dest['reporte_entradas@eltimonlibreria.com'] = 'Reporte de entradas';
                        //$dest['montiel_989@hotmail.com'] = 'Rodolfo Montiel García';
                        $email_send = Helper\Emails::send($dest,array(
                            # Título del mensaje
                            '{{title}}' => $autentificar['name'].' entró a las '.$hora_entrada.'.',
                            # Asunto
                            '{{subject}}' => 'Día y hora de ingreso',
                            # Url de logo
                            '{{url_logo}}' => $config['build']['url'],
                            # Logo
                            '{{logo}}' => $config['mailer']['logo'],
                            # Contenido del mensaje
                            '{{content}} ' => $HTML,
                            # Copyright
                            '{{copyright}}' => '&copy; '.date('Y') .' <a href="'.$config['build']['url'].'">'.$config['build']['name'].'</a> - Todos los derechos reservados.'
                        ),1);
                        
                        $this->db->insert('reporte_entradas', array(
                            'id_user' => $autentificar['id_user'],
                            'dia' => $dia_entrada,
                            'hora' => $hora_entrada
                        ));

                        # Verificar si hubo algún problema con el envío del correo
                        if(false === $email_send) {
                            throw new ModelsException('No se ha podido enviar el correo electrónico.');
                        }
                    }

                }

                return array('success' => 1, 'title' => 'Iniciando sesión', 'message' => 'Espere un momento por favor...');
            }
            
            throw new ModelsException('Verifica tus datos para iniciar sesión e intenta nuevamente.');

        } catch (ModelsException $e) {
            return array('success' => 0, 'title' => '¡ERROR!', 'message' => $e->getMessage());
        }        
    }

    /**
     * Realiza la acción de registro dentro del sistema
     *
     * @return array : Con información de éxito/falla al registrar el usuario nuevo.
     */
    public function register() : array {
        try {
            global $http;

            # Obtener los datos $_POST
            $name = $http->request->get('name');
            $email = $http->request->get('email');
            $pass = $http->request->get('pass');
            $pass_repeat = $http->request->get('pass_repeat');

            # Verificar que no están vacíos
            if (Helper\Functions::e($name, $email, $pass, $pass_repeat)) {
                throw new ModelsException('Todos los datos son necesarios');
            }

            # Verificar email 
            $this->checkEmail($email);

            # Veriricar contraseñas
            $this->checkPassMatch($pass, $pass_repeat);

            # Registrar al usuario
            $id_user = $this->db->insert('administradores', array(
                'name' => $name,
                'email' => $email,
                'pass' => Helper\Strings::hash($pass)
            ));

            # Iniciar sesión
            $this->generateSession(array(
                'id_user' => $id_user
            ));

            return array('success' => 1, 'message' => 'Registrado con éxito.');
        } catch (ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
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
            $email = $http->request->get('email');
            
            # Campo lleno
            if (Helper\Functions::emp($email)) {
                throw new ModelsException('El campo email debe estar lleno.');
            }

            # Filtro
            $email = $this->db->scape($email);

            # Obtener información del usuario 
            $user_data = $this->db->select('id_user,name', 'administradores', null, "email='$email'", 1);

            # Verificar correo en base de datos 
            if (false === $user_data) {
                throw new ModelsException('El email no está registrado en el sistema.');
            }

            # Generar token y contraseña 
            $token = md5(time());
            $pass = uniqid();
            $link = $config['build']['url'] . 'lostpass?token='.$token.'&user='.$user_data[0]['id_user'];

            # Construir mensaje y enviar mensaje
            $HTML = 'Hola <b>'. $user_data[0]['name'] .'</b>, ha solicitado recuperar su contraseña perdida, si no ha realizado esta acción no necesita hacer nada.
					<br />
					<br />
					Para cambiar su contraseña por <b>'. $pass .'</b> haga <a href="'. $link .'" target="_blank">clic aquí</a> o en el botón de recuperar.';

            # Enviar el correo electrónico
            $dest = array();
			$dest[$email] = $user_data[0]['name'];
            $email_send = Helper\Emails::send($dest,array(
                # Título del mensaje
                '{{title}}' => 'Recuperar contraseña de ' . $config['build']['name'],
                # Url de logo
                '{{url_logo}}' => $config['build']['url'],
                # Logo
                '{{logo}}' => $config['mailer']['logo'],
                # Contenido del mensaje
                '{{content}} ' => $HTML,
                # Url del botón
                '{{btn-href}}' => $link,
                # Texto del boton
                '{{btn-name}}' => 'Recuperar Contraseña',
                # Copyright
                '{{copyright}}' => '&copy; '.date('Y') .' <a href="'.$config['build']['url'].'">'.$config['build']['name'].'</a> - Todos los derechos reservados.'
              ),0);

            # Verificar si hubo algún problema con el envío del correo
            if(false === $email_send) {
                throw new ModelsException('No se ha podido enviar el correo electrónico.');
            }

            # Actualizar datos 
            $id_user = $user_data[0]['id_user'];
            $this->db->update('administradores',array(
                'tmp_pass' => Helper\Strings::hash($pass),
                'token' => $token
            ),"id_user='$id_user'",1);

            return array('success' => 1, 'message' => 'Se ha enviado un enlace a su correo electrónico.');
        } catch(ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }

    /**
     * Desconecta a un usuario si éste está conectado, y lo devuelve al inicio
     *
     * @return void
     */    
    public function logout() {
        global $session, $cookie;

        $this->db->update('administradores',array('online' => 0),"id_user='".$this->id_user."'",1);
        /*(new Model\Actividad)->registrarActividad('Historial', 'Cierre de sesión', null, $this->id_user, null, date('Y-m-d H:i:s'), 0);*/
	    
        $session->remove($cookie->get('session_hash') . '__user_id');
        foreach($cookie->all() as $name => $value) {
            $cookie->remove($name);
        }

        Helper\Functions::redir();
    }

    /**
     * Cambia la contraseña de un usuario en el sistema, luego de que éste haya solicitado cambiarla.
     * Luego retorna al sitio de inicio con la variable GET success=(bool)
     *
     * La URL debe tener la forma URL/lostpass?token=TOKEN&user=ID
     *
     * @return void
     */  
    public function changeTemporalPass() {
        global $config, $http;
        
        # Obtener los datos $_GET 
        $id_user = $http->query->get('user');
        $token = $http->query->get('token');

        $success = false;
        if (!Helper\Functions::emp($token) && is_numeric($id_user) && $id_user >= 1) {
            # Filtros a los datos
            $id_user = $this->db->scape($id_user);
            $token = $this->db->scape($token);
            # Ejecutar el cambio
            $this->db->query("UPDATE users SET pass=tmp_pass, tmp_pass=NULL, token=NULL
            WHERE id_user='$id_user' AND token='$token' LIMIT 1;");
            # Éxito
            $success = true;
        }
        
        # Devolover al sitio de inicio
        Helper\Functions::redir($config['build']['url'] . '?sucess=' . (int) $success);
    }

    /**
      * Valida si la contraseña de usuario es correcta
      *
      * @param string $new_password : Contraseña sin encriptar
      *
      * @return true|false: false si no hay datos.
      *                     
    */
    final public function validatePassword(string $password, int $id_user) {
      
      
      $query = $this->db->select('pass','administradores',null, "id_user='$id_user'",1);

      if(false !== $query && Helper\Strings::chash($query[0]['pass'],$password)) {
        return true;
      } 
      return false;
    }

    /**
     * __construct()
     */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}