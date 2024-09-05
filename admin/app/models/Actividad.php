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
 * Modelo Actividad
 */
class Actividad extends Models implements IModels {
    use DBModel;

    public function registrarActividad($tipo, $texto, $perfil, $administrador, $modulo, $fecha, $estado){

    	if(is_null($perfil) && is_null($modulo)){

    		$this->db->insert('actividad', array(
	    		'tipo' => $tipo,
	    		'texto' => $texto,
	    		'id_user' => $administrador,
	    		'fecha' => $fecha,
	    		'estado' => $estado
	    	));

    	}elseif(is_null($perfil) && !is_null($modulo)){

	    	$this->db->insert('actividad', array(
                'tipo' => $tipo,
	    		'texto' => $texto,
	    		'id_user' => $administrador,
	    		'id_modulo' => $modulo,
	    		'fecha' => $fecha,
	    		'estado' => $estado
            ));

    	}elseif(is_null($modulo) && !is_null($perfil)){

	    	$this->db->insert('actividad', array(
                'tipo' => $tipo,
	    		'texto' => $texto,
	    		'id_perfil' => $perfil,
	    		'id_user' => $administrador,
	    		'fecha' => $fecha,
	    		'estado' => $estado
            ));

    	}else{

    		$this->db->insert('actividad', array(
	    		'tipo' => $tipo,
	    		'texto' => $texto,
	    		'id_perfil' => $perfil,
	    		'id_user' => $administrador,
	    		'id_modulo' => $modulo,
	    		'fecha' => $fecha,
	    		'estado' => $estado
	    	));
	    	
    	}

    }

    public function eventos() {
    	global $http;

    	$ver = $http->request->get('ver');
        $id_evento = intval($http->request->get('evento'));
    	$idPerfilConectado = intval($http->request->get('idPerfilConectado'));

        if($ver == 'visto'){
            $this->db->update('actividad', array('estado' => '1'), "id='$id_evento'");
        }
        
        $today = date('Y-m-d');
        
    	$eventos = $this->db->select('*', 'actividad', null, "tipo='Evento' AND id_perfil = '$idPerfilConectado' AND fecha >= '$today' ORDER BY id DESC");

    	$html = '';

    	if($eventos){

    		$cant = count($eventos);

    		$plural = (count($eventos) == 1) ? '': 's';
            $totalEventos = "Hoy <b>$cant</b> movimiento$plural registrado$plural en la BD";

    		$html .= '
    			<li class="header text-center" style="font-weight:500;">
    				'.$totalEventos.'
    			</li>

              	<li>
                	<ul class="menu">';

                		foreach ($eventos as $key => $value) {
                			$modulos = $this->db->select('icono', 'modulos', null, "id_modulo = '{$value['id_modulo']}'");
                			$icono = $modulos[0]['icono'];
                			if($value['estado'] == 0){
                				$visto = 'font-weight:bold; cursor:pointer;';
                                $id_evento = 'evento="'.$value['id'].'"';
                			}else{
                				$visto = 'color: #999 !important;';
                                $id_evento = 'evento="null"';
                			}
                			$html .= '
	                			<li title="'.$value['texto'].'" class="movimientosBD" '.$id_evento.'>
									<a class="miEvento" style="'.$visto.'" '.$id_evento.'>
										<small><i class="'.$icono.'" style="margin-right:5px;"></i> '.$value['texto'].'</small>
									</a>
	                			</li>';	
                		}

                $html .= ' 		
                	</ul>
              	</li>'; 

    	}else{
    		$html = '
    		<li class="header text-center">
				No hay movimientos registrados en la BD
			</li>';
    	}

    	$eventosSinVer = $this->db->select('*', 'actividad', null, "tipo = 'Evento' AND id_perfil = '$idPerfilConectado' AND estado = '0' AND fecha >= '$today'");
    	if($eventosSinVer){
    		$totalSinVer = count($eventosSinVer);
    	}else{
    		$totalSinVer = 0;
    	}
    	

    	return array('eventos' => $html, 'eventos_sin_ver' => $totalSinVer);
    }


    public function notificaciones() {
        global $http;

        $ver = $http->request->get('ver');
        $id_notificacion = intval($http->request->get('notificacion'));
        $idPerfilConectado = intval($http->request->get('idPerfilConectado'));

        if($ver == 'visto'){
            $this->db->update('actividad', array('estado' => '1'), "id='$id_notificacion'");
        }
        
        $today = date('Y-m-d');
        
        $notificaciones = $this->db->select('*', 'actividad', null, "tipo='Notificacion' AND id_perfil = '$idPerfilConectado' AND fecha >= '$today' ORDER BY id DESC");

        $html = '';

        if($notificaciones){

            $cant = count($notificaciones);

            $totalNotificaciones = "Hoy $cant Notificaci";
            $plural = (count($notificaciones) == 1) ? 'ón': 'ones';

            $html .= '
                <li class="header text-center" style="font-weight:500;">
                    '.$totalNotificaciones.$plural.'
                </li>

                <li>
                    <ul class="menu">';

                        foreach ($notificaciones as $key => $value) {
                            $modulos = $this->db->select('icono, modulo', 'modulos', null, "id_modulo = '{$value['id_modulo']}'");
                            $icono = $modulos[0]['icono'];
                            $modulo = $modulos[0]['modulo'];
                            if($value['estado'] == 0){
                                $visto_enlace = 'cursor:pointer;';
                                $visto_color = 'color:rgb(68, 68, 68) !important;';
                                $visto_fuente = 'font-weight: bold;';
                                $visto_color2 = '';
                                $id_notificacion = 'notificacion="'.$value['id'].'"';
                            }else{
                                $visto_enlace = '';
                                $visto_color = 'color: #999 !important;';
                                $visto_fuente = '';
                                $visto_color2 = $visto_color;
                                $id_notificacion = '';
                            }
                            $html .= '
                                <li title="'.$value['texto'].'">
                                    <a class="miNotificacion" '.$id_notificacion.' style="'.$visto_enlace.'">
                                        <div class="pull-left">
                                            <i class="'.$icono.'" style="'.$visto_color.'"></i>
                                        </div>
                                        <h4 style="'.$visto_color.'">
                                            '.$modulo.'
                                            <small><i class="fa fa-clock-o"></i> '.Helper\Strings::amigable_time(strtotime($value['fecha'])).'</small>
                                        </h4>
                                        <p style="'.$visto_fuente.' '.$visto_color2.'">'.$value['texto'].'</p>
                                    </a>
                                </li>'; 
                        }

                $html .= '      
                    </ul>
                </li>'; 

        }else{
            $html = '
            <li class="header text-center">
                No hay notificaciones
            </li>';
        }

        $notificacionesSinVer = $this->db->select('*', 'actividad', null, "tipo = 'Notificacion' AND id_perfil = '$idPerfilConectado' AND estado = '0' AND fecha >= '$today'");
        if($notificacionesSinVer){
            $totalSinVer = count($notificacionesSinVer);
        }else{
            $totalSinVer = 0;
        }
        

        return array('notificaciones' => $html, 'notificaciones_sin_ver' => $totalSinVer);
    }

    public function historial($id){
        $historial = $this->db->select("*", "actividad", null, "id_user='$id'", null, "ORDER BY id DESC");
        return $historial;
    }

    /**
     * __construct()
    */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
		$this->startDBConexion();
    }
}