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
 * Modelo Menu
 */
class Menu extends Models implements IModels {
    use DBModel;

    public function modulos($perfil){
    	if($perfil != 0){
    		$modulos = $this->db->select('m.id_modulo, m.modulo, m.ruta, m.icono','modulos AS m', "INNER JOIN perfiles_modulos AS pm ON pm.id_modulo=m.id_modulo", "m.id_modulo_padre IS NULL AND pm.id_perfil='$perfil'");
    	}else{
    		$modulos = $this->db->select('DISTINCT m.id_modulo, m.modulo, m.ruta, m.icono','modulos AS m', null, "m.id_modulo_padre IS NULL");
    	}
        return $modulos;
    }

    public function submodulos($id, $perfil){
    	if($perfil != 0){
        	$submodulos = $this->db->select('m.id_modulo, m.modulo, m.ruta, m.icono','modulos AS m', "INNER JOIN perfiles_modulos AS pm ON pm.id_modulo=m.id_modulo", "m.id_modulo_padre = '$id' AND pm.id_perfil='$perfil'");
    	}else{
    		$submodulos = $this->db->select('DISTINCT m.id_modulo, m.modulo, m.ruta, m.icono','modulos AS m', null, "m.id_modulo_padre = '$id'");
    	}
        return $submodulos;
    }

    public function getMenuUser(){

        $perfilP = (new Model\Perfiles)->perfilPrincipal($this->id_user);

        $modulos = $this->modulos((int) $perfilP['id_perfil']);
        
        if($modulos){

        	# Inicia menú
            $menuHtml = '
            <ul class="sidebar-menu" data-widget="tree">';
		$menuHtml .= '<li class="header text-uppercase text-center">MENÚ DE OPCIONES</li>';
            foreach ($modulos as $key => $value) {

                $submodulos = $this->submodulos((int) $value['id_modulo'], (int) $perfilP['id_perfil']);

                if($submodulos && count($submodulos) > 0){

                    $menuHtml .= "
                    			<li class='treeview' title='{$value['modulo']}'>
                    				<a href='{$value['ruta']}'>
                    					<i class='{$value['icono']}' title='{$value['modulo']}'></i> <span>{$value['modulo']}</span>
                    					<span class='pull-right-container'>
							              <i class='fa fa-angle-left pull-right'></i>
							            </span>
                    				</a>
                    				<ul class='treeview-menu'>";

                            foreach ($submodulos as $key2 => $value2) {
                                $menuHtml .= "
                                			<li title='{$value2['modulo']}'>
                                				<a href='{$value2['ruta']}'>
                                					<i class='{$value2['icono']}'></i> {$value2['modulo']}</span>
                                				</a>
                                			</li>";
                            }
                        $menuHtml .= '
                        			</ul>
                        		</li>';

                }else{

                    $menuHtml .= "
                    			<li title='{$value['modulo']}'>
                    				<a href='{$value['ruta']}'>
                    					<i class='{$value['icono']}'></i> <span> {$value['modulo']}</span>";
                    					if($value['id_modulo'] == 8){
                    					    
                    					    $comprasAprovadas = $this->db->select("COUNT(id) AS succeeded", "compras_stripe", null, "estatus = 'succeeded'"); 
                    					    $totalAprovadas = (int) $comprasAprovadas[0]['succeeded'];
                    					    if($totalAprovadas > 0){
                    					        $spanAprovadas = '<small class="label pull-right bg-light-blue spanAprovadas">'.$totalAprovadas.'</small>';
                    					    }else{
                    					        $spanAprovadas = '';
                    					    }
                    					    
                    					    $comprasPreparadas = $this->db->select("COUNT(id) AS prepared", "compras_stripe", null, "estatus = 'prepared'");
                    					    $totalPreparadas = (int) $comprasPreparadas[0]['prepared'];
                    					    if($totalPreparadas > 0){
                    					        $spanPreparadas = '<small class="label pull-right bg-aqua spanPreparadas">'.$totalPreparadas.'</small>';
                    					    }else{
                    					        $spanPreparadas = '';
                    					    }
                    					    
                    					    $menuHtml .= '
                    					    <span class="pull-right-container">
                                                '.$spanPreparadas.'
                                                '.$spanAprovadas.'
                                            </span>'; 
                    					}
                    $menuHtml .= "</a>
                    			</li>";

                }
            }
	    if($perfilP['id_perfil'] == 4){
	    	$menuHtml .= '<li class="header text-uppercase text-center">Accesos directos</li>
	    		<li><a href="registrarCompras" class="bg-purple"><i class="fas fa-barcode"></i> <span>Registrar entrada</span></a></li>
	    		<!--<li><a href="registrarCredito" class="bg-orange"><i class="fas fa-barcode"></i> <span>Registrar crédito</span></a></li>-->
	    		<li><a href="puntoDeVenta" class="bg-aqua"><i class="fas fa-barcode"></i> <span>Registrar salida</span></a></li>';
	    }
            # Termina menú
            $menuHtml .= '</ul>';

            return $menuHtml;
        }

        return false;
    }

    public function menu() {

    	$modulos = $this->modulos(null);

    	if($modulos){

    		$menuHtml = '<ul class="list-unstyled text-muted">';

    			foreach ($modulos as $key => $value) {

    				$submodulos = $this->submodulos((int) $value['id_modulo'], null);
    				
    				if($submodulos && count($submodulos) > 0){

    					$menuHtml .= "
    						<li style='padding: 5px 0;'>
								<input type='checkbox' value='{$value['id_modulo']}' name='modulos[]' id='modulo{$value['id_modulo']}'> 
									<small>
										<label class='badge bg-black' style='margin-top:-1px; padding-bottom:4px; margin-left:3px;'for='modulo{$value['id_modulo']}'>
											{$value['modulo']}
										</label>
									</small>
								<ul class='list-unstyled' style='padding: 5px 0;'>";

								foreach ($submodulos as $key2 => $value2) {
									$menuHtml .= "
									<li style='margin-left:25px; padding: 5px 0;'>										
										<input type='checkbox' value='{$value2['id_modulo']}' name='modulos[]' id='modulo{$value2['id_modulo']}'>
											<small>
												<label class='badge bg-gray' style='margin-top:-1px; padding-bottom:4px; margin-left:3px;'for='modulo{$value2['id_modulo']}'>
													{$value2['modulo']}
												</label>
											</small>											
									</li>";
								}
									
						$menuHtml .= "
								</ul>
    						</li>";

    				}else{

    					$menuHtml .= "
	    					<li style='padding: 5px 0;'>
	        					<input type='checkbox' value='{$value['id_modulo']}' name='modulos[]' id='modulo{$value['id_modulo']}'> 
	        						<small>
	        							<label class='badge bg-black' style='margin-top:-1px; padding-bottom:4px; margin-left:3px;'for='modulo{$value['id_modulo']}'>
	        								{$value['modulo']}
	        							</label>
	        						</small>
	      					</li>";

    				}

    			}

    		$menuHtml .= '</ul>';

    		return $menuHtml;

    	}

    	return false;

    }

    public function menuPerfil($id_perfil) {

    	$modulos = $this->modulos(null);

    	if($modulos){

    		$menuHtml = '<ul class="list-unstyled text-muted">';

    			foreach ($modulos as $key => $value) {

    				$mAsignado = $this->db->select('*', 'perfiles_modulos', null, "id_perfil = '$id_perfil' AND id_modulo = '{$value['id_modulo']}'", 1);
    				$mChecked = ($mAsignado) ? 'checked': '';

    				$submodulos = $this->submodulos((int) $value['id_modulo'], null);
    				$submodulosAsignados = $this->submodulos((int) $value['id_modulo'], (int) $id_perfil);
    				
    				if($submodulos && count($submodulos) > 0){

    					if($submodulosAsignados){
    						$c_submodulosAsignados = count($submodulosAsignados);
    					}else{
    						$c_submodulosAsignados = 0;
    					}

    					if(count($submodulos) != $c_submodulosAsignados){
    						$mChecked = '';
    						$clase = 'indeterminate';
    					}else{
    						$mChecked = 'checked';
    						$clase = '';
    					}

    					if($c_submodulosAsignados == 0){
    						$mChecked = '';
    						$clase = '';
    					}

    					$menuHtml .= "
    						<li style='padding: 5px 0;'>
								<input type='checkbox' value='{$value['id_modulo']}' name='modulos[]' id='modulo{$value['id_modulo']}' $mChecked class='$clase'> 
									<small>
										<label class='badge bg-black' style='margin-top:-1px; padding-bottom:4px; margin-left:3px;'for='modulo{$value['id_modulo']}'>
											{$value['modulo']}
										</label>
									</small>
								<ul class='list-unstyled' style='padding: 5px 0;'>";

								foreach ($submodulos as $key2 => $value2) {

									$smAsignado = $this->db->select('*', 'perfiles_modulos', null, "id_perfil = '$id_perfil' AND id_modulo = '{$value2['id_modulo']}'", 1);
    								$smChecked = ($smAsignado) ? 'checked': '';

									$menuHtml .= "
									<li style='margin-left:25px; padding: 5px 0;'>										
										<input type='checkbox' value='{$value2['id_modulo']}' name='modulos[]' id='modulo{$value2['id_modulo']}' $smChecked>
											<small>
												<label class='badge bg-gray' style='margin-top:-1px; padding-bottom:4px; margin-left:3px;'for='modulo{$value2['id_modulo']}'>
													{$value2['modulo']}
												</label>
											</small>											
									</li>";
								}
									
						$menuHtml .= "
								</ul>
    						</li>";

    				}else{

    					$menuHtml .= "
	    					<li style='padding: 5px 0;'>
	        					<input type='checkbox' value='{$value['id_modulo']}' name='modulos[]' id='modulo{$value['id_modulo']}' $mChecked> 
	        						<small>
	        							<label class='badge bg-black' style='margin-top:-1px; padding-bottom:4px; margin-left:3px;'for='modulo{$value['id_modulo']}'>
	        								{$value['modulo']}
	        							</label>
	        						</small>
	      					</li>";

    				}

    			}

    		$menuHtml .= '</ul>';

    		return $menuHtml;

    	}

    	return false;
    }

    public function module_access($administrador, $ruta) {

        $modulo = $this->db->select('id_modulo','modulos', null, "ruta='$ruta'", 1);
        if($modulo){
            $id_modulo = $modulo[0]['id_modulo'];
            foreach ($administrador['perfiles'] as $key => $value) {
                if($value['principal'] == 1){
                    $id_perfil = $value['id_perfil'];
                }
            }
            $module_access = $this->db->select('*', 'perfiles_modulos', null, "id_perfil = '$id_perfil' AND id_modulo = '$id_modulo'", 1);
            if($module_access){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
    
    /**
     * Obtener los datos del modulo segun la ruta
     * 
     * Si no hay resultados retorna false
    */
    public function datosModulo($ruta){
        $datosModulo = $this->db->select('*', 'modulos', null, "ruta = '$ruta'");
        if($datosModulo){
            return $datosModulo[0];
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