<?php

/* cuenta/mis-datos.twig */
class __TwigTemplate_2197a5e96ee2497bc635f6d6c3ef1862fbfe57772c8f7444eaa4be8834eb9e7b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "cuenta/mis-datos.twig", 1);
        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
            'appBody' => array($this, 'block_appBody'),
            'appFooter' => array($this, 'block_appFooter'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "overall/layout";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_appTitle($context, array $blocks = array())
    {
        // line 4
        echo "    <title>Mis datos</title>
";
    }

    // line 7
    public function block_appBody($context, array $blocks = array())
    {
        // line 8
        echo "
    ";
        // line 9
        $this->loadTemplate("cuenta/menu", "cuenta/mis-datos.twig", 9)->display($context);
        // line 10
        echo "
    <main class=\"container\">
        <div class=\"row div_datos\">
            <div class=\"col-12 col-md-6 col-lg-4 mt-3 div_form_data\">
                <form role=\"form\" id=\"form_data\">
                    <div class=\"card bg-white border animated fadeIn z-depth-2\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-2\">
                                Datos personales
                            </h4>
                            <div class=\"div_loading_data\">
                                <div class=\"spinner-grow bg-app mx-auto\" role=\"status\" aria-hidden=\"true\"></div>
                            </div>
                            <div class=\"row div_data_inputs\">
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-0\">Correo electrónico</small>
                                    <input type=\"email\" class=\"form-control form-control-sm px-2 d_mail\" value=\"\" disabled>
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-0\">Nombre completo</small>
                                    <input type=\"text\" name=\"d_name\" class=\"form-control form-control-sm px-2 d_name\" value=\"\">
                                </div>
                                <div class=\"col-12 col-sm-6 col-md-12 col-lg-7 pr-3 pr-sm-1 pr-md-3 pr-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">RFC</small>
                                    <input type=\"text\" name=\"d_rfc\" class=\"form-control form-control-sm px-2 d_rfc\" value=\"\">
                                </div>
                                <div class=\"col-12 col-sm-6 col-md-12 col-lg-5 pl-3 pl-sm-1 pl-md-3 pl-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Teléfono</small>
                                    <input type=\"text\" name=\"d_phone\" class=\"form-control form-control-sm px-2 d_phone\" value=\"\">
                                </div>
                            </div>
                        </div>
                        
                        <div class=\"card-footer text-right\">
                            <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_data\" disabled>
                                <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                <span class=\"btn-inner--text\">Guardar</span>
                                <i class=\"fas fa-save\"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class=\"col-12 col-md-6 col-lg-8 mt-3 div_form_address\">
                <form role=\"form\" id=\"form_address\">
                    <div class=\"card bg-white border animated fadeIn z-depth-2\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-2\">
                                Dirección de envío <small class=\"text-muted\">(exclusivo para México)</small>
                            </h4>
                            <div class=\"div_loading_data\">
                                <div class=\"spinner-grow bg-app mx-auto\" role=\"status\" aria-hidden=\"true\"></div>
                            </div>
                            <div class=\"row div_data_inputs\">
                                <div class=\"col-sm-3 col-md-12 col-lg-2 pr-sm-1 pr-md-3 pr-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Código postal</small>
                                    <input type=\"text\" name=\"p_code\" class=\"form-control form-control-sm p_code\">
                                </div>
                                <div class=\"col-sm-9 col-md-12 col-lg-5 pl-sm-1 pl-md-3 px-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Estado</small>
                                    <select class=\"selectpicker state\" name=\"state\" title=\"Selecciona\" data-live-search=\"true\" data-live-search-placeholder=\"Buscar ...\">
                                        <option value=\"1\">Aguascalientes</option>
                                        <option value=\"2\">Baja California</option>
                                        <option value=\"3\">Baja California Sur</option>
                                        <option value=\"4\">Campeche</option>
                                        <option value=\"5\">Coahuila</option>
                                        <option value=\"6\">Colima</option>
                                        <option value=\"7\">Chiapas</option>
                                        <option value=\"8\">Chihuahua</option>
                                        <option value=\"9\">Ciudad de México</option>
                                        <option value=\"10\">Durango</option>
                                        <option value=\"11\">Guanajuato</option>
                                        <option value=\"12\">Guerrero</option>
                                        <option value=\"13\">Hidalgo</option>
                                        <option value=\"14\">Jalisco</option>
                                        <option value=\"15\">México</option>
                                        <option value=\"16\">Michoacán</option>
                                        <option value=\"17\">Morelos</option>
                                        <option value=\"18\">Nayarit</option>
                                        <option value=\"19\">Nuevo León</option>
                                        <option value=\"20\">Oaxaca</option>
                                        <option value=\"21\">Puebla</option>
                                        <option value=\"22\">Querétaro</option>
                                        <option value=\"23\">Quintana Roo</option>
                                        <option value=\"24\">San Luis Potosí</option>
                                        <option value=\"25\">Sinaloa</option>
                                        <option value=\"26\">Sonora</option>
                                        <option value=\"27\">Tabasco</option>
                                        <option value=\"28\">Tamaulipas</option>
                                        <option value=\"29\">Tlaxcala</option>
                                        <option value=\"30\">Veracruz</option>
                                        <option value=\"31\">Yucatán</option>
                                        <option value=\"32\">Zacatecas</option>
                                        <option disabled></option>
                                    </select>
                                </div>
                                <div class=\"col-lg-5 pl-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Delegación o municipio</small>
                                    <input type=\"text\" name=\"municipality\" class=\"form-control form-control-sm municipality\">
                                </div>
                                <div class=\"col-lg-5 pr-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Colonia</small>
                                    <input type=\"text\" name=\"colony\" class=\"form-control form-control-sm colony\">
                                </div>
                                <div class=\"col-sm-9 col-md-12 col-lg-5 pl-sm-3 pr-sm-1 pl-md-3 px-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Calle</small>
                                    <input type=\"text\" name=\"street\" class=\"form-control form-control-sm street\">
                                </div>
                                <div class=\"col-sm-3 col-md-12 col-lg-2 pl-sm-1 pl-md-3 pl-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">N. ext.</small>
                                    <input type=\"text\" name=\"o_number\" class=\"form-control form-control-sm o_number\">
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-0\">Entre que calles</small>
                                    <textarea class=\"form-control form-control-sm textarea-autosize b_streets\" name=\"b_streets\" rows=\"2\"></textarea>
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-0\">Referencias</small>
                                    <textarea class=\"form-control form-control-sm textarea-autosize a_references\" name=\"a_references\" rows=\"3\"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class=\"card-footer text-right\">
                            <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_address\" disabled>
                                <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                <span class=\"btn-inner--text\">Guardar</span>
                                <i class=\"fas fa-save\"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <div>
    </main>
";
    }

    // line 146
    public function block_appFooter($context, array $blocks = array())
    {
        // line 147
        echo "    <script src=\"assets/plugins/textarea-autosize/textarea-autosize.min.js\"></script>
    <script src=\"assets/plugins/bootstrap-select/js/bootstrap-select.min.js\"></script>
\t<script src=\"assets/plugins/Inputmask-5.x/jquery.inputmask.min.js\"></script>
    <script src=\"assets/jscontrollers/cuenta/mis-datos.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "cuenta/mis-datos.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  187 => 147,  184 => 146,  46 => 10,  44 => 9,  41 => 8,  38 => 7,  33 => 4,  30 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}

{% block appTitle %}
    <title>Mis datos</title>
{% endblock %}

{% block appBody %}

    {% include 'cuenta/menu' %}

    <main class=\"container\">
        <div class=\"row div_datos\">
            <div class=\"col-12 col-md-6 col-lg-4 mt-3 div_form_data\">
                <form role=\"form\" id=\"form_data\">
                    <div class=\"card bg-white border animated fadeIn z-depth-2\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-2\">
                                Datos personales
                            </h4>
                            <div class=\"div_loading_data\">
                                <div class=\"spinner-grow bg-app mx-auto\" role=\"status\" aria-hidden=\"true\"></div>
                            </div>
                            <div class=\"row div_data_inputs\">
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-0\">Correo electrónico</small>
                                    <input type=\"email\" class=\"form-control form-control-sm px-2 d_mail\" value=\"\" disabled>
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-0\">Nombre completo</small>
                                    <input type=\"text\" name=\"d_name\" class=\"form-control form-control-sm px-2 d_name\" value=\"\">
                                </div>
                                <div class=\"col-12 col-sm-6 col-md-12 col-lg-7 pr-3 pr-sm-1 pr-md-3 pr-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">RFC</small>
                                    <input type=\"text\" name=\"d_rfc\" class=\"form-control form-control-sm px-2 d_rfc\" value=\"\">
                                </div>
                                <div class=\"col-12 col-sm-6 col-md-12 col-lg-5 pl-3 pl-sm-1 pl-md-3 pl-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Teléfono</small>
                                    <input type=\"text\" name=\"d_phone\" class=\"form-control form-control-sm px-2 d_phone\" value=\"\">
                                </div>
                            </div>
                        </div>
                        
                        <div class=\"card-footer text-right\">
                            <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_data\" disabled>
                                <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                <span class=\"btn-inner--text\">Guardar</span>
                                <i class=\"fas fa-save\"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class=\"col-12 col-md-6 col-lg-8 mt-3 div_form_address\">
                <form role=\"form\" id=\"form_address\">
                    <div class=\"card bg-white border animated fadeIn z-depth-2\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-2\">
                                Dirección de envío <small class=\"text-muted\">(exclusivo para México)</small>
                            </h4>
                            <div class=\"div_loading_data\">
                                <div class=\"spinner-grow bg-app mx-auto\" role=\"status\" aria-hidden=\"true\"></div>
                            </div>
                            <div class=\"row div_data_inputs\">
                                <div class=\"col-sm-3 col-md-12 col-lg-2 pr-sm-1 pr-md-3 pr-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Código postal</small>
                                    <input type=\"text\" name=\"p_code\" class=\"form-control form-control-sm p_code\">
                                </div>
                                <div class=\"col-sm-9 col-md-12 col-lg-5 pl-sm-1 pl-md-3 px-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Estado</small>
                                    <select class=\"selectpicker state\" name=\"state\" title=\"Selecciona\" data-live-search=\"true\" data-live-search-placeholder=\"Buscar ...\">
                                        <option value=\"1\">Aguascalientes</option>
                                        <option value=\"2\">Baja California</option>
                                        <option value=\"3\">Baja California Sur</option>
                                        <option value=\"4\">Campeche</option>
                                        <option value=\"5\">Coahuila</option>
                                        <option value=\"6\">Colima</option>
                                        <option value=\"7\">Chiapas</option>
                                        <option value=\"8\">Chihuahua</option>
                                        <option value=\"9\">Ciudad de México</option>
                                        <option value=\"10\">Durango</option>
                                        <option value=\"11\">Guanajuato</option>
                                        <option value=\"12\">Guerrero</option>
                                        <option value=\"13\">Hidalgo</option>
                                        <option value=\"14\">Jalisco</option>
                                        <option value=\"15\">México</option>
                                        <option value=\"16\">Michoacán</option>
                                        <option value=\"17\">Morelos</option>
                                        <option value=\"18\">Nayarit</option>
                                        <option value=\"19\">Nuevo León</option>
                                        <option value=\"20\">Oaxaca</option>
                                        <option value=\"21\">Puebla</option>
                                        <option value=\"22\">Querétaro</option>
                                        <option value=\"23\">Quintana Roo</option>
                                        <option value=\"24\">San Luis Potosí</option>
                                        <option value=\"25\">Sinaloa</option>
                                        <option value=\"26\">Sonora</option>
                                        <option value=\"27\">Tabasco</option>
                                        <option value=\"28\">Tamaulipas</option>
                                        <option value=\"29\">Tlaxcala</option>
                                        <option value=\"30\">Veracruz</option>
                                        <option value=\"31\">Yucatán</option>
                                        <option value=\"32\">Zacatecas</option>
                                        <option disabled></option>
                                    </select>
                                </div>
                                <div class=\"col-lg-5 pl-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Delegación o municipio</small>
                                    <input type=\"text\" name=\"municipality\" class=\"form-control form-control-sm municipality\">
                                </div>
                                <div class=\"col-lg-5 pr-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Colonia</small>
                                    <input type=\"text\" name=\"colony\" class=\"form-control form-control-sm colony\">
                                </div>
                                <div class=\"col-sm-9 col-md-12 col-lg-5 pl-sm-3 pr-sm-1 pl-md-3 px-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">Calle</small>
                                    <input type=\"text\" name=\"street\" class=\"form-control form-control-sm street\">
                                </div>
                                <div class=\"col-sm-3 col-md-12 col-lg-2 pl-sm-1 pl-md-3 pl-lg-1\">
                                    <small class=\"form-text text-muted mb-0\">N. ext.</small>
                                    <input type=\"text\" name=\"o_number\" class=\"form-control form-control-sm o_number\">
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-0\">Entre que calles</small>
                                    <textarea class=\"form-control form-control-sm textarea-autosize b_streets\" name=\"b_streets\" rows=\"2\"></textarea>
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-0\">Referencias</small>
                                    <textarea class=\"form-control form-control-sm textarea-autosize a_references\" name=\"a_references\" rows=\"3\"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class=\"card-footer text-right\">
                            <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_address\" disabled>
                                <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                <span class=\"btn-inner--text\">Guardar</span>
                                <i class=\"fas fa-save\"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <div>
    </main>
{% endblock %}

{% block appFooter %}
    <script src=\"assets/plugins/textarea-autosize/textarea-autosize.min.js\"></script>
    <script src=\"assets/plugins/bootstrap-select/js/bootstrap-select.min.js\"></script>
\t<script src=\"assets/plugins/Inputmask-5.x/jquery.inputmask.min.js\"></script>
    <script src=\"assets/jscontrollers/cuenta/mis-datos.js\"></script>
{% endblock %}", "cuenta/mis-datos.twig", "/home4/eltimonl/public_html/app/templates/cuenta/mis-datos.twig");
    }
}
