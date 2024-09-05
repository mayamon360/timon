<?php

/* cuenta/cuenta.twig */
class __TwigTemplate_e40ae6c43d1cb600e42e9c8a794532f40036c5a3a51b0faaf0fe38c02ad19147 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "cuenta/cuenta.twig", 1);
        $this->blocks = array(
            'appHeader' => array($this, 'block_appHeader'),
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
    public function block_appHeader($context, array $blocks = array())
    {
        // line 5
        echo "<style>
.nav-scroller {
    position: relative;
    z-index: 2;
    overflow-y: hidden;
}

.nav-scroller .nav {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    margin-top: -1px;
    overflow-x: auto;
    color: rgba(255, 255, 255, .75);
    text-align: center;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}
</style>

";
    }

    // line 29
    public function block_appBody($context, array $blocks = array())
    {
        // line 30
        echo "

    <div class=\"container-fluid mt-0 z-depth-1\">
        <div class=\"container animated fadeIn\">
        
            <div class=\"nav-scroller py-1 mb-2\">
                <nav class=\"nav d-flex justify-content-between\">
                    <a class=\"p-2 link_datos\" href=\"";
        // line 37
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/mis-datos\"><i class=\"fas fa-user-cog fa-lg mr-1\"></i> Mis datos</a>
                    <a class=\"p-2 link_deseos\" href=\"";
        // line 38
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/lista-deseos\"><i class=\"fas fa-heart fa-lg mr-1\"></i> Mi lista de deseos</a>
                    <a class=\"p-2 link_libros\" href=\"";
        // line 39
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/mis-libros\"><i class=\"fas fa-book-open fa-lg mr-1\"></i> Mis libros</a>
                    <a class=\"p-2 link_cambiar_clave\" href=\"";
        // line 40
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/cambiar-contrasena\"><i class=\"fas fa-user-lock fa-lg mr-1\"></i> Cambiar contraseña</a>
                    <a class=\"p-2 link_eliminar\" href=\"";
        // line 41
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/eliminar-cuenta\"><i class=\"fas fa-user-times fa-lg mr-1\"></i> Eliminar cuenta</a>
                </nav>
            </div>

        </div>
    </div>

    <main class=\"container\">
        <div class=\"row div_datos\">
            <div class=\"col-12 col-md-6 col-lg-4 mt-5 div_form_data\">
                <form role=\"form\" id=\"form_data\">
                    <div class=\"card bg-white border animated fadeIn z-depth-2\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-3\">
                                Datos personales
                            </h4>
                            <div class=\"form-group disabled\">
                                <small class=\"form-text text-muted mb-2\">Correo electrónico</small>
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"email\" iname=\"d_mail\" class=\"form-control form-control-sm\" value=\"";
        // line 60
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["owner_user"] ?? null), "correoElectronico", array()), "html", null, true);
        echo "\" disabled>
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <small class=\"form-text text-muted mb-2\">Nombre completo</small>
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"text\" name=\"d_name\" class=\"form-control form-control-sm\" value=\"";
        // line 69
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["owner_user"] ?? null), "cliente", array()), "html", null, true);
        echo "\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-user\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <small class=\"form-text text-muted mb-2\">RFC</small>
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"text\" name=\"d_rfc\" class=\"form-control form-control-sm\" value=\"";
        // line 78
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["owner_user"] ?? null), "rfc", array()), "html", null, true);
        echo "\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-address-card\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group mb-0\">
                                <small class=\"form-text text-muted mb-2\">Teléfono</small>
                                <div class=\"input-group input-group-transparent\">
                                    <input type=\"text\" name=\"d_phone\" class=\"form-control form-control-sm\" value=\"";
        // line 87
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["owner_user"] ?? null), "telefono", array()), "html", null, true);
        echo "\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-phone\"></i></span>
                                    </div>
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
            <div class=\"col-12 col-md-6 col-lg-8 mt-5 div_form_address\">
                <form role=\"form\" id=\"form_address\">
                    <div class=\"card bg-white border animated fadeIn z-depth-2\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-2\">
                                Dirección de envío <small class=\"text-muted\">(exclusivo para México)</small>
                            </h4>
                            <div class=\"row\">
                                <div class=\"col-sm-3 col-md-12 col-lg-2\">
                                    <small class=\"form-text text-muted mb-2\">Código postal</small>
                                    <input type=\"text\" name=\"p_code\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-sm-9 col-md-12 col-lg-5\">
                                    <small class=\"form-text text-muted mb-2\">Estado</small>
                                    <select class=\"selectpicker\" name=\"state\" title=\"Selecciona\" data-live-search=\"true\" data-live-search-placeholder=\"Buscar ...\">
                                        <option value=\"\">Alerts</option>
                                        <option value=\"\">Badges</option>
                                        <option value=\"\">Buttons</option>
                                        <option value=\"\">Cards</option>
                                        <option value=\"\">Forms</option>
                                        <option value=\"\">Modals</option>
                                    </select>
                                </div>
                                <div class=\"col-sm-12 col-md-12 col-lg-5\">
                                    <small class=\"form-text text-muted mb-2\">Delegación o municipio</small>
                                    <input type=\"text\" name=\"municipality\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-sm-12 col-lg-5\">
                                    <small class=\"form-text text-muted mb-2\">Colonia</small>
                                    <input type=\"text\" name=\"colony\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-12 col-sm-9 col-md-12 col-lg-5\">
                                    <small class=\"form-text text-muted mb-2\">Calle</small>
                                    <input type=\"text\" name=\"street\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-12 col-sm-3 col-md-12 col-lg-2\">
                                    <small class=\"form-text text-muted mb-2\">#int - #ext</small>
                                    <input type=\"text\" name=\"numbers\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-2\">Entre que calles</small>
                                    <textarea class=\"form-control form-control-sm textarea-autosize\" name=\"b_streets\" rows=\"1\"></textarea>
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-2\">Referencias</small>
                                    <textarea class=\"form-control form-control-sm textarea-autosize\" name=\"references\" rows=\"1\"></textarea>
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
        <div>
    </main>
    
";
    }

    // line 168
    public function block_appFooter($context, array $blocks = array())
    {
        // line 169
        echo "    <script src=\"assets/plugins/bootstrap-select/js/bootstrap-select.min.js\"></script>
\t<script src=\"assets/plugins/textarea-autosize/textarea-autosize.min.js\"></script>
    <script>
        \$(document).ready(function() {
            \$('.selectpicker')[0] && \$('.selectpicker').selectpicker();
        })
    </script>
";
    }

    public function getTemplateName()
    {
        return "cuenta/cuenta.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  232 => 169,  229 => 168,  145 => 87,  133 => 78,  121 => 69,  109 => 60,  87 => 41,  83 => 40,  79 => 39,  75 => 38,  71 => 37,  62 => 30,  59 => 29,  33 => 5,  30 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}

{% block appHeader %}
{# Estilos personalizados para la página #}
<style>
.nav-scroller {
    position: relative;
    z-index: 2;
    overflow-y: hidden;
}

.nav-scroller .nav {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    margin-top: -1px;
    overflow-x: auto;
    color: rgba(255, 255, 255, .75);
    text-align: center;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}
</style>

{% endblock %}

{% block appBody %}


    <div class=\"container-fluid mt-0 z-depth-1\">
        <div class=\"container animated fadeIn\">
        
            <div class=\"nav-scroller py-1 mb-2\">
                <nav class=\"nav d-flex justify-content-between\">
                    <a class=\"p-2 link_datos\" href=\"{{config.build.url}}cuenta/mis-datos\"><i class=\"fas fa-user-cog fa-lg mr-1\"></i> Mis datos</a>
                    <a class=\"p-2 link_deseos\" href=\"{{config.build.url}}cuenta/lista-deseos\"><i class=\"fas fa-heart fa-lg mr-1\"></i> Mi lista de deseos</a>
                    <a class=\"p-2 link_libros\" href=\"{{config.build.url}}cuenta/mis-libros\"><i class=\"fas fa-book-open fa-lg mr-1\"></i> Mis libros</a>
                    <a class=\"p-2 link_cambiar_clave\" href=\"{{config.build.url}}cuenta/cambiar-contrasena\"><i class=\"fas fa-user-lock fa-lg mr-1\"></i> Cambiar contraseña</a>
                    <a class=\"p-2 link_eliminar\" href=\"{{config.build.url}}cuenta/eliminar-cuenta\"><i class=\"fas fa-user-times fa-lg mr-1\"></i> Eliminar cuenta</a>
                </nav>
            </div>

        </div>
    </div>

    <main class=\"container\">
        <div class=\"row div_datos\">
            <div class=\"col-12 col-md-6 col-lg-4 mt-5 div_form_data\">
                <form role=\"form\" id=\"form_data\">
                    <div class=\"card bg-white border animated fadeIn z-depth-2\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-3\">
                                Datos personales
                            </h4>
                            <div class=\"form-group disabled\">
                                <small class=\"form-text text-muted mb-2\">Correo electrónico</small>
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"email\" iname=\"d_mail\" class=\"form-control form-control-sm\" value=\"{{owner_user.correoElectronico}}\" disabled>
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <small class=\"form-text text-muted mb-2\">Nombre completo</small>
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"text\" name=\"d_name\" class=\"form-control form-control-sm\" value=\"{{owner_user.cliente}}\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-user\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <small class=\"form-text text-muted mb-2\">RFC</small>
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"text\" name=\"d_rfc\" class=\"form-control form-control-sm\" value=\"{{owner_user.rfc}}\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-address-card\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group mb-0\">
                                <small class=\"form-text text-muted mb-2\">Teléfono</small>
                                <div class=\"input-group input-group-transparent\">
                                    <input type=\"text\" name=\"d_phone\" class=\"form-control form-control-sm\" value=\"{{owner_user.telefono}}\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-phone\"></i></span>
                                    </div>
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
            <div class=\"col-12 col-md-6 col-lg-8 mt-5 div_form_address\">
                <form role=\"form\" id=\"form_address\">
                    <div class=\"card bg-white border animated fadeIn z-depth-2\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-2\">
                                Dirección de envío <small class=\"text-muted\">(exclusivo para México)</small>
                            </h4>
                            <div class=\"row\">
                                <div class=\"col-sm-3 col-md-12 col-lg-2\">
                                    <small class=\"form-text text-muted mb-2\">Código postal</small>
                                    <input type=\"text\" name=\"p_code\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-sm-9 col-md-12 col-lg-5\">
                                    <small class=\"form-text text-muted mb-2\">Estado</small>
                                    <select class=\"selectpicker\" name=\"state\" title=\"Selecciona\" data-live-search=\"true\" data-live-search-placeholder=\"Buscar ...\">
                                        <option value=\"\">Alerts</option>
                                        <option value=\"\">Badges</option>
                                        <option value=\"\">Buttons</option>
                                        <option value=\"\">Cards</option>
                                        <option value=\"\">Forms</option>
                                        <option value=\"\">Modals</option>
                                    </select>
                                </div>
                                <div class=\"col-sm-12 col-md-12 col-lg-5\">
                                    <small class=\"form-text text-muted mb-2\">Delegación o municipio</small>
                                    <input type=\"text\" name=\"municipality\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-sm-12 col-lg-5\">
                                    <small class=\"form-text text-muted mb-2\">Colonia</small>
                                    <input type=\"text\" name=\"colony\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-12 col-sm-9 col-md-12 col-lg-5\">
                                    <small class=\"form-text text-muted mb-2\">Calle</small>
                                    <input type=\"text\" name=\"street\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-12 col-sm-3 col-md-12 col-lg-2\">
                                    <small class=\"form-text text-muted mb-2\">#int - #ext</small>
                                    <input type=\"text\" name=\"numbers\" class=\"form-control form-control-sm\">
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-2\">Entre que calles</small>
                                    <textarea class=\"form-control form-control-sm textarea-autosize\" name=\"b_streets\" rows=\"1\"></textarea>
                                </div>
                                <div class=\"col-12\">
                                    <small class=\"form-text text-muted mb-2\">Referencias</small>
                                    <textarea class=\"form-control form-control-sm textarea-autosize\" name=\"references\" rows=\"1\"></textarea>
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
        <div>
    </main>
    
{% endblock %}

{% block appFooter %}
    <script src=\"assets/plugins/bootstrap-select/js/bootstrap-select.min.js\"></script>
\t<script src=\"assets/plugins/textarea-autosize/textarea-autosize.min.js\"></script>
    <script>
        \$(document).ready(function() {
            \$('.selectpicker')[0] && \$('.selectpicker').selectpicker();
        })
    </script>
{% endblock %}", "cuenta/cuenta.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\cuenta\\cuenta.twig");
    }
}
